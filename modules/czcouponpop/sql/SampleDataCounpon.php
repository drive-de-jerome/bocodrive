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

class SampleDataCounpon
{
	public function initData()
	{
		$return = true;
		$languages = Language::getLanguages(true);
		$id_shop = Configuration::get('PS_SHOP_DEFAULT');
		
		$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'czcouponpop` (`id_czcouponpop`, `cookies_time`, `active`) VALUES 
		(1, 864000, 1);
		');
		
		$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'czcouponpop_shop` (`id_czcouponpop`, `id_shop`, `cookies_time`, `active`) VALUES 
		(1, "'.$id_shop.'", 864000, 1);
		');
		
		$text = '<div class="innerbox-newsletter"><h3 class="newsletter_title">Subscribe Newsletter!</h3><div class="newsletter-text"><p>Subscribe to our latest newsletter to get news about special discounts and upcoming sales.</p></div></div>';
		
		foreach ($languages as $language)
		{
			$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'czcouponpop_lang` (`id_czcouponpop`, `id_shop`, `id_lang`, `content`, `background`) VALUES 
			(1, "'.$id_shop.'", "'.$language['id_lang'].'", \''.$text.'\', "newsletter_banner.jpg");
			');
		}
		return $return;
	}
}
?>