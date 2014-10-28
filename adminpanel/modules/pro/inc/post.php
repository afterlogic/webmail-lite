<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CProPostAction extends ap_CoreModuleHelper
{
	public function CommonTenant()
	{
		if ($this->oAdminPanel->IsTenantAuthType())
		{
			$oTenant = $this->oModule->GetTenantAdminObject();
			/* @var $oTenant CTenant */
			if ($oTenant)
			{
				if ($oTenant->AllowChangeAdminEmail)
				{
					$oTenant->Email = CPost::Get('txtTenantAdminEmail');
				}

				if ($oTenant->AllowChangeAdminPassword && API_DUMMY !== CPost::Get('txtTenantPassword') && 0 < strlen(trim(CPost::Get('txtTenantPassword'))))
				{
					$oTenant->SetPassword(CPost::Get('txtTenantPassword'));
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

	public function CommonBranding()
	{
		$oApiCapa = CApi::Manager('capability');
		/* @var $oApiCapa CApiCapabilityManager */

		$oTenant = /* @var $oTenant CTenant */  $this->oModule->GetTenantAdminObject();
		if ($oTenant)
		{
			if (!$oApiCapa->IsTenantsSupported())
			{
				$oTenant->LoginStyleImage = CPost::Get('txtLoginStyleImage');
				$oTenant->AppStyleImage = CPost::Get('txtAppStyleImage');
			}
			else if (!$this->oAdminPanel->IsTenantAuthType() &&
				($this->oAdminPanel->IsSuperAdminAuthType() || $this->oAdminPanel->IsOnlyReadAuthType()))
			{
				$oTenant->LoginStyleImage = CPost::Get('txtLoginStyleImage');
			}
			else if ($this->oAdminPanel->IsTenantAuthType() && $oTenant)
			{
				$oTenant->AppStyleImage = CPost::Get('txtAppStyleImage');
			}

			if ($this->oModule->UpdateTenantAdminObject($oTenant))
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

	public function CommonHelpdesk()
	{
		$oApiCapa = CApi::Manager('capability');
		if ($oApiCapa && $oApiCapa->IsHelpdeskSupported() && (
			$this->oAdminPanel->IsTenantAuthType() ||
			($this->oAdminPanel->IsSuperAdminAuthType() && !$oApiCapa->IsTenantsSupported())
		))
		{
			$oTenant = /* @var $oTenant CTenant */  $this->oModule->GetTenantAdminObject();
			if ($oTenant && $oTenant->IsHelpdeskSupported())
			{
				$oTenant->HelpdeskAdminEmailAccount = CPost::Get('txtAdminEmailAccount');
				$oTenant->HelpdeskAllowFetcher = CPost::GetCheckBox('chHelpdeskAllowFetcher');
				$oTenant->HelpdeskClientIframeUrl = CPost::Get('txtClientIframeUrl');
				$oTenant->HelpdeskAgentIframeUrl = CPost::Get('txtAgentIframeUrl');
				$oTenant->HelpdeskSiteName = CPost::Get('txtHelpdeskSiteName');
				$oTenant->HelpdeskStyleAllow = CPost::Get('chHelpdeskStyleAllow');
				$oTenant->HelpdeskStyleImage = CPost::Get('txtHelpdeskStyleImage');
				$oTenant->SetHelpdeskStyleText(CPost::Get('txtHelpdeskStyleText'));

				$oTenant->HelpdeskFacebookAllow = CPost::Get('chHelpdeskFacebookAllow');
				$oTenant->HelpdeskFacebookId = CPost::Get('txtHelpdeskFacebookId');
				$oTenant->HelpdeskFacebookSecret = CPost::Get('txtHelpdeskFacebookSecret');
				$oTenant->HelpdeskGoogleAllow = CPost::Get('chHelpdeskGoogleAllow');
				$oTenant->HelpdeskGoogleId = CPost::Get('txtHelpdeskGoogleId');
				$oTenant->HelpdeskGoogleSecret = CPost::Get('txtHelpdeskGoogleSecret');
				$oTenant->HelpdeskTwitterAllow = CPost::Get('chHelpdeskTwitterAllow');
				$oTenant->HelpdeskTwitterId = CPost::Get('txtHelpdeskTwitterId');
				$oTenant->HelpdeskTwitterSecret = CPost::Get('txtHelpdeskTwitterSecret');
				
				if (CPost::Has('radioHelpdeskFetcherType'))
				{
					$oTenant->HelpdeskFetcherType =
						EnumConvert::FromPost(CPost::Get('radioHelpdeskFetcherType'), 'EHelpdeskFetcherType');
				}
			}

			if ($oTenant && $oTenant->IsHelpdeskSupported() && $this->oModule->UpdateTenantAdminObject($oTenant))
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

	public function CommonTwilio()
	{
		$oApiCapa = CApi::Manager('capability');
		/* @var $oApiCapa CApiCapabilityManager */

		if ($oApiCapa && $oApiCapa->IsTwilioSupported())
		{
			$oTenant = /* @var $oTenant CTenant */  $this->oModule->GetTenantAdminObject();

			if ($oTenant && $oTenant->IsTwilioSupported())
			{
				$oTenant->TwilioAllow = !$oTenant->SipAllow ? CPost::GetCheckBox('chAllowTwilio') : 0;
				$oTenant->TwilioPhoneNumber = CPost::Get('txtTwilioPhoneNumber');
				$oTenant->TwilioAccountSID = CPost::Get('txtTwilioAccountSID');
				$oTenant->TwilioAuthToken = CPost::Get('txtTwilioAuthToken');
				$oTenant->TwilioAppSID = CPost::Get('txtTwilioAppSID');

				if ($this->oModule->UpdateTenantAdminObject($oTenant))
				{
					$this->LastMessage = !$oTenant->SipAllow ? AP_LANG_SAVESUCCESSFUL : CApi::I18N('ADMIN_PANEL/MSG_SAVESUCCESSFUL_TWILIO');
					$this->LastError = '';
				}
				else
				{
					$this->LastMessage = '';
					$this->LastError = AP_LANG_SAVEUNSUCCESSFUL;
				}
			} else
			{
				$this->LastMessage = '';
				$this->LastError = AP_LANG_SAVEUNSUCCESSFUL;
			}
		}
	}

	public function CommonSip()
	{
		$oApiCapa = CApi::Manager('capability');
		/* @var $oApiCapa CApiCapabilityManager */

		if ($oApiCapa && $oApiCapa->IsSipSupported())
		{
			$oTenant = /* @var $oTenant CTenant */  $this->oModule->GetTenantAdminObject();

			if ($oTenant && $oTenant->IsSipSupported())
			{
				$oTenant->SipAllow = !$oTenant->TwilioAllow ? CPost::GetCheckBox('chAllowSip') : 0;
				$oTenant->SipRealm = CPost::Get('txtSipRealm');
				$oTenant->SipWebsocketProxyUrl = CPost::Get('txtSipWebsocketProxyUrl');
				$oTenant->SipOutboundProxyUrl = CPost::Get('txtSipOutboundProxyUrl');
				$oTenant->SipCallerID = CPost::Get('txtSipCallerID');

				if ($this->oModule->UpdateTenantAdminObject($oTenant))
				{
					$this->LastMessage = !$oTenant->TwilioAllow ? AP_LANG_SAVESUCCESSFUL : CApi::I18N('ADMIN_PANEL/MSG_SAVESUCCESSFUL_SIP');
					$this->LastError = '';
				}
				else
				{
					$this->LastMessage = '';
					$this->LastError = AP_LANG_SAVEUNSUCCESSFUL;
				}
			} else
			{
				$this->LastMessage = '';
				$this->LastError = AP_LANG_SAVEUNSUCCESSFUL;
			}
		}
	}
	


	public function TenantsCollectionDelete()
	{
		$aCollection = CPost::Get('chCollection', array());
		$aAccountsIds = is_array($aCollection) ? $aCollection : array();

		$this->checkBolleanDeleteWithMessage(
			(0 < count($aAccountsIds) && $this->oModule->DeleteTenants($aAccountsIds))
		);
	}

	public function ChannelsCollectionDelete()
	{
		$aCollection = CPost::Get('chCollection', array());
		$aAccountsIds = is_array($aCollection) ? $aCollection : array();

		$this->checkBolleanDeleteWithMessage(
			(0 < count($aAccountsIds) && $this->oModule->DeleteChannels($aAccountsIds))
		);
	}

	public function UsersCollectionDelete()
	{
		$aCollection = CPost::Get('chCollection', array());
		$aAccountsIds = is_array($aCollection) ? $aCollection : array();

		$this->checkBolleanDeleteWithMessage(
			(0 < count($aAccountsIds) && $this->oModule->DeleteAccounts($aAccountsIds))
		);
	}

	public function UsersCollectionEnable()
	{
		$aCollection = CPost::Get('chCollection', array());
		$aAccountsIds = is_array($aCollection) ? $aCollection : array();
		if (0 < count($aAccountsIds))
		{
			$this->oModule->EnableAccounts($aAccountsIds, true);
		}
	}

	public function UsersCollectionDisable()
	{
		$aCollection = CPost::Get('chCollection', array());
		$aAccountsIds = is_array($aCollection) ? $aCollection : array();
		if (0 < count($aAccountsIds))
		{
			$this->oModule->EnableAccounts($aAccountsIds, false);
		}
	}

	public function DomainsCollectionDelete()
	{
		$aCollection = CPost::Get('chCollection', array());
		$aDomainsIds = is_array($aCollection) ? $aCollection : array();

		if (0 < count($aDomainsIds))
		{
			if (!$this->oModule->DeleteDomains($aDomainsIds))
			{
				$this->LastError = $this->oModule->GetLastErrorMessage();
			}
			else
			{
				$this->checkBolleanDeleteWithMessage(true);
			}
		}
		else
		{
			$this->checkBolleanDeleteWithMessage(false);
		}
	}

	public function SystemLicensing()
	{
		$sKey = CPost::Get('txtLicenseKey', null);
		if (null !== $sKey)
		{
			$this->checkBolleanWithMessage($this->oModule->UpdateLicenseKey(CPost::Get('txtLicenseKey')));
		}
	}

	public function CommonDav()
	{
		$bResult = false;

		$this->oSettings->SetConf('WebMail/ExternalHostNameOfDAVServer', CPost::Get('text_DAVUrl'));
		$this->oSettings->SetConf('WebMail/ExternalHostNameOfLocalImap', CPost::Get('text_IMAPHostName'));
		$this->oSettings->SetConf('WebMail/ExternalHostNameOfLocalSmtp', CPost::Get('text_SMTPHostName'));

		/* @var $oApiDavManager CApiDavManager */
		$oApiDavManager = CApi::Manager('dav');
		if ($oApiDavManager)
		{
			$bResult = $oApiDavManager->SetMobileSyncEnable(CPost::GetCheckBox('ch_EnableMobileSync'));
			$bResult &= $this->oSettings->SaveToXml();
		}

		$this->checkBolleanWithMessage((bool) $bResult);
	}
}