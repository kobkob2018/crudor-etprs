<div class="focus-box">

    <b>דומיין:</b> <?= $this->data['migration_site']['old_domain'] ?> <br/>
    <b>מספר:</b> <?= $this->data['migration_site']['old_id'] ?> <br/>
    <b>unk:</b> <?= $this->data['migration_site']['old_unk'] ?> <br/>
    <b>כותרת:</b> <?= $this->data['migration_site']['old_title'] ?> <br/>

</div>

<div class="controll-header">
    <div class="item-edit-menu">
            <a href = "<?= inner_url('migration_site/list/') ?>" class="item-edit-a <?= $view->a_c_class('migration_site') ?>">פרטי האתר לייבוא</a>
            |
            <a href = "<?= inner_url('migration_page/list/') ?>" class="item-edit-a <?= $view->a_c_class('migration_page') ?>">רשימת דפים לייבוא</a>
            |
            <a href = "<?= inner_url('migration_cat/list/') ?>" class="item-edit-a <?= $view->a_c_class('migration_cat') ?>">התאמת קטגוריות</a>
            |
            <a href = "<?= inner_url('migration_gallery/prepare/') ?>" class="item-edit-a <?= $view->a_c_class('migration_gallery') ?>">ייבוא גלריות</a>
 
            
            
    </div>
</div>