<?php if(isset($this->data['migration_user'])): ?>
    <div class="focus-box">

        <b>unk:</b> <?= $this->data['migration_user']['old_unk'] ?> <br/>
        <b>מספר:</b> <?= $this->data['migration_user']['old_id'] ?> <br/>
        <b>שם:</b> <?= $this->data['migration_user']['old_name'] ?> <br/>
        <b>שם מלא:</b> <?= $this->data['migration_user']['old_full_name'] ?> <br/>

    </div>
<?php endif; ?>
<div class="controll-header">
    <div class="item-edit-menu">
            <a href = "<?= inner_url('migration_user/list/?row_id='.$_REQUEST['user_id']) ?>" class="item-edit-a <?= $view->a_c_class('migration_user') ?>">פרטי המשתמש לייבוא</a>
            |
            <a href = "<?= inner_url('migration_user_payments/prepare/?user_id='.$_REQUEST['user_id']) ?>" class="item-edit-a <?= $view->a_c_class('migration_user_payments') ?>">רשימת תשלומים לייבוא</a>
    </div>
</div>