<?php
App::uses('AppController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('MessagesController', 'Controller');
/**
 * Conversations Controller
 *
 * @property Conversation $Conversation
 * @property PaginatorComponent $Paginator
 */
class ConversationsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator');

	/**
	 * index method
	 *
	 * @return void
	 */

	 public function index()
	 {
		 $this->loadModel('User');
	 
		 $currentUserId = AuthComponent::user('id');
	 
		 $users = $this->User->find('all', [
			 'conditions' => [
				 'User.id !=' => $currentUserId
			 ]
		 ]);
	 
		 $this->set('users', $users); 
	 }
	 
	 

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		if (!$this->Conversation->exists($id)) {
			throw new NotFoundException(__('Invalid conversation'));
		}
		$options = array('conditions' => array('Conversation.' . $this->Conversation->primaryKey => $id));
		$this->set('conversation', $this->Conversation->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			$this->Conversation->create();
			if ($this->Conversation->save($this->request->data)) {
				$this->Flash->success(__('The conversation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The conversation could not be saved. Please, try again.'));
			}
		}
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null)
	{
		if (!$this->Conversation->exists($id)) {
			throw new NotFoundException(__('Invalid conversation'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Conversation->save($this->request->data)) {
				$this->Flash->success(__('The conversation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The conversation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Conversation.' . $this->Conversation->primaryKey => $id));
			$this->request->data = $this->Conversation->find('first', $options);
		}
		$conversations = $this->Conversation->Conversation->find('list');
		$startedByUsers = $this->Conversation->StartedByUser->find('list');
		$this->set(compact('conversations', 'startedByUsers'));
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null)
	{
		if (!$this->Conversation->exists($id)) {
			throw new NotFoundException(__('Invalid conversation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Conversation->delete($id)) {
			$this->Flash->success(__('The conversation has been deleted.'));
		} else {
			$this->Flash->error(__('The conversation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));

	}
	public function getConversationById()
	{
		$this->autoRender = false;
	
		$userId_1 = AuthComponent::user('id');
		$userId_2 = $this->request->data['userId'];
	
		$conversation = $this->Conversation->find('first', [
			'conditions' => [
				'OR' => [
					['userId_1' => $userId_1, 'userId_2' => $userId_2],
					['userId_1' => $userId_2, 'userId_2' => $userId_1],
				]
			],
			'contain' => ['User'],
			'fields' => [
				'Conversation.id',
				'Conversation.createdAt',
				'User1.id',
				'User1.name',
				'User1.imageLink',
				'User2.id',
				'User2.name',
				'User2.imageLink',
			]
		]);
	
		if ($conversation) {
			if ($conversation['User1']['id'] !== $userId_1) {
				// Swap users
				[$conversation['User1'], $conversation['User2']] = [$conversation['User2'], $conversation['User1']];
			}
	
			$this->loadModel('Message');

			$offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
		
			$paginationLimit = 5; 

			$messages = $this->Message->find('all', [
				'conditions' => [
					'Message.conversationId' => $conversation['Conversation']['id']
				],
				'contain' => ['User'],
				'fields' => [
					'Message.senderId',
					'Message.recipientId',
					'Message.messageContent',
				],
				'limit' => $paginationLimit,
				'offset' => $offset,
				'order' => ['Message.id' => 'DESC']
			]);
			$messages = array_reverse($messages);
			$response = [
				'status' => true,
				'conversationCreated' => false,
				'messages' => $conversation
			];

			if ($messages) {
				$response['messages']['Messages'] = $messages;
			}
	
			$statusCode = 200;
		} else {
			// Create new conversation
			$newConversation = $this->Conversation->create();
			$newConversation['Conversation']['userId_1'] = $userId_2;
			$newConversation['Conversation']['userId_2'] = $userId_1;
	
			if ($this->Conversation->save($newConversation)) {
				$conversationId = $this->Conversation->getLastInsertID();
				$this->loadModel('User');
				$user1 = $this->User->findById($userId_1);
				$user2 = $this->User->findById($userId_2);
	
				if ($user1 && $user2) {
					$conversationData = $newConversation;
					$conversationData['Conversation']['id'] = $conversationId;
					$conversationData['User1'] = $user1['User'];
					$conversationData['User2'] = $user2['User'];
	
					$response = [
						'status' => true,
						'conversationCreated' => true,
						'messages' => $conversationData
					];
	
					$statusCode = 200;
				} else {
					$response = ['error' => 'Failed to fetch user data'];
					$statusCode = 500;
				}
			} else {
				$response = ['error' => 'Failed to create conversation'];
				$statusCode = 500;
			}
		}

		$this->response->statusCode($statusCode);
		$this->response->body(json_encode($response));
		return $this->response;
	}	
}



