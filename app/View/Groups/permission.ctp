<?php echo $this->element('left'); ?>
<?php
    echo $this->Html->script('jquery.js');
    echo $this->Html->script('js.js');
?>

<div class="groups index">
	<h2>Permission</h2>

	<div id="container">
	
	 <?php echo $this->Form->create('groups', array('action' => 'permission')); ?>
			<div class='header'>
				<div class='name'><?php echo __('Permission'); ?></div>
				<div class='grouplist'>
					<?php foreach($groups as $group){ ?>
						<span class='groupitem'><?php echo $group['Group']['name']; ?></span>
					<?php } ?>
				</div>
				<div class='clear'></div>
			</div>
			
			<div id="rolelist">
				<?php
					foreach( $rules as $rule ){
						$rule_id = $rule['Aco']['id'];
						$rule_name = $rule['Aco']['alias'];
						$rule_parent_id = $rule['Aco']['parent_id'];
						if(!$rule_parent_id) $rule_parent_id = 0;
						
				?>		
					<div class='ruleitem ruleitem<?php echo $rule_parent_id; ?>'>
						<div class='name'><?php echo __($rule['Aco']['alias']); ?></div>
						<div class='grouplist'>
							<?php 
								foreach($groups as $group){
									$group_id = $group['Group']['id'];
									$acos_id = $aros[$group_id]['Aro']['id'];
									
									$checked = '';
									if( @$permission[$rule_id][$group_id] ){
										$checked = 'checked';
									}
							?>
								<span class='groupitem'>
									<input name="<?php echo "permission[{$acos_id}][{$rule_id}]"; ?>" id='<?php echo 'role-'.$rule_id.'-'.$group_id; ?>' ref='<?php echo 'rolegroup-'.$rule_parent_id; ?>' class='group-<?php echo $group_id; ?>' type='checkbox' <?php echo $checked; ?> value='<?php echo $rule_id; ?>' />
								</span>
							<?php } ?>
						</div>
						<div class='clear'></div>
					</div>	
				<?php } ?>
			</div>
			
			<div class="actions">
			    <?php echo $this->Form->end(__('Save Permission')); ?>
			</div>
		</form>
	</div>
</div>