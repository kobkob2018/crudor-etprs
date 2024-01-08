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
                    <label for="row[full_name]" id="full_name_label"><?= __tr("Full name") ?></label>
                    <input type="text" name="row[full_name]" id="full_name" class="form-input required" data-msg-required="<?= __tr("This field is mandatory") ?>" value="<?= $this->get_form_input("full_name"); ?>"  />
                </div>					
                <div class="form-group span3">
                    <label for="row[biz_name]" id="biz_name_label"><?= __tr("Business name") ?></label>
                    <input type="text" name="row[biz_name]" id="biz_name" class="form-input required" data-msg-required="<?= __tr("This field is mandatory") ?>" value="<?= $this->get_form_input("biz_name"); ?>""  />
                </div>	
            </div>
            <div class="row-fluid">	
                <div class="form-group span3">
                    <label for="row[phone]" id="phone_label"><?= __tr("Phone") ?></label>
                    <input type="text" name="row[phone]" id="phone" class="form-input required"  value="<?= $this->get_form_input("phone"); ?>""   data-msg-required="<?= __tr("This field is mandatory") ?>" />
                </div>
                <div class="form-group span3">
                    <label for="row[email]" id="email_label"><?= __tr("Email") ?></label>
                    <input type="text" name="row[email]" id="email" class="form-input email required"  value="<?= $this->get_form_input("email"); ?>"" data-msg-required="<?= __tr("This field is mandatory") ?>"  data-msg-email="<?= __tr("Invalid email") ?>" />
                </div>
            </div>
            <div class="row-fluid">		
                <div class="form-group span3">
                    <label for="row[username]" id="username_label"><?= __tr("Username") ?></label>
                    <input type="text" name="row[username]" id="username" class="form-input required"  value="<?= $this->get_form_input("username"); ?>"" data-msg-required="<?= __tr("This field is mandatory") ?>"  data-msg-email="<?= __tr("Invalid Username") ?>" />
                </div>
                <div class="form-group span3">
                    <label for="row[password]" id="password_label"><?= __tr("Password") ?></label>
                    <input type="password" name="row[password]" id="password" class="form-input required" minlength="6"  data-msg-minlength="<?= __tr("There is a minimum of 6 notes") ?>" data-msg-required="<?= __tr("This field is mandatory") ?>" />
                </div>
                <div class="form-group span3">
                    <label for="row[password_confirm]" id="password_confirm_label"><?= __tr("Password validation") ?></label>
                    <input type="password" name="row[password_confirm]" id="password_confirm" class="form-input required" equalTo="#password" data-msg-required="<?= __tr("This field is mandatory") ?>" data-msg-equalTo="<?= __tr("Passwords not match") ?>" />
                </div>	
            </div>
            <div class="row-fluid">	
                <div class="form-group span3">
                    <label id="submit_label"></label>
                    <input type="submit"  class="submit-btn"  value="<?= __tr("Send") ?>" />
                </div>	
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="success-inner-message">
        <?= __tr("Your registration ended successfuly") ?>.<br/>
        <?= __tr("Please check your mailbox at $1 and click the link for email confirmation", array($registered_user)) ?>.<br/>
        <?= __tr("Thankyou") ?>.
    </div>
<?php endif; ?>