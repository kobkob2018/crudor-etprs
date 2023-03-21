<div id = "right_bar">

    <?php if($view->user_is('login')): ?>
        <h4>hello <?= $this->user['full_name']; ?></h4>
    <?php endif; ?>

    <?php if(isset($this->data['add_leads_filter_form'])): ?>
        <?php $this->call_module('leads_filter','add_filter_form'); ?>
    <?php endif; ?>
</div>