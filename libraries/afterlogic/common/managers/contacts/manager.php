<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiContactsManager class summary
 * 
 * @package Contacts
 */
class CApiContactsManager extends AApiManager
{
	/*
	 * @var $oApiContactsMainManager CApiContactsmainManager
	 */
	private $oApiContactsMainManager;

	/*
	 * @var $oApiGContactsManager CApiGcontactsManager
	 */
	private $oApiGContactsManager;

	/*
	 * @var $oApiContactsMainManagerDAV CApiContactsmainManager
	 */
	private $oApiContactsMainManagerDAV;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('contacts', $oManager, $sForcedStorage);

		$this->oApiGContactsManager = CApi::Manager('gcontacts');
		$this->oApiContactsMainManager = CApi::Manager('contactsmain');
		$this->oApiContactsMainManagerDAV = CApi::Manager('contactsmain', 'sabredav');
		//$this->oApiContactsMainManagerDAV = CApi::Manager('contactsmain', 'carddav');
	}

	/**
	 * Creates a new instance of ContactList object. 
	 * 
	 * @return CContactListItem
	 */
	public function createContactListItemObject()
	{
		return new CContactListItem();
	}

	/**
	 * Creates a new instance of Contact object. 
	 * 
	 * @return CContact
	 */
	public function createContactObject()
	{
		return new CContact();
	}

	/**
	 * Creates a new instance of Group object. 
	 * 
	 * @return CGroup
	 */
	public function createGroupObject()
	{
		return new CGroup();
	}

	/**
	 * Returns contact item identified by user ID and contact ID. 
	 * 
	 * @param int $iUserId User ID value 
	 * @param mixed $mContactId Contact ID value 
	 * @param bool $bIgnoreHideInGab If **true**, the contact will be fetched from Global Address Book disregarding Hidden flag. Default value is **false**. [Aurora only.](http://dev.afterlogic.com/aurora)
	 * @param int $iSharedTenantId If set, the search will be performed within shared contacts of the specified tenant. Default value is **null**. [Aurora only.](http://dev.afterlogic.com/aurora)
	 * @param bool $bIgnoreAutoCreate Default value is **false**
	 *
	 * @return CContact|bool
	 */
	public function getContactById($iUserId, $mContactId, $bIgnoreHideInGab = false, $iSharedTenantId = null, $bIgnoreAutoCreate = false)
	{
		return $this->oApiContactsMainManager->getContactById($iUserId, $mContactId, $bIgnoreHideInGab, $iSharedTenantId, $bIgnoreAutoCreate);
	}

	/**
	 * Returns contact item identified by email address. 
	 * 
	 * @param int $iUserId User ID 
	 * @param string $sEmail Email address 
	 * 
	 * @return CContact|bool
	 */
	public function getContactByEmail($iUserId, $sEmail)
	{
		return $this->oApiContactsMainManager->getContactByEmail($iUserId, $sEmail);
	}

	/**
	 * Returns contact item identified by str_id value. 
	 * 
	 * @param int $iUserId User ID 
	 * @param string $sContactStrId str_id value to look up 
	 * @param int $iSharedTenantId Tenant ID. Default value is **null**. [Aurora only.](http://dev.afterlogic.com/aurora)
	 * 
	 * @return CContact|bool
	 */
	public function getContactByStrId($iUserId, $sContactStrId, $iSharedTenantId = null)
	{
		return $this->oApiContactsMainManager->getContactByStrId($iUserId, $sContactStrId, $iSharedTenantId);
	}

	/**
	 * Returns list of shared contacts by str_id value.
	 *
	 * @param int $iUserId
	 * @param int $iSharedTenantId Tenant ID. Default value is **null**. [Aurora only.](http://dev.afterlogic.com/aurora)
	 *
	 * @return array
	 */
	public function getSharedContactIds($iUserId, $iSharedTenantId = null)
	{
		return $this->oApiContactsMainManager->getSharedContactIds($iUserId, $iSharedTenantId);
	}

	/**
	 * Returns list of groups ID's the specific contact belongs to.
	 * 
	 * @param CContact $oContact Contact object 
	 * 
	 * @return array|bool
	 */
	public function getContactGroupsIds($oContact)
	{
		return $this->oApiContactsMainManager->getContactGroupsIds($oContact);
	}

	/**
	 * Returns group item identified by its ID.
	 * 
	 * @param int $iUserId User ID 
	 * @param mixed $mGroupId Group ID 
	 * 
	 * @return CGroup
	 */
	public function getGroupById($iUserId, $mGroupId)
	{
		return $this->oApiContactsMainManager->getGroupById($iUserId, $mGroupId);
	}

	/**
	 * Returns group item identified by str_id value. 
	 * 
	 * @param int $iUserId User ID 
	 * @param string $sGroupStrId str_id value
	 *
	 * @return CGroup
	 */
	public function getGroupByStrId($iUserId, $sGroupStrId)
	{
		return $this->oApiContactsMainManager->getGroupByStrId($iUserId, $sGroupStrId);
	}
	
	/**
	 * Returns group item identified by its name.
	 *
	 * @param int $iUserId User ID
	 * @param string $sName
	 *
	 * @return CGroup
	 */
	public function getGroupByName($iUserId, $sName)
	{
		return $this->oApiContactsMainManager->getGroupByName($iUserId, $sName);
	}	

	/**
	 * Updates contact information. Using this method is required to finalize changes made to the contact object. 
	 * 
	 * @param CContact $oContact  Contact object to be updated 
	 * @param bool $bUpdateFromGlobal
	 * 
	 * @return bool
	 */
	public function updateContact($oContact, $bUpdateFromGlobal = true)
	{
		$res1 = $res2 = false;

		if ($oContact)
		{
			if ($oContact->Type === EContactType::Personal)
			{
				$res1 = $this->oApiContactsMainManager->updateContact($oContact);
			}
			else if ($oContact->Type === EContactType::Global_)
			{
				$res1 = $this->oApiContactsMainManager->updateContact($oContact);
				
				if ($res1 && $bUpdateFromGlobal)
				{
					$oGlobalContact = $this->oApiContactsMainManager->GetMyGlobalContact($oContact->IdUser);
					if ($oGlobalContact)
					{
						if ($oGlobalContact->CompareAndComputedByNewGlobalContact($oContact))
						{
							$res1 = $this->oApiGContactsManager->updateContact($oGlobalContact);
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
				$this->updateContactGroupsIdsWithNames($oContact);
				$oContactDAV = $this->oApiContactsMainManagerDAV->getContactByStrId($oContact->IdUser, $oContact->IdContactStr);
				if ($oContactDAV)
				{
					$res2 = $this->oApiContactsMainManagerDAV->updateContact($oContact);
				}
				else
				{
					$res2 = $this->oApiContactsMainManagerDAV->createContact($oContact);
				}
			}
			else
			{
				$res2 = true;
			}			
		}

		return ($res1 && $res2);
	}
	
	/**
	 * Update contact information setting a new user ID 
	 * 
	 * @param CContact $oContact Contact object to be updated 
	 * @param int $iUserId User ID 
	 * 
	 * @return bool
	 */
	public function updateContactUserId($oContact, $iUserId)
	{
		$res1 = $res2 = false;
		if ($oContact)
		{
			if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
			{
				$res2 = $this->oApiContactsMainManagerDAV->updateContactUserId($oContact, $iUserId);
			}
			else
			{
				$res2 = true;
			}
			$res1 = $this->oApiContactsMainManager->updateContactUserId($oContact, $iUserId);
		}

		return ($res1 && $res2);
	}		

	/**
	 * Updates group information. Using this method is required to finalize changes made to the group object. 
	 * 
	 * @param CGroup $oGroup
	 *
	 * @return bool
	 */
	public function updateGroup($oGroup)
	{
		$res1 = $res2 = false;

		$oGroupDb = $this->oApiContactsMainManager->getGroupById($oGroup->IdUser, $oGroup->IdGroup);

		$res1 = $this->oApiContactsMainManager->updateGroup($oGroup);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts') && $oGroupDb)
		{
			$oGroup->IdGroup = $oGroupDb->Name;

			$oContactItems = $this->oApiContactsMainManager->getContactItems($oGroup->IdUser, EContactSortField::EMail,
				ESortOrder::ASC, 0, 999, '', '', $oGroupDb->IdGroup);
			
			if (is_array($oContactItems))
			{
				foreach ($oContactItems as $oContactItem)
				{
					$oContact = $this->oApiContactsMainManagerDAV->getContactById($oGroup->IdUser, $oContactItem->IdStr);
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
						$this->oApiContactsMainManagerDAV->updateContact($oContact);
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
	 * Returns list of contacts which match the specified criteria 
	 * 
	 * @param int $iUserId User ID 
	 * @param string $sSearch Search pattern. Default value is empty string.
	 * @param string $sFirstCharacter If specified, will only return contacts with names starting from the specified character. Default value is empty string.
	 * @param int $iGroupId Group ID. Default value is **0**.
	 * @param int $iTenantId Group ID. Default value is null.
	 * @param bool $bAll Default value is null
	 * 
	 * @return int
	 */
	public function getContactItemsCount($iUserId, $sSearch = '', $sFirstCharacter = '', $iGroupId = 0, $iTenantId = null, $bAll = false)
	{
		return $this->oApiContactsMainManager->getContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId, $iTenantId, $bAll);
	}

	/**
	 * Returns list of contacts within specified range. Sorting is not performed. 
	 * 
	 * @param int $iUserId User ID 
	 * @param int $iOffset Ordinal number of the contact item the list stars with. Default value is **0**.
	 * @param int $iRequestLimit The upper limit for total number of contacts returned. Default value is **20**.
	 * 
	 * @return array|bool
	 */
	public function getContactItemsWithoutOrder($iUserId, $iOffset = 0, $iRequestLimit = 20)
	{
		return $this->oApiContactsMainManager->getContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit);
	}

	/**
	 * Returns list of contacts within specified range, sorted according to specified requirements. 
	 * 
	 * @param string $mUserId User ID.
	 * @param int $iSortField Sort field. Accepted values:
	 *
	 *		EContactSortField::Name
	 *		EContactSortField::EMail
	 *		EContactSortField::Frequency
	 *
	 * Default value is **EContactSortField::EMail**.
	 * @param int $iSortOrder Sorting order. Accepted values:
	 *
	 *		ESortOrder::ASC
	 *		ESortOrder::DESC,
	 *
	 * for ascending and descending respectively. Default value is **ESortOrder::ASC**.
	 * @param int $iOffset Ordinal number of the contact item the list stars with. Default value is **0**.
	 * @param int $iRequestLimit The upper limit for total number of contacts returned. Default value is **20**.
	 * @param string $sSearch Search pattern. Default value is empty string.
	 * @param string $sFirstCharacter If specified, will only return contacts with names starting from the specified character. Default value is empty string.
	 * @param string $mGroupId Group ID. Default value is empty string.
	 * @param int $iTenantId Tenant ID. Default value is **null**. [Aurora only.](http://dev.afterlogic.com/aurora)
	 * @param bool $bAll
	 * 
	 * @return array|bool
	 */
	public function getContactItems($mUserId,
		$iSortField = EContactSortField::EMail, $iSortOrder = ESortOrder::ASC,
		$iOffset = 0, $iRequestLimit = 20, $sSearch = '', $sFirstCharacter = '', $mGroupId = '', $iTenantId = null, $bAll = false)
	{
		return $this->oApiContactsMainManager->getContactItems($mUserId, $iSortField, $iSortOrder,
			$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $mGroupId, $iTenantId, $bAll);
	}

	/**
	 * Returns a number of groups which match the specified criteria. 
	 * 
	 * @param int $iUserId User ID 
	 * @param string $sSearch = '' Search pattern. Default value is empty string. 
	 * @param string $sFirstCharacter = '' If specified, will only return contacts with names starting from the specified character. Default value is empty string. 
	 * 
	 * @return int
	 */
	public function getGroupItemsCount($iUserId, $sSearch = '', $sFirstCharacter = '')
	{
		return $this->oApiContactsMainManager->getGroupItemsCount($iUserId, $sSearch, $sFirstCharacter);
	}

	/**
	 * Returns filtered and sorted list of user's groups. 
	 * 
	 * @param int $iUserId User ID 
	 * @param int $iSortField Default value is **EContactSortField::Name**.
	 * @param int $iSortOrder Sorting order. Accepted values:
	 *
	 *		ESortOrder::ASC
	 *		ESortOrder::DESC,
	 *
	 * for ascending and descending respectively. Default value is **ESortOrder::ASC**. ,
	 * @param int $iOffset Ordinal number of the contact item the list stars with. Default value is **0**.
	 * @param int $iRequestLimit The upper limit for total number of contacts returned. Default value is **20**.
	 * @param string $sSearch Search pattern. Default value is empty string.
	 * @param string $sFirstCharacter If specified, will only return contacts with names starting from the specified character. Default value is empty string.
	 * @param int $iContactId If set, will only return groups which contain specific contact. Default value is **0**.
	 * 
	 * @return array|bool
	 */
	public function getGroupItems($iUserId,
		$iSortField = EContactSortField::Name, $iSortOrder = ESortOrder::ASC,
		$iOffset = 0, $iRequestLimit = 20, $sSearch = '', $sFirstCharacter = '', $iContactId = 0)
	{
		return $this->oApiContactsMainManager->getGroupItems($iUserId, $iSortField, $iSortOrder,
			$iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iContactId);
	}

	/**
	 * Provides flexible interface for getting autocompletion list of contacts 
	 * 
	 * @param CAccount $oAccount Account object 
	 * @param string $sSearch Search pattern, usually a text entered in the input field thus far. Default value is empty string.
	 * @param int $iRequestLimit The upper limit for total number of contacts returned. Default value is **20**.
	 * @param bool $bGlobalOnly If set to **true**, will only return entries from Global Address book. Default value is **false**. [Aurora only.](http://dev.afterlogic.com/aurora)
	 * @param bool $bPhoneOnly If set to **true**, will only search against phone numbers. Default value is **false**.
	 * @param int $iSharedTenantId If specified, will only search within shared contacts for specific tenant. Default value is **null**. [Aurora only.](http://dev.afterlogic.com/aurora)
	 *
	 * @return array|bool
	 */
	public function getSuggestItems($oAccount, $sSearch = '', $iRequestLimit = 20, $bGlobalOnly = false, $bPhoneOnly = false, $iSharedTenantId = null)
	{
		return $this->oApiContactsMainManager->getSuggestItems($oAccount, $sSearch, $iRequestLimit, $bGlobalOnly, $bPhoneOnly, $iSharedTenantId);
	}

	/**
	 * The method is used for saving created contact to the database. 
	 * 
	 * @param CContact $oContact
	 * 
	 * @return bool
	 */
	public function createContact($oContact)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsMainManager->createContact($oContact);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$this->updateContactGroupsIdsWithNames($oContact);
			$res2 = $this->oApiContactsMainManagerDAV->createContact($oContact);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * The method is used for saving created group to the database. 
	 * 
	 * @param CGroup $oGroup
	 * 
	 * @return bool
	 */
	public function createGroup($oGroup)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsMainManager->createGroup($oGroup);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsMainManagerDAV->createGroup($oGroup);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * Deletes one or multiple contacts from user's address book.
	 * 
	 * @param int $iUserId User ID
	 * @param array $aContactsIds List of contacts IDs
	 * @param int $iTenantId If specified, the search is restricted to specific tenant. Default value is **null**. [Aurora only.](http://dev.afterlogic.com/aurora)
	 * 
	 * @return bool
	 */
	public function deleteContacts($iUserId, $aContactsIds, $iTenantId = null)
	{
		$aContactsStrIds = array();
		foreach ($aContactsIds as $iContactsId)
		{
			$oContact = $this->oApiContactsMainManager->getContactById($iUserId, $iContactsId, $iTenantId);
			if ($oContact)
			{
				$aContactsStrIds[] = $oContact->IdContactStr;
			}
		}

		$res1 = $res2 = false;

		$res1 = $this->oApiContactsMainManager->deleteContacts($iUserId, $aContactsIds, $iTenantId);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts') && is_null($iTenantId))
		{
			$res2 = $this->oApiContactsMainManagerDAV->deleteContacts($iUserId, $aContactsStrIds);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * The method is used for deleting one or multiple contacts to autocompletion list of contacts.
	 * 
	 * @param int $iUserId User ID
	 * @param array $aContactsIds List of contacts IDs
	 * 
	 * @return bool
	 */
	public function deleteSuggestContacts($iUserId, $aContactsIds)
	{
		$aContactsStrIds = array();
		foreach ($aContactsIds as $iContactsId)
		{
			$oContact = $this->oApiContactsMainManager->getContactById($iUserId, $iContactsId);
			if ($oContact)
			{
				$aContactsStrIds[] = $oContact->IdContactStr;
			}
		}

		$res1 = $res2 = false;

		$res1 = $this->oApiContactsMainManager->deleteSuggestContacts($iUserId, $aContactsIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsMainManagerDAV->deleteSuggestContacts($iUserId, $aContactsStrIds);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * todo
	 *
	 * @param int $iUserId User ID
	 * @param mixed $mGroupId Group ID
	 *
	 * @return bool
	 */
	public function resetContactFrequency($iUserId, $sContactId)
	{
		return $this->oApiContactsMainManager->resetContactFrequency($iUserId, $sContactId);
	}

	/**
	 * Deletes one or multiple contacts from user's address book.
	 * 
	 * @param int $iUserId User ID
	 * @param array $aGroupsIds List of groups IDs
	 * 
	 * @return bool
	 */
	public function deleteGroups($iUserId, $aGroupsIds)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsMainManager->deleteGroups($iUserId, $aGroupsIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsMainManagerDAV->deleteGroups($iUserId, $aGroupsIds);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * Deletes specific group from user's address book.
	 * 
	 * @param int $iUserId User ID
	 * @param mixed $mGroupId Group ID
	 * 
	 * @return bool
	 */
	public function deleteGroup($iUserId, $mGroupId)
	{
		return $this->oApiContactsMainManager->deleteGroup($iUserId, $mGroupId);
	}

	/**
	 * The method is used for deleting one or multiple contacts to autocompletion list of contacts.
	 * 
	 * @param int $iUserId User ID
	 * @param array $aEmails List of email addresses
	 * 
	 * @return bool
	 */
	function updateSuggestTable($iUserId, $aEmails)
	{
		return $this->oApiContactsMainManager->updateSuggestTable($iUserId, $aEmails);
	}

	/**
	 * Allows for importing data into user's address book.
	 * 
	 * @param int $iUserId User ID
	 * @param string $sSyncType Data source type. Currently, "csv" and "vcf" options are supported.
	 * @param string $sTempFileName Path to the file data are imported from.
	 * @param int $iParsedCount
	 * @param int $iGroupId
	 * @param bool $bIsShared
	 *
	 * @return int|false If importing is successful, number of imported entries is returned. 
	 */
	public function import($iUserId, $sSyncType, $sTempFileName, &$iParsedCount, $iGroupId, $bIsShared)
	{
		$oApiUsersManager = CApi::Manager('users');
		$oAccount = $oApiUsersManager->getDefaultAccount($iUserId);

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

				if ($this->createContact($oContact))
				{
					$iParsedCount++;
				}
			}
			return $iParsedCount;
		}

		return false;
	}

	/**
	 * Allows for exporting data from user's address book. 
	 * 
	 * @param int $iUserId User ID 
	 * @param string $sSyncType Data source type. Currently, "csv" and "vcf" options are supported. 
	 * 
	 * @return string | bool
	 */
	public function export($iUserId, $sSyncType)
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
			$aContactItems = $this->oApiContactsMainManagerDAV->GetContactItemObjects($iUserId);
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

	///**
	// * @param int $iUserId
	// * @param array $aContactsIds
	// * @return bool
	// */
	//public function DeleteContactsExceptIds($iUserId, $aContactsIds)
	//{
	//	$aContactsStrIds = array();
	//	foreach ($aContactsIds as $iContactsId)
	//	{
	//		$oContact = $this->oApiContactsMainManager->getContactById($iUserId, $iContactsId);
	//		if ($oContact)
	//		{
	//			$aContactsStrIds[] = $oContact->IdContactStr;
	//		}
	//	}
	//
	//	$res1 = $res2 = false;
	//
	//	$res1 = $this->oApiContactsMainManager->DeleteContactsExceptIds($iUserId, $aContactsIds);
	//	if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
	//	{
	//		$res2 = $this->oApiContactsManagerSabreDAV->DeleteContactsExceptIds($iUserId, $aContactsStrIds);
	//	}
	//	else
	//	{
	//		$res2 = true;
	//	}
	//
	//	return ($res1 && $res2);
	//}
	//
	///**
	// * @param int $iUserId
	// * @param array $aGroupIds
	// * @return bool
	// */
	//public function DeleteGroupsExceptIds($iUserId, $aGroupIds)
	//{
	//	$res1 = $res2 = false;
	//
	//	$res1 = $this->oApiContactsMainManager->DeleteGroupsExceptIds($iUserId, $aGroupIds);
	//	if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
	//	{
	//		$res2 = $this->oApiContactsManagerSabreDAV->DeleteGroupsExceptIds($iUserId, $aGroupIds);
	//	}
	//	else
	//	{
	//		$res2 = true;
	//	}
	//
	//	return ($res1 && $res2);
	//}

	/**
	 * The method will delete all the groups and contacts from user's address book. 
	 * 
	 * @param CAccount $oAccount Object representing account to be processed 
	 * 
	 * @return bool
	 */
	public function clearAllContactsAndGroups($oAccount)
	{
		$res1 = $res2 = false;

		$res1 = $this->oApiContactsMainManager->clearAllContactsAndGroups($oAccount);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsMainManagerDAV->clearAllContactsAndGroups($oAccount);
		}
		else
		{
			$res2 = true;
		}

		return ($res1 && $res2);
	}

	/**
	 * The method will completely delete all marked as deleted contacts from user's address book.
	 *
	 * @return bool
	 */
	public function flushContacts()
	{
		return $this->oApiContactsMainManager->flushContacts();
	}

	/**
	 * The method will replace contact groups id's width groups names
	 *
	 * @param CContact $oContact
	 */
	public function updateContactGroupsIdsWithNames(&$oContact)
	{
		$aResult = array();

		foreach ($oContact->GroupsIds as $mGroupId)
		{
			$oGroup = $this->oApiContactsMainManager->getGroupById($oContact->IdUser, $mGroupId);
			if ($oGroup)
			{
				$aResult[] = (string) $oGroup->Name;
			}
		}
		
		$oContact->GroupsIds = $aResult;
	}

	/**
	 * Adds one or multiple contacts to the specific group. 
	 * 
	 * @param CGroup $oGroup Group object to be used 
	 * @param array $aContactIds List of contact IDs to be added 
	 * 
	 * @return bool
	 */
	public function addContactsToGroup($oGroup, $aContactIds)
	{
		$res1 = $res2 = false;

		$aContactsStrIds = array();
		foreach ($aContactIds as $iContactId)
		{
			$oContact = $this->oApiContactsMainManager->getContactById($oGroup->IdUser, $iContactId);
			if ($oContact)
			{
				$aContactsStrIds[] = $oContact->IdContactStr;
			}
		}
		
		$res1 = $this->oApiContactsMainManager->addContactsToGroup($oGroup, $aContactIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsMainManagerDAV->addContactsToGroup($oGroup, $aContactsStrIds);
		}
		else
		{
			$res2 = true;
		}
		
		return ($res1 && $res2);
	}
	
	/**
	 * Adds one or multiple contacts from global address book to the specific group. [Aurora only.](http://dev.afterlogic.com/aurora)
	 * 
	 * @param CAccount $oAccount Object representing account to be processed 
	 * @param CGroup $oGroup Group object to be used 
	 * @param array $aContactIds List of contact IDs to be added 
	 * 
	 * @return bool
	 */
	public function addGlobalContactsToGroup($oAccount, $oGroup, $aContactIds)
	{
		if ($this->oApiGContactsManager)
		{
			$aNewContactIds = array();

			foreach ($aContactIds as $mId)
			{
				$mContactId = $this->oApiContactsMainManager->ConvertedContactLocalId($oAccount, $mId, EContactType::Global_);
				if (!$mContactId)
				{
					$oGlobalContact = $this->oApiGContactsManager->getContactById($oAccount, $mId);

					/* @var $oGlobalContact CContact */
					if ($oGlobalContact)
					{
						$oGlobalContact->IdUser = $oAccount->IdUser;
						$oGlobalContact->IdDomain = $oAccount->IdDomain;
						$oGlobalContact->IdTenant = $oAccount->IdTenant;
						$oGlobalContact->Type = EContactType::Global_;
						$oGlobalContact->IdTypeLink = $mId;

						$bResult = $this->createContact($oGlobalContact);
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
				return $this->addContactsToGroup($oGroup, $aNewContactIds);
			}
		}

		return false;
	}

	/**
	 * The method deletes one or multiple contacts from the group. 
	 * 
	 * @param CGroup $oGroup Group object to be used 
	 * @param array $aContactIds List of contact IDs to be deleted 
	 * 
	 * @return bool
	 */
	public function removeContactsFromGroup($oGroup, $aContactIds)
	{
		$res1 = $res2 = false;

		$aContactsStrIds = array();
		foreach ($aContactIds as $iContactId)
		{
			$oContact = $this->oApiContactsMainManager->getContactById($oGroup->IdUser, $iContactId);
			if ($oContact)
			{
				$aContactsStrIds[] = $oContact->IdContactStr;
			}
		}

		$res1 = $this->oApiContactsMainManager->removeContactsFromGroup($oGroup, $aContactIds);
		if ('sabredav' !== CApi::GetManager()->GetStorageByType('contacts'))
		{
			$res2 = $this->oApiContactsMainManagerDAV->removeContactsFromGroup($oGroup, $aContactsStrIds);
		}
		else
		{
			$res2 = true;
		}
		return ($res1 && $res2);
	}

	/**
	 * The method create contact in global address book for each system account
	 *
	 * @param CAccount $oAccount
	 * @param bool $bSelfOnly
	 *
	 * @return bool
	 */
	public function synchronizeExternalContacts($oAccount, $bSelfOnly = false)
	{
		$aIds = array();
		if ($this->oApiGContactsManager)
		{
			if ($bSelfOnly)
			{
				$oGlobalContact = $this->oApiGContactsManager->getContactByTypeId($oAccount, $oAccount->IdUser, true);

				/* @var $oGlobalContact CContact */
				if ($oGlobalContact)
				{
					$mContactId = $this->oApiContactsMainManager->ConvertedContactLocalId($oAccount,
						$oGlobalContact->IdContact, EContactType::Global_);

					if ($mContactId)
					{
						$aIds[$mContactId] = $oGlobalContact->IdContact;
					}
				}
			}
			else
			{
				$aIds = $this->oApiContactsMainManager->ConvertedContactLocalIdCollection($oAccount, EContactType::Global_);
			}

			if ($aIds && is_array($aIds) && 0 < count($aIds))
			{
				CApi::Log('synchronizeExternalContacts: '.count($aIds));

				$aLinkedContact = $this->oApiContactsMainManager->ContactIdsLinkedToGroups(array_keys($aIds));
				$aLinkedContact = is_array($aLinkedContact) ? $aLinkedContact : array();

				$aContactToDelete = array();
				foreach ($aIds as $iLocalContactId => $sGlobalId)
				{
					if (in_array($iLocalContactId, $aLinkedContact))
					{
						$oGlobalContact = $this->oApiGContactsManager->getContactById($oAccount, $sGlobalId, true);
						if ($oGlobalContact)
						{
							$oLocalGlobalContact = $this->oApiContactsMainManager->getContactById($oAccount->IdUser, $iLocalContactId, true);
							if ($oLocalGlobalContact && EContactType::Global_ === $oLocalGlobalContact->Type)
							{
								if ($oLocalGlobalContact->CompareAndComputedByNewGlobalContact($oGlobalContact))
								{
									$this->updateContact($oLocalGlobalContact, false);
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
					$this->deleteContacts($oAccount->IdUser, $aContactToDelete);
				}

				return true;
			}
			else
			{
				CApi::Log('synchronizeExternalContacts: none');
			}
		}

		return true;
	}
	
	
	/**
	 * Returns list of calendar events the specific group belongs to
	 *
	 * @param int $iUserId
	 * @param int $iGroupId
	 *
	 * @return bool
	 */
	public function getGroupEvents($iUserId, $iGroupId)
	{
		$aResult = array();
		$aEvents = $this->oApiContactsMainManager->getGroupEvents($iGroupId);
		if (is_array($aEvents) && 0 < count($aEvents))
		{
			$oApiUsersManager = CApi::Manager('users');
			$iAccountId =  $oApiUsersManager->getDefaultAccountId($iUserId);
			$oAccount = $oApiUsersManager->getAccountById($iAccountId);
			
			if ($oAccount)
			{
				$oApiCalendarManager = CApi::Manager('calendar');

				foreach ($aEvents as $aEvent)
				{
					$aResult[] = $oApiCalendarManager->getBaseEvent($oAccount, $aEvent['id_calendar'], $aEvent['id_event']);
				}
			}
		}
		
		return $aResult;
	}	
	
	/**
	 *  Return calendar event the specific group belongs to
	 *
	 * @param string $sCalendarId
	 * @param string $sEventId
	 *
	 * @return bool
	 */
	public function getGroupEvent($sCalendarId, $sEventId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oApiContactsMainManager->getGroupEvent($sCalendarId, $sEventId);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}	
	
	/**
	 *  Return calendar event existence the specific group belongs to
	 *
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 *
	 * @return bool
	 */
	public function isGroupEventExists($iGroupId, $sCalendarId, $sEventId)
	{
		$aResult = false;
		try
		{
			$aEvent = $this->getGroupEvent($iGroupId, $sCalendarId, $sEventId);
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
	 *  Adds calendar event to the specific group.
	 *
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 *
	 * @return bool
	 */
	public function addEventToGroup($iGroupId, $sCalendarId, $sEventId)
	{
		return $this->oApiContactsMainManager->addEventToGroup($iGroupId, $sCalendarId, $sEventId);
	}
	
	/**
	 *  Remove calendar event from the specific group.
	 *
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 *
	 * @return bool
	 */
	public function removeEventFromGroup($iGroupId, $sCalendarId, $sEventId)
	{
		return $this->oApiContactsMainManager->removeEventFromGroup($iGroupId, $sCalendarId, $sEventId);
	}
	
	/**
	 *  Remove calendar event from the all groups.
	 *
	 * @param string $sCalendarId
	 * @param string $sEventId
	 *
	 * @return bool
	 */
	public function removeEventFromAllGroups($sCalendarId, $sEventId)
	{
		return $this->oApiContactsMainManager->removeEventFromAllGroups($sCalendarId, $sEventId);
	}
}
