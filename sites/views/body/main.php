<?php $this->include_view('modules/accessibility.php'); ?>
<div id="page_wrap" class="page-wrap">
	<?php if($this->data['site_styling'] && $this->data['site_styling']['header_html'] != ''): ?>
        <?= $this->data['site_styling']['header_html'] ?>
	<?php else: ?>
    
		<div class="header page-fixed-top">
			<div id="logo_wrap" class="fix-top-right">
				
				<img src="<?= $this->file_url_of('logo',$this->data['site']['logo']) ?>" alt="<?= $this->data['site']['title']; ?>" />
			</div>
			<div id="top_menu_wrap" class="fix-top-center">
				<?php $this->call_module('site_menus','top_menu'); ?>
			</div>
			<div id="accessebility_and_phone" class="fix-top-left">
				<a class="accessibility-door"  href="javascript://" onclick="openDrawer('accessibility')"><span class="fa fa-wheelchair"></span></a>
				
			
			</div>
			<div class="clear"></div>	
		</div>
	<?php endif; ?>
    <div id="page_middle" class="page-middle">
        <div id="right_bar_wrap" class="page-bar right-bar">
            <?php $this->include_view('page_bars/right_bar.php'); ?>
        </div>
        <div id="center_bar_wrap" class="page-bar center-bar">
            <?php $this->call_module('system_messages','show'); ?>
            <?php $this->print_action_output(); ?>
        </div>
        <div id="left_bar_wrap" class="page-bar left-bar">
            <?php $this->include_view('page_bars/left_bar.php'); ?>
        </div>
    </div>
	
	<?php if($this->data['site_styling'] && $this->data['site_styling']['footer_html'] != ''): ?>
		<?= $this->data['site_styling']['footer_html'] ?>
	<?php else: ?>
		<div id="footer" class="footer">
		
		© <?= __tr("All rights reserved") ?> <a href="https://il-biz.co.il" class="copyrightBottom" title="<?= __tr("Israel business portal") ?>"><?= __tr("Israel business portal") ?></a>&nbsp;&nbsp;&nbsp; <a href="https://il-biz.co.il" class="copyrightBottom" target="_blank" title="<?= __tr("IL-BIZ Internet business promotion") ?>"><?= __tr("IL-BIZ Internet business promotion") ?></a>&nbsp;&nbsp;&nbsp; <a href="https://il-biz.co.il/" class="copyrightBottom" target="_blank" title="<?= __tr("Web promotion") ?>"><?= __tr("Web promotion") ?></a> - <?= __tr("Ilan Shvartz") ?>&nbsp;&nbsp;&nbsp; <a href="https://il-biz.co.il" class="copyrightBottom" target="_blank" title="<?= __tr("Websites building") ?>"><?= __tr("Websites building") ?></a>
	
		</div>
	<?php endif; ?>

</div>