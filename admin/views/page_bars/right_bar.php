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
            
            
            
            <li class="bar-item <?= $view->a_class("user/details/") ?>"><a href="<?= inner_url("user/details/") ?>" class="a-link">עדכון פרטים</a></li>
            <li class="bar-item"><a href="<?= inner_url("userLogin/logout/") ?>" class="a-link">יציאה</a></li>
            
            
            
        <?php endif; ?>
            
            
        <?php if($view->user_is('login')): ?>
            <li class="bar-item <?= $view->a_class("userSites/list/") ?>">
                <a href="<?= inner_url('userSites/list/') ?>" title="האתרים שלי" class="a-link">האתרים שלי</a>
            </li>
            <?php endif; ?>
            
            <?php if($view->site_user_is('author')): ?>
                <li class="bar-item <?= $view->a_class("tasks/list/") ?>">
                    <a href="<?= inner_url('tasks/list/') ?>" title="המשימות שלי" class="a-link">המשימות שלי</a>
            </li>
        <?php endif; ?>
            

            
        <?php if($view->site_user_is('master_admin')): ?>
                <li class="bar-item <?= $view->a_class("site_users/list/") ?>">
                    <a href="<?= inner_url('site_users/list/') ?>" title="מנהלי אתר" class="a-link">מנהלי אתר</a>
                </li>        
        <?php endif; ?>

    </ul>

    <?php if($view->site_user_is('master_admin')): ?>
        <h4>עיצוב האתר</h4>
        <ul class="item-group">
            <li class="bar-item <?= $view->a_class("site/edit/") ?>">
                <a href="<?= inner_url('site/edit/') ?>" title="ניהול" class="a-link">ניהול</a>
            </li>  
            <li class="bar-item <?= $view->a_c_class("site_styling/list/") ?>">
                <a href="<?= inner_url('site_styling/list/') ?>" title="מבנה הדף" class="a-link">מבנה הדף</a>
            </li>  
            <li class="bar-item <?= $view->a_c_class("site_colors/edit/") ?>">
                <a href="<?= inner_url('site_colors/edit/') ?>" title="צבעים" class="a-link">צבעים</a>
            </li>         
        </ul>
    <?php endif; ?>
    <?php if($view->site_user_is('admin')): ?>
        <ul class="item-group">

            <li class="bar-item <?= $view->a_class("news/list/") ?> <?= $view->a_c_class("news") ?>">
                <a href="<?= inner_url('news/list/') ?>" title="חדשות האתר" class="a-link">חדשות האתר</a>
            </li>
            
        </ul>
        <h4>ניהול תפריטים</h4>
        <ul class="item-group">

            <li class="bar-item <?= $view->a_class("menus/right_menu/") ?>">
                <a href="<?= inner_url('menus/right_menu/') ?>" title="תפריט ימני" class="a-link">תפריט ימני</a>
            </li>
            <li class="bar-item <?= $view->a_class("menus/top_menu/") ?>">

                <a href="<?= inner_url('menus/top_menu/') ?>" title="תפריט עליון" class="a-link">תפריט עליון</a>
            </li>
            <li class="bar-item <?= $view->a_class("menus/bottom_menu/") ?>">
       
                <a href="<?= inner_url('menus/bottom_menu/') ?>" title="תפריט תחתון" class="a-link">תפריט תחתון</a>
            </li>
            <li class="bar-item <?= $view->a_class("menus/hero_menu/") ?>">
       
                <a href="<?= inner_url('menus/hero_menu/') ?>" title="תפריט הירו" class="a-link">תפריט הירו</a>
            </li>           
        </ul>

        <ul class="item-group">

            <li class="bar-item <?= $view->a_class("pages/list/") ?> <?= $view->a_c_class("pages, blocks") ?>">
                <a href="<?= inner_url('pages/list/') ?>" title="דפים באתר" class="a-link">דפים באתר</a>
            </li>
            <?php if($view->controller_is("pages") || $view->controller_is("blocks")): ?>
                <li class="bar-item child-item <?= $view->a_class("pages/add/") ?>">
                    <a href="<?= inner_url('pages/add/') ?>" title="דף חדש" class="a-link">דף חדש</a>
                </li>
            <?php endif; ?>          
        </ul>


        <ul class="item-group">

            <?php if($view->user_is('master_admin')): //can switch to site_user_is.. ?>
                <li class="bar-item <?= $view->a_class("quote_cats/list/") ?> <?= $view->a_c_class("quote_cats, quotes") ?>">
                    <a href="<?= inner_url('quote_cats/list/') ?>" title="הצעות מחיר" class="a-link">הצעות מחיר</a>
                </li>
            <?php endif; ?>
            
            <li class="bar-item <?= $view->a_class("product_cats/list/") ?> <?= $view->a_c_class("product_cats, products") ?>">
                <a href="<?= inner_url('product_cats/list/') ?>" title="מוצרים" class="a-link">ניהול מוצרים</a>
            </li>
        </ul>

        <ul class="item-group">

            <li class="bar-item <?= $view->a_class("gallery_images/list/") ?> <?= $view->a_c_class("gallery_images") ?>">
                <a href="<?= inner_url('gallery_images/gallery_list/') ?>" title="גלריות" class="a-link">גלריות</a>
            </li>

        </ul>

        <ul class="item-group">

            <li class="bar-item <?= $view->a_class("redirections/list/") ?>">
                <a href="<?= inner_url('redirections/list/') ?>" title="הפניות 301" class="a-link">הפניות 301</a>
            </li>

        </ul>
    <?php endif; ?>

    <?php if($view->site_user_is('master_admin')): ?>
        <h4>העלאת קבצים</h4>
        <ul class="item-group">

            <li class="bar-item <?= $view->a_class("files/library/") ?>">
                <a href="<?= inner_url('files/library/') ?>" title="העלאות קבצים" class="a-link">ספריית העלאות</a>
            </li>

        </ul>
    <?php endif; ?>
    <ul class="item-group">
        <?php if($view->site_user_is('author')): //can switch to site_user_is.. ?>
            <li class="bar-item <?= $view->a_class("quotes/my_list/") ?> <?= $view->a_class("quotes/my_list/") ?>">
               <h3>תוכן אישי באתר</h3>
            </li>
        <?php endif; ?>
        <?php if($view->site_user_can('quotes')): //can switch to site_user_is.. ?>
            <li class="bar-item <?= $view->a_class("quotes/my_list/") ?> <?= $view->a_class("quotes/my_list/") ?>">
                <a href="<?= inner_url('quotes/my_list/') ?>" title="הצעות המחיר שלי" class="a-link">הצעות המחיר שלי</a>
            </li>
        <?php endif; ?>
        <?php if($view->site_user_can('pages')): //can switch to site_user_is.. ?>
            <li class="bar-item <?= $view->a_class("pages/list/") ?> <?= $view->a_c_class("pages, blocks") ?>">
                <hr/>
                <a href="<?= inner_url('pages/list/') ?>" title="דפים באתר" class="a-link">דפים באתר</a>
            </li>
            <?php if($view->controller_is("pages") || $view->controller_is("blocks")): ?>
                <li class="bar-item child-item <?= $view->a_class("pages/add/") ?>">
                    <a href="<?= inner_url('pages/add/') ?>" title="דף חדש" class="a-link">דף חדש</a>
                    <hr/>
                </li>
            <?php endif; ?>  
        <?php endif; ?>
    </ul>
</div>