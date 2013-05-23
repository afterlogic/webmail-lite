<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Contacts
 */
class CApiContactsManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('contacts', $oManager, $sForcedStorage);

		$this->inc('classes.enum');
		$this->inc('classes.contact-list-item');
		$this->inc('classes.contact');
		$this->inc('classes.group');

		if (CApi::Manager('dav'))
		{
			$this->inc('classes.vcard-helper');
		}
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
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->GetContactById($iUserId, $mContactId);
			if ($oContact)
			{
				$mGroupsIds = $this->GetContactGroupsIds($oContact);
				if (is_array($mGroupsIds))
				{
					$oContact->GroupsIds = $mGroupsIds;
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$oContact = false;
			$this->setLastException($oException);
		}

		return $oContact;
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return CContact | bool
	 */
	public function GetContactByEmail($iUserId, $sEmail)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->GetContactByEmail($iUserId, $sEmail);
			if ($oContact)
			{
				$mGroupsIds = $this->GetContactGroupsIds($oContact);
				if (is_array($mGroupsIds))
				{
					$oContact->GroupsIds = $mGroupsIds;
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$oContact = false;
			$this->setLastException($oException);
		}

		return $oContact;
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @return CContact
	 */
	public function GetContactByStrId($iUserId, $sContactStrId)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->GetContactByStrId($iUserId, $sContactStrId);
			if ($oContact)
			{
				$mGroupsIds = $this->GetContactGroupsIds($oContact);
				if (is_array($mGroupsIds))
				{
					$oContact->GroupsIds = $mGroupsIds;
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$oContact = false;
			$this->setLastException($oException);
		}

		return $oContact;
	}

	/**
	 * @param CContact $oContact
	 * @return array | bool
	 */
	public function GetContactGroupsIds($oContact)
	{
		$aGroupsIds = false;
		try
		{
			$aGroupsIds = $this->oStorage->GetContactGroupsIds($oContact);
		}
		catch (CApiBaseException $oException)
		{
			$aGroupsIds = false;
			$this->setLastException($oException);
		}

		return $aGroupsIds;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupId
	 * @return CGroup
	 */
	public function GetGroupById($iUserId, $mGroupId)
	{
		$oGroup = null;
		try
		{
			$oGroup = $this->oStorage->GetGroupById($iUserId, $mGroupId);
		}
		catch (CApiBaseException $oException)
		{
			$oGroup = false;
			$this->setLastException($oException);
		}

		return $oGroup;
	}

	/**
	 * @param int $iUserId
	 * @param string $sGroupStrId
	 * @return CGroup
	 */
	public function GetGroupByStrId($iUserId, $sGroupStrId)
	{
		$oGroup = null;
		try
		{
			$oGroup = $this->oStorage->GetGroupByStrId($iUserId, $sGroupStrId);
		}
		catch (CApiBaseException $oException)
		{
			$oGroup = false;
			$this->setLastException($oException);
		}

		return $oGroup;
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function UpdateContact($oContact)
	{
		$bResult = false;
		try
		{
			if ($oContact->Validate())
			{
				$bResult = $this->oStorage->UpdateContact($oContact);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function UpdateGroup($oGroup)
	{
		$bResult = false;
		try
		{
			if ($oGroup->Validate())
			{
				$bResult = $this->oStorage->UpdateGroup($oGroup);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
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
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId);
		}
		catch (CApiBaseException $oException)
		{
			$iResult = 0;
			$this->setLastException($oException);
		}

		return $iResult;
	}

	/**
	 * @param int $iUserId
	 * @param int $iOffset = 0
	 * @param int $iRequestLimit = 20
	 * @return bool | array
	 */
	public function GetContactItemsWithoutOrder($iUserId, $iOffset = 0, $iRequestLimit = 20)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit);
		}
		catch (CApiBaseException $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}

		return $mResult;
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
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetContactItems($mUserId, $iSortField, $iSortOrder,
				$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $mGroupId);
		}
		catch (CApiBaseException $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}

		return $mResult;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch = ''
	 * @param string $sFirstCharacter = ''
	 * @return int
	 */
	public function GetGroupItemsCount($iUserId, $sSearch = '', $sFirstCharacter = '')
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetGroupItemsCount($iUserId, $sSearch, $sFirstCharacter);
		}
		catch (CApiBaseException $oException)
		{
			$iResult = 0;
			$this->setLastException($oException);
		}

		return $iResult;
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
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetGroupItems($iUserId, $iSortField, $iSortOrder,
				$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iContactId);
		}
		catch (CApiBaseException $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}

		return $mResult;
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
		$mResult = false;
		try
		{
			$mResult = array();

			if ($oAccount->User->GetCapa('PAB'))
			{
				$aContactItems = $this->oStorage->GetSuggestContactItems($oAccount->IdUser, $sSearch, $iRequestLimit);
				if (is_array($aContactItems))
				{
					$mResult = $aContactItems;
				}
			}

			if ($iRequestLimit > count($mResult) && $oAccount->User->GetCapa('GAB'))
			{
				$oApiCollaborationManager = /* @var $oApiCollaborationManager CApiCollaborationManager */ CApi::Manager('collaboration');
				if ($oApiCollaborationManager && $oApiCollaborationManager->IsContactsGlobalSuggestSupported())
				{
					$oApiGcontactManager = $oApiCollaborationManager->GetGlobalContactsManager();
					if ($oApiGcontactManager)
					{
						$aAccountItems = $oApiGcontactManager->GetContactItems($oAccount,
							EContactSortField::EMail, ESortOrder::ASC, 0, $iRequestLimit, $sSearch);

						if (is_array($aAccountItems))
						{
							$mResult = array_merge($mResult, $aAccountItems);
						}
						else
						{
							$oException = $oApiGcontactManager->GetLastException();
							if ($oException)
							{
								throw $oException;
							}
						}
					}
				}
			}

			if (is_array($mResult) && 1 < count($mResult))
			{
				$aTemp = array();
				foreach ($mResult as /* @var $oItem CContactListItem */ $oItem)
				{
					$sName = trim($oItem->ToString());
					if (!isset($aTemp[$sName]))
					{
						$aTemp[$sName] = $oItem;
					}
				}

				$mResult = array_values($aTemp);
			}

			if (is_array($mResult) && $iRequestLimit < count($mResult))
			{
				array_splice($mResult, $iRequestLimit);
			}
		}
		catch (CApiBaseException $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}

		return $mResult;
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function CreateContact($oContact)
	{
		$bResult = false;
		try
		{
			if ($oContact->Validate())
			{
				$bResult = $this->oStorage->CreateContact($oContact);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
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
		try
		{
			if ($oGroup->Validate())
			{
				$bResult = $this->oStorage->CreateGroup($oGroup);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
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
		try
		{
			$bResult = $this->oStorage->DeleteContacts($iUserId, $aContactsIds);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
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
		try
		{
			$bResult = $this->oStorage->DeleteGroups($iUserId, $aGroupsIds);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param array $aEmails
	 * @return bool
	 */
	function UpdateSuggestTable($iUserId, $aEmails)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->UpdateSuggestTable($iUserId, $aEmails);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
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
	 * @param array $aContactIds
	 * @return bool
	 */
	public function DeleteContactsExceptIds($iUserId, $aContactIds)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteContactsExceptIds($iUserId, $aContactIds);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
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
		try
		{
			$bResult = $this->oStorage->DeleteGroupsExceptIds($iUserId, $aGroupIds);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function ClearAllContactsAndGroups($oAccount)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->ClearAllContactsAndGroups($oAccount);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function FlushContacts()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->FlushContacts();
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

}
