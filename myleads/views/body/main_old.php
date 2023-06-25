<div id="page_wrap" class="page-wrap">
	<div class="header">
		<div id="logo_wrap" class="">
			<img src="style/image/logo.png" alt="מערכת הלידים של איי-אל-ביז" />
		</div>	
		<h2 class="admin-title">מערכת הלידים של איי-אל-ביז</h2>
		
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
		<a href="https://ilbiz.co.il" class="copyrightBottom" title="איי אל ביז - קידום עסקים באינטרנט">
			<img src = "<?= styles_url('style/image/logo-il-biz.png') ?>" alt="איי אל ביז" />
			

		</a> 
		&nbsp;&nbsp;&nbsp;
		<a href="https://ilbiz.co.il" class="copyrightBottom" title="איי אל ביז - קידום עסקים באינטרנט">
			קידום עסקים באינטרנט
		</a> 
	</div>
</div>