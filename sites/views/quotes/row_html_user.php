<!--desktop_only-->
<div class="quote-wrap row">	
	<div class="col col-first">
		<!-- if image -->
			<div class="quote-img-wrap">
				<img class="quote-img" title="{{label}}" src="{{img_url}}" />
			</div>
		<!-- endif -->
	</div>
	<div class="col">								
		<h3 class="quote-title color-title">
			{{label}}
		</h3>

		<div class="quote-content smaller">
		{{description}}
		</div>
	</div>
	<div class="col">		
		<!-- if price -->						
		<h4 class="quote-price color-title">
			₪{{price}}
		</h4>
		<!-- endif -->
		<!-- if price_text -->
		<div class="quote-price-text smaller">
		{{price_text}}
		</div>	
		<!-- endif -->
	</div>
	
	<div class="col col-last">
		<!-- if link -->
		<div class="quote-link">
			<a class="color-button"  href="{{link}}" title="<?= __tr("To representative") ?>">
				<span class="whitelink nounderline">
				<?= __tr("To representative") ?>
				</span>
			</a>
			
		</div>
		<!-- endif -->
	</div>
	
</div>	
<!--end_desktop_only-->		


<!--mobile_only-->
<div class="quote-wrap mobile-quote-wrap">	
	<div class="row">

	
		<div class="span2 col col-first col-right">
			<!-- if image -->
				<div class="quote-img-wrap">
					<img class="quote-img" title="{{label}}" src="{{img_url}}" />
				</div>
			<!-- endif -->
		</div>
		
		<div class="span2 col col-last col-left">								
			<h3 class="quote-title color-title">
				{{label}}
			</h3>
		
			<div class="quote-price color-b">
				₪{{price}}
			</div>	
		</div>
	</div>
	<div class="row">
		<div class="quote-phone col col-right">
		<!-- if phone -->
			<a class="quote-phone-link"  href="tel:{{phone}}"><span class="fa fa-phone color-button"></span>&nbsp;<b class="color-b"><?= __tr("Call") ?></b></a>
		<!-- endif -->	
		</div>
		
		<div class="quote-link col col-left">
		<!-- if link -->
			<a class="color-button" href="{{link}}" title="<?= __tr("To representative") ?>">
				<span class="whitelink nounderline">
				<?= __tr("To representative") ?>
				</span>
			</a>
		<!-- endif -->	
		</div>
	</div>
</div>
<!--end_mobile_only-->		