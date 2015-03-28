<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Contacts
 */
class CApiContactsmainDbStorage extends CApiContactsmainStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * @var CApiContactsmainCommandCreator
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
			$this, array(
				EDbType::MySQL => 'CApiContactsmainCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiContactsmainCommandCreatorPostgreSQL'
			)
		);
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @param bool $bIgnoreHideInGab = false
	 * @param int $iSharedTenantId = null
	 * @return CContact|bool
	 */
	public function GetContactById($iUserId, $mContactId, $bIgnoreHideInGab = false, $iSharedTenantId = null)
	{
		return $this->getContactBySql($this->oCommandCreator->GetContactById($iUserId, (int) $mContactId, $bIgnoreHideInGab, $iSharedTenantId), $iUserId);
	}

	/**
	 * @param mixed $mTypeId
	 * @param mixed $mContactId
	 * @param bool $bIgnoreHideInGab = false
	 * @return CContact | bool
	 */
	public function GetContactByTypeId($mTypeId, $mContactId, $bIgnoreHideInGab = false)
	{
		return $this->getContactBySql($this->oCommandCreator->GetContactByTypeId($mTypeId, $mContactId, $bIgnoreHideInGab));
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return CContact | bool
	 */
	public function GetContactByEmail($iUserId, $sEmail)
	{
		return $this->getContactBySql($this->oCommandCreator->GetContactByEmail($iUserId, $sEmail), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @param int $iSharedTenantId = null
	 * @return CContact | bool
	 */
	public function GetContactByStrId($iUserId, $sContactStrId, $iSharedTenantId = null)
	{
		return $this->getContactBySql($this->oCommandCreator->GetContactByStrId($iUserId, $sContactStrId, $iSharedTenantId), $iUserId, $iSharedTenantId);
	}
	
	/**
	 * @param int $iUserId
	 * @return array | bool
	 */
	public function GetSharedContactIds($iUserId, $iSharedTenantId)
	{
		$mSharedContactIds = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetSharedContactIds($iUserId, $iSharedTenantId)))
		{
			$oRow = null;
			$mSharedContactIds = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$mSharedContactIds[] = (string) $oRow->str_id;
			}
		}

		return $mSharedContactIds;
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
				$mGroupsIds[] = (string) $oRow->id_group;
			}
		}

		return $mGroupsIds;
	}

	/**
	 * @param int $iUserId
	 * @return CContact|null
	 */
	public function GetMyGlobalContact($iUserId)
	{
		/* @var $oApiUsersManager CApiUsersManager */
		$oApiUsersManager = CApi::Manager('users');
		
		/* @var $oApiGContactsManager CApiGcontactsManager */
		$oApiGContactsManager = CApi::Manager('gcontacts');
		
		if ($oApiUsersManager && $oApiGContactsManager)
		{
			$oDefAccount = null;
			
			$iIdAccount = $oApiUsersManager->GetDefaultAccountId($iUserId);
			if (0 < $iIdAccount)
			{
				$oDefAccount = $oApiUsersManager->GetAccountById($iIdAccount);
			}

			if ($oDefAccount)
			{
				return $oApiGContactsManager->GetContactByTypeId($oDefAccount, $oDefAccount->IdUser);
			}
		}

		return null;
	}

	/**
	 * @param string $sSql
	 * @param int $iUserId = null
	 * @param int $iSharedTenantId = null
	 * @return CContact
	 */
	protected function getContactBySql($sSql, $iUserId = null, $iSharedTenantId = null)
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

				if ($oContact->ReadOnly && null !== $iUserId &&
					(EContactType::Global_ === $oContact->Type || EContactType::GlobalAccounts === $oContact->Type))
				{
					$oGContact = $this->GetMyGlobalContact($iUserId);
					if ($oGContact && (string) $oContact->IdTypeLink === (string) $oGContact->IdContact)
					{
						$oContact->ReadOnly = false;
						$oContact->ItsMe = true;
					}
				}

				$this->oConnection->FreeResult();
				$this->updateContactGroupIds($oContact);
			}
			else
			{
				$this->oConnection->FreeResult();
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

			$this->oConnection->FreeResult();
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
	 * @param int $iSharedTenantId = null
	 * @param bool $bAll = false
	 * @return bool | array
	 */
	public function GetContactItems($iUserId, $iSortField, $iSortOrder, $iOffset,
		$iRequestLimit, $sSearch, $sFirstCharacter, $iGroupId, $iSharedTenantId = null, $bAll = false)
	{
		$mContactItems = false;
		$mItsMeTypeId = null;
		
		if (0 < $iGroupId)
		{
			$oGContact = $this->GetMyGlobalContact($iUserId);
			$mItsMeTypeId = $oGContact ? $oGContact->IdContact : null;
		}
		if ($bAll)
		{
			$mItsMeTypeId = $iUserId;
		}

		if ($this->oConnection->Execute($this->oCommandCreator->GetContactItems($iUserId, $iSortField, $iSortOrder,
			$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iGroupId, $iSharedTenantId, $bAll)))
		{
			$mContactItems = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oContactItem = new CContactListItem();
				$oContactItem->InitByDbRowWithType('contact', $oRow, $mItsMeTypeId);
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
	 * @param int $iSharedTenantId = null
	 * @param bool $bAll = false
	 * @return int
	 */
	public function GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId, $iSharedTenantId = null, $bAll = false)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->GetContactItemsCount(
			$iUserId, $sSearch, $sFirstCharacter, $iGroupId, $iSharedTenantId, $bAll)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->cnt;
			}

			$this->oConnection->FreeResult();
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

			$this->oConnection->FreeResult();
		}
		return $iResult;
	}

	/**
	 * @param int $iUserId
	 * @param int $iTenantId = 0
	 * @param bool $bAddGlobal = true
	 * @return bool | array
	 */
	public function GetAllContactsNamesWithPhones($iUserId, $iTenantId = 0, $bAddGlobal = true)
	{
		$mNames = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetAllContactsNamesWithPhones(
			$iUserId, $iTenantId, $bAddGlobal)))
		{
			$mNames = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$sName = trim($oRow->fullname);
				if (0 === strlen($sName))
				{
					$sName = trim($oRow->firstname);
					$sName .= ' '.trim($oRow->surname);
					$sName = trim($sName);
				}

				if (0 === strlen($sName))
				{
					$sName = trim($oRow->view_email);
				}

				if (0 < strlen($sName))
				{
					$sPhone = trim($oRow->b_phone);
					if (0 < strlen($sPhone))
					{
						$sPhone = api_Utils::ClearPhone($sPhone);
						if (0 < strlen($sPhone))
						{
							$mNames[$sPhone] = $sName;
						}
					}

					$sPhone = trim($oRow->h_phone);
					if (0 < strlen($sPhone))
					{
						$sPhone = api_Utils::ClearPhone($sPhone);
						if (0 < strlen($sPhone))
						{
							$mNames[$sPhone] = $sName;
						}
					}

					$sPhone = trim($oRow->h_mobile);
					if (0 < strlen($sPhone))
					{
						$sPhone = api_Utils::ClearPhone($sPhone);
						if (0 < strlen($sPhone))
						{
							$mNames[$sPhone] = $sName;
						}
					}
				}
			}
		}

		return $mNames;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @param bool $bPhoneOnly = false
	 * @param int $iSharedTenantId = null
	 * @param bool $bAll = false
	 * @return bool | array
	 */
	public function GetSuggestContactItems($iUserId, $sSearch, $iRequestLimit, $bPhoneOnly = false, $iSharedTenantId = null, $bAll = false)
	{
		$mContactItems = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetSuggestContactItems(
			$iUserId, $sSearch, $iRequestLimit, $bPhoneOnly, $iSharedTenantId, $bAll)))
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
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @param int $iSharedTenantId = null
	 * @return bool | array
	 */
	public function GetSuggestGroupItems($iUserId, $sSearch, $iRequestLimit, $iSharedTenantId = null)
	{
		$mGroupItems = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetSuggestGroupItems(
			$iUserId, $sSearch, $iRequestLimit)))
		{
			$mGroupItems = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oItem = new CContactListItem();
				$oItem->InitByDbRowWithType('group', $oRow);
				
				$oContactItems = $this->GetContactItems($iUserId, EContactSortField::Frequency, ESortOrder::ASC, 0, 99, '', '', $oItem->Id, $iSharedTenantId);

				$aEmails = array();
				foreach ($oContactItems as $oContactItem)
				{
					$aEmails[] = $oContactItem->UseFriendlyName && 0 < strlen(trim($oContactItem->Name)) ? 
							'"'.trim($oContactItem->Name).'" <'.trim($oContactItem->Email).'>' : trim($oContactItem->Email);
				}
				
				$oItem->Email = implode(', ', $aEmails);

				if (!empty($oItem->Email))
				{
					$mGroupItems[] = $oItem;
				}
				
				unset($oItem);
			}
		}
		return $mGroupItems;
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
	 * @param CContact $oContact
	 * @param int $iUserId
	 * @return string
	 */
	public function UpdateContactUserId($oContact, $iUserId)
	{
		if ($this->oConnection->Execute($this->oCommandCreator->UpdateContactUserId($oContact, $iUserId)))
		{
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
			$oContact->IdContact = $this->oConnection->GetLastInsertId('awm_addr_book', 'id_addr');
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
			$oGroup->IdGroup = $this->oConnection->GetLastInsertId('awm_addr_groups', 'id_group');
			$bResult = $this->UpdateGroup($oGroup);
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @param int $iSharedTenantId = null
	 * @return bool
	 */
	public function DeleteContacts($iUserId, $aContactsIds, $iSharedTenantId = null)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->DeleteContacts($iUserId, $aContactsIds, $iSharedTenantId)))
		{
			$bResult = true;
			$this->oConnection->Execute($this->oCommandCreator->ClearGroupsIdsByContactsIds($aContactsIds));
		}

		return $bResult;
	}
	
	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
	public function DeleteSuggestContacts($iUserId, $aContactsIds)
	{
		return $this->DeleteContacts($iUserId, $aContactsIds);
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
	 * @param array $aEmails
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
//	public function DeleteContactsExceptIds($iUserId, $aContactIds)
//	{
//		$bResult = false;
//		if ($this->oConnection->Execute($this->oCommandCreator->DeleteContactsExceptIds($iUserId, $aContactIds)))
//		{
//			$bResult = true;
//			$this->oConnection->Execute($this->oCommandCreator->ClearGroupsIdsByExceptContactsIds($iUserId, $aContactIds));
//		}
//
//		return $bResult;
//	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupIds
	 * @return bool
	 */
//	public function DeleteGroupsExceptIds($iUserId, $aGroupIds)
//	{
//		$bResult = false;
//		if ($this->oConnection->Execute($this->oCommandCreator->DeleteGroupsExceptIds($iUserId, $aGroupIds)))
//		{
//			$bResult = true;
//			$this->oConnection->Execute($this->oCommandCreator->ClearContactsIdsByExceptGroupsIds($iUserId, $aGroupIds));
//		}
//
//		return $bResult;
//	}

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
			$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteUserGlobalSubContact($iUserId));
			$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteUserGlobalContact($iUserId));
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
			$this->oConnection->FreeResult();
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
			$this->oConnection->FreeResult();
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
			$this->oConnection->FreeResult();
		}
		return $aResult;
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
	
	
	/**
	 * @param int $iGroupId
	 * @return bool
	 */
	public function GetGroupEvents($iGroupId)
	{
		$aResult = array();
		if ($this->oConnection->Execute($this->oCommandCreator->GetGroupEvents($iGroupId)))
		{
			$aResult = $this->oConnection->GetResultAsAssocArrays();
			$this->oConnection->FreeResult();
		}		
		
		return $aResult;
	}
	
	/**
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function GetGroupEvent($sCalendarId, $sEventId)
	{
		$aResult = array();
		if ($this->oConnection->Execute($this->oCommandCreator->GetGroupEvent($sCalendarId, $sEventId)))
		{
			$aResult = $this->oConnection->GetResultAsAssocArrays();
			$this->oConnection->FreeResult();
		}		
		
		return $aResult;
	}

	/**
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function AddEventToGroup($iGroupId, $sCalendarId, $sEventId)
	{
		$iResult = $this->oConnection->Execute($this->oCommandCreator->AddEventToGroup($iGroupId, $sCalendarId, $sEventId));

		return (bool) $iResult;
	}	
	
	/**
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function RemoveEventFromGroup($iGroupId, $sCalendarId, $sEventId)
	{
		$iResult = $this->oConnection->Execute($this->oCommandCreator->RemoveEventFromGroup($iGroupId, $sCalendarId, $sEventId));

		return (bool) $iResult;
	}	
	
	/**
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function RemoveEventFromAllGroups($sCalendarId, $sEventId)
	{
		$iResult = $this->oConnection->Execute($this->oCommandCreator->RemoveEventFromAllGroups($sCalendarId, $sEventId));

		return (bool) $iResult;
	}		
}
