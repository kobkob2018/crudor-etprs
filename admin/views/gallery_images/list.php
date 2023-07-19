<?php $this->include_view("/gallery_images/gallery_header.php"); ?>

<div class="add-item-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('gallery_images/add/') ?>?gallery_id=<?= $this->data['gallery_info']['id'] ?>">הוספת תמונה</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col"></div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['images_list'] as $image): ?>
        <div class="table-tr row">
            <div class="col">
                
                <a href = "<?= inner_url('gallery_images/edit/') ?>?gallery_id=<?= $this->data['gallery_info']['id'] ?>&row_id=<?= $image['id'] ?>" title="ערוך תמונה"><?= $image['label'] ?></a>
                <div class="gallery-image-small thumb-wrap">
                    <img src ="<?= $image['form_handler']->get_form_file_url('small_image'); ?>" />
                </div>
            </div>
            <div class="col">
                <a href = "<?= inner_url('gallery_images/delete/') ?>?row_id=<?= $image['id'] ?>&gallery_id=<?= $this->data['gallery_info']['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

