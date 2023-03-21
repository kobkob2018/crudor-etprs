<h3>איפוס סיסמה</h3>
<hr/>

<div id="user_forgotPassword_wrap" class="user-form">
	<form class="form-validate" action="" method="POST">
		<input type="hidden" name="sendAction" value="resetPasswordSend" />
		
		<div class="row-fluid">		
			<div class="form-group span3">
				<label for="usr[password]" id="password_label">סיסמה חדשה</label>
				<input type="password" name="usr[password]" id="password" class="form-input" />
			</div>
			<div class="form-group span3">
				<label for="usr[password_auth]" id="password_auth_label">אימות סיסמה</label>
				<input type="password" name="usr[password_auth]" id="password_auth" class="form-input"  equalTo="#password" data-msg-equalTo="על הסיסמאות להיות תואמות" />
			</div>	
		</div>

		<div class="form-group">
			<input type="submit"  class="submit-btn"  value="שליחה" />
		</div>
		<div class="form-group">
			<a href = "<?= inner_url('userLogin/login/') ?>">כניסה למערכת</a>
		</div>			
	</form>
</div>