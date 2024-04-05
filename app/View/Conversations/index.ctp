<div class="conversations index">
	<h2>
		<?php echo __('Conversations'); ?>
	</h2>
	<div class="chat-container" id="message-container">
		<div class="chat-messages">
			<div class="message recipient-message">
				<img src="http://localhost/message-board/app/webroot/img/uploads/2024/04/03/660d14dd3debe-5.jpg"
					class="recipient-picture" alt="Sender's Picture">
				<div class="message-content-recipient">
					<p>Hello! How can I assist you today?</p>
				</div>
			</div>

			<div class="message sender-message">
				<div class="message-content-sender">
					<p>Hello! How can I assist you today?</p>
				</div>
				<img src="http://localhost/message-board/app/webroot/img/uploads/2024/04/03/660d14f8a36ed-images2.jpg"
					class="sender-picture" alt="Sender's Picture">
			</div>
		</div>
		<div class="chat-input-container">
			<input type="text" class="chat-input" placeholder="Type your message...">
		</div>
	</div>
</div>
<div class="actions">
	<h2>
		<?php echo __('Inbox'); ?>
	</h2>
	<div class="contact-list">
		<?php foreach ($user as $userData): ?>
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
		<?php endforeach; ?>
		<!-- Add more contacts as needed -->
	</div>
</div>

<script>
	$(document).ready(function () {
		$('.contact-link').click(function (e) {
			e.preventDefault();

			var userId = $(this).data('user-id');
			var chatMessagesContainer = $('.chat-messages');

			$.ajax({
				url: 'http://localhost/message-board/conversations/getConversationById',
				method: 'POST',
				data: { userId: userId },
				success: function (response) {
					var jsonResponse = JSON.parse(response);

					var conversation = jsonResponse.conversation.Conversation;
					var user1 = jsonResponse.conversation.User1;
					var user2 = jsonResponse.conversation.User2;
					var messages = jsonResponse.conversation.Messages;

					chatMessagesContainer.empty();

					messages.forEach(function (message) {
						var senderId = message.Message.senderId;
						var messageContent = message.Message.messageContent;
						var senderName = (senderId === user1.id) ? user1.name : user2.name;
						var messageClass = (senderId === user1.id) ? 'sender' : 'recipient';

						var imageSrc = (senderId === user1.id) ? user1.imageLink : user2.imageLink;
						var imageHtml = `<img src="http://localhost/${imageSrc}" class="${messageClass}-picture" alt="Picture">`;

						var messageHtml = `
						<div class="message ${messageClass}-message">
							${messageClass === 'sender' ? '' : imageHtml}
							<div class="message-content-${messageClass}">
								<p>${messageContent}</p>
							</div>
							${messageClass === 'sender' ? imageHtml : ''}
						</div>
					`;

						chatMessagesContainer.append(messageHtml);
					});
				},
				error: function (xhr, status, error) {
					console.error('AJAX request failed:', status, error);
			
					chatMessagesContainer.empty();

					var messageHtml = `
										<div class="message ${messageClass}-message">
											${messageClass === 'sender' ? '' : imageHtml}
											<div class="message-content-${messageClass}">
												<p>${messageContent}</p>
											</div>
											${messageClass === 'sender' ? imageHtml : ''}
										</div>
									`;

					chatMessagesContainer.append(messageHtml);
				}
			});
		});
	});
</script>