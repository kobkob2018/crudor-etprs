<div class="sub-header">
    <div class="item-edit-menu">
        <a href = "<?= inner_url('products/edit/') ?>?row_id=<?= $this->data['product_info']['id'] ?>" class="item-edit-a <?= $view->a_class('products/edit/') ?>">עריכה</a>
        | 
        <a href = "<?= inner_url('products/assign_subs/') ?>?row_id=<?= $this->data['product_info']['id'] ?>" class="item-edit-a <?= $view->a_class('products/assign_subs/') ?>">שיוך לתתי תיקיות</a>
        | 
        <a href = "<?= inner_url('product_images/add/') ?>?product_id=<?= $this->data['product_info']['id'] ?>" class="item-edit-a <?= $view->a_class('product_images/add/') ?>">תמונות של המוצר</a>
    </div>
</div>
