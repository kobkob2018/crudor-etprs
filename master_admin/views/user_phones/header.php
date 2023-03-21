<div class="sub-header">
    <h4>עריכת מספר טלפון <?= $this->data['phone_info']['number'] ?></h4>

    <div class="item-edit-menu">

        <a href = "<?= inner_url('user_phones/edit/') ?>?row_id=<?= $this->data['phone_info']['id'] ?>&user_id=<?= $this->data['user_info']['id'] ?>" class="item-edit-a <?= $view->a_class('user_phones/edit/') ?>">עריכה</a>
        |
        <a href = "<?= $this->url_to_api_list() ?>" class="item-edit-a <?= $view->a_class('user_phones/edit_api_list/') ?>">API</a>
        
    </div>
</div>