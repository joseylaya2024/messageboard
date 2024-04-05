
<?php
    echo $this->Form->create('User');
    echo $this->Form->input('username');
    echo $this->Form->input('password');
    echo $this->Form->submit('Login'); 
    echo $this->Form->end();
?>

<br>

<?= $this->Html->link('Register', ['controller' => 'Users', 'action' => 'add'], ['class' => 'button']); ?>
