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
class CApiContactsManager extends AApiManager
{
	/*
	 * @var $oApiContactsManager CApiContactsmainManager
	 */
	private $oApiContactsManager;

	/*
	 * @var $oApiGContactsManager CApiGcontactsManager
	 */
	private $oApiGContactsManager;

	/*
	 * @var $oApiContactsManagerDAV CApiContactsmainManager
	 */
	private $oApiContactsManagerDAV;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('contacts', $oManager, $sForcedStorage);

		$this->oApiGContactsManager = CApi::Manager('gcontacts');
		$this->oApiContactsManager = CApi::Manager('contactsmain');
		$this->oApiContactsManagerDAV = CApi::Manager('contactsmain', 'sabredav');
//		$this->oApiContactsManagerDAV = CApi::Manager('contactsmain', 'carddav');
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
	 * @param bool $bIgnoreHideInGab = false
	 * @param int $iSharedTenantId = null
	 * @return CContact | bool
	 */
	public function GetContactById($iUserId, $mContactId, $bIgnoreHideInGab = false, $iSharedTenantId = null)
	{
		return $this->oApiContactsManager->GetContactById($iUserId, $mContactId, $bIgnoreHideInGab, $iSharedTenantId);
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
	 * @param int $iSharedTenantId = null
	 * @return CContact
	 */
	public function GetContactByStrId($iUserId, $sContactStrId, $iSharedTenantId = null)
	{
		return $this->oApiContactsManager->GetContactByStrId($iUserId, $sContactStrId, $iSharedTenantId);
	}

	/**
	 * @param int $iUserId
	 * @param int $iSharedTenantId = null
	 * @return array
	 */
	public function GetSharedContactIds($iUserId, $iSharedTenantId = null)
	{
		return $this->oApiContactsManager->GetSharedContactIds($iUserId, $iSharedTenantId);
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
	 * @param int $iUserId
	 * @param string $sName
	 * @return CGroup
	 */
	public function GetGroupByName($iUserId, $sName)
	{
		return $this->oApiContactsManager->GetGroupByName($iUserId, $sName);
	}	

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function UpdateContact($oContact, $bUpdateFromGlobal = true)
	{
		$res1 = $res2 = false;

		if ($oContact)
		{
			if ($oContact->Type === EContactType::Personal)
			{
				$res1 = $this->oApiContactsManager->UpdateContact($oContact);
			}
			else if ($oContact->Type === EContactType::Global_)
			{
				$res1 = $this->oApiContactsManager->UpdateContact($oContact);
				
				if ($res1 && $bUpdateFromGlobal)
				{
					$oGlobalContact = $this->oApiContactsManager->GetMyGlobalContact($oContact->IdUser);
					if ($oGlobalContact)
					{
						if ($oGlobalContact->CompareAndComputedByNewGlobalContact($oContact))
						{
							$res1 = $this->oApiGContactsManager->UpdateContact($oGlobalContact);
						}
						else
						{
							$res1 = true;
						}
					}
				}
			}

			if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
			{
				$this->UpdateContactGroupsIdsWhithNames($oContact);
				$res2 = $this->oApiContactsManagerDAV->UpdateContact($oContact);
			}
			else
			{
				$res2 = true;
			}
		}

		return ($res1 && $res2);
	}
	
	/**
	 * @param CContact $oContact
	 * @param int $iUserId
	 * @return string
	 */
	public function UpdateContactUserId($oContact, $iUserId)
	{
		$res1 = $res2 = false;
		if ($oContact)
		{
			if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
			{
				$res2 = $this->oApiContactsManagerDAV->UpdateContactUserId($oContact, $iUserId);
			}
			else
			{
				$res2 = true;
			}
			$res1 = $this->oApiContactsManager->UpdateContactUserId($oContact, $iUserId);
		}

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
					$oContact = $this->oApiContactsManagerDAV->GetContactById($oGroup->IdUser, $oContactItem->IdStr);
					if ($oContact)
					{
						$aGroupsIds = array();
						foreach($oContact->GroupsIds as $sGroupId)
						{
							$sGroupId = (string) $sGroupId;
							if ($sGroupId === (string) $oGroup->IdGroup)
							{
								$sGroupId = $oGroup->Name;
							}
							
							$aGroupsIds[] = $sGroupId;
						}
						$oContact->GroupsIds = $aGroupsIds;
						$this->oApiContactsManagerDAV->UpdateContact($oContact);
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
	 * @param int $iTenantId = null
	 * @param bool $bAll = false
	 * @return int
	 */
	public function GetContactItemsCount($iUserId, $sSearch = '', $sFirstCharacter = '', $iGroupId = 0, $iTenantId = null, $bAll = false)
	{
		return $this->oApiContactsManager->GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId, $iTenantId, $bAll);
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
	 * @param int $iTenantId = null
	 * @param bool $bAll = false
	 * @return bool | array
	 */
	public function GetContactItems($mUserId,
		$iSortField = EContactSortField::EMail, $iSortOrder = ESortOrder::ASC,
		$iOffset = 0, $iRequestLimit = 20, $sSearch = '', $sFirstCharacter = '', $mGroupId = '', $iTenantId = null, $bAll = false)
	{
		return $this->oApiContactsManager->GetContactItems($mUserId, $iSortField, $iSortOrder,
			$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $mGroupId, $iTenantId, $bAll);
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
	 * @param bool $bGlobalOnly = false
	 * @param bool $bPhoneOnly = false
	 * @param int $iSharedTenantId = null
	 *
	 * @return bool | array
	 */
	public function GetSuggestItems($oAccount, $sSearch = '', $iRequestLimit = 20, $bGlobalOnly = false, $bPhoneOnly = false, $iSharedTenantId = null)
	{
		return $this->oApiContactsManager->GetSuggestItems($oAccount, $sSearch, $iRequestLimit, $bGlobalOnly, $bPhoneOnly, $iSharedTenantId);
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
			$res2 = $this->oApiContactsManagerDAV->CreateContact($oContact);
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
			$res2 = $this->oApiContactsManagerDAV->CreateGroup($oGroup);
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
	 * @param int $iTenantId = null
	 * @return bool
	 */
	public function DeleteContacts($iUserId, $aContactsIds, $iTenantId = null)
	{
		$aContactsStrIds = array();
		foreach ($aContactsIds as $iContactsId)
		{
			$oContact = $this->oApiContactsManager->GetContactById($iUserId, $iContactsId, $iTenantId);
			if ($oContact)
			{
				$aContactsStrIds[] = $oContact->IdContactStr;
			}
		}

		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->DeleteContacts($iUserId, $aContactsIds, $iTenantId);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts') && is_null($iTenantId))
		{
			$res2 = $this->oApiContactsManagerDAV->DeleteContacts($iUserId, $aContactsStrIds);
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
	public function DeleteSuggestContacts($iUserId, $aContactsIds)
	{
		$aContactsStrIds = array();
		foreach ($aContactsIds as $iContactsId)
		{
			$oContact = $this->oApiContactsManager->GetContactById($iUserId, $iContactsId);
			if ($oContact)
			{
				$aContactsStrIds[] = $oContact->IdContactStr;
			}
		}

		$res1 = $res2 = false;

		$res1 = $this->oApiContactsManager->DeleteSuggestContacts($iUserId, $aContactsIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerDAV->DeleteSuggestContacts($iUserId, $aContactsStrIds);
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
			$res2 = $this->oApiContactsManagerDAV->DeleteGroups($iUserId, $aGroupsIds);
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
		return $this->oApiContactsManager->UpdateSuggestTable($iUserId, $aEmails);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSyncType
	 * @param string $sTempFileName
	 * @param int $iParsedCount
	 * @param int $iGroupId
	 * @param bool $bIsShared
	 * @return int | false
	 */
	public function Import($iUserId, $sSyncType, $sTempFileName, &$iParsedCount, $iGroupId, $bIsShared)
	{
		$oApiUsersManager = CApi::Manager('users');
		$oAccount = $oApiUsersManager->GetDefaultAccount($iUserId);

		if ($sSyncType === \EContactFileType::CSV)
		{
			$this->inc('helpers.'.$sSyncType.'.formatter');
			$this->inc('helpers.'.$sSyncType.'.parser');
			$this->inc('helpers.sync.'.$sSyncType);

			$sSyncClass = 'CApi'.ucfirst($this->GetManagerName()).'Sync'.ucfirst($sSyncType);
			if (class_exists($sSyncClass))
			{
				$oSync = new $sSyncClass($this);
				return $oSync->Import($iUserId, $sTempFileName, $iParsedCount, $iGroupId, $bIsShared);
			}
		}
		else if ($sSyncType === \EContactFileType::VCF)
		{
			// You can either pass a readable stream, or a string.
			$oHandler = fopen($sTempFileName, 'r');
			$oSplitter = new \Sabre\VObject\Splitter\VCard($oHandler);
			while($oVCard = $oSplitter->getNext())
			{
				$oContact = new \CContact();

				$oContact->InitFromVCardObject($iUserId, $oVCard);

				if ($oAccount)
				{
					$oContact->IdDomain = $oAccount->IdDomain;
					$oContact->IdTenant = $oAccount->IdTenant;
				}
				$oContact->SharedToAll = $bIsShared;
				$oContact->GroupsIds = array($iGroupId);

				if ($this->CreateContact($oContact))
				{
					$iParsedCount++;
				}
			}
			return $iParsedCount;
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
		if ($sSyncType === \EContactFileType::CSV)
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
		}
		else if ($sSyncType === \EContactFileType::VCF)
		{
            $sOutput = '';
			$aContactItems = $this->oApiContactsManagerDAV->GetContactItemObjects($iUserId);
			if (is_array($aContactItems))
			{
				foreach ($aContactItems as $oContactItem)
				{
					$sOutput .= \Sabre\VObject\Reader::read($oContactItem->get())->serialize();
				}
			}
			return $sOutput;            
		}
		
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
//	public function DeleteContactsExceptIds($iUserId, $aContactsIds)
//	{
//		$aContactsStrIds = array();
//		foreach ($aContactsIds as $iContactsId)
//		{
//			$oContact = $this->oApiContactsManager->GetContactById($iUserId, $iContactsId);
//			if ($oContact)
//			{
//				$aContactsStrIds[] = $oContact->IdContactStr;
//			}
//		}
//
//		$res1 = $res2 = false;
//
//		$res1 = $this->oApiContactsManager->DeleteContactsExceptIds($iUserId, $aContactsIds);
//		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
//		{
//			$res2 = $this->oApiContactsManagerSabreDAV->DeleteContactsExceptIds($iUserId, $aContactsStrIds);
//		}
//		else
//		{
//			$res2 = true;
//		}
//
//		return ($res1 && $res2);
//	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupIds
	 * @return bool
	 */
//	public function DeleteGroupsExceptIds($iUserId, $aGroupIds)
//	{
//		$res1 = $res2 = false;
//
//		$res1 = $this->oApiContactsManager->DeleteGroupsExceptIds($iUserId, $aGroupIds);
//		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
//		{
//			$res2 = $this->oApiContactsManagerSabreDAV->DeleteGroupsExceptIds($iUserId, $aGroupIds);
//		}
//		else
//		{
//			$res2 = true;
//		}
//
//		return ($res1 && $res2);
//	}

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
			$res2 = $this->oApiContactsManagerDAV->ClearAllContactsAndGroups($oAccount);
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
				$aResult[] = (string) $oGroup->Name;
			}
		}
		
		$oContact->GroupsIds = $aResult;
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
			$res2 = $this->oApiContactsManagerDAV->AddContactsToGroup($oGroup, $aContactsStrIds);
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
		if ($this->oApiGContactsManager)
		{
			$aNewContactIds = array();

			foreach ($aContactIds as $mId)
			{
				$mContactId = $this->oApiContactsManager->ConvertedContactLocalId($oAccount, $mId, EContactType::Global_);
				if (!$mContactId)
				{
					$oGlobalContact = $this->oApiGContactsManager->GetContactById($oAccount, $mId);

					/* @var $oGlobalContact CContact */
					if ($oGlobalContact)
					{
						$oGlobalContact->IdUser = $oAccount->IdUser;
						$oGlobalContact->IdDomain = $oAccount->IdDomain;
						$oGlobalContact->IdTenant = $oAccount->IdTenant;
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
			if ($oContact)
			{
				$aContactsStrIds[] = $oContact->IdContactStr;
			}
		}

		$res1 = $this->oApiContactsManager->RemoveContactsFromGroup($oGroup, $aContactIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsManagerDAV->RemoveContactsFromGroup($oGroup, $aContactsStrIds);
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
	public function SynchronizeExternalContacts($oAccount, $bSelfOnly = false)
	{
		$aIds = array();
		if ($this->oApiGContactsManager)
		{
			if ($bSelfOnly)
			{
				$oGlobalContact = $this->oApiGContactsManager->GetContactByTypeId($oAccount, $oAccount->IdUser, true);

				/* @var $oGlobalContact CContact */
				if ($oGlobalContact)
				{
					$mContactId = $this->oApiContactsManager->ConvertedContactLocalId($oAccount,
						$oGlobalContact->IdContact, EContactType::Global_);

					if ($mContactId)
					{
						$aIds[$mContactId] = $oGlobalContact->IdContact;
					}
				}
			}
			else
			{
				$aIds = $this->oApiContactsManager->ConvertedContactLocalIdCollection($oAccount, EContactType::Global_);
			}

			if ($aIds && is_array($aIds) && 0 < count($aIds))
			{
				CApi::Log('SynchronizeExternalContacts: '.count($aIds));

				$aLinkedContact = $this->oApiContactsManager->ContactIdsLinkedToGroups(array_keys($aIds));
				$aLinkedContact = is_array($aLinkedContact) ? $aLinkedContact : array();

				$aContactToDelete = array();
				foreach ($aIds as $iLocalContactId => $sGlobalId)
				{
					if (in_array($iLocalContactId, $aLinkedContact))
					{
						$oGlobalContact = $this->oApiGContactsManager->GetContactById($oAccount, $sGlobalId, true);
						if ($oGlobalContact)
						{
							$oLocalGlobalContact = $this->oApiContactsManager->GetContactById($oAccount->IdUser, $iLocalContactId, true);
							if ($oLocalGlobalContact && EContactType::Global_ === $oLocalGlobalContact->Type)
							{
								if ($oLocalGlobalContact->CompareAndComputedByNewGlobalContact($oGlobalContact))
								{
									$this->UpdateContact($oLocalGlobalContact, false);
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
		}

		return true;
	}
	
	
	/**
	 * @param int $iUserId
	 * @param int $iGroupId
	 * @return bool
	 */
	public function GetGroupEvents($iUserId, $iGroupId)
	{
		$aResult = array();
		$aEvents = $this->oApiContactsManager->GetGroupEvents($iGroupId);
		if (is_array($aEvents) && 0 < count($aEvents))
		{
			$oApiUsersManager = CApi::Manager('users');
			$iAccountId =  $oApiUsersManager->GetDefaultAccountId($iUserId);
			$oAccount = $oApiUsersManager->GetAccountById($iAccountId);
			
			if ($oAccount)
			{
				$oApiCalendarManager = CApi::Manager('calendar');

				foreach ($aEvents as $aEvent)
				{
					$aResult[] = $oApiCalendarManager->GetBaseEvent($oAccount, $aEvent['id_calendar'], $aEvent['id_event']);
				}
			}
		}
		
		return $aResult;
	}	
	
	/**
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function GetGroupEvent($sCalendarId, $sEventId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oApiContactsManager->GetGroupEvent($sCalendarId, $sEventId);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}	
	
	/**
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function IsGroupEventExists($iGroupId, $sCalendarId, $sEventId)
	{
		$aResult = false;
		try
		{
			$aEvent = $this->GetGroupEvent($iGroupId, $sCalendarId, $sEventId);
			if (is_array($aEvent) && 0 < count($aEvent))
			{
				$aResult = true;
			}
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
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
		return $this->oApiContactsManager->AddEventToGroup($iGroupId, $sCalendarId, $sEventId);
	}
	
	/**
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function RemoveEventFromGroup($iGroupId, $sCalendarId, $sEventId)
	{
		return $this->oApiContactsManager->RemoveEventFromGroup($iGroupId, $sCalendarId, $sEventId);
	}
	
	/**
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function RemoveEventFromAllGroups($sCalendarId, $sEventId)
	{
		return $this->oApiContactsManager->RemoveEventFromAllGroups($sCalendarId, $sEventId);
	}	
	
	
}
