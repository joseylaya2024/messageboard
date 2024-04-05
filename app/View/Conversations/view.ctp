<!-- <div class="conversations view">
<h2><?php echo __('Conversation'); ?></h2>
	<dl>
		<dt><?php echo __('ConversationId'); ?></dt>
		<dd>
			<?php echo h($conversation['Conversation']['conversationId']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subject'); ?></dt>
		<dd>
			<?php echo h($conversation['Conversation']['subject']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('CreatedAt'); ?></dt>
		<dd>
			<?php echo h($conversation['Conversation']['createdAt']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Conversation'), array('action' => 'edit', $conversation['Conversation']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Conversation'), array('action' => 'delete', $conversation['Conversation']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $conversation['Conversation']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Conversations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conversation'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Conversations'), array('controller' => 'conversations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conversation'), array('controller' => 'conversations', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Conversations'); ?></h3>
	<?php if (!empty($conversation['Conversation'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('ConversationId'); ?></th>
		<th><?php echo __('Subject'); ?></th>
		<th><?php echo __('CreatedAt'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($conversation['Conversation'] as $conversation): ?>
		<tr>
			<td><?php echo $conversation['conversationId']; ?></td>
			<td><?php echo $conversation['subject']; ?></td>
			<td><?php echo $conversation['createdAt']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'conversations', 'action' => 'view', $conversation['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'conversations', 'action' => 'edit', $conversation['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'conversations', 'action' => 'delete', $conversation['id']), array('confirm' => __('Are you sure you want to delete # %s?', $conversation['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Conversation'), array('controller' => 'conversations', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div> -->
