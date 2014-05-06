<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 * 
 */

namespace afterlogic\DAV;

class Utils
{
	public static function getTenantUser($oAccount)
	{
		$sEmail = 'default_' . Constants::DAV_TENANT_PRINCIPAL;
		if ($oAccount->IdTenant > 0)
		{
			$oApiTenantsMan = \CApi::Manager('tenants');
			$oTenant = $oApiTenantsMan ? $oApiTenantsMan->GetTenantById($oAccount->IdTenant) : null;
			if ($oTenant)
			{
				$sEmail = $oTenant->Login . '_' . Constants::DAV_TENANT_PRINCIPAL;
			}
		}
		
		return Backends::Principal()->getPrincipalByEmail($sEmail);
	}
	
	public static function getTenantPrincipalUri($principalUri)
	{
		$sTenantPrincipalUri = null;
		
		/* @var $oApiUsersManager \CApiUsersManager */
		$oApiUsersManager = \CApi::Manager('users');
		$oAccount = $oApiUsersManager->GetAccountOnLogin(basename($principalUri));
		if ($oAccount)
		{
			$sTenantEmail = self::getTenantUser($oAccount);
			$sTenantPrincipalUri = 'principals/'.$sTenantEmail;
		}
		
		return $sTenantPrincipalUri;
	}	
	
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
		
		$stmt->execute(array($sPrincipal, Constants::ADDRESSBOOK_DEFAULT_NAME_OLD));
		$bHasOldDefaultAddressbooks = (count($stmt->fetchAll()) != 0);

		$stmt->execute(array($sPrincipal, Constants::ADDRESSBOOK_COLLECTED_NAME));
		$bHasCollectedAddressbooks = (count($stmt->fetchAll()) != 0);

		$stmt1 = $oPdo->prepare(
			'INSERT INTO '.$dbPrefix.Constants::T_ADDRESSBOOKS.'
				(principaluri, displayname, uri, description, ctag)
				VALUES (?, ?, ?, ?, 1)'
		);
		if (!$bHasDefaultAddressbooks)
		{
			if ($bHasOldDefaultAddressbooks)
			{
				$stmt = $oPdo->prepare(
					'UPDATE '.$dbPrefix.Constants::T_ADDRESSBOOKS.'
						SET uri = ? WHERE principaluri = ? and uri = ?'
				);
				$stmt->execute(array(
						Constants::ADDRESSBOOK_DEFAULT_NAME,
						$sPrincipal,
						Constants::ADDRESSBOOK_DEFAULT_NAME_OLD,
					)
				);
			}
			else
			{
				$stmt1->execute(array(
						$sPrincipal,
						Constants::ADDRESSBOOK_DEFAULT_DISPLAY_NAME,
						Constants::ADDRESSBOOK_DEFAULT_NAME,
						Constants::ADDRESSBOOK_DEFAULT_DISPLAY_NAME
					)
				);
			}
		}
		if (!$bHasCollectedAddressbooks)
		{
			$stmt1->execute(array(
					$sPrincipal,
					Constants::ADDRESSBOOK_COLLECTED_DISPLAY_NAME,
					Constants::ADDRESSBOOK_COLLECTED_NAME,
					Constants::ADDRESSBOOK_COLLECTED_DISPLAY_NAME
				)
			);
		}
	}
	
	public static function getPrincipalByEmail($sEmail) 
	{
		$sEmail = trim(str_ireplace("mailto:", "", $sEmail));
		
		$oPrincipalBackend = Backends::Principal();
		$mPrincipalPath = $oPrincipalBackend->searchPrincipals('principals', array('{http://sabredav.org/ns}email-address'=>$sEmail));
		if($mPrincipalPath == 0) 
		{
			throw new \Exception("Unknown email address");
		}
		
		$sPrincipal = null;
		foreach ($mPrincipalPath as $aPrincipal)
		{
			if ($aPrincipal === 'principals/' . $sEmail)
			{
				$sPrincipal = $aPrincipal;
				break;
			}
		}
		if (!isset($sPrincipal))
		{
			throw new \Exception("Unknown email address");
		}
		
		return $oPrincipalBackend->getPrincipalByPath($sPrincipal);
	}
	
}