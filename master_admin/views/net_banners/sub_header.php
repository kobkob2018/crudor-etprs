
<div class="sub-header">

    
    <h3>ניהול באנר: <?= $this->data['item_info']['label'] ?></h3>
    <div class="item-edit-menu">
        <a href = "<?= inner_url('net_banners/edit/') ?>?dir_id=<?= $this->data['dir_info']['id'] ?>&row_id=<?= $this->data['item_info']['id'] ?>" class="item-edit-a <?= $view->a_class('net_banners/edit/') ?>">עריכת הבאנר</a>
        | 
        <a href = "<?= inner_url('net_banners/select_cats/') ?>?dir_id=<?= $this->data['dir_info']['id'] ?>&row_id=<?= $this->data['item_info']['id'] ?>" class="item-edit-a <?= $view->a_class('net_banners/select_cats/') ?>">שיוך קטגוריות</a>
    </div>
    <hr/>
</div>