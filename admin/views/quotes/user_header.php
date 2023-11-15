<?php if(isset($this->data['user_info'])): ?>
<div class="sub-header">
    <div class="item-edit-menu">
        <a href = "<?= inner_url('quotes/user_list/') ?>?user_id=<?= $this->data['user_info']['id'] ?>" class="item-edit-a <?= $view->a_class('quotes/user_list/') ?>">הצעות המחיר של הלקוח</a>
        | 
        <a href = "<?= inner_url('quotes_user/list/') ?>?user_id=<?= $this->data['user_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('quotes_user') ?>">ניהול פרטים כלליים</a>
        
    </div>
</div>
<?php endif; ?>