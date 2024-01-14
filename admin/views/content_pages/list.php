<h3>דפים באתר</h3>

<?php if(isset($info['filter_form'])): ?>
    <?php $this->include_view('form_builder/filter_form.php',$info); ?>
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
                <?php $this->include_view("portal_user/item_assign_label.php",array('item'=>$content_page,'global_info'=>$info)) ?>
                
                <?php if($content_page['status'] == '5'): ?>
                    <br/>
                    <b class="red">ממתין לאישור מנהל</b>
                <?php endif; ?>
                <?php if($content_page['status'] == '9'): ?>
                    <br/>
                    <b class="red">הדף לא אושר</b>
                <?php endif; ?>

                <br/>

                <?php if($content_page['archived'] == '1'): ?>
                    <div class="focus-box">
                        <a href = "<?= inner_url('pages/restore_from_archive/') ?>?row_id=<?= $content_page['id'] ?>" onclick = "return confirm('האם לשחזר את הדף מהארכיון?');" title="שחזר מהארכיון">שחזר מהארכיון</a>
                    </div>
                <?php elseif($view->site_user_is('admin')): ?>
                    <div class="focus-box">
                        שינוי סטטוס:
                        <br/>
                        <a class="set-status-1<?= $content_page['status'] ?>" href = "<?= inner_url('pages/status_update/') ?>?row_id=<?= $content_page['id'] ?>&status=1" title="מאשר">מאשר</a>
                        | 
                        <a class="set-status-9<?= $content_page['status'] ?>" href = "<?= inner_url('pages/status_update/') ?>?row_id=<?= $content_page['id'] ?>&status=9" title="לא מאשר">לא מאשר</a>
                        | 
                        <a class="set-status-5<?= $content_page['status'] ?>" href = "<?= inner_url('pages/status_update/') ?>?row_id=<?= $content_page['id'] ?>&status=5" title="ממתין לאישור">ממתין לאישור</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col">
                <?php if($content_page['status'] != '1' || $content_page['archived'] == '1'): ?>
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
                <?php if($content_page['archived'] == '1'): ?>
                    <a href = "<?= inner_url('pages/delete/') ?>?row_id=<?= $content_page['id'] ?>" onclick = "return confirm('האם למחוק את הדף לצמיתות?');" title="מחק">מחק</a>
                <?php else: ?>
                    <a href = "<?= inner_url('pages/delete/') ?>?row_id=<?= $content_page['id'] ?>" onclick = "return confirm('האם להעביר את הדף לארכיון?')" title="העבר לארכיון">העבר לארכיון</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php $this->include_view("portal_user/items_assign_scripts.php",array('api_url'=>"pages/ajax_assign_user/",'site_users'=>$info['site_users'],'global_info'=>$info)) ?>