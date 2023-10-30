<h2>נרשמים חדשים</h2>
<br/>


<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">#</div>
        <div class="col">סטטוס</div>
        <div class="col">שם משתמש</div>
        <div class="col">שם מלא</div>
        <div class="col">שם העסק</div>
        <div class="col">אימייל</div>
        <div class="col">טלפון</div>
        <div class="col">כתובת</div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
    <?php foreach($info['register_list'] as $register): ?>
        <div class="table-tr row">
            <div class="col">
                <?= $register['id'] ?>
            </div>
            <div class="col">
                <?= $register['status'] ?>
            </div>

            <div class="col">
                <?= $register['username'] ?>
            </div>

            <div class="col">
                <?= $register['full_name'] ?>
            </div>

            <div class="col">
                <?= $register['biz_name'] ?>
            </div>

            <div class="col">
                <?= $register['email'] ?>
            </div>

            <div class="col">
                <?= $register['phone'] ?>
            </div>

            <div class="col">
                <?= $register['address'] ?>, <?= $register['city_name'] ?>
            </div>

            <div class="col">
                <a href = "<?= inner_url('user_register/confirm/') ?>?row_id=<?= $register['id'] ?>" title="אישור">אישור</a>
            </div>
            <div class="col">
                <a href = "<?= inner_url('user_register/deny/') ?>?row_id=<?= $register['id'] ?>" title="דחייה">דחייה</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>