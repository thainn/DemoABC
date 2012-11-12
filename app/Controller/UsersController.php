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

	function index() {

	}

	function login() {

	}

	function logout() {

	}
	function view($id=null) {

	}

	function edit($id=null) {

	}

	function delete($id=null) {

	}
}