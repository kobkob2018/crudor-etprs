<h2>האתרים שלי</h2>
<?php if(Helper::user_is('master_admin',$this->user)): ?>
    <?php if($info['site_list_type'] == 'master_admin'): ?>
        <h3>רשימת כל האתרים במערכת</h3>
        
        <a href="<?= inner_url("userSites/list/") ?>" title="חזרה לרימה רגילה">
            חזור לרשימת האתרים שלי
        </a>
        
    <?php else: ?>
        <h3>
            <a href="<?= inner_url("userSites/list/?master_list=1") ?>" title="כל האתרים במערכת">
                צפה בכל האתרים הקיימים במערכת
            </a>
        </h3>
    <?php endif; ?>
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
             <?php if($info['site_list_type'] == 'master_admin'): ?>
                |        
                <a href="<?= inner_url("site_users/master_admin_add_me/") ?>?site_id=<?= $site['id'] ?>" title="הוסף אותי כמנהל ראשי">הוסף אותי כמנהל ראשי לאתר זה</a>                
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

