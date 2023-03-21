<h3>כניסה למערכת</h3>
<hr/>

<div id="user_login_wrap" class="user-form">
	<form  class="user-form form-validate" action="" method="POST">
	
		<input type="hidden" name="sendAction" value="smsLoginSend" />
		<div class="row-fluid">	
			<div class="form-group span3">
				<label for="sms_code" id="sms_code_label">קוד שקיבלת בSMS</label>
				<input type="text" name="sms_code" class="form-input required"  data-msg-required="יש למלא שדה זה" />
			</div>
		</div>
		<div class="row-fluid">		
			<div class="form-group">
				<input type="submit"  class="submit-btn"  value="שליחה" />
			</div>
			<div class="form-group">
				<a href = '<?php echo inner_url('userLogin/logout/'); ?>'>לחץ כאן להחלפת משתמש</a>
			</div>		
		</div>
	</form>
</div>