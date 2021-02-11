{* 
* @Module Name: CZ CouponPop Module
* @Website: http://www.codezeel.com - prestashop template provider
* @author Codezeel <support@codezeel.com>
* @copyright  2016-2020 Codezeel 
*}

<script type="text/javascript">
    var cz_coupon_url = "{$cz_coupon_url|escape:'html':'UTF-8'}";
	var cookies_time = {$newsletter_setting.cookies_time|intval};
</script>

{if $newsletter_setting}
<div id="overlay" style="{if $cookies_time>0}display: none;{/if}" onclick="closeDialog(cookies_time)"></div>
<div id="newsletter-main" class="newsletter-main" style="{if $cookies_time>0}display: none;{/if}">
	<div class="cz-newsletter-popup-close">
		<a class="cz_close" href="javascript:void(0)" onclick="closeDialog(cookies_time)">
			<i class="material-icons clear">&#xe14c;</i>
		</a>

	</div>
	<div class="left-block">
		{if $newsletter_setting.background != ''}
		<div class="cz-newsletter-popup">
			<img src="{$newsletter_setting.background|escape:'html':'UTF-8'}">
		{else}
		<div class="cz-newsletter-popup">
		{/if}
		</div>
	</div>
	<div class="right-block">
	    <div class="inner">
	
			<div class="clearfix newsletter-content">
				{$newsletter_setting.content|escape:'quotes':'UTF-8' nofilter}
			</div>
			<div class="newsletter-form">
				<div class="newsletter-popup-form">
					<div class="input-wrapper">
						<input class="newsletter-input-email" id="newsletter_input_email" id="" type="text" name="email" size="18" placeholder="{l s='Enter your email address...' mod='czcouponpop'}" value="" />
						<a onclick="regisNewsletter()" name="submitNewsletter" class="btn btn-default button">{l s='Subscribe' mod='czcouponpop'}
							<i class="material-icons arrow_forward">&#xe5c8;</i>
						</a>
					</div>
				</div>
				<div id="regisNewsletterMessage"></div>
				
				{if $voucher_code != ''}
				<div class="coupon-side clearfix">
					<div class="coupon-wrapper clearfix">
						<div id="coupon-element" class="coupon" >
							<div class="dashed-border">
								<span id="coupon-text-before"  style="{if $show_voucher == 1}display: none;{else}display: block;{/if}">
								{l s='Your coupon code display here' mod='czcouponpop'}</span>
								<span id="coupon-text-after" style="{if $show_voucher == 1}display: block;{else}display: none;{/if}">{l s='Coupon Code' mod='czcouponpop'}: {$voucher_code|escape:'html':'UTF-8'}</span>
							</div>
						</div>
					</div>
				</div>
				{/if}
				<div class="newsletter-checkbox">                    
					<div class="checkbox">
						<input id="notshow" name="notshow" type="checkbox" value="1">
						{l s='Do not show this popup again' mod='czcouponpop'}
					</div>
				</div>
			</div>
	    </div> 
    </div>   
</div>

<script type="text/javascript">
var regisNewsletterMessage = '{l s='You have subscribled successfully!' mod='czcouponpop' js=1}';
var enterEmail = '{l s='Please enter valid email address!' mod='czcouponpop' js=1}';
</script>
{/if}
{*
<div class="cz-show-newsletter-popup {if $cookies_time>0}open{else}close{/if}">
	<div class="cz-coupon-small">
		<div class="tab-image"></div>
		<div class="shears-small"></div>
		<div class="dashes-d"></div>
		<div class="dashes-b"></div>
		<div class="share-coupon-small-wrapper"><a href="javascript: void(0)"><span>{l s='Discount' mod='czcouponpop'}</span></a></div>
	</div>
</div>
*}