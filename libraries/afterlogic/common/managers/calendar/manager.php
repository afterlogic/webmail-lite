<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Calendar
 */
class CApiCalendarManager extends AApiManagerWithStorage
{
	/*
	 * @type $ApiUsersManager CApiUsersManager
	 */
	protected $ApiUsersManager;

	/*
	 * @type CApiCapabilityManager
	 */
	protected $oApiCapabilityManager;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('calendar', $oManager, $sForcedStorage);

		$this->inc('classes.helper');
		$this->inc('classes.calendar');
		$this->inc('classes.event');
		$this->inc('classes.parser');

		$this->ApiUsersManager = CApi::Manager('users');
		$this->oApiCapabilityManager = CApi::Manager('capability');
		$this->oApiDavManager = CApi::Manager('dav');
	}

	public function GetCalendarAccess($oAccount, $sCalendarId)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->GetCalendarAccess($oAccount, $sCalendarId);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function GetPublicUser()
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->GetPublicUser();
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function GetPublicAccount()
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->GetPublicAccount();
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function GetTenantUser($oAccount)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->GetTenantUser($oAccount);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function GetTenantAccount($oAccount)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->GetTenantAccount($oAccount);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function GetAccountFromAccountList($oAccount, $sEmail)
	{
		$oResult = null;
		$iResultAccountId = 0;
		
		try
		{
			if ($oAccount)
			{
				$aUserAccounts = $this->ApiUsersManager->GetUserAccountListInformation($oAccount->IdUser);
				foreach ($aUserAccounts as $iAccountId => $aUserAccount)
				{
					if (isset($aUserAccount) && isset($aUserAccount[1]) && 
							strtolower($aUserAccount[1]) === strtolower($sEmail))
					{
						$iResultAccountId = $iAccountId;
						break;
					}
				}
				if (0 < $iResultAccountId)
				{
					$oResult = $this->ApiUsersManager->GetAccountById($iResultAccountId);
				}
			}
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	// Calendars
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 */
	public function GetCalendar($oAccount, $sCalendarId)
	{
		$oCalendar = false;
		try
		{
			$oCalendar = $this->oStorage->GetCalendar($oAccount, $sCalendarId);
			if ($oCalendar)
			{
				$oCalendar = $this->PopulateCalendarShares($oAccount, $oCalendar);
			}
		}
		catch (Exception $oException)
		{
			$oCalendar = false;
			$this->setLastException($oException);
		}
		return $oCalendar;
	}

	/**
	 * @param CAccount $oAccount
	 * @param CCalendar $oCalendar
	 */
	public function PopulateCalendarShares($oAccount, $oCalendar)
	{
		if (!$oCalendar->Shared || $oCalendar->Shared && $oCalendar->Access === \ECalendarPermission::Write || $oCalendar->IsCalendarOwner($oAccount))
		{
			$oCalendar->PubHash = $this->GetPublicCalendarHash($oCalendar->Id);
			$aUsers = $this->GetCalendarUsers($oAccount, $oCalendar);

			$aShares = array();
			if ($aUsers && is_array($aUsers))
			{
				foreach ($aUsers as $aUser)
				{
					if ($aUser['email'] === $this->GetPublicUser())
					{
						$oCalendar->IsPublic = true;
					}
					else if ($aUser['email'] === $this->GetTenantUser($oAccount))
					{
						$oCalendar->SharedToAll = true;
						$oCalendar->SharedToAllAccess = (int) $aUser['access'];
					}
					else
					{
						$aShares[] = $aUser;
					}
				}
			}
			$oCalendar->Shares = $aShares;
		}
		else
		{
			$oCalendar->IsDefault = false;
		}
		
		return $oCalendar;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param CCalendar $oCalendar
	 */
	public function GetCalendarAsArray($oAccount, $oCalendar)
	{
		return array(
			'Id' => $oCalendar->Id,
			'Url' => $oCalendar->Url,
			'ExportHash' => CApi::EncodeKeyValues(array('CalendarId' => $oCalendar->Id)),
			'Color' => $oCalendar->Color,
			'Description' => $oCalendar->Description,
			'Name' => $oCalendar->DisplayName,
			'Owner' => $oCalendar->Owner,
			'IsDefault' => $oCalendar->IsDefault,
			'PrincipalId' => $oCalendar->GetMainPrincipalUrl(),
			'ServerUrl' => $this->oApiDavManager && $oAccount ? $this->oApiDavManager->GetServerUrl($oAccount) : '',
			'PrincipalUrl' => $this->oApiDavManager && $oAccount ? $this->oApiDavManager->GetPrincipalUrl($oAccount) : '',
			'Shared' => $oCalendar->Shared,
			'SharedToAll' => $oCalendar->SharedToAll,
			'SharedToAllAccess' => $oCalendar->SharedToAllAccess,
			'Access' => $oCalendar->Access,
			'IsPublic' => $oCalendar->IsPublic,
			'PubHash' => $oCalendar->PubHash,
			'Shares' => $oCalendar->Shares,
			'CTag' => $oCalendar->CTag
		);
	}	
	
	/**
	 * @param string $sCalendarId
	 */
	public function GetPublicCalendar($sCalendarId)
	{
		return $this->GetCalendar($this->GetPublicAccount(), $sCalendarId);
	}
	
	/**
	 * @param CAccount $oAccount
	 */
	public function GetDefaultCalendar($oAccount)
	{
		$mResult = false;
		$aCalendars = $this->GetCalendars($oAccount);
		if (is_array($aCalendars) && isset($aCalendars[0]))
		{
			$mResult = $aCalendars[0];
		}
		
		return $mResult;
	}

	/**
	 * @param string $sHash
	 */
	public function GetPublicCalendarByHash($sHash)
	{
		return $this->GetPublicCalendar($sHash);
	}

	/**
	 * @param string $sCalendarId
	 */
	public function GetPublicCalendarHash($sCalendarId)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->GetPublicCalendarHash($sCalendarId);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function GetUserCalendars($oAccount)
	{
		return $this->oStorage->GetCalendars($oAccount);
	}

	public function ___qSortCallback ($a, $b)
	{
		return ($a['is_default'] === '1' ? -1 : 1);
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function GetCalendars($oAccount)
	{
		$oResult = array();
		try
		{
			$oCalendars = array();
			$oCalendarsOwn = $this->oStorage->GetCalendars($oAccount);
			
			if ($this->oApiCapabilityManager->IsCalendarSharingSupported($oAccount))
			{
				$oCalendarsSharedToAll = array();
				
				$aCalendarsSharedToAllIds = array_map(
					function($oCalendar) { 
						if ($oCalendar->SharedToAll)
						{
							return $oCalendar->IntId; 
						}
					}, 
					$oCalendarsOwn
				);
				$aCalendarsOwnIds = array_map(
					function($oCalendar) { 
						if (!$oCalendar->SharedToAll && !$oCalendar->Shared)
						{
							return $oCalendar->IntId; 
						}					
					}, 
					$oCalendarsOwn
				);
/*				foreach ($oCalendarsShared as $oCalendarShared)
				{
					if (in_array($oCalendarShared->IntId, $aCalendarsSharedToAllIds))
					{
						$oCalendarShared->SharedToAll = true;
					}
					$oCalendarsSharedToAll[$oCalendarShared->IntId] = $oCalendarShared;
				}
*/
				foreach ($oCalendarsOwn as $oCalendarOwn)
				{
					if (in_array($oCalendarOwn->IntId, $aCalendarsSharedToAllIds))
					{
						$oCalendarOwn->Shared = true;
						$oCalendarOwn->SharedToAll = true;
					}
					$oCalendarsSharedToAll[$oCalendarOwn->IntId] = $oCalendarOwn;
				}
				$oCalendars = $oCalendarsSharedToAll;
			}
			else
			{
				$oCalendars = $oCalendarsOwn;
			}
			
			$bDefault = false;
			foreach ($oCalendars as $oCalendar)
			{
				if (!$bDefault && $oCalendar->Access !== ECalendarPermission::Read)
				{
					$oCalendar->IsDefault = $bDefault = true;
				}
				$oCalendar = $this->PopulateCalendarShares($oAccount, $oCalendar);
				$oResult[] = $this->GetCalendarAsArray($oAccount, $oCalendar);
			}
			
			if (is_array($oResult) && count($oResult) > 0)
			{
				$oResult[0]['IsDefault'] = true;
			}
			
//			uasort($oResult['user'], array(&$this, '___qSortCallback'));
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sName
	 * @param string $sDescription
	 * @param int $iOrder
	 * @param string $sColor
	 */
	public function CreateCalendar($oAccount, $sName, $sDescription, $iOrder, $sColor)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->CreateCalendar($oAccount, $sName, $sDescription, $iOrder, $sColor);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sName
	 * @param string $sDescription
	 * @param int $iOrder
	 * @param string $sColor
	 */
	public function UpdateCalendar($oAccount, $sCalendarId, $sName, $sDescription, $iOrder, $sColor)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->UpdateCalendar($oAccount, $sCalendarId, $sName, $sDescription, $iOrder, $sColor);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param string $sCalendarId
	 * @param int $iVisible
	 */
	public function UpdateCalendarVisible($sCalendarId, $iVisible)
	{
		$oResult = null;
		try
		{
			$this->oStorage->UpdateCalendarVisible($sCalendarId, $iVisible);
			$oResult = true;
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sColor
	 */
	public function UpdateCalendarColor($oAccount, $sCalendarId, $sColor)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->UpdateCalendarColor($oAccount, $sCalendarId, $sColor);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 */
	public function DeleteCalendar($oAccount, $sCalendarId)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->DeleteCalendar($oAccount, $sCalendarId);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 */
	public function UnsubscribeCalendar($oAccount, $sCalendarId)
	{
		$oResult = null;
		if ($this->oApiCapabilityManager->IsCalendarSharingSupported($oAccount))
		{
			try
			{
				$oResult = $this->oStorage->UnsubscribeCalendar($oAccount, $sCalendarId);
			}
			catch (Exception $oException)
			{
				$oResult = false;
				$this->setLastException($oException);
			}
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sUserId
	 * @param int $iPermission
	 */
	public function UpdateCalendarShare($oAccount, $sCalendarId, $sUserId, $iPermission)
	{
		$oResult = null;
		if ($this->oApiCapabilityManager->IsCalendarSharingSupported($oAccount))
		{
			try
			{
				$oResult = $this->oStorage->UpdateCalendarShare($oAccount, $sCalendarId, $sUserId, $iPermission);
			}
			catch (Exception $oException)
			{
				$oResult = false;
				$this->setLastException($oException);
			}
		}
		return $oResult;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param array $aShares
	 */
	public function UpdateCalendarShares($oAccount, $sCalendarId, $aShares)
	{
		$oResult = null;
		if ($this->oApiCapabilityManager->IsCalendarSharingSupported($oAccount))
		{
			try
			{
				$oResult = $this->oStorage->UpdateCalendarShares($oAccount, $sCalendarId, $aShares);
			}
			catch (Exception $oException)
			{
				$oResult = false;
				$this->setLastException($oException);
			}
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param bool $bShareToAll
	 * @param int $iPermission
	 */
	public function UpdateCalendarShareToAll($oAccount, $sCalendarId, $bShareToAll, $iPermission)
	{
		$sUserId = $this->GetTenantUser($oAccount);
		$aShares[] = array(
			'name' => $sUserId,
			'email' => $sUserId,
			'access' => $bShareToAll ? $iPermission : \ECalendarPermission::RemovePermission
		);
		
		return $this->UpdateCalendarShares($oAccount, $sCalendarId, $aShares);
	}	
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param bool $bIsPublic
	 */
	public function PublicCalendar($oAccount, $sCalendarId, $bIsPublic = false)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->PublicCalendar($oAccount, $sCalendarId, $bIsPublic);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sUserId
	 */
	public function DeleteCalendarShare($oAccount, $sCalendarId, $sUserId)
	{
		$oResult = null;
		try
		{
			$oResult = $this->UpdateCalendarShare($oAccount, $sCalendarId, $sUserId);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param FileInfo $oCalendar
	 */
	public function GetCalendarUsers($oAccount, $oCalendar)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->GetCalendarUsers($oAccount, $oCalendar);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @return string
	 */
	public function ExportCalendarToIcs($oAccount, $sCalendarId)
	{
		$mResult = null;
		try
		{
			$mResult = $this->oStorage->ExportCalendarToIcs($oAccount, $sCalendarId);
		}
		catch (Exception $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sTempFileName
	 * @return string
	 */
	public function ImportToCalendarFromIcs($oAccount, $sCalendarId, $sTempFileName)
	{
		$mResult = null;
		try
		{
			$mResult = $this->oStorage->ImportToCalendarFromIcs($oAccount, $sCalendarId, $sTempFileName);
		}
		catch (Exception $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}
	
	
	// Events

	/**
	 * @param CAccount $oAccount
	 * @param array | string $mCalendarId
	 * @param string $dStart
	 * @param string $dFinish
	 */
	public function GetEvents($oAccount, $mCalendarId, $dStart = null, $dFinish = null)
	{
		$aResult = array();
		try
		{
			$dStart = ($dStart != null) ? date('Ymd\T000000\Z', $dStart/*  + 86400*/) : null;
			$dFinish = ($dFinish != null) ? date('Ymd\T235959\Z', $dFinish) : null;
			$mCalendarId = !is_array($mCalendarId) ? array($mCalendarId) : $mCalendarId;
			
			foreach ($mCalendarId as $sCalendarId) 
			{
				$aEvents = $this->oStorage->GetEvents($oAccount, $sCalendarId, $dStart, $dFinish);
				if ($aEvents && is_array($aEvents))
				{
					$aResult = array_merge($aResult, $aEvents);
				}
			}
		}
		catch (Exception $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param string $sCalendarId
	 * @param string $dStart
	 * @param string $dFinish
	 */
	public function GetPublicEvents($sCalendarId, $dStart = null, $dFinish = null)
	{
		return $this->GetEvents($this->GetPublicAccount(), $sCalendarId, $dStart, $dFinish);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 */
	public function GetEvent($oAccount, $sCalendarId, $sEventId)
	{
		$mResult = null;
		try
		{
			$mResult = array();
			$aData = $this->oStorage->GetEvent($oAccount, $sCalendarId, $sEventId);
			if ($aData !== false)
			{
				if (isset($aData['vcal']))
				{
					$oVCal = $aData['vcal'];
					$oCalendar = $this->oStorage->GetCalendar($oAccount, $sCalendarId);
					$mResult = CalendarParser::ParseEvent($oAccount, $oCalendar, $oVCal);
					$mResult['vcal'] = $oVCal;
				}
			}
		}
		catch (Exception $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 */
	public function GetBaseEvent($oAccount, $sCalendarId, $sEventId)
	{
		$mResult = null;
		try
		{
			$mResult = array();
			$aData = $this->oStorage->GetEvent($oAccount, $sCalendarId, $sEventId);
			if ($aData !== false)
			{
				if (isset($aData['vcal']))
				{
					$oVCal = $aData['vcal'];
					$oVCalOriginal = clone $oVCal;
					$oCalendar = $this->oStorage->GetCalendar($oAccount, $sCalendarId);
					$oVEvent = $oVCal->getBaseComponents('VEVENT');
					if (isset($oVEvent[0]))
					{
						unset($oVCal->VEVENT);
						$oVCal->VEVENT = $oVEvent[0];
					}
					$oEvent = CalendarParser::ParseEvent($oAccount, $oCalendar, $oVCal, $oVCalOriginal);
					if (isset($oEvent[0]))
					{
						$mResult = $oEvent[0];
					}
				}
			}
		}
		catch (Exception $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @param string $dStart
	 * @param string $dEnd
	 */
	public function GetExpandedEvent($oAccount, $sCalendarId, $sEventId, $dStart = null, $dEnd = null)
	{
		$mResult = null;
		
		try
		{
			$dStart = ($dStart != null) ? date('Ymd\T000000\Z', $dStart/*  + 86400*/) : null;
			$dEnd = ($dEnd != null) ? date('Ymd\T235959\Z', $dEnd) : null;
			$mResult = $this->oStorage->GetExpandedEvent($oAccount, $sCalendarId, $sEventId, $dStart, $dEnd);
		}
		catch (Exception $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}	

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @param array $sData
	 */
	public function CreateEventFromRaw($oAccount, $sCalendarId, $sEventId, $sData)
	{
		$oResult = null;
		$aEvents = array();
		try
		{
			$oVCal = \Sabre\VObject\Reader::read($sData);
			if ($oVCal && $oVCal->VEVENT)
			{
				if (!empty($sEventId))
				{
					$oResult = $this->oStorage->CreateEvent($oAccount, $sCalendarId, $sEventId, $oVCal);
				}
				else
				{
					foreach ($oVCal->VEVENT as $oVEvent)
					{
						$sUid = (string)$oVEvent->UID;
						if (!isset($aEvents[$sUid]))
						{
							$aEvents[$sUid] = new \Sabre\VObject\Component\VCalendar();
						}
						$aEvents[$sUid]->add($oVEvent);
					}

					foreach ($aEvents as $sUid => $oVCalNew)
					{
						$this->oStorage->CreateEvent($oAccount, $sCalendarId, $sUid, $oVCalNew);
					}
				}
			}
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param CEvent $oEvent
	 */
	public function CreateEvent($oAccount, $oEvent)
	{
		$oResult = null;
		try
		{
			$oEvent->Id = \Sabre\DAV\UUIDUtil::getUUID();

			$oVCal = new \Sabre\VObject\Component\VCalendar();
			$oVCal->add('VEVENT', array(
				'SEQUENCE' => 1,
				'TRANSP' => 'OPAQUE',
				'DTSTAMP' => new \DateTime('now')
			));

			CCalendarHelper::PopulateVCalendar($oAccount, $oEvent, $oVCal->VEVENT);
			
			$oResult = $this->oStorage->CreateEvent($oAccount, $oEvent->IdCalendar, $oEvent->Id, $oVCal);

			if ($oResult)
			{
				$this->UpdateEventGroups($oAccount, $oEvent);
			}
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param CEvent $oEvent
	 */
	public function UpdateEvent($oAccount, $oEvent)
	{
		$oResult = null;
		try
		{
			$aData = $this->oStorage->GetEvent($oAccount, $oEvent->IdCalendar, $oEvent->Id);
			if ($aData !== false)
			{
				$oVCal = $aData['vcal'];

				if ($oVCal)
				{
					$iIndex = CCalendarHelper::GetBaseVEventIndex($oVCal->VEVENT);
					if ($iIndex !== false)
					{
						CCalendarHelper::PopulateVCalendar($oAccount, $oEvent, $oVCal->VEVENT[$iIndex]);
					}
					$oVCalCopy = clone $oVCal;
					if (!isset($oEvent->RRule))
					{
						unset($oVCalCopy->VEVENT);
						foreach ($oVCal->VEVENT as $oVEvent)
						{
							if (!isset($oVEvent->{'RECURRENCE-ID'}))
							{
								$oVCalCopy->add($oVEvent);
							}
						}
					}
					$oResult = $this->oStorage->UpdateEvent($oAccount, $oEvent->IdCalendar, $oEvent->Id, $oVCalCopy);
					if ($oResult)
					{
						$this->UpdateEventGroups($oAccount, $oEvent);
					}
					
				}
			}
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function MoveEvent($oAccount, $sCalendarId, $sCalendarIdNew, $sEventId)
	{
		$oResult = null;
		try
		{
			$aData = $this->oStorage->GetEvent($oAccount, $sCalendarId, $sEventId);
			if ($aData !== false && isset($aData['vcal']) && $aData['vcal'] instanceof \Sabre\VObject\Component\VCalendar)
			{
				$oResult = $this->oStorage->MoveEvent($oAccount, $sCalendarId, $sCalendarIdNew, $sEventId, $aData['vcal']->serialize());
				$this->UpdateEventGroupByMoving($sCalendarId, $sEventId, $sCalendarIdNew);				
				return true;
			}
			return false;
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 */
	public function DeleteEvent($oAccount, $sCalendarId, $sEventId)
	{
		$oResult = false;
		try
		{
			$aData = $this->oStorage->GetEvent($oAccount, $sCalendarId, $sEventId);
			if ($aData !== false && isset($aData['vcal']) && $aData['vcal'] instanceof \Sabre\VObject\Component\VCalendar)
			{
				$oVCal = $aData['vcal'];

				$iIndex = CCalendarHelper::GetBaseVEventIndex($oVCal->VEVENT);
				if ($iIndex !== false)
				{
					$oVEvent = $oVCal->VEVENT[$iIndex];

					$sOrganizer = (isset($oVEvent->ORGANIZER)) ? 
							str_replace('mailto:', '', strtolower((string)$oVEvent->ORGANIZER)) : null;

					if (isset($sOrganizer))
					{
						if ($sOrganizer === $oAccount->Email)
						{
							if (isset($oVEvent->ATTENDEE))
							{
								foreach($oVEvent->ATTENDEE as $oAttendee)
								{
									$sEmail = str_replace('mailto:', '', strtolower((string)$oAttendee));

									$oVCal->METHOD = 'CANCEL';
									$sSubject = (string)$oVEvent->SUMMARY . ': Canceled';

									CCalendarHelper::SendAppointmentMessage($oAccount, $sEmail, $sSubject, $oVCal->serialize(), 'REQUEST');
									unset($oVCal->METHOD);
								}
							}
						}
/*
						else
						{
							$oVEvent->{'LAST-MODIFIED'} = gmdate("Ymd\THis\Z");
							unset($oVEvent->ATTENDEE);
							$oVEvent->add('ATTENDEE', 'mailto:'.$oAccount->Email, array(
								'CN' => $oAccount->FriendlyName,
								'PARTSTAT' => 'DECLINED',
								'RESPONDED-AT' => gmdate("Ymd\THis\Z")
							));

							$oVCal->METHOD = 'REPLY';
							$sSubject = (string)$oVEvent->SUMMARY . ': Declined';

							CCalendarHelper::SendAppointmentMessage($oAccount, $sOrganizer, $sSubject, $oVCal->serialize(), (string)$oVCal->METHOD);

							unset($oVCal->METHOD);
						}
*/
					}
				}
				$oResult = $this->oStorage->DeleteEvent($oAccount, $sCalendarId, $sEventId);
				if ($oResult)
				{
					$this->oApiContacts = \CApi::Manager('contacts');
					$this->oApiContacts->RemoveEventFromAllGroups($sCalendarId, $sEventId);
				}
			}
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param CEvent $oEvent
	 * @param string $sRecurrenceId
	 * @param bool $bDelete
	 */
	public function UpdateExclusion($oAccount, $oEvent, $sRecurrenceId, $bDelete = false)
	{
		$oResult = null;
		try
		{
			$aData = $this->oStorage->GetEvent($oAccount, $oEvent->IdCalendar, $oEvent->Id);
			if ($aData !== false && isset($aData['vcal']) && $aData['vcal'] instanceof \Sabre\VObject\Component\VCalendar)
			{
				$oVCal = $aData['vcal'];
				$iIndex = CCalendarHelper::GetBaseVEventIndex($oVCal->VEVENT);
				if ($iIndex !== false)
				{
					unset($oVCal->VEVENT[$iIndex]->{'LAST-MODIFIED'});
					$oVCal->VEVENT[$iIndex]->add('LAST-MODIFIED', new \DateTime('now'));

					$oDTExdate = CCalendarHelper::PrepareDateTime($sRecurrenceId, $oAccount->GetDefaultStrTimeZone());
					$oDTStart = $oVCal->VEVENT[$iIndex]->DTSTART->getDatetime();

					$mIndex = CCalendarHelper::isRecurrenceExists($oVCal->VEVENT, $sRecurrenceId);
					if ($bDelete)
					{
						// if exclude first event in occurrence
						if ($oDTExdate == $oDTStart)
						{
							$it = new \Sabre\VObject\RecurrenceIterator($oVCal, (string) $oVCal->VEVENT[$iIndex]->UID);
							$it->fastForward($oDTStart);
							$it->next();
							
							if ($it->valid())
							{
								$oEventObj = $it->getEventObject();
							}
							
							$oVCal->VEVENT[$iIndex]->DTSTART = $oEventObj->DTSTART;
							$oVCal->VEVENT[$iIndex]->DTEND = $oEventObj->DTEND;
						}

						$oVCal->VEVENT[$iIndex]->add('EXDATE', $oDTExdate);

						if (false !== $mIndex)
						{
							$aVEvents = $oVCal->VEVENT;
							unset($oVCal->VEVENT);

							foreach($aVEvents as $oVEvent)
							{
								if ($oVEvent->{'RECURRENCE-ID'})
								{
									$iRecurrenceId = CCalendarHelper::GetStrDate($oVEvent->{'RECURRENCE-ID'},
											$oAccount->GetDefaultStrTimeZone(), 'Ymd');
									if ($iRecurrenceId == (int) $sRecurrenceId)
									{
										continue;
									}
								}
								$oVCal->add($oVEvent);
							}
						}
					}
					else
					{
						$oVEventRecur = null;
						if ($mIndex === false)
						{
							$oVEventRecur = $oVCal->add('VEVENT', array(
								'SEQUENCE' => 1,
								'TRANSP' => 'OPAQUE',
								'RECURRENCE-ID' => $oDTExdate
							));
						}
						else if (isset($oVCal->VEVENT[$mIndex]))
						{
							$oVEventRecur = $oVCal->VEVENT[$mIndex];
						}
						if ($oVEventRecur)
						{
							$oEvent->RRule = null;
							CCalendarHelper::PopulateVCalendar($oAccount, $oEvent, $oVEventRecur);
						}
					}

					return $this->oStorage->UpdateEvent($oAccount, $oEvent->IdCalendar, $oEvent->Id, $oVCal);

				}
			}
			return false;
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @param string $iRecurrenceId
	 */
	public function DeleteExclusion($oAccount, $sCalendarId, $sEventId, $iRecurrenceId)
	{
		$oResult = null;
		try
		{
			$aData = $this->oStorage->GetEvent($oAccount, $sCalendarId, $sEventId);
			if ($aData !== false && isset($aData['vcal']) && $aData['vcal'] instanceof \Sabre\VObject\Component\VCalendar)
			{
				$oVCal = $aData['vcal'];

				$aVEvents = $oVCal->VEVENT;
				unset($oVCal->VEVENT);

				foreach($aVEvents as $oVEvent)
				{
					if (isset($oVEvent->{'RECURRENCE-ID'}))
					{
						$iServerRecurrenceId = CCalendarHelper::GetStrDate($oVEvent->{'RECURRENCE-ID'},
								$oAccount->GetDefaultStrTimeZone(), 'Ymd');
						if ($iRecurrenceId == $iServerRecurrenceId)
						{
							continue;
						}
					}
					$oVCal->add($oVEvent);
				}
				return $this->oStorage->UpdateEvent($oAccount, $sCalendarId, $sEventId, $oVCal);
			}
			return false;
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function GetReminders($start = null, $end = null)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->GetReminders($start, $end);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function DeleteReminder($eventId)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->DeleteReminder($eventId);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function DeleteReminderByCalendar($calendarUri)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->DeleteReminderByCalendar($calendarUri);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	public function UpdateReminder($sEmail, $sCalendarUri, $sEventId, $sData)
	{
		$oResult = null;
		try
		{
			$oResult = $this->oStorage->UpdateReminder($sEmail, $sCalendarUri, $sEventId, $sData);
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sData
	 * @param string $mFromEmail
	 * @param bool $bUpdateAttendeeStatus
	 * @return array | bool
	 */
	public function ProcessICS($oAccount, $sData, $mFromEmail, $bUpdateAttendeeStatus = false)
	{
		$mResult = false;
		
		/* @var $oDefaultAccount CAccount */
		$oDefaultAccount = $oAccount->IsDefaultAccount ? $oAccount : $this->ApiUsersManager->GetDefaultAccount($oAccount->IdUser);

		$aAccountEmails = array();
		$aUserAccounts = $this->ApiUsersManager->GetUserAccountListInformation($oAccount->IdUser);
		foreach ($aUserAccounts as $aUserAccount)
		{
			if (isset($aUserAccount) && isset($aUserAccount[1]))
			{
				$aAccountEmails[] = $aUserAccount[1];
			}
		}
		$oApiFetchers = \CApi::Manager('fetchers');
		$aFetchers = $oApiFetchers->GetFetchers($oDefaultAccount);
		if (is_array($aFetchers) && 0 < count($aFetchers))
		{
			foreach ($aFetchers as /* @var $oFetcher \CFetcher */ $oFetcher)
			{
				if ($oFetcher)
				{
					$aAccountEmails[] = !empty($oFetcher->Email) ? $oFetcher->Email : $oFetcher->IncomingMailLogin;
				}
			}
		}
		$aIdentities = $this->ApiUsersManager->GetIdentitiesByUserID($oAccount);
		if (is_array($aIdentities) && 0 < count($aIdentities))
		{
			foreach ($aIdentities as /* @var $oIdentity \CIdentity */ $oIdentity)
			{
				if ($oIdentity)
				{
					$aAccountEmails[] = $oIdentity->Email;
				}
			}
		}
		
		try
		{
			$oVCal = \Sabre\VObject\Reader::read($sData);
			if ($oVCal)
			{
				$oVCalResult = $oVCal;
				
				$oMethod = isset($oVCal->METHOD) ? $oVCal->METHOD : null;
				$sMethod = isset($oMethod) ? (string) $oMethod : 'SAVE';

				if (!in_array($sMethod, array('REQUEST', 'REPLY', 'CANCEL', 'PUBLISH'))) // TODO added PUBLISH
				{
					return false;
				}

				$aVEvents = $oVCal->getBaseComponents('VEVENT');
				$oVEvent = (isset($aVEvents) && count($aVEvents) > 0) ? $aVEvents[0] : null;
				
				if (isset($oVEvent))
				{
					$sCalendarId = '';
					$oVEventResult = $oVEvent;
	
					$sEventId = (string)$oVEventResult->UID;

					$aCalendars = $this->oStorage->GetCalendarNames($oDefaultAccount);
					$aCalendarIds = $this->oStorage->FindEventInCalendars($oDefaultAccount, $sEventId, $aCalendars);
					if (is_array($aCalendarIds) && isset($aCalendarIds[0]))
					{
						$sCalendarId = $aCalendarIds[0];
						$aDataServer = $this->oStorage->GetEvent($oDefaultAccount, $sCalendarId, $sEventId);
						if ($aDataServer !== false)
						{
							$oVCalServer = $aDataServer['vcal'];
							if (isset($oMethod))
							{
								$oVCalServer->METHOD = $oMethod;
							}
							$aVEventsServer = $oVCalServer->getBaseComponents('VEVENT');
							if (count($aVEventsServer) > 0)
							{
								$oVEventServer = $aVEventsServer[0];

								if (isset($oVEvent->{'LAST-MODIFIED'}) && isset($oVEventServer->{'LAST-MODIFIED'}))
								{
									$lastModified = $oVEvent->{'LAST-MODIFIED'}->getDateTime();
									$lastModifiedServer = $oVEventServer->{'LAST-MODIFIED'}->getDateTime();
									if ($lastModifiedServer > $lastModified)
									{
										$oVCalResult = $oVCalServer;
										$oVEventResult = $oVEventServer;
									}
									else if (isset($sMethod))
									{
										if ($sMethod === 'REPLY')
										{
											$oVCalResult = $oVCalServer;
											$oVEventResult = $oVEventServer;

											if (isset($oVEvent->ATTENDEE))
											{
												$oAttendee = $oVEvent->ATTENDEE[0];
												$sAttendee = str_replace('mailto:', '', strtolower((string)$oAttendee));
												if (isset($oVEventResult->ATTENDEE))
												{
													foreach ($oVEventResult->ATTENDEE as $oAttendeeResult)
													{
														$sEmailResult = str_replace('mailto:', '', strtolower((string)$oAttendeeResult));
														if ($sEmailResult === $sAttendee)
														{
															if (isset($oAttendee['PARTSTAT']))
															{
																$oAttendeeResult['PARTSTAT'] = $oAttendee['PARTSTAT']->getValue();
															}
															break;
														}
													}
												}
											}
											if ($bUpdateAttendeeStatus)
											{
												unset($oVCalResult->METHOD);
												$oVEventResult->{'LAST-MODIFIED'} = gmdate("Ymd\THis\Z");
												$mResult = $this->oStorage->UpdateEventRaw($oDefaultAccount, $sCalendarId, $sEventId, $oVCalResult->serialize());
												$oVCalResult->METHOD = $sMethod;
											}
										}
										else if ($sMethod === 'CANCEL' && $bUpdateAttendeeStatus)
										{
											if ($this->DeleteEvent($oDefaultAccount, $sCalendarId, $sEventId))
											{
												$mResult = true;
											}
										}
									}
								}
							}
						}
					}
					
					if (!$bUpdateAttendeeStatus)
					{
						$sTimeFormat = (isset($oVEventResult->DTSTART) && !$oVEventResult->DTSTART->hasTime()) ? 'D, M d' : 'D, M d, Y, H:i';
						$mResult = array(
							'Calendars' => $aCalendars,
							'CalendarId' => $sCalendarId,
							'UID' => $sEventId,
							'Body' => $oVCalResult->serialize(),
							'Action' => $sMethod,
							'Location' => isset($oVEventResult->LOCATION) ? (string)$oVEventResult->LOCATION : '',
							'Description' => isset($oVEventResult->DESCRIPTION) ? (string)$oVEventResult->DESCRIPTION : '',
							'When' => CCalendarHelper::GetStrDate($oVEventResult->DTSTART, $oDefaultAccount->GetDefaultStrTimeZone(), $sTimeFormat)
						);

						$aAccountEmails = ($sMethod === 'REPLY') ? array($mFromEmail) : $aAccountEmails;
						if (isset($oVEventResult->ATTENDEE))
						{
							foreach($oVEventResult->ATTENDEE as $oAttendee)
							{
								$sAttendee = str_replace('mailto:', '', strtolower((string)$oAttendee));
								if (in_array($sAttendee, $aAccountEmails) && isset($oAttendee['PARTSTAT']))
								{
									$mResult['Attendee'] = $sAttendee;
									$mResult['Action'] = $sMethod . '-' . $oAttendee['PARTSTAT']->getValue();
								}
							}
						}
					}
				}
			}
		}
		catch (Exception $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}

		return $mResult;
	}

	/**
	 * @param CAccount | string $mAccount
	 * @param string $sAttendee
	 * @param string $sAction
	 * @param string $sCalendarId
	 * @param string $sData
	 * @param bool $bExternal
	 */
	public function AppointmentAction($oAccount, $sAttendee, $sAction, $sCalendarId, $sData, $bExternal = false)
	{
		$oDefaultAccount = null;
		$oAttendeeAccount = null;
		$bDefaultAccountAsEmail = false;
		
		if (isset($oAccount) && is_object($oAccount) &&  $oAccount instanceof \CAccount)
		{
			$bDefaultAccountAsEmail = false;
			/* @var $oDefaultAccount CAccount */
			$oDefaultAccount = $this->ApiUsersManager->GetDefaultAccount($oAccount->IdUser);
		}
		else
		{
			$oAttendeeAccount = $this->ApiUsersManager->GetAccountOnLogin($sAttendee);
			if ($oAttendeeAccount)
			{
				$bDefaultAccountAsEmail = false;
				$oDefaultAccount = $oAttendeeAccount;
			}
			else
			{
				$bDefaultAccountAsEmail = true;
			}
		}
		if (!$bDefaultAccountAsEmail)
		{
			$oCalendar = $this->GetDefaultCalendar($oDefaultAccount);
			if ($oCalendar)
			{
				$sCalendarId = $oCalendar['Id'];
			}
		}

		$bResult = false;
		$sEventId = null;
		try
		{
			$sTo = $sSubject = $sBody = $sSummary = '';

			$oVCal = \Sabre\VObject\Reader::read($sData);
			if ($oVCal)
			{
				$sMethod = $sMethodOriginal = (string)$oVCal->METHOD;
				$aVEvents = $oVCal->getBaseComponents('VEVENT');

				if (isset($aVEvents) && count($aVEvents) > 0)
				{
					$oVEvent = $aVEvents[0];
					$sEventId = (string)$oVEvent->UID;
					$bAllDay = (isset($oVEvent->DTSTART) && !$oVEvent->DTSTART->hasTime());

					if (isset($oVEvent->SUMMARY))
					{
						$sSummary = (string)$oVEvent->SUMMARY;
					}
					if (isset($oVEvent->ORGANIZER))
					{
						$sTo = str_replace('mailto:', '', strtolower((string)$oVEvent->ORGANIZER));
					}
					if (strtoupper($sMethod) === 'REQUEST')
					{
						$sMethod = 'REPLY';
						$sSubject = $sSummary;

						unset($oVEvent->ATTENDEE);
						$sPartstat = strtoupper($sAction);
						switch ($sPartstat)
						{
							case 'ACCEPTED':
								$sSubject = 'Accepted: '. $sSubject;
								break;
							case 'DECLINED':
								$sSubject = 'Declined: '. $sSubject;
								break;
							case 'TENTATIVE':
								$sSubject = 'Tentative: '. $sSubject;
								break;
						}
						
						$oVEvent->add('ATTENDEE', 'mailto:'.$sAttendee, array(
							'CN' => isset($oDefaultAccount) && ($sAttendee ===  $oDefaultAccount->Email) ? $oDefaultAccount->FriendlyName : '',
							'PARTSTAT' => $sPartstat,
							'RESPONDED-AT' => gmdate("Ymd\THis\Z")
						));
					}

					$oVCal->METHOD = $sMethod;
					$oVEvent->{'LAST-MODIFIED'} = gmdate("Ymd\THis\Z");

					$sBody = $oVCal->serialize();

					if ($sCalendarId !== false && $bExternal === false && !$bDefaultAccountAsEmail)
					{
						unset($oVCal->METHOD);
						if (strtoupper($sAction) == 'DECLINED' || strtoupper($sMethod) == 'CANCEL')
						{
							$this->DeleteEvent($oDefaultAccount, $sCalendarId, $sEventId);
						}
						else
						{
							$this->oStorage->UpdateEventRaw($oDefaultAccount, $sCalendarId, $sEventId, $oVCal->serialize());
						}
					}

					if (strtoupper($sMethodOriginal) == 'REQUEST'/* && (strtoupper($sAction) !== 'DECLINED')*/)
					{
						if (!empty($sTo) && !empty($sBody))
						{
							$oToAccount = $this->ApiUsersManager->GetAccountOnLogin($sTo);
							if ($oToAccount)
							{
								$bResult = ($this->ProcessICS($oToAccount, $sBody, $sAttendee, true) !== false);
							}
							if ((!$oToAccount || !$bResult) && $oDefaultAccount instanceof \CAccount)
							{
								if (!$oAttendeeAccount)
								{
									$oAttendeeAccount = $this->GetAccountFromAccountList($oAccount, $sAttendee);
								}
								if (!($oAttendeeAccount instanceof \CAccount))
								{
									$oAttendeeAccount = $oDefaultAccount;
								}
								$bResult = CCalendarHelper::SendAppointmentMessage($oAttendeeAccount, $sTo, $sSubject, $sBody, $sMethod, $bAllDay);
							}
						}
					}
					else
					{
						$bResult = true;
					}
				}
			}

			if (!$bResult)
			{
				CApi::Log('Ics Appointment Action FALSE result!', ELogLevel::Error);
				if ($oAccount)
				{
					CApi::Log('Email: '.$oAccount->Email.', Action: '. $sAction.', Data:', ELogLevel::Error);
				}
				CApi::Log($sData, ELogLevel::Error);
			}
			else
			{
				$bResult = $sEventId;
			}

			return $bResult;
		}
		catch (Exception $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	public function UpdateAppointment($oAccount, $sCalendarId, $sEventId, $sAttendee, $sAction)
	{
		$oResult = null;
		try
		{
			$aData = $this->oStorage->GetEvent($oAccount, $sCalendarId, $sEventId);
			if ($aData !== false)
			{
				$oVCal = $aData['vcal'];
				$oVCal->METHOD = 'REQUEST';
				return $this->AppointmentAction($oAccount, $sAttendee, $sAction, $sCalendarId, $oVCal->serialize());
			}
		}
		catch (Exception $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function ClearAllCalendars($oAccount)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->ClearAllCalendars($oAccount);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}
	
	public function UpdateEventGroups($oAccount, $oEvent)
	{
		$aGroups = CCalendarHelper::FindGroupsHashTagsFromString($oEvent->Name);
		$aGroupsDescription = CCalendarHelper::FindGroupsHashTagsFromString($oEvent->Description);
		$aGroups = array_merge($aGroups, $aGroupsDescription);
		$aGroupsLocation = CCalendarHelper::FindGroupsHashTagsFromString($oEvent->Location);
		$aGroups = array_merge($aGroups, $aGroupsLocation);

		
		$this->oApiContacts = \CApi::Manager('contacts');
		foreach ($aGroups as $sGroup)
		{
			$sGroupName = ltrim($sGroup, '#');
			$oGroup = $this->oApiContacts->GetGroupByName($oAccount->IdUser, $sGroupName);
			if (!$oGroup)
			{
				$oGroup = new \CGroup();
				$oGroup->IdUser = $oAccount->IdUser;
				$oGroup->Name = $sGroupName;
				$this->oApiContacts->CreateGroup($oGroup);
			}

			$this->oApiContacts->RemoveEventFromGroup($oGroup->IdGroup, $oEvent->IdCalendar, $oEvent->Id);
			$this->oApiContacts->AddEventToGroup($oGroup->IdGroup, $oEvent->IdCalendar, $oEvent->Id);
		}
	}
	
	public function UpdateEventGroupByMoving($sCalendarId, $sEventId, $sNewCalendarId)
	{
		$this->oApiContacts = \CApi::Manager('contacts');
		
		$aEvents = $this->oApiContacts->GetGroupEvent($sCalendarId, $sEventId);
		if (is_array($aEvents) && 0 < count($aEvents))
		{
			foreach ($aEvents as $aEvent)
			{
				if (isset($aEvent['id_group']))
				{
					$this->oApiContacts->RemoveEventFromGroup($aEvent['id_group'], $sCalendarId, $sEventId);
					$this->oApiContacts->AddEventToGroup($aEvent['id_group'], $sNewCalendarId, $sEventId);
				}
			}
		}
	}
	
	
}
