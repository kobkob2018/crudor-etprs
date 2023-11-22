<?php if($view->site_user_is('admin')): ?>
    <div class="sub-header focus-box">
        <div class="item-edit-menu">
            <a href = "<?= inner_url('products/list/') ?>" class="item-edit-a <?= $view->a_c_class('products, product_images') ?>">מוצרים באתר</a>
            | 
            <a href = "<?= inner_url('product_cats/list/') ?>" class="item-edit-a <?= $view->a_c_class('product_cats') ?>">תיקיות מוצרים</a>
            | 
            <a href = "<?= inner_url('product_subs/list/') ?>" class="item-edit-a <?= $view->a_c_class('product_subs') ?>">תתי תיקיות</a>
            
        </div>
    </div>
<?php endif; ?>
<h2>ניהול מוצרים</h2>
<div class="watch-helper">
    <a target="_BLANK" href="<?= $this->data['work_on_site']['url'] ?>/products/view/">צפה באתר</a>
</div>