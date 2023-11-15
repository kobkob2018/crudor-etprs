<?php $target_str = 'target="_BLANK"'; ?>
<?php if(isset($_REQUEST['go_to_page'])): ?>
    <?php $target_str = "" ?>
<?php endif; ?>
<div class="eject-box">
    <a class="back-link" href="<?= inner_url('pages/list/') ?>">חזרה</a>
</div>
<h3>ניהול דף: <?= $this->data['page_info']['title'] ?></h3>
<div class='view-on-site'>
<?php if($this->data['page_info']['status'] != '1'): ?>
    <?php if($this->data['page_info']['status'] == '5'): ?>
        <b class="red">הדף ממתין לאישור מנהל</b>
        <br/>
    <?php endif; ?>
    <?php if($this->data['page_info']['status'] == '9'): ?>
        <b class="red">הדף לא אושר</b>
        <br/>
    <?php endif; ?>
    <a href = "<?= $this->data['work_on_site']['url'] ?>/<?= $this->data['page_info']['link'] ?>/?demo_view=1" <?= $target_str ?> title="צפה באתר">צפה בהדמייה של הדף</a>
<?php else: ?>
    <a href = "<?= $this->data['work_on_site']['url'] ?>/<?= $this->data['page_info']['link'] ?>" <?= $target_str ?> title="צפה באתר">צפה באתר</a>
<?php endif; ?>
</div>
<div class="item-edit-menu">
    <a href = "<?= inner_url('pages/edit/') ?>?row_id=<?= $this->data['page_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('pages') ?>">ראשי</a>
     | 
     <a href = "<?= inner_url('blocks/list/') ?>?page_id=<?= $this->data['page_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('blocks') ?>">בלוקים</a>
     | 
     <a href = "<?= inner_url('biz_forms/list/') ?>?page_id=<?= $this->data['page_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('biz_forms') ?>">ניהול טופס</a>
     | 
     <a href = "<?= inner_url('page_style/list/') ?>?page_id=<?= $this->data['page_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('page_style') ?>">עיצוב ומבנה</a>
    |
    <a href = "javascript://" class="item-edit-a helper content-helper-open">?</a>
</div>
<hr/>

<?php $this->include_view('content_pages/helper.php'); ?>