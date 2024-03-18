<h3>נוטיפיקציות ווטסאפ</h3>

<?php if(isset($info['filter_form'])): ?>
    <?php $this->include_view('form_builder/filter_form.php',$info); ?>
<?php endif; ?>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-tiny">
            <?php $this->include_view(
                'crud/list_th_order_by.php',
                array(
                    'order_by'=>'id',
                    'label'=>'#'
                )
            );?>
        </div>
        <div class="col">
            פרטים
        </div>
       
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($info['list'] as $key=>$note): ?>
        <div class="table-tr row">
            <div class="col col-tiny"><?= $key ?></div>
            <div class="col">
                <?php print_r_help($note); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>