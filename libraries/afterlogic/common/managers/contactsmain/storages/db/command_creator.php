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
class CApiContactsmainCommandCreator extends api_CommandCreator
{
	/**
	 * @var \CApiUsersManager
	 */
	private $oUsersManager = null;

	/**
	 * @var array
	 */
	private $aAccountsCache = array();

	/**
	 * @param int $iIdUser
	 * @param null $iGroupId = null
	 * @param bool $bWatchShare = false
	 * @param bool $bWatchShareAndUser = true
	 * @param string $sTablePrefix = ''
	 * @param bool $bAll = false
	 *
	 * @return string
	 */
	protected function sharedItemsSqlHelper($iIdUser, $iGroupId = null, $bWatchShare = false, $bWatchShareAndUser = false, $sTablePrefix = '', $bAll = false)
	{
		if ($bAll)
		{
			$bWatchShare = true;
			$bWatchShareAndUser = true;
		}

		$aTypes[] = sprintf('%stype = %d', $sTablePrefix, EContactType::Personal);
		if ($iGroupId && 0 < $iGroupId)
		{
			$aTypes[] = sprintf('%stype = %d', $sTablePrefix, EContactType::Global_);
		}

		if ($bWatchShare && null !== $iIdUser)
		{
			$oAccount = null;
			if (isset($this->aAccountsCache[$iIdUser]))
			{
				$oAccount = $this->aAccountsCache[$iIdUser];
			}

			if (!$oAccount)
			{
				if (null === $this->oUsersManager)
				{
					$this->oUsersManager = CApi::Manager('users');
				}

				$oAccount = $this->oUsersManager ? $this->oUsersManager->GetDefaultAccount($iIdUser) : null;
				if ($oAccount)
				{
					$this->aAccountsCache[$iIdUser] = $oAccount;
				}
			}

			if ($oAccount)
			{
				$sEnd = '1 = 0';
				if (EContactsGABVisibility::Off !== $oAccount->GlobalAddressBook)
				{
					if (0 <= $oAccount->IdDomain && $oAccount->Domain)
					{
						if (EContactsGABVisibility::DomainWide === $oAccount->GlobalAddressBook)
						{
							$sEnd = sprintf('%sid_domain = %d', $sTablePrefix, $oAccount->IdDomain);
						}
						else if (EContactsGABVisibility::TenantWide === $oAccount->GlobalAddressBook)
						{
							$sEnd = sprintf('%sid_tenant = %d', $sTablePrefix, $oAccount->Domain->IdTenant);
						}
						else if (EContactsGABVisibility::SystemWide === $oAccount->GlobalAddressBook)
						{
							$sEnd = '1 = 1';
						}
					}
				}

				if ($bAll)
				{
					$aTypes[] = sprintf('%stype = %d', $sTablePrefix, EContactType::GlobalAccounts);
					$aTypes[] = sprintf('%stype = %d', $sTablePrefix, EContactType::GlobalMailingList);
				}

				return '('.
					'('.implode(' OR ', $aTypes).') AND ('.
					sprintf('(%sshared_to_all = 1 AND %s)', $sTablePrefix, $sEnd).
					($bAll ? sprintf(' OR ((%stype = %d OR %stype = %d) AND %s)', $sTablePrefix, EContactType::GlobalAccounts,
						$sTablePrefix, EContactType::GlobalMailingList, $sEnd) : '').
					($bWatchShareAndUser ? sprintf(' OR (%sid_user = %d AND %sshared_to_all = 0)', $sTablePrefix, $iIdUser, $sTablePrefix) : '').
				'))';
			}
		}

		return null !== $iIdUser ?
			sprintf('(%sid_user = %d AND %sshared_to_all = 0 AND (%s))', $sTablePrefix, $iIdUser, $sTablePrefix, implode(' OR ', $aTypes)) : '';
	}
	
	/**
	 * @param int|null $iIdUser
	 * @param string $sWhere
	 * @param bool $bIgnoreHideInGab = false
	 * @param int $iSharedTenantId = null
	 * @return string
	 */
	protected function getContactByWhere($iIdUser, $sWhere, $bIgnoreHideInGab = false, $iSharedTenantId = null)
	{
		$aMap = api_AContainer::DbReadKeys(CContact::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sHideInGab = $bIgnoreHideInGab ? '' : ' AND hide_in_gab = 0';
		$sUserWhere = $this->sharedItemsSqlHelper($iIdUser, 999, is_int($iSharedTenantId)); /// 999 - hack
		
		$sSql = 'SELECT %s FROM %sawm_addr_book WHERE deleted = 0 AND auto_create = 0 '.
			($sUserWhere ? ' AND '.$sUserWhere : '').$sHideInGab.' AND %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}

	/**
	 * @param int $iUserId
	 * @param int $iContactId
	 * @param bool $bIgnoreHideInGab = false
	 * @param int $iSharedTenantId = null
	 * @return string
	 */
	public function GetContactById($iUserId, $iContactId, $bIgnoreHideInGab = false, $iSharedTenantId = null)
	{
		return $this->getContactByWhere($iUserId, sprintf('%s = %d',
			$this->escapeColumn('id_addr'), $iContactId), $bIgnoreHideInGab, $iSharedTenantId);
	}

	/**
	 * @param mixed $mTypeId
	 * @param int $iContactType
	 * @param bool $bIgnoreHideInGab = false
	 * @return string
	 */
	public function GetContactByTypeId($mTypeId, $iContactType, $bIgnoreHideInGab = false)
	{
		return $this->getContactByWhere(null, sprintf('%s = %s AND %s = %d',
			$this->escapeColumn('type_id'), $this->escapeString($mTypeId), $this->escapeColumn('type'), $iContactType), $bIgnoreHideInGab);
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return string
	 */
	public function GetContactByEmail($iUserId, $sEmail)
	{
		$sEscapedEmail = $this->escapeString($sEmail);
		return $this->getContactByWhere($iUserId, sprintf('(%s = %s OR %s = %s OR %s = %s)',
			$this->escapeColumn('h_email'), $sEscapedEmail,
			$this->escapeColumn('b_email'), $sEscapedEmail,
			$this->escapeColumn('other_email'), $sEscapedEmail
		));
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @param int $iSharedTenantId = null
	 * @return string
	 */
	public function GetContactByStrId($iUserId, $sContactStrId, $iSharedTenantId = null)
	{
		$sWhere = sprintf('%s = %s', $this->escapeColumn('str_id'), $this->escapeString($sContactStrId));
		return $this->getContactByWhere($iUserId, $sWhere, false, $iSharedTenantId);
	}

	/**
	 * @param int $iUserId
	 * @param int $iSharedTenantId = null
	 * @return string
	 */
	public function GetSharedContactIds($iUserId, $iSharedTenantId = null)
	{
		$sUserWhere = $this->sharedItemsSqlHelper($iUserId, 999, is_int($iSharedTenantId)); /// 999 - hack
		
		$sSql = 'SELECT str_id FROM %sawm_addr_book WHERE deleted = 0 AND auto_create = 0 AND hide_in_gab = 0'.
			($sUserWhere ? ' AND '.$sUserWhere : '');

		return sprintf($sSql, $this->Prefix());
	}
	
	/**
	 * @param int $iUserId
	 * @param int $iContactId
	 * @return string
	 */
	public function GetContactGroupsIds($iUserId, $iContactId)
	{
		$sSql = 'SELECT id_group FROM %sawm_addr_groups_contacts WHERE id_addr = %d';
		return sprintf($sSql, $this->Prefix(), $iContactId);
	}

	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getGroupByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CGroup::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_addr_groups WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}

	/**
	 * @param int $iUserId
	 * @param int $iGroupId
	 * @return string
	 */
	public function GetGroupById($iUserId, $iGroupId)
	{
		return $this->getGroupByWhere(sprintf('%s = %d AND %s = %d',
			$this->escapeColumn('id_user'), $iUserId, $this->escapeColumn('id_group'), $iGroupId));
	}

	/**
	 * @param int $iUserId
	 * @param int $sGroupStrId
	 * @return string
	 */
	public function GetGroupByStrId($iUserId, $sGroupStrId)
	{
		return $this->getGroupByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_user'), $iUserId, $this->escapeColumn('group_str_id'),
			$this->escapeString($sGroupStrId)));
	}

	/**
	 * @param int $iUserId
	 * @param int $sName
	 * @return string
	 */
	public function GetGroupByName($iUserId, $sName)
	{
		return $this->getGroupByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_user'), $iUserId, $this->escapeColumn('group_nm'),
			$this->escapeString($sName)));
	}

	/**
	 * @param CContact $oContact
	 * @return string
	 */
	public function CreateContact(CContact $oContact)
	{
		$aResults = api_AContainer::DbInsertArrays($oContact, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_addr_book ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(),
				implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		return '';
	}

	/**
	 * @param CGroup $oGroup
	 * @return string
	 */
	public function CreateGroup(CGroup $oGroup)
	{
		$aResults = api_AContainer::DbInsertArrays($oGroup, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_addr_groups ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(),
				implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		return '';
	}

	/**
	 * @param CContact $oContact
	 * @return string
	 */
	public function UpdateContact($oContact)
	{
		$sUserWhere = $this->sharedItemsSqlHelper($oContact->IdUser, null, true, true);
		$sSql = 'UPDATE %sawm_addr_book SET %s WHERE %s AND id_addr = %d';
		return sprintf($sSql, $this->Prefix(),
			implode(', ', api_AContainer::DbUpdateArray($oContact, $this->oHelper)),
			$sUserWhere, $oContact->IdContact);
	}
	
	/**
	 * @param CContact $oContact
	 * @param int $iUserId
	 * @return string
	 */
	public function UpdateContactUserId($oContact, $iUserId)
	{
		$sUserWhere = $this->sharedItemsSqlHelper($oContact->IdUser, null, true, true);
		$sSql = 'UPDATE %sawm_addr_book SET id_user = %d WHERE %s AND id_addr = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId,
			$sUserWhere, $oContact->IdContact);
	}
	
	/**
	 * @param CGroup $oGroup
	 * @return string
	 */
	public function UpdateGroup($oGroup)
	{
		$sSql = 'UPDATE %sawm_addr_groups SET %s WHERE id_user = %d AND id_group = %d';
		return sprintf($sSql, $this->Prefix(),
			implode(', ', api_AContainer::DbUpdateArray($oGroup, $this->oHelper)),
			$oGroup->IdUser, $oGroup->IdGroup);
	}

	/**
	 * @param CContact $oContact
	 * @return string
	 */
	public function UpdateGroupIdsInContact($oContact)
	{
		if (0 < count($oContact->GroupsIds))
		{
			$sSql = 'INSERT INTO %sawm_addr_groups_contacts (id_addr, id_group) VALUES %s';
			$aValues = array();

			foreach ($oContact->GroupsIds as $sGroupId)
			{
				$aValues[] = '('.((int) $oContact->IdContact).', '.((int) $sGroupId).')';
			}

			return sprintf($sSql, $this->Prefix(), implode(', ', $aValues));
		}
		return '';
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @param int $iSharedTenantId = null
	 * @return string
	 */
	public function DeleteContacts($iUserId, $aContactsIds, $iSharedTenantId = null)
	{
		$sSql = 'DELETE FROM %sawm_addr_book WHERE %s = %d AND %s IN (%s)';

		$aContactsIds = array_map('intval', $aContactsIds);

		$sColumnName = $this->escapeColumn('id_user');
		$sColumnValue = $iUserId;
		if (is_int($iSharedTenantId))
		{
			$sColumnName = $this->escapeColumn('id_tenant');
			$sColumnValue = $iSharedTenantId;
		}

		return sprintf($sSql, $this->Prefix(), $sColumnName, $sColumnValue,
			$this->escapeColumn('id_addr'), implode(', ', $aContactsIds));
	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupsIds
	 * @return string
	 */
	public function DeleteGroups($iUserId, $aGroupsIds)
	{
		$sSql = 'DELETE FROM %sawm_addr_groups WHERE %s = %d AND %s IN (%s)';

		$aGroupsIds = array_map('intval', $aGroupsIds);

		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_user'), $iUserId, $this->escapeColumn('id_group'), implode(', ', $aGroupsIds));
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return string
	 */
//	public function DeleteContactsExceptIds($iUserId, $aContactsIds)
//	{
//		$sSqlAdd = '';
//		if (is_array($aContactsIds) && 0 < count($aContactsIds))
//		{
//			$sSqlAdd = sprintf(' AND %s NOT IN (%s)', $this->escapeColumn('id_addr'),
//				implode(', ', $aContactsIds = array_map('intval', $aContactsIds)));
//		}
//
//		$sSql = 'DELETE FROM %sawm_addr_book WHERE %s = %d%s';
//
//		return sprintf($sSql, $this->Prefix(), $this->escapeColumn('id_user'), $iUserId, $sSqlAdd);
//	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupsIds
	 * @return string
	 */
//	public function DeleteGroupsExceptIds($iUserId, $aGroupsIds)
//	{
//		$sSqlAdd = '';
//		if (is_array($aGroupsIds) && 0 < count($aGroupsIds))
//		{
//			$sSqlAdd = sprintf(' AND %s NOT IN (%s)',
//				$this->escapeColumn('id_group'), implode(', ', array_map('intval', $aGroupsIds)));
//		}
//
//		$sSql = 'DELETE FROM %sawm_addr_groups WHERE id_user = %d%s';
//
//		return sprintf($sSql, $this->Prefix(), $iUserId, $sSqlAdd);
//	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteAllGroupsContacts($iUserId)
	{
		$sSql = 'DELETE %sawm_addr_groups_contacts
FROM %sawm_addr_groups_contacts, %sawm_addr_groups
WHERE %sawm_addr_groups_contacts.id_group = %sawm_addr_groups.id_group
AND %sawm_addr_groups.id_user = %d';
		return sprintf($sSql, $this->Prefix(), $this->Prefix(), $this->Prefix(),
			$this->Prefix(), $this->Prefix(), $this->Prefix(), $iUserId);
	}
	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteAllGroups($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_addr_groups WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteAllContacts($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_addr_book WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteUserGlobalSubContact($iUserId)
	{
		// TODO Magic
		$sSql = 'DELETE aa1 '.
'FROM %sawm_addr_book AS aa1 '.
'INNER JOIN %sawm_addr_book AS aa2 ON aa1.type_id = aa2.id_addr '.
'WHERE aa1.type = 1 AND aa2.type = 2 AND aa2.type_id = %s';

		return sprintf($sSql, $this->Prefix(), $this->Prefix(), $this->escapeString($iUserId));
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteUserGlobalContact($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_addr_book WHERE %s = %d AND %s = %s';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('type'), 2, // TODO Magic
			$this->escapeColumn('type_id'),
			$this->escapeString($iUserId));
	}

	/**
	 * @param string $sWhereField
	 * @param array $aIds
	 * @return string
	 */
	protected function clearGroupContactsIds($sWhereField, $aIds)
	{
		if (is_array($aIds) && 0 < count($aIds))
		{
			$sSql = 'DELETE FROM %sawm_addr_groups_contacts WHERE %s IN (%s)';
			return sprintf($sSql, $this->Prefix(),
				$this->escapeColumn($sWhereField), implode(', ', array_map('intval', $aIds)));
		}

		return '';
	}

	/**
	 * @param array $aContactsIds
	 * @return string
	 */
	public function ClearGroupsIdsByContactsIds($aContactsIds)
	{
		return $this->clearGroupContactsIds('id_addr', $aContactsIds);
	}
	/**
	 * @param int $aGroupsIds
	 * @return string
	 */
	public function ClearContactsIdsByGroupsIds($aGroupsIds)
	{
		return $this->clearGroupContactsIds('id_group', $aGroupsIds);
	}

	/**
	 * @param string $sWhereField
	 * @param array $aIds
	 * @return string
	 */
	protected function clearGroupContactsExceptIds($iUserId, $sWhereField, $aIds)
	{
		$sSqlAdd = '';
		if (is_array($aIds) && 0 < count($aIds))
		{
			$sSqlAdd = sprintf(' AND %s NOT IN (%s)',
				'gr_cnt.'.$sWhereField,	implode(', ', array_map('intval', $aIds)));
		}

		$sSql = 'DELETE gr_cnt
FROM %sawm_addr_groups_contacts AS gr_cnt,
%s%s AS book
WHERE book.id_user = %d AND %s = %s%s';

		$sBook = ('id_addr' === $sWhereField) ? 'awm_addr_book' : 'awm_addr_groups';

		return sprintf($sSql, $this->Prefix(), $this->Prefix(), $sBook,
			$iUserId, 'book.'.$sWhereField, 'gr_cnt.'.$sWhereField, $sSqlAdd);
	}

	/**
	 * @param int $iUserId
	 * @param array $aEmails
	 * @return string
	 */
	public function GetExistingEmails($iUserId, $aEmails)
	{
		$sSql = 'SELECT view_email FROM %sawm_addr_book WHERE deleted = 0 AND hide_in_gab = 0 AND id_user = %d AND view_email IN (%s)';

		$aEmails = array_map(array(&$this, 'escapeString'), $aEmails);
		return sprintf($sSql, $this->Prefix(), $iUserId, implode(', ', $aEmails));
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @param string $sName = ''
	 * @return string
	 */
	public function CreateAutoCreateContact($iUserId, $sEmail, $sName = '')
	{
		$sSql = 'INSERT INTO %sawm_addr_book
(id_user, h_email, view_email, fullname, primary_email, auto_create, use_frequency, date_created, date_modified)
VALUES (%d, %s, %s, %s, 0, 1, 0, %s, %s)';

		$sEmail = $this->escapeString($sEmail);
		$sName = $this->escapeString($sName);
		$sNow = $this->oHelper->TimeStampToDateFormat(time(), true);
		
		return sprintf($sSql, $this->Prefix(), $iUserId, $sEmail, $sEmail, $sName, $sNow, $sNow);
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return string
	 */
	public function UpdateContactFrequencyByEmails($iUserId, $aEmails)
	{
		if (0 < count($aEmails))
		{
			$sSql = 'UPDATE %sawm_addr_book	SET use_frequency = use_frequency + 1, date_modified = %s 
WHERE deleted = 0 AND hide_in_gab = 0 AND id_user = %d AND view_email IN (%s)';

			$sNow = $this->oHelper->TimeStampToDateFormat(time(), true);
			$aEmails = array_map(array(&$this, 'escapeString'), $aEmails);
			return sprintf($sSql, $this->Prefix(), $sNow, $iUserId, implode(', ', $aEmails));
		}

		return '';
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return string
	 */
	public function DeleteAutoCreateContacts($iUserId, $sEmail)
	{
		if (!empty($sEmail))
		{
			$sSql = 'DELETE FROM %sawm_addr_book WHERE id_user = %d AND auto_create = 1 AND h_email = %s';
			return sprintf($sSql, $this->Prefix(), $iUserId, $this->escapeString($sEmail));
		}
		
		return '';
	}

	/**
	 * @param array $aContactsIds
	 * @return string
	 */
	public function ClearGroupsIdsByExceptContactsIds($iUserId, $aContactsIds)
	{
		return $this->clearGroupContactsExceptIds($iUserId, 'id_addr', $aContactsIds);
	}
	
	/**
	 * @param int $aGroupsIds
	 * @return string
	 */
	public function ClearContactsIdsByExceptGroupsIds($iUserId, $aGroupsIds)
	{
		return $this->clearGroupContactsExceptIds($iUserId, 'id_group', $aGroupsIds);
	}
}

/**
 * @package Contacts
 */
class CApiContactsmainCommandCreatorMySQL extends CApiContactsmainCommandCreator
{
	/**
	 * @param int|null $iUserId
	 * @param string $sWhere
	 * @param bool $bIgnoreHideInGab = false
	 * @param int $iSharedTenantId = null
	 * @return string
	 */
	protected function getContactByWhere($iUserId, $sWhere, $bIgnoreHideInGab = false, $iSharedTenantId = null)
	{
		return parent::getContactByWhere($iUserId, $sWhere, $bIgnoreHideInGab, $iSharedTenantId).' LIMIT 1';
	}

	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getGroupByWhere($sWhere)
	{
		return parent::getGroupByWhere($sWhere).' LIMIT 1';
	}

	/**
	 * @param int $iUserId
	 * @param int $iOffset
	 * @param int $iRequestLimit
	 * @return bool | array
	 */
	public function GetContactItemsWithoutOrder($iUserId, $iOffset, $iRequestLimit)
	{
		$sSql = 'SELECT id_addr, id_user, str_id, view_email, primary_email, h_email, b_email, other_email,
use_frequency, fullname, firstname, surname, nickname, use_friendly_nm, type, type_id, shared_to_all 
FROM %sawm_addr_book WHERE %s AND deleted = 0 AND hide_in_gab = 0 AND auto_create = 0 LIMIT %d OFFSET %d';

		$sUserWhere = $this->sharedItemsSqlHelper($iUserId);

		return sprintf($sSql, $this->Prefix(), $sUserWhere, $iRequestLimit, $iOffset);
	}

	/**
	 * @param int $iUserId
	 * @param int $iSharedTenantId = 0
	 * @param bool $bAddGlobal = true
	 * @return string
	 */
	public function GetAllContactsNamesWithPhones($iUserId, $iSharedTenantId = 0, $bAddGlobal = true)
	{
		$sUserWhere = $this->sharedItemsSqlHelper($iUserId, null, is_int($iSharedTenantId), true, '', $bAddGlobal);
		
		$sSql = 'SELECT id_addr, id_user, auto_create, view_email,
fullname, firstname, surname, type, type_id, b_phone, h_phone, h_mobile, shared_to_all
FROM %sawm_addr_book
WHERE %s AND deleted = 0 AND auto_create = 0 AND
	hide_in_gab = 0 AND (b_phone <> \'\' OR h_phone <> \'\' OR h_mobile <> \'\')
LIMIT 5000';

		return sprintf($sSql, $this->Prefix(), $sUserWhere);
	}
	
	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @param bool $bPhoneOnly = false
	 * @param int $iSharedTenantId = null
	 * @param bool $bAll = false
	 * @return string
	 */
	public function GetSuggestContactItems($iUserId, $sSearch, $iRequestLimit, $bPhoneOnly = false, $iSharedTenantId = null, $bAll = false)
	{
		$sUserWhere = $this->sharedItemsSqlHelper($iUserId, null, is_int($iSharedTenantId), true, '', $bAll);
		
		$sSearchAdd = '';
		if (0 < strlen($sSearch))
		{
			$bPhone = api_Utils::IsPhoneSearch($sSearch);
			$sPhoneSearch = $bPhone ? api_Utils::ClearPhoneSearch($sSearch) : '';
			
			$sSearch = '\'%'.$this->escapeString($sSearch, true, true).'%\'';

			if ($bPhoneOnly)
			{
				$sSearchAdd .= '(b_phone <> \'\' OR h_phone <> \'\' OR h_mobile <> \'\') AND ';
			}
			
			$sSearchAdd .= sprintf('(h_email <> \'\' OR b_email <> \'\' OR other_email <> \'\') '.
' AND (fullname LIKE %s OR firstname LIKE %s'.
' OR surname LIKE %s OR nickname LIKE %s'.
' OR h_email LIKE %s OR b_email LIKE %s'.
' OR other_email LIKE %s)', $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);

			if (0 < strlen($sPhoneSearch))
			{
				$sPhoneSearch = '\'%'.$this->escapeString($sPhoneSearch, true, true).'%\'';

				$sSearchAdd = '('.$sSearchAdd.sprintf(') OR '.
					 '(b_phone <> \'\' AND REPLACE(REPLACE(REPLACE(REPLACE(b_phone, \'(\', \'\'), \')\', \'\'), \'+\', \'\'), \' \', \'\') LIKE %s) OR '.
					 '(h_phone <> \'\' AND REPLACE(REPLACE(REPLACE(REPLACE(h_phone, \'(\', \'\'), \')\', \'\'), \'+\', \'\'), \' \', \'\') LIKE %s) OR '.
					 '(h_mobile <> \'\' AND REPLACE(REPLACE(REPLACE(REPLACE(h_mobile, \'(\', \'\'), \')\', \'\'), \'+\', \'\'), \' \', \'\') LIKE %s)',
					 $sPhoneSearch, $sPhoneSearch, $sPhoneSearch);
			}
		}
		
		$sSql = 'SELECT id_addr, str_id, id_user, auto_create, view_email, primary_email, h_email, b_email, other_email,
use_frequency, fullname, firstname, use_friendly_nm, type, type_id, b_phone, h_phone, h_mobile, shared_to_all,
(use_frequency/CEIL(DATEDIFF(CURDATE() + 1, date_modified)/30)) as age_score
FROM %sawm_addr_book
WHERE %s AND deleted = 0 AND hide_in_gab = 0%s
ORDER BY shared_to_all ASC, age_score DESC
LIMIT %d OFFSET %d';

		return sprintf($sSql, $this->Prefix(), $sUserWhere, 0 < strlen($sSearchAdd) ? ' AND ('.$sSearchAdd.')' : '', $iRequestLimit, 0);
	}
	
	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @return string
	 */
	public function GetSuggestGroupItems($iUserId, $sSearch, $iRequestLimit)
	{
		$sWhere = '1 = 0';
		if (0 < strlen($sSearch))
		{
			$sWhere = 'group_nm LIKE \'%'.$this->escapeString($sSearch, true, true).'%\'';
		}
		
		$sSql = 'SELECT id_group, use_frequency, group_nm, organization
FROM %sawm_addr_groups
WHERE id_user = %d AND %s
ORDER BY use_frequency DESC
LIMIT %d OFFSET %d';

		return sprintf($sSql, $this->Prefix(), $iUserId, $sWhere, $iRequestLimit, 0);
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
	 * @return string
	 */
	public function GetContactItems($iUserId, $iSortField, $iSortOrder, $iOffset,
		$iRequestLimit, $sSearch, $sFirstCharacter, $iGroupId, $iSharedTenantId = null, $bAll = false)
	{
		$sGroupAdd = '';
		if (0 < $iGroupId)
		{
			$sGroupAdd = sprintf('
INNER JOIN %sawm_addr_groups_contacts AS gr_cnt ON gr_cnt.id_addr = book.id_addr AND gr_cnt.id_group = %d',
				$this->Prefix(), $iGroupId);
		}

		$sSearchAdd = '';
		$sFirstCharacter = (0 < strlen(trim($sFirstCharacter))) ? trim($sFirstCharacter) : '';
		if (!empty($sFirstCharacter))
		{
			$sSearch = '\''.$this->escapeString($sFirstCharacter, true, true).'%\'';
			$sSearchAdd = sprintf('book.fullname LIKE %s OR book.firstname LIKE %s OR book.surname LIKE %s OR book.nickname LIKE %s OR book.h_email LIKE %s OR book.b_email LIKE %s OR book.other_email LIKE %s',
				$sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);
		}

		if (0 < strlen($sSearch))
		{
			$bPhone = api_Utils::IsPhoneSearch($sSearch);
			$sPhoneSearch = $bPhone ? api_Utils::ClearPhoneSearch($sSearch) : '';

			$sSearch = '\'%'.$this->escapeString($sSearch, true, true).'%\'';

			$sMainSearch = sprintf('book.fullname LIKE %s OR book.firstname LIKE %s OR book.surname LIKE %s OR book.nickname LIKE %s OR book.h_email LIKE %s OR book.b_email LIKE %s OR book.other_email LIKE %s',
				$sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);
			
			if (0 < strlen($sPhoneSearch))
			{
				$sPhoneSearch = '\'%'.$this->escapeString($sPhoneSearch, true, true).'%\'';

				$sMainSearch .= sprintf(' OR '.
					 '(b_phone <> \'\' AND REPLACE(REPLACE(REPLACE(REPLACE(b_phone, \'(\', \'\'), \')\', \'\'), \'+\', \'\'), \' \', \'\') LIKE %s) OR '.
					 '(h_phone <> \'\' AND REPLACE(REPLACE(REPLACE(REPLACE(h_phone, \'(\', \'\'), \')\', \'\'), \'+\', \'\'), \' \', \'\') LIKE %s) OR '.
					 '(h_mobile <> \'\' AND REPLACE(REPLACE(REPLACE(REPLACE(h_mobile, \'(\', \'\'), \')\', \'\'), \'+\', \'\'), \' \', \'\') LIKE %s)',
					 $sPhoneSearch, $sPhoneSearch, $sPhoneSearch);
			}

			$sSearchAdd = (0 === strlen($sSearchAdd)) ? $sMainSearch : '('.$sSearchAdd.') AND ('.$sMainSearch.')';
		}

		$sUserWhere = $this->sharedItemsSqlHelper($iUserId, $iGroupId, is_int($iSharedTenantId), 0 < $iGroupId, 'book.', $bAll);

		$sSql = 'SELECT book.id_addr, book.id_user, book.str_id, book.view_email, book.primary_email, book.h_email, book.b_email, book.other_email,
book.fullname, book.use_frequency, book.firstname, book.surname, book.use_friendly_nm, book.type, book.type_id, book.b_phone, book.h_phone, book.h_mobile,
book.shared_to_all
FROM %sawm_addr_book AS book%s
WHERE %s AND book.deleted = 0 AND book.hide_in_gab = 0 AND book.auto_create = 0%s
ORDER BY %s
LIMIT %d OFFSET %d';

		$sField = 'book.'.EContactSortField::GetContactDbField($iSortField);
		$sOrder = (ESortOrder::ASC === $iSortOrder) ? 'ASC' : 'DESC';
		$sOrderBy = $sField.' '.$sOrder;

		if ('book.use_frequency' === $sField)
		{
			$aAdd = 'book.shared_to_all ';
			$aAdd .= (ESortOrder::ASC === $iSortOrder) ? 'DESC' : 'ASC';
			$sOrderBy = $aAdd.', '.$sOrderBy;
		}
		else if ('book.fullname' === $sField)
		{
			$aAdd = 'book.view_email ';
			$aAdd .= (ESortOrder::ASC !== $iSortOrder) ? 'DESC' : 'ASC';
			$sOrderBy = $sOrderBy.', '.$aAdd;
		}

		return sprintf($sSql, $this->Prefix(), $sGroupAdd, $sUserWhere,
			0 < strlen($sSearchAdd) ? ' AND ('.$sSearchAdd.')' : '', $sOrderBy, $iRequestLimit, $iOffset);
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
	 * @return string
	 */
	public function GetGroupItems($iUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iContactId)
	{
		$sContactAdd = '';
		if (0 < $iContactId)
		{
			$sContactAdd = sprintf('
INNER JOIN %sawm_addr_groups_contacts AS gr_cnt ON gr_cnt.id_group = book.id_group AND gr_cnt.id_addr = %d',
				$this->Prefix(), $iContactId);
		}

		$sSearchAdd = '';
		if (!empty($sSearch))
		{
			$sSearch = '\'%'.$this->escapeString($sSearch, true, true).'%\'';
			$sSearchAdd = sprintf(' AND book.group_nm LIKE %s', $sSearch);
		}

		$sFirstCharacter = (0 < strlen(trim($sFirstCharacter))) ? trim($sFirstCharacter) : '';
		if (!empty($sFirstCharacter))
		{
			$sSearch = '\''.$this->escapeString($sFirstCharacter, true, true).'%\'';
			$sSearchAdd .= sprintf(' AND book.group_nm LIKE %s', $sSearch);
		}

		$sSql = 'SELECT book.id_group, book.group_nm, book.use_frequency, book.organization
FROM %sawm_addr_groups AS book%s
WHERE book.id_user = %d%s
ORDER BY %s %s
LIMIT %d OFFSET %d';

		$sField = 'book.'.EContactSortField::GetGroupDbField($iSortField);
		$sOrder = (ESortOrder::ASC === $iSortOrder) ? 'ASC' : 'DESC';

		return sprintf($sSql, $this->Prefix(), $sContactAdd, $iUserId, $sSearchAdd, $sField, $sOrder, $iRequestLimit, $iOffset);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iGroupId
	 * @param int $iSharedTenantId = null
	 * @param bool $bAll = false
	 * @return string
	 */
	public function GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId, $iSharedTenantId = null, $bAll = false)
	{
		$sGroupAdd = '';
		if (0 < $iGroupId)
		{
			$sGroupAdd = sprintf('
INNER JOIN %sawm_addr_groups_contacts AS gr_cnt ON gr_cnt.id_addr = book.id_addr AND gr_cnt.id_group = %d',
				$this->Prefix(), $iGroupId);
		}

		$sSearchAdd = '';
		$sFirstCharacter = (0 < strlen(trim($sFirstCharacter))) ? trim($sFirstCharacter) : '';
		if (!empty($sFirstCharacter))
		{
			$sSearch = '\''.$this->escapeString($sFirstCharacter, true, true).'%\'';
			$sSearchAdd = sprintf('book.fullname LIKE %s OR book.firstname LIKE %s OR book.surname LIKE %s OR book.nickname LIKE %s OR book.h_email LIKE %s OR book.b_email LIKE %s OR book.other_email LIKE %s',
				$sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);
		}

		if (0 < strlen($sSearch))
		{
			$bPhone = api_Utils::IsPhoneSearch($sSearch);
			$sPhoneSearch = $bPhone ? api_Utils::ClearPhoneSearch($sSearch) : '';

			$sSearch = '\'%'.$this->escapeString($sSearch, true, true).'%\'';

			$sMainSearch = sprintf('book.fullname LIKE %s OR book.firstname LIKE %s OR book.surname LIKE %s OR book.nickname LIKE %s OR book.h_email LIKE %s OR book.b_email LIKE %s OR book.other_email LIKE %s',
				$sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);

			if (0 < strlen($sPhoneSearch))
			{
				$sPhoneSearch = '\'%'.$this->escapeString($sPhoneSearch, true, true).'%\'';

				$sMainSearch .= sprintf(' OR '.
					 '(b_phone <> \'\' AND REPLACE(REPLACE(REPLACE(REPLACE(b_phone, \'(\', \'\'), \')\', \'\'), \'+\', \'\'), \' \', \'\') LIKE %s) OR '.
					 '(h_phone <> \'\' AND REPLACE(REPLACE(REPLACE(REPLACE(h_phone, \'(\', \'\'), \')\', \'\'), \'+\', \'\'), \' \', \'\') LIKE %s) OR '.
					 '(h_mobile <> \'\' AND REPLACE(REPLACE(REPLACE(REPLACE(h_mobile, \'(\', \'\'), \')\', \'\'), \'+\', \'\'), \' \', \'\') LIKE %s)',
					 $sPhoneSearch, $sPhoneSearch, $sPhoneSearch);
			}

			$sSearchAdd = (0 === strlen($sSearchAdd)) ? $sMainSearch : '('.$sSearchAdd.') AND ('.$sMainSearch.')';
		}

		$sUserWhere = $this->sharedItemsSqlHelper($iUserId, $iGroupId, is_int($iSharedTenantId), 0 < $iGroupId, 'book.', $bAll);

		$sSql = 'SELECT COUNT(book.id_addr) AS cnt FROM %sawm_addr_book as book%s
WHERE %s AND book.deleted = 0 AND book.hide_in_gab = 0 AND book.auto_create = 0%s';

		return sprintf($sSql, $this->Prefix(), $sGroupAdd, $sUserWhere, 0 < strlen($sSearchAdd) ? ' AND ('.$sSearchAdd.')' : '');
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param string $sFirstCharacter
	 * @return string
	 */
	public function GetGroupItemsCount($iUserId, $sSearch, $sFirstCharacter)
	{
		$sSearchAdd = '';
		if (!empty($sSearch))
		{
			$sSearch = '\'%'.$this->escapeString($sSearch, true, true).'%\'';
			$sSearchAdd .= sprintf(' AND (group_nm LIKE %s)', $sSearch);
		}

		$sFirstCharacter = (0 < strlen(trim($sFirstCharacter))) ? trim($sFirstCharacter) : '';
		if (!empty($sFirstCharacter))
		{
			$sSearch = '\''.$this->escapeString($sFirstCharacter, true, true).'%\'';
			$sSearchAdd .= sprintf(' AND (group_nm LIKE %s)', $sSearch);
		}

		$sSql = 'SELECT COUNT(id_group) AS cnt FROM %sawm_addr_groups WHERE id_user = %d%s';

		return sprintf($sSql, $this->Prefix(), $iUserId, $sSearchAdd);
	}

	/**
	 * @param CAccount $oAccount
	 * @param mixed $mContactId
	 * @param int $iContactType
	 * @return string
	 */
	public function ConvertedContactLocalId($oAccount, $mContactId, $iContactType)
	{
		$sSql = 'SELECT id_addr FROM %sawm_addr_book
WHERE id_user = %d AND deleted = 0 AND auto_create = 0 AND type_id = %s AND type = %d';

		return sprintf($sSql, $this->Prefix(), $oAccount->IdUser, $this->escapeString($mContactId), $iContactType);
	}

	/**
	 * @param CAccount $oAccount
	 * @param int $iContactType
	 * @return string
	 */
	public function ConvertedContactLocalIdCollection($oAccount, $iContactType)
	{
		$sSql = 'SELECT id_addr, type_id FROM %sawm_addr_book
WHERE id_user = %d AND deleted = 0 AND auto_create = 0 AND type = %d';

		return sprintf($sSql, $this->Prefix(), $oAccount->IdUser, $iContactType);
	}

	/**
	 * @param array $aContactsIds
	 * @return string
	 */
	public function ContactIdsLinkedToGroups($aContactsIds)
	{
		$sSql = 'SELECT DISTINCT id_addr FROM %sawm_addr_groups_contacts WHERE id_addr IN (%s)';

		$aContactsIds = array_map('intval', $aContactsIds);

		return sprintf($sSql, $this->Prefix(), implode(', ', $aContactsIds));
	}

	/**
	 * @return bool
	 */
	public function FlushContacts()
	{
		$sSql = 'DELETE FROM %sawm_addr_book WHERE deleted = 1';

		return sprintf($sSql, $this->Prefix());
	}

	/**
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return string
	 */
	public function AddContactsToGroup($oGroup, $aContactIds)
	{
		if ($oGroup && is_array($aContactIds) && 0 < count($aContactIds))
		{
			$sSql = 'INSERT INTO %sawm_addr_groups_contacts (id_addr, id_group) VALUES %s';
			$aValues = array();

			foreach ($aContactIds as $mContactId)
			{
				$aValues[] = '('.$mContactId.', '.$oGroup->IdGroup.')';
			}

			return sprintf($sSql, $this->Prefix(), implode(', ', $aValues));
		}

		return '';
	}

	/**
	 * @param CGroup $oGroup
	 * @param array $aContactIds
	 * @return string
	 */
	public function RemoveContactsFromGroup($oGroup, $aContactIds)
	{
		if (is_array($aContactIds) && 0 < count($aContactIds))
		{
			$sSql = 'DELETE FROM %sawm_addr_groups_contacts WHERE id_group = %s AND id_addr IN (%s)';
			return sprintf($sSql, $this->Prefix(),
				$oGroup->IdGroup, implode(', ', array_map('intval', $aContactIds)));
		}

		return '';
	}

	/**
	 * @param int $iUserId
	 * @param int $mContactId
	 * @return string
	 */
	public function GetGlobalContactById($iUserId, $mContactId)
	{
		return $this->getContactByWhere($iUserId, sprintf('%s = %s AND %s = %d',
			$this->escapeColumn('type_id'), $this->escapeString($mContactId),
			$this->escapeColumn('type'),  EContactType::Global_));
	}	
	
	/**
	 * @param int $iGroupId
	 * @return bool
	 */
	public function GetGroupEvents($iGroupId)
	{
		if ($iGroupId)
		{
			$sSql = 'SELECT id_group, id_calendar, id_event FROM %sawm_addr_groups_events WHERE id_group = %d';

			return sprintf($sSql, $this->Prefix(), $iGroupId);
		}

		return '';
	}
	
	/**
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return bool
	 */
	public function GetGroupEvent($sCalendarId, $sEventId)
	{
		if ($sCalendarId && $sEventId)
		{
			$sSql = 'SELECT id_group, id_calendar, id_event FROM %sawm_addr_groups_events WHERE id_calendar = %s AND id_event = %s';

			return sprintf($sSql, $this->Prefix(), $this->escapeString($sCalendarId), $this->escapeString($sEventId));
		}

		return '';
	}

	/**
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return string
	 */
	public function AddEventToGroup($iGroupId, $sCalendarId, $sEventId)
	{
		if ($iGroupId && $sCalendarId && $sEventId)
		{
			$sSql = 'INSERT INTO %sawm_addr_groups_events (id_group, id_calendar, id_event) VALUES (%d, %s, %s)';

			return sprintf($sSql, $this->Prefix(), $iGroupId, $this->escapeString($sCalendarId), $this->escapeString($sEventId));
		}

		return '';
	}	
	
	/**
	 * @param int $iGroupId
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return string
	 */
	public function RemoveEventFromGroup($iGroupId, $sCalendarId, $sEventId)
	{
		if ($iGroupId && $sCalendarId && $sEventId)
		{
			$sSql = 'DELETE FROM %sawm_addr_groups_events WHERE id_group = %d AND id_calendar = %s AND id_event = %s';
			return sprintf($sSql, $this->Prefix(), $iGroupId, $this->escapeString($sCalendarId), $this->escapeString($sEventId));
		}

		return '';
	}	
	
	/**
	 * @param string $sCalendarId
	 * @param string $sEventId
	 * @return string
	 */
	public function RemoveEventFromAllGroups($sCalendarId, $sEventId)
	{
		if ($sCalendarId && $sEventId)
		{
			$sSql = 'DELETE FROM %sawm_addr_groups_events WHERE id_calendar = %s AND id_event = %s';
			return sprintf($sSql, $this->Prefix(), $this->escapeString($sCalendarId), $this->escapeString($sEventId));
		}

		return '';
	}		
	
	
}

class CApiContactsmainCommandCreatorPostgreSQL  extends CApiContactsmainCommandCreatorMySQL
{
	// TODO
}
