

<?php
 
 echo $this->element('left');

?>
<div class="posts index">
	<h2><?php Articles ;?></h2>
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
	foreach ($news as $news):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $news['News']['id']; ?>&nbsp;</td>
		
		<td><?php echo $news['News']['title']; ?>&nbsp;</td>
		<td><?php echo $news['News']['content']; ?>&nbsp;</td>
                <td><?php echo $news['News']['created']; ?>&nbsp;</td>
                <?php 
                $username='2';
                        if($news['News']['user_id']==$username || $username=='1' )  //username ==1 la admin
                        {
                            
                  ?>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $news['News']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $news['News']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $news['News']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $news['News']['id'])); ?>
		</td>
                <?php } ?>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
