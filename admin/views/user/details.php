<h3>ניהול פרטים אישיים</h3>
<hr/>
<div id="user_register_wrap" class="user-form">
	<form name="send_affiliate_form" class="user-form form-validate" id="user_register_form" method="post" action="">
		<input type="hidden" name="sendAction" value="updateSend" />
		
			<div class="form-group span3">
				<label for="usr[full_name]" id="full_name_label">שם מלא</label>
				<br/>
				<input type="text" name="usr[full_name]" id="full_name" class="form-input required" data-msg-required="*" value="<?= $this->user["full_name"]; ?>"  />
				<br/><br/>
			</div>
			<div class="form-group span3">
				<label for="usr[biz_name]" id="biz_name_label">שם העסק</label>
				<br/>
				<input type="text" name="usr[biz_name]" id="biz_name" class="form-input required" data-msg-required="*" value="<?= $this->user["biz_name"]; ?>"  />
				<br/><br/>
			</div>
			<div class="form-group span3">
				<label for="usr[phone]" id="phone_label">טלפון</label>
				<br/>
				<input type="text" name="usr[phone]" id="phone" class="form-input required"  value="<?= $this->user["phone"]; ?>"   data-msg-required="*" />
				<br/><br/>
			</div>	
			<div class="form-group span3">
				<label for="usr[email]" id="email_label">אימייל</label>
				<br/>
				<?= $this->user["email"]; ?>
				<br/><br/>
			</div>			
		
		<hr/>
			

			<div class="form-group span3">
				<b>פרטי כניסה למערכת</b>
				<br/>
			</div>	
				
		
			<div class="form-group span3">
				<label for="usr[username]" id="username_label">שם משתמש</label>
				<br/>
				<input type="text" name="usr[username]" id="username" class="form-input required"  value="<?= $this->user["username"]; ?>"   data-msg-required="*" />
				<br/><br/>
			</div>		
			<div class="form-group span3">
				<label for="usr[password]" id="password_label">סיסמה<small>(השאר ריק אם אינך רוצה לשנות)</small></label>
				<br/>
				<input type="password" name="usr[password]" id="password" class="form-input" />
				<br/><br/>
			</div>
			<div class="form-group span3">
				<label for="usr[password_auth]" id="password_auth_label">אימות סיסמה</label>
				<br/>
				<input type="password" name="usr[password_auth]" id="password_auth" class="form-input"  equalTo="#password" data-msg-equalTo="על הסיסמאות להיות תואמות" />
				<br/>
				<br/>
			</div>	
		
		<hr/>
		<br/>
			<div class="form-group span3">
				<label id="submit_label"></label>
				
				<input type="submit"  class="submit-btn"  value="שליחה" />
			</div>
		
	</form>
</div>
