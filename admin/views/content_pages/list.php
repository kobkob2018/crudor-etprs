<h3>דפים באתר</h3>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">עדכון דף</div>
        <div class="col">צפייה</div>
        <div class="col">מחיקה</div>
        <div class="col">בחירה</div>
    </div>
    <?php foreach($this->data['content_pages'] as $content_page): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('blocks/list/') ?>?page_id=<?= $content_page['id'] ?>" title="ערוך דף"><?= $content_page['title'] ?></a>
            </div>
            <div class="col">
                <a href = "<?= $this->data['work_on_site']['url'] ?>/<?= $content_page['link'] ?>/" target="_BLANK" title="צפה באתר">צפה באתר</a>
            </div>
            <div class="col">
                <a href = "<?= inner_url('pages/delete/') ?>?row_id=<?= $content_page['id'] ?>" title="מחק">מחק</a>
            </div>
            <div class="col">בחירה</div>
        </div>
    <?php endforeach; ?>
</div>

