<?php foreach($this->data['page_blocks'] as $block): ?>
    <div class="page-content-block" data-block_id="<?= $block['id'] ?>">
        <?= $block['content'] ?>
    </div>
<?php endforeach; ?>