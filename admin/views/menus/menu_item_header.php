<div class="sub-header">
    <?php if(isset($this->data['item_parent_tree'])): ?>
        <h3><?= $this->data['page_title'] ?> 
            <br/>
            <a href = "<?= inner_url("menus/".$this->data['action_name']."/") ?>">
                ראשי
            </a>
            > 
            <?php foreach($this->data['item_parent_tree'] as $parent_item): ?>
                <?php if($parent_item['is_current']): ?>
                    <?= $parent_item['label'] ?>
                <?php else: ?>
                    <a href = "<?= inner_url("menus/".$this->data['action_name']."/") ?>?row_id=<?= $parent_item['id'] ?>">
                        <?= $parent_item['label'] ?>
                    </a>
                    > 
                <?php endif; ?>
            <?php endforeach; ?>
            
        </h3>

    <?php else: ?>
        <h3><?= $this->data['page_title'] ?> ניהול ראשי</h3>
    <?php endif; ?>
</div>