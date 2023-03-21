<?php if(isset($this->data['set_priority_item'])): ?>
    <?php $set_priority_item = $this->data['set_priority_item']; ?>
    <div class = 'move-item-wrap alt-box'>
        <h3>העברת משימה: <?= $set_priority_item['title'] ?></h3>
        <p>בחר מיקום ולחץ "העבר לכאן"</p>
        <div class='bottom-buttons'>
                <div class='cancel-button-wrap'>
                    <a class='cancel-button' href="<?= inner_url('tasks/set_priority/') ?>?cancel=1">ביטול</a> 
                </div>
        </div>

    </div>
<?php endif; ?>
<div class="task-list">
    <h2>הנה רשימת המשימות</h2>
    <p>
        <a href="<?= inner_url("tasks/add/") ?>">הוספת משימה</a>
    </p>
    <?php foreach($this->data['task_list'] as $task): ?>

        <div class="task">
            <a href="<?= inner_url('tasks/edit/') ?>?row_id=<?= $task['id'] ?>"><?= $task['title'] ?></a> 
             | 
             <?php if(isset($this->data['set_priority_item'])): ?>
                
                <a class="sub-focus button-focus" href = "<?= inner_url('tasks/set_priority/') ?>?row_to=<?= $task['id'] ?>" title="ערוך">העבר מעליי</a>
                
            <?php else: ?>
                <a href="<?= inner_url('tasks/set_priority/') ?>?row_id=<?= $task['id'] ?>">העבר</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>