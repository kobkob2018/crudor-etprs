<h3>שחזור סיסמא</h3>
<hr/>

<div id="user_forgotPassword_wrap" class="user-form">
	<form class="form-validate" action="" method="POST">
		<input type="hidden" name="sendAction" value="forgotPasswordSend" />
		
		<div class="form-group">
			<label for="user_email" id="user_email_label">כתובת המייל לשחזור סיסמה</label>
			<input type="text" name="user_email" class="form-input required email" />
		</div>
		<div class="form-group">
			<input type="submit"  class="submit-btn"  value="שליחה" />
		</div>
		<div class="form-group">
			<a href = "<?= inner_url('userLogin/login/') ?>">כניסה למערכת</a>
		</div>			
	</form>
</div>