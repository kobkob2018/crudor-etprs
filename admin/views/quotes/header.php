<div class="sub-header">
    <div class="item-edit-menu">
        <a href = "<?= inner_url('quotes/edit/') ?>?row_id=<?= $this->data['item_info']['id'] ?>&cat_id=<?= $this->data['cat_info']['id'] ?>" class="item-edit-a <?= $view->a_class('quotes/edit/') ?>">עריכה</a>
        | 
        <a href = "<?= inner_url('quotes/assign_cats/') ?>?row_id=<?= $this->data['item_info']['id'] ?>&cat_id=<?= $this->data['cat_info']['id'] ?>" class="item-edit-a <?= $view->a_class('quotes/assign_cats/') ?>">שיוך לתיקיות</a>
        
    </div>
</div>
