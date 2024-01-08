<?php $this->include_view('modules/accessibility.php'); ?>
<div id="page_wrap" class="page-wrap">
	
    <?php if($this->data['page_style'] && $this->data['page_style']['header_html'] != ''): ?>
        <?= $this->data['page_style']['header_html'] ?>

    <?php elseif($this->data['site_styling'] && $this->data['site_styling']['header_html'] != ''): ?>
        <?= $this->data['site_styling']['header_html'] ?>
	<?php else: ?>
          
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
    <?php if($this->data['page_style'] && $this->data['page_style']['footer_html'] != ''): ?>
        <?= $this->data['page_style']['footer_html'] ?>
    <?php elseif($this->data['site_styling'] && $this->data['site_styling']['footer_html'] != ''): ?>
		<?= $this->data['site_styling']['footer_html'] ?>
	<?php else: ?>
        © <?= __tr("All rights reserved") ?> <a href="https://il-biz.co.il" class="copyrightBottom" title="<?= __tr("Israel business portal") ?>"><?= __tr("Israel business portal") ?></a>&nbsp;&nbsp;&nbsp; <a href="https://il-biz.co.il" class="copyrightBottom" target="_blank" title="<?= __tr("IL-BIZ Internet business promotion") ?>"><?= __tr("IL-BIZ Internet business promotion") ?></a>&nbsp;&nbsp;&nbsp; <a href="http://kidum.ilbiz.co.il/" class="copyrightBottom" target="_blank" title="<?= __tr("Web promotion") ?>"><?= __tr("Web promotion") ?></a> - <?= __tr("Ilan Shvartz") ?>&nbsp;&nbsp;&nbsp; <a href="https://il-biz.co.il" class="copyrightBottom" target="_blank" title="<?= __tr("Websites building") ?>"><?= __tr("Websites building") ?></a>
    <?php endif; ?>
</div>