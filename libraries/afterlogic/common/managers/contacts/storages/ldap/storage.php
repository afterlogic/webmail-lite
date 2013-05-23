<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Contacts
 */
class CApiContactsLdapStorage extends CApiContactsStorage
{
	/**
	 * @var resource
	 */
	protected $rLink;

	/**
	 * @var resource
	 */
	protected $rSearch;

	/**
	 * @param CApiGlobalManager $oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('ldap', $oManager);

		$this->rLink = null;
		$this->rSearch = null;

		$this->inc('config');
		if (!class_exists('CApiContactsLdapHelper')) // TODO
		{
			$this->inc('helper');
		}

		CSession::$sSessionName = API_SESSION_WEBMAIL_NAME;
	}

	/**
	 * @param int $iUserId
	 * @return CApiContactsLdapConfig
	 */
	public function initConfigFromUserId($iUserId)
	{
		$oConfig = null;

		$sPabUrl = CApiContactsLdapHelper::GetPabUrlFromId($iUserId);

		if (!empty($sPabUrl))
		{
			$aPabUrl = api_Utils::LdapUriParse($sPabUrl);
			if (isset($aPabUrl['host'], $aPabUrl['port'], $aPabUrl['search_dn']))
			{
				$oConfig = new CApiContactsLdapConfig(
					$aPabUrl['host'], $aPabUrl['port'], $aPabUrl['search_dn'],
					CApi::GetConf('contacts.ldap-bind-dn', ''), CApi::GetConf('contacts.ldap-bind-password', ''));
			}
		}
		else
		{
			CApi::Log('LDAP: Empty Pub Uri', ELogLevel::Error);
		}

		if (!$oConfig)
		{
			CApi::Log('LDAP: Error config', ELogLevel::Error);
		}

		return $oConfig;
	}

	protected function connect($oConfig)
	{
		if (!extension_loaded('ldap'))
		{
			CApi::Log('LDAP: Can\'t load LDAP extension.', ELogLevel::Error);
			return false;
		}

		if ($oConfig && !is_resource($this->rLink))
		{
			CApi::Log('LDAP: connect to '.$oConfig->Host().':'.$oConfig->Port());
			$this->rLink = @ldap_connect($oConfig->Host(), $oConfig->Port());
			if ($this->rLink)
			{
				@register_shutdown_function(array(&$this, 'RegDisconnect'));

				@ldap_set_option($this->rLink, LDAP_OPT_PROTOCOL_VERSION, 3);
				@ldap_set_option($this->rLink, LDAP_OPT_REFERRALS, 0);

				CApi::Log('LDAP: bind = "'.$oConfig->BindDn().'" / "'.$oConfig->BindPassword().'"');
				if (!@ldap_bind($this->rLink, $oConfig->BindDn(), $oConfig->BindPassword()))
				{
					$this->validateLdapErrorOnFalse(false);
					$this->disconnect();
					return false;
				}
			}
			else
			{
				$this->validateLdapErrorOnFalse(false);
				return false;
			}

			if ($this->rLink)
			{
				if ('0' === CSession::Get('PabValidate', '0'))
				{
					CSession::Set('PabValidate', '1');

					$rSearchLink = @ldap_list($this->rLink, $oConfig->SearchDn(), '(objectClass=*)');
					if (!$rSearchLink)
					{
						CApi::Log('LDAP: Init PabUrl Entry');

						$sNewDn = $oConfig->SearchDn();
						$aDnExplode = ldap_explode_dn($sNewDn, 1);
						$sOu =  isset($aDnExplode[0]) ? trim($aDnExplode[0]) : '';
						if (!empty($sOu))
						{
							$aEntry = array(
								'ou' => $sOu,
								'objectClass' => array('top', 'organizationalUnit')
							);

							CApi::Log('LDAP: ldap_add(rLink, "'.$sNewDn.'", $aEntry');
							CApi::Log('LDAP: $aEntry = '.print_r($aEntry, true));
							$this->validateLdapErrorOnFalse(@ldap_add($this->rLink, $sNewDn, $aEntry));
						}
						else
						{
							CApi::Log('LDAP: empty Ou in SearchDn = '.$oConfig->SearchDn());
						}
					}
				}
			}
		}

		return true;
	}

	public function RegDisconnect()
	{
		static $isReg = false;
		if (!$isReg)
		{
			$this->disconnect();
			$isReg = true;
		}
	}

	protected function disconnect()
	{
		if (is_resource($this->rLink))
		{
			CApi::Log('LDAP: disconnect');
			@ldap_close($this->rLink);
			$this->rLink = null;
		}
	}

	protected function validateLdapErrorOnFalse($bReturn)
	{
		if (false === $bReturn)
		{
			CApi::Log('LDAP: error #'.@ldap_errno($this->rLink).': '.@ldap_error($this->rLink), ELogLevel::Error);
		}

		return $bReturn;
	}

	/**
	 * @param CApiContactsLdapConfig $oConfig
	 * @param string $sObjectFilter
	 * @return bool
	 */
	protected function search($iUserId, $sObjectFilter)
	{
		$oConfig = $this->initConfigFromUserId($iUserId);
		if ($oConfig && $this->connect($oConfig))
		{
			CApi::Log('LDAP: search = "'.$oConfig->SearchDn().'" / '.$sObjectFilter);
			$this->rSearch = @ldap_search($this->rLink, $oConfig->SearchDn(), $sObjectFilter);
			$this->validateLdapErrorOnFalse($this->rSearch);
			return is_resource($this->rSearch);
		}

		return false;
	}

	/**
	 * @param string $sField
	 * @param string $sOrder "asc" or "desc"
	 * @param int $iOffset = null
	 * @param int $iRequestLimit = null
	 * @return array
	 */
	protected function sortPaginate($sField, $sOrder = 'asc', $iOffset = null, $iRequestLimit = null)
	{
		$iTotalEntries = ldap_count_entries($this->rLink, $this->rSearch);

		$iEnd = 0;
		$iStart = 0;
		if ($iOffset === null || $iRequestLimit === null)
		{
			$iStart = 0;
			$iEnd = $iTotalEntries - 1;
		}
		else
		{
			$iStart = $iOffset;
			$iStart = ($iStart < 0) ? 0 : $iStart;

			$iEnd = $iStart + $iRequestLimit;
			$iEnd = ($iEnd > $iTotalEntries) ? $iTotalEntries : $iEnd;
		}

		ldap_sort($this->rLink, $this->rSearch, $sField);
		$aList = array();

		for ($iCurrent = 0, $rEntry = ldap_first_entry($this->rLink, $this->rSearch);
			$iCurrent < $iEnd && is_resource($rEntry);
			$iCurrent++, $rEntry = ldap_next_entry($this->rLink, $rEntry)
		)
		{
			if ($iCurrent >= $iStart)
			{
				array_push($aList, ldap_get_attributes($this->rLink, $rEntry));
			}
		}

		return ($sOrder === 'desc') ? array_reverse($aList) : $aList;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact | bool
	 */
	public function GetContactById($iUserId, $mContactId)
	{
		$oContact = false;
		if ($this->search($iUserId, '(&(objectClass='.CApiContactsLdapHelper::CONTACT_OBJECT_CLASS.')(un='.$mContactId.'))'))
		{
			$aResurn = ldap_get_entries($this->rLink, $this->rSearch);
			$this->validateLdapErrorOnFalse($aResurn);
			$oContact = CApiContactsLdapHelper::LdapContactPopulate($aResurn, $iUserId);
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
		$oContact = false;
		if ($this->search($iUserId, '(&(objectClass='.CApiContactsLdapHelper::CONTACT_OBJECT_CLASS.')(mail='.$sEmail.'))'))
		{
			$aResurn = ldap_get_entries($this->rLink, $this->rSearch);
			$this->validateLdapErrorOnFalse($aResurn);
			$oContact = CApiContactsLdapHelper::LdapContactPopulate($aResurn, $iUserId);
		}

		return $oContact;
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @return CContact | bool
	 */
	public function GetContactByStrId($iUserId, $sContactStrId)
	{
		return $this->GetContactById($iUserId, $sContactStrId);
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
		$aContactIds = false;
		if ($this->search($iUserId, '(&(objectClass='.CApiContactsLdapHelper::GROUP_OBJECT_CLASS.')(memberofpabgroup='.$mGroupId.'))'))
		{
			$aContactIds = array();
			$aResurn = ldap_get_entries($this->rLink, $this->rSearch);
			$this->validateLdapErrorOnFalse($aResurn);
			if ($aResurn)
			{
				$iIndex = 0;
				while (isset($aReturn[$iIndex]))
				{
					$aRow = $aReturn[$iIndex];
					if (is_array($aRow) && isset($aRow['un'][0]))
					{
						$aContactIds[] = $aRow['un'][0];
					}

					$iIndex++;
				}
			}
		}

		return $aContactIds;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupId
	 * @return CGroup
	 */
	public function GetGroupById($iUserId, $mGroupId)
	{
		$oGroup = false;
		if ($this->search($iUserId, '(&(objectClass='.CApiContactsLdapHelper::GROUP_OBJECT_CLASS.')(un='.$mGroupId.'))'))
		{
			$aResurn = ldap_get_entries($this->rLink, $this->rSearch);
			$this->validateLdapErrorOnFalse($aResurn);
			$oGroup = CApiContactsLdapHelper::LdapGroupPopulate($aResurn, $iUserId);

			$this->updateGroupContactIds($oGroup);
		}

		return $oGroup;
	}

	/**
	 * @param CGroup $oGroup
	 */
	protected function updateGroupContactIds(&$oGroup)
	{
		if ($oGroup)
		{
			$aContactIds = false;
			if ($this->search($oGroup->IdGroup, '(&(objectClass='.CApiContactsLdapHelper::CONTACT_OBJECT_CLASS.')(memberofpabgroup='.$oGroup->IdGroup.'))'))
			{
				$aResurn = ldap_get_entries($this->rLink, $this->rSearch);
				$this->validateLdapErrorOnFalse($aResurn);
				if ($aResurn)
				{
					$iIndex = 0;
					$aContactIds = array();
					while (isset($aResurn[$iIndex]))
					{
						$aRow = $aResurn[$iIndex];
						if (is_array($aRow) && isset($aRow['un'][0]))
						{
							$aContactIds[] = $aRow['un'][0];
						}

						$iIndex++;
					}
				}
			}

			if (is_array($aContactIds))
			{
				$oGroup->ContactsIds = $aContactIds;
				return true;
			}
		}

		return false;
	}

	/**
	 * @param int $iUserId
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @return array | bool
	 */
	public function GetContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit)
	{
		$aContacts = false;
		if ($this->search($iUserId, '(objectClass='.CApiContactsLdapHelper::CONTACT_OBJECT_CLASS.')'))
		{
			$iTotalEntries = ldap_count_entries($this->rLink, $this->rSearch);
			if ($iOffset === null || $iRequestLimit === null)
			{
				# fetch all in one page
				$iStart = 0;
				$iEnd = $iTotalEntries - 1;
			}
			else
			{
				$iStart = $iOffset;
				$iStart = ($iStart < 0) ? 0 : $iStart;

				$iEnd = $iStart + $iRequestLimit;
				$iEnd = ($iEnd > $iTotalEntries - 1) ? $iTotalEntries - 1 : $iEnd;
			}

			$aList = array();
			for ($iCurrent = 0, $rEntry = ldap_first_entry($this->rLink, $this->rSearch);
				$iCurrent <= $iEnd && is_resource($rEntry);
				$iCurrent++, $rEntry = ldap_next_entry($this->rLink, $rEntry))
			{
				if ($iCurrent >= $iStart)
				{
					array_push($aList, ldap_get_attributes($this->rLink, $rEntry));
				}
			}

			$aContacts = array();
			if (0 < count($aList))
			{
				$aContacts = array();
				if (0 < count($aReturn))
				{
					foreach ($aReturn as $aItem)
					{
						$oContactItem = new CContactListItem();
						$oContactItem->InitByLdapRowWithType('contact', $aItem);
						$aContacts[] = $oContactItem;
						unset($oContactItem);
					}
				}
			}
		}

		return $aContacts;
	}

	/**
	 * @param string $mUserId
	 * @param int $iSortField
	 * @param int $iSortOrder
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param string $mGroupId
	 * @return bool | array
	 */
	public function GetContactItems($mUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $mGroupId)
	{
		$aContacts = false;
		$sFirstCharacterFilter = empty($sFirstCharacter) ? '' : '(|(cn='.$sFirstCharacter.'*)(mail='.$sFirstCharacter.'*))';

		$sFilter = '(objectClass='.CApiContactsLdapHelper::CONTACT_OBJECT_CLASS.')';
		$sFilter = empty($sFirstCharacterFilter) ? $sFilter : '(&'.$sFirstCharacterFilter.$sFilter.')';
		$sFilter = empty($mGroupId) ? $sFilter : '(&'.$sFilter.'(memberofpabgroup='.$mGroupId.'))';

		if (0 < strlen($sSearch))
		{
			$sFilter = '(&(|(cn=*'.$sSearch.'*)(mail=*'.$sSearch.'*))'.$sFilter.')';
		}

		if ($this->search($mUserId, $sFilter))
		{
			$aReturn = $this->sortPaginate(
				(EContactSortField::EMail === $iSortField) ? 'mail' : 'cn',
				(ESortOrder::ASC === $iSortOrder) ? 'asc' : 'desc', $iOffset, $iRequestLimit);

			$this->validateLdapErrorOnFalse($aReturn);
			if ($aReturn && is_array($aReturn))
			{
				$aContacts = array();
				if (0 < count($aReturn))
				{
					foreach ($aReturn as $aItem)
					{
						$oContactItem = new CContactListItem();
						$oContactItem->InitByLdapRowWithType('contact', $aItem);
						$aContacts[] = $oContactItem;
						unset($oContactItem);
					}
				}
			}
		}

		return $aContacts;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param int $mGroupId
	 * @return int
	 */
	public function GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $mGroupId)
	{
		$iResult = 0;
		$sFirstCharacterFilter = empty($sFirstCharacter) ? '' : '(|(cn='.$sFirstCharacter.'*)(mail='.$sFirstCharacter.'*))';

		$sFilter = '(objectClass='.CApiContactsLdapHelper::CONTACT_OBJECT_CLASS.')';
		$sFilter = empty($sFirstCharacterFilter) ? $sFilter : '(&'.$sFirstCharacterFilter.$sFilter.')';
		$sFilter = empty($mGroupId) ? $sFilter : '(&'.$sFilter.'(memberofpabgroup='.$mGroupId.'))';

		if (0 < strlen($sSearch))
		{
			$sFilter = '(&(|(cn=*'.$sSearch.'*)(mail=*'.$sSearch.'*))'.$sFilter.')';
		}

		if ($this->search($iUserId, $sFilter))
		{
			$iCount = ldap_count_entries($this->rLink, $this->rSearch);
			$this->validateLdapErrorOnFalse($iCount);
			if (false !== $iCount)
			{
				$iResult = $iCount;
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
	 * @param mixed $mContactId
	 * @return bool | array
	 */
	public function GetGroupItems($iUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $mContactId)
	{
		$aGroups = false;
		$sFirstCharacterFilter = empty($sFirstCharacter) ? '' : '(cn='.$sFirstCharacter.'*)';

		$sFilter = '(objectClass='.CApiContactsLdapHelper::GROUP_OBJECT_CLASS.')';
		$sFilter = empty($sFirstCharacterFilter) ? $sFilter : '(&'.$sFirstCharacterFilter.$sFilter.')';

		if (!empty($mContactId))
		{
			$oContact = $this->GetContactById($iUserId, $mContactId);
			if ($oContact)
			{
				$aGroupIds = $oContact->GroupsIds;
				if (is_array($aGroupIds) && 0 < count($aGroupIds))
				{
					if (1 === count($aGroupIds))
					{
						$sAdd = '(un='.$aGroupIds[0].')';
					}
					else
					{
						$aAdd = array();
						foreach ($aGroupIds as $sGroupId)
						{
							$aAdd[] = '(un='.$sGroupId.')';
						}

						$sAdd = '(|'.implode('', $aAdd).')';
					}

					$sFilter = '(&'.$sAdd.$sFilter.')';
				}
				else
				{
					return array();
				}
			}
		}

		if (0 < strlen($sSearch))
		{
			$sFilter = '(&(cn=*'.$sSearch.'*)'.$sFilter.')';
		}

		if ($this->search($iUserId, $sFilter))
		{
			$aReturn = $this->sortPaginate('cn',
				(ESortOrder::ASC === $iSortOrder) ? 'asc' : 'desc', $iOffset, $iRequestLimit);

			$this->validateLdapErrorOnFalse($aReturn);
			if ($aReturn && is_array($aReturn))
			{
				$aGroups = array();
				if (0 < count($aReturn))
				{
					foreach ($aReturn as $aItem)
					{
						$oGroupItem = new CContactListItem();
						$oGroupItem->InitByLdapRowWithType('group', $aItem);
						$aGroups[] = $oGroupItem;
						unset($oGroupItem);
					}
				}
			}
		}

		return $aGroups;
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
		$sFirstCharacterFilter = empty($sFirstCharacter) ? '' : '(cn='.$sFirstCharacter.'*)';

		$sFilter = '(objectClass='.CApiContactsLdapHelper::GROUP_OBJECT_CLASS.')';
		$sFilter = empty($sFirstCharacterFilter) ? $sFilter : '(&'.$sFirstCharacterFilter.$sFilter.')';

		if (0 < strlen($sSearch))
		{
			$sFilter = '(&(cn=*'.$sSearch.'*)'.$sFilter.')';
		}

		if ($this->search($iUserId, $sFilter))
		{
			$iCount = ldap_count_entries($this->rLink, $this->rSearch);
			$this->validateLdapErrorOnFalse($iCount);
			if (false !== $iCount)
			{
				$iResult = $iCount;
			}
		}

		return $iResult;
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function UpdateContact($oContact)
	{
		$bReturn = false;
		$oConfig = $this->initConfigFromUserId($oContact->IdUser);
		if ($oConfig && $this->connect($oConfig))
		{
			$oCurrentContact = $this->GetContactById($oContact->IdUser, $oContact->IdContact);
			if ($oCurrentContact)
			{
				$aEntry = CApiContactsLdapHelper::GetEntryFromContact($oContact, true);

				CApi::Log('LDAP: Update Contact: ldap_modify ("un='.$oContact->IdContact.','.$oConfig->SearchDn().'", $aEntry);');
				CApi::Log('LDAP: $aEntry = '.print_r($aEntry, true));

				$bReturn = @ldap_modify($this->rLink, 'un='.$oContact->IdContact.','.$oConfig->SearchDn(), $aEntry);
				$this->validateLdapErrorOnFalse($bReturn);
			}
		}

		return $bReturn;
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function UpdateGroup($oGroup)
	{
		$bReturn = false;
		$oConfig = $this->initConfigFromUserId($oGroup->IdUser);
		if ($oConfig && $this->connect($oConfig))
		{
			$oCurrentGroup = $this->GetGroupById($oGroup->IdUser, $oGroup->IdGroup);
			if ($oCurrentGroup)
			{
				$aContactsIds = $oGroup->ContactsIds;
				$aCurrentContactsIds = $oCurrentGroup->ContactsIds;

				$aIdsToRemove = array_diff($aCurrentContactsIds, $aContactsIds);
				$aIdsToAdd = array_diff($aContactsIds, $aCurrentContactsIds);

				if (0 < count($aIdsToAdd))
				{
					foreach($aIdsToAdd as $sContactId)
					{
						$oContactToAddFromGroup = $this->GetContactById($oGroup->IdUser, $sContactId);
						if ($oContactToAddFromGroup)
						{
							$aGroupsIds = $oContactToAddFromGroup->GroupsIds;
							$aGroupsIds[] = $oGroup->IdGroup;
							$oContactToAddFromGroup->GroupsIds = array_unique($aGroupsIds);
							$this->UpdateContact($oContactToAddFromGroup);
						}
					}
				}

				if (0 < count($aIdsToRemove))
				{
					foreach($aIdsToRemove as $sContactId)
					{
						$oContactToRemoveFromGroup = $this->GetContactById($oGroup->IdUser, $sContactId);
						if ($oContactToRemoveFromGroup)
						{
							$aNewGroupsIds = array();
							$aGroupsIds = $oContactToRemoveFromGroup->GroupsIds;
							foreach ($aGroupsIds as $sGroupId)
							{
								if ($oGroup->IdGroup !== $sGroupId)
								{
									$aNewGroupsIds[] = $sGroupId;
								}
							}
							$oContactToRemoveFromGroup->GroupsIds = $aNewGroupsIds;
							$this->UpdateContact($oContactToRemoveFromGroup);
						}
					}
				}

				$aEntry = CApiContactsLdapHelper::GetEntryFromGroup($oGroup, true);

				CApi::Log('LDAP: Update Group: ldap_modify ("un='.$oGroup->IdGroup.','.$oConfig->SearchDn().'", $aEntry);');
				CApi::Log('LDAP: $aEntry = '.print_r($aEntry, true));

				$bReturn = @ldap_modify($this->rLink, 'un='.$oGroup->IdGroup.','.$oConfig->SearchDn(), $aEntry);
				$this->validateLdapErrorOnFalse($bReturn);
			}
		}

		return $bReturn;
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function CreateContact($oContact)
	{
		$bReturn = false;
		$oConfig = $this->initConfigFromUserId($oContact->IdUser);
		if ($oConfig && $this->connect($oConfig))
		{
			$sUid = CApiContactsLdapHelper::CreateNewContactUn($oContact);
			$oContact->IdContact = $sUid;
			$oContact->IdContactStr = $oContact->IdContact;

			$aEntry = CApiContactsLdapHelper::GetEntryFromContact($oContact);

			CApi::Log('LDAP: Add Group: ldap_add ("un='.$sUid.','.$oConfig->SearchDn().'", $aEntry);');
			CApi::Log('LDAP: $aEntry = '.print_r($aEntry, true));

			$bReturn = @ldap_add($this->rLink, 'un='.$sUid.','.$oConfig->SearchDn(), $aEntry);
			$this->validateLdapErrorOnFalse($bReturn);
		}

		return $bReturn;
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function CreateGroup($oGroup)
	{
		$bReturn = false;
		$oConfig = $this->initConfigFromUserId($oGroup->IdUser);
		if ($oConfig && $this->connect($oConfig))
		{
			$sUid = CApiContactsLdapHelper::CreateNewGroupUn($oGroup);
			$oGroup->IdGroup = $sUid;
			$oGroup->IdGroupStr = $oGroup->IdGroup;

			$aEntry = CApiContactsLdapHelper::GetEntryFromGroup($oGroup);

			CApi::Log('LDAP: Add Group: ldap_add ("un='.$sUid.','.$oConfig->SearchDn().'", $aEntry);');
			CApi::Log('LDAP: $aEntry = '.print_r($aEntry, true));

			$bReturn = @ldap_add($this->rLink, 'un='.$sUid.','.$oConfig->SearchDn(), $aEntry);
			$this->validateLdapErrorOnFalse($bReturn);
		}

		return $bReturn;
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
	public function DeleteContacts($iUserId, $aContactsIds)
	{
		$bReturn = false;
		if (is_array($aContactsIds) && 0 < count($aContactsIds))
		{
			$oConfig = $this->initConfigFromUserId($iUserId);
			if ($oConfig && $this->connect($oConfig))
			{
				foreach ($aContactsIds as $sContactId)
				{
					CApi::Log('LDAP: Delete Contact: @ldap_delete ("un='.$sContactId.','.$oConfig->SearchDn().'");');
					$bReturn = @ldap_delete($this->rLink, 'un='.$sContactId.','.$oConfig->SearchDn());
					$this->validateLdapErrorOnFalse($bReturn);
					if (!$bReturn)
					{
						break;
					}
				}
			}
		}

		return $bReturn;
	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupsIds
	 * @return bool
	 */
	public function DeleteGroups($iUserId, $aGroupsIds)
	{
		$bReturn = false;
		if (is_array($aGroupsIds) && 0 < count($aGroupsIds))
		{
			$oConfig = $this->initConfigFromUserId($iUserId);
			if ($oConfig && $this->connect($oConfig))
			{
				foreach ($aGroupsIds as $sGroupId)
				{
					CApi::Log('LDAP: Delete Group: @ldap_delete ("un='.$sGroupId.','.$oConfig->SearchDn().'");');
					$bReturn = @ldap_delete($this->rLink, 'un='.$sGroupId.','.$oConfig->SearchDn());
					$this->validateLdapErrorOnFalse($bReturn);
					if (!$bReturn)
					{
						break;
					}
				}
			}
		}

		return $bReturn;
	}
}
