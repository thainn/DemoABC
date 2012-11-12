
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
               <li><?php echo $this->Html->link(__('add News', true), array('controller' => 'news', 'action' => 'add'));  ?></li>
               <li><?php echo $this->Html->link(__('add Recruits', true), array('controller' => 'recruits', 'action' => 'add'));  ?></li>
		<li><?php echo $this->Html->link(__('List User', true), array('controller' => 'users', 'action' => 'index'));  ?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List News', true), array('controller' => 'news', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('List Recruits', true), array('controller' => 'recruits', 'action' => 'index')); ?> </li>
		
	</ul>
</div>