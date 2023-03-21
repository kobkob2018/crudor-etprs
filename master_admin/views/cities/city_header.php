<div class="sub-header">

    
    <h3>ניהול ערים ואזורים</h3>
    <?php if(isset($this->data['item_parent_tree'])): ?>
        <h3>ניהול קטגוריה
            <br/>
            <a href = "<?= inner_url("cities/list/") ?>">
                ראשי
            </a>
            > 
            <?php foreach($this->data['item_parent_tree'] as $parent_item): ?>
                <?php if($parent_item['is_current']): ?>
                    <?= $parent_item['label'] ?>
                <?php else: ?>
                    <a href = "<?= inner_url("cities/list/") ?>?row_id=<?= $parent_item['id'] ?>">
                        <?= $parent_item['label'] ?>
                    </a>
                    > 
                <?php endif; ?>
            <?php endforeach; ?>
            
        </h3>


        
        <div class="item-edit-menu">
            <a href = "<?= inner_url('cities/list/') ?>?row_id=<?= $this->data['current_item_id'] ?>" class="item-edit-a <?= $view->a_class('cities/list/') ?>">תתי קטגוריה</a>
            | 
            <a href = "<?= inner_url('cities/edit/') ?>?row_id=<?= $this->data['current_item_id'] ?>" class="item-edit-a <?= $view->a_class('cities/edit/') ?>">עריכה</a>
            | 
            <a href = "<?= inner_url('cities/select_biz_cat/') ?>?row_id=<?= $this->data['current_item_id'] ?>" class="item-edit-a <?= $view->a_class('cities/select_biz_cat/') ?>">שיוך קטגוריות</a>
        </div>
        <hr/>

    <?php else: ?>
        <h3>ניהול אזורים ראשי</h3>
    <?php endif; ?>
</div>