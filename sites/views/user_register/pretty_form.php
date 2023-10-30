<?php if($info['state'] == 'reg_form'): ?>
    <?php $this->include_view('messages/formMessages.php'); ?>
    <div id="user_register_wrap" class="user-form">
        <?php if(isset($info['err_massage'])): ?>
            <div class="messages error-messages">
                <div class="message error-message">
                    <b><?= $info['err_massage'] ?></b>
                </div>
            </div>
        <?php endif; ?>
        <form name="send_user_form" class="user-form form-validate" id="user_register_form" method="post" action="">
        <input type="hidden" name="sendAction" value="<?= $this->data['form_builder']['sendAction'] ?>" />
            
            <div class="row-fluid">	
                <div class="form-group span3">
                    <label for="row[full_name]" id="full_name_label">שם מלא</label>
                    <input type="text" name="row[full_name]" id="full_name" class="form-input required" data-msg-required="זהו שדה חובה" value="<?= $this->get_form_input("full_name"); ?>"  />
                </div>					
                <div class="form-group span3">
                    <label for="row[biz_name]" id="biz_name_label">שם העסק</label>
                    <input type="text" name="row[biz_name]" id="biz_name" class="form-input required" data-msg-required="זהו שדה חובה" value="<?= $this->get_form_input("biz_name"); ?>""  />
                </div>	
            </div>
            <div class="row-fluid">	
                <div class="form-group span3">
                    <label for="row[phone]" id="phone_label">טלפון</label>
                    <input type="text" name="row[phone]" id="phone" class="form-input required"  value="<?= $this->get_form_input("phone"); ?>""   data-msg-required="זהו שדה חובה" />
                </div>
                <div class="form-group span3">
                    <label for="row[email]" id="email_label">אימייל</label>
                    <input type="text" name="row[email]" id="email" class="form-input email required"  value="<?= $this->get_form_input("email"); ?>"" data-msg-required="זהו שדה חובה"  data-msg-email="כתובת המייל לא תקינה" />
                </div>
            </div>
            <div class="row-fluid">		
                <div class="form-group span3">
                    <label for="row[username]" id="username_label">שם משתמש</label>
                    <input type="text" name="row[username]" id="username" class="form-input required"  value="<?= $this->get_form_input("username"); ?>"" data-msg-required="זהו שדה חובה"  data-msg-email="שם המשתמש לא תקין" />
                </div>
                <div class="form-group span3">
                    <label for="row[password]" id="password_label">סיסמה</label>
                    <input type="password" name="row[password]" id="password" class="form-input required" minlength="6"  data-msg-minlength="יש למלא מינימום 6 תווים" data-msg-required="זהו שדה חובה" />
                </div>
                <div class="form-group span3">
                    <label for="row[password_confirm]" id="password_confirm_label">אימות סיסמה</label>
                    <input type="password" name="row[password_confirm]" id="password_confirm" class="form-input required" equalTo="#password" data-msg-required="זהו שדה חובה" data-msg-equalTo="הסיסמאות אינן זהות" />
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