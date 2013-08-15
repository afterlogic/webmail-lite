<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Contacts
 */
class CApiContactsManager extends AApiManager
{
	/*
	 * @var $oApiContactsManager CApiMaincontactsManager
	 */
	private $oApiContactsManager;

	/*
	 * @var $oApiGContactsManager CApiGcontactsManager
	 */
	private $oApiGContactsManager;

	/*
	 * @var $oApiSabreContactsManager CApiMaincontactsManager
	 */
	private $oApiContactsManagerSabreDAV;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('contacts', $oManager, $sForcedStorage);

		$this->oApiGContactsManager = CApi::Manager('gcontacts');
		$this->oApiContactsManager = CApi::Manager('maincontacts');
		$this->oApiContactsManagerSabreDAV = CApi::Manager('maincontacts', 'sabredav');
	}

	/**
	 * @return CContactListItem
	 */
	public function NewContactListItemObject()
	{
		return new CContactListItem();
	}

	/**
	 * @return CContact
	 */
	public function NewContactObject()
	{
		return new CContact();
	}

	/**
	 * @return CGroup
	 */
	public function NewGroupObject()
	{
		return new CGroup();
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact | bool
	 */
	public function GetContactById($iUserId, $mContactId)
	{
		return $this->oApiContactsManager->GetContactById($iUserId, $mContactId);
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return CContact | bool
	 */
	public function GetContactByEmail($iUserId, $sEmail)
	{
		return $this->oApiContactsManager->GetContactByEmail($iUserId, $sEmail);
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @return CContact
	 */
	public function GetContactByStrId($iUserId, $sContactStrId)
	{
		return $this->oApiContactsManager->GetContactByStrId($iUserId, $sContactStrId);
	}

	/**
	 * @param CContact $oContact
	 * @return array | bool
	 */
	public function GetContactGroupsIds($oContact)
	{
		return $this->oApiContactsManager->GetContactGroupsIds($oContact);
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupId
	 * @return CGroup
	 */
	public function GetGroupById($iUserId, $mGroupId)
	{
		return $this->oApiContactsManager->GetGroupById($iUserId, $mGroupId);
	}

	/**
	 * @param int $iUserId
	 * @param string $sGroupStrId
	 * @return CGroup
	 */
	public function GetGroupByStrId($iUserId, $sGroupStrId)
	{
		return $this->oApiContactsManager->GetGroupByStrId($iUserId, $sGroupStrId);
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function UpdateContact($oContact)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->UpdateContact($oContact);
		$res2 = true;

		 // TODO sasha
//		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
//		{
//			$this->UpdateContactGroupsIdsWhithNames($oContact);
//			$res2 = $this->oApiContactsManagerSabreDAV->UpdateContact($oContact);
//		}
//		else
//		{
//			$res2 = true;
//		}

		return ($res1 && $res2);
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function UpdateGroup($oGroup)
	{
		$res1 = $res2 = false;

		$oGroupDb = $this->oApiContactsManager->GetGroupById($oGroup->IdUser, $oGroup->IdGroup);

		$res1 = $this->oApiContactsManager->UpdateGroup($oGroup);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts') && $oGroupDb)
		{
			$oGroup->IdGroup = $oGroupDb->Name;

			$oContactItems = $this->oApiContactsManager->GetContactItems($oGroup->IdUser, EContactSortField::EMail,
				ESortOrder::ASC, 0, 999, '', '', $oGroupDb->IdGroup);
			if (is_array($oContactItems))
			{
				foreach ($oContactItems as $oContactItem)
				{
					$oContact = $this->oApiContactsManagerSabreDAV->GetContactById($oGroup->IdUser, $oContactItem->IdStr);
					if ($oContact)
					{
						$aGroupsIds = array();
						foreach($oContact->GroupsIds as $iGroupId)
						{
							if ($iGroupId === $oGroup->IdGroup)
							{
								$iGroupId = $oGroup->Name;
							}
							$aGroupsIds[] = $iGroupId;
						}
						$oContact->GroupsIds = $aGroupsIds;
						$this->oApiContactsManagerSabreDAV->UpdateContact($oContact);
					}
				}
			}
			$res2 = true;
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch = ''
	 * @param string $sFirstCharacter = ''
	 * @param int $iGroupId = 0
	 * @return int
	 */
	public function GetContactItemsCount($iUserId, $sSearch = '', $sFirstCharacter = '', $iGroupId = 0)
	{
		return $this->oApiContactsManager->GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId);
	}

	/**
	 * @param int $iUserId
	 * @param int $iOffset = 0
	 * @param int $iRequestLimit = 20
	 * @return bool | array
	 */
	public function GetContactItemsWithoutOrder($iUserId, $iOffset = 0, $iRequestLimit = 20)
	{
		return $this->oApiContactsManager->GetContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit);
	}

	/**
	 * @param string $mUserId
	 * @param int $iSortField = EContactSortField::EMail,
	 * @param int $iSortOrder = ESortOrder::ASC,
	 * @param int $iOffset = 0
	 * @param int $iRequestLimit = 20
	 * @param string $sSearch = ''
	 * @param string $sFirstCharacter = ''
	 * @param string $mGroupId = ''
	 * @return bool | array
	 */
	public function GetContactItems($mUserId,
		$iSortField = EContactSortField::EMail, $iSortOrder = ESortOrder::ASC,
		$iOffset = 0, $iRequestLimit = 20, $sSearch = '', $sFirstCharacter = '', $mGroupId = '')
	{
		return $this->oApiContactsManager->GetContactItems($mUserId, $iSortField, $iSortOrder,
			$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $mGroupId);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch = ''
	 * @param string $sFirstCharacter = ''
	 * @return int
	 */
	public function GetGroupItemsCount($iUserId, $sSearch = '', $sFirstCharacter = '')
	{
		return $this->oApiContactsManager->GetGroupItemsCount($iUserId, $sSearch, $sFirstCharacter);
	}

	/**
	 * @param int $iUserId
	 * @param int $iSortField = EContactSortField::Name,
	 * @param int $iSortOrder = ESortOrder::ASC,
	 * @param int $iOffset = 0
	 * @param int $iRequestLimit = 20
	 * @param string $sSearch = ''
	 * @param string $sFirstCharacter = ''
	 * @param int $iContactId = 0
	 * @return bool | array
	 */
	public function GetGroupItems($iUserId,
		$iSortField = EContactSortField::Name, $iSortOrder = ESortOrder::ASC,
		$iOffset = 0, $iRequestLimit = 20, $sSearch = '', $sFirstCharacter = '', $iContactId = 0)
	{
		return $this->oApiContactsManager->GetGroupItems($iUserId, $iSortField, $iSortOrder,
			$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iContactId);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sSearch = ''
	 * @param int $iRequestLimit = 20
	 *
	 * @return bool | array
	 */
	public function GetSuggestItems($oAccount, $sSearch = '', $iRequestLimit = 20)
	{
		return $this->oApiContactsManager->GetSuggestItems($oAccount, $sSearch, $iRequestLimit);
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function CreateContact($oContact)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->CreateContact($oContact);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$this->UpdateContactGroupsIdsWhithNames($oContact);
			$res2 = $this->oApiContactsManagerSabreDAV->CreateContact($oContact);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function CreateGroup($oGroup)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->CreateGroup($oGroup);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->CreateGroup($oGroup);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
	public function DeleteContacts($iUserId, $aContactsIds)
	{
		$aContactsStrIds = array();
		foreach ($aContactsIds as $iContactsId)
		{
			$oContact = $this->oApiContactsManager->GetContactById($iUserId, $iContactsId);
			$aContactsStrIds[] = $oContact->IdContactStr;
		}

		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->DeleteContacts($iUserId, $aContactsIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->DeleteContacts($iUserId, $aContactsStrIds);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupsIds
	 * @return bool
	 */
	public function DeleteGroups($iUserId, $aGroupsIds)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->DeleteGroups($iUserId, $aGroupsIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->DeleteGroups($iUserId, $aGroupsIds);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupId
	 * @return bool
	 */
	public function DeleteGroup($iUserId, $mGroupId)
	{
		return $this->oApiContactsManager->DeleteGroup($iUserId, $mGroupId);
	}

	/**
	 * @param int $iUserId
	 * @param array $aEmails
	 * @return bool
	 */
	function UpdateSuggestTable($iUserId, $aEmails)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->UpdateSuggestTable($iUserId, $aEmails);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->UpdateSuggestTable($iUserId, $aEmails);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSyncType
	 * @param string $sTempFileName
	 * @return int | false
	 */
	public function Import($iUserId, $sSyncType, $sTempFileName)
	{
		$iParsedCount = 0;
		return $this->ImportEx($iUserId, $sSyncType, $sTempFileName, $iParsedCount);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSyncType
	 * @param string $sTempFileName
	 * @param int $iParsedCount
	 * @return int | false
	 */
	public function ImportEx($iUserId, $sSyncType, $sTempFileName, &$iParsedCount)
	{
		$this->inc('helpers.'.$sSyncType.'.formatter');
		$this->inc('helpers.'.$sSyncType.'.parser');
		$this->inc('helpers.sync.'.$sSyncType);

		$sSyncClass = 'CApi'.ucfirst($this->GetManagerName()).'Sync'.ucfirst($sSyncType);
		if (class_exists($sSyncClass))
		{
			$oSync = new $sSyncClass($this);
			return $oSync->Import($iUserId, $sTempFileName, $iParsedCount);
		}

		return false;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSyncType
	 * @return string | bool
	 */
	public function Export($iUserId, $sSyncType)
	{
		$this->inc('helpers.'.$sSyncType.'.formatter');
		$this->inc('helpers.'.$sSyncType.'.parser');
		$this->inc('helpers.sync.'.$sSyncType);

		$sSyncClass = 'CApi'.ucfirst($this->GetManagerName()).'Sync'.ucfirst($sSyncType);
		if (class_exists($sSyncClass))
		{
			$oSync = new $sSyncClass($this);
			return $oSync->Export($iUserId);
		}

		return false;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
	public function DeleteContactsExceptIds($iUserId, $aContactsIds)
	{
		$aContactsStrIds = array();
		foreach ($aContactsIds as $iContactsId)
		{
			$oContact = $this->oApiContactsManager->GetContactById($iUserId, $iContactsId);
			$aContactsStrIds[] = $oContact->IdContactStr;
		}

		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->DeleteContactsExceptIds($iUserId, $aContactsIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->DeleteContactsExceptIds($iUserId, $aContactsStrIds);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupIds
	 * @return bool
	 */
	public function DeleteGroupsExceptIds($iUserId, $aGroupIds)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->DeleteGroupsExceptIds($iUserId, $aGroupIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->DeleteGroupsExceptIds($iUserId, $aGroupIds);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function ClearAllContactsAndGroups($oAccount)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->ClearAllContactsAndGroups($oAccount);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->ClearAllContactsAndGroups($oAccount);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @return bool
	 */
	public function FlushContacts()
	{
		return $this->oApiContactsManager->FlushContacts();
	}

	public function UpdateContactGroupsIdsWhithNames(&$oContact)
	{
		$aResult = array();

		foreach ($oContact->GroupsIds as $mGroupId)
		{
			$oGroup = $this->oApiContactsManager->GetGroupById($oContact->IdUser, $mGroupId);
			if ($oGroup)
			{
				$aResult[] = $oGroup->Name;
			}
		}
		
		$oContact->GroupsIds = $aResult;
	}

	/**
	 * @deprecated
	 * @param CContact $oContact
	 * @param array $aGroupIds
	 * @return bool
	 */
	public function AddContactToGroups($oContact, $aGroupIds)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->AddContactToGroups($oContact, $aGroupIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->AddContactToGroups($oContact, $aGroupIds);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return bool
	 */
	public function AddContactsToGroup($oGroup, $aContactIds)
	{
		$res1 = $res2 = false;

		$aContactsStrIds = array();
		foreach ($aContactIds as $iContactId)
		{
			$oContact = $this->oApiContactsManager->GetContactById($oGroup->IdUser, $iContactId);
			if ($oContact)
			{
				$aContactsStrIds[] = $oContact->IdContactStr;
			}
		}
		
		$res1 = $this->oApiContactsManager->AddContactsToGroup($oGroup, $aContactIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->AddContactsToGroup($oGroup, $aContactsStrIds);
		}
		else
		{
			$res2 = true;
		}
		
		return ($res1 && $res2);
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return bool
	 */
	public function AddGlobalContactsToGroup($oAccount, $oGroup, $aContactIds)
	{
		$oGlobalApiManager = CApi::Manager('gcontacts');
		if ($oGlobalApiManager)
		{
			$aNewContactIds = array();

			foreach ($aContactIds as $mId)
			{
				$mContactId = $this->oApiContactsManager->ConvertedContactLocalId($oAccount, $mId, EContactType::Global_);
				if (!$mContactId)
				{
					$oGlobalContact = $oGlobalApiManager->GetContactById($oAccount, $mId);
					if ($oGlobalContact)
					{
						$oGlobalContact->Type = EContactType::Global_;
						$oGlobalContact->IdTypeLink = $mId;

						$bResult = $this->CreateContact($oGlobalContact);
						if ($bResult)
						{
							$aNewContactIds[] = $oGlobalContact->IdContact;
						}
					}
				}
				else
				{
					$aNewContactIds[] = $mContactId;
				}
			}

			if (0 < count($aNewContactIds))
			{
				return $this->AddContactsToGroup($oGroup, $aNewContactIds);
			}
		}
	}

	/**
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return bool
	 */
	public function RemoveContactsFromGroup($oGroup, $aContactIds)
	{
		$res1 = $res2 = false;

		$aContactsStrIds = array();
		foreach ($aContactIds as $iContactId)
		{
			$oContact = $this->oApiContactsManager->GetContactById($oGroup->IdUser, $iContactId);
			$aContactsStrIds[] = $oContact->IdContactStr;
		}

		$res1 = $this->oApiContactsManager->RemoveContactsFromGroup($oGroup, $aContactIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->RemoveContactsFromGroup($oGroup, $aContactsStrIds);
		}
		else
		{
			$res2 = true;
		}
		return ($res1 && $res2);
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function SynchronizeExternalContacts($oAccount)
	{
		$aIds = $this->oApiContactsManager->ConvertedContactLocalIdCollection($oAccount, EContactType::Global_);
		if ($aIds && is_array($aIds) && 0 < count($aIds) && $this->oApiGContactsManager)
		{
			CApi::Log('SynchronizeExternalContacts: '.count($aIds));

			$aLinkedContact = $this->oApiContactsManager->ContactIdsLinkedToGroups(array_keys($aIds));
			$aLinkedContact = is_array($aLinkedContact) ? $aLinkedContact : array();

			$aContactToDelete = array();
			foreach ($aIds as $iLocalContactId => $sGlobalId)
			{
				if (in_array($iLocalContactId, $aLinkedContact))
				{
					$oGlobalContact = $this->oApiGContactsManager->GetContactById($oAccount, $sGlobalId);
					if ($oGlobalContact)
					{
						$oLocalGlobalContact = $this->oApiContactsManager->GetContactById($oAccount->IdUser, $iLocalContactId);
						if ($oLocalGlobalContact && EContactType::Global_ === $oLocalGlobalContact->Type)
						{
							if ($oLocalGlobalContact->CompareAndComputedByNewGlobalContact($oGlobalContact))
							{
								$this->UpdateContact($oLocalGlobalContact);
							}
						}
					}
					else
					{
						$aContactToDelete[] = $iLocalContactId;
					}
				}
				else
				{
					$aContactToDelete[] = $iLocalContactId;
				}
			}
			
			if (0 < count($aContactToDelete))
			{
				$this->DeleteContacts($oAccount->IdUser, $aContactToDelete);
			}

			return true;
		}
		else
		{
			CApi::Log('SynchronizeExternalContacts: none');
		}

		return true;
	}

	/**
	 * @deprecated
	 * @param mixed $mContactId
	 * @param mixed $aGroupIds
	 * @return bool
	 */
	public function DeleteContactFromGroups($mContactId, $aGroupIds)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->DeleteContactFromGroups($mContactId, $aGroupIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerSabreDAV->DeleteContactFromGroups($mContactId, $aGroupIds);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

}
