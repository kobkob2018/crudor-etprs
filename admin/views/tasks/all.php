<div class="task-list">
    <h2>הנה רשימת המשימות</h2>
    <p>
        <a href="<?= inner_url("tasks/add/") ?>">הוספת משימה</a>
    </p>
    <?php foreach($this->data['task_list'] as $task): ?>
        <div class="task">
            <a href="<?= inner_url('tasks/view/') ?>?row_id=<?= $task['id'] ?>"><?= $task['title'] ?></a> 
            (נמשוייך למשתמש: <?= $task['user_name'] ?>)
        </div>
    <?php endforeach; ?>
</div>