<div id = "right_bar">
    <?php if($view->user_is('login')): ?>
        <h4>hello <?= $this->user['full_name']; ?></h4>
    <?php endif; ?>
    <ul class="item-group">
        <?php if(!$this->user): ?>
            <li class="bar-item  <?= $view->a_class("userLogin/login/") ?>"">
                <a href = "<?= inner_url("userLogin/login/"); ?>" class="a-link <?= $view->a_class("userLogin/login/") ?>">כניסה למערכת</a>
            </li>
            <li class="bar-item  <?= $view->a_class("userLogin/register/") ?>">
                <a href = "<?= inner_url("userLogin/register/"); ?>" class="a-link <?= $view->a_class("userLogin/register/") ?>">הרשמה</a> 
            </li>
        <?php endif; ?>
        <?php if($view->user_is('login')): ?>
            
            <li class="bar-item"><a href="<?= inner_url("userLogin/logout/") ?>" class="a-link">יציאה</a></li>
            
            <li class="bar-item <?= $view->a_class("tasks/list/") ?>">
                <a href="<?= inner_url('tasks/list/') ?>" title="המשימות שלי" class="a-link">המשימות שלי</a>
            </li>            
            
        <?php endif; ?>
                
        <?php if($view->user_is('master_admin')): ?>
            <li class="bar-item <?= $view->a_class("global_settings/edit/") ?>">
                <a href="<?= inner_url('global_settings/edit/') ?>" title="הגדרות כלליות" class="a-link">הגדרות כלליות</a>
            </li> 



            <li class="bar-item <?= $view->a_c_class("cities") ?>">
                <a href="<?= inner_url('cities/list/') ?>" title="ניהול ערים ואזורים" class="a-link">ניהול ערים ואזורים</a>
            </li> 

            <li class="bar-item <?= $view->a_c_class("languages","language_messages") ?>">
                <a href="<?= inner_url('languages/list/') ?>" title="ניהול תרגומים" class="a-link">ניהול תרגומים</a>
            </li> 

            <li class="bar-item <?= $view->a_c_class("biz_categories") ?>">
                <a href="<?= inner_url('biz_categories/list/') ?>" title="ניהול קטגוריות בפורטל" class="a-link">ניהול קטגוריות</a>
            </li> 

            <li class="bar-item <?= $view->a_c_class("users") ?>">
                <a href="<?= inner_url('users/list/') ?>" title="ניהול משתמשים" class="a-link">ניהול משתמשים</a>
            </li> 

            <li class="bar-item <?= $view->a_c_class("net_directories, net_banners") ?>">
                <a href="<?= inner_url('net_directories/list/') ?>" title="ניהול באנרים" class="a-link">ניהול באנרים</a>
            </li> 

            <li class="bar-item <?= $view->a_c_class("supplier_cubes") ?>">
                <a href="<?= inner_url('supplier_cubes/list/') ?>" title="ניהול קוביות ספקים" class="a-link">קוביות ספקים</a>
            </li> 


            <li class="bar-item <?= $view->a_c_class("refund_reasons") ?>">
                <a href="<?= inner_url('refund_reasons/list/') ?>" title="סיבות זיכוי" class="a-link">סיבות זיכוי</a>
            </li> 

            <li class="bar-item <?= $view->a_class("net_messages/list/") ?>">
                <a href="<?= inner_url('net_messages/list/') ?>" title="הודעות רשת" class="a-link">הודעות רשת</a>
            </li> 

            <li class="bar-item <?= $view->a_class('content/find_in_sites/') ?>">
                <a href="<?= inner_url('content/find_in_sites/') ?>" title="חפש ערך בכל האתרים" class="a-link">חפש ערך בכל האתרים</a>
            </li> 

            <h4>
                שיגורי תשלום
            </h4>

            <li class="bar-item <?= $view->a_class('user_lounch_fee/list/') ?>">
                <a href="<?= inner_url('user_lounch_fee/list/') ?>" title="שיגורי התשלום שלא שולמו" class="a-link">שיגורי התשלום שלא שולמו</a>
            </li> 

            <h4>ניהול לידים</h4>
            <li class="bar-item <?= $view->a_class('biz_requests/list/') ?>">
                <a href="<?= inner_url('biz_requests/list/?reset_filter=1') ?>" title="בקשות להצעת מחיר" class="a-link">בקשות להצעת מחיר</a>
            </li> 

            <li class="bar-item <?= $view->a_class('leads_backup/list/') ?>">
                <a href="<?= inner_url('leads_backup/list/?reset_filter=1') ?>" title="לידים ישנים - גיבוי" class="a-link">לידים ישנים - חיפוש בגיבויים</a>
            </li> 

            <li class="bar-item <?= $view->a_class('biz_requests/spam_list/') ?>">
                <a href="<?= inner_url('biz_requests/spam_list/?reset_spam_filter=1') ?>" title="ספאם" class="a-link">ספאם</a>
            </li> 
            <li class="bar-item <?= $view->a_class("users_leads/list/") ?>">
                <a href="<?= inner_url('users_leads/list/') ?>" title="לידים ללקוחות שנבחרו" class="a-link">לידים ללקוחות שנבחרו</a>
            </li> 
            <li class="bar-item <?= $view->a_class("refund_requests/list/") ?>">
                <a href="<?= inner_url('refund_requests/list/') ?>" title="בקשות לזיכויים" class="a-link">בקשות לזיכויים</a>
            </li> 
            <li class="bar-item <?= $view->a_class("missing_user_phones/list/") ?>">
                <a href="<?= inner_url('missing_user_phones/list/') ?>" title="מספרי טלפון חסרים" class="a-link">מספרי טלפון חסרים</a>
            </li>
            <li class="bar-item <?= $view->a_class("call_monitor/misscalls_comments/") ?>">
                <a href="<?= inner_url('call_monitor/misscalls_comments/') ?>" title="שיחות שלא הפכו לליד" class="a-link">שיחות שלא הפכו לליד</a>
            </li>

            <li class="bar-item <?= $view->a_class("monthly_income/report/") ?>">
                <a href="<?= inner_url('monthly_income/report/') ?>" title="דוח הכנסות חודשיות" class="a-link">דוח הכנסות חודשיות</a>
            </li>

            <li class="bar-item <?= $view->a_class("daily_income/report/") ?>">
                <a href="<?= inner_url('daily_income/report/') ?>" title="דוח הכנסות יומיות" class="a-link">דוח הכנסות יומיות</a>
            </li>

            <li class="bar-item <?= $view->a_c_class("leads_user_get/report/") ?>">
                <a href="<?= inner_url('leads_user_get/report/') ?>" title="מספור קבלת לידים ללקוח" class="a-link">מספור קבלת לידים ללקוח</a>
            </li>
            <h4>- - -</h4>
            <li class="bar-item <?= $view->a_class("domain_redirections/list/") ?>">
                <a href="<?= inner_url('domain_redirections/list/') ?>" title="הפניות כלליות לדומיינים" class="a-link">הפניות כלליות לדומיינים</a>
            </li>

            <h4>ווטסאפ</h4>
            <li class="bar-item <?= $view->a_c_class("whatsapp_conversations","whatsapp_messages") ?>">
                <a href="<?= inner_url('whatsapp_conversations/list/') ?>" title="שיחות ווטסאפ" class="a-link">שיחות ווטסאפ</a>
            </li>
            
        <?php endif; ?>

    </ul>
</div>