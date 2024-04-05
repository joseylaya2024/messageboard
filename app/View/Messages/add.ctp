<div class="messages form">
<?php echo $this->Form->create('Message'); ?>
	<fieldset>
		<legend><?php echo __('Add Message'); ?></legend>
	<?php
		echo $this->Form->input('messageId');
		echo $this->Form->input('conversationId');
		echo $this->Form->input('senderId');
		echo $this->Form->input('recipientId');
		echo $this->Form->input('messageContent');
		echo $this->Form->input('createdAt');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Messages'), array('action' => 'index')); ?></li>
	</ul>
</div>
