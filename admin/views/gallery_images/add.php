<?php $this->include_view("gallery_images/gallery_header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= inner_url("/gallery_images/list/") ?>?gallery_id=<?= $this->data['gallery_info']['id'] ?>">חזרה לרשימה</a>
    </div>
    <h3>הוספת תמונה</h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
