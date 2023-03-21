<div class="sub-header">
    <?php if(!isset($this->data['item_parent_tree'])): ?>
        <h3>ניהול קטגוריות ראשיות</h3>
    <?php else: ?>
        <h3>ניהול קטגוריה
            <br/>
        <a href = "<?= inner_url("biz_categories/list/") ?>">
            ראשי
        </a>
         > 
         <?php foreach($this->data['item_parent_tree'] as $parent_item): ?>
            <?php if($parent_item['is_current']): ?>
                <?= $parent_item['label'] ?>
                <?php else: ?>
                <a href = "<?= inner_url("biz_categories/list/") ?>?row_id=<?= $parent_item['id'] ?>">
                <?= $parent_item['label'] ?>
            </a>
            > 
            <?php endif; ?>
            <?php endforeach; ?>
            
        </h3>


        
        <div class="item-edit-menu">
            <a href = "<?= inner_url('biz_categories/list/') ?>?row_id=<?= $this->data['current_item_id'] ?>" class="item-edit-a <?= $view->a_class('biz_categories/list/') ?>">תתי קטגוריה</a>
            | 
            <a href = "<?= inner_url('biz_categories/edit/') ?>?row_id=<?= $this->data['current_item_id'] ?>" class="item-edit-a <?= $view->a_class('biz_categories/edit/') ?>">עריכה</a>
            | 
            <a href = "<?= inner_url('biz_categories/select_cities/') ?>?row_id=<?= $this->data['current_item_id'] ?>" class="item-edit-a <?= $view->a_class('biz_categories/select_cities/') ?>">שיוך ערים</a> 
            <br/>
            <a href = "<?= inner_url('cat_phone_display_hours/list/') ?>?cat_id=<?= $this->data['current_item_id'] ?>" class="item-edit-a <?= $view->a_c_class('cat_phone_display_hours') ?>">תצוגת טלפון</a> 
            
        </div>
        <hr/>

    <?php endif; ?>
</div>