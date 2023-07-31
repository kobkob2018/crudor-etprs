<h2>ייבוא גלריות מאתר המקור</h2>
<?php $this->include_view("migration_site/header.php"); ?>
<?php if($info['migration_exist']): ?>
    <div class="focus-box red">
        כבר קיימים מוצרים מיובאים באתר זה. על מנת לייבא, יש למחוק את הייבוא הקודם.
        <a class="button-focus" href="<?= inner_url('migration_product/delete_older/') ?>">
            לחץ כאן למחיקת ייבוא גלריות ישן
        </a>
    </div>
<?php else: ?>
    <div class="focus-box red">
        <h4>תהליך הייבוא קורה במכה אחת. וייבא את כל המוצרים, התמונות והקטגוריות מאתר המקור. לאחר התהליך יש לוודא את תקינות הייבוא</h4>
        <a class="button-focus" href="<?= inner_url('migration_product/do_migrate/') ?>">
            לחץ כאן להתחלת ייבוא
        </a>
    </div>
<?php endif; ?>