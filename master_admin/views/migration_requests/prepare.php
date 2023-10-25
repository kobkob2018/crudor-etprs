<h2>ייבוא גלריות מאתר המקור</h2>
<?php $this->include_view("migration_site/header.php"); ?>
<?php if($info['migration_exist']): ?>
    <div class="focus-box red">
        כבר קיימות גלריות מיובאות באתר זה. על מנת לייבא, יש למחוק את הייבוא הקודם.
        <a class="button-focus" href="<?= inner_url('migration_requests/delete_older/') ?>">
            לחץ כאן למחיקת ייבוא גלריות ישן
        </a>
    </div>
<?php endif; ?>

<div class="focus-box red">
    <h2>ייבוא לידים</h2>
    <hr/>
    <a class="button-focus" href="<?= inner_url('migration_requests/do_migrate_requests/') ?>">
        לחץ כאן לייבוא קבוצת לידים
    </a>
    <h4>שים לב: המשך עד שנגמרים הלידים לייבוא.</h4>
    
</div>