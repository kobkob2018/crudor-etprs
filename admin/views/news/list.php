<h2>חדשות האתר</h2>

<div class="add-item-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('news/add/') ?>">הוספת חדשה</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-tiny">מיקום</div>
        <div class="col">כותרת</div>
        <div class="col">תוכן</div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['news_list'] as $news_post): ?>
        <div class="table-tr row">
            <div class="col col-tiny">
                <?= $news_post['priority'] ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('news/edit/') ?>?row_id=<?= $news_post['id'] ?>" title="ערוך חדשה"><?= $news_post['label'] ?></a>
            </div>
            


            <div class="col">
                <?= nl2br($news_post['content']) ?>
                <hr/>
                <div class="col-addition">
                    <b>לינק: </b><br/>
                    <?= $news_post['link'] ?>
                </div>

            </div>
            <div class="col">
                <a href = "<?= inner_url('news/delete/') ?>?row_id=<?= $news_post['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

