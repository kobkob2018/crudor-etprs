<div class="right-bar">
    <?php if($view->controller_is("pages")): ?>
        <?php if(isset($this->data['page']['right_banner']) && $this->data['page']['right_banner']): ?>
            <div class = 'right-bar-banner'>
                <img src="<?= $this->file_url_of('right_banner', $this->data['page']['right_banner']) ?>" />
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php $this->call_module('site_menus','right_menu'); ?>
</div>