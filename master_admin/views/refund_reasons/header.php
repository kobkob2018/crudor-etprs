<?php if(isset($this->data['user_info']) && $this->data['user_info']): ?>
    <?php $this->include_view("users/header.php"); ?>

<?php endif; ?>

<?php if(isset($this->data['cat_info']) && $this->data['cat_info']): ?>
    <?php $this->include_view("biz_categories/biz_cat_header.php"); ?>

<?php endif; ?>