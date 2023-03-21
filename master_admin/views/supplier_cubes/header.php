<?php if(isset($this->data['item_info']) && $this->data['item_info']): ?>
    <h3>ניהול קוביית ספק שירות: <?= $this->data['item_info']['label'] ?></h3>
<?php else: ?>
    <h3>הוספת קוביית ספק שירות</h3>
<?php endif; ?>