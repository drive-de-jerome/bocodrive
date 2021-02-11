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

/**
* In some cases you should not drop the tables.
* Maybe the merchant will just try to reset the module
* but does not want to loose all of the data associated to the module.
*/

$sql = array();
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'czcouponpop_lang`';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'czcouponpop_shop`';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'czcouponpop`';

foreach ($sql as $query)
	if (Db::getInstance()->execute($query) == false)
		return false;
