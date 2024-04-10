<h3>כניסות למערכת</h3>

<?php if(isset($info['filter_form'])): ?>
    <?php $this->include_view('form_builder/filter_form.php',$info); ?>
<?php endif; ?>
<div class="focus-box">
    <b>מומלץ לנקות מהמערכת כניסות ישנות שכבר אינן בתוקף</b>
    <br/>
    <a class="button-focus" href="<?= inner_url("login_trace/clear_old_logins/") ?>">לחץ כאן לניקוי כניסות ישנות</a>
    <br/>
    הפעולה תנקה את כל הכניסות שמעל 7 ימים
</div>
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
    </div>
    <?php foreach($info['list'] as $key=>$item): ?>
        <div class="table-tr row">
            <div class="col col-tiny"><?= $item['id'] ?></div>
            <div class="col">
                <?php if($item['user']): ?>
                    [<?= $item['user_id'] ?>] 
                    <?= $item['user']['full_name'] ?>
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
