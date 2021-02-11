{* 
* @Module Name: CZ Feature
* @Website: http://www.codezeel.com - prestashop template provider
* @author Codezeel <support@codezeel.com>
* @copyright  2010-2019 Codezeel
* @description: CZ Feature for prestashop 1.7: ajax cart, review, compare, wishlist at product list 
*}
<div class="compare">
	<a class="st-compare-button btn-product btn{if $added} added{/if}" href="#" data-id-product="{$leo_compare_id_product}" title="{if $added}{l s='Remove from Compare' mod='stfeature'}{else}{l s='Add to Compare' mod='stfeature'}{/if}">
		{*<span class="leo-compare-bt-loading cssload-speeding-wheel"></span>*}
		<span class="st-compare-bt-content">
			<i class="fa fa-area-chart"></i>
			{l s='Add to Compare' mod='stfeature'}
		</span>
	</a>
</div>