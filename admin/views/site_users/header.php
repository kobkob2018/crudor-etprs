<?php if(isset($this->data['user_info'])): ?>
<div class="sub-header">
    <div class="item-edit-menu">
        <a href = "<?= inner_url('site_users/edit/') ?>?user_id=<?= $this->data['user_info']['id'] ?>" class="item-edit-a <?= $view->a_class('site_users/edit/') ?>">הרשאות מנהל אתר</a>
        | 
        <a href = "<?= inner_url('portal_user/list/') ?>?user_id=<?= $this->data['user_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('portal_user') ?>">מאפייניי פורטל</a>   
        | 
        <a href = "<?= inner_url('portal_styling/list/') ?>?user_id=<?= $this->data['user_info']['id'] ?>" class="item-edit-a <?= $view->a_c_class('portal_styling') ?>">עיצוב ייחודי לפורטל</a>   

    </div>
</div>
<?php endif; ?>