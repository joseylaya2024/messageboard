<div class="conversations form">
<?php echo $this->Form->create('Conversation'); ?>
	<fieldset>
		<legend><?php echo __('Add Conversation'); ?></legend>
	<?php
		echo $this->Form->input('conversationId');
		echo $this->Form->input('subject');
		echo $this->Form->input('createdAt');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Conversations'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Conversations'), array('controller' => 'conversations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conversation'), array('controller' => 'conversations', 'action' => 'add')); ?> </li>
	</ul>
</div>
