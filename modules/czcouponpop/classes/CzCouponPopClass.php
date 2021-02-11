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

class CzCouponPopClass extends ObjectModel
{
	public $id_czcouponpop;
	public $cookies_time;
	public $background;
	public $content;
	public $active;
	
	public $temp_url = '{czcouponpop_url}';
	public static $definition = array(
		'table' => 'czcouponpop',
		'primary' => 'id_czcouponpop',
		'multilang' => true,
		'multilang_shop' => true,
		'fields' => array(
			'content' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
			'background' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isFileName'),
			'cookies_time'  => 		array('type' => self::TYPE_INT,'shop' => true),
			'active'  => 		array('type' => self::TYPE_BOOL,'shop' => true)
		)
	);
	
	public	function __construct($id = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id, $id_lang, $id_shop);
		if ($this->id)
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
				foreach ($this->fieldsValidateLang as $field => $validation)
				{	
					if (isset($this->{$field}[(int)($language['id_lang'])]))
					{
						$temp = str_replace($this->temp_url, _PS_BASE_URL_.__PS_BASE_URI__, $this->{$field}[(int)($language['id_lang'])]);
						$this->{$field}[(int)($language['id_lang'])] = $temp;
					}
				}
		}
		Shop::addTableAssociation('czcouponpop', array('type' => 'shop'));
		Shop::addTableAssociation('czcouponpop_lang', array('type' => 'fk_shop'));
	}
}
