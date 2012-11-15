<?php

class UsersController extends AppController {

	function beforeFilter() {
		parent::beforeFilter();
				$this->Auth->allow(array('*'));
	}

	function add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}


	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}

		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	function initDB() {
		
		$group =& $this->User->Group;
		
		//Allow admins to everything
		$group->id = 1;
		$this->Acl->allow($group, 'controllers');
		//allow managers to posts and widgets
// 		$group->id = 2;
// 		$this->Acl->deny($group, 'controllers');
// 		$this->Acl->allow($group, 'controllers/Posts');
// 		$this->Acl->allow($group, 'controllers/Widgets');
// 		//allow users to only add and edit on posts and widgets
// 		$group->id = 3;
// 		$this->Acl->deny($group, 'controllers');
// 		$this->Acl->allow($group, 'controllers/News/view');
// 		$this->Acl->allow($group, 'controllers/News/add');
// 		$this->Acl->allow($group, 'controllers/News/edit');
// 		$this->Acl->allow($group, 'controllers/Recruits/view');
// 		$this->Acl->allow($group, 'controllers/Recruits/edit');
// 		//we add an exit to avoid an ugly "missing views" error message
		echo "all done";
		exit;
	}
	

	
	public function login() {
		if ($this->Session->read('Auth.User')) {
			$this->Session->setFlash('You are logged in!');
			$this->redirect('/', null, false);
		}
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash('Your username or password was incorrect.');
			}
		}
		
	}
	
	public function logout() {
		$this->Session->setFlash('Good-Bye');
		$this->redirect($this->Auth->logout());
	}
	
	function index() {
		$this->set('users', $this->paginate()); // mac dinh la 20 items
	}
	
	
	function view($id=null) {

	}


	function delete($id=null) {

	}
}