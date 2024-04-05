<div class="messages view">
<h2><?php echo __('Message'); ?></h2>
	<dl>
		<dt><?php echo __('MessageId'); ?></dt>
		<dd>
			<?php echo h($message['Message']['messageId']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('ConversationId'); ?></dt>
		<dd>
			<?php echo h($message['Message']['conversationId']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('SenderId'); ?></dt>
		<dd>
			<?php echo h($message['Message']['senderId']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('RecipientId'); ?></dt>
		<dd>
			<?php echo h($message['Message']['recipientId']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('MessageContent'); ?></dt>
		<dd>
			<?php echo h($message['Message']['messageContent']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('CreatedAt'); ?></dt>
		<dd>
			<?php echo h($message['Message']['createdAt']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Message'), array('action' => 'edit', $message['Message']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Message'), array('action' => 'delete', $message['Message']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $message['Message']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Messages'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Message'), array('action' => 'add')); ?> </li>
	</ul>
</div>
