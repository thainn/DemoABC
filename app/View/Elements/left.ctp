
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List User', true), array('controller' => 'users', 'action' => 'index'));  ?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Posts', true), array('controller' => 'posts', 'action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('List Article', true), array('controller' => 'news', 'action' => 'index')); ?> </li>
		
	</ul>
</div>