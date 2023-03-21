<div class="request-success-wrap">
    <b>בקשתך נשלחה ל <?= $info['recivers'] ?> נותני שירות</b>
    <br/>
    <a href = "<?= inner_url("biz_request/view/") ?>">לחץ כאן כדי לצפות בנותני השירות</a>
    <?php if(isset($info['thanks_pixel'])): ?>
        <?= $info['thanks_pixel'] ?>
    <?php endif; ?>
</div>