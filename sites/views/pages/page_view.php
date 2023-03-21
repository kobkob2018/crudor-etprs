<div id="content_title_wrap" class="title-wrap flex-row flex-wrap">
    <h1 id="content_title" class="main-title grow-1 color-title"><?= $this->data['page']['title']; ?></h1>
    <div id="share_buttons_wrap">
        <?php $this->call_module('share_buttons','print'); ?>
    </div>
</div>
<div id="content_wrap">
    <?php foreach($this->data['content_blocks'] as $content_block): ?>
        <div class="page-block <?= $content_block['css_class'] ?>">
            <?= $content_block['content'] ?>
            <div class="clear"></div>
        </div>
    <?php endforeach; ?>
</div>