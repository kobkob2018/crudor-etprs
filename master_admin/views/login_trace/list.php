<h3>כניסות למערכת</h3>

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
            שם משתמש
        </div>
        <div class="col">
            זמן כניסה
        </div>
        <div class="col">
            ip
        </div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($info['list'] as $key=>$item): ?>
        <div class="table-tr row">
            <div class="col col-tiny"><?= $item['id'] ?></div>
            <div class="col">
                <?php if($item['user']): ?>
                    <?= $item['user']['full_name'] ?><?= $item['user_id'] ?>
                <?php else: ?>
                    <b class="red">המשתמש מחוק</b>
                <?php endif; ?>
            </div>
            <div class="col">
                <?= $item['login_time'] ?>
            </div>
            <div class="col">
                <?= $item['ip'] ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
