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