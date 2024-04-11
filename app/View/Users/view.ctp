<div class="users view">
<h2><?php echo __('User'); ?></h2>
	<dl>
		
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($user['User']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($user['User']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</dd>
       
      
		<dt><?php echo __('Joined'); ?></dt>
		<dd>
            <?php $createdAt = $user['User']['createdAt'];
                    $formattedCreatedAt = date('M d, Y', strtotime($createdAt));
            ?>
			<?php echo $formattedCreatedAt; ?>
			&nbsp;
		</dd>

	</dl>
</div>
<div class="actions">
    <div class="avatar-container">
    <img src="http://localhost/<?php echo $user['User']['imageLink'] ?>" alt="Avatar" class="avatar"> 
    </div>
</div>