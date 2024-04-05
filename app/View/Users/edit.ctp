<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Edit Profile'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('email');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
	<div class="actions">
	<h3><?php echo __('Profile Photo'); ?></h3>
		<div id="imageContainer" class="image-container">
        <div class="avatar-container">
			<img id="previewImage" src="http://localhost/<?= $this->request->data['User']['imageLink'] ?>" alt="Avatar" class="avatar"> 
			<label for="imageInput" id="browseButton" class="browse-button">Browse</label>
        </div>
			<?= $this->Form->create(null, ['id' => 'uploadForm', 'type' => 'file', 'url' => ['controller' => 'Images', 'action' => 'upload'], 'enctype' => 'multipart/form-data']) ?>
            <?= $this->Form->hidden('userId', ['id' => 'userIdInput', 'value' => $this->request->data['User']['id'] ]) ?> 
				<?= $this->Form->file('image', ['id' => 'imageInput', 'class' => 'file-input']) ?>
			<?= $this->Form->end() ?>
		</div>
</div>

<script>
document.getElementById('imageInput').addEventListener('change', function(event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    
    reader.onload = function(e) {
        document.getElementById('previewImage').src = e.target.result;
    }

    var userId = document.getElementById('userIdInput').value; 
    reader.readAsDataURL(file);
    var formData = new FormData(document.getElementById('uploadForm'));
    formData.append('userId', userId); 
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/message-board/users/uploadImage/', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send(formData);
});
</script>
