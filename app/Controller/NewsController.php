<?php

class NewsController extends AppController {

     
    var $uses = array('News');

	function index()
	{
            
             $this->paginate = array(
            'limit' => '5',
            'conditions' => array('status' => '1')
            
        );
        $data = $this->paginate("News");
        $this->set("news", $data);
            
            
	}

	function view($id)
	{
		$news = $this->News->read(null, $id);
		$this->set('news', $news);
                
	}

	function edit($a = null)
	{
           
		if(empty($this->data))   
		{
			if($a)
			{
				$news = $this->News->read(null, $a);
                                
				$this->data = $news; // sinh ra code tu dong cake php
				$this->set('news', $news); // test
				
			}
		}
		else
		{
			if($this->News->save($this->data))
			{
				$this->redirect('index');
			}
		}
	}
	
	public function add() {
		if ($this->request->is('post')) {
			$this->News->create();
			if ($this->News->save($this->request->data)) {
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
                 
             $sql = 'UPDATE News SET status = -1 WHERE id =' . $id;
            $this->News->query($sql);
            $this->Session->setFlash('The News with id: ' . $id . ' has been deleted.');
		$this->redirect(array('action' => 'index'));
		
             }
	}
}