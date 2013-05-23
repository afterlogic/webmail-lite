<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Contacts
 */
class CApiContactsSabredavStorage extends CApiContactsStorage
{
	/**
	 * @var api_Settings
	 */
	protected $Settings;

	/**
	 * @var string
	 */
	public $Principal;

	/**
	 * @var bool
	 */
	protected $Connected;

	/**
	 * @var string
	 */
	protected $User;

	/**
	 * @var string
	 */
	protected $TimeZone;

	/**
	 * @var string
	 */
	protected $DbPrefix;

	/**
	 * @var CAccount
	 */
	protected $Account;

	/**
	 * @var $oApiUsersManager CApiUsersManager
	 */
	protected $ApiUsersManager;

	/**
	 * @var \afterlogic\DAV\Server
	 */
	protected $Server;

	protected $aAddressBooksCache;
	protected $aContactItemsCache;
	protected $aGroupItemsCache;
	protected $ContactsCache;
	protected $AccountsCache;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('sabredav', $oManager);

		$this->Settings = CApi::GetSettings();
		$this->User = null;
		$this->Account = null;
		$this->DbPrefix = $this->Settings->GetConf('Common/DBPrefix');
		$this->Connected = false;

		$this->CalendarHomeSet = '';

		$this->aAddressBooksCache = array();
		$this->aContactItemsCache = array();
		$this->aGroupItemsCache = array();

		$this->ContactsCache = array();
		$this->GroupsCache = array();
		$this->AccountsCache = array();

		$this->ApiUsersManager = CApi::Manager('users');
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function InitByAccount($oAccount)
	{
		$bResult = false;
		if ($oAccount != null && ($this->User != $oAccount->Email ||
			$this->Account->Email != $oAccount->Email))
		{
			$this->Account = $oAccount;
			$this->User = $oAccount->Email;

			if ($this->Account)
			{
				$this->aAddressBooksCache = array();
				$this->aContactItemsCache = array();
				$this->aGroupItemsCache = array();

				$this->ContactsCache = array();
				$this->GroupsCache = array();

				$this->Server = new \afterlogic\DAV\Server();
				$oPdo = CApi::GetPDO();
				$oHelper = new \afterlogic\DAV\Auth\Backend\Helper($oPdo, $this->DbPrefix);
				$oHelper->CheckPrincipals($oAccount->Email);

				$oPrincipal = null;
				$oPrincipalCollection = $this->Server->tree->getNodeForPath('principals');
				if ($oPrincipalCollection->childExists($this->User))
				{
					$oPrincipal = $oPrincipalCollection->getChild($this->User);
				}
				if (isset($oPrincipal))
				{
					$aProperties = $oPrincipal->getProperties(array('uri'));
					$this->Principal = $aProperties['uri'];
				}

				$this->Connected = true;
			}
		}

		if ($this->Account)
		{
			$bResult = true;
		}

		return $bResult;
	}

	protected function GetDefaultAccountByUserId($iUserId)
	{
		if (!isset($this->AccountsCache[$iUserId]))
		{
			$iAccountId = $this->ApiUsersManager->GetDefaultAccountId($iUserId);
			$oAccount = $this->ApiUsersManager->GetAccountById($iAccountId);
			$this->AccountsCache[$iUserId] = $oAccount;
		}

		return $this->AccountsCache[$iUserId];
	}


	/**
	}
	 * @param int $iUserId
	 */
	public function Init($iUserId)
	{

		$oAccount = $this->GetDefaultAccountByUserId($iUserId);
		return $this->InitByAccount($oAccount);
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact | false
	 */
	public function GetContactById($iUserId, $mContactId)
	{
		$oContact = false;
		if($this->Init($iUserId))
		{
			$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
			$oContactItem = $this->geItem($iUserId, $oAddressBook, $mContactId);
			if ($oContactItem)
			{
				$sVCardData = $oContactItem->get();
				if ($sVCardData)
				{
					$oContact = new CContact();
					$oContact->InitFromVCardStr($iUserId, $sVCardData);
					$oContact->IdContact = $mContactId;
					$oContact->ETag = trim($oContactItem->getETag(), '"');
				}
			}
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
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @return CContact | bool
	 */
	public function GetContactByStrId($iUserId, $sContactStrId)
	{
		$this->GetContactById($iUserId, $sContactStrId);
	}

	/**
	 * @param CContact $oContact
	 * @return array | bool
	 */
	public function GetContactGroupsIds($oContact)
	{
		return $oContact->GroupsIds;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupId
	 * @return array | bool
	 */
	public function GetGroupContactsIds($iUserId, $mGroupId)
	{
		$oGroup = false;;
		if (!empty($mGroupId))
		{
			$oGroup = $this->GetGroupById($iUserId, $mGroupId);
			if ($oGroup)
			{
				return $oGroup->ContactsIds;
			}
		}
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupId
	 * @return CGroup
	 */
	public function GetGroupById($iUserId, $mGroupId)
	{
		$bResult = false;

		if (!isset($this->GroupsCache[$mGroupId]))
		{
			if($this->Init($iUserId))
			{
				$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
				if ($oAddressBook)
				{
					if (!isset($this->aGroupItemsCache[$oAddressBook->getName()]))
					{
						$this->getItems($iUserId, $oAddressBook);
					}

					if (isset($this->aGroupItemsCache[$oAddressBook->getName()][$mGroupId]))
					{
						$bResult = new CGroup();
						$bResult->IdUser = $iUserId;
						$bResult->IdGroup = $mGroupId;
						$bResult->IdGroupStr = $mGroupId;
						$bResult->Name =  $mGroupId;

						$aItems = $this->aGroupItemsCache[$oAddressBook->getName()][$mGroupId];
						$aContactsIds = array();
						foreach ($aItems as $sContactsId)
						{
							$aContactsIds[] = $sContactsId;
						}
						$bResult->ContactsIds = $aContactsIds;
					}
				}
			}
		}

		if (isset($this->GroupsCache[$mGroupId]))
		{
			$bResult = $this->GroupsCache[$mGroupId];
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param string $sGroupStrId
	 * @return CGroup
	 */
	public function GetGroupByStrId($iUserId, $sGroupStrId)
	{
		return $this->GetGroupById($iUserId, $sGroupStrId);
	}

	/**
	 * @param mixed $iUserId
	 * @param string $sName
	 * @return bool | array
	 */
	protected function getAddressBook($iUserId, $sName)
	{
		$oAddressBook = false;
		if ($this->Init($iUserId))
		{
			if (!isset($this->aAddressBooksCache[$iUserId][$sName]))
			{
				$oUserAddressBooks = new \Sabre\CardDAV\UserAddressBooks($this->Server->GetCarddavBackend(),
						$this->Principal);
				if ($oUserAddressBooks->childExists($sName))
				{
					$this->aAddressBooksCache[$iUserId][$sName] = $oUserAddressBooks->getChild($sName);
				}
			}

			if (isset($this->aAddressBooksCache[$iUserId][$sName]))
			{
				$oAddressBook = $this->aAddressBooksCache[$iUserId][$sName];
			}
		}

		return $oAddressBook;
	}

	/**
	 * @param int $iUserId
	 * @param \afterlogic\DAV\CardDAV\AddressBook $oAddressBook
	 * @return bool | array
	 */
	protected function getObjectItems($iUserId, $oAddressBook)
	{
		$mResult = false;
		$sName = null;
		if ($oAddressBook)
		{
			$sName = $oAddressBook->getName();
			if (!isset($this->aContactItemsCache[$sName]))
			{
				$this->Init($iUserId);

				$this->aContactItemsCache[$sName] = array();
				foreach ($oAddressBook->getChildren() as $oChild)
				{
					$this->aContactItemsCache[$sName][$oChild->getName()] = $oChild;
				}
			}

			$mResult = $this->aContactItemsCache[$sName];
		}
		return $mResult;
	}

	/**
	 * @param int $iUserId
	 * @param \Sabre\CardDAV\AddressBook $oAddressBook
	 * @param string $sId
	 * @return bool | \Sabre\DAV\Card
	 */
	protected function geItem($iUserId, $oAddressBook, $sId)
	{
		$bResult = false;
		$sName = null;
		if ($oAddressBook)
		{
			$sName = $oAddressBook->getName();
			if (isset($this->aContactItemsCache[$sName][$sId]))
			{
				$bResult = $this->aContactItemsCache[$sName][$sId];
			}
			else
			{
				if ($oAddressBook->childExists($sId))
				{
					$bResult = $oAddressBook->getChild($sId);
				}
			}
		}
		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param \afterlogic\DAV\CardDAV\AddressBook
	 * @param string $sSearch
	 * @param string $sFirstCharacter = ''
	 * @param int $sGroupId
	 * @return bool | array
	 */
	protected function getItems($iUserId, $oAddressBook, $sSearch = '', $sFirstCharacter = '', $sGroupId = null)
	{
		$aResult = array();
		$sName = null;
		if ($this->Init($iUserId) && $oAddressBook)
		{

			$sName = $oAddressBook->getName();

			if (!empty($sGroupId))
			{
				unset($this->ContactsCache[$sName]);
			}

			if (isset($this->ContactsCache[$sName]))
			{
				$aResult = $this->ContactsCache[$sName];
			}
			else
			{
				$aItems = $this->getObjectItems($iUserId, $oAddressBook);

				foreach ($aItems as $oItem)
				{
					$sItemId = $oItem->getName();
					$vCard = false;
					try
					{
						$vCard = \Sabre\VObject\Reader::read($oItem->get());
					}
					catch(Exception $ex)
					{
						CApi::Log('SABREDAV: Invalid VCard with Id='.$sItemId);
					}
					if ($vCard)
					{
						$sFullName = $sFirstName = $sLastName = $sTitle = $sNickName = '';
						if (isset($vCard->FN))
						{
							$sFullName = $vCard->FN->value;
						}
						if (isset($vCard->N))
						{
							$aNames = explode(';', $vCard->N->value);
							if (!empty($aNames[0]))
							{
								$sLastName = $aNames[0];
							}
							if (!empty($aNames[1]))
							{
								$sFirstName = $aNames[1];
							}
							if (!empty($aNames[3]))
							{
								$sTitle = $aNames[3];
							}
						}
						if (isset($vCard->NICKNAME))
						{
							$sNickName = $vCard->NICKNAME->value;
						}

						$bFindEmail = false;
						if (isset($vCard->EMAIL))
						{
							foreach($vCard->EMAIL as $oEmail)
							{
								if (stripos($oEmail->value, $sSearch) !== false)
								{
									$bFindEmail = true;
									break;
								}
							}
						}

						$sCategories = '';
						if (isset($vCard->CATEGORIES))
						{
							$sCategories = $vCard->CATEGORIES->value;
							$aCategories = explode(',', $vCard->CATEGORIES->value);
							foreach($aCategories as $sCategory)
							{
								$sCategory = trim($sCategory);
								if (!empty($sCategory))
								{
									$this->aGroupItemsCache[$sName][$sCategory][$sItemId] = $sItemId;
								}
							}
						}

						if (!empty($sItemId) && (empty($sSearch) || stripos($sFullName, $sSearch) !== false ||
							stripos($sFirstName, $sSearch) !== false ||
							stripos($sLastName, $sSearch) !== false ||
							stripos($sNickName, $sSearch) !== false ||
							stripos($sTitle, $sSearch) !== false || $bFindEmail) &&
							(empty($sGroupId) || (!empty($sGroupId) && strpos($sCategories, $sGroupId) !== false)))
						{
							$oContactItem = new CContactListItem();
							$oContactItem->InitBySabreCardDAVCard($vCard);
							$oContactItem->Id = $sItemId;
							$oContactItem->ETag = $oItem->getETag();
							$aResult[] = $oContactItem;
							unset($oContactItem);
						}
					}
					unset($vCard);
				}
				$this->ContactsCache[$sName] = $aResult;
			}
		}

		return $aResult;
	}


	/**
	 * @param int $iUserId
	 * @param \afterlogic\DAV\CardDAV\AddressBook
	 * @return bool | array
	 */
	protected function initGroupItems($iUserId, $oAddressBook)
	{
		if ($this->Init($iUserId))
		{

			$aItems = $this->getObjectItems($iUserId, $oAddressBook);

			foreach ($aItems as $oItem)
			{
				$sItemId = $oItem->getName();
				$vCard = false;
				try
				{
					$vCard = \Sabre\VObject\Reader::read($oItem->get());
				}
				catch(Exception $ex)
				{
					CApi::Log('SABREDAV: Invalid VCard with Id='.$sItemId);
				}
				if ($vCard)
				{
					$sCategories = '';
					if (isset($vCard->CATEGORIES))
					{
						$sCategories = $vCard->CATEGORIES->value;
						$aCategories = explode(',', $vCard->CATEGORIES->value);
						foreach($aCategories as $sCategory)
						{
							$sCategory = trim($sCategory);
							if (!empty($sCategory))
							{
								$this->aGroupItemsCache[$oAddressBook->getName()][$sCategory][$sItemId] = $sItemId;
							}
						}
					}
				}
				unset($vCard);
			}
		}
	}

	public function ___qSortCallback($a, $b)
	{
		$sSortField = $GLOBALS['ItemsSortField'];
		$iSortOrder = $GLOBALS['ItemsSortOrder'];

		if ($a->{$sSortField} === $b->{$sSortField})
		{
			return 0;
		}
		else if (ESortOrder::ASC == $iSortOrder)
		{
			return ($a->{$sSortField} > $b->{$sSortField}) ? -1 : 1;
		}
		else
		{
			return ($a->{$sSortField} < $b->{$sSortField}) ? -1 : 1;
		}
	}


	/**
	 * @param array $aItems
	 * @param int $iSortField
	 * @param int $iSortOrder
	 */
	protected function sortItems(&$aItems, $iSortField, $iSortOrder)
	{
		$aMapSortField = array(
			EContactSortField::EMail => 'Email',
			EContactSortField::Name => 'Name',
			EContactSortField::Frequency => 'Frequency'
		);

		if (!isset($aMapSortField[$iSortField]))
		{
			return;
		}

		$GLOBALS['ItemsSortField'] = $aMapSortField[$iSortField];
		$GLOBALS['ItemsSortOrder'] = $iSortOrder;

		// Sort
		usort($aItems, array(&$this, '___qSortCallback'));

		unset($GLOBALS['ItemsSortField']);
		unset($GLOBALS['ItemsSortOrder']);
	}

	/**
	 * @param int $iUserId
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @return bool | array
	 */
	public function GetContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit)
	{
		$aResult = array();

		$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
		$aContactItems = $this->getObjectItems($iUserId, $oAddressBook);

		foreach ($aContactItems as $oItem)
		{
			$sItemId = $oItem->getName();
			$vCard = null;
			try
			{
				$vCard = \Sabre\VObject\Reader::read($oItem->get());
			}
			catch(Exception $ex)
			{
				CApi::Log('SABREDAV: Invalid VCard with Id='.$sItemId);
			}
			if (isset($vCard))
			{
				$oContactItem = new CContactListItem();
				$oContactItem->InitBySabreCardDAVCard($vCard);
				$oContactItem->Id = $oItem->getName();

				$aResult[] = $oContactItem;
				unset($oContactItem);
			}
			unset($vCard);
		}

		return array_slice($aResult, $iOffset, $iRequestLimit);
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
		$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
		$aResult = $this->getItems($iUserId, $oAddressBook, $sSearch, $sFirstCharacter, $iGroupId);
		$this->sortItems($aResult, $iSortField, $iSortOrder);

		return array_slice($aResult, $iOffset, $iRequestLimit);
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
		$iCount = 0;
		$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
		if (false !== $oAddressBook)
		{
			$iCount = count($this->getItems($iUserId, $oAddressBook, $sSearch, $sFirstCharacter, $iGroupId));
		}
		return $iCount;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param string $sContactId
	 * @return bool | array
	 */
	protected function getGroupItemsWithoutOrder($iUserId, $sSearch = '', $sFirstCharacter = '', $sContactId = '')
	{
		$aResult = array();
		$this->Init($iUserId);

		if (!empty($sContactId))
		{
			$oContact = $this->GetContactById($iUserId, $sContactId);
			if ($oContact)
			{
				foreach ($oContact->GroupsIds as $sGroupId)
				{
					$oContactItem = new CContactListItem();
					$oContactItem->Id = $sGroupId;
					$oContactItem->Name = $sGroupId;
					$oContactItem->IsGroup = true;

					if ($sSearch == '' || stripos($oContactItem->Name, $sSearch) !== false)
					{
						$aResult[] = $oContactItem;
					}
					unset($oContactItem);
				}
			}
		}
		else
		{
			$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
			if (false !== $oAddressBook)
			{
				$sName = $oAddressBook->getName();
				if (!isset($this->aGroupItemsCache[$sName]))
				{
					$this->getItems($iUserId, $oAddressBook);
				}

				if (isset($this->aGroupItemsCache[$sName]))
				{
					$aItems = $this->aGroupItemsCache[$sName];
					foreach ($aItems as $sKey => $aIds)
					{
						$aContactsIds = array();
						foreach($aIds as $sContactsId)
						{
							$aContactsIds[] = $sContactsId;
						}
						$oContactItem = new CContactListItem();
						$oContactItem->Id = $sKey;
						$oContactItem->Name = $sKey;
						$oContactItem->IsGroup = true;

						if (empty($sContactId) || !empty($sContactId) && in_array($sContactId, $aContactsIds))
						{
							if ($sSearch == '' || stripos($oContactItem->Name, $sSearch) !== false)
							{
								$aResult[] = $oContactItem;
							}
						}
						unset($oContactItem);
					}
				}
			}
		}
		return $aResult;
	}

	protected function searchContactItemsByEmail($sUserId, $sEmail, $oAddressBook)
	{
		$aResult = array();

		$aContactItems = $this->getItems($sUserId, $oAddressBook, $sEmail);
		foreach($aContactItems as $oContactItem)
		{
			$aResult[] = $oContactItem->Id;
		}

		return $aResult;
	}

	/**
	 * @param int $iUserId
	 * @param int $iSortField
	 * @param int $iSortOrder
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param string $sContactId
	 * @return bool | array
	 */
	public function GetGroupItems($iUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $sContactId)
	{
		$aResult = $this->getGroupItemsWithoutOrder($iUserId, $sSearch, $sFirstCharacter, $sContactId);
		$this->sortItems($aResult, $iSortField, $iSortOrder);
		return array_slice($aResult, $iOffset, $iRequestLimit);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @return int
	 */
	public function GetGroupItemsCount($iUserId, $sSearch, $sFirstCharacter)
	{
		$iCount = count($this->getGroupItemsWithoutOrder($iUserId, $sSearch, $sFirstCharacter));
		return $iCount;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @return bool | array
	 */
	public function GetSuggestContactItems($iUserId, $sSearch, $iRequestLimit)
	{
		$aResult = array();
		$this->Init($iUserId);

		$oDefaultAB = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
		$oCollectedAB = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME);

		$aCollectedContactItems = $this->getObjectItems($iUserId, $oCollectedAB);
		$aDefaultContactItems = $this->getObjectItems($iUserId, $oDefaultAB);

		$aContactItems = array_merge($aDefaultContactItems, $aCollectedContactItems);

		foreach ($aContactItems as $oItem)
		{
			$sItemId = $oItem->getName();
			$vCard = null;
			try
			{
				$vCard = \Sabre\VObject\Reader::read($oItem->get());
			}
			catch(Exception $ex)
			{
				CApi::Log('SABREDAV: Invalid VCard with Id='.$sItemId);
			}
			if (isset($vCard))
			{
				$oContactItem = new CContactListItem();
				$oContactItem->InitBySabreCardDAVCard($vCard);
				$oContactItem->Id = $oItem->getName();

				if (empty($sSearch) ||
					stripos($oContactItem->Name, $sSearch) !== false ||
					stripos($oContactItem->Email, $sSearch) !== false)
				{
					$aResult[] = $oContactItem;
				}
				unset($oContactItem);
			}

			unset($vCard);
		}

		$this->sortItems($aResult, EContactSortField::Frequency, ESortOrder::ASC);

		return array_slice($aResult, 0, $iRequestLimit);
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function UpdateContact($oContact)
	{
		$bResult = false;
		$iUserId = $oContact->IdUser;
		$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
		$oContactItem = $this->geItem($iUserId, $oAddressBook, $oContact->IdContact);
		if ($oContactItem)
		{
			$sData = $oContactItem->get();
/*
			$sETag = md5($sData);
			if ($oContact->ETag !== $sETag)
			{
 				throw new CApiBaseException(Errs::Sabre_PreconditionFailed);
			}
 */

			$vCard = \Sabre\VObject\Reader::read($sData);
			if ($vCard)
			{
				CApiContactsVCardHelper::UpdateVCardFromContact($oContact, $vCard);
				$oContactItem->put($vCard->serialize());
				$bResult = true;
			}
			unset($vCard);
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

		$sGroupId = null;
		$sGroupName = $oGroup->Name;
		if (!empty($oGroup->IdGroup))
		{
			$sGroupId = $oGroup->IdGroup;
		}
		else
		{
			$sGroupId = $oGroup->Name;
		}

		if (!empty($sGroupId))
		{
			$oAddressBook = $this->getAddressBook($oGroup->IdUser, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);

			if ($oAddressBook)
			{
				$aContactIds = $oGroup->ContactsIds;
				foreach ($aContactIds as $sContactId)
				{
					if ($oAddressBook->childExists($sContactId))
					{
						$oContact = $oAddressBook->GetChild($sContactId);
						$vCard = \Sabre\VObject\Reader::read($oContact->get());

						$sCategories = '';
						if (isset($vCard->CATEGORIES))
						{
							$sCategories = $vCard->CATEGORIES->value;
							$aCategories = explode(',', $vCard->CATEGORIES->value);
							$aResultCategories = array();
							foreach ($aCategories as $sCategory)
							{
								if ($sCategory === $sGroupId)
								{
									$aResultCategories[] = $sGroupName;
								}
								else
								{
									$aResultCategories[] = $sCategory;
								}
							}
							if (!in_array($sGroupId, $aResultCategories))
							{
								$aResultCategories[] = $sGroupName;
							}
							$sCategories = implode(',', array_unique($aResultCategories));
						}
						else
						{
							$vCard->add(new \Sabre\VObject\Property('CATEGORIES'));
							$sCategories = $sGroupName;
						}

						$vCard->CATEGORIES->value = $sCategories;
						$oContact->put($vCard->serialize());
					}
				}

				$aContactIds = $oGroup->DeletedContactsIds;
				foreach ($aContactIds as $sContactId)
				{
					if ($oAddressBook->childExists($sContactId))
					{
						$oContact = $oAddressBook->GetChild($sContactId);
						$vCard = \Sabre\VObject\Reader::read($oContact->get());

						$sCategories = '';
						if (isset($vCard->CATEGORIES))
						{
							$sCategories = $vCard->CATEGORIES->value;
							if (strpos($sCategories, $sGroupId) !== false)
							{
								$aCategories = explode(',', $vCard->CATEGORIES->value);
								$aResultCategories = array();
								foreach($aCategories as $sCategory)
								{
									if ($oGroup->IdGroup !== $sCategory)
									{
										$aResultCategories[] = $sCategory;
									}
								}
								$sCategories = implode(',', array_unique($aResultCategories));
							}
							if (empty($sCategories))
							{
								unset($vCard->CATEGORIES);
							}
							else
							{
								$vCard->CATEGORIES->value = $sCategories;
							}
							$oContact->put($vCard->serialize());
						}
					}
				}
			}
			$bResult = true;
		}
		return $bResult;
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function CreateContact($oContact)
	{
		$bResult = false;
		if (isset($oContact))
		{
			$this->Init($oContact->IdUser);
			$oAddressBook = $this->getAddressBook($oContact->IdUser, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
			if ($oAddressBook)
			{
				$sUUID = \Sabre\DAV\UUIDUtil::getUUID();
				if (empty($oContact->IdContact))
				{
					$oContact->IdContact = $sUUID. '.vcf';
				}

				$vCard = new \Sabre\VObject\Component('VCARD');
				CApiContactsVCardHelper::UpdateVCardFromContact($oContact, $vCard);

				$oAddressBook->createFile($oContact->IdContact, $vCard->serialize());
				$bResult = true;
			}

			$sEmail = '';
			switch($oContact->PrimaryEmail)
			{
				case EPrimaryEmailType::Home:
					$sEmail = $oContact->HomeEmail;
					break;
				case EPrimaryEmailType::Business:
					$sEmail = $oContact->BusinessEmail;
					break;
				case EPrimaryEmailType::Other:
					$sEmail = $oContact->OtherEmail;
					break;
				default:
					$sEmail = $oContact->HomeEmail;
					break;

			}
			$oAddressBook = $this->getAddressBook($oContact->IdUser, \afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME);
			$aContactsIds = $this->searchContactItemsByEmail($oContact->IdUser, $sEmail, $oAddressBook);

			$this->deleteContactsByAddressBook($oContact->IdUser, $aContactsIds, $oAddressBook);
		}

		return $bResult;
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function CreateGroup($oGroup)
	{
		return $this->UpdateGroup($oGroup);
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @param \afterlogic\DAV\CardDAV\AddressBook
	 * @return bool
	 */
	protected function deleteContactsByAddressBook($iUserId, $aContactsIds, $oAddressBook)
	{
		$this->Init($iUserId);

		if ($oAddressBook)
		{
			foreach($aContactsIds as $sContactId)
			{
				if ($oAddressBook->childExists($sContactId))
				{
					$oContact = $oAddressBook->GetChild($sContactId);
					$oContact->delete();
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
	public function DeleteContacts($iUserId, $aContactsIds)
	{
		$this->Init($iUserId);
		$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
		return $this->deleteContactsByAddressBook($iUserId, $aContactsIds, $oAddressBook);
	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupsIds
	 * @return bool
	 */
	public function DeleteGroups($iUserId, $aGroupsIds)
	{
		$this->Init($iUserId);

		$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
		$sName = $oAddressBook->getName();
		if ($oAddressBook)
		{
			$this->getItems($iUserId, $oAddressBook);

			foreach($aGroupsIds as $sGroupsId)
			{
				if (isset($this->aGroupItemsCache[$sName][$sGroupsId]))
				{
					$aContactIds = $this->aGroupItemsCache[$sName][$sGroupsId];
					foreach ($aContactIds as $sContactId)
					{
						if ($oAddressBook->childExists($sContactId))
						{
							$oContact = $oAddressBook->GetChild($sContactId);
							$vCard = \Sabre\VObject\Reader::read($oContact->get());

							$sCategories = '';
							if (isset($vCard->CATEGORIES))
							{
								$sCategories = $vCard->CATEGORIES->value;
								if (strpos($sCategories, $sGroupsId) !== false)
								{
									$aCategories = explode(',', $vCard->CATEGORIES->value);
									$aResultCategories = array();
									foreach($aCategories as $sCategory)
									{
										$sCategory = trim($sCategory);
										if ($sCategory !== $sGroupsId)
										{
											$aResultCategories[] = $sCategory;
										}
									}
									$sResultCategories = implode(',', $aResultCategories);
									if (empty($sResultCategories))
									{
										unset($vCard->CATEGORIES);
									}
									else
									{
										$vCard->CATEGORIES->value = $sResultCategories;
									}
									$oContact->put($vCard->serialize());
									$this->aContactItemsCache[$sName][$oContact->getName()] = $oContact;
								}
							}
						}
					}
					unset($this->aGroupItemsCache[$sName][$sGroupsId]);
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return bool
	 */
	public function UpdateSuggestTable($iUserId, $aEmails)
	{
		$bResult = false;
		$this->Init($iUserId);

		$oDefautltAB = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
		$oCollectedAB = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME);

		$aCollectedContactItems = $this->getObjectItems($iUserId, $oCollectedAB);

		foreach ($aEmails as $sEmail => $sName)
		{
			$mFindContact = false;
			foreach ($aCollectedContactItems as $oCollectedContactItem)
			{
				$vCard = \Sabre\VObject\Reader::read($oCollectedContactItem->get());
				if (isset($vCard->EMAIL))
				{
					foreach ($vCard->EMAIL as $oEmail)
					{
						if (strtolower($oEmail->value) == strtolower($sEmail))
						{
							$mFindContact = $oCollectedContactItem;
							break;
						}
					}
				}
				unset($vCard);
			}

			$aDefaultContactIds = $this->searchContactItemsByEmail($iUserId, $sEmail, $oDefautltAB);
			if (count($aDefaultContactIds) === 0)
			{
				if ($mFindContact === false)
				{
					$sUUID = \Sabre\DAV\UUIDUtil::getUUID();
					$oContact = new CContact();
					$oContact->FullName = $sName;
					$oContact->HomeEmail = $sEmail;
					$oContact->IdContact = $sUUID;

					$vCard = new \Sabre\VObject\Component('VCARD');
					$vCard->{'X-AFTERLOGIC-USE-FREQUENCY'} = '1';
					CApiContactsVCardHelper::UpdateVCardFromContact($oContact, $vCard);

					$oCollectedAB->createFile($sUUID . '.vcf', $vCard->serialize());
					$bResult = true;
				}
				else if ($mFindContact instanceof \Sabre\CardDAV\Card)
				{
					$vCard = \Sabre\VObject\Reader::read($mFindContact->get());
					if (isset($vCard->{'X-AFTERLOGIC-USE-FREQUENCY'}))
					{
						$vCard->{'X-AFTERLOGIC-USE-FREQUENCY'}->value = (int)$vCard->{'X-AFTERLOGIC-USE-FREQUENCY'}->value + 1;
					}
					else
					{
						$vCard->{'X-AFTERLOGIC-USE-FREQUENCY'} = '1';
					}
					$mFindContact->put($vCard->serialize());
					unset($vCard);
				}
			}
			else
			{
				foreach($aDefaultContactIds as $sDefaultContactId)
				{
					$mDefaultContact = $this->geItem($iUserId, $oDefautltAB, $sDefaultContactId);
					if ($mDefaultContact !== false)
					{
						$vCard = \Sabre\VObject\Reader::read($mDefaultContact->get());
						if (isset($vCard->{'X-AFTERLOGIC-USE-FREQUENCY'}))
						{
							$vCard->{'X-AFTERLOGIC-USE-FREQUENCY'}->value = (int)$vCard->{'X-AFTERLOGIC-USE-FREQUENCY'}->value + 1;
						}
						else
						{
							$vCard->{'X-AFTERLOGIC-USE-FREQUENCY'} = '1';
						}
						$mDefaultContact->put($vCard->serialize());
						unset($vCard);
					}
				}

				if ($mFindContact instanceof \Sabre\CardDAV\Card)
				{
					$mFindContact->delete();
				}
			}
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactIds
	 * @return bool
	 */
	public function DeleteContactsExceptIds($iUserId, $aContactIds)
	{
		$this->Init($iUserId);

		$oAddressBook = $this->getAddressBook($iUserId, \afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
		if ($oAddressBook)
		{
			$aContactItems = $this->getObjectItems($iUserId);
			foreach ($aContactItems as $oContactItem)
			{
				$vCard = \Sabre\VObject\Reader::read($oContactItem->get());
				if (isset($vCard->UID) && !in_array($vCard->UID->value, $aContactIds))
				{
					$oContactItem->delete();
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupIds
	 * @return bool
	 */
	public function DeleteGroupsExceptIds($iUserId, $aGroupIds)
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function FlushContacts()
	{
		return true;

	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function ClearAllContactsAndGroups($oAccount)
	{
		$bResult = false;
		$this->InitByAccount($oAccount);

		$oAddressBooks = new \Sabre\CardDAV\UserAddressBooks($this->Server->GetCarddavBackend(),
				$this->Principal);

		foreach ($oAddressBooks->getChildren() as $oAddressBook)
		{
			if ($oAddressBook instanceof \Sabre\CardDAV\AddressBook)
			{
				try
				{
					$oAddressBook->delete();
					$bResult = true;
				}
				catch (Exception $ex)
				{
					CApi::Log($ex->getTraceAsString());
					$bResult = false;
				}
			}
		}
		return $bResult;
	}
}