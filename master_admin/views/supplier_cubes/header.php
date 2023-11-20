<?php if(isset($this->data['item_info']) && $this->data['item_info']): ?>
    <h3>ניהול קוביית ספק שירות: <?= $this->data['item_info']['label'] ?></h3>
    <div class="form-group">
        קוד מקוצר: <input class="form-input" style="width:330px; text-align:left; direction: ltr;" type="text" value="{{% mod | supplier_cubes | custom | cube:<?= $this->data['item_info']['id'] ?> %}}" />
    </div>
<?php else: ?>
    <h3>הוספת קוביית ספק שירות</h3>
<?php endif; ?>