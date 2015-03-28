<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Contactsmain
 */
class CApiContactsMainManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('contactsmain', $oManager, $sForcedStorage);

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
	 * @param bool $bIgnoreHideInGab = false
	 * @param int $iSharedTenantId = null
	 * @return CContact|bool
	 */
	public function GetContactById($iUserId, $mContactId, $bIgnoreHideInGab = false, $iSharedTenantId = null)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->GetContactById($iUserId, $mContactId, $bIgnoreHideInGab, $iSharedTenantId);
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
	 * @param mixed $mTypeId
	 * @param int $iContactType
	 * @return CContact | bool
	 */
	public function GetContactByTypeId($mTypeId, $iContactType)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->GetContactByTypeId($mTypeId, $iContactType);
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
	 * @param int $iSharedTenantId = null
	 * @return CContact
	 */
	public function GetContactByStrId($iUserId, $sContactStrId, $iSharedTenantId = null)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->GetContactByStrId($iUserId, $sContactStrId, $iSharedTenantId);
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
	 * @param int $iSharedTenantId = null
	 * @return array
	 */
	public function GetSharedContactIds($iUserId, $iSharedTenantId = null)
	{
		$aContactIds = array();
		try
		{
			$aContactIds = $this->oStorage->GetSharedContactIds($iUserId, $iSharedTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$aContactIds = false;
			$this->setLastException($oException);
		}

		return $aContactIds;
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
	 * @param int $iUserId
	 * @param string $sName
	 * @return CGroup
	 */
	public function GetGroupByName($iUserId, $sName)
	{
		$oGroup = null;
		try
		{
			$oGroup = $this->oStorage->GetGroupByName($iUserId, $sName);
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

		if ($bResult)
		{
			$oApiVoiceManager = /* @var $oApiVoiceManager \CApiVoiceManager */ CApi::Manager('voice');
			if ($oApiVoiceManager)
			{
				$oApiVoiceManager->FlushCallersNumbersCache($oContact->IdUser);
			}
		}

		return $bResult;
	}
	
	/**
	 * @param CContact $oContact
	 * @param int $iUserId
	 * @return string
	 */
	public function UpdateContactUserId($oContact, $iUserId)
	{
		$bResult = false;
		try
		{
			if ($oContact->Validate())
			{
				$bResult = $this->oStorage->UpdateContactUserId($oContact, $iUserId);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		if ($bResult)
		{
			$oApiVoiceManager = /* @var $oApiVoiceManager \CApiVoiceManager */ CApi::Manager('voice');
			if ($oApiVoiceManager)
			{
				$oApiVoiceManager->FlushCallersNumbersCache($iUserId);
			}
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
	 * @param int $iTenantId = null
	 * @param bool $bAll = false
	 * @return int
	 */
	public function GetContactItemsCount($iUserId, $sSearch = '', $sFirstCharacter = '', $iGroupId = 0, $iTenantId = null, $bAll = false)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId, $iTenantId, $bAll);
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
	 * @param int $iTenantId = null
	 * @param bool $bAll = false
	 * @return bool | array
	 */
	public function GetContactItems($mUserId,
		$iSortField = EContactSortField::EMail, $iSortOrder = ESortOrder::ASC,
		$iOffset = 0, $iRequestLimit = 20, $sSearch = '', $sFirstCharacter = '', $mGroupId = '', $iTenantId = null, $bAll = false)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetContactItems($mUserId, $iSortField, $iSortOrder,
				$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $mGroupId, $iTenantId, $bAll);
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
	 * @return bool | array
	 */
	public function GetContactItemObjects($mUserId)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetContactItemObjects($mUserId);
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
	 * @return CContact|null
	 */
	public function GetMyGlobalContact($iUserId)
	{
		return $this->oStorage->GetMyGlobalContact($iUserId);
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
	 * @return bool | array
	 */
	public function GetAllContactsNamesWithPhones($oAccount)
	{
		$mResult = false;
		try
		{
			$mResult = array();
			$oApiCapaManager = /* @var $oApiCapaManager CApiCapabilityManager */ CApi::Manager('capability');
			if ($oApiCapaManager)
			{
				if ($oApiCapaManager->IsPersonalContactsSupported($oAccount))
				{
					$mResult = $this->oStorage->GetAllContactsNamesWithPhones($oAccount->IdUser, $oAccount->IdTenant,
						$oApiCapaManager->IsGlobalContactsSupported($oAccount));
				}
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
		$mResult = false;
		try
		{
			$mResult = array();
			$oApiCapaManager = /* @var $oApiCapaManager CApiCapabilityManager */ CApi::Manager('capability');

			if (!$bGlobalOnly && $oApiCapaManager->IsPersonalContactsSupported($oAccount))
			{
				$aGroupItems = $this->oStorage->GetSuggestGroupItems($oAccount->IdUser, $sSearch, $iRequestLimit, $iSharedTenantId);
				if (is_array($aGroupItems))
				{
					$mResult = array_merge($mResult, $aGroupItems);
				}

				$aContactItems = $this->oStorage->GetSuggestContactItems($oAccount->IdUser, 
					$sSearch, $iRequestLimit, $bPhoneOnly, $iSharedTenantId, false);

				if (is_array($aContactItems))
				{
					$aAuto = array();
					$aNonAuto = array();
					
					foreach ($aContactItems as /* @var $oItem CContactListItem */ $oItem)
					{
						$sEmail = $oItem->Email;
						if (!empty($sEmail))
						{
							if ($oItem->Auto)
							{
								$aAuto[$sEmail] = isset($aAuto[$sEmail]) && $aAuto[$sEmail] > $oItem->AgeScore ?
									$aAuto[$sEmail] : $oItem->AgeScore;
							}
							else
							{
								$aNonAuto[$sEmail] = isset($aNonAuto[$sEmail]) && $aNonAuto[$sEmail] > $oItem->AgeScore ?
									$aNonAuto[$sEmail] : $oItem->AgeScore;
							}
						}
					}

					foreach ($aNonAuto as $sEmail => $iFrequency)
					{
						if (isset($aAuto[$sEmail]))
						{
							$aNonAuto[$sEmail] = $iFrequency > $aAuto[$sEmail] ? $iFrequency : $aAuto[$sEmail];
							unset($aAuto[$sEmail]);
						}
					}

					$aTemp = array();
					foreach ($aContactItems as /* @var $oItem CContactListItem */ $oItem)
					{
						$sEmail = $oItem->Email;
						if (!empty($sEmail))
						{
							if (isset($aNonAuto[$sEmail]))
							{
								$oItem->AgeScore = $oItem->AgeScore < $aNonAuto[$sEmail] ? $aNonAuto[$sEmail] : $oItem->AgeScore;
							}
							else if (isset($aAuto[$sEmail]))
							{
								$oItem->AgeScore = $oItem->AgeScore < $aAuto[$sEmail] ? $aAuto[$sEmail] : $oItem->AgeScore;
							}

							if (!isset($aTemp[$sEmail]))
							{
								$aTemp[$sEmail] = $oItem;
							}
						}
					}

					$mResult = array_merge($mResult, array_values($aTemp));
				}
			}

			if ($iRequestLimit > count($mResult) && $oApiCapaManager->IsGlobalSuggestContactsSupported($oAccount))
			{
				$oApiGcontactManager = /* @var CApiGcontactsManager */ CApi::Manager('gcontacts');
				if ($oApiGcontactManager)
				{
					$aAccountItems = $oApiGcontactManager->GetContactItems($oAccount,
						EContactSortField::Frequency, ESortOrder::DESC, 0, $iRequestLimit, $sSearch, $bPhoneOnly);

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

			if (is_array($mResult) && 1 < count($mResult))
			{
				$aTemp = array();
				foreach ($mResult as /* @var $oItem CContactListItem */ $oItem)
				{
					$sName = trim($oItem->ToString());
					$aTemp[$sName] = isset($aTemp[$sName]) && $aTemp[$sName]->AgeScore > $oItem->AgeScore ? $aTemp[$sName] : $oItem;
				}
				
				$mResult = array_values($aTemp);

				usort($mResult, function ($oA, $oB) {
					if ($oA->AgeScore === $oB->AgeScore) {
						return 0;
					}
					return ($oA->AgeScore > $oB->AgeScore) ? -1 : 1;
				});
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
		
		if ($bResult)
		{
			$oApiVoiceManager = /* @var $oApiVoiceManager \CApiVoiceManager */ CApi::Manager('voice');
			if ($oApiVoiceManager)
			{
				$oApiVoiceManager->FlushCallersNumbersCache($oContact->IdUser);
			}
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
	 * @param int $iTenantId = null
	 * @return bool
	 */
	public function DeleteContacts($iUserId, $aContactsIds, $iTenantId = null)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteContacts($iUserId, $aContactsIds, $iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		if ($bResult)
		{
			$oApiVoiceManager = /* @var $oApiVoiceManager \CApiVoiceManager */ CApi::Manager('voice');
			if ($oApiVoiceManager)
			{
				$oApiVoiceManager->FlushCallersNumbersCache($iUserId);
			}
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
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteSuggestContacts($iUserId, $aContactsIds);
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
	 * @param mixed $mGroupId
	 * @return bool
	 */
	public function DeleteGroup($iUserId, $mGroupId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteGroup($iUserId, $mGroupId);
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
	 * @param array $aContactIds
	 * @return bool
	 */
//	public function DeleteContactsExceptIds($iUserId, $aContactIds)
//	{
//		$bResult = false;
//		try
//		{
//			$bResult = $this->oStorage->DeleteContactsExceptIds($iUserId, $aContactIds);
//		}
//		catch (CApiBaseException $oException)
//		{
//			$bResult = false;
//			$this->setLastException($oException);
//		}
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
//		try
//		{
//			$bResult = $this->oStorage->DeleteGroupsExceptIds($iUserId, $aGroupIds);
//		}
//		catch (CApiBaseException $oException)
//		{
//			$bResult = false;
//			$this->setLastException($oException);
//		}
//		return $bResult;
//	}

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

		if ($bResult)
		{
			$oApiVoiceManager = /* @var $oApiVoiceManager \CApiVoiceManager */ CApi::Manager('voice');
			if ($oApiVoiceManager)
			{
				$oApiVoiceManager->FlushCallersNumbersCache($oAccount->IdUser);
			}
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

	/**
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return bool
	 */
	public function AddContactsToGroup($oGroup, $aContactIds)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->AddContactsToGroup($oGroup, $aContactIds);
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
	 * @param array $aContactIds
	 * @return bool
	 */
	public function RemoveContactsFromGroup($oGroup, $aContactIds)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->RemoveContactsFromGroup($oGroup, $aContactIds);
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
	 * @param mixed $mContactId
	 * @param int $iContactType = EContactType::Global_
	 * @return mixed
	 */
	public function ConvertedContactLocalId($oAccount, $mContactId, $iContactType = EContactType::Global_)
	{
		$mResult = null;
		try
		{
			$mResult = $this->oStorage->ConvertedContactLocalId($oAccount, $mContactId, $iContactType);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param int $iContactType = EContactType::Global_
	 * @return mixed
	 */
	public function ConvertedContactLocalIdCollection($oAccount, $iContactType = EContactType::Global_)
	{
		$aResult = array();
		try
		{
			$aResult = $this->oStorage->ConvertedContactLocalIdCollection($oAccount, $iContactType);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param array $aIds
	 * @return mixed
	 */
	public function ContactIdsLinkedToGroups($aIds)
	{
		$aResult = array();
		try
		{
			$aResult = $this->oStorage->ContactIdsLinkedToGroups($aIds);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact | bool
	 */
	public function GetGlobalContactById($iUserId, $mContactId)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->GetGlobalContactById($iUserId, $mContactId);
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
	 * @param int $iGroupId
	 * @return bool
	 */
	public function GetGroupEvents($iGroupId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->GetGroupEvents($iGroupId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}
	
	/**
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function GetGroupEvent($sCalendarId, $sEventId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->GetGroupEvent($sCalendarId, $sEventId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function AddEventToGroup($iGroupId, $sCalendarId, $sEventId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->AddEventToGroup($iGroupId, $sCalendarId, $sEventId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}
	
	/**
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function RemoveEventFromGroup($iGroupId, $sCalendarId, $sEventId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->RemoveEventFromGroup($iGroupId, $sCalendarId, $sEventId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}	
	
	/**
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function RemoveEventFromAllGroups($sCalendarId, $sEventId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->RemoveEventFromAllGroups($sCalendarId, $sEventId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}	
	
}
