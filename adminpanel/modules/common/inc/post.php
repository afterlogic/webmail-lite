<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CCommonPostAction extends ap_CoreModuleHelper
{
	public function SystemDb()
	{
		if (CApi::GetCsrfToken('p7admToken') === CPost::Get('txtToken'))
		{
			if (CApi::GetConf('mailsuite', false))
			{
				$this->oSettings->SetConf('Common/DBType', EDbType::MySQL);
			}
			else
			{
				$this->oSettings->SetConf('Common/DBType',
					EnumConvert::FromPost(CPost::Get('radioSqlType'), 'EDbType'));
			}

			if (CPost::Has('txtSqlLogin'))
			{
				$this->oSettings->SetConf('Common/DBLogin', CPost::Get('txtSqlLogin'));
			}
			if (CPost::Has('txtSqlPassword') &&
				AP_DUMMYPASSWORD !== (string) CPost::Get('txtSqlPassword'))
			{
				$this->oSettings->SetConf('Common/DBPassword', CPost::Get('txtSqlPassword'));
			}
			if (CPost::Has('txtSqlName'))
			{
				$this->oSettings->SetConf('Common/DBName', CPost::Get('txtSqlName'));
			}
			if (CPost::Has('txtSqlSrc'))
			{
				$this->oSettings->SetConf('Common/DBHost', CPost::Get('txtSqlSrc'));
			}

			if (CPost::GetCheckBox('isTestConnection'))
			{
				CDbCreator::ClearStatic();

				$aConnections =& CDbCreator::CreateConnector($this->oSettings);
				$oConnect = $aConnections[0];
				if ($oConnect)
				{
					$this->LastError = AP_LANG_CONNECTUNSUCCESSFUL;
					try
					{
						if ($oConnect->Connect())
						{
							$this->LastMessage = AP_LANG_CONNECTSUCCESSFUL;
							$this->LastError = '';
						}
					}
					catch (CApiDbException $oException)
					{
						$this->LastError .=
							"\r\n".$oException->getMessage().' ('.((int) $oException->getCode()).')';
					}
				}
				else
				{
					$this->LastError = AP_LANG_CONNECTUNSUCCESSFUL;
				}

				$this->oSettings->SaveToXml();
			}
			else
			{
				$this->saveSettingsXmlWithMessage();
			}

			return '';
		}
	}

	public function SystemSecurity()
	{
		$bDoSave = true;
		if (CApi::GetCsrfToken('p7admToken') === CPost::Get('txtToken'))
		{
			if (CPost::Has('txtNewPassword') && CPost::Has('txtConfirmNewPassword'))
			{
				if (md5(trim(CPost::Get('txtOldPassword'))) !== $this->oSettings->GetConf('Common/AdminPassword')) {
					$bDoSave = false;
					$this->LastError = CM_PASSWORDS_INVALID_OLD;
				} else if ((string)CPost::Get('txtNewPassword') !== (string)CPost::Get('txtConfirmNewPassword')) {
					$bDoSave = false;
					$this->LastError = CM_PASSWORDS_NOT_MATCH;
				} else if (AP_DUMMYPASSWORD !== (string)CPost::Get('txtNewPassword')) {
					$this->oSettings->SetConf('Common/AdminPassword', md5(trim(CPost::Get('txtNewPassword'))));
				}

				if ($bDoSave) {
					if (CPost::Has('txtUserName')) {
						$this->oSettings->SetConf('Common/AdminLogin', CPost::Get('txtUserName'));
					}

					$this->saveSettingsXmlWithMessage();
				}
			}
		}
		else
		{
			$this->LastError = CApi::I18N('API/INVALID_TOKEN');
		}
	}

	public function CommonSocial()
	{
		$oApiCapa = CApi::Manager('capability');
		/* @var $oApiCapa CApiCapabilityManager */

		if ($oApiCapa)
		{
			$oTenant = /* @var $oTenant CTenant */  $this->oModule->GetTenantAdminObject();
			if ($oTenant)
			{
				$aSocials = $oTenant->GetDefaultSocials();
				$aTenentSocials = array();
				foreach ($aSocials as $sKey => $oSocial)
				{
					$oTenentSocial = new CTenantSocials();
					$oTenentSocial->IdTenant = $oTenant->IdTenant;
					$oTenentSocial->SocialAllow = CPost::GetCheckBox($sKey . '_chSocialAllow');
					$oTenentSocial->SocialName = ucfirst($sKey);
					$oTenentSocial->SocialId = CPost::Get($sKey . '_txtSocialId');
					$oTenentSocial->SocialSecret = CPost::Get($sKey . '_txtSocialSecret');
					$oTenentSocial->SocialApiKey = CPost::Get($sKey . '_txtSocialApiKey');
					$oTenentSocial->SocialScopes = CPost::Get($sKey . '_txtSocialScopes');
					
					$aTenentSocials[$sKey] = $oTenentSocial;
				}
				$oTenant->SetSocials($aTenentSocials);
			}

			if ($oTenant && $this->oModule->UpdateTenantAdminObject($oTenant))
			{
				$this->LastMessage = AP_LANG_SAVESUCCESSFUL;
				$this->LastError = '';
			}
			else
			{
				$this->LastMessage = '';
				$this->LastError = AP_LANG_SAVEUNSUCCESSFUL;
			}
		}
	}
}