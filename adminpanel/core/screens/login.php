<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class ap_Login_Screen extends ap_Simple_Screen
{
	/**
	 * @param CAdminPanel $oAdminPanel
	 * @return ap_Login_Screen
	 */
	public function __construct(CAdminPanel &$oAdminPanel)
	{
		parent::__construct($oAdminPanel, 'login.php');

		$sError = '';
		if (isset($_GET['auth_error']))
		{
			$sError = AP_LANG_LOGIN_AUTH_ERROR;
		}
		else if (isset($_GET['sess_error']))
		{
			$sError = AP_LANG_LOGIN_SESS_ERROR;
		}
		else if (isset($_GET['access_error']))
		{
			$sError = AP_LANG_LOGIN_ACCESS_ERROR;
		}

		if (0 < strlen($sError))
		{
			$this->Data->SetValue('LoginErrorDesc', '<div class="wm_login_error"><div class="wm_login_error_icon"></div><div class="wm_login_error_message" id="login_error_message">'.$sError.'</div></div>');
		}

		$this->JsAddInitText('$(\'#loginId\').focus();');
		$this->CssAddFile('static/styles/screens/login.css');

		$this->Data->SetValue('AdminLogin', CApi::GetConf('demo.adminpanel.login', ''));
		$this->Data->SetValue('AdminPassword', CApi::GetConf('demo.adminpanel.password', ''));

		if (CApi::GetConf('demo.adminpanel.enable', false))
		{
			$this->Data->SetValue('LoginDemoFooter', '<div class="info" id="demo_info" dir="ltr">
<div class="demo_note">
This is a demo version of administrative interface. <br />For WebMail demo interface, click <a href="..">here</a>.
</div>
</div>');
		}
	}
}
