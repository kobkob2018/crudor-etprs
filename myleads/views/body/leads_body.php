<div id="page_wrap" class="page-wrap">
	<div class="header">
		<div id="logo_wrap" class="">
			<img src="style/image/logo.png" alt="מערכת הלידים של איי-אל-ביז" />
		</div>
		<div id="filter_door" class="header-item">
			<a href="javascript://" onClick="open_close_filter(this)" class="closed">
				<img src="style/image/Search-icon.png" alt="מערכת הלידים של איי-אל-ביז" style="width:30px; border: 5px solid transparent;"/>
			</a>
		</div>		
		<?php if($this->user): ?>
			<div id="header_links" class="header-right-menu">
				<a id="header_lead_list_link" class="header-link header-item" href='<?= inner_url("leads/list/") ?>'><span class="bg"></span><span class="header-link-title">הלידים שלי</span></a>
			</div>
			<div id="left_menu" class="header-left-menu">
				<div id="user_menu" class="header-item">
					<div id="header_usermenu_wrap">
					  <a id="header_usermenu_door" href="javascript://" rel="closed" onClick="open_close_usermenu()">
						<span class="bg"></span><span class="user-name"><?= $this->user['full_name']; ?></span>
					  </a>


					  <div id="usermenu_wrap" style="display:none;">
						<div id="usermenu_content">
							<h4><?= $this->user['full_name']; ?></h4>
							<h5><?= $this->user['username']; ?></h5>
							<ul>
								<li><a href="<?= inner_url("credits/buy_leads/") ?>">רכישת לידים</a></li>
								<li><a href="<?= inner_url("payments/list/") ?>">תשלומים אחרונים</a></li>
								<li><a href="<?= inner_url("user/details/") ?>">עדכון פרטים</a></li>
								<?php if($this->user['have_net_banners']): ?>
									<li><a href="<?= inner_url("reports/banners/") ?>" ?>">הבאנרים שלי</a></li>
								<?php endif; ?>
								<li><a href="<?= inner_url("userLogin/logout/") ?>">יציאה</a></li>
							</ul>
						</div>
					  </div>
					</div>						
				</div>
				<div id="notifications_menu" class="header-item">
					<div id="header_notifications_wrap">
						  <a id="header_notifications_door" href="javascript://" data-touched='0' rel="closed" onClick="open_close_notifications()">
							
						  </a>

						  <div id="notifications_wrap" style="display:none;">
							<div id="notifications_content">
								<div id="notifications_recived_content">
								
								</div>
							</div>
						  </div>
						  <div id="notifications_bugger_wrap" style="display:none;">
							<div id="notifications_bugger_content">
								קיימות הודעות מערכת.
								<br/>
								 <a href="javascript://" onClick="hide_notifications_bugger()">
									לחץ כאן לצפייה 
								</a>
							 
							</div>
						  </div>						  
					</div>						
				</div>					
			</div>
			<script type="text/javascript">
				check_notifications_interval();
			</script>
		<?php elseif(false): ?>
			<a href = "<?= inner_url("userLogin/login/"); ?>">כניסה למערכת</a>
		  	<a href = "<?= inner_url("userLogin/register/"); ?>">הרשמה</a>
		  	<a href = "<?= inner_url(""); ?>">דף הבית</a>
		<?php endif; ?>
		
		<?php $this->include_view('leads/all_messages.php'); ?>
		<div class="clear"></div>	
	</div>
	<div class="header-space-keeper"></div>
	<div id="content_wrap">
		<?php $this->print_action_output(); ?>
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