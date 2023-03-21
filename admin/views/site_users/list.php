<div class="user-list">
    <h2>מנהלי אתר</h2>
    <p>
        <a href="<?= inner_url("siteUsers/add/") ?>">הוספת מנהל</a>
    </p>
    <?php foreach($this->data['site_user_list'] as $site_user): ?>
        <div class="site-user">
            <?php if($site_user['id'] != $this->user['id']): ?>
                <a href="<?= inner_url('siteUsers/view/') ?>?row_id=<?= $site_user['id'] ?>"><?= $site_user['user_name'] ?></a>
            <?php else: ?>
                <b><?= $site_user['user_name'] ?></b>
            <?php endif; ?>
             - <?= $site_user['roll_title'] ?>
        </div>
    <?php endforeach; ?>
</div>