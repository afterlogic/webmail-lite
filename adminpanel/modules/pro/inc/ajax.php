<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CProAjaxAction extends ap_CoreModuleHelper
{
	public function UsersNew_Pre()
	{
		/* @var $oAccount CAccount */
		$oAccount =& $this->oAdminPanel->GetMainObject('account_new');
		if (!$oAccount)
		{
			$iDomainId = (int) CPost::Get('hiddenDomainId', 0);
			if ($this->oAdminPanel->HasAccessDomain($iDomainId))
			{
				$oDomain = $this->oModule->GetDomain($iDomainId);
				if ($oDomain)
				{
					$oAccount = new CAccount($oDomain);
					$this->oAdminPanel->SetMainObject('account_new', $oAccount);
				}
			}
		}
	}

	public function UsersNew()
	{
		/* @var $oAccount CAccount */
		$oAccount =& $this->oAdminPanel->GetMainObject('account_new');
		if ($oAccount)
		{
			$this->initNewAccountByPost($oAccount);
		}
	}

	public function UsersNew_Post()
	{
		/* @var $oAccount CAccount */
		$oAccount =& $this->oAdminPanel->GetMainObject('account_new');
		if ($oAccount)
		{
			$this->oAdminPanel->DeleteMainObject('account_new');
			if ($this->oModule->CreateAccount($oAccount))
			{
				$this->checkBolleanWithMessage(true);
				$this->Ref = '?edit&tab=users&uid='.$oAccount->IdAccount;
			}
			else
			{
				if (0 < $this->oModule->GetLastErrorCode())
				{
					$this->LastError = $this->oModule->GetLastErrorMessage();
				}
				else
				{
					$this->checkBolleanWithMessage(false);
				}
			}
		}
	}

	public function UsersEdit_Pre()
	{
		/* @var $oAccount CAccount */
		$oAccount =& $this->oAdminPanel->GetMainObject('account_edit');
		if (!$oAccount)
		{
			$iDomainId = (int) CPost::Get('hiddenDomainId', 0);
			if (CPost::Has('hiddenAccountId') && is_numeric(CPost::Get('hiddenAccountId', false))
			&& 	$this->oAdminPanel->HasAccessDomain($iDomainId))
			{
				$oAccount = $this->oModule->GetAccount((int) CPost::Get('hiddenAccountId', 0));
				$this->oAdminPanel->SetMainObject('account_edit', $oAccount);
			}
		}
	}

	public function UsersEdit()
	{
		/* @var $oAccount CAccount */
		$oAccount =& $this->oAdminPanel->GetMainObject('account_edit');
		if ($oAccount)
		{
			$this->initEditAccountByPost($oAccount);
		}
	}

	public function UsersEdit_Post()
	{
		/* @var $oAccount CAccount */
		$oAccount =& $this->oAdminPanel->GetMainObject('account_edit');
		if ($oAccount && $this->oAdminPanel->HasAccessDomain($oAccount->Domain->IdDomain))
		{
			$this->oAdminPanel->DeleteMainObject('account_edit');
			if ($this->oModule->UpdateAccount($oAccount))
			{
				$this->checkBolleanWithMessage(true);
				$this->Ref = '?edit&tab=users&uid='.$oAccount->IdAccount;
			}
			else
			{
				if (0 < $this->oModule->GetLastErrorCode())
				{
					$this->LastError = $this->oModule->GetLastErrorMessage();
				}
				else
				{
					$this->checkBolleanWithMessage(false);
				}
			}
		}
	}

	/**
	 * @param CAccount $oAccount
	 */
	protected function initEditAccountByPost(CAccount &$oAccount)
	{
		$oAccount->IsDisabled = !CPost::GetCheckBox('chEnableUser');
		if (CPost::Has('txtFullName'))
		{
			$oAccount->FriendlyName = (string) CPost::Get('txtFullName', '');
		}

		$oAccount->HideInGAB = CPost::GetCheckBox('chHideInGAB');
		
		if (CPost::Has('txtEditStorageQuota'))
		{
			$oAccount->StorageQuota = ((int) substr(CPost::Get('txtEditStorageQuota'), 0, 9) * 1024);
		}

		if (0 < $oAccount->Domain->IdTenant && CApi::GetConf('capa', false))
		{
			$oAccount->User->IdSubscription = (int) CPost::Get('selSubscribtions');

			$oTenantsApi = CApi::Manager('tenants');
			/* @var $oTenantsApi CApiTenantsManager */

			if ($oTenantsApi)
			{
				$oTenant = $oTenantsApi->GetTenantById($oAccount->Domain->IdTenant);
				if ($oTenant)
				{
					$oAccount->User->SetCapa($oTenant, ECapa::GAB, CPost::GetCheckBox('chExtGAB') && $oAccount->Domain->AllowContacts);
					$oAccount->User->SetCapa($oTenant, ECapa::FILES, CPost::GetCheckBox('chExtFiles') && $oAccount->Domain->AllowFiles);
					$oAccount->User->SetCapa($oTenant, ECapa::HELPDESK, CPost::GetCheckBox('chExtHelpdesk') && $oAccount->Domain->AllowHelpdesk);
				}
			}
		}

		$oCapabylity = CApi::Manager('capability');
		/* @var $oCapabylity CApiCapabilityManager */
		if ($oAccount && $oCapabylity)
		{
			if ($oCapabylity->IsSipSupported($oAccount))
			{
				$oAccount->User->SipEnable = CPost::GetCheckBox('chSipEnable', $oAccount->User->SipEnable);
				$oAccount->User->SipImpi = trim(CPost::Get('txtSipImpi', $oAccount->User->SipImpi));
				$sSipPassword = trim(CPost::Get('txtSipPassword', API_DUMMY));
				if (API_DUMMY !== $sSipPassword && 0 < strlen($sSipPassword))
				{
					$oAccount->User->SipPassword = $sSipPassword;
				}
			}

			if ($oCapabylity->IsTwilioSupported($oAccount))
			{
				$oAccount->User->TwilioNumber = trim(CPost::Get('txtTwilioNumber', $oAccount->User->TwilioNumber));
				$oAccount->User->TwilioEnable = CPost::GetCheckBox('chTwilioEnable', $oAccount->User->TwilioEnable);
				$oAccount->User->TwilioDefaultNumber = CPost::GetCheckBox('chTwilioDefaultNumber', $oAccount->User->TwilioDefaultNumber);
			}
		}
	}

	/**
	 * @param CAccount $oAccount
	 */
	protected function initNewAccountByPost(CAccount &$oAccount)
	{
		if (CPost::Has('txtNewPassword'))
		{
			$oAccount->IsDefaultAccount = true;
			$oAccount->InitLoginAndEmail(CPost::Get('txtNewLogin'));
			$oAccount->IncomingMailPassword = CPost::Get('txtNewPassword');

			if ($oAccount->Domain && $oAccount->Domain->IsDefaultDomain)
			{
				$oAccount->Email = CPost::Get('txtNewEmail');

				$oAccount->IncomingMailProtocol = EnumConvert::FromPost(
				CPost::Get('selIncomingMailProtocol'), 'EMailProtocol');

				$oAccount->IncomingMailLogin = CPost::Get('txtIncomingMailLogin');
				$oAccount->IncomingMailServer = CPost::Get('txtIncomingMailHost');
				$oAccount->IncomingMailPort = (int) CPost::Get('txtIncomingMailPort');
				$oAccount->IncomingMailUseSSL = CPost::GetCheckBox('chIncomingUseSSL');

				$oAccount->OutgoingMailLogin = CPost::Get('txtOutgoingMailLogin');
				$oAccount->OutgoingMailPassword = CPost::Get('txtOutgoingMailPassword');
				$oAccount->OutgoingMailServer = CPost::Get('txtOutgoingMailHost');
				$oAccount->OutgoingMailPort = (int) CPost::Get('txtOutgoingMailPort');
				$oAccount->OutgoingMailUseSSL = CPost::GetCheckBox('chOutgoingUseSSL');

				$oAccount->OutgoingMailAuth = CPost::GetCheckBox('chOutgoingAuth')
					? ESMTPAuthType::AuthCurrentUser : ESMTPAuthType::NoAuth;
			}
		}

		if (0 < $oAccount->Domain->IdTenant && CApi::GetConf('capa', false))
		{
			$oAccount->User->IdSubscription = (int) CPost::Get('selSubscribtions');
		}

		if (CPost::Has('txtEditStorageQuota'))
		{
			$oAccount->StorageQuota = ((int) substr(CPost::Get('txtEditStorageQuota'), 0, 9) * 1024);
		}
	}

	public function DomainsEdit()
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_edit');
		if ($oDomain)
		{
			$this->initUpdateDomainByPost($oDomain);
		}
	}

	protected function initUpdateDomainByPost(CDomain &$oDomain)
	{
		$oDomain->OverrideSettings = 0 < $oDomain->IdTenant ? true : CPost::GetCheckBox('chOverrideSettings');

		if ($oDomain->OverrideSettings)
		{
			$oDomain->AllowCalendar = CPost::GetCheckBox('chEnableCalendar');
			$oDomain->AllowFiles = CPost::GetCheckBox('chEnableFiles');
			$oDomain->AllowHelpdesk = CPost::GetCheckBox('chEnableHelpdesk');

			if (CPost::Has('selWeekStartsOn'))
			{
				$oDomain->CalendarWeekStartsOn =
					EnumConvert::FromPost(CPost::Get('selWeekStartsOn'), 'ECalendarWeekStartOn');
			}

			$oDomain->CalendarShowWeekEnds = CPost::GetCheckBox('chShowWeekends');
			$oDomain->CalendarShowWorkDay = CPost::GetCheckBox('chShowWorkday');

			if (CPost::Has('selWorkdayStarts'))
			{
				$oDomain->CalendarWorkdayStarts = (int) CPost::Get('selWorkdayStarts');
			}

			if (CPost::Has('selWorkdayEnds'))
			{
				$oDomain->CalendarWorkdayEnds = (int) CPost::Get('selWorkdayEnds');
			}

			if (CPost::Has('radioDefaultTab'))
			{
				$oDomain->CalendarDefaultTab =
					EnumConvert::FromPost(CPost::Get('radioDefaultTab'), 'ECalendarDefaultTab');
			}

			$oDomain->UseThreads = CPost::GetCheckBox('chUseThreads');
			$oDomain->AllowUsersAddNewAccounts = CPost::GetCheckBox('chAllowUsersAddNewAccounts');
			$oDomain->AllowOpenPGP = CPost::GetCheckBox('chAllowOpenPGP');

			if (CPost::Has('selGlobalAddressBook'))
			{
				$oDomain->GlobalAddressBook =
					EnumConvert::FromPost(CPost::Get('selGlobalAddressBook'), 'EContactsGABVisibility');

				if (!$this->oAdminPanel->RType() && EContactsGABVisibility::TenantWide === $oDomain->GlobalAddressBook)
				{
					$oDomain->GlobalAddressBook	= EContactsGABVisibility::SystemWide;
				}
			}
		}
	}

	protected function initTenantByPost(CTenant &$oTenant)
	{
		$oTenant->Login = CPost::Get('txtLogin', $oTenant->Login);
		$oTenant->Email = CPost::Get('txtEmail', $oTenant->Email);

		$sChannel = CPost::Get('txtChannel', '');
		if (0 < strlen($sChannel))
		{
			$oChannelsApi = CApi::Manager('channels');
			if ($oChannelsApi)
			{
				/* @var $oChannel CChannel */
				$iIdChannel = $oChannelsApi->GetChannelIdByLogin($sChannel);
				if (0 < $iIdChannel)
				{
					$oTenant->IdChannel = $iIdChannel;
				}
				else
				{
					$this->oAdminPanel->DeleteMainObject('tenant_new');
					$this->oAdminPanel->DeleteMainObject('tenant_edit');
					$this->LastError = CApi::I18N('API/CHANNELSMANAGER_CHANNEL_DOES_NOT_EXIST');
				}
			}
		}

		if (CPost::Has('txtPassword') && (string) AP_DUMMYPASSWORD !== (string) CPost::Get('txtPassword'))
		{
			$oTenant->SetPassword(CPost::Get('txtPassword'));
		}

		$oTenant->QuotaInMB = (int) CPost::Get('txtQuota', 0);
		$oTenant->UserCountLimit = (int) CPost::Get('txtUserLimit', 0);
		$oTenant->IsEnableAdminPanelLogin = CPost::GetCheckBox('chEnableAdminLogin');

		$bIsDisabled = !CPost::GetCheckBox('chTenantEnabled');
		if ($bIsDisabled !== $oTenant->IsDisabled)
		{
			$oTenant->IsDisabled = $bIsDisabled;
		}

		$oTenant->SipAllowConfiguration = !!CPost::GetCheckBox('chTenantSipConfiguration');
		
		$oTenant->TwilioAllowConfiguration = !!CPost::GetCheckBox('chTenantTwilioConfiguration');

		$oTenant->Description = CPost::Get('txtDescription', $oTenant->Description);

		if (CApi::GetConf('capa', false))
		{
			$oTenant->Capa = CPost::Get('txtCapa', $oTenant->Capa);
		}
	}

	protected function initChannelByPost(CChannel &$oChannel)
	{
		$oChannel->Login = CPost::Get('txtLogin', $oChannel->Login);
		$oChannel->Description = CPost::Get('txtDescription', $oChannel->Description);
	}

	public function TenantsNew_Pre()
	{
		/* @var $oTenant CChannel */
		$oTenant =& $this->oAdminPanel->GetMainObject('tenant_new');
		if (!$oTenant)
		{
			$oTenant = new CTenant();
			$this->oAdminPanel->SetMainObject('tenant_new', $oTenant);
		}
	}

	public function TenantsNew()
	{
		$oTenant =& $this->oAdminPanel->GetMainObject('tenant_new');
		if ($oTenant)
		{
			$this->initTenantByPost($oTenant);
		}
	}

	public function TenantsNew_Post()
	{
		$oTenant =& $this->oAdminPanel->GetMainObject('tenant_new');
		if ($oTenant)
		{
			if ($this->oModule->CreateTenant($oTenant))
			{
				$this->checkBolleanWithMessage(true);
				$this->Ref = '?root';
			}
			else
			{
				if (0 < $this->oModule->GetLastErrorCode())
				{
					$this->LastError = $this->oModule->GetLastErrorMessage();
				}
				else
				{
					$this->checkBolleanWithMessage(false);
				}
			}
		}
	}

	public function ChannelsNew()
	{
		$oChannel = new CChannel();
		$this->initChannelByPost($oChannel);

		if ($this->oModule->CreateChannel($oChannel))
		{
			$this->checkBolleanWithMessage(true);
			$this->Ref = '?root';
		}
		else
		{
			if (0 < $this->oModule->GetLastErrorCode())
			{
				$this->LastError = $this->oModule->GetLastErrorMessage();
			}
			else
			{
				$this->checkBolleanWithMessage(false);
			}
		}
	}

	public function TenantsEdit()
	{
		$iTenantId = (int) CPost::Get('intTenantId', -1);

		$oTenant = $this->oModule->GetTenantById($iTenantId);
		if ($oTenant)
		{
			$this->initTenantByPost($oTenant);

			if ($this->oModule->UpdateTenant($oTenant))
			{
				$this->Ref = '?edit&tab='.AP_TAB_TENANTS.'&uid='.$iTenantId;
				$this->checkBolleanWithMessage(true);
			}
			else
			{
				if (0 < $this->oModule->GetLastErrorCode())
				{
					$this->LastError = $this->oModule->GetLastErrorMessage();
				}
				else
				{
					$this->checkBolleanWithMessage(false);
				}
			}
		}
		else
		{
			$this->checkBolleanWithMessage(false);
		}
	}

	public function ChannelsEdit()
	{
		$iChannelId = (int) CPost::Get('intChannelId', -1);

		$oChannel = $this->oModule->GetChannelById($iChannelId);
		if ($oChannel)
		{
			$this->initChannelByPost($oChannel);

			if ($this->oModule->UpdateChannel($oChannel))
			{
				$this->Ref = '?edit&tab='.AP_TAB_CHANNELS.'&uid='.$iChannelId;
				$this->checkBolleanWithMessage(true);
			}
			else
			{
				if (0 < $this->oModule->GetLastErrorCode())
				{
					$this->LastError = $this->oModule->GetLastErrorMessage();
				}
				else
				{
					$this->checkBolleanWithMessage(false);
				}
			}
		}
		else
		{
			$this->checkBolleanWithMessage(false);
		}
	}

	public function DomainsNew_Pre()
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_new');
		if (!$oDomain)
		{
			$oDomain = new CDomain();
			$this->oAdminPanel->SetMainObject('domain_new', $oDomain);
		}
	}

	public function DomainsNew()
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_new');
		if ($oDomain)
		{
			$this->initNewDomainByPost($oDomain);
		}
	}

	public function DomainsNew_Post()
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_new');
		if ($oDomain)
		{
			$this->oAdminPanel->DeleteMainObject('domain_new');

			if ($this->oModule->CreateDomain($oDomain))
			{
				$this->checkBolleanWithMessage(true);
				$this->Ref = ($oDomain->OverrideSettings)
					? '?edit&tab=domains&uid='.$oDomain->IdDomain : '?root';
			}
			else
			{
				if (0 < $this->oModule->GetLastErrorCode())
				{
					$this->LastError = $this->oModule->GetLastErrorMessage();
				}
				else
				{
					$this->checkBolleanWithMessage(false);
				}
			}
		}
	}

	protected function initNewDomainByPost(CDomain &$oDomain)
	{
		$sDomainName = CPost::Get('txtDomainName', '');

		$oDomain->IsDefaultDomain = false;
		$oDomain->OverrideSettings = CPost::GetCheckBox('chOverrideSettings');

		$oDomain->Name = $sDomainName;
		$oDomain->Url = '';

		if (0 < $this->oAdminPanel->TenantId())
		{
			$oDomain->IdTenant = $this->oAdminPanel->TenantId();
		}
		else
		{
			$sTenant = CPost::Get('txtTenantName', '');
			if (0 < strlen($sTenant))
			{
				$iIdTenant = $this->oModule->GetTenantIdByName($sTenant);
				if (0 === $iIdTenant)
				{
					$this->oAdminPanel->DeleteMainObject('domain_new');
					$this->LastError = CApi::I18N('API/TENANTSMANAGER_TENANT_DOES_NOT_EXIST');
				}
				else
				{
					$oDomain->IdTenant = $iIdTenant;
				}
			}
		}
	}
}
