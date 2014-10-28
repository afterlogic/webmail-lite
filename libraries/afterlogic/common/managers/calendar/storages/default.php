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
class CApiCalendarStorage extends AApiManagerStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct($sStorageName, CApiGlobalManager &$oManager)
	{
		parent::__construct('calendar', $sStorageName, $oManager);
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function Init($oAccount)
	{
	}

	/**
	 * @param CalendarInfo  $oCalendar
	 */
	public function InitCalendar(&$oCalendar)
	{
	}

	public function GetCalendarAccess($oAccount, $sCalendarId)
	{
		return ECalendarPermission::Write;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 */
	public function GetCalendar($oAccount, $sCalendarId)
	{
		return null;
	}

	/*
	 * @param string $sCalendar
	 */
	public function GetPublicCalendar($sCalendar)
	{

		return false;
	}

	/*
	 * @param string $sHash
	 */
	public function GetPublicCalendarByHash($sHash)
	{
		return false;
	}

	/*
	 * @param string $sCalendarId
	 */
	public function GetPublicCalendarHash($sCalendarId)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function GetCalendarsSharedToAll($oAccount)
	{
		return array();
	}

	/**
	}
	 * @param CAccount $oAccount
	 */
	public function GetCalendars($oAccount)
	{
		return array();
	}

	/**
	 * @param CAccount $oAccount
     * @return array
	 */
	public function GetCalendarNames($oAccount)
	{
		return array();
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
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sName
	 * @param string $sDescription
	 * @param int $iOrder
	 * @param string $sColor
	 */
	public function UpdateCalendar($oAccount, $sCalendarId, $sName, $sDescription, $iOrder,
			$sColor)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sColor
	 */
	public function UpdateCalendarColor($oAccount, $sCalendarId, $sColor)
	{
		return false;
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
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sUserId
	 * @param int $iPerms
	 */
	public function UpdateCalendarShare($oAccount, $sCalendarId, $sUserId, $iPerms = ECalendarPermission::RemovePermission)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param bool $bIsPublic
	 */
	public function PublicCalendar($oAccount, $sCalendarId, $bIsPublic)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $oCalendar
	 */
	public function GetCalendarUsers($oAccount, $oCalendar)
	{
		return array();
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $dStart
	 * @param string $dFinish
	 */
	public function GetEvents($oAccount, $sCalendarId, $dStart, $dFinish)
	{
		return array();
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 */
	public function GetEvent($oAccount, $sCalendarId, $sEventId)
	{
		return array();
	}

	/**
	}
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param \Sabre\VObject\Component\VCalendar $vCal
	 */
	public function CreateEvent($oAccount, $sCalendarId, $sEventId, $vCal)
	{
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
		return true;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @param array $aArgs
	 */
	public function UpdateEvent($oAccount, $sCalendarId, $sEventId, $aArgs)
	{
		return false;
	}

	public function MoveEvent($oAccount, $sCalendarId, $sNewCalendarId, $sEventId, $sData)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sCalendarId
	 * @param string $sEventId
	 */
	public function DeleteEvent($oAccount, $sCalendarId, $sEventId)
	{
		return false;
	}

	public function GetReminders($start, $end)
	{
		return false;
	}

	public function AddReminder($sEmail, $calendarUri, $eventid, $time = null)
	{
		return false;
	}

	public function UpdateReminder($sEmail, $calendarUri, $eventId, $sData)
	{
		return false;
	}

	public function DeleteReminder($eventId)
	{
		return false;
	}

	public function DeleteReminderByCalendar($calendarUri)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function ClearAllCalendars($oAccount)
	{
		return true;
	}
}

