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

		$users = $this->User->find('all');

		$this->set('user', $users);
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
	
		$userId_1 = $this->request->data['userId'];
		$userId_2 = AuthComponent::user('id');
	
		$conversation = $this->Conversation->find('first', [
			'conditions' => [
				'OR' => [
					['userId_1' => $userId_1, 'userId_2' => $userId_2],
					['userId_1' => $userId_2, 'userId_2' => $userId_1]
				]
			],
			'contain' => ['User'],
			'fields' => [
				'Conversation.id',
				'Conversation.createdAt',
				'User1.id',
				'User1.imageLink',
				'User2.id',
				'User2.imageLink',
			]
		]);
	
		if ($conversation) {
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
	
			$conversation['Messages'] = $messages;
			if($messages){
				$this->response->statusCode(200);
				$this->response->body(json_encode(['conversation' => $conversation]));
			}else{
				$this->response->statusCode(404);
				$this->response->body(json_encode(['error' => 'Conversation not found']));
			}
			
		} else {
			$newConversation = $this->Conversation->create();
			$newConversation['Conversation']['userId_1'] = $userId_2;
			$newConversation['Conversation']['userId_2'] = $userId_1;
	
			if ($this->Conversation->save($newConversation)) {
				// Return the newly created conversation data
				$this->response->statusCode(201);
				$this->response->body(json_encode(['conversationCreated' => true, 'conversation' => $newConversation]));
			} else {
				$this->response->statusCode(500);
				$this->response->body(json_encode(['error' => 'Failed to create conversation']));
			}
		}
		return $this->response;
	}
}



