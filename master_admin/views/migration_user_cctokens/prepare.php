<h2>ייבוא כרטיסי אשראי</h2>
<?php $this->include_view("migration_user/header.php"); ?>
<?php if($info['migration_exist']): ?>
    <div class="focus-box red">
        כבר קיימים כרטיסים מיובאים למשתמש זה. על מנת לייבא, יש למחוק את הייבוא הקודם.
        <a class="button-focus" href="<?= inner_url('migration_user_cctokens/delete_older/?user_id='.$_REQUEST['user_id']) ?>">
            לחץ כאן למחיקת ייבוא כרטיסים ישן
        </a>
    </div>
<?php else: ?>
    <div class="focus-box red">
        <h4>תהליך הייבוא קורה במכה אחת. וייבא את כל הכרטיסים. לאחר התהליך יש לוודא את תקינות הייבוא</h4>
        <a class="button-focus" href="<?= inner_url('migration_user_cctokens/do_migrate/?user_id='.$_REQUEST['user_id']) ?>">
            לחץ כאן להתחלת ייבוא
        </a>
    </div>
<?php endif; ?>