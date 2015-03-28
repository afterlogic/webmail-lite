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
class CApiContactsmainLdapStorage extends CApiContactsmainStorage
{
	/**
	 * @var string
	 */
	private $sContactUidFieldName;

	/**
	 * @var string
	 */
	private $sGroupUidFieldName;

	/**
	 * @var string
	 */
	private $sContactObjectClass;

	/**
	 * @var string
	 */
	private $sGroupObjectClass;

	/**
	 * @var string
	 */
	private $sEmailFieldName;

	/**
	 * @var string
	 */
	private $sContactNameFieldName;

	/**
	 * @var string
	 */
	private $sGroupNameFieldName;

	/**
	 * @param CApiGlobalManager $oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('ldap', $oManager);

		$this->sContactObjectClass = strtolower(CApi::GetConf('contacts.ldap.contact-object-class', 'pabPerson'));
		$this->sGroupObjectClass = strtolower(CApi::GetConf('contacts.ldap.group-object-class', 'pabGroup'));
		$this->sEmailFieldName = strtolower(CApi::GetConf('contacts.ldap.email-field-name', 'mail'));
		$this->sContactUidFieldName = strtolower(CApi::GetConf('contacts.ldap.contact-uid-field-name', 'un'));
		$this->sGroupUidFieldName = strtolower(CApi::GetConf('contacts.ldap.group-uid-field-name', 'un'));
		$this->sContactNameFieldName = strtolower(CApi::GetConf('contacts.ldap.contact-name-field-name', 'cn'));
		$this->sGroupNameFieldName = strtolower(CApi::GetConf('contacts.ldap.group-name-field-name', 'cn'));
	}

	/**
	 * @staticvar CLdapConnector|null $oLdap
	 * @param CAccount $oAccount
	 * @return CLdapConnector|bool
	 */
	private function Ldap($oAccount)
	{
//		if ($oAccount)
//		{
//			// TODO
//			$aCustomFields = $oAccount->CustomFields;
//			$aCustomFields['LdapPabUrl'] = 'ldap://192.168.0.197:389/ou=TestUser2,ou=PAB,dc=example,dc=com';
//			$aCustomFields['LdapPabUrl'] = 'ldap://jes7dir.netvision.net.il:389/ou=24606995,ou=People,o=netvision.net.il,o=NVxSP,o=pab';
//			$oAccount->CustomFields = $aCustomFields;
//		}

		static $aLdap = array();
		if (!$oAccount || !isset($oAccount->CustomFields) || empty($oAccount->CustomFields['LdapPabUrl']))
		{
			return false;
		}

		$sPabUrl = $oAccount->CustomFields['LdapPabUrl'];
		$aPabUrl = api_Utils::LdapUriParse($sPabUrl);

		if (isset($aLdap[$sPabUrl]) && $aLdap[$sPabUrl])
		{
			return $aLdap[$sPabUrl];
		}

		if (!extension_loaded('ldap'))
		{
			CApi::Log('LDAP: Can\'t load LDAP extension.', ELogLevel::Error);
			return false;
		}

		if (!class_exists('CLdapConnector'))
		{
			CApi::Inc('common.ldap');
		}

		$oLdap = new CLdapConnector($aPabUrl['search_dn']);
		$oLdap = $oLdap->Connect(
			(string) $aPabUrl['host'],
			(int) $aPabUrl['port'],
			(string) CApi::GetConf('contacts.ldap.bind-dn', ''),
			(string) CApi::GetConf('contacts.ldap.bind-password', '')
		) ? $oLdap : false;

		if ($oLdap)
		{
			if (!$oLdap->Search('(objectClass=*)'))
			{
				CApi::Log('LDAP: Init PabUrl Entry');

				$sNewDn = $oLdap->GetSearchDN();
				$aDnExplode = ldap_explode_dn($sNewDn, 1);
				$sOu =  isset($aDnExplode[0]) ? trim($aDnExplode[0]) : '';

				$aPabUrlEntry = CApi::GetConf('contacts.ldap.pab-url-entry', array(
					'objectClass' => array('top', 'organizationalUnit')
				));

				if (isset($aPabUrlEntry['objectClass']))
				{
					$aPabUrlEntry['ou'] = $sOu;
					if (0 < strlen($sOu))
					{
						if (!$oLdap->Add('', $aPabUrlEntry))
						{
							$oLdap = false;
						}
					}
					else
					{
						CApi::Log('LDAP: empty Ou in SearchDn = '.$sNewDn);
						$oLdap = false;
					}
				}
				else
				{
					CApi::Log('LDAP: pab-url-entry format error');
					CApi::Log(print_r($aPabUrlEntry, true));

					$oLdap = false;
				}
			}
		}

		$aLdap[$sPabUrl] = $oLdap;

		return $oLdap;
	}

	/**
	 * @param int $iUserId
	 * @return CAccount|bool
	 */
	private function getAccountFromUserId($iUserId)
	{
		/* @var $oApiUsersManager CApiUsersManager */
		$oApiUsersManager = CApi::Manager('users');

		if (0 < $iUserId && $oApiUsersManager)
		{
			$iIdAccount = $oApiUsersManager->GetDefaultAccountId($iUserId);
			if (0 < $iIdAccount)
			{
				$oDefAccount = $oApiUsersManager->GetAccountById($iIdAccount);
			}

			if ($oDefAccount)
			{
				return $oDefAccount;
			}
		}

		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @return CContactListItem|bool
	 */
	private function populateContactItem($oAccount, $aLdapData)
	{
		$oContactItem = false;
		if ($aLdapData && isset($aLdapData[$this->sContactUidFieldName][0]))
		{
			$oContactItem = new CContactListItem();
			$oContactItem->Id = (string) $aLdapData[$this->sContactUidFieldName][0];
			$oContactItem->IdStr = $oContactItem->Id;
			$oContactItem->IsGroup = false;

			$oContactItem->Name = isset($aLdapData[$this->sContactNameFieldName][0]) ? (string) $aLdapData[$this->sContactNameFieldName][0] :
				(isset($aLdapData['cn'][0]) ? (string) $aLdapData['cn'][0] : '');

			$oContactItem->Email = isset($aLdapData[$this->sEmailFieldName][0]) ? (string) $aLdapData[$this->sEmailFieldName][0] :
				(isset($aLdapData['mail'][0]) ? (string) $aLdapData['mail'][0] :
					(isset($aLdapData['homeemail'][0]) ? (string) $aLdapData['homeemail'][0] : ''));

			$oContactItem->UseFriendlyName = true;

			if ('-' === $oContactItem->Name)
			{
				$oContactItem->Name = '';
			}
		}
		
		return $oContactItem;
	}

	/**
	 * @param CAccount $oAccount
	 * @return CContactListItem|bool
	 */
	private function populateGroupItem($oAccount, $aLdapData)
	{
		$oContactItem = false;
		if ($aLdapData && isset($aLdapData[$this->sGroupUidFieldName][0], $aLdapData['cn'][0]))
		{
			$oContactItem = new CContactListItem();
			$oContactItem->Id = (string) $aLdapData[$this->sGroupUidFieldName][0];
			$oContactItem->IdStr = $oContactItem->Id;
			$oContactItem->IsGroup = true;
			$oContactItem->Name = isset($aLdapData[$this->sGroupNameFieldName][0]) ? (string) $aLdapData[$this->sGroupNameFieldName][0] :
				(isset($aLdapData['cn'][0]) ? (string) $aLdapData['cn'][0] : '');
			$oContactItem->UseFriendlyName = true;
		}

		return $oContactItem;
	}

	/**
	 * @return array
	 */
	private static function contactObjectMap()
	{
		$aMap = array(
			'givenName' => 'FirstName',
			'sn' => 'LastName',
			'cn' => 'FullName',

			'mail' => 'HomeEmail',
			'homeemail' => 'HomeEmail',

			'street' => 'HomeStreet',
			'l' => 'HomeCity',
			'st' => 'HomeState',
			'postalcode' => 'HomeZip',
			'co' => 'HomeCountry',
			'facsimileTelephoneNumber' => 'HomeFax',
			'mobile' => 'HomeMobile',
			'homePhone' => 'HomePhone',
			'labeledUri' => 'HomeWeb',

			'description' => 'Notes',
		);

		return $aMap;
	}

	/**
	 * @param CAccount $oAccount
	 * @return CContact|bool
	 */
	private function populateContact($oAccount, $aLdapData)
	{
		$oContact = false;
		if ($aLdapData && isset($aLdapData[$this->sContactUidFieldName][0]))
		{
			CApi::LogObject($aLdapData);

			$oContact = new CContact();
			$oContact->IdUser = $oAccount->IdUser;
			$oContact->IdTenant = $oAccount->IdTenant;
			$oContact->IdDomain = $oAccount->IdDomain;
			$oContact->IdContact = (string) $aLdapData[$this->sContactUidFieldName][0];
			$oContact->IdContactStr = $oContact->IdContact;
			$oContact->UseFriendlyName = true;

			// TODO
			if (!empty($aLdapData['sn'][0]) && '-' === $aLdapData['sn'][0])
			{
				$aLdapData['sn'][0] = '';
			}

			$aMap = $this->contactObjectMap();
			$aMap = array_change_key_case($aMap, CASE_LOWER);
			$aLdapDataLower = array_change_key_case($aLdapData, CASE_LOWER);

			foreach ($aLdapDataLower as $sKey => $mRow)
			{
				if (isset($aMap[$sKey]) && isset($oContact->{$aMap[$sKey]}) && 0 === strlen($oContact->{$aMap[$sKey]}))
				{
					$oContact->{$aMap[$sKey]} = isset($mRow[0]) ? $mRow[0] : '';
				}
			}

			$sDateOfBirth = isset($aLdapDataLower['dateofbirth'][0]) ? (string) $aLdapDataLower['dateofbirth'][0] : '';
			if (strlen($sDateOfBirth) > 0)
			{
				$aDateOfBirth = explode('/', $sDateOfBirth, 3);
				if (3 === count($aDateOfBirth) && isset($aDateOfBirth[0], $aDateOfBirth[1], $aDateOfBirth[2]))
				{
					$oContact->BirthdayDay = is_numeric($aDateOfBirth[0]) ? (int) $aDateOfBirth[0] : 0;
					$oContact->BirthdayMonth = is_numeric($aDateOfBirth[1]) ? (int) $aDateOfBirth[1] : 0;
					$oContact->BirthdayYear = is_numeric($aDateOfBirth[2]) ? (int) $aDateOfBirth[2] : 0;
				}
			}

			if (isset($aLdapDataLower['memberofpabgroup']))
			{
				unset($aLdapDataLower['memberofpabgroup']['count']);

				if (is_array($aLdapDataLower['memberofpabgroup']))
				{
					$aGroupsIds = array();
					$aMemberOfPabGroup = array_values($aLdapDataLower['memberofpabgroup']);
					foreach ($aMemberOfPabGroup as $sGroupId)
					{
						if (!empty($sGroupId))
						{
							$aGroupsIds[] = (string) $sGroupId;
						}
					}

					$oContact->GroupsIds = $aGroupsIds;
				}
			}
		}

		return $oContact;
	}

	/**
	 * @param CAccount $oAccount
	 * @return CGroup|bool
	 */
	private function populateGroup($oAccount, $aLdapData)
	{
		$oGroup = false;
		if ($aLdapData && isset($aLdapData[$this->sGroupUidFieldName][0], $aLdapData['cn'][0]))
		{
			$oGroup = new CGroup();
			$oGroup->IdUser = $oAccount->IdUser;
			$oGroup->IdGroup = (string) $aLdapData[$this->sGroupUidFieldName][0];
			$oGroup->IdGroupStr = $oGroup->IdGroup;
			$oGroup->Name = isset($aLdapData[$this->sGroupNameFieldName][0]) ? (string) $aLdapData[$this->sGroupNameFieldName][0] :
				(isset($aLdapData['cn'][0]) ? (string) $aLdapData['cn'][0] : '');

			$oGroup->IsOrganization = false;
		}

		return $oGroup;
	}

	/**
	 * @param CAccount $oAccount
	 * @param CContact $oContact
	 * @param bool $bIsUpdate
	 * @return array
	 */
	private function getEntryFromContact($oAccount, &$oContact, $bIsUpdate)
	{
		$sId = $oContact->IdContact;
		if (!$bIsUpdate)
		{
			$sId = 'contact'.md5($oContact->FullName.$oContact->ViewEmail.time().rand(1000, 9999));
		}

		$oContact->IdContact = (string) $sId;
		$oContact->IdUser = $oAccount->IdUser;
		$oContact->IdContactStr = $oContact->IdContact;
		$oContact->IdDomain = $oAccount->IdDomain;
		$oContact->IdTenant = $oAccount->IdTenant;

		$aE = array(
			'sn' => '',
			'cn' => '',
			'objectClass' => array(
				'top',
				'person',
				'pabperson', //TODO
				'organizationalPerson',
				'inetOrgPerson'
			)
		);

		$aE[$this->sContactUidFieldName] = $oContact->IdContact;

		$aMap = $this->contactObjectMap();
		$aMapCache = array();
		foreach ($aMap as $sEntryKey => $sObjectKey)
		{
			if (isset($oContact->{$sObjectKey}) && strlen($oContact->{$sObjectKey}) > 0)
			{
				if (!isset($aMapCache[$sObjectKey]))
				{
					$aE[$sEntryKey] = $oContact->{$sObjectKey};
					$aMapCache[$sObjectKey] = true;
				}
			}
		}

		$aE['memberofpabgroup'] = array();
		$aGroupsIds = $oContact->GroupsIds;
		if (is_array($aGroupsIds) && 0 < count($aGroupsIds))
		{
			foreach($aGroupsIds as $mGroupId)
			{
				$aE['memberofpabgroup'][] = (string) $mGroupId;
			}
		}

		if ($bIsUpdate)
		{
			unset($aE['objectClass']);
		}
		else
		{
			if (0 === count($aE['memberofpabgroup']))
			{
				unset($aE['memberofpabgroup']);
			}
		}

		CApi::Plugin()->RunHook('api-ldap-get-entry-from-contact', array($oAccount, &$oContact, &$aE, $bIsUpdate));

		if (empty($aE['sn']))
		{
			$aE['sn'] = '-';
		}
		
		return $aE;
	}

	/**
	 * @param CAccount $oAccount
	 * @param CGroup $oGroup
	 * @param bool $bIsUpdate
	 * @return array
	 */
	private function getEntryFromGroup($oAccount, &$oGroup, $bIsUpdate)
	{
		$sId = $oGroup->IdGroup;
		if (!$bIsUpdate)
		{
			$sId = 'group'.md5($oGroup->Name.time().rand(1000, 9999));
		}

		$oGroup->IdGroup = (string) $sId;
		$oGroup->IdGroupStr = $oGroup->IdGroup;
		$oGroup->IdUser = $oAccount->IdUser;

		$aE = array(
			'cn' => $oGroup->Name,
			'objectClass' => array(
				'top',
				'pabGroup' // TODO
			)
		);

		$aE[$this->sGroupUidFieldName] = $oGroup->IdGroup;

		if ($bIsUpdate)
		{
			unset($aE['objectClass']);
		}

		CApi::Plugin()->RunHook('api-ldap-get-entry-from-group', array($oAccount, &$oGroup, &$aE, $bIsUpdate));

		return $aE;
	}

	/**
	 * @param mixed $mTypeId
	 * @param int $mContactId
	 * @return CContact | bool
	 */
	public function GetContactByTypeId($mTypeId, $mContactId)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact | bool
	 */
	public function GetContactById($iUserId, $mContactId)
	{
		$oContact = false;
		$oAccount = $this->getAccountFromUserId($iUserId);
		$oLdap = $this->Ldap($oAccount);

		if ($oLdap && $oLdap->Search('(&(objectClass='.$this->sContactObjectClass.')('.$this->sContactUidFieldName.'='.$mContactId.'))'))
		{
			$oContact = $this->populateContact($oAccount, $oLdap->ResultItem());
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
		$oAccount = $this->getAccountFromUserId($iUserId);
		$oLdap = $this->Ldap($oAccount);

		if ($oLdap && $oLdap->Search('(&(objectClass='.$this->sContactObjectClass.')('.$this->sEmailFieldName.'='.$sEmail.'))'))
		{
			$oContact = $this->populateContact($oAccount, $oLdap->ResultItem());
		}

		return $oContact;
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @param int $iSharedTenantId = null
	 * @return CContact | bool
	 */
	public function GetContactByStrId($iUserId, $sContactStrId, $iSharedTenantId = null)
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
	 * @todo
	 * @param int $iUserId
	 * @return CContact|null
	 */
	public function GetMyGlobalContact($iUserId)
	{
		return null;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupId
	 * @return CGroup
	 */
	public function GetGroupById($iUserId, $mGroupId)
	{
		$oGroup = false;
		$oAccount = $this->getAccountFromUserId($iUserId);
		$oLdap = $this->Ldap($oAccount);

		if ($oLdap && $oLdap->Search('(&(objectClass='.$this->sGroupObjectClass.')('.$this->sGroupUidFieldName.'='.$mGroupId.'))'))
		{
			$oGroup = $this->populateGroup($oAccount, $oLdap->ResultItem());
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
		return $this->GetGroupById($iUserId, $sGroupStrId);
	}

	/**
	 * @todo
	 * @param int $iUserId
	 * @param string $sName
	 * @return CGroup
	 */
	public function GetGroupByName($iUserId, $sName)
	{
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
		$aContacts = false;
		$oAccount = $this->getAccountFromUserId($iUserId);
		$oLdap = $this->Ldap($oAccount);
		
		if ($oLdap && $oLdap->Search('(objectClass='.$this->sContactObjectClass.')'))
		{
			$aContacts = array();
			$mLdapContactsItems = $oLdap->SortPaginate('', true, $iOffset, $iRequestLimit);
			foreach ($mLdapContactsItems as $aItem)
			{
				$oContact = $this->populateContactItem($oAccount, $aItem);
				if ($oContact)
				{
					$aContacts[] = $oContact;
				}
			}
		}
	
		return $aContacts;
	}

	private function buildContactFilter($sSearch, $sFirstCharacter, $mGroupId)
	{
		$sFirstCharacterFilter = empty($sFirstCharacter) ? '' : '(|('.$this->sContactNameFieldName.'='.$sFirstCharacter.'*)('.$this->sEmailFieldName.'='.$sFirstCharacter.'*))';

		$sFilter = '(objectClass='.$this->sContactObjectClass.')';
		$sFilter = empty($sFirstCharacterFilter) ? $sFilter : '(&'.$sFirstCharacterFilter.$sFilter.')';
		$sFilter = empty($mGroupId) ? $sFilter : '(&'.$sFilter.'(memberofpabgroup='.$mGroupId.'))';

		if (0 < strlen($sSearch))
		{
			$sFilter = '(&(|('.$this->sContactNameFieldName.'=*'.$sSearch.'*)('.$this->sEmailFieldName.'=*'.$sSearch.'*))'.$sFilter.')';
		}

		return $sFilter;
	}

	/**
	 * @param int $iUserId
	 * @param int $iSortField
	 * @param int $iSortOrder
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param mixed $mGroupId
	 * @return bool | array
	 */
	public function GetContactItems($iUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $mGroupId, $iTenantId = null, $bAll = false)
	{
		$aContacts = false;
		$oAccount = $this->getAccountFromUserId($iUserId);
		$oLdap = $this->Ldap($oAccount);

		$aContacts = false;
		if ($oLdap && $oLdap->Search($this->buildContactFilter($sSearch, $sFirstCharacter, $mGroupId)))
		{
			$mLdapContactsItems = $oLdap->SortPaginate(
				EContactSortField::EMail === $iSortField ? $this->sEmailFieldName : $this->sContactNameFieldName,
				ESortOrder::ASC === $iSortOrder, $iOffset, $iRequestLimit);
			
			$aContacts = array();
			foreach ($mLdapContactsItems as $aItem)
			{
				$oContact = $this->populateContactItem($oAccount, $aItem);
				if ($oContact)
				{
					$aContacts[] = $oContact;
				}
			}
		}

		return $aContacts;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @param mixed $mGroupId
	 * @return int
	 */
	public function GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $mGroupId, $iTenantId = null, $bAll = false)
	{
		$iResult = 0;
		
		$aContacts = false;
		$oLdap = $this->Ldap($this->getAccountFromUserId($iUserId));

		$aContacts = false;
		if ($oLdap && $oLdap->Search($this->buildContactFilter($sSearch, $sFirstCharacter, $mGroupId)))
		{
			$iResult = $oLdap->ResultCount();
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
		$oAccount = $this->getAccountFromUserId($iUserId);
		$oLdap = $this->Ldap($oAccount);

		$sFirstCharacterFilter = empty($sFirstCharacter) ? '' : '('.$this->sGroupNameFieldName.'='.$sFirstCharacter.'*)';

		$sFilter = '(objectClass='.$this->sGroupObjectClass.')';
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
						$sAdd = '('.$this->sGroupUidFieldName.'='.$aGroupIds[0].')';
					}
					else
					{
						$aAdd = array();
						foreach ($aGroupIds as $sGroupId)
						{
							$aAdd[] = '('.$this->sGroupUidFieldName.'='.$sGroupId.')';
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
			$sFilter = '(&('.$this->sGroupNameFieldName.'=*'.$sSearch.'*)'.$sFilter.')';
		}

		$aGroups = false;
		if ($oLdap && $oLdap->Search($sFilter))
		{
			$mLdapGroupsItems = $oLdap->SortPaginate(
				$this->sGroupNameFieldName, ESortOrder::ASC === $iSortOrder, $iOffset, $iRequestLimit);

			$aGroups = array();
			foreach ($mLdapGroupsItems as $aItem)
			{
				$oGroup = $this->populateGroupItem($oAccount, $aItem);
				if ($oGroup)
				{
					$aGroups[] = $oGroup;
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
		$oLdap = $this->Ldap($this->getAccountFromUserId($iUserId));

		$sFirstCharacterFilter = empty($sFirstCharacter) ? '' : '('.$this->sGroupNameFieldName.'='.$sFirstCharacter.'*)';

		$sFilter = '(objectClass='.$this->sGroupObjectClass.')';
		$sFilter = empty($sFirstCharacterFilter) ? $sFilter : '(&'.$sFirstCharacterFilter.$sFilter.')';

		if (0 < strlen($sSearch))
		{
			$sFilter = '(&('.$this->sGroupNameFieldName.'=*'.$sSearch.'*)'.$sFilter.')';
		}

		if ($oLdap && $oLdap->Search($sFilter))
		{
			$iResult = $oLdap->ResultCount();
		}

		return $iResult;
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @param bool $bPhoneOnly = false
	 * @return bool | array
	 */
	public function GetSuggestContactItems($iUserId, $sSearch, $iRequestLimit, $bPhoneOnly = false)
	{
		return $this->GetContactItems($iUserId, EContactSortField::EMail, ESortOrder::ASC, 0, $iRequestLimit, $sSearch, '', '');
	}

	/**
	 * @param CContact $oContact
	 * @return bool
	 */
	public function UpdateContact($oContact)
	{
		$oAccount = $this->getAccountFromUserId($oContact->IdUser);
		$oLdap = $this->Ldap($oAccount);

		$bReturn = false;
		if ($oLdap)
		{
			$aEntry = $this->getEntryFromContact($oAccount, $oContact, true);
			if ($aEntry)
			{
				$bReturn = $oLdap->Modify($this->sContactUidFieldName.'='.$oContact->IdContact, $aEntry);
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
		$oAccount = $this->getAccountFromUserId($oGroup->IdUser);
		$oLdap = $this->Ldap($oAccount);

		$bReturn = false;
		if ($oLdap)
		{
			$aEntry = $this->getEntryFromGroup($oAccount, $oGroup, true);
			if ($aEntry)
			{
				$bReturn = $oLdap->Modify($this->sGroupUidFieldName.'='.$oGroup->IdGroup, $aEntry);
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
		$oAccount = $this->getAccountFromUserId($oContact->IdUser);
		$oLdap = $this->Ldap($oAccount);
		
		$bReturn = false;
		if ($oLdap)
		{
			$aEntry = $this->getEntryFromContact($oAccount, $oContact, false);
			if ($aEntry)
			{
				$bReturn = $oLdap->Add($this->sContactUidFieldName.'='.$oContact->IdContact, $aEntry);
			}
		}

		return $bReturn;
	}

	/**
	 * @param CGroup $oGroup
	 * @return bool
	 */
	public function CreateGroup($oGroup)
	{
		$oAccount = $this->getAccountFromUserId($oGroup->IdUser);
		$oLdap = $this->Ldap($oAccount);

		$bReturn = false;
		if ($oLdap)
		{
			$aEntry = $this->getEntryFromGroup($oAccount, $oGroup, false);
			if ($aEntry)
			{
				$bReturn = $oLdap->Add($this->sGroupUidFieldName.'='.$oGroup->IdGroup, $aEntry);
			}
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
		$oAccount = $this->getAccountFromUserId($iUserId);
		$oLdap = $this->Ldap($oAccount);
		
		$bReturn = false;
		if ($oLdap && is_array($aContactsIds) && 0 < count($aContactsIds))
		{
			foreach ($aContactsIds as $sContactId)
			{
				$bReturn = $oLdap->Delete($this->sContactUidFieldName.'='.$sContactId);
				if (!$bReturn)
				{
					break;
				}
			}
		}

		return $bReturn;
	}
	
	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return bool
	 */
	public function DeleteSuggestContacts($iUserId, $aContactsIds)
	{
		return true;
	}	

	/**
	 * @param int $iUserId
	 * @param array $aGroupsIds
	 * @return bool
	 */
	public function DeleteGroups($iUserId, $aGroupsIds)
	{
		$oAccount = $this->getAccountFromUserId($iUserId);
		$oLdap = $this->Ldap($oAccount);
		
		$bReturn = false;
		if ($oLdap && is_array($aGroupsIds) && 0 < count($aGroupsIds))
		{
			foreach ($aGroupsIds as $sGroupId)
			{
				$bReturn = $oLdap->Delete($this->sGroupUidFieldName.'='.$sGroupId);
				if (!$bReturn)
				{
					break;
				}
			}
		}

		return $bReturn;
	}

	/**
	 * @param int $iUserId
	 * @param mixed $mGroupsId
	 * @return bool
	 */
	public function DeleteGroup($iUserId, $mGroupsId)
	{
		return $this->DeleteGroups($iUserId, array($mGroupsId));
	}

	/**
	 * @todo
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return bool
	 */
	public function UpdateSuggestTable($iUserId, $aEmails)
	{
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
	 * @todo
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function ClearAllContactsAndGroups($oAccount)
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
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return bool
	 */
	public function AddContactsToGroup($oGroup, $aContactIds)
	{
		$iResult = 1;

		$oContact = null;
		foreach ($aContactIds as $mContactId)
		{
			$oContact = $this->GetContactById($oGroup->IdUser, $mContactId);
			if ($oContact)
			{
				$aIds = $oContact->GroupsIds;
				$aIds = is_array($aIds) ? $aIds : array();
				$aIds[] = $oGroup->IdGroup;
				$aIds = array_unique($aIds);

				if (implode(',', $aIds) !== implode(',', $oContact->GroupsIds))
				{
					$oContact->GroupsIds = $aIds;
					$iResult &= $this->UpdateContact($oContact);
				}
			}
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
		$iResult = 1;

		$oContact = null;
		foreach ($aContactIds as $mContactId)
		{
			$oContact = $this->GetContactById($oGroup->IdUser, $mContactId);
			if ($oContact)
			{
				$aIds = $oContact->GroupsIds;
				$bNew = array();
				foreach ($aIds as $mId)
				{
					if ((string) $mId !== (string) $oGroup->IdGroup)
					{
						$bNew[] = $mId;
					}
				}
				$bNew = array_unique($bNew);

				if (implode(',', $bNew) !== implode(',', $oContact->GroupsIds))
				{
					$oContact->GroupsIds = $bNew;
					$iResult &= $this->UpdateContact($oContact);
				}
			}
		}

		return (bool) $iResult;
	}

	/**
	 * @todo
	 * @param CAccount $oAccount
	 * @param mixed $mContactId
	 * @param int $iContactType = EContactType::Global_
	 * @return int|null
	 */
	public function ConvertedContactLocalId($oAccount, $mContactId, $iContactType)
	{
		return null;
	}

	/**
	 * @todo
	 * @param CAccount $oAccount
	 * @param int $iContactType = EContactType::Global_
	 * @return array
	 */
	public function ConvertedContactLocalIdCollection($oAccount, $iContactType = EContactType::Global_)
	{
		return array();
	}

	/**
	 * @todo
	 * @param array $aIds
	 * @return array
	 */
	public function ContactIdsLinkedToGroups($aIds)
	{
		return array();
	}

	/**
	 * @todo
	 * @param int $iUserId
	 * @param mixed $mContactId
	 * @return CContact|false
	 */
	public function GetGlobalContactById($iUserId, $mContactId)
	{
		return false;
	}
}
