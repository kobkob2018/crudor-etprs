<div class="user-list">
    <h2>מנהלי אתר</h2>
    <p>
        <a href="<?= inner_url("site_users/add/") ?>">הוספת מנהל</a>
    </p>

    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col"></div>
            <div class="col">שם הלקוח</div>
            <div class="col">תפקיד מנהל</div>
            <div class="col">סטטוס</div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['site_users_list'] as $site_user): ?>
            <div class="table-tr row">
                <div class="col">
                    <?php if($site_user['user_id'] != $this->user['id']): ?>
                        <a href = "<?= inner_url('site_users/edit/') ?>?row_id=<?= $site_user['id'] ?>" title="ערוך">
                            <?= $this->get_label_value('user_id', $site_user,$info['fields_colection']) ?>
                        </a>
                    <?php else: ?>
                        <b><?= $this->get_label_value('user_id', $site_user,$info['fields_colection']) ?></b>
                    <?php endif; ?>
                </div>

                <div class="col">
                    <?= $this->get_label_value('roll', $site_user,$info['fields_colection']) ?>
                </div>       
                <div class="col">
                <?= $this->get_label_value('status', $site_user,$info['fields_colection']) ?>
                </div>        
                <div class="col">
                    <?php if($site_user['user_id'] != $this->user['id']): ?>
                        <a href = "<?= inner_url('site_users/delete/') ?>?row_id=<?= $site_user['id'] ?>" title="מחק">מחק</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>