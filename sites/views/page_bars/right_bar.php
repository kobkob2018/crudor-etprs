<div class="right-bar">
    <div class="grab-content right-top-grabber" data-grab="go-right-top"></div>
    <?php if(isset($this->data['is_home_page']) && $this->data['is_home_page']): ?>
        <?php $this->call_module('scrolling_news','print'); ?>
    <?php endif; ?>
    <?php if($view->controller_is("pages")): ?>
        <?php if(isset($this->data['page']['right_banner']) && $this->data['page']['right_banner']): ?>
            <div class = 'right-bar-banner'>
                <img src="<?= $this->file_url_of('right_banner', $this->data['page']['right_banner']) ?>" />
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(!isset($this->data['is_portal_view'])): ?>
        <?php $this->call_module('site_menus','right_menu'); ?>
    <?php else: ?>
        <?php $this->call_module('site_menus','portal_menu'); ?>
    <?php endif; ?>
    <?php if(isset($this->data['is_home_page']) && !$this->data['is_home_page']): ?>
        <?php if($this->data['site_styling']['add_scrolling_requests'] == '1'): ?>
            <?php $this->call_module('scrolling_last_requests','print'); ?>
        <?php endif; ?>
    <?php endif; ?>
    <div class="grab-content right-bot-grabber" data-grab="go-right-bot"></div>
</div>