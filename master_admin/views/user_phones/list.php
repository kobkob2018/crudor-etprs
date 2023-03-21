<?php $this->include_view("users/header.php"); ?>

<h3>ניהול מספרי טלפון</h3>


<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('user_phones/add/') ?>?user_id=<?= $this->data['user_info']['id'] ?>">הוספת מספר</a>
</div>
<?php if(!empty($this->data['user_phones'])): ?>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">עדכון</div>
        <div class="col">קמפיין</div>

        <div class="col">חיוב בליד</div>
        <div class="col">SMS בשיחה שלא נענתה</div>
        <div class="col">SMS לאחר שיחה</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['user_phones'] as $user_phone): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('user_phones/edit/') ?>?row_id=<?= $user_phone['id'] ?>&user_id=<?= $this->data['user_info']['id'] ?>" title="עריכה"><?= $user_phone['number'] ?></a>
            </div>

            <div class="col">
                סוג: <?= $this->get_label_value('campaign_type',$user_phone) ?>
                <br/>
                שם: <?= $user_phone['campaign_name'] ?>

            </div>
            <div class="col">
                <?= $this->get_label_value('lead_bill',$user_phone) ?>
                
            </div>
            <div class="col">
                <?= $this->get_label_value('misscall_sms_return',$user_phone) ?>
                <div class="tiny-text-show">
                    <?= $user_phone['misscall_sms'] ?>
                </div>
            </div>

            <div class="col">
                <?= $this->get_label_value('aftercall_sms_send',$user_phone) ?>
                <div class="tiny-text-show">
                    <?= $user_phone['aftercall_sms'] ?>
                </div>
            </div>
            <div class="col">
                <a href = "<?= inner_url('user_phones/delete/') ?>?&row_id=<?= $user_phone['id'] ?>&user_id=<?= $this->data['user_info']['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
