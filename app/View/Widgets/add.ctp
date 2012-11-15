
<div class="posts form">
<h1>Add News</h1>


<?php
$username='5';
    echo $this->Form->create('News', array('action' => 'edit')); 
    echo $this->Form->input('title',array('value' =>""));
    echo $this->Form->input('content', array('rows' => '3'));
    echo $this->Form->input('id', array('type' => 'hidden'));
     echo $this->Form->input('user_id', array('type' => 'hidden','value' =>$username));
      echo $this->Form->input('status', array('type' => 'hidden','value' =>'0'));
    echo $this->Form->end('Save Articles');
  
 ?>
  </div>


<?php
 
 echo $this->element('left');

?>


