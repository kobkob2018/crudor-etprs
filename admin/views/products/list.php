<?php $this->include_view("products/main_header.php",$info); ?>

<?php if(isset($info['filter_form'])): ?>
    <?php $this->include_view('form_builder/filter_form.php',$info); ?>
<?php endif; ?>

<div class="add-item-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('products/add/') ?>">הוספת מוצר</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col"></div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
    <?php foreach($info['list'] as $product): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('products/edit/') ?>?row_id=<?= $product['id'] ?>" title="ערוך מוצר"><?= $product['label'] ?></a>
                <?php if(isset($product['user_label'])): ?>
                    <br/>
                    <b>נוצר ע"י: </b><?= $product['user_label'] ?>
                <?php endif; ?>
                <?php if($product['status'] == '5'): ?>
                    <br/>
                    <b class="red">ממתין לאישור מנהל</b>
                <?php endif; ?>
                <?php if($product['status'] == '9'): ?>
                    <br/>
                    <b class="red">המוצר לא אושר</b>
                <?php endif; ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('products/delete/') ?>?row_id=<?= $product['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

