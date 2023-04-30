<?php $this->include_view("products/main_header.php"); ?>


<?php $this->include_view("products/header.php"); ?>
<h3>תמונות של המוצר</h3>

<div class="row-fluid">


-- - - --  -

    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['images_list'] as $image): ?>
            <div class="table-tr row">
                <div class="col">
                    <div class="product-image-small">
                        <img src ="<?= $image['form_handler']->get_form_file_url('small_image'); ?>" />
                    </div>
                </div>
                <div class="col">
                    <a href = "<?= inner_url('product_images/delete/') ?>?row_id=<?= $image['id'] ?>&product_id=<?= $this->data['product_info']['id'] ?>" title="מחק">מחק</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>