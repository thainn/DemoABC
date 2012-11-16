<?php


class WidgetsController extends AppController {

	 
	var $uses = array('Widget');

	function index() {
		$this->paginate = array(
				'limit' => '5',
		);
		
		$data = $this->paginate("Widget");
		$this->set("widgets", $data);
	}

	function view($id) {
		$rercuit = $this->Recruit->read(null, $id);
		$this->set('rercuit', $rercuit);
	}

	function edit($a = null)
	{
		 
		if(empty($this->data))
		{
			if($a)
			{
				$rercuit = $this->Recruit->read(null, $a);

				$this->data = $rercuit; // sinh ra code tu dong cake php
				$this->set('rercuit', $rercuit); // test

			}
		}
		else
		{
			if($this->Recruit->save($this->data))
			{
				$this->redirect('index');
			}
		}
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Recruit->create();
			if ($this->Recruit->save($this->request->data)) {
				$this->Session->setFlash('Your post has been saved.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your post.');
			}
		}
	}

	function delete($id)
	{
		if ($id != null) {
			 
			$sql = 'UPDATE recruits SET status = -1 WHERE id =' . $id;
			// var_dump($sql);
			// return;
			$this->Recruit->query($sql);
			$this->Session->setFlash('The Recruit with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));

		}
	}

}