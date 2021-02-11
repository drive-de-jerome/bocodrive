{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="czcategorytabs" class="tabs products_block clearfix">
	<div class="categorytab block">
		<h2 class="h1 products-section-title text-uppercase">{l s='Latest Collection' mod='cz_categoryslider'}</h2>
		<div class="czcategory-tab">
			<ul id="czcategory-tabs" class="nav nav-tabs clearfix">
				{$count=0}
				{foreach from=$czcategorysliderinfos item=czcategorysliderinfo}
					<li class="nav-item">
						<a href="#tab_{$czcategorysliderinfo.id}" data-toggle="tab" class="nav-link {if $count == 0}active{/if}">
						<span class="category-title">{$czcategorysliderinfo.name}</span>
						</a>
					</li>
					{$count= $count+1}
				{/foreach}
			</ul>
		</div> 
		
		<div class="tab-content">
			{$tabcount=0}
			{foreach from=$czcategorysliderinfos item=czcategorysliderinfo}
				<div id="tab_{$czcategorysliderinfo.id}" class="tab-pane {if $tabcount == 0}active{/if}">
					{if isset($czcategorysliderinfo.product) && $czcategorysliderinfo.product}
						{assign var='sliderFor' value=5}
						{assign var='productCount' value=count($czcategorysliderinfo.product)}
						{if $slider == 1 && $productCount >= $sliderFor}
							<div class="czcategory-double-slide products row">
								<ul id="czcategory{$czcategorysliderinfo.id}-carousel" class="cz-carousel product_list product_slider_grid" data-catid="{$czcategorysliderinfo.id}">
									{foreach from=$czcategorysliderinfo.product item='product' name=czCategoryProducts}
										{if $smarty.foreach.czCategoryProducts.index % 2 == 0} 
										<li class="double-slideitem">
											<ul>
											   {/if}	
											   <li class="item">
													{include file="catalog/_partials/miniatures/product.tpl" product=$product}
											   </li>
											   {if $smarty.foreach.czCategoryProducts.index % 2 != 0} 
											</ul>
										</li>
										{/if}
									{/foreach}
								</ul>		
							
								<div class="customNavigation">
									<a class="btn prev czcategory_prev">&nbsp;</a>
									<a class="btn next czcategory_next">&nbsp;</a>
								</div>
							</div>
						{else}
							<div class="products row">
								<ul id="czcategory{$czcategorysliderinfo.id}" class="product_list grid row gridcount" data-catid="{$czcategorysliderinfo.id}">
									{foreach from=$czcategorysliderinfo.product item='product'}
										<li class="product_item col-xs-12 col-sm-6 col-md-4 col-lg-3">
											{include file="catalog/_partials/miniatures/product.tpl" product=$product}
										</li>
									{/foreach}
								</ul>
							</div>
							
							{if $slider == 0}				
								<div class="view_more">
									<a class="all-product-link" href="{$link->getCategoryLink($czcategorysliderinfo.id, $czcategorysliderinfo.link_rewrite)|escape:'html':'UTF-8'}">
										{l s='View All Products' mod='cz_categoryslider'}
									</a>
								</div>
							{/if}
							
						{/if}	
					{else}
						<div class="alert alert-info">{l s='No Products in current category at this time.' mod='cz_categoryslider'}</div>
					{/if}	
			</div> 
			{$tabcount= $tabcount+1}
			{/foreach}
			
		</div>
	
	</div> 
</div>