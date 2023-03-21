
<div class="eject-box">
    <a class="back-link" href="<?= inner_url('net_directories/list/') ?>">חזרה</a>
</div>

<h3>ניהול תיקיית באנרים: <?= $this->data['dir_info']['label'] ?></h3>
<div class="item-edit-menu">
    <a href = "<?= inner_url('net_directories/edit/') ?>?row_id=<?= $this->data['dir_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('net_directories') ?>">עריכת תיקייה</a>
     | 
     <a href = "<?= inner_url('net_banners/list/') ?>?dir_id=<?= $this->data['dir_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('net_banners') ?>">באנרים</a>
</div>
<hr/>