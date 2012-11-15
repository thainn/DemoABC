

<?php

echo $this->element('left');

?>
<div class="posts index">
	<h2><?php echo __('widgets'); ?></h2>
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
		foreach ($widgets as $Widget):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		?>
		<tr <?php echo $class;?>>
			<td><?php echo $Widget['Widget']['id']; ?>&nbsp;</td>

			<td><?php echo $Widget['Widget']['title']; ?>&nbsp;</td>
			<td><?php echo $Widget['Widget']['content']; ?>&nbsp;</td>
			<td><?php echo $Widget['Widget']['created']; ?>&nbsp;</td>
			<?php 
			$username='2';
			if($Widget['Widget']['user_id']==$username || $username=='1' )  //username ==1 la admin
			{

				?>
			<td class="actions"><?php echo $this->Html->link(__('View', true), array('action' => 'view', $Widget['Widget']['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $Widget['Widget']['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $Widget['Widget']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $Widget['Widget']['id'])); ?>
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
