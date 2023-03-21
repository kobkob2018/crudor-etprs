<?php $this->include_view("content_pages/header.php"); ?>

<?php if(isset($this->data['set_priority_item'])): ?>
    <?php $set_priority_item = $this->data['set_priority_item']; ?>
    <div class = 'move-item-wrap alt-box'>
        <h3>העברת בלוק <?= $set_priority_item['label'] ?></h3>
        <p>בחר מיקום ולחץ "העבר לכאן"</p>
        <div class='bottom-buttons'>
                <div class='cancel-button-wrap'>
                    <a class='cancel-button' href="<?= inner_url('blocks/set_priority/') ?>?page_id=<?= $this->data['page_info']['id'] ?>&cancel=1">ביטול</a> 
                </div>
        </div>

    </div>
<?php endif; ?>


<h4>תוכן מחולק לבלוקים</h4>
<div class="content-blocks">
    <?php if(!isset($this->data['set_priority_item'])): ?>
        <div class="add-item-wrap">
            <a class="focus-box button-focus" href="<?= inner_url('blocks/add/') ?>?page_id=<?= $this->data['page_info']['id'] ?>">הוספת בלוק</a>
        </div>
    <?php endif; ?>
    <?php foreach($this->data['content_blocks'] as $content_block): ?>
        <?php if(isset($this->data['set_priority_item'])): ?>
            <div class="block-priority-move-here">
                <a class="focus-box sub-focus button-focus" href = "<?= inner_url('blocks/set_priority/') ?>?page_id=<?= $this->data['page_info']['id'] ?>&row_to=<?= $content_block['id'] ?>" title="ערוך">העבר לכאן</a>
            </div>
        <?php endif; ?>
        <div class="content-block">
            <div class="block-label">
                <?= $content_block['label'] ?> [<?= $content_block['css_class'] ?>]
            </div>
            <div class="block-content">
                <?= $content_block['content'] ?>
                <div class='clear'></div>
            </div>
            <?php if(!isset($this->data['set_priority_item'])): ?>
                <div class="block-buttons">
                    <div class="block-edit-button">
                        <a href = "<?= inner_url('blocks/edit/') ?>?page_id=<?= $this->data['page_info']['id'] ?>&row_id=<?= $content_block['id'] ?>" title="ערוך">ערוך</a>
                    </div>
                    
                        <div class="block-priority-button">
                            <a href = "<?= inner_url('blocks/set_priority/') ?>?page_id=<?= $this->data['page_info']['id'] ?>&row_id=<?= $content_block['id'] ?>" title="ערוך">העבר מיקום</a>
                        </div>
                    
                    <div class="block-delete-button">
                        <a href = "<?= inner_url('blocks/delete/') ?>?page_id=<?= $this->data['page_info']['id'] ?>&row_id=<?= $content_block['id'] ?>" title="מחק">מחק</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if(isset($this->data['set_priority_item'])): ?>
        <div class="block-priority-move-here">
            <a href = "<?= inner_url('blocks/set_priority/') ?>?page_id=<?= $this->data['page_info']['id'] ?>&row_to=-1" title="ערוך">העבר לכאן</a>
        </div>
    <?php endif; ?>
</div>
<?php if($this->data['page_style']): ?>
    <?= $this->data['page_style']['styling_tags'] ?>
<?php endif; ?>