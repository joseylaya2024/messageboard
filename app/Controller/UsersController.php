<?php
App::uses('AppController', 'Controller');


use Cake\Utility\Text;

/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'Flash');

	public function beforeFilter()
	{
		$this->Auth->allow('add');
	}


	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
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
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}


	public function viewProfile()
	{
		$id = AuthComponent::user('id');
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$userData = $this->User->findById($id);
		if (!$userData) {
			throw new NotFoundException(__('User not found'));
		}
		$this->set('user', $userData);
	}


	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			if ($this->request->data['User']['password'] !== $this->request->data['User']['confirm_password']) {
				$this->Flash->error(__('Passwords do not match.'));
				return;
			}
			
			$this->User->create();
			$this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
			if ($this->User->save($this->request->data)) {
				// Log in the user after registration
				$user = $this->User->findByUsername($this->request->data['User']['username']); // Assuming 'username' is the unique identifier
				if ($user) {
					$this->Auth->login($user['User']);
				}
	
				$this->Flash->success(__('Welcome! You have been registered and logged in.'));
				return $this->redirect(array('controller' => 'Pages', 'action' => 'welcome')); // Redirect to homepage
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
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
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
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
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete($id)) {
			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	// auth method

	public function getUsernameById($id)
	{
		$data = $this->User->findById($id);
		if ($data) {
			return $data['User']['name'];
		} else {
			return null;
		}
	}

	public function login()
	{
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirectUrl());

			} else {
				$this->Flash->error('Invalid Username or Password');
			}
		}
	}

	public function logout()
	{
		$this->Auth->logout();
		return $this->redirect(array('controller' => 'users', 'action' => 'login'));
	}

	public function uploadImage()
	{
		$this->autoRender = false;
		$path = '/messageboard/img/uploads/';
		$uploadDir = create_date_folder(WWW_ROOT . 'img/uploads/');
		$image = $this->request->data['User']['image'];

		$filename = uniqid() . '-' . str_replace(' ', '_', $image['name']); 
		
		$imageLinkPath = implode('/', array_slice(explode('/', $uploadDir['day']), 5));

		print_r($imageLinkPath);
		$filepath = $uploadDir['day'];
		if (move_uploaded_file($image['tmp_name'], $filepath . $filename)) {
			$userId = $this->request->data['userId'];
			$user = $this->User->findById($userId);
			$user['User']['imageLink'] = $imageLinkPath . $filename; 
			$this->User->save($user);

			$this->response->type('json');
			$this->response->body(json_encode(['filename' => $filename, 'imageLink' => $path . $filename]));
		} else {
			$this->response->statusCode(500);
			$this->response->body('Failed to upload image.');
		}

		return $this->response;

	}

}
