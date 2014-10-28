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
class CApiCalendarSabredavStorage extends CApiCalendarStorage
{
	/**
	 * @var array
	 */
	public $Principal;

	/*
	 * @var CAccount
	 */
	public $Account;

	/*
	 * @var array
	 */
	protected $CalendarsCache;

	/*
	 * @var array
	 */
	protected $CalDAVCalendarsCache;

	/*
	 * @var array
	 */
	protected $CalDAVCalendarObjectsCache;
	
	/*
	 * @var string
	 */
	protected $TenantUser;

	/**
	 * @param CApiGlobalManager $oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('sabredav', $oManager);

		$this->Account = null;
		$this->TenantUser = null;
		$this->Principal = array();

		$this->CalendarsCache = array();
		$this->CalDAVCalendarsCache = array();
		$this->CalDAVCalendarObjectsCache = array();
	}
	
    /**
	 * @param CAccount $oAccount
     * @return bool
     */		
	protected function Initialized($oAccount)
	{
		return ($oAccount !== null && $this->Account !== null && $this->Account->Email === $oAccount->Email);
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function Init($oAccount)
	{
		if (!$this->Initialized($oAccount))
		{
			$this->Account = $oAccount;
			\afterlogic\DAV\Auth\Backend::getInstance()->setCurrentUser($oAccount->Email);
			\afterlogic\DAV\Utils::CheckPrincipals($oAccount->Email);

			$this->Principal = $this->GetPrincipalInfo($oAccount->Email);
		}
	}
	
	public function GetBackend()
	{
		return \afterlogic\DAV\Backends::Caldav();
	}

	public function GetPrincipalInfo($sEmail)
	{
		$aPrincipal = array();

		$aPrincipalProperties = \afterlogic\DAV\Backends::Principal()->getPrincipalByPath(\afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $sEmail);
		if ($aPrincipalProperties)
		{
			if (isset($aPrincipalProperties['uri']))
			{
				$aPrincipal['uri'] = $aPrincipalProperties['uri'];
				$aPrincipal['id'] = $aPrincipalProperties['id'];
			}
		}
		return $aPrincipal;
	}

	public function GetCalendarAccess($oAccount, $sCalendarId)
	{
		$mResult = ECalendarPermission::Read;
		$oCalendar = $this->GetCalendar($oAccount, $sCalendarId);
		if ($oCalendar)
		{
			$mResult = $oCalendar->Shared ? $oCalendar->Access : ECalendarPermission::Write;
		}
		return $mResult;
	}

    /**
     * Returns a single calendar, by name
     *
     * @param string $sPath
     * @return \Sabre\CalDAV\Calendar | bool
     */	
	protected function GetCalDAVCalendar($sPath)
	{
		$oCalendar = false;
		list(, $sCalendarId) = \Sabre\DAV\URLUtil::splitPath($sPath);
		if (count($this->CalDAVCalendarsCache) > 0 && isset($this->CalDAVCalendarsCache[$sCalendarId][$this->Account->Email]))
		{
			$oCalendar = $this->CalDAVCalendarsCache[$sCalendarId][$this->Account->Email];
		}
		else
		{
			$oCalendars = new \afterlogic\DAV\CalDAV\UserCalendars($this->GetBackend(), $this->Principal);
			if (isset($oCalendars) && $oCalendars->childExists($sCalendarId))
			{
				$oCalendar = $oCalendars->getChild($sCalendarId);
				$this->CalDAVCalendarsCache[$sCalendarId][$this->Account->Email] = $oCalendar;
			}
		}
	
		return $oCalendar;
	}

    /**
     * @param \Sabre\CalDAV\Calendar $oCalDAVCalendar
     * @return \CCalendar
     */	
	public function ParseCalendar($oCalDAVCalendar)
	{
		if (!($oCalDAVCalendar instanceof \Sabre\CalDAV\Calendar))
		{
			return false;
		}
		$aProps = $oCalDAVCalendar->getProperties(array(
			'id',
			'uri',
			'principaluri',
			'{DAV:}displayname',
			'{'.\Sabre\CalDAV\Plugin::NS_CALENDARSERVER.'}getctag',
			'{'.\Sabre\CalDAV\Plugin::NS_CALDAV.'}calendar-description',
			'{http://apple.com/ns/ical/}calendar-color',
			'{http://apple.com/ns/ical/}calendar-order',
			'{http://sabredav.org/ns}read-only',
			'{http://sabredav.org/ns}owner-principal',
			'{http://calendarserver.org/ns/}summary'			
		));

		$oCalendar = new \CCalendar($aProps['uri']);
		$oCalendar->IntId = $aProps['id'];

		if ($oCalDAVCalendar instanceof \Sabre\CalDAV\SharedCalendar)
		{
			$oCalendar->Shared = true;
			if (isset($aProps['{http://sabredav.org/ns}read-only']))
			{
				$oCalendar->Access = $aProps['{http://sabredav.org/ns}read-only'] ? ECalendarPermission::Read : ECalendarPermission::Write;
			}
			if (isset($aProps['{http://calendarserver.org/ns/}summary']))
			{
				$oCalendar->Description = $aProps['{http://calendarserver.org/ns/}summary'];
			}
		}
		else 
		{
			if (isset($aProps['{'.\Sabre\CalDAV\Plugin::NS_CALDAV.'}calendar-description']))
			{
				$oCalendar->Description = $aProps['{'.\Sabre\CalDAV\Plugin::NS_CALDAV.'}calendar-description'];
			}
		}

		if (isset($aProps['{DAV:}displayname']))
		{
			$oCalendar->DisplayName = $aProps['{DAV:}displayname'];
		}
		if (isset($aProps['{'.\Sabre\CalDAV\Plugin::NS_CALENDARSERVER.'}getctag']))
		{
			$oCalendar->CTag = $aProps['{'.\Sabre\CalDAV\Plugin::NS_CALENDARSERVER.'}getctag'];
		}
		if (isset($aProps['{http://apple.com/ns/ical/}calendar-color']))
		{
			$oCalendar->Color = $aProps['{http://apple.com/ns/ical/}calendar-color'];
		}
		if (isset($aProps['{http://apple.com/ns/ical/}calendar-order']))
		{
			$oCalendar->Order = $aProps['{http://apple.com/ns/ical/}calendar-order'];
		}
		if (isset($aProps['{http://sabredav.org/ns}owner-principal']))
		{
			$oCalendar->Principals = array($aProps['{http://sabredav.org/ns}owner-principal']);
		}
		else
		{
			$oCalendar->Principals = array($aProps['principaluri']);
		}

		$sPrincipal = $oCalendar->GetMainPrincipalUrl();
		$sEmail = basename(urldecode($sPrincipal));

		$oCalendar->Owner = (!empty($sEmail)) ? $sEmail : $this->Account->Email;
		$oCalendar->Url = '/calendars/'.$this->Account->Email.'/'.$aProps['uri'];
		$oCalendar->RealUrl = 'calendars/'.$oCalendar->Owner.'/'.$aProps['uri'];

		$aTenantPrincipal = $this->GetPrincipalInfo($this->GetTenantUser($this->Account));			
		if($aTenantPrincipal && $aTenantPrincipal['uri'] === $aProps['principaluri'])
		{
			$oCalendar->SharedToAll = true;
		}
		
		return $oCalendar;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
     * @return \CCalendar | bool
	 */
	public function GetCalendar($oAccount, $sCalendarId)
	{
		$this->Init($oAccount);

		$oCalDAVCalendar = null;
		$oCalendar = false;
		if (count($this->CalendarsCache) > 0 && isset($this->CalendarsCache[$this->Account->Email][$sCalendarId]))
		{
			$oCalendar = $this->CalendarsCache[$this->Account->Email][$sCalendarId];
		}
		else
		{
			$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);
			if ($oCalDAVCalendar)
			{
				$oCalendar = $this->ParseCalendar($oCalDAVCalendar);
			}
		}
		return $oCalendar;
	}

	/**
     * @return string
	 */
	public function GetPublicUser()
	{
		return \afterlogic\DAV\Backends::Principal()->getPrincipalByEmail(\afterlogic\DAV\Constants::DAV_PUBLIC_PRINCIPAL);
	}

	/**
     * @return \CAccount
	 */
	public function GetPublicAccount()
	{
		$oAccount = new CAccount(new CDomain());
		$oAccount->Email = $this->GetPublicUser();
		return $oAccount;
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function GetTenantUser($oAccount)
	{
		if (!isset($this->TenantUser))
		{
			$sPrincipal = 'default_' . \afterlogic\DAV\Constants::DAV_TENANT_PRINCIPAL;
			if ($oAccount->IdTenant > 0)
			{
				$oApiTenantsMan = CApi::Manager('tenants');
				$oTenant = $oApiTenantsMan ? $oApiTenantsMan->GetTenantById($oAccount->IdTenant) : null;
				if ($oTenant)
				{
					$sPrincipal = $oTenant->Login . '_' . \afterlogic\DAV\Constants::DAV_TENANT_PRINCIPAL;
				}
			}

			$this->TenantUser = \afterlogic\DAV\Backends::Principal()->getPrincipalByEmail($sPrincipal);
		}
		return $this->TenantUser;
	}
	
	/**
	 * @param CAccount $oAccount
     * @return string
	 */
	public function GetTenantAccount($oAccount)
	{
		$oTenantAccount = new CAccount(new CDomain());
		$oTenantAccount->Email = $this->GetTenantUser($oAccount);
		$oTenantAccount->FriendlyName = \CApi::ClientI18N('CONTACTS/SHARED_TO_ALL', $oAccount);
		return $oTenantAccount;
	}	

	/*
	 * @param string $sCalendarId
     * @return string
	 */
	public function GetPublicCalendarHash($sCalendarId)
	{
		return $sCalendarId;
	}

	/**
	 * @param CAccount $oAccount
     * @return array
	 */
	public function GetCalendars($oAccount)
	{
		$this->Init($oAccount);

		$aCalendars = array();
		if (count($this->CalendarsCache) > 0 && isset($this->CalendarsCache[$this->Account->Email]))
		{
			$aCalendars = $this->CalendarsCache[$this->Account->Email];
		}
		else
		{
			$oUserCalendars = new \afterlogic\DAV\CalDAV\UserCalendars($this->GetBackend(), $this->Principal);

			foreach ($oUserCalendars->getChildren() as $oCalDAVCalendar)
			{
				$oCalendar = $this->ParseCalendar($oCalDAVCalendar);
				if ($oCalendar)
				{
					$aCalendars[$oCalendar->Id] = $oCalendar;
				}
			}

			$this->CalendarsCache[$this->Account->Email] = $aCalendars;
		}
 		return $aCalendars;
	}
	
	/**
	 * @param CAccount $oAccount
     * @return array
	 */
	public function GetCalendarNames($oAccount)
	{
		$aCalendarNames = array();
		$aCalendars = $this->GetCalendars($oAccount);
		if (is_array($aCalendars))
		{
			/* @var $oCalendar \CCalendar */
			foreach ($aCalendars as $oCalendar)
			{
				if ($oCalendar instanceof \CCalendar)
				{
					$aCalendarNames[$oCalendar->Id] = $oCalendar->DisplayName;
				}
			}
		}
		return $aCalendarNames;
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
		$this->Init($oAccount);

		$oUserCalendars = new \afterlogic\DAV\CalDAV\UserCalendars($this->GetBackend(), $this->Principal);

		$sSystemName = \Sabre\DAV\UUIDUtil::getUUID();
		$oUserCalendars->createExtendedCollection($sSystemName, 
				array(
					'{DAV:}collection',
					'{urn:ietf:params:xml:ns:caldav}calendar'
				), 
				array(
					'{DAV:}displayname' => $sName,
					'{'.\Sabre\CalDAV\Plugin::NS_CALENDARSERVER.'}getctag' => 1,
					'{'.\Sabre\CalDAV\Plugin::NS_CALDAV.'}calendar-description' => $sDescription,
					'{http://apple.com/ns/ical/}calendar-color' => $sColor,
					'{http://apple.com/ns/ical/}calendar-order' => $iOrder
				)
		);
		return $sSystemName;
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
		$this->Init($oAccount);
		
		$bOnlyColor = ($sName === null && $sDescription === null && $iOrder === null);

		$oUserCalendars = new \afterlogic\DAV\CalDAV\UserCalendars($this->GetBackend(), $this->Principal);
		if ($oUserCalendars->childExists($sCalendarId))
		{
			$oCalDAVCalendar = $oUserCalendars->getChild($sCalendarId);
			if ($oCalDAVCalendar)
			{
				$aCalendarProperties = $oCalDAVCalendar->getProperties(array(
						'principaluri',
						'{http://sabredav.org/ns}owner-principal'
					)
				);
				$sPrincipal = isset($aCalendarProperties['principaluri']) ? $aCalendarProperties['principaluri'] : null; 

				$sOwnerPrincipal = isset($aCalendarProperties['{http://sabredav.org/ns}owner-principal']) ? 
						$aCalendarProperties['{http://sabredav.org/ns}owner-principal'] : $sPrincipal; 
				$bIsOwner = (isset($sOwnerPrincipal) && basename($sOwnerPrincipal) === $oAccount->Email);

				$bShared = ($oCalDAVCalendar instanceof \Sabre\CalDAV\SharedCalendar);
				$bSharedToAll = (isset($sPrincipal) && basename($sPrincipal) === $this->GetTenantUser($oAccount));
				$bSharedToMe = ($bShared && !$bSharedToAll && !$bIsOwner);
				
				$aUpdateProperties = array();
				if ($bSharedToMe)
				{
					$aUpdateProperties = array(
						'href' => $oAccount->Email,
						'color' => $sColor,
					);
					if (!$bOnlyColor)
					{
						$aUpdateProperties['displayname'] = $sName;
						$aUpdateProperties['summary'] = $sDescription;
						$aUpdateProperties['color'] = $sColor;
					}
				}
				else 
				{
					$aUpdateProperties = array(
						'{http://apple.com/ns/ical/}calendar-color' => $sColor
					);
					if (!$bOnlyColor)
					{
						$aUpdateProperties['{DAV:}displayname'] = $sName;
						$aUpdateProperties['{'.\Sabre\CalDAV\Plugin::NS_CALDAV.'}calendar-description'] = $sDescription;
						$aUpdateProperties['{http://apple.com/ns/ical/}calendar-color'] = $sColor;
						$aUpdateProperties['{http://apple.com/ns/ical/}calendar-order'] = $iOrder;
					}
				}
				unset($this->CalDAVCalendarsCache[$sCalendarId]);
				unset($this->CalDAVCalendarObjectsCache[$sCalendarId]);
				return $oCalDAVCalendar->updateProperties($aUpdateProperties);
				
			}
		}
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sColor
	 */
	public function UpdateCalendarColor($oAccount, $sCalendarId, $sColor)
	{
		return $this->UpdateCalendar($oAccount, $sCalendarId, null, null, null, $sColor);
	}

	/**
	 * @param string $sCalendarId
	 * @param int $iVisible
	 */
	public function UpdateCalendarVisible($sCalendarId, $iVisible)
	{
		@setcookie($sCalendarId, $iVisible, time() + 86400);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 */
	public function DeleteCalendar($oAccount, $sCalendarId)
	{
		$this->Init($oAccount);

		$oUserCalendars = new \afterlogic\DAV\CalDAV\UserCalendars($this->GetBackend(), $this->Principal);
		if ($oUserCalendars->childExists($sCalendarId))
		{
			$oCalDAVCalendar = $oUserCalendars->getChild($sCalendarId);
			if ($oCalDAVCalendar)
			{
				if ($oCalDAVCalendar instanceof \Sabre\CalDAV\SharedCalendar)
				{
					$this->UnsubscribeCalendar($oAccount, $sCalendarId);
				}
				else
				{
					$oCalDAVCalendar->delete();
				}

				$this->DeleteReminderByCalendar($sCalendarId);
				unset($this->CalDAVCalendarsCache[$sCalendarId]);
				unset($this->CalDAVCalendarObjectsCache[$sCalendarId]);

				return true;
			}
		}
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function ClearAllCalendars($oAccount)
	{
		$this->Init($oAccount);

		$oUserCalendars = new \afterlogic\DAV\CalDAV\UserCalendars($this->GetBackend(), $this->Principal);
		foreach ($oUserCalendars->getChildren() as $oCalDAVCalendar)
		{
			if ($oCalDAVCalendar instanceof \Sabre\CalDAV\Calendar)
			{
				if ($oCalDAVCalendar instanceof \Sabre\CalDAV\SharedCalendar)
				{
//					$this->UnsubscribeCalendar($oAccount, $sCalendarId);
				}
				else
				{
					$oCalDAVCalendar->delete();
				}
			}
		}
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 *
	 * @return bool
	 */
	public function UnsubscribeCalendar($oAccount, $sCalendarId)
	{
		$this->Init($oAccount);

		$oCalendar = $this->GetCalendar($oAccount, $sCalendarId);
		if ($oCalendar)
		{
			$this->GetBackend()->updateShares($oCalendar->IntId, array(), array($oAccount->Email));
		}

		return true;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sUserId
	 * @param int $iPerm
	 *
	 * @return bool
	 */
	public function UpdateCalendarShares($oAccount, $sCalendarId, $aShares)
	{
		$this->Init($oAccount);

		$oCalendar = $this->GetCalendar($oAccount, $sCalendarId);

		if ($oCalendar)
		{
			$aCalendarUsers = $this->GetCalendarUsers($oAccount, $oCalendar);
			$aSharesEmails = array_map(function ($aItem) {
				return $aItem['email'];
			}, $aShares);
			
			$add = array();
			$remove = array();
			
			// add to delete list
			foreach($aCalendarUsers as $aCalendarUser)
			{
				if (!in_array($aCalendarUser['email'], $aSharesEmails))
				{
					$remove[] = $aCalendarUser['email'];
				}
			}
			
			if (count($oCalendar->Principals) > 0)
			{
				foreach ($aShares as $aShare)
				{
					if ($aShare['access'] === \ECalendarPermission::RemovePermission)
					{
						$remove[] = $aShare['email'];
					}
					else
					{
						$add[] = array(
							'href' => $aShare['email'],
							'readonly' => ($aShare['access'] === \ECalendarPermission::Read) ? 1 : 0,
						);
					}
				}
				
				$this->GetBackend()->updateShares($oCalendar->IntId, $add, $remove);
			}
		}

		return true;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sUserId
	 * @param int $iPerms
	 *
	 * @return bool
	 */
	public function UpdateCalendarShare($oAccount, $sCalendarId, $sUserId, $iPerms = ECalendarPermission::RemovePermission)
	{
		$this->Init($oAccount);

		$oCalendar = $this->GetCalendar($oAccount, $sCalendarId);

		if ($oCalendar)
		{
			if (count($oCalendar->Principals) > 0)
			{
				$add = array();
				$remove = array();
				if ($iPerms === ECalendarPermission::RemovePermission) 
				{
					$remove[] = $sUserId;
				}
				else
				{
					$aItem['href'] = $sUserId;
					if ($iPerms === \ECalendarPermission::Read)
					{
						$aItem['readonly'] = true;
					}
					elseif ($iPerms === \ECalendarPermission::Write) 
					{
						$aItem['readonly'] = false;
					}
					$add[] = $aItem;
				}
				
				$this->GetBackend()->updateShares($oCalendar->IntId, $add, $remove);
			}
		}

		return true;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sUserId
	 * @param int $iPerm
	 *
	 * @return bool
	 */
	public function DeleteCalendarShares($oAccount, $sCalendarId)
	{
		$this->Init($oAccount);

		$oCalendar = $this->GetCalendar($oAccount, $sCalendarId);

		if ($oCalendar)
		{
			if (count($oCalendar->Principals) > 0)
			{
				$this->UpdateCalendarShares($oAccount, $sCalendarId, array());
			}
		}

		return true;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param bool $bIsPublic
	 */
	public function PublicCalendar($oAccount, $sCalendarId, $bIsPublic = false)
	{
		$iPermission = $bIsPublic ? \ECalendarPermission::Read : \ECalendarPermission::RemovePermission;
		return $this->UpdateCalendarShare($oAccount, $sCalendarId, $this->GetPublicUser(), $iPermission);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $oCalendar
	 * @return array
	 */
	public function GetCalendarUsers($oAccount, $oCalendar)
	{
		$aResult = array();
		$this->Init($oAccount);

		if ($oCalendar != null)
		{
			$aShares = $this->GetBackend()->getShares($oCalendar->IntId);

			foreach($aShares as $aShare)
			{
				$aResult[] = array(
					'name' => basename($aShare['href']),
					'email' => basename($aShare['href']),
					'access' => $aShare['readOnly'] ? ECalendarPermission::Read : ECalendarPermission::Write
				);
			}
		}
		return $aResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @return string | bool
	 */
	public function ExportCalendarToIcs($oAccount, $sCalendarId)
	{
		$this->Init($oAccount);

		$mResult = false;
		$oCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalendar)
		{
			$aCollectedTimezones = array();

			$aTimezones = array();
			$aObjects = array();

			foreach ($oCalendar->getChildren() as $oChild)
			{
				$oNodeComp = \Sabre\VObject\Reader::read($oChild->get());
				foreach($oNodeComp->children() as $oNodeChild)
				{
					switch($oNodeChild->name)
					{
						case 'VEVENT' :
						case 'VTODO' :
						case 'VJOURNAL' :
							$aObjects[] = $oNodeChild;
							break;

						case 'VTIMEZONE' :
							if (in_array((string)$oNodeChild->TZID, $aCollectedTimezones))
							{
								continue;
							}

							$aTimezones[] = $oNodeChild;
							$aCollectedTimezones[] = (string)$oNodeChild->TZID;
							break;

					}
				}
			}

			$oVCal = new \Sabre\VObject\Component\VCalendar();
			foreach($aTimezones as $oTimezone)
			{
				$oVCal->add($oTimezone);
			}
			foreach($aObjects as $oObject)
			{
				$oVCal->add($oObject);
			}

			$mResult = $oVCal->serialize();
		}

		return $mResult;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sTempFileName
	 * @return mixed
	 */
	public function ImportToCalendarFromIcs($oAccount, $sCalendarId, $sTempFileName)
	{
		$this->Init($oAccount);

		$mResult = false;
		$oCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalendar)
		{
			// You can either pass a readable stream, or a string.
			$h = fopen($sTempFileName, 'r');
			$splitter = new \Sabre\VObject\Splitter\ICalendar($h);

			$iCount = 0;
			while($oVCalendar = $splitter->getNext()) 
			{
				$oVEvents = $oVCalendar->getBaseComponents('VEVENT');
				if (isset($oVEvents) && 0 < count($oVEvents))
				{
					if (!$oCalendar->childExists($oVEvents[0]->UID . '.ics'))
					{
						$oCalendar->createFile($oVEvents[0]->UID . '.ics', $oVCalendar->serialize());
						$iCount++;
					}
				}
			}
			$mResult = $iCount;
		}
		return $mResult;
	}
	

	/**
	 * @param \Sabre\CalDAV\Calendar $oCalDAVCalendar
	 * @param string $sEventId
	 * @return \Sabre\CalDAV\CalendarObject
	 */
	public function GetCalDAVCalendarObject($oCalDAVCalendar, $sEventId)
	{
		if ($oCalDAVCalendar)
		{
			$sEventFileName = $sEventId . '.ics';
			if (count($this->CalDAVCalendarObjectsCache) > 0 && isset($this->CalDAVCalendarObjectsCache[$oCalDAVCalendar->getName()][$sEventFileName][$this->Account->Email]))
			{
				return $this->CalDAVCalendarObjectsCache[$oCalDAVCalendar->getName()][$sEventFileName][$this->Account->Email];
			}
			else
			{
				if ($oCalDAVCalendar->childExists($sEventFileName))
				{
					$oChild = $oCalDAVCalendar->getChild($sEventFileName);
					if ($oChild instanceof \Sabre\CalDAV\CalendarObject)
					{
						$this->CalDAVCalendarObjectsCache[$oCalDAVCalendar->getName()][$sEventFileName][$this->Account->Email] = $oChild;
						return $oChild;
					}
				}
				else
				{
					foreach ($oCalDAVCalendar->getChildren() as $oChild)
					{
						if ($oChild instanceof \Sabre\CalDAV\CalendarObject)
						{
							$oVCal = \Sabre\VObject\Reader::read($oChild->get());
							if ($oVCal && $oVCal->VEVENT)
							{
								foreach ($oVCal->VEVENT as $oVEvent)
								{
									foreach($oVEvent->select('UID') as $oUid)
									{
										if ((string)$oUid === $sEventId)
										{
											$this->CalDAVCalendarObjectsCache[$oCalDAVCalendar->getName()][$sEventFileName][$this->Account->Email] = $oChild;
											return $oChild;
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return false;
	}
	

	/**
	 * @param CAccount $oAccount
	 * @param object $oCalendar
	 * @param string $dStart
	 * @param string $dEnd
	 */
	public function GetEventsFromVCalendar($oAccount, $oCalendar, $oVCal, $dStart, $dEnd)
	{
		$oVCalOriginal = clone $oVCal;

		$oVCal->expand(
			\Sabre\VObject\DateTimeParser::parse($dStart), 
			\Sabre\VObject\DateTimeParser::parse($dEnd)
		);
		
		$aEvents = CalendarParser::ParseEvent($oAccount, $oCalendar, $oVCal, $oVCalOriginal);
		
		return $aEvents;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @param string $dStart
	 * @param string $dEnd
	 */
	public function GetExpandedEvent($oAccount, $sCalendarId, $sEventId, $dStart, $dEnd)
	{
		$this->Init($oAccount);

		$mResult = array(
			'Events' => array(),
			'CTag' => 1
		);
		$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalDAVCalendar)
		{
			$oCalDAVCalendarObject = $this->GetCalDAVCalendarObject($oCalDAVCalendar, $sEventId);
			if ($oCalDAVCalendarObject)
			{
				$oVCal = \Sabre\VObject\Reader::read($oCalDAVCalendarObject->get());

				$oCalendar = $this->ParseCalendar($oCalDAVCalendar);
				$mResult['Events'] = $this->GetEventsFromVCalendar($oAccount, $oCalendar, $oVCal, $dStart, $dEnd);
				$mResult['CTag'] = $oCalendar->CTag;
			}
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sEventId
	 * @return array
	 */
	public function FindEventInCalendars($oAccount,  $sEventId, $aCalendars)
	{
		$aEventCalendarIds = array();
		foreach (array_keys($aCalendars) as $sKey)
		{
			if ($this->EventExists($oAccount, $sKey, $sEventId))
			{
				$aEventCalendarIds[] = $sKey;
			}
		}
		
		return $aEventCalendarIds;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 */
	public function EventExists($oAccount, $sCalendarId, $sEventId)
	{
		$mResult = false;
		$this->Init($oAccount);

		$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalDAVCalendar && $this->GetCalDAVCalendarObject($oCalDAVCalendar, $sEventId) !== false)
		{		
			$mResult = true;
		}
		return $mResult;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 */
	public function GetEvent($oAccount, $sCalendarId, $sEventId)
	{
		$mResult = false;
		$this->Init($oAccount);

		$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalDAVCalendar)
		{		
			$oCalendarObject = $this->GetCalDAVCalendarObject($oCalDAVCalendar, $sEventId);
			if ($oCalendarObject)
			{
				$mResult = array(
					'url'  => $oCalendarObject->getName(),
					'vcal' => \Sabre\VObject\Reader::read($oCalendarObject->get())
				);
			}
		}
		return $mResult;
	}	
	
	public function GetEventUrls($oCalendar, $dStart, $dEnd)
	{
		return $oCalendar->calendarQuery(array(
			'name' => 'VCALENDAR',
			'comp-filters' => array(
				array(
					'name' => 'VEVENT',
					'comp-filters' => array(),
					'prop-filters' => array(),
					'is-not-defined' => false,
					'time-range' => array(
						'start' => \Sabre\VObject\DateTimeParser::parse($dStart),
						'end' => \Sabre\VObject\DateTimeParser::parse($dEnd),
					),
				),
			),
			'prop-filters' => array(),
			'is-not-defined' => false,
			'time-range' => null,
		));
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $dStart
	 * @param string $dEnd
	 */
	public function GetEvents($oAccount, $sCalendarId, $dStart, $dEnd)
	{
		$this->Init($oAccount);

		$mResult = false;
		$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);

		if ($oCalDAVCalendar)
		{
			$aUrls = $this->GetEventUrls($oCalDAVCalendar, $dStart, $dEnd);
			
 			$oCalendar = $this->ParseCalendar($oCalDAVCalendar);
			$mResult = array();
			foreach ($aUrls as $sUrl)
			{
				if (isset($this->CalDAVCalendarObjectsCache[$oCalDAVCalendar->getName()][$sUrl][$this->Account->Email]))
				{
					$oCalDAVCalendarObject = $this->CalDAVCalendarObjectsCache[$oCalDAVCalendar->getName()][$sUrl][$this->Account->Email];
				}
				else
				{
					$oCalDAVCalendarObject = $oCalDAVCalendar->getChild($sUrl);
					$this->CalDAVCalendarObjectsCache[$oCalDAVCalendar->getName()][$sUrl][$this->Account->Email] = $oCalDAVCalendarObject;		
				}
				$oVCal = \Sabre\VObject\Reader::read($oCalDAVCalendarObject->get());
				$aEvents = $this->GetEventsFromVCalendar($oAccount, $oCalendar, $oVCal, $dStart, $dEnd);
				foreach (array_keys($aEvents) as $key) 
				{
					$aEvents[$key]['lastModified'] = $oCalDAVCalendarObject->getLastModified();
				}
				$mResult = array_merge($mResult, $aEvents);
			}
		}

		return $mResult;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @param \Sabre\VObject\Component\VCalendar $oVCal
	 */
	public function CreateEvent($oAccount, $sCalendarId, $sEventId, $oVCal)
	{
		$this->Init($oAccount);

		$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalDAVCalendar)
		{
			$oCalendar = $this->ParseCalendar($oCalDAVCalendar);
			if ($oCalendar->Access !== \ECalendarPermission::Read)
			{
				$sData = $oVCal->serialize();
				$oCalDAVCalendar->createFile($sEventId.'.ics', $sData);

				$this->UpdateReminder($oCalendar->Owner, $oCalendar->RealUrl, $sEventId, $sData);

				return $sEventId;
			}
		}

		return null;
	}


	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @param string $sData
	 */
	public function UpdateEventRaw($oAccount, $sCalendarId, $sEventId, $sData)
	{
		$this->Init($oAccount);

		$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalDAVCalendar)
		{
			$oCalendar = $this->ParseCalendar($oCalDAVCalendar);
			if ($oCalendar->Access !== \ECalendarPermission::Read)
			{
				$oCalDAVCalendarObject = $this->GetCalDAVCalendarObject($oCalDAVCalendar, $sEventId);
				if ($oCalDAVCalendarObject)
				{
					$oChild = $oCalDAVCalendar->getChild($oCalDAVCalendarObject->getName());
					if ($oChild)
					{
						$oChild->put($sData);
						$this->UpdateReminder($oCalendar->Owner, $oCalendar->RealUrl, $sEventId, $sData);
						unset($this->CalDAVCalendarObjectsCache[$sCalendarId][$sEventId.'.ics']);
						return true;
					}
				}
				else
				{
					$oCalDAVCalendar->createFile($sEventId.'.ics', $sData);
					$this->UpdateReminder($oCalendar->Owner, $oCalendar->RealUrl, $sEventId, $sData);
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @param array $oVCal
	 */
	public function UpdateEvent($oAccount, $sCalendarId, $sEventId, $oVCal)
	{
 		$this->Init($oAccount);

		$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalDAVCalendar)
		{
			$oCalendar = $this->ParseCalendar($oCalDAVCalendar);
			if ($oCalendar->Access !== \ECalendarPermission::Read)
			{
				$oChild = $oCalDAVCalendar->getChild($sEventId . '.ics');
				$sData = $oVCal->serialize();
				$oChild->put($sData);
				
				$this->UpdateReminder($oCalendar->Owner, $oCalendar->RealUrl, $sEventId, $sData);
				unset($this->CalDAVCalendarObjectsCache[$sCalendarId][$sEventId.'.ics']);
				return true;
			}
		}
		return false;
	}

	public function MoveEvent($oAccount, $sCalendarId, $sNewCalendarId, $sEventId, $sData)
	{
		$this->Init($oAccount);

		$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalDAVCalendar)
		{
			$oCalDAVCalendarNew = $this->GetCalDAVCalendar($sNewCalendarId);
			if ($oCalDAVCalendarNew)
			{
				$oCalendar = $this->ParseCalendar($oCalDAVCalendarNew);
				if ($oCalendar->Access !== \ECalendarPermission::Read)
				{
					$oCalDAVCalendarNew->createFile($sEventId . '.ics', $sData);
	
					$oChild = $oCalDAVCalendar->getChild($sEventId . '.ics');
					$oChild->delete();

					$this->DeleteReminder($sEventId);
					$this->UpdateReminder($oCalendar->Owner, $oCalendar->RealUrl, $sEventId, $sData);
					unset($this->CalDAVCalendarObjectsCache[$sCalendarId][$sEventId.'.ics']);
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 */
	public function DeleteEvent($oAccount, $sCalendarId, $sEventId)
	{
		$this->Init($oAccount);

		$oCalDAVCalendar = $this->GetCalDAVCalendar($sCalendarId);
		if ($oCalDAVCalendar)
		{
			$oCalendar = $this->ParseCalendar($oCalDAVCalendar);
			if ($oCalendar->Access !== \ECalendarPermission::Read)
			{
				$oChild = $oCalDAVCalendar->getChild($sEventId.'.ics');
				$oChild->delete();

				$this->DeleteReminder($sEventId);
				unset($this->CalDAVCalendarObjectsCache[$sCalendarId][$sEventId.'.ics']);

				return (string) ($oCalendar->CTag + 1);
			}
		}
		return false;
	}

	public function GetReminders($start, $end)
	{
		return \afterlogic\DAV\Backends::Reminders()->getReminders($start, $end);
	}

	public function AddReminder($sEmail, $sCalendarUri, $sEventId, $time = null, $starttime = null)
	{
		return \afterlogic\DAV\Backends::Reminders()->addReminders($sEmail, $sCalendarUri, $sEventId, $time, $starttime);
	}
	
	public function UpdateReminder($sEmail, $sCalendarUri, $sEventId, $sData)
	{
		\afterlogic\DAV\Backends::Reminders()->updateReminder(trim($sCalendarUri, '/') . '/' . $sEventId . '.ics', $sData, $sEmail);
	}

	public function DeleteReminder($sEventId)
	{
		return \afterlogic\DAV\Backends::Reminders()->deleteReminder($sEventId);
	}

	public function DeleteReminderByCalendar($sCalendarUri)
	{
		return \afterlogic\DAV\Backends::Reminders()->deleteReminderByCalendar($sCalendarUri);
	}
	
}
