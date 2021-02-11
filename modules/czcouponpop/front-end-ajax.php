<?php
/**
 * 2016-2020 Codezeel
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 *  @Module Name: CZ CouponPop Module
 *  @author    codezeel <support@codezeel.com>
 *  @copyright 2010-2019 codezeel
 *  @license   http://www.codezeel.com - prestashop template provider
 */

//require_once(dirname(__FILE__).'../../../config/config.inc.php');
//require_once(dirname(__FILE__).'../../../init.php');
//require_once(dirname(__FILE__).'/czcouponpop.php');

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('czcouponpop.php');

$module = new CzCouponPop();
$task = Tools::getValue('task');
if ($task == 'cancelRegisNewsletter')
{		
	$notshow = (int)Tools::getValue('notshow', 0);
	$cookies_time = Tools::getValue('cookies_time', 0);
	
	
	if ($notshow == '1')
	{
		Context::getContext()->cookie->__set('cookies_time', $cookies_time);
		Context::getContext()->cookie->__set('notshow', $notshow);
	}
	else
	{
		Context::getContext()->cookie->__set('notshow', $notshow);
	}
	
	die(Tools::jsonEncode('1'));
}
if ($task == 'showPopup')
{		
	Context::getContext()->cookie->__set('notshow', 0);
	Context::getContext()->cookie->__set('cookies_time', 0);
	die(Tools::jsonEncode('1'));
}
if ($task == 'regisNewsletter')
{
	$result = $module->newsletterRegistrationAjax();
	
	//$notshow = (int)Tools::getValue('notshow', 0);
	//Context::getContext()->cookie->__set('notshow', $notshow);
	//Context::getContext()->cookie->__set('show_voucher', 1);
	
	die(Tools::jsonEncode($result));
}