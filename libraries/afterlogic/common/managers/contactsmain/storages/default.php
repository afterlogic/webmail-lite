<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @ignore
 * @package Contactsmain
 * @subpackage Storages
 */
class CApiContactsmainStorage extends AApiManagerStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct($sStorageName, CApiGlobalManager &$oManager)
	{
		parent::__construct('contactsmain', $sStorageName, $oManager);
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact | false
	 */
	public function getContactById($iUserId, $mContactId)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @return CContact|null
	 */
	public function GetMyGlobalContact($iUserId)
	{
		return null;
	}

	/**
	 * @param mixed $mTypeId
	 * @param int $iContactType
	 * @return CContact|bool
	 */
	public function GetContactByTypeId($mTypeId, $mContactId)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return CContact|bool
	 */
	public function getContactByEmail($iUserId, $sEmail)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @return CContact|bool
	 */
	public function getContactByStrId($iUserId, $sContactStrId)
	{
		return false;
	}
	
	/**
	 * @param int $iUserId
	 * @return array|bool
	 */
	public function getSharedContactIds($iUserId, $sContactStrId)
	{
		return array();
	}
	

	/**
	 * @param CContact $oContact
	 * @return array|bool
	 */
	public function getContactGroupsIds($oContact)
	{
		return array();
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupId
	 * @return CGroup
	 */
	public function getGroupById($iUserId, $mGroupId)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param string $sGroupStrId
	 * @return CGroup
	 */
	public function getGroupByStrId($iUserId, $sGroupStrId)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param string $sName
	 * @return CGroup
	 */
	public function getGroupByName($iUserId, $sName)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @return bool|array
	 */
	public function getContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit)
	{
		return array();
	}

	/**
	 * @param int $iUserId
	 * @param int $iSortField
	 * @param int $iSortOrder
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param int $iGroupId
	 * @param int $iTenantId
	 * @return bool|array
	 */
	public function getContactItems($iUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iGroupId, $iTenantId = null, $bAll = false)
	{
		return array();
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param int $iGroupId
	 * @param int $iTenantId
	 * @return int
	 */
	public function getContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId, $iTenantId = null, $bAll = false)
	{
		return 0;
	}

	/**
	 * @param int $iUserId
	 * @param int $iSortField
	 * @param int $iSortOrder
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param int $iContactId
	 * @return bool|array
	 */
	public function getGroupItems($iUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iContactId)
	{
		return array();
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @return int
	 */
	public function getGroupItemsCount($iUserId, $sSearch, $sFirstCharacter)
	{
		return 0;
	}

	/**
	 * @param int $iUserId
	 * @param int $iTenantId = 0
	 * @param bool $bAddGlobal = true
	 * @return bool|array
	 */
	public function GetAllContactsNamesWithPhones($iUserId, $iTenantId = 0, $bAddGlobal = true)
	{
		return array();
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @param bool $bPhoneOnly = false
	 * @return bool|array
	 */
	public function GetSuggestContactItems($iUserId, $sSearch, $iRequestLimit, $bPhoneOnly = false)
	{
		return array();
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @return bool|array
	 */
	public function GetSuggestGroupItems($iUserId, $sSearch, $iRequestLimit)
	{
		return array();
	}
	
	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function updateContact($oContact)
	{
		return false;
	}
	
	/**
	 * @param CContact $oContact
	 * @param int $iUserId
	 * @return string
	 */
	public function updateContactUserId($oContact, $iUserId)
	{
		return true;
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function updateGroup($oGroup)
	{
		return false;
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function createContact($oContact)
	{
		return false;
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function createGroup($oGroup)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
	public function deleteContacts($iUserId, $aContactsIds)
	{
		return true;
	}
	
	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
	public function deleteSuggestContacts($iUserId, $aContactsIds)
	{
		return true;
	}	

	/**
	 * @param int $iUserId
	 * @param array $aGroupsIds
	 * @return bool
	 */
	public function deleteGroups($iUserId, $aGroupsIds)
	{
		return true;
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return bool
	 */
	public function updateSuggestTable($iUserId, $aEmails)
	{
		return true;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactIds
	 * @return bool
	 */
//	public function DeleteContactsExceptIds($iUserId, $aContactIds)
//	{
//		return true;
//	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupIds
	 * @return bool
	 */
//	public function DeleteGroupsExceptIds($iUserId, $aGroupIds)
//	{
//		return true;
//	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function clearAllContactsAndGroups($oAccount)
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function flushContacts()
	{
		return true;

	}

	/**
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return bool
	 */
	public function addContactsToGroup($oGroup, $aContactIds)
	{
		return true;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact | false
	 */
	public function GetGlobalContactById($iUserId, $mContactId)
	{
		return false;
	}	
	
}
