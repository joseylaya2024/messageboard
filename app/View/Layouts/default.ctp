<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
	?>
<!DOCTYPE html>
<html>

<head>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
	echo $this->Html->meta('icon');

	echo $this->Html->css('cake.generic');

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
</head>

<body>
	<div id="container">
		<div id="header">
			<div class="topnav">
				<?php
				App::import('Controller', 'Users');
				?>

				<?php
				if (AuthComponent::user()) { ?>
					<a href="http://localhost/message-board/">Chats</a>
					<div class="right-nav">
						<a href="http://localhost/message-board/users/viewProfile">
							<?php echo AuthComponent::user('name') ?>
						</a>

						<?php echo $this->HTML->link('Logout', array('controller' => 'users', 'action' => 'logout '));
				}else{ 
					 echo (__('Login'));
				}
				?>
				</div>
			</div>
		</div>
		
		<div id="content">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
</body>

</html>

<script>
	$(document).ready(function () {
		var path = window.location.pathname;

		$('.topnav a').each(function () {
			var href = $(this).attr('href');
			if ('http://localhost' + path == href) {
				$(this).addClass('active');
			}
		});
	});

</script>