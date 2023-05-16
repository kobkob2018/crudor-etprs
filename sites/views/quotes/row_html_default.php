<!--desktop_only-->
<div class="quote-wrap row">	
	<div class="span2 col col-first">
		<!-- if image -->
			<div class="quote-img-wrap">
				<img class="quote-img" title="{{label}}" src="{{img_url}}" />
			</div>
		<!-- endif -->
	</div>
	<div class="span6  col">								
		<div class="quote-title">
		{{label}}
		</div>

		<div class="quote-content">
		{{description}}
		</div>
	</div>
	<div class="span2  col">								
		<div class="quote-price">
			₪{{price}}
		</div>

		<div class="quote-price-text">
		{{price_text}}
		</div>	
	</div>
	
	<div class="span2  col col-last">
		<!-- if link -->
		<div class="quote-link">
			<a href="{{link}}" title="לנציג">
				<span class="whitelink nounderline">
				לנציג
				</span>
			</a>
			
		</div>
		<!-- endif -->
	</div>
	
</div>	
<!--end_desktop_only-->		


<!--mobile_only-->
<div class="quote-wrap">	
	
	<div class="span2 col col-first col-right">
		<!-- if image -->
			<div class="quote-img-wrap">
				<img class="quote-img" title="{{label}}" src="{{img_url}}" />
			</div>
		<!-- endif -->
	</div>
	
	<div class="span2 col col-last col-left">								
		<div class="quote-title">
		{{label}}
		</div>
	
		<div class="quote-price">
			₪{{price}}
		</div>	
	</div>
	<div style="clear:both;height:15px;"></div>
	
	<div class="service_offer_phone service_offer_rightcol">
	<!-- if phone -->
		<a href="tel:{{phone}}"><i class="fa fa-phone"></i>&nbsp;<b>התקשר</b></a>
	<!-- endif -->	
	</div>
	
	<div class="service_offer_link service_offer_leftcol">
	<!-- if link -->
		<a href="{{link}}" title="לנציג">
			<span class="whitelink nounderline">
			לנציג
			</span>
		</a>
	<!-- endif -->	
	</div>

	
	
	<div style="clear:both;"></div>
	
</div>
<!--end_mobile_only-->		