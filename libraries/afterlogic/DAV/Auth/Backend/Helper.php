<?php

namespace afterlogic\DAV\Auth\Backend;

use afterlogic\DAV\Constants;

class Helper
{

	public static function ValidateClient($sClient)
	{
		$bIsSync = false;
		if (isset($GLOBALS['server']) && $GLOBALS['server'] instanceof \Sabre\DAV\Server)
		{
			$aHeaders = $GLOBALS['server']->httpRequest->getHeaders();
			if (isset($aHeaders['user-agent']))
			{
				$sUserAgent = $aHeaders['user-agent'];
				if (strpos(strtolower($sUserAgent), 'afterlogic ' . strtolower($sClient)) !== false)
				{
					$bIsSync = true;
				}
			}
		}
		return $bIsSync;
	}

	public static function CheckPrincipals($sUserName)
	{
		$oPdo = \CApi::GetPDO();
		$Settings = \CApi::GetSettings();
		$dbPrefix = $Settings->GetConf('Common/DBPrefix');
		
		$sPrincipal = 'principals/' . $sUserName;

		$stmt = $oPdo->prepare(
			'SELECT id FROM '.$dbPrefix.Constants::T_PRINCIPALS.' WHERE uri = ? LIMIT 1'
		);
		$stmt->execute(array($sPrincipal));
		if(count($stmt->fetchAll()) === 0)
		{
			$stmt = $oPdo->prepare(
				'INSERT INTO '.$dbPrefix.Constants::T_PRINCIPALS.'
					(uri,email,displayname) VALUES (?, ?, ?)'
			);
			$stmt->execute(array($sPrincipal, $sUserName, ''));
		}

		$stmt = $oPdo->prepare(
			'SELECT principaluri FROM '.$dbPrefix.Constants::T_CALENDARS.'
				WHERE principaluri = ?'
		);
		$stmt->execute(array($sPrincipal));
		if (count($stmt->fetchAll()) === 0)
		{
			$stmt = $oPdo->prepare(
				'INSERT INTO '.$dbPrefix.Constants::T_CALENDARS.'
					(principaluri, displayname, uri, description, components, ctag, calendarcolor)
					VALUES (?, ?, ?, ?, ?, 1, ?)'
			);
			$stmt->execute(array(
					$sPrincipal,
					Constants::CalendarDefaultName,
					\Sabre\DAV\UUIDUtil::getUUID(),
					'',
					'VEVENT,VTODO',
					Constants::CALENDAR_DEFAULT_COLOR
				)
			);
		}		
		
		$stmt = $oPdo->prepare(
			'SELECT principaluri FROM '.$dbPrefix.Constants::T_CALENDARS.'
				WHERE principaluri = ? and uri = ? LIMIT 1'
		);
		$stmt->execute(array($sPrincipal, Constants::CALENDAR_DEFAULT_NAME));
		if (count($stmt->fetchAll()) !== 0)
		{
			$stmt = $oPdo->prepare(
				'UPDATE '.$dbPrefix.Constants::T_CALENDARS.'
					SET uri = ? WHERE principaluri = ? and uri = ?'
			);
			$stmt->execute(array(
					\Sabre\DAV\UUIDUtil::getUUID(),
					$sPrincipal,
					Constants::CALENDAR_DEFAULT_NAME
				)
			);
		}

		$stmt = $oPdo->prepare(
			'SELECT principaluri FROM '.$dbPrefix.Constants::T_ADDRESSBOOKS.'
				WHERE principaluri = ? and uri = ? LIMIT 1'
		);

		$stmt->execute(array($sPrincipal, Constants::ADDRESSBOOK_DEFAULT_NAME));
		$bHasDefaultAddressbooks = (count($stmt->fetchAll()) != 0);

		$stmt->execute(array($sPrincipal, Constants::ADDRESSBOOK_COLLECTED_NAME));
		$bHasCollectedAddressbooks = (count($stmt->fetchAll()) != 0);

		$stmt = $oPdo->prepare(
			'INSERT INTO '.$dbPrefix.Constants::T_ADDRESSBOOKS.'
				(principaluri, displayname, uri, description, ctag)
				VALUES (?, ?, ?, ?, 1)'
		);
		if (!$bHasDefaultAddressbooks)
		{
			$stmt->execute(array(
					$sPrincipal,
					Constants::ADDRESSBOOK_DEFAULT_DISPLAY_NAME,
					Constants::ADDRESSBOOK_DEFAULT_NAME,
					Constants::ADDRESSBOOK_DEFAULT_DISPLAY_NAME
				)
			);
		}
		if (!$bHasCollectedAddressbooks)
		{
			$stmt->execute(array(
					$sPrincipal,
					Constants::ADDRESSBOOK_COLLECTED_DISPLAY_NAME,
					Constants::ADDRESSBOOK_COLLECTED_NAME,
					Constants::ADDRESSBOOK_COLLECTED_DISPLAY_NAME
				)
			);
		}
	}

}