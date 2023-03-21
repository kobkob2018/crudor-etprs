<div id="page_wrap" class="page-wrap landing-page-wrap">
	<?php $this->include_view('modules/accessibility.php'); ?>
    <?php if($this->data['page_style'] && $this->data['page_style']['header_html'] != ''): ?>
        <?= $this->data['page_style']['header_html'] ?>
    <?php endif; ?>
    <div id="page_middle" class="page-middle">
        <div id="center_bar_wrap" class="page-bar center-bar">
            <?php $this->call_module('system_messages','show'); ?>
            <?php $this->print_action_output(); ?>
        </div>
    </div>

    <?php if($this->data['page_style'] && $this->data['page_style']['footer_html'] != ''): ?>
        <?= $this->data['page_style']['footer_html'] ?>
    <?php endif; ?>
</div>