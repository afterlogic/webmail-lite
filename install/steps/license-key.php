<?php

// api
include_once WM_INSTALLER_PATH.'../libraries/afterlogic/api.php';

class CLicensekeyStep extends AInstallerStep
{
	/**
	 * @var api_Settings
	 */
	protected $oSettings;

	/**
	 * @var CApiLicensingManager
	 */
	protected $oApiLicensing;

	public function __construct()
	{
		$this->oSettings =& CApi::GetSettings();
		$this->oApiLicensing = CApi::Manager('licensing');

		if (!isset($_SESSION['wm_install_t']))
		{
			$sKey = @file_exists(WM_INSTALLER_PATH.'KEY') ? @file_get_contents(WM_INSTALLER_PATH.'KEY') : '';
			if ($this->oApiLicensing && 0 === strlen($this->oApiLicensing->GetLicenseKey()))
			{
				if (empty($sKey))
				{
					$this->oApiLicensing->UpdateLicenseKey($this->oApiLicensing->GetT());
				}
				else
				{
					$this->oApiLicensing->UpdateLicenseKey($sKey);
					if (11 === $this->oApiLicensing->GetLicenseType())
					{
						$this->oApiLicensing->UpdateLicenseKey($this->oApiLicensing->GetT());
					}
				}
			}
		}
	}

	public function DoPost()
	{
		if (CPost::Has('txtLicenseKey'))
		{
			$_SESSION['wm_install_t'] = CPost::Get('txtLicenseKey', '');
			$this->oApiLicensing->UpdateLicenseKey($_SESSION['wm_install_t']);
			return $this->oApiLicensing->IsValidKey();
		}
		
		return false;
	}

	public function TemplateValues()
	{
		$sKey = $this->oApiLicensing->GetLicenseKey();
		
		$sText = $this->oApiLicensing->IsValidKey() ? '' : 'Please specify valid license key.';
		$sText = $this->oApiLicensing->GetVersion() == -1 
			? 'This license key is outdated, please contact us to upgrade your license key.' : $sText;
		
		return array(
			'Key' => $sKey,
			'LicenseKeyText' => $sText,
			'LicenseKeyUrl' => $this->oApiLicensing->IsAU() ?
				'http://www.afterlogic.com/purchase/aurora' : 'http://www.afterlogic.com/purchase/webmail-pro',
			'TrialClass' => (in_array($this->oApiLicensing->GetLicenseType(), array(10, 11))) ? '' :' wm_hide'
		);
	}
}