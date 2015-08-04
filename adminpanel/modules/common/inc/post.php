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
		if (CApi::getCsrfToken('p7admToken') === CPost::get('txtToken'))
		{
			if (CApi::GetConf('mailsuite', false))
			{
				$this->oSettings->SetConf('Common/DBType', EDbType::MySQL);
			}
			else
			{
				$this->oSettings->SetConf('Common/DBType',
					EnumConvert::FromPost(CPost::get('radioSqlType'), 'EDbType'));
			}

			if (CPost::Has('txtSqlLogin'))
			{
				$this->oSettings->SetConf('Common/DBLogin', CPost::get('txtSqlLogin'));
			}
			if (CPost::Has('txtSqlPassword') &&
				AP_DUMMYPASSWORD !== (string) CPost::get('txtSqlPassword'))
			{
				$this->oSettings->SetConf('Common/DBPassword', CPost::get('txtSqlPassword'));
			}
			if (CPost::Has('txtSqlName'))
			{
				$this->oSettings->SetConf('Common/DBName', CPost::get('txtSqlName'));
			}
			if (CPost::Has('txtSqlSrc'))
			{
				$this->oSettings->SetConf('Common/DBHost', CPost::get('txtSqlSrc'));
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
		}

        return '';
	}

	public function SystemSecurity()
	{
		$bDoSave = true;
		if (CApi::getCsrfToken('p7admToken') === CPost::get('txtToken'))
		{
			if (CPost::Has('txtNewPassword') && CPost::Has('txtConfirmNewPassword'))
			{
                $oWebmailApi = CApi::Manager('webmail');
				if (!$oWebmailApi->validateAdminPassword(trim(CPost::get('txtOldPassword')))) {
					$bDoSave = false;
					$this->LastError = CM_PASSWORDS_INVALID_OLD;
				} else if ((string)CPost::get('txtNewPassword') !== (string)CPost::get('txtConfirmNewPassword')) {
					$bDoSave = false;
					$this->LastError = CM_PASSWORDS_NOT_MATCH;
				} else if (AP_DUMMYPASSWORD !== (string)CPost::get('txtNewPassword')) {
					$this->oSettings->SetConf('Common/AdminPassword', md5(trim(CPost::get('txtNewPassword'))));
				}

				if ($bDoSave) {
					if (CPost::Has('txtUserName')) {
						$this->oSettings->SetConf('Common/AdminLogin', CPost::get('txtUserName'));
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
				$aTenentSocials = array();
				foreach ($oTenant->getSocials() as $sKey => $oSocial)
				{
					$oTenentSocial = new CTenantSocials();
					$oTenentSocial->IdTenant = $oTenant->IdTenant;
					$oTenentSocial->SocialAllow = CPost::GetCheckBox($sKey . '_chSocialAllow');
					$oTenentSocial->SocialName = ucfirst($sKey);
					$oTenentSocial->SocialId = CPost::get($sKey . '_txtSocialId');
					$oTenentSocial->SocialSecret = CPost::get($sKey . '_txtSocialSecret');
					$oTenentSocial->SocialApiKey = CPost::get($sKey . '_txtSocialApiKey');
	
					$aScopes = CPost::get($sKey . '_chSocialScopes', array());
					$oTenentSocial->SocialScopes = implode(' ', array_keys($aScopes));
					
					$aTenentSocials[$sKey] = $oTenentSocial;
				}
				$oTenant->setSocials($aTenentSocials);
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
