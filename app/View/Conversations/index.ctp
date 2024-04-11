<div class="conversations index">
	<h2>
		<?php echo __('Conversations'); ?>
	</h2>
	<div class="chat-container" id="message-container">
		<div class="chat-header">
		</div>
		<div class="center">
			<a href="#" id="olderMessages" class="chat-pagination">show older messages</a>
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
	<div class="dropdown">
		<input type="text" id="searchInput" class="form-control" placeholder="Search users...">
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenu">
			<!-- Dropdown items will be dynamically added here -->
		</div>
	</div>
	<div class="contact-list">
		<?php
		if (isset($users)) {

			foreach ($users as $userData): ?>
				<a href="#" class="contact-link" data-user-id="<?php echo $userData['id']; ?>">
					<div class="contact">
						<img src="http://localhost/<?php echo $userData['imageLink'] ?>" alt="Profile Picture">
						<div class="contact-details">
							<span class="name">
								<?php echo $userData['name']; ?>
							</span>
							<span class="email">
								<?php echo $userData['email']; ?>
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
		$(document).on('click', '#deleteMessage', function (e) {
			e.preventDefault();

			var messageId = this.getAttribute('data-msg-id');
			var userId = this.getAttribute('data-user-id');

			$.ajax({
				url: 'http://localhost/message-board/messages/delete/' + messageId,
				type: 'POST',
				dataType: 'json',
				success: function (response) {
					if (response.success) {
						getMessages(userId, offset = null);
						console.log('Conversation deleted successfully');
					} else {
						console.error('Failed to delete conversation');
					}
				},
				error: function (xhr, status, error) {
					console.error('AJAX error:', error);
				}
			});
		});

		var typingTimer;
		var stopInput = 500;

		$('#searchInput').on('keyup', function () {
			clearTimeout(typingTimer);

			var searchText = $(this).val().toLowerCase();

			typingTimer = setTimeout(function () {
				var dropdownMenu = $('#dropdownMenu');
				dropdownMenu.empty();

				if (!searchText) {
					dropdownMenu.hide();
					return;
				}

				$.ajax({
					url: 'http://localhost/message-board/conversations/searchUser',
					dataType: 'json',
					type: 'POST',
					data: { searchText: searchText },
					success: function (users) {
						users.forEach(function (userData) {
							var userName = userData['User']['name'];
							var userEmail = userData['User']['email'];
							var userId = userData['User']['id'];
							var img = userData['User']['imageLink'];
							var dropdownItem = `
								<a href="#" class="contact-link" data-user-id="${userId}">
									<div class="contact">
										<img src="http://localhost/${img}" alt="Profile Picture">
										<div class="contact-details">
											<span class="name">${userName}</span>
											<span class="email">${userEmail}</span>
										</div>
									</div>
								</a>
							`;

							dropdownMenu.append(dropdownItem);
						});
						dropdownMenu.show();
					}
				});
			}, stopInput);
		});

		function messagesFunction(usr1, usr2, msgs, conv) {
			console.log(conv.User2.id)
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

			if (msgs) {
				msgs.forEach(function (msg) {
					var senderId = msg.Message.senderId;
					var msgContent = msg.Message.messageContent;
					var senderName = (senderId === usr1.id) ? usr1.name : usr2.name;
					var msgClass = (senderId === usr1.id) ? 'sender' : 'recipient';

					var imgSrc = (senderId === usr1.id) ? usr1.imageLink : usr2.imageLink;
					var sentAt = (senderId === usr1.id) ? usr1.createdAt : usr2.createdAt;

					var imgHtml = ` <a href="http://localhost/message-board/users/view/${usr2.id}"><img src="http://localhost/${imgSrc}" class="${msgClass}-picture" alt="Picture"></a>`;

					var msgHtml = `
						<div class="message ${msgClass}-message">
						
							${msgClass === 'sender' ? '' : imgHtml}
							<div class="messageContainer">
								<div class="message-content-${msgClass}">
									<p>${msgContent}</p>
									
								</div>
								<a href="#" class="delete-message" id="deleteMessage" data-user-id="${usr2.id}" data-msg-id="${msg.Message.id}">
										<i class="fas fa-trash-alt"></i>
									</a>
								<p class="dateTime">${formatDateTime(sentAt)}</p>
							</div>
							${msgClass === 'sender' ? imgHtml : ''}
						</div>`;

					chatMsgsContainer.append(msgHtml);
				});
			}
		}

		function formatDateTime(dateString) {
			var date = new Date(dateString);
			var monthAbbrev = date.toLocaleString('en-US', { month: 'short' });

			var day = date.getDate();

			var hours = date.getHours();

			var minutes = date.getMinutes();

			var ampm = hours >= 12 ? 'pm' : 'am';

			hours = hours % 12;
			hours = hours ? hours : 12;

			minutes = minutes < 10 ? '0' + minutes : minutes;
			var formattedDateTime = monthAbbrev + ' ' + day + ', ' + hours + ':' + minutes + ampm;

			return formattedDateTime;
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

			var userId = this.getAttribute('data-recipient-id');

			if (userId !== previousUserId) {
				offset = 0;
			}
			previousUserId = userId;

			offset += 5;

			getMessages(userId, offset);
		});

		$('#dropdownMenu').on('click', '.contact-link', contactLinkClickHandler);

		$('.contact-link').on('click', contactLinkClickHandler);

		function contactLinkClickHandler(event) {
			if (event) {
				event.preventDefault();
			}

			$('#searchInput').val('');
			var dropdownMenu = $('#dropdownMenu');
			dropdownMenu.empty();
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
		}

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