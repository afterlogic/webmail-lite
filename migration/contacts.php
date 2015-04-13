<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

// remove the following line for real use
exit('remove this line');

require_once dirname(__FILE__).'/../libraries/afterlogic/api.php';

$bReverse = isset($_REQUEST['rev']) ? true : false;

$sStorageFrom = 'db';
$sStorageTo = 'sabredav';

if ($bReverse)
{
	$sStorageTmp = $sStorageTo;
	$sStorageTo = $sStorageFrom;
	$sStorageFrom = $sStorageTmp;
}

$iItemsPerPage = 20;
$iCurDomainId = -1;
$iCurUsersPage = 1;
$iCurUserId = 0;

/* @var $oApiDomainsManager CApiDomainsManager */
$oApiDomainsManager = CApi::Manager('domains');

/* @var $oApiUsersManager CApiUsersManager */
$oApiUsersManager = CApi::Manager('users');

/* @var $oApiContactsManagerFrom CApiContactsManager */
$oApiContactsManagerFrom = CApi::Manager('contactsmain', $sStorageFrom);

/* @var $oApiContactsManagerTo CApiContactsManager */
$oApiContactsManagerTo = CApi::Manager('contactsmain', $sStorageTo);

$sFilePath = CApi::DataPath().'/migration';
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

CApi::Log('From storage: ' . $sStorageFrom, ELogLevel::Full, 'migration-');
CApi::Log('To storage: ' . $sStorageTo, ELogLevel::Full, 'migration-');

$aDomains = $oApiDomainsManager->GetFullDomainsList();
$aDomains[0] = array(false, 'Default'); // Default Domain

$bFindDomain = false;
$bFindUser = false;

function GetIdFromList($oItem)
{
	return $oItem->Id;
}

$iUserCount = 0;
$aUsersCache = array();
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
	while ($iCurUsersPage - 1 < $iPageUserCount)
	{
		file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iCurUserId);
		$aUsers = $oApiUsersManager->GetUserList($iDomainId, $iCurUsersPage, $iItemsPerPage);
		if ($aUsers)
		{
			foreach ($aUsers as $aUserItem)
			{
				if (in_array($aUserItem[1], $aUsersCache))
				{
					CApi::Log('WARNING: Duplicate user - ' . $aUserItem[1], ELogLevel::Full, 'migration-');
				}
				$aUsersCache[] = $aUserItem[1];
				$iUserId = (int) $aUserItem[4];
				$iUserCount++;
				CApi::Log('Process user: ' . $iUserCount . ' - ' . $aUserItem[1], ELogLevel::Full, 'migration-');
				if (!$bFindUser && $iCurUserId !== 0 && $iCurUserId !== $iUserId)
				{
					CApi::Log('Skip user: ' . $aUserItem[1], ELogLevel::Full, 'migration-');
					CApi::Log('--------------------', ELogLevel::Full, 'migration-');
					continue;
				}
				$bFindUser = true;
				file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iUserId);

				/* @var $aUserListItems array */
				$aUserListItems = $oApiContactsManagerFrom->GetContactItemsWithoutOrder($iUserId, 0, 9999);
				CApi::Log('Contacts count: ' . count($aUserListItems), ELogLevel::Full, 'migration-');

				/* @var $oListItem CContactListItem */
				foreach ($aUserListItems as $oListItem)
				{
					/* @var $oContactTo CContact */
					
					$oContactFrom = $oApiContactsManagerTo->GetContactByStrId($iUserId, $oListItem->Id);
					if (!$oContactFrom)
					{
						$oContactTo = $oApiContactsManagerFrom->GetContactById($iUserId, $oListItem->Id);
						if ($bReverse)						
						{
							$oContactTo = $oApiContactsManagerFrom->GetContactByStrId($iUserId, $oListItem->Id);
						}

						$oContactTo->IdContact = '';
						if (empty($oContactTo->FullName))
						{
							$oContactTo->FullName = $oContactTo->FirstName . ' ' . $oContactTo->LastName;
						}

/*						
						if (0 < count($oContact->GroupsIds))
						{
							foreach ($oContact->GroupsIds as $sGroupId)
							{
								$oGroup = $oApiContactsManagerTo->GetGroupById($iUserId, (string) $sGroupId);
							}
						}
*/
						
						$oContactTo->GroupsIds = array();
						
						CApi::Log('Add contact: ' . $oListItem->Id, ELogLevel::Full, 'migration-');
						$oContactTo->__SKIP_VALIDATE__ = true;
						$oApiContactsManagerTo->CreateContact($oContactTo);

						unset($oContactTo);
					}
					else 
					{
						CApi::Log('Skip contact: ' . $oListItem->Id, ELogLevel::Full, 'migration-');
					}
					set_time_limit(30);
				}

				CApi::Log('--------------------', ELogLevel::Full, 'migration-');
			}
		}
		$iCurUsersPage++;
	}
	$iCurUsersPage = 0;
}