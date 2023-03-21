<?php if(isset($this->data['move_item'])): ?>
    <div class = 'move-item-wrap alt-box'>
        <h3>העברת רכיב
        <br/>
        <?php foreach($this->data['move_item']['tree'] as $parent_item): ?>
            <?php if($parent_item['is_current']): ?>
                <?= $parent_item['label'] ?>
            <?php else: ?>
                <a href = "<?= $view->action_url(); ?>?row_id=<?= $parent_item['id'] ?>">
                    <?= $parent_item['label'] ?>
                </a>
                 > 
            <?php endif; ?>
        <?php endforeach; ?>    
        </h3>
        <p>בחר את רכיב האב ולחץ "העבר לכאן"</p>
        <div class='bottom-buttons'>
            <div class='cancel-button-wrap'>
                <a class='cancel-button' href="<?= current_url(array('move_item'=>'cancel')) ?>">ביטול</a> 
            </div>
        </div>

    </div>
<?php endif; ?>