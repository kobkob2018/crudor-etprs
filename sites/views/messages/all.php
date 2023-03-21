<?php if(!empty($this->data['err_messages'])){ ?>
	<div class="messages error-messages">
		<?php foreach($this->data['err_messages'] as $message){ ?>
			<?php $this->include_view('messages/errorMessage.php', array('message'=>$message)); ?>
		<?php } ?>
	</div>
<?php } ?>
<?php if(!empty($this->data['success_messages'])){ ?>
	<div  class="messages success-messages">
		<?php foreach($this->data['success_messages'] as $message){ ?>
			<?php $this->include_view('messages/successMessage.php', array('message'=>$message)); ?>
		<?php } ?>
	</div>
<?php } ?>
