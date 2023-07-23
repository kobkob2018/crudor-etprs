<h2>ייבוא גלריות מאתר המקור</h2>
<?php $this->include_view("migration_site/header.php"); ?>
<?php if($info['migration_exist']): ?>
    <div class="focus-box red">
        כבר קיימות גלריות מיובאות באתר זה. על מנת לייבא, יש למחוק את הייבוא הקודם.
        <a class="button-focus" href="<?= inner_url('migration_gallery/delete_older/') ?>">
            לחץ כאן למחיקת ייבוא גלריות ישן
        </a>
    </div>
<?php else: ?>
    <div class="focus-box red">
        <h4>תהליך הייבוא קורה במכה אחת. וייבא את כל הגלריות, התמונות והקטגוריות מאתר המקור. לאחר התהליך יש לוודא את תקינות הייבוא</h4>
        <a class="button-focus" href="<?= inner_url('migration_gallery/do_migrate/') ?>">
            לחץ כאן להתחלת ייבוא
        </a>
    </div>
<?php endif; ?>