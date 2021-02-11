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

include_once _PS_MODULE_DIR_.'czcouponpop/classes/CzCouponPopClass.php';
include_once _PS_MODULE_DIR_.'czcouponpop/sql/SampleDataCounpon.php';
class CzCouponPop extends Module
{
	const GUEST_NOT_REGISTERED = -1;
	const CUSTOMER_NOT_REGISTERED = 0;
	const GUEST_REGISTERED = 1;
	const CUSTOMER_REGISTERED = 2;

	private $html = '';

	public $pathImage = '';
	public function __construct()
	{
		$this->name = 'czcouponpop';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Codezeel';
		$this->secure_key = Tools::encrypt('codezeel'.$this->name);
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->trans('CZ CouponPop Module');
		$this->description = $this->trans('CZ CouponPop Module display Newsletter Subscription Popup on Homepage');
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
		$this->pathImage = dirname(__FILE__).'/views/img/';
		$this->error = false;
		$this->valid = false;
	}
	public function install($keep = true)
	{
		Configuration::updateValue('CZ_VOUCHER_CODE','PROMO25');
		if (!parent::install() || !$this->registerHook('displayHeader') || !$this->registerHook('displayFooter')) return false;
		if (!Configuration::updateGlobalValue('MOD_CZ_COUPONPOP', '1')) return false;
		include(dirname(__FILE__).'/sql/install.php');
		$sample_data = new SampleDataCounpon();
			$sample_data->initData();
		return true;
	}

	public function uninstall($keep = true)
	{
		Configuration::deleteByName('CZ_VOUCHER_CODE');
		
		include(dirname(__FILE__).'/sql/uninstall.php');
		if (!parent::uninstall()) return false;
		if (!Configuration::deleteByName('MOD_CZ_COUPONPOP')) return false;
		return true;
	}
	public function reset()
	{
		if (!$this->uninstall(false))
			return false;
		if (!$this->install(false))
			return false;
		return true;
	}
	
	
	public function getBackgroundSrc($image = '', $check = false)
	{
		if ($image && file_exists($this->pathImage.$image))
			if (Tools::usingSecureMode())
				return _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/'.$this->name.'/views/img/'.$image;
			else
				return _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/views/img/'.$image;
		else
			if ($check == true)
				return '';
			else
				if (Tools::usingSecureMode())
					return _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/'.$this->name.'/views/img/default.jpg';
				else
					return _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/views/img/default.jpg';
	}
	public function getLangOptions($id_lang = 0)
	{
		if ((int)$id_lang == 0) $id_lang = Context::getContext()->language->id;
		$items = DB::getInstance()->executeS('Select id_lang, name, iso_code From '._DB_PREFIX_.'lang Where active = 1');
		$options = '';
		if ($items)
		{
			foreach ($items as $item)
			{
				if ($item['id_lang'] == $id_lang)
					$options .= '<option value="'.$item['id_lang'].'" selected="selected">'.$item['name'].'</option>';
				else
					$options .= '<option value="'.$item['id_lang'].'">'.$item['name'].'</option>';
			}
		}
		return $options;
	}
	public function getAllLangs()
	{
		return $items = DB::getInstance()->executeS('Select id_lang, name, iso_code From '._DB_PREFIX_.'lang Where active = 1 Order By id_lang');
	}
	private function _displayContact()
{
		$this->html .= '
		<br/>
	 	<fieldset>
			<legend><img src="'.$this->_path.'views/img/help.png" alt="" title="" /> '.$this->trans('Help').'</legend>		
			For customizations or assistance, please contact: <strong><a   target="_blank" href="https://codezeel.freshdesk.com/">https://codezeel.com/contact</a></strong>
			<br>
			<a href="https://codezeel.com/" alt="codezeel" title="codezeel" target="_blank">https://codezeel.com/</a>
		</fieldset>';
		return $this->html;
}
	public function _displayAdvertising()
	{
		$this->html .= '
		<br/>
	 	<fieldset>
			<legend>'.$this->trans('Help').'</legend>		
			For customizations or assistance, please contact: <strong><a   target="_blank" href="https://codezeel.freshdesk.com/">https://codezeel.com/contact</a></strong>
			<br>
		</fieldset>
		<br/>
		<fieldset>
			<legend>'.$this->trans("More Themes & Modules").'</legend>	
			<iframe src="https://codezeel.com/" width="100%" height="420px;" border="0" style="border:none;"></iframe>
		</fieldset>';
		return $this->html;
	}
	public function getContent()
	{
		return $this->postProcess().$this->initForm().$this->_displayAdvertising();
	}
	public function postProcess()
	{		
		if (Tools::isSubmit('saveCoupon'))
		{
			$languageDefault = Configuration::get('PS_LANG_DEFAULT');
			$languages = Language::getLanguages(false);
			Configuration::updateValue('CZ_VOUCHER_CODE', Tools::getValue('cz_voucher_code'));
			$coupon = new CzCouponPopClass(Tools::getValue('id_czcouponpop'));
			$coupon->cookies_time = Tools::getValue('cookies_time');
			$coupon->active = Tools::getValue('active');
			if ($coupon->validateFields(false) && $coupon->validateFieldsLang(false))
			{
				foreach ($languages as $lang)
				{
					$coupon->content[$lang['id_lang']] = Tools::getValue('content_'.$lang['id_lang']);
					
					if (isset($_FILES['background_'.$lang['id_lang']]) && isset($_FILES['background_'.$lang['id_lang']]['tmp_name']) && !empty($_FILES['background_'.$lang['id_lang']]['tmp_name']))
					{
						$id_shop = $this->context->shop->id;
						if ($error = ImageManager::validateUpload($_FILES['background_'.$lang['id_lang']]))
							return false;
						elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['background_'.$lang['id_lang']]['tmp_name'], $tmpName))
							return false;
						elseif (!ImageManager::resize($tmpName, dirname(__FILE__).'/views/img/coupon-'.$id_shop.'-'.$lang['id_lang'].'.jpg'))
							return false;
						unlink($tmpName);
						$coupon->background[$lang['id_lang']] = 'coupon-'.$id_shop.'-'.$lang['id_lang'].'.jpg';
					}
					
				}
				if (!$coupon->update())
					return $this->displayError($this->trans('The slide could not be updated.'));
				Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
			}
			else
				return '<div class="conf error">'.$this->trans('An error occurred while attempting to save.').'</div>';
			
		}
	}
	public function initForm()
	{
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		$id_czcouponpop = 1;
		if ($id_czcouponpop)
			$coupon = new CzCouponPopClass((int)$id_czcouponpop);
		else
			$coupon = new CzCouponPopClass();
		
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->trans('Coupon Popup'),
			),
			'input' => array(
				array(
					'type' => 'textarea',
					'label' => $this->trans('Content:'),
					'lang' => true,
					'name' => 'content',
					'autoload_rte' => true,
					'cols' => 40,
					'rows' => 10
				),
				array(
					'type' => 'file_lang',
					'label' => $this->trans('Background:'),
					'name' => 'background',
					'lang' => true
				),
				array(
						'type' => 'text',
						'label' => $this->trans('Voucher Code:'),
						'lang' => false,
						'name' => 'cz_voucher_code',
					),		
				array(
					'type' => 'text',
					'label' => $this->trans('Cookies time:'),
					'name' => 'cookies_time'
				),				
			),
			'submit' => array(
				'title' => $this->trans('Save')
			)
		);
		
		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'czcouponpop';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		foreach (Language::getLanguages(false) as $lang)
			$helper->languages[] = array(
				'id_lang' => $lang['id_lang'],
				'iso_code' => $lang['iso_code'],
				'name' => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
			);
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'saveCoupon';
		$helper->toolbar_btn = array(
			'save' =>
			array(
				'desc' => $this->trans('Save'),
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			)
		);
		foreach (Language::getLanguages(false) as $lang)
		{
			$helper->fields_value['content'][(int)$lang['id_lang']] = Tools::getValue('content_'.(int)$lang['id_lang'], $coupon->content[(int)$lang['id_lang']]);
			$helper->fields_value['background'][(int)$lang['id_lang']] = Tools::getValue('background_'.(int)$lang['id_lang'], $coupon->background[(int)$lang['id_lang']]);
		}
			
		if (Tools::getValue('active', $coupon->active) != '')
			$active = Tools::getValue('active', $coupon->active);
		else
			$active = 1;
		$helper->fields_value['active'] = $active;
		
		
		$helper->fields_value['cz_voucher_code'] = Configuration::get('CZ_VOUCHER_CODE');
		
		if (Tools::getValue('cookies_time', $coupon->cookies_time) != '')
			$cookies_time = Tools::getValue('cookies_time', $coupon->cookies_time);
		else
			$cookies_time = 864000;
		
		$helper->fields_value['cookies_time'] = $cookies_time;
		if ($id_czcouponpop)
		{
			$this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_czcouponpop');
			$helper->fields_value['id_czcouponpop'] = (int)Tools::getValue('id_czcouponpop', $coupon->id_czcouponpop);	
		}
		
		$helper->tpl_vars = array(
			'uri' => $this->getPathUri(),
			'fields_value' => $helper->fields_value,
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper->generateForm($this->fields_form);
	}
	
	public function hookdisplayHeader()
	{
		$page_name = Dispatcher::getInstance()->getController();
		if ($page_name != 'index') return false;
		$this->context->controller->addCSS(($this->_path).'views/css/czcouponpop.css');
		$this->context->controller->addJS(($this->_path).'views/js/czcouponpop.js');
	}
	
	protected function _prepareHook($params)
	{
		if (Tools::isSubmit('submitNewsletter'))
		{
			$email = Tools::getValue('email');
			$action = Tools::getValue('action');
			$this->newsletterRegistration();
			if ($this->error)
			{
				$this->smarty->assign(
					array(
						'color' => 'red',
						'msg' => $this->error,
						'nw_value' => isset($email) ? pSQL($email) : false,
						'nw_error' => true,
						'action' => $action
					)
				);
			}
			else if ($this->valid)
			{
				$this->smarty->assign(
					array(
						'color' => 'green',
						'msg' => $this->valid,
						'nw_error' => false
					)
				);
			}
		}
		$this->smarty->assign('this_path', $this->_path);
	}
	protected function newsletterRegistration()
	{
		$email = Tools::getValue('email');
		$action = Tools::getValue('action');
		if (empty($email) || !Validate::isEmail($email))
			return $this->error = $this->trans('Invalid email address.');

		/* Unsubscription */
		else if ($action == '1')
		{
			$register_status = $this->isNewsletterRegistered($email);

			if ($register_status < 1)
				return $this->error = $this->trans('This email address is not registered.');

			if (!$this->unregister($email, $register_status))
				return $this->error = $this->trans('An error occurred while attempting to unsubscribe.');

			return $this->valid = $this->trans('Unsubscription successful.');
		}
		/* Subscription */
		else if ($action == '0')
		{
			$register_status = $this->isNewsletterRegistered($email);
			if ($register_status > 0)
				return $this->error = $this->trans('This email address is already registered.');

			$email = pSQL($email);
			if (!$this->isRegistered($register_status))
			{
				if (Configuration::get('NW_VERIFICATION_EMAIL'))
				{
					// create an unactive entry in the newsletter database
					if ($register_status == self::GUEST_NOT_REGISTERED)
						$this->registerGuest($email, false);

					if (!$token = $this->getToken($email, $register_status))
						return $this->error = $this->trans('An error occurred during the subscription process.');

					//$this->sendVerificationEmail($email, $token);

					return $this->valid = $this->trans('A verification email has been sent. Please check your inbox.');
				}
				else
				{
					if ($this->register($email, $register_status))
						$this->valid = $this->trans('You have successfully subscribed to this newsletter.');
					else
						return $this->error = $this->trans('An error occurred during the subscription process.');

					//if ($code = Configuration::get('CZ_VOUCHER_CODE'))
						//$this->sendVoucher($email, $code);

					//if (Configuration::get('NW_CONFIRMATION_EMAIL'))
						//$this->sendConfirmationEmail($email);
				}
			}
		}
	}
	

	
	public function hookdisplayFooter()
	{
		$page_name = Dispatcher::getInstance()->getController();
		if ($page_name != 'index') return false;
		if (isset(Context::getContext()->cookie->notshow))
			$notshow = Context::getContext()->cookie->notshow;
		else
			$notshow = 0;
		if (isset(Context::getContext()->cookie->show_voucher))
			$show_voucher = Context::getContext()->cookie->show_voucher;
		else
			$show_voucher = 0;
		if (isset(Context::getContext()->cookie->cookies_time))
			$cookies_time = Context::getContext()->cookie->cookies_time;	
		else
			$cookies_time = 0;
		
		if ($notshow >= 0)
		{
			
				$id_lang = (int)Context::getContext()->language->id;
				$id_shop = (int)Context::getContext()->shop->id;
				
				$sql = 'SELECT cpl.*, cps.*
						FROM `'._DB_PREFIX_.'czcouponpop` cp
						LEFT JOIN `'._DB_PREFIX_.'czcouponpop_shop` cps ON (cp.`id_czcouponpop` = cps.`id_czcouponpop`)
						LEFT JOIN `'._DB_PREFIX_.'czcouponpop_lang` cpl ON (cps.`id_czcouponpop` = cpl.`id_czcouponpop`)	
						WHERE cpl.`id_shop` = '.$id_shop.' AND cpl.`id_lang` = '.$id_lang.' AND cp.`id_czcouponpop` = 1';
				$item = DB::getInstance()->getRow($sql);	
				if ($item)
					if ($item['background']) $item['background'] = $this->getBackgroundSrc($item['background'], true);
				else
					$item = array();
				if (Tools::usingSecureMode())
					$cz_coupon_url = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/'.$this->name;
				else
					$cz_coupon_url = _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name;
				
				if ($code = Configuration::get('CZ_VOUCHER_CODE'))
					$voucher_code = $code;
				else
					$voucher_code = '';
				
				$this->context->smarty->assign(
				array(
					'newsletter_setting' => $item,
					'cz_coupon_url' => $cz_coupon_url,
					'voucher_code' => $voucher_code,
					'show_voucher' => $show_voucher,
					'cookies_time' => $cookies_time
					)
				);
			
			return $this->display(__FILE__, 'czcouponpop.tpl');
		}
		else
			return false;
	}
	public function clearCache($name = null)
	{
		parent::_clearCache('czcouponpop.tpl');
	}

	protected function isNewsletterRegistered($customer_email)
	{
		$sql = 'SELECT `email`
				FROM '._DB_PREFIX_.'emailsubscription
				WHERE `email` = \''.pSQL($customer_email).'\'
				AND id_shop = '.$this->context->shop->id;

		if (Db::getInstance()->getRow($sql))
			return self::GUEST_REGISTERED;
	
		$sql = 'SELECT `newsletter`
				FROM '._DB_PREFIX_.'customer
				WHERE `email` = \''.pSQL($customer_email).'\'
				AND id_shop = '.$this->context->shop->id;

		if (!$registered = Db::getInstance()->getRow($sql))
			return self::GUEST_NOT_REGISTERED;
		

		if ($registered['newsletter'] == '1')
			return self::CUSTOMER_REGISTERED;
		
		return self::CUSTOMER_NOT_REGISTERED;
	}
	protected function unregister($email, $register_status)
	{
		$email = Tools::getValue('email');
		if ($register_status == self::GUEST_REGISTERED)
			$sql = 'DELETE FROM '._DB_PREFIX_.'emailsubscription WHERE `email` = \''.pSQL($email).'\' AND id_shop = '.$this->context->shop->id;
		else if ($register_status == self::CUSTOMER_REGISTERED)
			$sql = 'UPDATE '._DB_PREFIX_.'customer SET `newsletter` = 0 WHERE `email` = \''.pSQL($email).'\' AND id_shop = '.$this->context->shop->id;

		if (!isset($sql) || !Db::getInstance()->execute($sql))
			return false;

		return true;
	}
	protected function isRegistered($register_status)
	{
		return in_array(
			$register_status,
			array(self::GUEST_REGISTERED, self::CUSTOMER_REGISTERED)
		);
	}
	protected function registerGuest($email, $active = true)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.'emailsubscription (id_shop, id_shop_group, email, newsletter_date_add, ip_registration_newsletter, http_referer, active)
				VALUES
				('.$this->context->shop->id.',
				'.$this->context->shop->id_shop_group.',
				\''.pSQL($email).'\',
				NOW(),
				\''.pSQL(Tools::getRemoteAddr()).'\',
				(
					SELECT c.http_referer
					FROM '._DB_PREFIX_.'connections c
					WHERE c.id_guest = '.(int)$this->context->customer->id.'
					ORDER BY c.date_add DESC LIMIT 1
				),
				'.(int)$active.'
				)';

		return Db::getInstance()->execute($sql);
	}
	protected function getToken($email, $register_status)
	{
		if (in_array($register_status, array(self::GUEST_NOT_REGISTERED, self::GUEST_REGISTERED)))
		{
			$sql = 'SELECT MD5(CONCAT( `email` , `newsletter_date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\')) as token
					FROM `'._DB_PREFIX_.'newsletter`
					WHERE `active` = 0
					AND `email` = \''.pSQL($email).'\'';
		}
		else if ($register_status == self::CUSTOMER_NOT_REGISTERED)
		{
			$sql = 'SELECT MD5(CONCAT( `email` , `date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\' )) as token
					FROM `'._DB_PREFIX_.'customer`
					WHERE `newsletter` = 0
					AND `email` = \''.pSQL($email).'\'';
		}

		return Db::getInstance()->getValue($sql);
	}
	public function newsletterRegistrationAjax()
	{
		$response = new stdClass();
		$response->status = 0;
		$email = Tools::getValue('email');
		$action = Tools::getValue('action');
		
		if (empty($email) || !Validate::isEmail($email))
			return $this->trans('Invalid email address.');
		else if ($action == '1')/* Unsubscription */
		{
			
			$register_status = $this->isNewsletterRegistered($email);

			if ($register_status < 1)
				return $this->trans('This email address is not registered.');

			if (!$this->unregister($email, $register_status))
				return $this->trans('An error occurred while attempting to unsubscribe.');

			return $this->trans('Unsubscription successful.');
		}
		else if ($action == '0')/* Subscription */
		{
			
			$register_status = $this->isNewsletterRegistered($email);
			
			if ($register_status > 0)
				return $this->trans('This email address is already registered.');
			
			$email = pSQL($email);
			if (!$this->isRegistered($register_status))
			{
				/*if (Configuration::get('NW_VERIFICATION_EMAIL'))
				{
					if ($register_status == self::GUEST_NOT_REGISTERED)
						$this->registerGuest($email, false);
					if (!$token = $this->getToken($email, $register_status))
						return $this->trans('An error occurred during the subscription process.');
					$this->sendVerificationEmail($email, $token);
					return $this->trans('A verification email has been sent. Please check your inbox.');
				}*/
				//else
				//{
					if (!$this->register($email, $register_status))
						return $this->trans('An error occurred during the subscription process.');
					else
					{
						//if ($code = Configuration::get('CZ_VOUCHER_CODE'))
							//$this->sendVoucher($email, $code);
						//if (Configuration::get('NW_CONFIRMATION_EMAIL'))
							//$this->sendConfirmationEmail($email);
						return 'sussess@'.$this->trans('You have successfully subscribed to this newsletter.');
					}	
				//}
			}
			else
				return $this->trans('You have not subscribed to this newsletter.');
		}
	}
	protected function sendConfirmationEmail($email)
	{
		return Mail::Send($this->context->language->id, 'newsletter_conf', Mail::l('Newsletter confirmation', $this->context->language->id), array(), pSQL($email), null, null, null, null, null, _PS_ROOT_DIR_.'/modules/blocknewsletter/mails/', false, $this->context->shop->id);
	}
	protected function sendVoucher($email, $code)
	{
		return Mail::Send($this->context->language->id, 'newsletter_voucher', Mail::l('Newsletter voucher', $this->context->language->id), array('{discount}' => $code), $email, null, null, null, null, null, _PS_ROOT_DIR_.'/modules/blocknewsletter/mails/', false, $this->context->shop->id);
	}
	protected function register($email, $register_status)
	{
		if ($register_status == self::GUEST_NOT_REGISTERED)
			return $this->registerGuest($email);

		if ($register_status == self::CUSTOMER_NOT_REGISTERED)
			return $this->registerUser($email);

		return false;
	}
	protected function registerUser($email)
	{
		$sql = 'UPDATE '._DB_PREFIX_.'customer
				SET `newsletter` = 1, newsletter_date_add = NOW(), `ip_registration_newsletter` = \''.pSQL(Tools::getRemoteAddr()).'\'
				WHERE `email` = \''.pSQL($email).'\'
				AND id_shop = '.$this->context->shop->id;

		return Db::getInstance()->execute($sql);
	}
	protected function sendVerificationEmail($email, $token)
	{
		$verif_url = Context::getContext()->link->getModuleLink('czcouponpop', 'verification', array('token' => $token,));
		return Mail::Send($this->context->language->id, 'newsletter_verif', Mail::l('Email verification', $this->context->language->id), array('{verif_url}' => $verif_url), $email, null, null, null, null, null, _PS_ROOT_DIR_.'/modules/blocknewsletter/mails/', false, $this->context->shop->id);
	}
	protected function getGuestEmailByToken($token)
	{
		$sql = 'SELECT `email`
				FROM `'._DB_PREFIX_.'emailsubscription`
				WHERE MD5(CONCAT( `email` , `newsletter_date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\')) = \''.pSQL($token).'\'
				AND `active` = 0';

		return Db::getInstance()->getValue($sql);
	}
	public function activateGuest($email)
	{
		return Db::getInstance()->execute(
			'UPDATE `'._DB_PREFIX_.'emailsubscription`
						SET `active` = 1
						WHERE `email` = \''.pSQL($email).'\''
		);
	}
	protected function getUserEmailByToken($token)
	{
		$sql = 'SELECT `email`
				FROM `'._DB_PREFIX_.'customer`
				WHERE MD5(CONCAT( `email` , `date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\')) = \''.pSQL($token).'\'
				AND `newsletter` = 0';

		return Db::getInstance()->getValue($sql);
	}
	public function confirmEmail($token)
	{
		$activated = false;
		if ($email = $this->getGuestEmailByToken($token))
			$activated = $this->activateGuest($email);
		else if ($email = $this->getUserEmailByToken($token))
			$activated = $this->registerUser($email);

		if (!$activated)
			return $this->trans('This email is already registered and/or invalid.');

		if ($discount = Configuration::get('CZ_VOUCHER_CODE'))
			$this->sendVoucher($email, $discount);

		if (Configuration::get('NW_CONFIRMATION_EMAIL'))
			$this->sendConfirmationEmail($email);

		return $this->trans('Thank you for subscribing to our newsletter.');
	}
}