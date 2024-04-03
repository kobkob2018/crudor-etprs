<h2>תבניות ווטסאפ</h2>

<div class="add-item-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('whatsapp_templates/add/') ?>">הוספת תבנית</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-tiny">#</div>
        <div class="col">שם התבנית</div>
        <div class="col">תוכן</div>
        <div class="col">תמונה</div>
        <div class="col">וידאן</div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['whatsapp_templates_list'] as $template): ?>
        <div class="table-tr row">
            <div class="col col-tiny">
                <?= $template['id'] ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('whatsapp_templates/edit/') ?>?row_id=<?= $template['id'] ?>" title="ערוך תבנית"><?= $template['label'] ?></a>
            </div>

            <div class="col">
                <?= nl2br($template['content']) ?>
            </div>

            <div class="col">
                <?php if($template['header_image'] != ""): ?>
                    <img src = "<?= $template['header_image'] ?>" alt="תמונת ראש" style="max-width:200px; "/>
                <?php endif; ?>
            </div>
            <div class="col">
                <?php if($template['header_video'] != ""): ?>
                    <img src = "<?= $template['header_video'] ?>" alt="תמונת ראש" style="max-width:200px; "/>
                <?php endif; ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('whatsapp_templates/delete/') ?>?row_id=<?= $template['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

