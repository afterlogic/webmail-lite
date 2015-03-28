<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV;

class Utils
{
	public static $oUsersManager = null;
	
	public static function getUsersManager()
	{
		if (null === self::$oUsersManager)
		{
			/* @var $oUsersManager \CApiUsersManager */
			self::$oUsersManager = \CApi::Manager('users');
		}
		return self::$oUsersManager;
	}
	
	public static function getCurrentAccount()
	{
		return self::getUsersManager()->GetAccountOnLogin(\afterlogic\DAV\Auth\Backend::getInstance()->getCurrentUser());
	}
	
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
		
		return Backend::Principal()->getPrincipalByEmail($sEmail);
	}
	
	public static function getTenantPrincipalUri($principalUri)
	{
		$sTenantPrincipalUri = null;
		
		$oAccount = \afterlogic\DAV\Utils::GetAccountByLogin(basename($principalUri));
		if ($oAccount)
		{
			$sTenantEmail = self::getTenantUser($oAccount);
			$sTenantPrincipalUri = \afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $sTenantEmail;
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

	public static function GetAccountByLogin($sUserName)
	{
		$oUsersManager = self::getUsersManager();
		return $oUsersManager->GetAccountOnLogin($sUserName);
	}	

	public static function CheckPrincipals($sUserName)
	{
		if (trim($sUserName) !== '')
		{
			$oPdo = \CApi::GetPDO();
			$dbPrefix = \CApi::GetSettingsConf('Common/DBPrefix');

			$sPrincipal = \afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $sUserName;

			$oStmt = $oPdo->prepare(
				'SELECT id FROM '.$dbPrefix.Constants::T_PRINCIPALS.' WHERE uri = ? LIMIT 1'
			);
			$oStmt->execute(array($sPrincipal));
			if(count($oStmt->fetchAll()) === 0)
			{
				$oStmt = $oPdo->prepare(
					'INSERT INTO '.$dbPrefix.Constants::T_PRINCIPALS.'
						(uri,email,displayname) VALUES (?, ?, ?)'
				);
				try
				{
					$oStmt->execute(array($sPrincipal, $sUserName, ''));
				}
				catch (Exception $e){}
			}

			$oStmt = $oPdo->prepare(
				'SELECT principaluri FROM '.$dbPrefix.Constants::T_CALENDARS.'
					WHERE principaluri = ?'
			);
			$oStmt->execute(array($sPrincipal));
			if (count($oStmt->fetchAll()) === 0)
			{
				$oStmt = $oPdo->prepare(
					'INSERT INTO '.$dbPrefix.Constants::T_CALENDARS.'
						(principaluri, displayname, uri, description, components, ctag, calendarcolor)
						VALUES (?, ?, ?, ?, ?, 1, ?)'
				);

				$oAccount = self::GetAccountByLogin($sUserName);

				$oStmt->execute(array(
						$sPrincipal,
						\CApi::ClientI18N('CALENDAR/CALENDAR_DEFAULT_NAME', $oAccount),
						\Sabre\DAV\UUIDUtil::getUUID(),
						'',
						'VEVENT,VTODO',
						Constants::CALENDAR_DEFAULT_COLOR
					)
				);
			}		

			$oStmt = $oPdo->prepare(
				'SELECT principaluri FROM '.$dbPrefix.Constants::T_CALENDARS.'
					WHERE principaluri = ? and uri = ? LIMIT 1'
			);
			$oStmt->execute(array($sPrincipal, Constants::CALENDAR_DEFAULT_NAME));
			if (count($oStmt->fetchAll()) !== 0)
			{
				$oStmt = $oPdo->prepare(
					'UPDATE '.$dbPrefix.Constants::T_CALENDARS.'
						SET uri = ? WHERE principaluri = ? and uri = ?'
				);
				$oStmt->execute(array(
						\Sabre\DAV\UUIDUtil::getUUID(),
						$sPrincipal,
						Constants::CALENDAR_DEFAULT_NAME
					)
				);
			}

			$oStmt = $oPdo->prepare(
				'SELECT principaluri FROM '.$dbPrefix.Constants::T_ADDRESSBOOKS.'
					WHERE principaluri = ? and uri = ? LIMIT 1'
			);

			$oStmt->execute(array($sPrincipal, Constants::ADDRESSBOOK_DEFAULT_NAME));
			$bHasDefaultAddressbooks = (count($oStmt->fetchAll()) != 0);

			$oStmt->execute(array($sPrincipal, Constants::ADDRESSBOOK_DEFAULT_NAME_OLD));
			$bHasOldDefaultAddressbooks = (count($oStmt->fetchAll()) != 0);

			$oStmt->execute(array($sPrincipal, Constants::ADDRESSBOOK_COLLECTED_NAME));
			$bHasCollectedAddressbooks = (count($oStmt->fetchAll()) != 0);

			$stmt1 = $oPdo->prepare(
				'INSERT INTO '.$dbPrefix.Constants::T_ADDRESSBOOKS.'
					(principaluri, displayname, uri, description, ctag)
					VALUES (?, ?, ?, ?, 1)'
			);
			if (!$bHasDefaultAddressbooks)
			{
				if ($bHasOldDefaultAddressbooks)
				{
					$oStmt = $oPdo->prepare(
						'UPDATE '.$dbPrefix.Constants::T_ADDRESSBOOKS.'
							SET uri = ? WHERE principaluri = ? and uri = ?'
					);
					$oStmt->execute(array(
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
	}
	
	public static function getPrincipalByEmail($sEmail) 
	{
		$sEmail = trim(str_ireplace("mailto:", "", $sEmail));
		
		$oPrincipalBackend = Backend::Principal();
		$mPrincipalPath = $oPrincipalBackend->searchPrincipals(\afterlogic\DAV\Constants::PRINCIPALS_PREFIX, array('{http://sabredav.org/ns}email-address'=>$sEmail));
		if($mPrincipalPath == 0) 
		{
			throw new \Exception("Unknown email address");
		}
		
		$sPrincipal = null;
		foreach ($mPrincipalPath as $aPrincipal)
		{
			if ($aPrincipal === \afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $sEmail)
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