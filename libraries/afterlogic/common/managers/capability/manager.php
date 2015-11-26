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
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('capability', $oManager);
	}

	/**
	 * @return bool
	 */
	public function isNotLite()
	{
		return !!CApi::Manager('licensing');
	}

	/**
	 * @return bool
	 */
	public function isCollaborationSupported()
	{
		return $this->isNotLite() && !!CApi::Manager('collaboration');
	}

	/**
	 * @return bool
	 */
	public function isMailsuite()
	{
		return !!CApi::GetConf('mailsuite', false) && !!CApi::Manager('mailsuite');
	}

	/**
	 * @return bool
	 */
	public function isDavSupported()
	{
		return $this->isNotLite() && !!CApi::Manager('dav');
	}

	/**
	 * @return bool
	 */
	public function isTenantsSupported()
	{
		return $this->isNotLite() && !!CApi::GetConf('tenant', false);
	}

	/**
	 * @param CAccount $oAccount = null
	 * 
	 * @return bool
	 */
	public function isCalendarSupported($oAccount = null)
	{
		$bResult = $this->isNotLite() && $this->isDavSupported();

		if ($bResult && $oAccount)
		{
			$bResult = $oAccount->Domain->AllowCalendar && $oAccount->User->getCapa(ECapa::CALENDAR);
		}

		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function isIosProfileSupported()
	{
		return $this->isNotLite();
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isCalendarSharingSupported($oAccount = null)
	{
		$bResult = $this->isCalendarSupported() && $this->isCollaborationSupported();

		if ($bResult && $oAccount)
		{
			$bResult = $this->isCalendarSupported($oAccount) && $oAccount->User->getCapa(ECapa::CAL_SHARING);
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isCalendarAppointmentsSupported($oAccount = null)
	{
		$bResult = $this->isCalendarSupported() && $this->isCollaborationSupported();
		if ($bResult && $oAccount)
		{
			$bResult = $this->isCalendarSupported($oAccount) && $oAccount->User->getCapa(ECapa::MEETINGS); // TODO
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isContactsSupported($oAccount = null)
	{
		$bResult = true;
		if ($oAccount)
		{
			$bResult = $oAccount->Domain->AllowContacts &&
				($oAccount->User->getCapa(ECapa::PAB) || $oAccount->User->getCapa(ECapa::GAB));
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isPersonalContactsSupported($oAccount = null)
	{
		$bResult = $this->isContactsSupported();
		if ($oAccount)
		{
			$bResult = $this->isContactsSupported($oAccount) && $oAccount->User->getCapa(ECapa::PAB);
		}
		
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @param bool $bCheckShowSettings = true
	 * @return bool
	 */
	public function isGlobalContactsSupported($oAccount = null, $bCheckShowSettings = true)
	{
		$bResult = $this->isContactsSupported() && $this->isNotLite() && !!CApi::Manager('gcontacts');
		if ($bResult && $bCheckShowSettings)
		{
			$oSettings = null;
			$oSettings =& CApi::GetSettings();
			$bResult = $oSettings && !!$oSettings->GetConf('Contacts/ShowGlobalContactsInAddressBook');
		}

		if ($bResult && $oAccount)
		{
			$bResult = $this->isContactsSupported($oAccount) && $oAccount->User->getCapa(ECapa::GAB) && $oAccount->GlobalAddressBook !== \EContactsGABVisibility::Off;
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isSharedContactsSupported($oAccount = null)
	{
		$bResult = $this->isContactsSupported() && $this->isCollaborationSupported() &&
			\CApi::GetConf('labs.contacts-sharing', false);
		
		if ($bResult && $oAccount)
		{
			$bResult = $this->isContactsSupported($oAccount) && $oAccount->User->getCapa(ECapa::CONTACTS_SHARING) && $oAccount->GlobalAddressBook !== \EContactsGABVisibility::Off;
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isGlobalSuggestContactsSupported($oAccount = null)
	{
		return $this->isGlobalContactsSupported($oAccount, false);
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isFilesSupported($oAccount = null)
	{
		$bResult = !!CApi::GetConf('files', false) && $this->isNotLite();
		if ($bResult && $oAccount)
		{
			if ($this->isTenantsSupported())
			{
				$bResult = false;
				$oTenant = $this->_getCachedTenant($oAccount->IdTenant);
				if ($oTenant)
				{
					$bResult = $oTenant->isFilesSupported();
				}
			}

			if ($bResult)
			{
				$bResult = $oAccount->Domain->AllowFiles && $oAccount->User->getCapa(ECapa::FILES);
			}			
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isTwilioSupported($oAccount = null)
	{
		$bResult = $this->isCollaborationSupported() && !!CApi::GetConf('labs.twilio', false);
		if ($bResult && $oAccount)
		{
			$oTenant = $this->_getCachedTenant($oAccount->IdTenant);
			if ($oTenant)
			{
				$bResult = $oTenant->isTwilioSupported();
			}
			
			if ($bResult)
			{
				$bResult = $oAccount->User->getCapa(ECapa::TWILIO);
			}
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isSipSupported($oAccount = null)
	{
		$bResult = $this->isCollaborationSupported() && !!CApi::GetConf('labs.voice', false);
		if ($bResult && $oAccount)
		{
			$oTenant = $this->_getCachedTenant($oAccount->IdTenant);
			if ($oTenant)
			{
				$bResult = $oTenant->isSipSupported();
			}

			if ($bResult)
			{
				$bResult = $oAccount->User->getCapa(ECapa::SIP);
			}
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isHelpdeskSupported($oAccount = null)
	{
		$bResult = $this->isCollaborationSupported() && !!CApi::GetConf('helpdesk', false);
		if ($bResult && $oAccount)
		{
			$oTenant = $this->_getCachedTenant($oAccount->IdTenant);
			if ($oTenant)
			{
				$bResult = $oTenant->isHelpdeskSupported();
			}

			if ($bResult)
			{
				$bResult = $oAccount->Domain->AllowHelpdesk && $oAccount->User->getCapa(ECapa::HELPDESK);
			}
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isMobileSyncSupported($oAccount = null)
	{
		$bResult = $this->isNotLite() && $this->isDavSupported() &&
			($this->isContactsSupported() || $this->isGlobalContactsSupported() ||
			$this->isCalendarSupported() || $this->isHelpdeskSupported());

		if ($bResult)
		{
			$oSettings = null;
			$oSettings =& CApi::GetSettings();
			$bResult = $oSettings && $oSettings->GetConf('Common/EnableMobileSync');
		}
			
		if ($bResult && $oAccount)
		{
			$bResult = $oAccount->User->getCapa(ECapa::MOBILE_SYNC) &&
				($this->isContactsSupported($oAccount) || $this->isGlobalContactsSupported($oAccount) ||
				$this->isCalendarSupported($oAccount) || $this->isHelpdeskSupported($oAccount));
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function isOutlookSyncSupported($oAccount = null)
	{
		$bResult = $this->isNotLite() && $this->isDavSupported() && $this->isCollaborationSupported();
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
	public function getSystemCapaAsString()
	{
		static $sCache = null;
		if (null === $sCache)
		{
			$aCapa[] = ECapa::WEBMAIL;

			if ($this->isPersonalContactsSupported())
			{
				$aCapa[] = ECapa::PAB;
			}

			if ($this->isGlobalContactsSupported())
			{
				$aCapa[] = ECapa::GAB;
			}

			if ($this->isCalendarSupported())
			{
				$aCapa[] = ECapa::CALENDAR;
			}

			if ($this->isCalendarAppointmentsSupported())
			{
				$aCapa[] = ECapa::MEETINGS;
			}

			if ($this->isCalendarSharingSupported())
			{
				$aCapa[] = ECapa::CAL_SHARING;
			}

			if ($this->isMobileSyncSupported())
			{
				$aCapa[] = ECapa::MOBILE_SYNC;
			}

			if ($this->isOutlookSyncSupported())
			{
				$aCapa[] = ECapa::OUTLOOK_SYNC;
			}

			if ($this->isFilesSupported())
			{
				$aCapa[] = ECapa::FILES;
			}

			if ($this->isHelpdeskSupported())
			{
				$aCapa[] = ECapa::HELPDESK;
			}

			if ($this->isSipSupported())
			{
				$aCapa[] = ECapa::SIP;
			}
			
			if ($this->isTwilioSupported())
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
	public function hasSslSupport()
	{
		return api_Utils::hasSslSupport();
	}

	/**
	 * @return bool
	 */
	public function hasGdSupport()
	{
		return api_Utils::HasGdSupport();
	}

	/**
	 * @param int $iIdTenant
	 * @return CTenant
	 */
	private function _getCachedTenant($iIdTenant)
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
				$oTenant = (0 < $iIdTenant) ? $oApiTenants->getTenantById($iIdTenant) : $oApiTenants->getDefaultGlobalTenant();
			}
		}

		if ($oTenant && !isset($aCache[$iIdTenant]))
		{
			$aCache[$iIdTenant] = $oTenant;
		}

		return $oTenant;
	}
}
