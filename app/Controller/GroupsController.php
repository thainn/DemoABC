<?php


/**
 * Description of GroupsController
 *
 * @author thainn
 */
class GroupsController extends AppController {

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('*'));
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Group->create();
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
	}
	
	function index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}

	function view($id=null) {

	}

	function edit($id=null) {

	}

	function delete($id=null) {

	}
}
