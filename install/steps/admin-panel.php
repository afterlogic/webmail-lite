<?php

// api
include_once WM_INSTALLER_PATH.'../libraries/afterlogic/api.php';

class CAdminpanelStep extends AInstallerStep
{
	/**
	 * @var api_Settings
	 */
	protected $oSettings;

	public function __construct()
	{
		$this->oSettings =& CApi::GetSettings();
	}

	public function DoPost()
	{
		if (isset($_POST['next_btn']))
		{
			if (5 > strlen(trim(CPost::Get('txtPassword1', ''))))
			{
				CSession::Set('wm_install_pass_error', 'Minimum password length is 5 characters.');
			}
			else if (CPost::Get('txtPassword1', '') !== CPost::Get('txtPassword2', ''))
			{
				CSession::Set('wm_install_pass_error', 'The password and its confirmation don\'t match.');
			}
			else
			{
				$this->oSettings->SetConf('Common/AdminPassword', md5(CPost::Get('txtPassword1', '')));
				return $this->oSettings->SaveToXml();
			}
		}
		
		return false;
	}

	public function TemplateValues()
	{
		$sFootError = '';
		if (CSession::Has('wm_install_pass_error'))
		{
			$sFootError = CSession::Get('wm_install_pass_error', '');
			CSession::Clear('wm_install_pass_error');
		}

		return array(
			'Login' => $this->oSettings->GetConf('Common/AdminLogin'),
			'Password1' => '',
			'Password2' => '',
			'FootError' => $sFootError,
		);
	}
}