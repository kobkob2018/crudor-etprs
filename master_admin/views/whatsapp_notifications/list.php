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
                <div class="notification_row type_<?= $note['type'] ?>" onclick="toggle_notification(this)">
                    <?php foreach($note['values'] as $note_val): ?>
                        <?php $class = str_replace('messages','messages-key',$note_val['class']); ?>
                        <?php $class = str_replace('body','body messages',$class); ?>
                        <div class="<?= $class ?>">
                            <b><?= $note_val['key'] ?>:</b> <span class="note-val"><?= $note_val['value'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col">
                <a href = "<?= inner_url('whatsapp_notifications/delete/') ?>?row_id=<?= $key ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<style type= "text/css">
    .notification_row{font-size: 5px;}
    .notification_row.bigger{font-size: 16px;}
    .notification_row.type_text .from,
    .notification_row.type_text .body,
    .notification_row.type_text .name,
    .notification_row.type_text .display_phone_number
    {font-size: 18px;}
</style>
<script type="text/javascript">
    function toggle_notification(note_el){
        if(note_el.classList.contains('bigger')){
            note_el.classList.remove('bigger');
        }
        else{
            note_el.classList.add('bigger');
        }
    }
</script>