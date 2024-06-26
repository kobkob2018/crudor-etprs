<?php if($this->data['page_layout'] != '1'): ?>
    <div id="content_title_wrap" class="title-wrap flex-row flex-wrap">
        <h1 id="content_title" class="main-title grow-1 color-title"><?= $this->data['page']['title']; ?></h1>
        <div id="share_buttons_wrap">
            <?php $this->call_module('share_buttons','print'); ?>
        </div>
    </div>
<?php endif; ?>
<div id="content_wrap">
    <?php foreach($this->data['content_blocks'] as $content_block): ?>
        <?php if($content_block['css_class'] != "nowrap"): ?>
            <div class="page-block <?= $content_block['css_class'] ?>">
                <?= $content_block['content'] ?>
                <div class="clear"></div>
            </div>
        <?php else: ?>
            <?= $content_block['content'] ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>