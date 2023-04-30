<h3>ניהול תתי תיקיית מוצרים <?= $this->data['item_info']['label'] ?></h3>
<hr/>

<div class="item-edit-menu">
    <a href = "<?= inner_url('product_subs/edit/') ?>?row_id=<?= $this->data['item_info']['id'] ?>" class="item-edit-a <?= $view->a_class('product_subs/edit/') ?>">עריכה</a>
     | 
     <a href = "<?= inner_url('product_subs/assign_cats/') ?>?row_id=<?= $this->data['item_info']['id'] ?>" class="item-edit-a <?= $view->a_class('product_subs/assign_cats/') ?>">שיוך לתיקיות אב</a>
</div>
<hr/>