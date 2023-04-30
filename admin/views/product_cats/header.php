<div class="eject-box">
    <a class="back-link" href="<?= inner_url('product_cats/list/') ?>">חזרה לרשימת התיקיות</a>
</div>

<h3>ניהול תיקיית מוצרים <?= $this->data['cat_info']['label'] ?></h3>
<hr/>

<div class="item-edit-menu">
    <a href = "<?= inner_url('product_cats/edit/') ?>?row_id=<?= $this->data['cat_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('product_cats') ?>">עריכה</a>
     | 
     <a href = "<?= inner_url('products/list/') ?>?cat_id=<?= $this->data['cat_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('products') ?>">מוצרים בתיקייה</a>
</div>
<hr/>