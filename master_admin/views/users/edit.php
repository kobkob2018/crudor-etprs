<?php $this->include_view("users/header.php"); ?>
<div id="page_form_wrap" class="focus-box form-gen page-form">
    <h4>עריכת פרטי משתמש</h4>
	<?php $this->include_view('form_builder/form.php'); ?>
</div>

<a href="<?= inner_url("migration_user/list/") ?>?row_id=<?= $this->data['item_info']['id'] ?>">לחץ כאן לניהול ייבוא פרטי לקוח ממערכת ישנה</a>

<div class="hidden comment-holder">
    <div class="form-builder-comment" data-for="password-filed">
        <b>השאר ריק אם אינך מחליף סיסמא</b>    
    </div>
    <div class="form-builder-comment" data-for="password-confirm-filed">
        <b>השאר ריק אם אינך מחליף סיסמא</b>    
    </div>
</div>