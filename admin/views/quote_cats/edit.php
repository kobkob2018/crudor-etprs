<div class="focus-box"> 
    <?php $this->include_view("quote_cats/header.php"); ?>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view("quote_cats/theme_import.php"); ?>
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
<div class="focus-box">
    <h3>ייצוא מבנה חדש מהתיקייה</h3>
    <form action = "<?= inner_url('quote_cats/export_to_theme/') ?>?row_id=<?= $this->data['cat_info']['id'] ?>" method="POST">
        <h4>בחר שם למבנה החדש: </h4>
        <input type="text" name="cat_label" />
        <input type="submit" value="לחץ כאן לייצוא המבנה" />
    </form>
</div>
