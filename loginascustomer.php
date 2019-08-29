<?php
/*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class LoginAsCustomer extends Module
{
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'loginascustomer';
		$this->tab = 'back_office_features';
		$this->version = '0.7.2';
		$this->author = 'PrestaShopModul';
		$this->controllers = array('login');
		
		$this->bootstrap = true;
		parent::__construct();

        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
		$this->displayName = $this->l('Login As Customer');
		$this->description = $this->l('Allows you login as customer');
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('displayAdminCustomers'))
			return false;
		return true;
	}

	public function hookDisplayAdminCustomers($request)
    {
        $customer = New CustomerCore ($request['request']->get('customerId'));
        $link = $this->context->link->getModuleLink($this->name, 'login', array('id_customer' => $customer->id, 'xtoken' => $this->makeToken($customer->id)));

        if (!Validate::isLoadedObject($customer)) {
            return;
        }
        return '<div class="col-md-3">
                <div class="card">
                  <h3 class="card-header">
                    <i class="material-icons">lock_outline</i>
                    ' . $this->l("Login As Customer") . '
                  </h3>
                  <div class="card-body">
                    <p class="text-muted text-center">
                        <a href="' . $link . '" target="_blank" style="text-decoration: none;">
                            <i class="material-icons d-block">lock_outline</i>' . $this->l("Login As Customer") . '
                        </a>
                    </p>
                  </div>
                </div>
                </div>';
    }
    
    public function makeToken($id_customer) {
        return md5(_COOKIE_KEY_.$id_customer.date("Ymd"));
    }

}
