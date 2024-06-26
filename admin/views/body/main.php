<div id="page_wrap" class="page-wrap">
	<div class="header">
		<div id="logo_wrap" class="">
			<img src="style/image/logo.png" alt="מערכת הלידים של איי-אל-ביז" />
		</div>	
		<div class="admin-title-wrap">
			<?php if($this->data['work_on_site']): ?>
				<h2 class="admin-title">ניהול אתר <?= $this->data['work_on_site']['title'] ?></h2>
			<?php else: ?>
				<h2 class="admin-title">ניהול אתרים - איי אל ביז</h2>
			<?php endif; ?>	
			
			<?php if($view->user_is('master_admin')): ?>
				<a href="<?= inner_url('master_admin/',array('system')) ?>" title="מעבר לניהול אתרים">
					ניהול ראשי
				</a>
			<?php endif; ?>
		</div>
		<div class="clear"></div>	
	</div>

    <div id="middle_wrap" class="row-fluid">
        <div id="right_bar_wrap" class="page-bar right-bar">
            <?php $this->include_view('page_bars/right_bar.php'); ?>
        </div>
        <div id="center_bar_wrap" class="page-bar center-bar">
            <?php $this->call_module('system_messages','show'); ?>
			<div id="content_wrap">
				<?php $this->print_action_output(); ?>
			</div>
        </div>
    </div>	
	<div id="footer" class="footer">
	<?php /*
	 © כל הזכויות שומורות <a href="http://www.ilbiz.co.il" class="copyrightBottom" title="פורטל עסקים ישראל">פורטל עסקים ישראל</a>&nbsp;&nbsp;&nbsp; <a href="http://www.il-biz.co.il" class="copyrightBottom" target="_blank" title="IL-BIZ קידום עסקים באינטרנט">IL-BIZ קידום עסקים באינטרנט</a>&nbsp;&nbsp;&nbsp; <a href="http://kidum.ilbiz.co.il/" class="copyrightBottom" target="_blank" title="קידום באינטרנט">קידום באינטרנט</a> - אילן שוורץ&nbsp;&nbsp;&nbsp; <a href="http://www.il-biz.co.il/" class="copyrightBottom" target="_blank" title="בניית אתרים">בניית אתרים</a>
	*/ 
	?>
	</div>
</div>