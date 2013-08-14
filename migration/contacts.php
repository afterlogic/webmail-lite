<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

// remove the following line for real use
exit('remove this line');

require_once dirname(__FILE__).'/../libraries/afterlogic/api.php';

$iItemsPerPage = 20;
$iCurDomainId = -1;
$iCurUsersPage = 0;
$iCurUserId = 0;

/* @var $oApiDomainsManager CApiDomainsManager */
$oApiDomainsManager = CApi::Manager('domains');

/* @var $oApiUsersManager CApiUsersManager */
$oApiUsersManager = CApi::Manager('users');

/* @var $oApiContactsManagerDB CApiContactsManager */
$oApiContactsManagerDB = CApi::Manager('maincontacts', 'db');

/* @var $oApiContactsManagerSabreDAV CApiContactsManager */
$oApiContactsManagerSabreDAV = CApi::Manager('maincontacts', 'sabredav');

$sFilePath = CApi::DataPath().'/mirgation';
if (file_exists($sFilePath))
{
	$handle = fopen($sFilePath, 'r');
	$sLine = fgets($handle);
	$aLine = explode(':', $sLine);
	if (isset($aLine[0]) && is_numeric($aLine[0]))
	{
		$iCurDomainId = (int) $aLine[0];
	}
	if (isset($aLine[1]) && is_numeric($aLine[1]))
	{
		$iCurUsersPage = (int) $aLine[1];
	}
	if (isset($aLine[2]) && is_numeric($aLine[2]))
	{
		$iCurUserId = (int) $aLine[2];
	}
}

$aDomains = $oApiDomainsManager->GetFullDomainsList();
$aDomains[0] = array(false, 'Default'); // Default Domain

$bFindDomain = false;
$bFindUser = false;

function GetIdFromList($oItem)
{
	return $oItem->Id;
}

foreach ($aDomains as $iDomainId => $oDomainItem)
{
	if (!$bFindDomain && $iCurDomainId !== -1 && $iCurDomainId !== $iDomainId)
	{
		CApi::Log('Skip domain: ' . $oDomainItem[1], ELogLevel::Full, 'migration-');
		continue;
	}
	else
	{
		$bFindDomain = true;
	}

	file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iCurUserId);

	CApi::Log('Process domain: ' . $oDomainItem[1], ELogLevel::Full, 'migration-');

	$iUsersCount = $oApiUsersManager->GetUserCount($iDomainId);
	$iPageUserCount = ceil($iUsersCount / $iItemsPerPage);

	CApi::Log('Users count: ' . $iUsersCount, ELogLevel::Full, 'migration-');

	$aUsers = array();
	while ($iCurUsersPage < $iPageUserCount)
	{
		file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iCurUserId);
		$aUsers = $oApiUsersManager->GetUserList($iDomainId, $iCurUsersPage, $iItemsPerPage);
		if ($aUsers)
		{
			foreach ($aUsers as $aUserItem)
			{
				$iUserId = (int) $aUserItem[4];
				CApi::Log('Process user - START: ' . $aUserItem[1], ELogLevel::Full, 'migration-');
				if (!$bFindUser && $iCurUserId !== 0 && $iCurUserId !== $iUserId)
				{
					CApi::Log('Skip user: ' . $aUserItem[1], ELogLevel::Full, 'migration-');
					continue;
				}
				$bFindUser = true;
				file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iUserId);

				/* @var $aUserListItems array */
				$aUserListItems = $oApiContactsManagerSabreDAV->GetContactItemsWithoutOrder($iUserId, -1, -1);

				/* @var $oListItem CContactListItem */
				foreach ($aUserListItems as $oListItem)
				{
					CApi::Log('Process contact - START: ' . $oListItem->Id, ELogLevel::Full, 'migration-');
					/* @var $oContact CContact */
					
					$oContactDb = $oApiContactsManagerDB->GetContactByStrId($iUserId, $oListItem->Id);
					if (!$oContactDb)
					{
						$oContact = $oApiContactsManagerSabreDAV->GetContactById($iUserId, $oListItem->Id);

						$oContact->IdContact = '';
						if (empty($oContact->FullName))
						{
							$oContact->FullName = $oContact->FirstName . ' ' . $oContact->LastName;
						}

						CApi::Log('Add contact to SabreDAV - START: ' . $oListItem->Id, ELogLevel::Full, 'migration-');
						$oContact->__SKIP_VALIDATE__ = true;
						CApi::LogObject($oContact, ELogLevel::Full, 'migration-');
						$oApiContactsManagerDB->CreateContact($oContact);

						CApi::Log('Process contact - END: ' . $oListItem->Id, ELogLevel::Full, 'migration-');

						unset($oContact);
					}
					set_time_limit(30);
				}

				CApi::Log('Process user - END: ' . $aUserItem[1], ELogLevel::Full, 'migration-');
			}
		}
		$iCurUsersPage++;
	}
}