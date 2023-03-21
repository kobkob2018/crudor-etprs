<?php $this->include_view("users/header.php"); ?>
<div class="focus-box warning-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה לרשימת משתמשים</a>
    </div>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <h2>מחיקת משתמש</h2>
        <h3>שים לב: פעולת מחיקה לא ניתנת לשחזור. עליך לאשר את המחיקה סופית</h3>

        <form action = "<?= current_url() ?>" method="POST">
            <input type="hidden" name="confirm_delete" value="1" />
            <div class="submit-wrap">
                <button type="submit">אני מאשר מחיקת משתמש</button>
            </div>
        </form>
    </div>
</div>