<div id="page_wrap" class="page-wrap">
	<?php $this->include_view('modules/accessibility.php'); ?>
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
        © כל הזכויות שומורות <a href="http://www.ilbiz.co.il" class="copyrightBottom" title="פורטל עסקים ישראל">פורטל עסקים ישראל</a>&nbsp;&nbsp;&nbsp; <a href="http://www.il-biz.com" class="copyrightBottom" target="_blank" title="IL-BIZ קידום עסקים באינטרנט">IL-BIZ קידום עסקים באינטרנט</a>&nbsp;&nbsp;&nbsp; <a href="http://kidum.ilbiz.co.il/" class="copyrightBottom" target="_blank" title="קידום באינטרנט">קידום באינטרנט</a> - אילן שוורץ&nbsp;&nbsp;&nbsp; <a href="http://www.il-biz.co.il/" class="copyrightBottom" target="_blank" title="בניית אתרים">בניית אתרים</a>
    <?php endif; ?>
</div>