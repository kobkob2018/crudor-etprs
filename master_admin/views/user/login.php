<h3>כניסה למערכת</h3>
<hr/>

<div id="user_login_wrap" class="user-form">
	<form  class="user-form form-validate" action="" method="POST">
	
		<input type="hidden" name="sendAction" value="loginSend" />
		<div class="row-fluid">	
			<div class="form-group span3">
				<label for="user_email" id="user_email_label">שם משתמש</label>
				<input type="text" name="user_username" class="form-input required"  data-msg-required="יש למלא שם משתמש" />
			</div>
			<div class="form-group  span3">
				<label for="user_pass" id="user_pass_label">סיסמא</label>
				<input type="password" name="user_pass" class="form-input required"  data-msg-required="יש למלא סיסמא"  />
			</div>
		</div>
		<div class="row-fluid">		
			<div class="form-group">
				<input type="submit"  class="submit-btn"  value="שליחה" />
			</div>
			<div class="form-group">
				<a href = '<?php echo inner_url('userLogin/forgotPassword/'); ?>'>שכחתי סיסמא</a>
			</div>		
		</div>
	</form>
</div>