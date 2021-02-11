{*
* 2007-2017 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="block_newsletter col-md-4 links block">
    <h4 class="main_heading title"><span class="news1">{l s='Subscribe Now' d='Shop.Theme.Global'}</span></h4>
    <span class="sub_heading">{l s='Join us for get latest updates' d='Shop.Theme.Global'}</span>
	<div class="block_content">
      <form action="{$urls.pages.index}#footer" method="post">
          <div class="newsletter-form">
            <input
              class="btn btn-primary pull-xs-right hidden-xs-down"
              name="submitNewsletter"
              type="submit"
              value="{l s='Subscribe' d='Shop.Theme.Actions'}"
            >
            <input
              class="btn btn-primary pull-xs-right hidden-sm-up"
              name="submitNewsletter"
              type="submit"
              value="{l s='OK' d='Shop.Theme.Actions'}"
            >
            <div class="input-wrapper">
              <input
                name="email"
                type="text"
                value="{$value}"
                placeholder="{l s='Your email address' d='Shop.Forms.Labels'}"
              >
            </div>
            <input type="hidden" name="action" value="0">
            <div class="clearfix"></div>
          </div>
          {if $msg}
			<p class="alert {if $nw_error}alert-danger{else}alert-success{/if}">
			  {$msg}
			</p>
		  {/if}
		  <div class="newsletter-message">
              {if $conditions}
                <p>{$conditions}</p>
              {/if}
          </div>
		  {if isset($id_module)}
			{hook h='displayGDPRConsent' id_module=$id_module}
		  {/if}
      </form>
    </div>
</div>
