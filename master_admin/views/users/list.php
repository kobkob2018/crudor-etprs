

<h3>ניהול משתמשים</h3>


<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('users/add/') ?>">הוספת משתמש</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">עדכון</div>
        <div class="col">אימייל</div>
        <div class="col">פעיל</div>
        <div class="col">תפקיד</div>
        <div class="col">מערכת לידים</div>
        <div class="col">מערכת ניהול אתרים</div>
        
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['users'] as $user): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('users/edit/') ?>?&row_id=<?= $user['id'] ?>" title="ערוך פרטי משתמש"><?= $user['full_name'] ?></a>
            </div>

            <div class="col">
                <?= $user['email'] ?>
            </div>
            <div class="col">
                <?= $this->get_label_value('active',$user) ?>
                
            </div>
            <div class="col">
                <?= $this->get_label_value('roll',$user) ?>
                
            </div>
            <div class="col">
                <a target="_BLANK" href="<?= inner_url('auto_login/myleads/?user_id=') ?><?= $user['id'] ?>" title="מערכת ניהול לידים">
                    מערכת נהול לידים
                </a>

            </div>
            <div class="col">

                <a target="_BLANK" href="<?= inner_url('auto_login/admin/?user_id=') ?><?= $user['id'] ?>" title="מערכת ניהול לידים">
                    מערכת ניהול אתרים
                </a>                         
            </div>
            <div class="col">
                <a href = "<?= inner_url('users/delete/') ?>?&row_id=<?= $user['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

