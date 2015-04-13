<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Capability
 */
class CApiCapabilityManager extends AApiManager
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('capability', $oManager);
	}

	/**
	 * @return bool
	 */
	public function IsNotLite()
	{
		return !!CApi::Manager('licensing');
	}

	/**
	 * @return bool
	 */
	public function IsCollaborationSupported()
	{
		return $this->IsNotLite() && !!CApi::Manager('collaboration');
	}

	/**
	 * @return bool
	 */
	public function IsMailsuite()
	{
		return !!CApi::GetConf('mailsuite', false) && !!CApi::Manager('mailsuite');
	}

	/**
	 * @return bool
	 */
	public function IsDavSupported()
	{
		return $this->IsNotLite() && !!CApi::Manager('dav');
	}

	/**
	 * @return bool
	 */
	public function IsTenantsSupported()
	{
		return $this->IsNotLite() && !!CApi::GetConf('tenant', false);
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsCalendarSupported($oAccount = null)
	{
		$bResult = $this->IsNotLite() && $this->IsDavSupported();

		if ($bResult && $oAccount)
		{
			$bResult = $oAccount->Domain->AllowCalendar && $oAccount->User->GetCapa(ECapa::CALENDAR);
		}

		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function IsIosProfileSupported()
	{
		return $this->IsNotLite();
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsCalendarSharingSupported($oAccount = null)
	{
		$bResult = $this->IsCalendarSupported() && $this->IsCollaborationSupported();

		if ($bResult && $oAccount)
		{
			$bResult = $this->IsCalendarSupported($oAccount) && $oAccount->User->GetCapa(ECapa::CAL_SHARING);
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsCalendarAppointmentsSupported($oAccount = null)
	{
		$bResult = $this->IsCalendarSupported() && $this->IsCollaborationSupported();
		if ($bResult && $oAccount)
		{
			$bResult = $this->IsCalendarSupported($oAccount) && $oAccount->User->GetCapa(ECapa::MEETINGS); // TODO
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsContactsSupported($oAccount = null)
	{
		$bResult = true;
		if ($oAccount)
		{
			$bResult = $oAccount->Domain->AllowContacts &&
				($oAccount->User->GetCapa(ECapa::PAB) || $oAccount->User->GetCapa(ECapa::GAB));
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsPersonalContactsSupported($oAccount = null)
	{
		$bResult = $this->IsContactsSupported();
		if ($oAccount)
		{
			$bResult = $this->IsContactsSupported($oAccount) && $oAccount->User->GetCapa(ECapa::PAB);
		}
		
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @param bool $bCheckShowSettings = true
	 * @return bool
	 */
	public function IsGlobalContactsSupported($oAccount = null, $bCheckShowSettings = true)
	{
		$bResult = $this->IsContactsSupported() && $this->IsCollaborationSupported() && !!CApi::Manager('gcontacts');
		if ($bResult && $bCheckShowSettings)
		{
			$oSettings = null;
			$oSettings =& CApi::GetSettings();
			$bResult = $oSettings && !!$oSettings->GetConf('Contacts/ShowGlobalContactsInAddressBook');
		}

		if ($bResult && $oAccount)
		{
			$bResult = $this->IsContactsSupported($oAccount) && $oAccount->User->GetCapa(ECapa::GAB);
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsSharedContactsSupported($oAccount = null)
	{
		$bResult = $this->IsContactsSupported() && $this->IsCollaborationSupported() &&
			\CApi::GetConf('labs.contacts-sharing', false);
		
		if ($bResult && $oAccount)
		{
			$bResult = $this->IsContactsSupported($oAccount) && $oAccount->User->GetCapa(ECapa::CONTACTS_SHARING) && $oAccount->GlobalAddressBook !== \EContactsGABVisibility::Off;
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsGlobalSuggestContactsSupported($oAccount = null)
	{
		return $this->IsGlobalContactsSupported($oAccount, false);
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsFilesSupported($oAccount = null)
	{
		$bResult = !!CApi::GetConf('files', false) && $this->IsNotLite();
		if ($bResult && $oAccount)
		{
			if ($this->IsTenantsSupported())
			{
				$bResult = false;
				$oTenant = $this->getCachedTenant($oAccount->IdTenant);
				if ($oTenant)
				{
					$bResult = $oTenant->IsFilesSupported();
				}
			}

			if ($bResult)
			{
				$bResult = $oAccount->Domain->AllowFiles && $oAccount->User->GetCapa(ECapa::FILES);
			}			
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsTwilioSupported($oAccount = null)
	{
		$bResult = $this->IsCollaborationSupported() && !!CApi::GetConf('labs.twilio', false);
		if ($bResult && $oAccount)
		{
			$oTenant = $this->getCachedTenant($oAccount->IdTenant);
			if ($oTenant)
			{
				$bResult = $oTenant->IsTwilioSupported();
			}
			
			if ($bResult)
			{
				$bResult = $oAccount->User->GetCapa(ECapa::TWILIO);
			}
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsSipSupported($oAccount = null)
	{
		$bResult = $this->IsCollaborationSupported() && !!CApi::GetConf('labs.voice', false);
		if ($bResult && $oAccount)
		{
			$oTenant = $this->getCachedTenant($oAccount->IdTenant);
			if ($oTenant)
			{
				$bResult = $oTenant->IsSipSupported();
			}

			if ($bResult)
			{
				$bResult = $oAccount->User->GetCapa(ECapa::SIP);
			}
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsHelpdeskSupported($oAccount = null)
	{
		$bResult = $this->IsCollaborationSupported() && !!CApi::GetConf('helpdesk', false);
		if ($bResult && $oAccount)
		{
			$oTenant = $this->getCachedTenant($oAccount->IdTenant);
			if ($oTenant)
			{
				$bResult = $oTenant->IsHelpdeskSupported();
			}

			if ($bResult)
			{
				$bResult = $oAccount->Domain->AllowHelpdesk && $oAccount->User->GetCapa(ECapa::HELPDESK);
			}
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsMobileSyncSupported($oAccount = null)
	{
		$bResult = $this->IsNotLite() && $this->IsDavSupported() &&
			($this->IsContactsSupported() || $this->IsGlobalContactsSupported() ||
			$this->IsCalendarSupported() || $this->IsHelpdeskSupported());

		if ($bResult)
		{
			$oSettings = null;
			$oSettings =& CApi::GetSettings();
			$bResult = $oSettings && $oSettings->GetConf('Common/EnableMobileSync');
		}
			
		if ($bResult && $oAccount)
		{
			$bResult = $oAccount->User->GetCapa(ECapa::MOBILE_SYNC) &&
				($this->IsContactsSupported($oAccount) || $this->IsGlobalContactsSupported($oAccount) ||
				$this->IsCalendarSupported($oAccount) || $this->IsHelpdeskSupported($oAccount));
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsOutlookSyncSupported($oAccount = null)
	{
		$bResult = $this->IsNotLite() && $this->IsDavSupported() && $this->IsCollaborationSupported();
//		if ($bResult && $oAccount)
//		{
//			$bResult = $oAccount->User->GetCapa(ECapa::OUTLOOK_SYNC);
//		}
// TODO

		return $bResult;
	}

	/**
	 * @staticvar $sCache
	 * @return string
	 */
	public function GetSystemCapaAsString()
	{
		static $sCache = null;
		if (null === $sCache)
		{
			$aCapa[] = ECapa::WEBMAIL;

			if ($this->IsPersonalContactsSupported())
			{
				$aCapa[] = ECapa::PAB;
			}

			if ($this->IsGlobalContactsSupported())
			{
				$aCapa[] = ECapa::GAB;
			}

			if ($this->IsCalendarSupported())
			{
				$aCapa[] = ECapa::CALENDAR;
			}

			if ($this->IsCalendarAppointmentsSupported())
			{
				$aCapa[] = ECapa::MEETINGS;
			}

			if ($this->IsCalendarSharingSupported())
			{
				$aCapa[] = ECapa::CAL_SHARING;
			}

			if ($this->IsMobileSyncSupported())
			{
				$aCapa[] = ECapa::MOBILE_SYNC;
			}

			if ($this->IsOutlookSyncSupported())
			{
				$aCapa[] = ECapa::OUTLOOK_SYNC;
			}

			if ($this->IsFilesSupported())
			{
				$aCapa[] = ECapa::FILES;
			}

			if ($this->IsHelpdeskSupported())
			{
				$aCapa[] = ECapa::HELPDESK;
			}

			if ($this->IsSipSupported())
			{
				$aCapa[] = ECapa::SIP;
			}
			
			if ($this->IsTwilioSupported())
			{
				$aCapa[] = ECapa::TWILIO;
			}

			$sCache = trim(strtoupper(implode(' ', $aCapa)));
		}

		return $sCache;
	}

	/**
	 * @return bool
	 */
	public function HasSslSupport()
	{
		return api_Utils::HasSslSupport();
	}

	/**
	 * @return bool
	 */
	public function HasGdSupport()
	{
		return api_Utils::HasGdSupport();
	}

	/**
	 * @param int $iIdTenant
	 * @return CTenant
	 */
	private function getCachedTenant($iIdTenant)
	{
		static $aCache = array();
		$oTenant = null;

		if (isset($aCache[$iIdTenant]))
		{
			$oTenant = $aCache[$iIdTenant];
		}
		else
		{
			$oApiTenants = /* @var $oApiTenants CApiTenantsManager */ CApi::Manager('tenants');
			if ($oApiTenants)
			{
				$oTenant = (0 < $iIdTenant) ? $oApiTenants->GetTenantById($iIdTenant) : $oApiTenants->GetDefaultGlobalTenant();
			}
		}

		if ($oTenant && !isset($aCache[$iIdTenant]))
		{
			$aCache[$iIdTenant] = $oTenant;
		}

		return $oTenant;
	}
}
