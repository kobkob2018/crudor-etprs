<h3>הרשמה למערכת השותפים</h3>
<hr/>
<?php if($info['state'] == 'reg_form'): ?>

    <div id="user_register_wrap" class="user-form">
        <?php if(isset($info['err_massage'])): ?>
            <div class="messages error-messages">
                <div class="message error-message">
                    <b><?= $info['err_massage'] ?></b>
                </div>
            </div>
        <?php endif; ?>
        <form name="send_user_form" class="user-form form-validate" id="user_register_form" method="post" action="">
            <input type="hidden" name="sendAction" value="registerSend" />
            <div class="row-fluid">	
                <div class="form-group span3">
                    <label for="reg[full_name]" id="first_name_label">שם פרטי</label>
                    <input type="text" name="reg[first_name]" id="first_name" class="form-input required" data-msg-required="זהו שדה חובה" value=""  />
                </div>					
                <div class="form-group span3">
                    <label for="reg[biz_name]" id="biz_name_label">שם העסק</label>
                    <input type="text" name="reg[biz_name]" id="biz_name" class="form-input required" data-msg-required="זהו שדה חובה" value=""  />
                </div>	
            </div>
            <div class="row-fluid">	
                <div class="form-group span3">
                    <label for="reg[phone]" id="phone_label">טלפון</label>
                    <input type="text" name="reg[phone]" id="phone" class="form-input required"  value=""   data-msg-required="זהו שדה חובה" />
                </div>
                <div class="form-group span3">
                    <label for="reg[email]" id="email_label">אימייל</label>
                    <input type="text" name="reg[email]" id="email" class="form-input email required"  value="" data-msg-required="זהו שדה חובה"  data-msg-email="כתובת המייל לא תקינה" />
                </div>
            </div>
            <div class="row-fluid">		
                <div class="form-group span3">
                    <label for="reg[username]" id="username_label">שם משתמש</label>
                    <input type="text" name="reg[username]" id="username" class="form-input required"  value="" data-msg-required="זהו שדה חובה"  data-msg-email="שם המשתמש לא תקין" />
                </div>
                <div class="form-group span3">
                    <label for="reg[password]" id="password_label">סיסמה</label>
                    <input type="password" name="reg[password]" id="password" class="form-input required" minlength="6"  data-msg-minlength="יש למלא מינימום 6 תווים" data-msg-required="זהו שדה חובה" />
                </div>
                <div class="form-group span3">
                    <label for="reg[password_auth]" id="password_auth_label">אימות סיסמה</label>
                    <input type="password" name="reg[password_auth]" id="password_auth" class="form-input required" equalTo="#password" data-msg-required="זהו שדה חובה" data-msg-equalTo="הסיסמאות אינן זהות" />
                </div>	
            </div>
            <div class="row-fluid">	
                <div class="form-group span3">
                    <label id="submit_label"></label>
                    <input type="submit"  class="submit-btn"  value="שליחה" />
                </div>	
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="success-inner-message">
        הרשמתך בוצעה בהצלחה.<br/>
        אנא בדוק את תיבת המייל שלך ב: <?= $registered_user ?> ולחץ על הלינק לאימות כתובת המייל.<br/>
        תודה.
    </div>


<?php endif; ?>