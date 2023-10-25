<h2>ייבוא לידים</h2>
<?php if($info['migration_exist']): ?>
    <div class="focus-box red">
        כבר קיימות גלריות מיובאות באתר זה.ניתן למחוק את הייבוא הקודם.
        <a class="button-focus" href="<?= inner_url('migration_requests/delete_older/') ?>">
            לחץ כאן למחיקת ייבוא לידים ישן
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