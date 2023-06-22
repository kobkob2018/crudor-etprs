<h2>רשימת משימות</h2>

<a href="<?= global_url('biz_form/special_access_for_unlimited_requests/') ?>" title="special access">לחץ כאן לגישה בלתי מוגבלת למילוי טפסים</a>
<br/><br/>
<a class="red" href="<?= global_url('biz_form/remove_access_for_unlimited_requests/') ?>" title="special access">בטל גישה בלתי מוגבלת למילוי טפסים</a>

<?php if($this->view->user_is('master_admin')): ?>
        <h4>צפה במשימות למשתמש</h4>
        <form action = "" method = "GET">
            <div class="form-group">

                <select class="tasks-user-select" name="user_id">
                    <option value="all">כל המשתמשים</option>
                    <?php foreach($info['user_options'] as $user): ?>
                        <option value = "<?= $user['value'] ?>"  <?= $user['selected_str'] ?>><?= $user['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form> 
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded",()=>{
            document.querySelector(".tasks-user-select").addEventListener("change",function(event){
                event.target.closest("form").submit();

            });
        });
    </script>
<?php endif; ?>

<div class="add-item-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('tasks/add/') ?>">הוספת משימה</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col"></div>
        <div class="col">סטטוס</div>
        <div class="col">משימה</div>
        <?php if($this->view->user_is('master_admin')): ?>
            <div class="col">
                שיוך למשתמש
            </div>
        <?php endif; ?>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['task_list'] as $task): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('tasks/edit/') ?>?row_id=<?= $task['id'] ?>" title="ערוך משימה"><?= $task['title'] ?></a>
            </div>

            <div class="col">
                <?= $info['status_list'][$task['status']] ?>
            </div>       
            <div class="col">
                <?= $task['description'] ?>
            </div>        
            <?php if($this->view->user_is('master_admin')): ?>
                <div class="col">
                    <?= $task['user_name'] ?>
                </div> 
                
            <?php endif; ?>
            <div class="col">
                <a href = "<?= inner_url('tasks/delete/') ?>?row_id=<?= $task['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>