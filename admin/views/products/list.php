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
                <?php $this->include_view("portal_user/item_assign_label.php",array('item'=>$product,'global_info'=>$info)) ?>
                
                <?php if($product['status'] == '5'): ?>
                    <br/>
                    <b class="red">ממתין לאישור מנהל</b>
                <?php endif; ?>
                <?php if($product['status'] == '9'): ?>
                    <br/>
                    <b class="red">המוצר לא אושר</b>
                <?php endif; ?>
                <?php if($view->site_user_is('admin')): ?>
                    <br/>
                    <div class="focus-box">
                        שינוי סטטוס:
                        <br/>
                        <a class="set-status-1<?= $product['status'] ?>" href = "<?= inner_url('products/status_update/') ?>?row_id=<?= $product['id'] ?>&status=1" title="מאשר">מאשר</a>
                        | 
                        <a class="set-status-9<?= $product['status'] ?>" href = "<?= inner_url('products/status_update/') ?>?row_id=<?= $product['id'] ?>&status=9" title="לא מאשר">לא מאשר</a>
                        | 
                        <a class="set-status-5<?= $product['status'] ?>" href = "<?= inner_url('products/status_update/') ?>?row_id=<?= $product['id'] ?>&status=5" title="ממתין לאישור">ממתין לאישור</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('products/delete/') ?>?row_id=<?= $product['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php $this->include_view("portal_user/items_assign_scripts.php",array('api_url'=>"products/ajax_assign_user/",'site_users'=>$info['site_users'],'global_info'=>$info)) ?>
