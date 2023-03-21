
<?php $this->include_view("users/header.php"); ?>
<div id="page_form_wrap" class="focus-box form-gen page-form">
	<h4>בחר ערים לשייך לקטגוריה
        <?php foreach($this->data['cat_parent_tree'] as $cat): ?>
            >> <?= $cat['label'] ?>
        <?php endforeach; ?>
    </h4>

    <div class="messages warning-messages">
        <div class="message warning-message">
                שימו לב! אלו שיוכיי עיר לקטגוריה הספציפית ולא למשתמש באופן כללי.
                <br/>
                <div class="eject-box">
                        <a href="<?= inner_url("users/select_cats/") ?>?row_id=<?= $this->data['item_info']['id'] ?>">חזרה לרשימת הקטגוריות של המשתמש</a>
                </div>
        </div>
    </div>

	<?php $this->include_view('tree/select_cats.php'); ?>
</div>