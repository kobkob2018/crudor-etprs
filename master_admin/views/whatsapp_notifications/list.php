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
                <div class="notification_val type_<?= $note['type'] ?>">
                    <?php foreach($note['values'] as $note_val): ?>
                        <div class="<?= $note_val['class'] ?>">
                            <b><?= $note_val['key'] ?>:</b> <span class="note-val"><?= $note_val['value'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<style type= "text/css">
    .notification{font-size: 5px;}
    .notification .type_text .from,
    .notification .type_text .body,
    .notification .type_text .name,
    .notification .type_text .display_phone_number
    {font-size: 18px;}
</style>