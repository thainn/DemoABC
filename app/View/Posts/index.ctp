

<?php

echo $this->element('left');

?>
<div class="posts index">
	<h2><?php echo __('posts'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('content');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php echo ('Actions');?></th>
		</tr>
		<?php
		$i = 0;
		foreach ($posts as $post):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr <?php echo $class;?>>
			<td><?php echo $post['Post']['id']; ?>&nbsp;</td>

			<td><?php echo $post['Post']['title']; ?>&nbsp;</td>
			<td><?php echo $post['Post']['content']; ?>&nbsp;</td>
			<td><?php echo $post['Post']['created']; ?>&nbsp;</td>
			<?php 
			$username='2';
			if($post['Post']['user_id']==$username || $username=='1' )  //username ==1 la admin
			{

				?>
			<td class="actions"><?php echo $this->Html->link(__('View', true), array('action' => 'view', $post['Post']['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $post['Post']['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $post['Post']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['Post']['id'])); ?>
			</td>
			<?php } ?>
		</tr>
		<?php endforeach; ?>
	</table>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
		|
		<?php echo $this->Paginator->numbers();?>
		|
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
