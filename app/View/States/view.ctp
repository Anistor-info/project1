<div class="states view">
<h2><?php  echo __('State');?></h2>
	<dl>
		<dt><?php echo __('State Id'); ?></dt>
		<dd>
			<?php echo h($state['State']['state_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Country'); ?></dt>
		<dd>
			<?php echo $this->Html->link($state['Country']['country_id'], array('controller' => 'countries', 'action' => 'view', $state['Country']['country_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State Name'); ?></dt>
		<dd>
			<?php echo h($state['State']['state_name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit State'), array('action' => 'edit', $state['State']['state_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete State'), array('action' => 'delete', $state['State']['state_id']), null, __('Are you sure you want to delete # %s?', $state['State']['state_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List States'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New State'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Countries'), array('controller' => 'countries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Country'), array('controller' => 'countries', 'action' => 'add')); ?> </li>
	</ul>
</div>
