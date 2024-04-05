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
		if ($conversation['User1']['id'] !== AuthComponent::user('id')) {
			$temp = $conversation['User1'];
			$conversation['User1'] = $conversation['User2'];
			$conversation['User2'] = $temp;
		}

	

			$this->loadModel('Message');
			
			$messages = $this->Message->find('all', [
				'conditions' => [
					'Message.conversationId' => $conversation['Conversation']['id']
				],
				'contain' => ['User'],
				'fields' => [
					'Message.senderId',
					'Message.recipientId',
					'Message.messageContent',
				]
			]);


			if ($messages) {
				$conversation['Messages'] = $messages;

				$this->response->statusCode(200);
				$this->response->body(json_encode(['status' => true,  'conversationCreated' => false, 'messages' => $conversation]));
			} else {
				$this->response->statusCode(200);
				$this->response->body(json_encode(['status' => false,  'conversationCreated' => false, 'messages' => $conversation]));
			}

		} else {
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
		
					$this->response->statusCode(200);
					$this->response->body(json_encode(['status' => false, 'conversationCreated' => true, 'messages' => $conversationData]));
				} else {
					// Handle if user data couldn't be fetched
					$this->response->statusCode(500);
					$this->response->body(json_encode(['error' => 'Failed to fetch user data']));
				}
			} else {
				$this->response->statusCode(500);
				$this->response->body(json_encode(['error' => 'Failed to create conversation']));
			}
		}
		return $this->response;
	}
}



