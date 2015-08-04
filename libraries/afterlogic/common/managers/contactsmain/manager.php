<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @ignore
 *
 * CApiIntegratorManager class summary
 *
 * @package Contactsmain
 */
class CApiContactsMainManager extends AApiManagerWithStorage
{
	/**
	 * Creates a new instance of the object.
	 *
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
	public function createContactListItemObject()
	{
		return new CContactListItem();
	}

	/**
	 * @return CContact
	 */
	public function createContactObject()
	{
		return new CContact();
	}

	/**
	 * @return CGroup
	 */
	public function createGroupObject()
	{
		return new CGroup();
	}

	/**
     * Returns contact item identified by user ID and contact ID.
     *
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @param bool $bIgnoreHideInGab. Default value is **false**
	 * @param int $iSharedTenantId. Default value is **null**
	 * @param bool $bIgnoreAutoCreate Default value is **false**
     *
	 * @return CContact|bool
	 */
	public function getContactById($iUserId, $mContactId, $bIgnoreHideInGab = false, $iSharedTenantId = null, $bIgnoreAutoCreate = false)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->getContactById($iUserId, $mContactId, $bIgnoreHideInGab, $iSharedTenantId, $bIgnoreAutoCreate);
			if ($oContact)
			{
				$mGroupsIds = $this->getContactGroupsIds($oContact);
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
     * //TODO
     *
	 * @param mixed $mTypeId
	 * @param int $iContactType
     *
	 * @return CContact|bool
	 */
	public function GetContactByTypeId($mTypeId, $iContactType)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->GetContactByTypeId($mTypeId, $iContactType);
			if ($oContact)
			{
				$mGroupsIds = $this->getContactGroupsIds($oContact);
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
     * Returns contact item identified by email address.
     *
	 * @param int $iUserId
	 * @param string $sEmail
     *
	 * @return CContact|bool
	 */
	public function getContactByEmail($iUserId, $sEmail)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->getContactByEmail($iUserId, $sEmail);
			if ($oContact)
			{
				$mGroupsIds = $this->getContactGroupsIds($oContact);
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
     * Returns contact item identified by str_id value.
     *
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @param int $iSharedTenantId. Default value is **null**
     *
	 * @return CContact
	 */
	public function getContactByStrId($iUserId, $sContactStrId, $iSharedTenantId = null)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->getContactByStrId($iUserId, $sContactStrId, $iSharedTenantId);
			if ($oContact)
			{
				$mGroupsIds = $this->getContactGroupsIds($oContact);
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
     * Returns list of shared contacts by str_id value.
     *
	 * @param int $iUserId
	 * @param int $iSharedTenantId Default value is **null**
     *
	 * @return array|bool
	 */
	public function getSharedContactIds($iUserId, $iSharedTenantId = null)
	{
		$aContactIds = array();
		try
		{
			$aContactIds = $this->oStorage->getSharedContactIds($iUserId, $iSharedTenantId);
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
	 *
	 * @return array|bool
	 */
	public function getContactGroupsIds($oContact)
	{
		$aGroupsIds = false;
		try
		{
			$aGroupsIds = $this->oStorage->getContactGroupsIds($oContact);
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
	 *
	 * @return CGroup
	 */
	public function getGroupById($iUserId, $mGroupId)
	{
		$oGroup = null;
		try
		{
			$oGroup = $this->oStorage->getGroupById($iUserId, $mGroupId);
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
	 *
	 * @return CGroup
	 */
	public function getGroupByStrId($iUserId, $sGroupStrId)
	{
		$oGroup = null;
		try
		{
			$oGroup = $this->oStorage->getGroupByStrId($iUserId, $sGroupStrId);
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
	 *
	 * @return CGroup
	 */
	public function getGroupByName($iUserId, $sName)
	{
		$oGroup = null;
		try
		{
			$oGroup = $this->oStorage->getGroupByName($iUserId, $sName);
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
	 *
	 * @return bool
	 */
	public function updateContact($oContact)
	{
		$bResult = false;
		try
		{
			if ($oContact->validate())
			{
				$bResult = $this->oStorage->updateContact($oContact);
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
				$oApiVoiceManager->flushCallersNumbersCache($oContact->IdUser);
			}
		}

		return $bResult;
	}
	
	/**
	 * @param CContact $oContact
	 * @param int $iUserId
	 *
	 * @return string
	 */
	public function updateContactUserId($oContact, $iUserId)
	{
		$bResult = false;
		try
		{
			if ($oContact->validate())
			{
				$bResult = $this->oStorage->updateContactUserId($oContact, $iUserId);
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
				$oApiVoiceManager->flushCallersNumbersCache($iUserId);
			}
		}

		return $bResult;
	}	

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function updateGroup($oGroup)
	{
		$bResult = false;
		try
		{
			if ($oGroup->validate())
			{
				$bResult = $this->oStorage->updateGroup($oGroup);
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
	 * @param string $sSearch Default value is empty string
	 * @param string $sFirstCharacter Default value is empty string
	 * @param int $iGroupId Default value is **0**
	 * @param int $iTenantId Default value is **null**
	 * @param bool $bAll Default value is **false**
	 *
	 * @return int
	 */
	public function getContactItemsCount($iUserId, $sSearch = '', $sFirstCharacter = '', $iGroupId = 0, $iTenantId = null, $bAll = false)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->getContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId, $iTenantId, $bAll);
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
	 * @param int $iOffset Default value is **0**
	 * @param int $iRequestLimit Default value is **20**
	 *
	 * @return bool|array
	 */
	public function getContactItemsWithoutOrder($iUserId, $iOffset = 0, $iRequestLimit = 20)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit);
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
	 * @param int $iSortField Default value is **EContactSortField::EMail 2**,
	 * @param int $iSortOrder Default value is **ESortOrder::ASC 0**,
	 * @param int $iOffset Default value is **0**
	 * @param int $iRequestLimit Default value is **20**
	 * @param string $sSearch Default value is empty string
	 * @param string $sFirstCharacter Default value is empty string
	 * @param string $mGroupId Default value is empty string
	 * @param int $iTenantId Default value is **null**
	 * @param bool $bAll Default value is **false**
	 *
	 * @return bool|array
	 */
	public function getContactItems($mUserId, $iSortField = EContactSortField::EMail, $iSortOrder = ESortOrder::ASC, $iOffset = 0, $iRequestLimit = 20, $sSearch = '', $sFirstCharacter = '', $mGroupId = '', $iTenantId = null, $bAll = false)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getContactItems($mUserId, $iSortField, $iSortOrder,
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
	 *
	 * @return bool|array
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
	 *
	 * @return CContact|null
	 */
	public function GetMyGlobalContact($iUserId)
	{
		return $this->oStorage->GetMyGlobalContact($iUserId);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch Default value is empty string
	 * @param string $sFirstCharacter Default value is empty string
	 *
	 * @return int
	 */
	public function getGroupItemsCount($iUserId, $sSearch = '', $sFirstCharacter = '')
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->getGroupItemsCount($iUserId, $sSearch, $sFirstCharacter);
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
	 * @param int $iSortField Default value is **EContactSortField::Name 1**,
	 * @param int $iSortOrder Default value is **ESortOrder::ASC 0**,
	 * @param int $iOffset Default value is **0**
	 * @param int $iRequestLimit Default value is **20**
	 * @param string $sSearch Default value is empty string
	 * @param string $sFirstCharacter Default value is empty string
	 * @param int $iContactId Default value is **0**
	 *
	 * @return bool|array
	 */
	public function getGroupItems($iUserId,
		$iSortField = EContactSortField::Name, $iSortOrder = ESortOrder::ASC,
		$iOffset = 0, $iRequestLimit = 20, $sSearch = '', $sFirstCharacter = '', $iContactId = 0)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getGroupItems($iUserId, $iSortField, $iSortOrder,
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
	 *
	 * @return bool|array
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
				if ($oApiCapaManager->isPersonalContactsSupported($oAccount))
				{
					$mResult = $this->oStorage->GetAllContactsNamesWithPhones($oAccount->IdUser, $oAccount->IdTenant,
						$oApiCapaManager->isGlobalContactsSupported($oAccount));
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
	 * @param string $sSearch Default value is empty string
	 * @param int $iRequestLimit Default value is **20**
	 * @param bool $bGlobalOnly Default value is **false**
	 * @param bool $bPhoneOnly Default value is **false**
	 * @param int $iSharedTenantId Default value is **null**
	 *
	 * @throws $oException
	 *
	 * @return bool|array
	 */
	public function getSuggestItems($oAccount, $sSearch = '', $iRequestLimit = 20, $bGlobalOnly = false, $bPhoneOnly = false, $iSharedTenantId = null)
	{
		$mResult = false;
		try
		{
			$mResult = array();
			$oApiCapaManager = /* @var $oApiCapaManager CApiCapabilityManager */ CApi::Manager('capability');

			if (!$bGlobalOnly && $oApiCapaManager->isPersonalContactsSupported($oAccount))
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

			if ($iRequestLimit > count($mResult) && $oApiCapaManager->isGlobalSuggestContactsSupported($oAccount))
			{
				$oApiGcontactManager = /* @var CApiGcontactsManager */ CApi::Manager('gcontacts');
				if ($oApiGcontactManager)
				{
					$aAccountItems = $oApiGcontactManager->getContactItems($oAccount,
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
	 *
	 * @return bool
	 */
	public function createContact($oContact)
	{
		$bResult = false;
		try
		{
			if ($oContact->validate())
			{
				$bResult = $this->oStorage->createContact($oContact);
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
				$oApiVoiceManager->flushCallersNumbersCache($oContact->IdUser);
			}
		}

		return $bResult;
	}

	/**
	 * @param CGroup $oGroup
	 *
	 * @return bool
	 */
	public function createGroup($oGroup)
	{
		$bResult = false;
		try
		{
			if ($oGroup->validate())
			{
				$bResult = $this->oStorage->createGroup($oGroup);
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
	 * @param int $iTenantId Default value is **null**
	 *
	 * @return bool
	 */
	public function deleteContacts($iUserId, $aContactsIds, $iTenantId = null)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteContacts($iUserId, $aContactsIds, $iTenantId);
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
				$oApiVoiceManager->flushCallersNumbersCache($iUserId);
			}
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 *
	 * @return bool
	 */
	public function deleteSuggestContacts($iUserId, $aContactsIds)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteSuggestContacts($iUserId, $aContactsIds);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * todo
	 *
	 * @param int $iUserId User ID
	 * @param string $sContactId Contact ID
	 *
	 * @return bool
	 */
	public function resetContactFrequency($iUserId, $sContactId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->resetContactFrequency($iUserId, $sContactId);
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
	 *
	 * @return bool
	 */
	public function deleteGroups($iUserId, $aGroupsIds)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteGroups($iUserId, $aGroupsIds);
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
	 *
	 * @return bool
	 */
	public function deleteGroup($iUserId, $mGroupId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteGroup($iUserId, $mGroupId);
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
	 *
	 * @return bool
	 */
	function updateSuggestTable($iUserId, $aEmails)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->updateSuggestTable($iUserId, $aEmails);
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
	 *
	 * @return bool
	 */
	public function clearAllContactsAndGroups($oAccount)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->clearAllContactsAndGroups($oAccount);
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
				$oApiVoiceManager->flushCallersNumbersCache($oAccount->IdUser);
			}
		}

		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function flushContacts()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->flushContacts();
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
	 *
	 * @return bool
	 */
	public function addContactsToGroup($oGroup, $aContactIds)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->addContactsToGroup($oGroup, $aContactIds);
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
	 *
	 * @return bool
	 */
	public function removeContactsFromGroup($oGroup, $aContactIds)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->removeContactsFromGroup($oGroup, $aContactIds);
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
	 * @param int $iContactType Default value is **EContactType::Global_ 1**
	 *
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
	 * @param int $iContactType Default value is **EContactType::Global_ 1**
	 *
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
	 *
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
	 *
	 * @return CContact|bool
	 */
	public function GetGlobalContactById($iUserId, $mContactId)
	{
		$oContact = null;
		try
		{
			$oContact = $this->oStorage->GetGlobalContactById($iUserId, $mContactId);
			if ($oContact)
			{
				$mGroupsIds = $this->getContactGroupsIds($oContact);
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
	 *
	 * @return bool
	 */
	public function getGroupEvents($iGroupId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->getGroupEvents($iGroupId);
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
	 *
	 * @return bool
	 */
	public function getGroupEvent($sCalendarId, $sEventId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->getGroupEvent($sCalendarId, $sEventId);
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
	 *
	 * @return bool
	 */
	public function addEventToGroup($iGroupId, $sCalendarId, $sEventId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->addEventToGroup($iGroupId, $sCalendarId, $sEventId);
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
	 *
	 * @return bool
	 */
	public function removeEventFromGroup($iGroupId, $sCalendarId, $sEventId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->removeEventFromGroup($iGroupId, $sCalendarId, $sEventId);
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
	 *
	 * @return bool
	 */
	public function removeEventFromAllGroups($sCalendarId, $sEventId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->removeEventFromAllGroups($sCalendarId, $sEventId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}	
	
}
