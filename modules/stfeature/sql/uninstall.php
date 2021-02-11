<?php
/**
 * 2010-2019 Codezeel
 *
 * NOTICE OF LICENSE
 *
 * Tm feature for prestashop 1.7: compare, wishlist at product list 
 *
 * DISCLAIMER
 *
 *  @Module Name: CZ Feature
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

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
