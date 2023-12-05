<h3>
    ניהול לקוח של הצעות מחיר: <?= $this->data['user_info']['biz_name'] ?>
</h3>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= inner_url("/quote_cats/list/") ?>">חזרה לרשימה</a>
    </div>
    <hr/>
    <?php $this->include_view("quotes/user_header.php"); ?>
    
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
    <br/><br/>
    <h3>ייבוא מבנה הצעות מחיר של לקוח</h3>
    <?php $this->include_view("quote_cats/theme_import.php"); ?>
</div>

<div class="focus-box">
    <h3>ייצוא מבנה חדש מהמבנה של המשתמש</h3>
    <form action = "<?= inner_url('quotes_user/export_to_theme/') ?>?user_id=<?= $this->data['user_info']['id'] ?>" method="POST">
        <h4>בחר שם למבנה החדש: </h4>
        <input type="text" name="theme_label" />
        <input type="submit" value="לחץ כאן לייצוא המבנה" />
    </form>
</div>