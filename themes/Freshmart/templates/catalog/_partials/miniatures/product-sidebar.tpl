{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 {block name='product_miniature_item'}
<div class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
	<div class="product_thumbnail">
		{block name='product_thumbnail'}
		  <a href="{$product.url}" class="thumbnail product-image">
			<img
			  class="lazyload" data-src = "{$product.cover.bySize.small_default.url}"
			  alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
			>
		  </a>
		{/block} 
	</div>

	<div class="product-info">

		{block name='product_reviews'}
			{hook h='displayProductListReviews' product=$product}
		 {/block}
		 
		{block name='product_name'}
			<h1 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:100:'...'}</a></h1>
		{/block}
	
		{block name='product_price_and_shipping'}
			{if $product.show_price}
			  <div class="product-price-and-shipping">
				{if $product.has_discount}
				  {hook h='displayProductPriceBlock' product=$product type="old_price"}
			
				  <span class="regular-price">{$product.regular_price}</span>
				  {if $product.discount_type === 'percentage'}
                  <span class="discount-percentage discount-product">{$product.discount_percentage}</span>
					{elseif $product.discount_type === 'amount'}
					  <span class="discount-amount discount-product">{$product.discount_amount_to_display}</span>
					{/if}
				{/if}
			
				{hook h='displayProductPriceBlock' product=$product type="before_price"}
			
				<span itemprop="price" class="price">{$product.price}</span>
			
				{hook h='displayProductPriceBlock' product=$product type='unit_price'}
			
				{hook h='displayProductPriceBlock' product=$product type='weight'}
			  </div>
			{/if}
		{/block}
		
		
		 
	</div>
</div>	
{/block}
