<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

// remove the following line for real use
exit('remove this line');

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

/* @var $oApiDomainsManager CApiDomainsManager */
$oApiDomainsManager = CApi::Manager('domains');

/* @var $oApiUsersManager CApiUsersManager */
$oApiUsersManager = CApi::Manager('users');

/* @var $oApiContactsManagerDB CApiContactsManager */
$oApiContactsManagerDB = CApi::Manager('contacts', 'db');

/* @var $oApiContactsManagerSabreDAV CApiContactsManager */
$oApiContactsManagerSabreDAV = CApi::Manager('contacts', 'sabredav');

$sFileFlushPath = CApi::DataPath().'/flushed';
if (!file_exists($sFileFlushPath))
{
	$oApiContactsManagerDB->FlushContacts();
	file_put_contents($sFileFlushPath, '');
}

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
	if (isset($aLine[3]) && is_numeric($aLine[3]))
	{
		$iCurContactsCount = (int) $aLine[3];
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
		file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iCurUserId . ':' . $iCurContactsCount);
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
				file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iUserId . ':' . $iCurContactsCount);

				$iContactsCount = $oApiContactsManagerDB->GetContactItemsCount($iUserId);

				CApi::Log('Contacts count: ' . $iContactsCount, ELogLevel::Full, 'migration-');

				if (0 < $iContactsCount)
				{
					$sIds = '';
					while (true)
					{
						/* @var $aUserListItems array */
						$aUserListItems = $oApiContactsManagerDB->GetContactItems($iUserId,
								EContactSortField::EMail, ESortOrder::ASC, 0, $iItemsPerPage);

						$sOldIds = $sIds;
						$sIds = implode(',', array_map('GetIdFromList', $aUserListItems));

						if (!is_array($aUserListItems) || 0 === count($aUserListItems) || $sIds == $sOldIds)
						{
							break;
						}

						/* @var $oListItem CContactListItem */
						foreach ($aUserListItems as $oListItem)
						{
							CApi::Log('Process contact - START: ' . $oListItem->Id, ELogLevel::Full, 'migration-');
							/* @var $oContact CContact */
							$oContact = $oApiContactsManagerDB->GetContactById($iUserId, $oListItem->Id);
							$newGroupsIds = array();
							foreach ($oContact->GroupsIds as $mGroupId)
							{
								/* @var $oGroup CGroup */
								$oGroup = $oApiContactsManagerDB->GetGroupById($iUserId, $mGroupId);
								if ($oGroup)
								{
									$newGroupsIds[] = $oGroup->Name;
								}
								unset($oGroup);
							}
							$oContact->GroupsIds = $newGroupsIds;

							$oContact->IdContact = '';
							if (empty($oContact->FullName))
							{
								$oContact->FullName = $oContact->FirstName . ' ' . $oContact->LastName;
							}

							$sMap = $oContact->GetMap();
							foreach ($sMap as $sProperty => $aValue)
							{
								if (!empty($oContact->{$sProperty}))
								{
									$oContact->{$sProperty} = str_replace(array("\r","\n\n","\n"), array("\n","\n",'\n'), $oContact->{$sProperty});
								}
							}

							CApi::Log('Add contact to SabreDAV - START: ' . $oListItem->Id, ELogLevel::Full, 'migration-');
							$oContact->__SKIP_VALIDATE__ = true;
							$oApiContactsManagerSabreDAV->CreateContact($oContact);

							CApi::Log('Delete contact from DB - START: ' . $oListItem->Id, ELogLevel::Full, 'migration-');
							$oApiContactsManagerDB->DeleteContacts($iUserId, array($oListItem->Id));

							CApi::Log('Process contact - END: ' . $oListItem->Id, ELogLevel::Full, 'migration-');

							unset($oContact);

							$iCurContactsCount++;
							file_put_contents($sFilePath, $iDomainId . ':' . $iCurUsersPage . ':' . $iUserId . ':' . $iCurContactsCount);
						}

						set_time_limit(30);
					}
				}
				$iCurOffsetContacts = 0;

				$iGroupsCount = $oApiContactsManagerDB->GetGroupItemsCount($iUserId);
				$iPageGroupsCount = round($iGroupsCount / $iItemsPerPage);

				$aGroupsIds = array();
				for ($iPageGroups = 0; $iPageGroups <= $iPageGroupsCount; $iPageGroups++)
				{
					/* @var $aGroupListItems array */
					$aGroupListItems = $oApiContactsManagerDB->GetGroupItems($iUserId,
							EContactSortField::Name, ESortOrder::ASC, $iPageGroups, $iItemsPerPage);
					/* @var $oListItem CContactListItem */
					foreach ($aGroupListItems as $oListItem)
					{
						CApi::Log('Process group: ' . $oListItem->Id, ELogLevel::Full, 'migration-');

						$aGroupsIds[] = $oListItem->Id;
					}
				}
				if (0 < count($aGroupsIds))
				{
					CApi::Log('Delete groups from DB: ' . implode(',', $aGroupsIds), ELogLevel::Full, 'migration-');

					$oApiContactsManagerDB->DeleteGroups($iUserId, $aGroupsIds);
				}

				CApi::Log('Process user - END: ' . $aUserItem[1], ELogLevel::Full, 'migration-');
			}
			set_time_limit(30);
		}
		$iCurUsersPage++;
	}
	$iCurUsersPage = 0;
}


echo 'Contacts Migrated: '. $iCurContactsCount;