<div class="controll-header">

    <div class="eject-box">
        <a class="back-link" href="<?= inner_url('net_messages/list/') ?>">חזרה לרשימת ההודעות</a>
    </div>

    <h3>ניהול הודעה: <?= $this->data['item_info']['label'] ?></h3>
    <div class="item-edit-menu">
        <a href = "<?= inner_url('net_messages/edit/') ?>?row_id=<?= $this->data['item_info']['id'] ?>" class="item-edit-a <?= $view->a_class('net_messages/edit/') ?>">עריכת פרטים</a>
        | 
        <a href = "<?= inner_url('net_messages/select_cats/') ?>?row_id=<?= $this->data['item_info']['id'] ?>" class="item-edit-a <?= $view->a_class('net_messages/select_cats/') ?>">קטגוריות</a>
    </div>
    <hr/>
    <a class="focus-box button-focus" href="<?= inner_url('net_messages/send/') ?>?row_id=<?= $this->data['item_info']['id'] ?>">לחץ כאן לשליחת ההודעה</a>
</div>