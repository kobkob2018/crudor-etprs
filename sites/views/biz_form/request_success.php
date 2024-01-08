<div class="request-success-wrap">
    <b><?= __tr("Your request sent to $1 service providers", array($info['recivers'])) ?></b>
    <br/>
    <a href = "<?= inner_url("biz_request/view/?r=".$info['reuqest_id']) ?>"><?= __tr("Click here to watch providers") ?></a>
    <?php if(isset($info['thanks_pixel'])): ?>
        <?= $info['thanks_pixel'] ?>
    <?php endif; ?>
</div>