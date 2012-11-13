<?php
 
 echo $this->element('left');

?>
<div class="posts form">
<h1>Edit Post</h1>


<?php
    echo $this->Form->create('News', array('action' => 'edit')); 
    echo $this->Form->input('title');
    echo $this->Form->input('content', array('rows' => '3'));
    echo $this->Form->input('id', array('type' => 'hidden'));
    echo $this->Form->end('Save Articles');
 ?>
   
  </div>

 <div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Article', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
