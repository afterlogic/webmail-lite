<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Contacts
 */
class CApiMaincontactsDbStorage extends CApiMaincontactsStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * @var CApiMaincontactsCommandCreator
	 */
	protected $oCommandCreator;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('db', $oManager);

		$this->oConnection =& $oManager->GetConnection();
		$this->oCommandCreator =& $oManager->GetCommandCreator(
			$this, array(EDbType::MySQL => 'CApiMaincontactsCommandCreatorMySQL')
		);
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact | bool
	 */
	public function GetContactById($iUserId, $mContactId)
	{
		return $this->getContactBySql($this->oCommandCreator->GetContactById($iUserId, (int) $mContactId));
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return CContact | bool
	 */
	public function GetContactByEmail($iUserId, $sEmail)
	{
		return $this->getContactBySql($this->oCommandCreator->GetContactByEmail($iUserId, $sEmail));
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @return CContact | bool
	 */
	public function GetContactByStrId($iUserId, $sContactStrId)
	{
		return $this->getContactBySql($this->oCommandCreator->GetContactByStrId($iUserId, $sContactStrId));
	}

	/**
	 * @param CContact $oContact
	 * @return array | bool
	 */
	public function GetContactGroupsIds($oContact)
	{
		$mGroupsIds = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetContactGroupsIds($oContact->IdUser, (int) $oContact->IdContact)))
		{
			$oRow = null;
			$mGroupsIds = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$mGroupsIds[] = $oRow->id_group;
			}
		}

		return $mGroupsIds;
	}

	/**
	 * @param string $sSql
	 * @return CContact
	 */
	protected function getContactBySql($sSql)
	{
		$oContact = false;
		if ($this->oConnection->Execute($sSql))
		{
			$oContact = null;

			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oContact = new CContact();
				$oContact->InitByDbRow($oRow);

				$this->updateContactGroupIds($oContact);
			}
		}

		$this->throwDbExceptionIfExist();
		return $oContact;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupId
	 * @return CGroup
	 */
	public function GetGroupById($iUserId, $mGroupId)
	{
		return $this->getGroupBySql($this->oCommandCreator->GetGroupById($iUserId, (int) $mGroupId));
	}

	/**
	 * @param int $iUserId
	 * @param string $sGroupStrId
	 * @return CGroup
	 */
	public function GetGroupByStrId($iUserId, $sGroupStrId)
	{
		return $this->getGroupBySql($this->oCommandCreator->GetGroupByStrId($iUserId, $sGroupStrId));
	}

	/**
	 * @param int $iUserId
	 * @param string $sName
	 * @return CGroup
	 */
	public function GetGroupByName($iUserId, $sName)
	{
		return $this->getGroupBySql($this->oCommandCreator->GetGroupByName($iUserId, $sName));
	}

	/**
	 * @param string $sSql
	 * @return CGroup
	 */
	protected function getGroupBySql($sSql)
	{
		$oGroup = false;
		if ($this->oConnection->Execute($sSql))
		{
			$oGroup = null;

			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oGroup = new CGroup();
				$oGroup->InitByDbRow($oRow);
			}
		}

		$this->throwDbExceptionIfExist();
		return $oGroup;
	}

	/**
	 * @param CContact $oContact
	 */
	protected function updateContactGroupIds(&$oContact)
	{
		if ($oContact)
		{
			$mGroupIds = $this->GetContactGroupsIds($oContact);
			if (is_array($mGroupIds))
			{
				$oContact->GroupsIds = $mGroupIds;
				return true;
			}
		}
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @return bool | array
	 */
	public function GetContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit)
	{
		$mContactsItems = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit)))
		{
			$mContactsItems = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oContactItem = new CContactListItem();
				$oContactItem->InitByDbRowWithType('contact', $oRow);
				$mContactsItems[] = $oContactItem;
				unset($oContactItem);
			}
		}

		return $mContactsItems;
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
	 * @return bool | array
	 */
	public function GetContactItems($iUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iGroupId)
	{
		$mContactItems = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetContactItems($iUserId, $iSortField, $iSortOrder,
			$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iGroupId)))
		{
			$mContactItems = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oContactItem = new CContactListItem();
				$oContactItem->InitByDbRowWithType('contact', $oRow);
				$mContactItems[] = $oContactItem;
				unset($oContactItem);
			}
		}
		return $mContactItems;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param int $iGroupId
	 * @return int
	 */
	public function GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->cnt;
			}
		}
		return $iResult;
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
	 * @return bool | array
	 */
	public function GetGroupItems($iUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iContactId)
	{
		$mGroupItems = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetGroupItems($iUserId, $iSortField, $iSortOrder,
			$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iContactId)))
		{
			$mGroupItems = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oGroupItem = new CContactListItem();
				$oGroupItem->InitByDbRowWithType('group', $oRow);
				$mGroupItems[] = $oGroupItem;
				unset($oGroupItem);
			}
		}
		return $mGroupItems;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @return int
	 */
	public function GetGroupItemsCount($iUserId, $sSearch, $sFirstCharacter)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->GetGroupItemsCount($iUserId, $sSearch, $sFirstCharacter)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->cnt;
			}
		}
		return $iResult;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @return bool | array
	 */
	public function GetSuggestContactItems($iUserId, $sSearch, $iRequestLimit)
	{
		$mContactItems = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetSuggestContactItems(
			$iUserId, $sSearch, $iRequestLimit)))
		{
			$mContactItems = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oContactItem = new CContactListItem();
				$oContactItem->InitByDbRowWithType('suggest-contacts', $oRow);
				$mContactItems[] = $oContactItem;
				unset($oContactItem);
			}
		}
		return $mContactItems;
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function UpdateContact($oContact)
	{
		if ($this->oConnection->Execute($this->oCommandCreator->UpdateContact($oContact)))
		{
			$this->oConnection->Execute($this->oCommandCreator->ClearGroupsIdsByContactsIds(array($oContact->IdContact)));
			if (0 < count($oContact->GroupsIds))
			{
				$this->oConnection->Execute($this->oCommandCreator->UpdateGroupIdsInContact($oContact));
			}
			return true;
		}
		return false;
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function UpdateGroup($oGroup)
	{
		return $this->oConnection->Execute($this->oCommandCreator->UpdateGroup($oGroup));
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function CreateContact($oContact)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->CreateContact($oContact)))
		{
			$oContact->IdContact = $this->oConnection->GetLastInsertId();
			$bResult = $this->UpdateContact($oContact);

			$this->oConnection->Execute($this->oCommandCreator->DeleteAutoCreateContacts(
				$oContact->IdUser, $oContact->ViewEmail));
		}
		return $bResult;
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function CreateGroup($oGroup)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->CreateGroup($oGroup)))
		{
			$oGroup->IdGroup = $this->oConnection->GetLastInsertId();
			$bResult = $this->UpdateGroup($oGroup);
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
	public function DeleteContacts($iUserId, $aContactsIds)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->DeleteContacts($iUserId, $aContactsIds)))
		{
			$bResult = true;
			$this->oConnection->Execute($this->oCommandCreator->ClearGroupsIdsByContactsIds($aContactsIds));
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupsIds
	 * @return bool
	 */
	public function DeleteGroups($iUserId, $aGroupsIds)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->DeleteGroups($iUserId, $aGroupsIds)))
		{
			$bResult = true;
			$this->oConnection->Execute($this->oCommandCreator->ClearContactsIdsByGroupsIds($aGroupsIds));
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupsId
	 * @return bool
	 */
	public function DeleteGroup($iUserId, $mGroupsId)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->DeleteGroups($iUserId, array($mGroupsId))))
		{
			$bResult = true;
			$this->oConnection->Execute($this->oCommandCreator->ClearContactsIdsByGroupsIds(array($mGroupsId)));
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return bool
	 */
	public function UpdateSuggestTable($iUserId, $aEmails)
	{
		if (0 < count($aEmails))
		{
			$aExistingEmails = array();
			if ($this->oConnection->Execute($this->oCommandCreator->GetExistingEmails($iUserId, array_keys($aEmails))))
			{
				$oRow = null;
				while (false !== ($oRow = $this->oConnection->GetNextRecord()))
				{
					if ($oRow->view_email && 0 < strlen($oRow->view_email))
					{
						$aExistingEmails[] = $oRow->view_email;
					}
				}
			}

			$aNonExistingEmails = array_diff(array_keys($aEmails), $aExistingEmails);

			if (0 < count($aNonExistingEmails))
			{
				foreach ($aEmails as $sEmail => $sName)
				{
					if (in_array($sEmail, $aNonExistingEmails))
					{
						$this->oConnection->Execute($this->oCommandCreator->CreateAutoCreateContact($iUserId, $sEmail, $sName));
					}
				}
			}

			$this->oConnection->Execute(
				$this->oCommandCreator->UpdateContactFrequencyByEmails($iUserId, array_keys($aEmails)));
		}
		return true;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactIds
	 * @return bool
	 */
	public function DeleteContactsExceptIds($iUserId, $aContactIds)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->DeleteContactsExceptIds($iUserId, $aContactIds)))
		{
			$bResult = true;
			$this->oConnection->Execute($this->oCommandCreator->ClearGroupsIdsByExceptContactsIds($iUserId, $aContactIds));
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupIds
	 * @return bool
	 */
	public function DeleteGroupsExceptIds($iUserId, $aGroupIds)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->DeleteGroupsExceptIds($iUserId, $aGroupIds)))
		{
			$bResult = true;
			$this->oConnection->Execute($this->oCommandCreator->ClearContactsIdsByExceptGroupsIds($iUserId, $aGroupIds));
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function ClearAllContactsAndGroups($oAccount)
	{
		$bResult = true;

		if ($oAccount && $oAccount->IsDefaultAccount)
		{
			$iUserId = $oAccount->IdUser;

			$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAllGroupsContacts($iUserId));
			$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAllContacts($iUserId));
			$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAllGroups($iUserId));
		}

		return (bool) $bResult;
	}

	/**
	 * @return bool
	 */
	public function FlushContacts()
	{
		$bResult = true;

		$bResult &= $this->oConnection->Execute($this->oCommandCreator->FlushContacts());

		return (bool) $bResult;
	}

	/**
	 * @deprecated
	 * @param CContact $oContact
	 * @param array $aGroupIds
	 * @return bool
	 */
	public function AddContactToGroup($oContact, $aGroupIds)
	{
		$bResult = true;

		$bResult &= $this->oConnection->Execute($this->oCommandCreator->AddContactToGroup($oContact, $aGroupIds));

		return (bool) $bResult;
	}

	/**
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return bool
	 */
	public function AddContactsToGroup($oGroup, $aContactIds)
	{
		$iResult = 1;

		$sSql = $this->oCommandCreator->RemoveContactsFromGroup($oGroup, $aContactIds);
		$iResult &= 0 < strlen($sSql) ? $this->oConnection->Execute($sSql) : true;
		if ($iResult)
		{
			$iResult &= $this->oConnection->Execute($this->oCommandCreator->AddContactsToGroup($oGroup, $aContactIds));
		}

		return (bool) $iResult;
	}

	/**
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return bool
	 */
	public function RemoveContactsFromGroup($oGroup, $aContactIds)
	{
		$sSql = $this->oCommandCreator->RemoveContactsFromGroup($oGroup, $aContactIds);
		return 0 < strlen($sSql) ? $this->oConnection->Execute($sSql) : true;
	}

	/**
	 * @param CAccount $oAccount
	 * @param mixed $mContactId
	 * @param int $iContactType = EContactType::Global_
	 * @return int|null
	 */
	public function ConvertedContactLocalId($oAccount, $mContactId, $iContactType)
	{
		$mResult = null;
		if ($this->oConnection->Execute($this->oCommandCreator->ConvertedContactLocalId($oAccount, $mContactId, $iContactType)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$mResult = (int) $oRow->id_addr;
				$mResult = 0 < $mResult ? $mResult : null;
			}
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param int $iContactType = EContactType::Global_
	 * @return array
	 */
	public function ConvertedContactLocalIdCollection($oAccount, $iContactType = EContactType::Global_)
	{
		$aResult = array();
		if ($this->oConnection->Execute($this->oCommandCreator->ConvertedContactLocalIdCollection($oAccount, $iContactType)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$aResult[(int) $oRow->id_addr] = (string) $oRow->type_id;
			}
		}
		return $aResult;
	}

	/**
	 * @param array $aIds
	 * @return array
	 */
	public function ContactIdsLinkedToGroups($aIds)
	{
		$aResult = array();
		if ($this->oConnection->Execute($this->oCommandCreator->ContactIdsLinkedToGroups($aIds)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$aResult[] = (int) $oRow->id_addr;
			}
		}
		return $aResult;
	}

	/**
	 * @deprecated
	 * @param mixed $mContactId
	 * @param array $aGroupIds
	 * @return bool
	 */
	public function DeleteContactFromGroup($mContactId, $aGroupIds)
	{
		$bResult = true;

		$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteContactFromGroup($mContactId, $aGroupIds));

		return (bool) $bResult;
	}
	
	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact | false
	 */
	public function GetGlobalContactById($iUserId, $mContactId)
	{
		return $this->getContactBySql($this->oCommandCreator->GetGlobalContactById($iUserId, (int) $mContactId));
	}
}
