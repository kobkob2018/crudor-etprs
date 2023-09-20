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

<div class="focus-box red">
    <h2>ייבוא גלריות גדולות</h2>
    <h4>זהו תהליך לא בטיחותי ומומלץ לא להשתמש בו אם יש ברירה</h4>

    <hr/>
    <h4>שלב ראשון: ייבוא תיקיות וגלריות בלי תמונות</h4>
    <?php if(!$info['migration_exist']): ?>
    <a class="button-focus" href="<?= inner_url('migration_gallery/do_migrate_cats/') ?>">
        לחץ כאן להתחלת ייבוא תיקיות וגלריות
    </a>
    <?php else: ?>
        <h2>כבר קיימות תיקיות מיובאות. ניתן למחוק אותן או לייבא תמונות. על מנת למחוק, לחץ על כפתור המחיקה למעלה</h2>
    <?php endif; ?>
    <?php if($info['migration_exist']): ?>
        <hr/>
        <h4>שלב שני: ייבוא תמונות</h4>
        <a class="button-focus" href="<?= inner_url('migration_gallery/do_migrate_images/') ?>">
            לחץ כאן להתחלת ייבוא תיקיות וגלריות
        </a>
        <h4>שים לב: התמונות מיובאות בקבוצות קטנות. יש להמשיך לייבא תמונות עד שתופיע הודעה שכל התמונות יובאו בהצלחה.</h4>
    <?php endif; ?>
</div>