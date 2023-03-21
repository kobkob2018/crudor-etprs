<?php $this->include_view("content_pages/header.php"); ?>

<h4>טפסים בעמוד</h4>

<div class="add-content-block-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('biz_forms/add/') ?>?page_id=<?= $this->data['page_info']['id'] ?>">הוספת טוםס</a>
</div>

<div class="content-biz-forms">
    <?php foreach($this->data['biz_forms'] as $biz_form): ?>
        <div class="content-biz-form">
            <div class="block-label">
                <?= $biz_form['title'] ?>
            </div>
            <div class="block-content">
                <?= $biz_form['cat_id'] ?>
                <div class='clear'></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>