<h3>דפים באתר</h3>

<?php if($view->site_user_is('admin')): ?>

    <?php if(isset($info['filter_form'])): ?>
        <?php $this->include_view('form_builder/filter_form.php',$info); ?>
    <?php endif; ?>

    <?php if(isset($_REQUEST['setup_status'])): ?>
        <div class="eject-box">
            <a class="back-link" href="<?= inner_url('pages/list/') ?>">צפה בכל הדפים באתר</a>
        </div>
    <?php else: ?>
        <div class="eject-box">
            <a class="back-link" href="<?= inner_url('pages/list/?setup_status=1') ?>">צפה בדפים הממתינים לאישור</a>
        </div>
    <?php endif; ?>
<?php endif; ?>

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
        <?php if($view->site_user_is('master_admin')): ?>
            <div class="col">מספר צפיות</div>
            <div class="col">המרות</div>
            <div class="col">ספאם</div>
        <?php endif; ?>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($info['list'] as $content_page): ?>
        <div class="table-tr row is-visible-0<?= $content_page['visible'] ?>">
            <div class="col">
                <?php if($content_page['visible'] != '1'): ?>
                    <span class="fa fa-eye-slash" title="דף נסתר"></span>
                <?php endif; ?>
                <a href = "<?= inner_url('blocks/list/') ?>?page_id=<?= $content_page['id'] ?>" title="ערוך דף"><?= $content_page['title'] ?></a>
                <?php if(isset($content_page['user_label'])): ?>
                    <br/>
                    <b>נוצר ע"י: </b><?= $content_page['user_label'] ?>
                <?php endif; ?>
                <?php if($content_page['status'] == '5'): ?>
                    <br/>
                    <b class="red">ממתין לאישור מנהל</b>
                <?php endif; ?>
                <?php if($content_page['status'] == '9'): ?>
                    <br/>
                    <b class="red">הדף לא אושר</b>
                <?php endif; ?>
            </div>
            <div class="col">
                <?php if($content_page['status'] != '1'): ?>
                    <a href = "<?= $this->data['work_on_site']['url'] ?>/<?= $content_page['link'] ?>/?demo_view=1" target="_BLANK" title="צפה באתר">צפה בהדמייה של הדף</a>
                <?php else: ?>
                    <a href = "<?= $this->data['work_on_site']['url'] ?>/<?= $content_page['link'] ?>/" target="_BLANK" title="צפה באתר">צפה באתר</a>
                <?php endif; ?>
            </div>
            <?php if($view->site_user_is('master_admin')): ?>
                <div class="col">
                    <?= $content_page['views'] ?>
                </div>
                <div class="col">
                    <?= $content_page['convertions'] ?>
                </div>
                <div class="col">
                    <?= $content_page['spam_convertions'] ?>
                </div>
            <?php endif; ?>
            <div class="col">
                <a href = "<?= inner_url('pages/delete/') ?>?row_id=<?= $content_page['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

