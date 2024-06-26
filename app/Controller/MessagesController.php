<?php
App::uses('AppController', 'Controller');
/**
 * Messages Controller
 *
 * @property Message $Message
 * @property PaginatorComponent $Paginator
 */
class MessagesController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'Flash');

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->Message->recursive = 0;
		$this->set('messages', $this->Paginator->paginate());
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
		if (!$this->Message->exists($id)) {
			throw new NotFoundException(__('Invalid message'));
		}
		$options = array('conditions' => array('Message.' . $this->Message->primaryKey => $id));
		$this->set('message', $this->Message->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */

	public function add()
	{
		if ($this->request->is('post')) {
			$senderId = AuthComponent::user('id');
			$convoId = $this->request->data['conv-id'];
			$recipientId = $this->request->data['recipient-id'];
			$messageContent = $this->request->data['Message']['message'];

			$messageData = array(
				'Message' => array(
					'senderId' => $senderId,
					'conversationId' => $convoId,
					'recipientId' => $recipientId,
					'messageContent' => $messageContent
				)
			);

			$this->Message->create();
			if ($this->Message->save($messageData)) {
				$response = array(
					'status' => true,
					'conversationCreated' => false,
					'message' => $messageData['Message'],
					'conversationId' => $convoId
				);
				$this->response->statusCode(200);
				$this->response->body(json_encode($response));
			} else {
				$this->response->statusCode(500);
				$this->response->body(json_encode(['status' => false]));
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
		if (!$this->Message->exists($id)) {
			throw new NotFoundException(__('Invalid message'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Message->save($this->request->data)) {
				$this->Flash->success(__('The message has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The message could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Message.' . $this->Message->primaryKey => $id));
			$this->request->data = $this->Message->find('first', $options);
		}
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
		$this->autoRender = false;
		$this->response->type('json');

		if (!$this->request->is('ajax')) {
			throw new MethodNotAllowedException();
		}

		if (!$this->Message->exists($id)) {
			$this->response->statusCode(404);
			echo json_encode(['success' => false, 'message' => 'Invalid conversation']);
			return;
		}

		if ($this->Message->delete($id)) {
			echo json_encode(['success' => true, 'message' => 'The conversation has been deleted.']);
		} else {
			$this->response->statusCode(500);
			echo json_encode(['success' => false, 'message' => 'The conversation could not be deleted.']);
		}
	}

}
