<div class="conversations index">
	<h2>
		<?php echo __('Conversations'); ?>
	</h2>
	<div class="chat-container" id="message-container">
		<div class="chat-header">
		</div>
		<div class="center">
			<a href="#" id="olderMessages" data-recipient-id = "null" class="chat-pagination">show older messages</a>
		</div>

		<div class="chat-messages">
			...
		</div>
		<div class="chat-input-container">
			<?php
			echo $this->Form->create(
				'Message',
				array(
					'id' => 'convFormId',
					'data-id' => 'convFormId',
				)
			);
			echo $this->Form->hidden(
				'recipientIid',
				array(
					'id' => 'recipientIid'
				)
			);
			echo $this->Form->input('message', array('class' => 'chat-input', 'placeholder' => 'Type your message...'));
			echo $this->Form->end('Submit');
			?>
		</div>
	</div>
</div>

<div class="actions">
	<h2>
		<?php echo __('Inbox'); ?>
	</h2>
	<div class="contact-list">
		<?php
		if (isset($users)) {
			foreach ($users as $userData): ?>
				<a href="#" class="contact-link" data-user-id="<?php echo $userData['User']['id']; ?>">
					<div class="contact">
						<img src="http://localhost/<?php echo $userData['User']['imageLink'] ?>" alt="Profile Picture">
						<div class="contact-details">
							<span class="name">
								<?php echo $userData['User']['name']; ?>
							</span>
							<span class="email">
								<?php echo $userData['User']['email']; ?>
							</span>
						</div>
					</div>
				</a>
			<?php endforeach;
		} ?>
	</div>
</div>

<script>
	$(document).ready(function () {

		function messagesFunction(usr1, usr2, msgs, conv) {
			var chatMsgsContainer = $('.chat-messages');
			var chatMsgsHeaderContainer = $('.chat-header');


			chatMsgsHeaderContainer.empty();
			chatMsgsContainer.empty();

			var msgHeaderHtml = `
						<img src="http://localhost/${conv.User2.imageLink}" class="profile-picture" alt="Profile Picture">
							<div class="profile-info">
								<h2>${conv.User2.name}</h2>
								<p>online</p>
							</div>`;

			chatMsgsHeaderContainer.append(msgHeaderHtml);

			msgs ? msgs.forEach(function (msg) {
				var senderId = msg.Message.senderId;
				var msgContent = msg.Message.messageContent;
				var senderName = (senderId === usr1.id) ? usr1.name : usr2.name;
				var msgClass = (senderId === usr1.id) ? 'sender' : 'recipient';

				var imgSrc = (senderId === usr1.id) ? usr1.imageLink : usr2.imageLink;
				var imgHtml = `<img src="http://localhost/${imgSrc}" class="${msgClass}-picture" alt="Picture">`;

				var msgHtml = `
							<div class="message ${msgClass}-message">
								${msgClass === 'sender' ? '' : imgHtml}
								<div class="message-content-${msgClass}">
									<p>${msgContent}</p>
								</div>
								${msgClass === 'sender' ? imgHtml : ''}
							</div>
						`;
				chatMsgsContainer.append(msgHtml);
			}) : null;
		}

		function getMessages(userId, offset) {
			$.ajax({
				url: 'http://localhost/message-board/conversations/getConversationById',
				method: 'POST',
				data: { userId: userId, offset: offset },
				success: function (response) {
					var respJson = JSON.parse(response);
					var conv = respJson.messages;

					var conversationId = conv.Conversation.id;

					$('#recipientIid').attr('data-reciepent-id', respJson.messages.User2.id);

					$('#convFormId').attr('data-id', conversationId);

					if (respJson.status) {

						var usr1 = conv.User1;
						var usr2 = conv.User2;
						var msgs = conv.Messages;

						messagesFunction(usr1, usr2, msgs, conv);
					}
				},
				error: function (xhr, status, error) {
					console.error('AJAX request failed:', status, error);
				}
			});
		}

		var offset = 0;
		var paginationLimit = 5;
		var previousUserId = null; 

		$('#olderMessages').click(function (e) {
			e.preventDefault();

			var userId = $(this).data('recipient-id');
			console.log(userId);
			if (userId !== previousUserId) {
				offset = 0;
			}
			previousUserId = userId;

			offset += 5;

			getMessages(userId, offset);
		});

		$('.contact-link').click(function (e) {
			e.preventDefault();

			var userId = $(this).data('user-id');
			var chatMsgsContainer = $('.chat-messages');
			var chatMsgsHeaderContainer = $('.chat-header');

			$('#olderMessages').attr('data-recipient-id', userId);

			$.ajax({
				url: 'http://localhost/message-board/conversations/getConversationById',
				method: 'POST',
				data: { userId: userId },
				success: function (response) {
					var respJson = JSON.parse(response);
					var conv = respJson.messages;

					var conversationId = conv.Conversation.id;

					$('#recipientIid').attr('data-reciepent-id', respJson.messages.User2.id);
				
					$('#convFormId').attr('data-id', conversationId);

					if (respJson.status) {

						var usr1 = conv.User1;
						var usr2 = conv.User2;
						var msgs = conv.Messages;

						messagesFunction(usr1, usr2, msgs, conv);

					} else if (respJson.conversationCreated) {
						var msgHeaderHtml = `
							<img src="http://localhost/${respJson.messages.User2.imageLink}" class="profile-picture" alt="Profile Picture">
							<div class="profile-info">
								<h2>${respJson.messages.User2.name}</h2>
								<p>online</p>
							</div>`;

						chatMsgsHeaderContainer.append(msgHeaderHtml);
					} else {
						var msgHeaderHtml = `
							<img src="http://localhost/${conv.User2.imageLink}" class="profile-picture" alt="Profile Picture">
							<div class="profile-info">
								<h2>${conv.User2.name}</h2>
								<p>online</p>
							</div>`;

						chatMsgsHeaderContainer.append(msgHeaderHtml);
					}
				},
				error: function (xhr, status, error) {
					console.error('AJAX request failed:', status, error);
				}
			});
		});


		$('#convFormId').submit(function (e) {
			e.preventDefault();
			var formData = $(this).serialize();

			var convIdVal = $(this).data('id');
			var RecipientIdVal = $('#recipientIid').data('reciepent-id');

			formData += '&conv-id=' + encodeURIComponent(convIdVal);
			formData += '&recipient-id=' + encodeURIComponent(RecipientIdVal);

			$.ajax({
				url: 'http://localhost/message-board/messages/add',
				method: 'POST',
				data: formData,
				success: function (response) {
					var $contactLink = $('.contact-link[data-user-id="' + RecipientIdVal + '"]');
					$('.chat-input').val('');
					if ($contactLink.length > 0) {
						$contactLink.click();
					} else {
						console.log("Contact link not found for user ID: " + RecipientIdVal);
					}
				},
				error: function (xhr, status, error) {
					console.error(xhr.responseText);
				}
			});
		});
	});
</script>