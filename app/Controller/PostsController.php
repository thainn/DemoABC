<?php

class PostsController extends AppController {

	 
	var $uses = array('Post');

	function index() {
		$this->paginate = array(
				'limit' => '5',
		);
		$data = $this->paginate("Post");
		$this->set("posts",  $data);


	}

	function view($id) {
		$news = $this->News->read(null, $id);
		$this->set('news', $news);

	}

	function edit($a = null) {
		if(empty($this->data)) {
			if($a) {
				$news = $this->Post->read(null, $a);

				$this->data = $news; // sinh ra code tu dong cake php
				$this->set('news', $news); // test
			}
		}
		else {
			if($this->Post->save($this->data)) {
				$this->redirect('index');
			}
		}
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Post->create();
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash('Your post has been saved.');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Unable to add your post.');
			}
		}
	}

}