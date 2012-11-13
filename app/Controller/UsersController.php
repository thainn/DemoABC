<?php

class UsersController extends AppController {

	function beforeFilter() {
		parent::beforeFilter();
		// 		$this->Auth->allow(array('*'));
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
		//cho phép administrators truy cập mọi thứ
		$group->id = 1;
		$this->Acl->allow($group, 'controllers');

		//cho phép managers quản lý post, cấm truy xuất khu vực và action khác
		$group->id = 2;
		$this->Acl->deny($group, 'controllers');
		$this->Acl->allow($group, 'controllers/Posts');

		//cho phép users chỉ được add và edit post, cấm truy xuất các khu vực và action khác
		$group->id = 3;
		$this->Acl->deny($group, 'controllers');
		$this->Acl->allow($group, 'controllers/Posts/add');
		$this->Acl->allow($group, 'controllers/Posts/edit');
		$this->Acl->allow($group, 'controllers/Widgets/add');
		$this->Acl->allow($group, 'controllers/Widgets/edit');

		echo "all done";
		exit;
	}

	function index() {
		$this->set('users', $this->paginate()); // mac dinh la 20 items
	}

	function login() {
		if ($this->request->is('post')) {
		 	if ($this->Auth->login()) {
		 			
		 		$data['User'] = $this->Auth->user();
	
		 		//var_dump($data['User']['username']);
		 		$this->Session->write('username',$data['User']['username']);
		 		// return;
		 		$this->redirect($this->Auth->redirect());
		 	} else {
		 		$this->Session->setFlash('Your username or password was incorrect.');
		 	}
		}
	}

	function logout() {

	}
	function view($id=null) {

	}


	function delete($id=null) {

	}
}