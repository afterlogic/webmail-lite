<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

// remove the following line for real use
//exit('remove this line');

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

require_once WM_ROOTPATH.'application/include.php';
require_once WM_ROOTPATH.'common/inc_constants.php';

if (!CApi::IsValidFullSupportPhpVersion())
{
	exit('php version is not valid');
}

$iItemsPerPage = 20;

$iCurDomainId = -1;
$iCurUsersPage = 0;
$iCurUserId = 0;
$iCurOffsetContacts = 0;
$iCurContactsCount = 0;
$iCurContactsOffset = 0;


/* @var $oApiDomainsManager CApiDomainsManager */
$oApiDomainsManager = CApi::Manager('domains');

/* @var $oApiUsersManager CApiUsersManager */
$oApiUsersManager = CApi::Manager('users');

/* @var $oApiContactsManager CApiContactsManager */
$oApiContactsManager = CApi::Manager('contacts');

$sFilePath = CApi::DataPath().'/contacts-names';
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
	if (isset($aLine[3]) && is_numeric($aLine[3]))
	{
		$iCurContactsOffset = (int) $aLine[3];
	}
	if (isset($aLine[4]) && is_numeric($aLine[4]))
	{
		$iCurContactsCount = (int) $aLine[4];
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
		CApi::Log('Skip domain: ' . $oDomainItem[1], ELogLevel::Full, 'contacts-names-');
		continue;
	}
	else
	{
		$bFindDomain = true;
	}

	file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iCurUserId . ':' . $iCurContactsOffset . ':' . $iCurContactsCount);

	CApi::Log('Process domain: ' . $oDomainItem[1], ELogLevel::Full, 'contacts-names-');

	$iUsersCount = $oApiUsersManager->GetUserCount($iDomainId);
	$iPageUserCount = ceil($iUsersCount / $iItemsPerPage);

	CApi::Log('Users count: ' . $iUsersCount, ELogLevel::Full, 'contacts-names-');

	$aUsers = array();
	while ($iCurUsersPage < $iPageUserCount)
	{
		file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iCurUserId . ':' . $iCurContactsOffset . ':' . $iCurContactsCount);
		$aUsers = $oApiUsersManager->GetUserList($iDomainId, $iCurUsersPage, $iItemsPerPage);
		if ($aUsers)
		{
			foreach ($aUsers as $aUserItem)
			{
				$iUserId = (int) $aUserItem[4];
				if ($iUserId !== 0)
				{
					CApi::Log('Process user - START: ' . $aUserItem[1] . ' - ' . $iUserId, ELogLevel::Full, 'contacts-names-');
					if (!$bFindUser && $iCurUserId !== 0 && $iCurUserId !== $iUserId)
					{
						CApi::Log('Skip user: ' . $aUserItem[1], ELogLevel::Full, 'contacts-names-');
						continue;
					}
					$bFindUser = true;
					file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iUserId . ':' . $iCurContactsOffset . ':' . $iCurContactsCount);

					$iContactsCount = $oApiContactsManager->GetContactItemsCount($iUserId);

					CApi::Log('Contacts count: ' . $iContactsCount, ELogLevel::Full, 'contacts-names-');

					if (0 < $iContactsCount)
					{
						while ($iCurContactsOffset < $iContactsCount)
						{
							/* @var $aUserListItems array */
							$aUserListItems = $oApiContactsManager->GetContactItems($iUserId,
									EContactSortField::EMail, ESortOrder::ASC, $iCurContactsOffset,
									$iItemsPerPage);

							/* @var $oListItem CContactListItem */
							foreach ($aUserListItems as $oListItem)
							{
								CApi::Log('Process contact - START: ' . $oListItem->Id, ELogLevel::Full, 'contacts-names-');
								/* @var $oContact CContact */
								$oContact = $oApiContactsManager->GetContactById($iUserId, $oListItem->Id);
								$sName = $oContact->FirstName;
								$oContact->FirstName = $oContact->LastName;
								$oContact->LastName = $sName;

								$oApiContactsManager->UpdateContact($oContact);

								unset($oContact);

								$iCurContactsCount++;
								file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iUserId . ':' . $iCurContactsOffset . ':' . $iCurContactsCount);
							}

							$iCurContactsOffset += $iItemsPerPage;

							set_time_limit(30);
						}
					}
					$iCurOffsetContacts = 0;

					CApi::Log('Process user - END: ' . $aUserItem[1], ELogLevel::Full, 'contacts-names-');
				}
			}
			set_time_limit(30);
		}
		$iCurUsersPage++;
	}
	$iCurUsersPage = 0;
}


echo 'Contacts Migrated: '. $iCurContactsCount;


