<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Contacts
 */
class CApiContactsCommandCreator extends api_CommandCreator
{
	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getContactByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CContact::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_addr_book WHERE deleted = 0 AND auto_create = 0 AND %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}

	/**
	 * @param int $iUserId
	 * @param int $iContactId
	 * @return string
	 */
	public function GetContactById($iUserId, $iContactId)
	{
		return $this->getContactByWhere(sprintf('%s = %d AND %s = %d',
			$this->escapeColumn('id_user'), $iUserId, $this->escapeColumn('id_addr'), $iContactId));
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return string
	 */
	public function GetContactByEmail($iUserId, $sEmail)
	{
		return $this->getContactByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_user'), $iUserId,
				$this->escapeColumn('view_email'), $this->escapeString($sEmail)));
	}

	/**
	 * @param int $iUserId
	 * @param string $sContactStrId
	 * @return string
	 */
	public function GetContactByStrId($iUserId, $sContactStrId)
	{
		return $this->getContactByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_user'), $iUserId, $this->escapeColumn('str_id'),
			$this->escapeString($sContactStrId)));
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
	 * @param int $iUserId
	 * @param int $iContactId
	 * @return string
	 */
	public function GetGroupContactsIds($iUserId, $iGroupId)
	{
		$sSql = 'SELECT id_addr FROM %sawm_addr_groups_contacts WHERE id_group = %d';

		return sprintf($sSql, $this->Prefix(), $iGroupId);
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
	 * @param int $iGroupId
	 * @return string
	 */
	public function GetGroupByStrId($iUserId, $sGroupStrId)
	{
		return $this->getGroupByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_user'), $iUserId, $this->escapeColumn('group_str_id'),
			$this->escapeString($sGroupStrId)));
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
		$sSql = 'UPDATE %sawm_addr_book SET %s WHERE id_user = %d AND id_addr = %d';
		return sprintf($sSql, $this->Prefix(),
			implode(', ', api_AContainer::DbUpdateArray($oContact, $this->oHelper)),
			$oContact->IdUser, $oContact->IdContact);
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

			foreach ($oContact->GroupsIds as $iGroupId)
			{
				$aValues[] = '('.$oContact->IdContact.', '.$iGroupId.')';
			}

			return sprintf($sSql, $this->Prefix(), implode(', ', $aValues));
		}
		return '';
	}

	/**
	 * @param CGroup $oGroup
	 * @return string
	 */
	public function UpdateContactIdsInGroup($oGroup)
	{
		if (0 < count($oGroup->ContactsIds))
		{
			$sSql = 'INSERT INTO %sawm_addr_groups_contacts (id_addr, id_group) VALUES %s';
			$aValues = array();
			foreach ($oGroup->ContactsIds as $iContactId)
			{
				$aValues[] = '('.$iContactId.', '.$oGroup->IdGroup.')';
			}

			return sprintf($sSql, $this->Prefix(), implode(', ', $aValues));
		}
		return '';
	}

	/**
	 * @param int $iUserId
	 * @param array $aContactsIds
	 * @return string
	 */
	public function DeleteContacts($iUserId, $aContactsIds)
	{
		$sSql = 'UPDATE %sawm_addr_book SET deleted = 1, date_modified = %s WHERE %s = %d AND %s IN (%s)';

		$aContactsIds = array_map('intval', $aContactsIds);

		return sprintf($sSql, $this->Prefix(), $this->oHelper->TimeStampToDateFormat(time(), true),
			$this->escapeColumn('id_user'), $iUserId, $this->escapeColumn('id_addr'), implode(', ', $aContactsIds));
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
	public function DeleteContactsExceptIds($iUserId, $aContactsIds)
	{
		$sSqlAdd = '';
		if (is_array($aContactsIds) && 0 < count($aContactsIds))
		{
			$sSqlAdd = sprintf(' AND %s NOT IN (%s)', $this->escapeColumn('id_addr'),
				implode(', ', $aContactsIds = array_map('intval', $aContactsIds)));
		}

		$sSql = 'UPDATE %sawm_addr_book SET deleted = 1, date_modified = %s WHERE %s = %d%s';

		return sprintf($sSql, $this->Prefix(), $this->oHelper->TimeStampToDateFormat(time(), true),
			$this->escapeColumn('id_user'), $iUserId, $sSqlAdd);
	}

	/**
	 * @param int $iUserId
	 * @param array $aGroupsIds
	 * @return string
	 */
	public function DeleteGroupsExceptIds($iUserId, $aGroupsIds)
	{
		$sSqlAdd = '';
		if (is_array($aGroupsIds) && 0 < count($aGroupsIds))
		{
			$sSqlAdd = sprintf(' AND %s NOT IN (%s)',
				$this->escapeColumn('id_group'), implode(', ', array_map('intval', $aGroupsIds)));
		}

		$sSql = 'DELETE FROM %sawm_addr_groups WHERE id_user = %d%s';

		return sprintf($sSql, $this->Prefix(), $iUserId, $sSqlAdd);
	}

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
	public function DeleteAllContacts($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_addr_book WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
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
	 * @param string $sEmail
	 * @return string
	 */
	public function GetExistingEmails($iUserId, $aEmails)
	{
		$sSql = 'SELECT view_email FROM %sawm_addr_book WHERE deleted = 0 AND id_user = %d AND view_email IN (%s)';

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
(id_user, h_email, view_email, firstname, primary_email, auto_create, use_frequency, date_created, date_modified)
VALUES (%d, %s, %s, %s, 0, 1, 0, %s, %s)';

		$sEmail = $this->escapeString($sEmail);
		$sName = $this->escapeString($sName);
		$sNow = $this->oHelper->TimeStampToDateFormat(gmdate('U'), true);
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
			$sSql = 'UPDATE %sawm_addr_book	SET use_frequency = use_frequency + 1
WHERE deleted = 0 AND id_user = %d AND view_email IN (%s)';

			$aEmails = array_map(array(&$this, 'escapeString'), $aEmails);
			return sprintf($sSql, $this->Prefix(), $iUserId, implode(', ', $aEmails));
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
class CApiContactsCommandCreatorMySQL extends CApiContactsCommandCreator
{
	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getContactByWhere($sWhere)
	{
		return parent::getContactByWhere($sWhere).' LIMIT 1';
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
		$sSql = 'SELECT id_addr, view_email, primary_email, h_email, b_email, other_email,
use_frequency, fullname, firstname, surname, nickname, use_friendly_nm
FROM %sawm_addr_book WHERE id_user = %d AND deleted = 0 AND auto_create = 0 LIMIT %d, %d';

		return sprintf($sSql, $this->Prefix(), $iUserId, $iOffset, $iRequestLimit);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iRequestLimit
	 * @return string
	 */
	public function GetSuggestContactItems($iUserId, $sSearch, $iRequestLimit)
	{
		$sSearchAdd = '';
		if (!empty($sSearch))
		{
			$sSearch = $this->escapeString($sSearch.'%');
			$sSearchAdd = sprintf(' AND (fullname LIKE %s OR firstname LIKE %s OR surname LIKE %s OR nickname LIKE %s OR h_email LIKE %s OR b_email LIKE %s OR other_email LIKE %s)',
				$sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);
		}

		$sSql = 'SELECT id_addr, view_email, primary_email, h_email, b_email, other_email,
use_frequency, fullname, use_friendly_nm
FROM %sawm_addr_book
WHERE id_user = %d AND deleted = 0%s
ORDER BY use_frequency DESC
LIMIT %d, %d';

		return sprintf($sSql, $this->Prefix(), $iUserId, $sSearchAdd, 0, $iRequestLimit);
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
	 * @return string
	 */
	public function GetContactItems($iUserId, $iSortField, $iSortOrder, $iOffset, $iRequestLimit, $sSearch, $sFirstCharacter, $iGroupId)
	{
		$sGroupAdd = '';
		if (0 < $iGroupId)
		{
			$sGroupAdd = sprintf('
INNER JOIN %sawm_addr_groups_contacts AS gr_cnt ON gr_cnt.id_addr = book.id_addr AND gr_cnt.id_group = %d',
				$this->Prefix(), $iGroupId);
		}

		$sSearchAdd = '';
		if (!empty($sSearch))
		{
			$sSearch = $this->escapeString('%'.$sSearch.'%');
			$sSearchAdd .= sprintf(' AND (book.fullname LIKE %s OR book.firstname LIKE %s OR book.surname LIKE %s OR book.nickname LIKE %s OR book.h_email LIKE %s OR book.b_email LIKE %s OR book.other_email LIKE %s)',
				$sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);
		}

		$sFirstCharacter = (0 < strlen(trim($sFirstCharacter))) ? trim($sFirstCharacter) : '';
		if (!empty($sFirstCharacter))
		{
			$sSearch = $this->escapeString($sFirstCharacter.'%');
			$sSearchAdd .= sprintf(' AND (book.fullname LIKE %s OR book.firstname LIKE %s OR book.surname LIKE %s OR book.nickname LIKE %s OR book.h_email LIKE %s OR book.b_email LIKE %s OR book.other_email LIKE %s)',
				$sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);
		}

		$sSql = 'SELECT book.id_addr, book.view_email, book.primary_email, book.h_email, book.b_email, book.other_email,
book.fullname, book.use_frequency, book.firstname, book.surname, book.use_friendly_nm
FROM %sawm_addr_book AS book%s
WHERE book.id_user = %d AND book.deleted = 0 AND book.auto_create = 0%s
ORDER BY %s %s
LIMIT %d, %d';

		$sField = 'book.'.EContactSortField::GetContactDbField($iSortField);
		$sOrder = (ESortOrder::ASC === $iSortOrder) ? 'ASC' : 'DESC';

		return sprintf($sSql, $this->Prefix(), $sGroupAdd, $iUserId, $sSearchAdd, $sField, $sOrder, $iOffset, $iRequestLimit);
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
			$sSearch = $this->escapeString('%'.$sSearch.'%');
			$sSearchAdd = sprintf(' AND book.group_nm LIKE %s', $sSearch);
		}

		$sFirstCharacter = (0 < strlen(trim($sFirstCharacter))) ? trim($sFirstCharacter) : '';
		if (!empty($sFirstCharacter))
		{
			$sSearch = $this->escapeString($sFirstCharacter.'%');
			$sSearchAdd .= sprintf(' AND book.group_nm LIKE %s', $sSearch);
		}

		$sSql = 'SELECT book.id_group, book.group_nm, book.use_frequency
FROM %sawm_addr_groups AS book%s
WHERE book.id_user = %d%s
ORDER BY %s %s
LIMIT %d, %d';

		$sField = 'book.'.EContactSortField::GetGroupDbField($iSortField);
		$sOrder = (ESortOrder::ASC === $iSortOrder) ? 'ASC' : 'DESC';

		return sprintf($sSql, $this->Prefix(), $sContactAdd, $iUserId, $sSearchAdd, $sField, $sOrder, $iOffset, $iRequestLimit);
	}

	/**
	 * @param int $iUserId
	 * @param string $sSearch
	 * @param int $iGroupId
	 * @return string
	 */
	public function GetContactItemsCount($iUserId, $sSearch, $sFirstCharacter, $iGroupId)
	{
		$sGroupAdd = '';
		if (0 < $iGroupId)
		{
			$sGroupAdd = sprintf('
INNER JOIN %sawm_addr_groups_contacts AS gr_cnt ON gr_cnt.id_addr = book.id_addr AND gr_cnt.id_group = %d',
				$this->Prefix(), $iGroupId);
		}

		$sSearchAdd = '';
		if (!empty($sSearch))
		{
			$sSearch = $this->escapeString('%'.$sSearch.'%');
			$sSearchAdd .= sprintf(' AND (fullname LIKE %s OR firstname LIKE %s OR surname LIKE %s OR nickname LIKE %s OR h_email LIKE %s OR b_email LIKE %s OR other_email LIKE %s)',
				$sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);
		}

		$sFirstCharacter = (0 < strlen(trim($sFirstCharacter))) ? trim($sFirstCharacter) : '';
		if (!empty($sFirstCharacter))
		{
			$sSearch = $this->escapeString($sFirstCharacter.'%');
			$sSearchAdd .= sprintf(' AND (fullname LIKE %s OR firstname LIKE %s OR surname LIKE %s OR nickname LIKE %s OR h_email LIKE %s OR b_email LIKE %s OR other_email LIKE %s)',
				$sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch, $sSearch);
		}

		$sSql = 'SELECT COUNT(book.id_addr) AS cnt FROM %sawm_addr_book as book%s
WHERE book.id_user = %d AND book.deleted = 0 AND book.auto_create = 0%s';

		return sprintf($sSql, $this->Prefix(), $sGroupAdd, $iUserId, $sSearchAdd);
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
			$sSearch = $this->escapeString('%'.$sSearch.'%');
			$sSearchAdd .= sprintf(' AND (group_nm LIKE %s)', $sSearch);
		}

		$sFirstCharacter = (0 < strlen(trim($sFirstCharacter))) ? trim($sFirstCharacter) : '';
		if (!empty($sFirstCharacter))
		{
			$sSearch = $this->escapeString($sFirstCharacter.'%');
			$sSearchAdd .= sprintf(' AND (group_nm LIKE %s)', $sSearch);
		}

		$sSql = 'SELECT COUNT(id_group) AS cnt FROM %sawm_addr_groups WHERE id_user = %d%s';

		return sprintf($sSql, $this->Prefix(), $iUserId, $sSearchAdd);
	}
	
	/**
	 * @return bool
	 */
	public function FlushContacts()
	{
		$sSql = 'DELETE FROM %sawm_addr_book WHERE deleted = 1';

		return sprintf($sSql, $this->Prefix());
	}
}
