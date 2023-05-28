<h2>האתרים שלי</h2>
<?php if(Helper::user_is('master_admin',$this->user)): ?>
<div class="add-button-block-wrap">
    <a class="focus-box button-focus" href="/admin/site/add/">יצירת אתר חדש</a>
</div>
<?php endif; ?>
<h4>בחר אתר לעריכה</h4>
<ul> 
    <?php foreach($this->data['user_sites_link_list'] as $site): ?>
        <li>
            <a href="<?= inner_url("userSites/checkin/") ?>?workon=<?= $site['id'] ?>" title="<?= $site['title']; ?>"><?= $site['title']; ?></a>
             | 
             <a href="http://<?= $site['domain']; ?>" title="<?= $site['title']; ?>" target="_BLANK">צפה באתר</a>
        </li>
    <?php endforeach; ?>
</ul>

