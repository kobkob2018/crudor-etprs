<h3>דפים באתר</h3>

<?php if(isset($this->data['page_import_prepare']) && $view->site_user_is('master_admin')): ?>
	<a href="<?= inner_url("pages/import_page/") ?>">
		הדף "<?= $this->data['page_import_prepare']['title'] ?>" מוכן להעתקה. לחץ כאן להעתיק את הדף
	</a>
    <br/>
    <br/>
    <a href="<?= inner_url("pages/page_import_unset/") ?>">
		לחץ כאן לשחרר את ההעתקה
	</a>
<?php endif; ?>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">עדכון דף</div>
        <div class="col">צפייה</div>
        <div class="col">מספר צפיות</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['content_pages'] as $content_page): ?>
        <div class="table-tr row is-visible-0<?= $content_page['visible'] ?>">
            <div class="col">
                <?php if($content_page['visible'] != '1'): ?>
                    <span class="fa fa-eye-slash" title="דף נסתר"></span>
                <?php endif; ?>
                <a href = "<?= inner_url('blocks/list/') ?>?page_id=<?= $content_page['id'] ?>" title="ערוך דף"><?= $content_page['title'] ?></a>

            </div>
            <div class="col">
                <a href = "<?= $this->data['work_on_site']['url'] ?>/<?= $content_page['link'] ?>/" target="_BLANK" title="צפה באתר">צפה באתר</a>
            </div>
            <div class="col">
                <?= $content_page['views'] ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('pages/delete/') ?>?row_id=<?= $content_page['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

