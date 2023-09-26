<h2>ייבוא מוצרים מאתר המקור</h2>
<?php $this->include_view("migration_site/header.php"); ?>
<?php if($info['migration_exist']): ?>
    <div class="focus-box red">
        כבר קיימים מוצרים מיובאים באתר זה. על מנת לייבא, יש למחוק את הייבוא הקודם.
        <a class="button-focus" href="<?= inner_url('migration_product/delete_older/') ?>">
            לחץ כאן למחיקת ייבוא מוצרים ישן
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



<div class="focus-box red">
    <h2>ייבוא רשימות מוצרים גדולות</h2>
    <h4>זהו תהליך לא בטיחותי ומומלץ לא להשתמש בו אם יש ברירה</h4>

    <hr/>
    <h4>שלב ראשון: ייבוא תיקיות בלי מוצרים</h4>
    <?php if(!$info['migration_exist']): ?>
    <a class="button-focus" href="<?= inner_url('migration_product/do_migrate_cats/') ?>">
        לחץ כאן להתחלת ייבוא תיקיות
    </a>
    <?php else: ?>
        <h2>כבר קיימות תיקיות מיובאות. ניתן למחוק אותן או לייבא מוצרים. על מנת למחוק, לחץ על כפתור המחיקה למעלה</h2>
    <?php endif; ?>
    <?php if($info['migration_exist']): ?>
        <hr/>
        <h4>שלב שני: ייבוא מוצרים</h4>
        <a class="button-focus" href="<?= inner_url('migration_product/do_migrate_products/') ?>">
            לחץ כאן להתחלת ייבוא מוצרים
        </a>
        <h4>שים לב: התמונות מיובאות בקבוצות קטנות. יש להמשיך לייבא תמונות עד שתופיע הודעה שכל התמונות יובאו בהצלחה.</h4>
    <?php endif; ?>
</div>