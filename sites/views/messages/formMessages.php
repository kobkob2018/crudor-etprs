<?php if(isset($this->data['form_success_messages'])): ?>
    <div class="messages success-messages">
        <?php foreach($this->data['form_success_messages'] as $form_message): ?>
            <div class="message success-message">
                <b><?php echo $form_message; ?></b>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if(isset($this->data['form_err_messages'])): ?>
    <div class="messages err-messages">
        <?php foreach($this->data['form_err_messages'] as $form_message): ?>
            <div class="message err-message">
                <b><?php echo $form_message; ?></b>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>