<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Users
 */
class CApiUsersCommandCreator extends api_CommandCreator
{
	/**
	 * @return string
	 */
	public function CreateAUser()
	{
		$sSql = 'INSERT INTO %sa_users ( deleted ) VALUES ( 0 )';
		return sprintf($sSql, $this->Prefix());
	}

	/**
	 * @param CIdentity $oIdentity
	 * @return string
	 */
	public function CreateIdentity(CIdentity $oIdentity)
	{
		$aResults = api_AContainer::DbInsertArrays($oIdentity, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_identities ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		return '';
	}

	/**
	 * @param CCalUser $oCalUser
	 * @return string
	 */
	public function CreateCalUser(CCalUser $oCalUser)
	{
		$aResults = api_AContainer::DbInsertArrays($oCalUser, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sacal_users_data ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CAccount $oAccount
	 * @return string
	 */
	public function CreateAccount(CAccount $oAccount)
	{
		$aResults = api_AContainer::DbInsertArrays($oAccount, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_accounts ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		return '';
	}

	/**
	 * @param CUser $oUser
	 * @return string
	 */
	public function CreateUser(CUser $oUser)
	{
		$aResults = api_AContainer::DbInsertArrays($oUser, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_settings ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		return '';
	}

	/**
	 * @param array $aAccountsIds
	 * @param bool $bIsEnabled
	 * @return string
	 */
	public function EnableAccounts($aAccountsIds, $bIsEnabled)
	{
		$sSql = 'UPDATE %sawm_accounts SET deleted = %d WHERE id_acct IN (%s)';
		return sprintf($sSql, $this->Prefix(), !$bIsEnabled, implode(', ', $aAccountsIds));
	}

	/**
	 * @param CIdentity $oIdentity
	 * @return string
	 */
	public function UpdateIdentity(CIdentity $oIdentity)
	{
		$aResult = api_AContainer::DbUpdateArray($oIdentity, $this->oHelper);

		$sSql = 'UPDATE %sawm_identities SET %s WHERE id_identity = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $oIdentity->IdIdentity);
	}

	/**
	 * @param CAccount $oAccount
	 * @return string
	 */
	public function UpdateAccount(CAccount $oAccount)
	{
		$aResult = api_AContainer::DbUpdateArray($oAccount, $this->oHelper);

		$sSql = 'UPDATE %sawm_accounts SET %s WHERE id_acct = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $oAccount->IdAccount);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function UpdateAccountLastLoginAndCount($iUserId)
	{
		$sSql = 'UPDATE %sawm_settings SET last_login = %s, logins_count = logins_count + 1 WHERE id_user = %d';

		return sprintf($sSql, $this->Prefix(),
			$this->escapeString($this->oHelper->TimeStampToDateFormat(gmdate('U'))),
			$iUserId);
	}

	/**
	 * @param CUser $oUser
	 * @return string
	 */
	public function UpdateUser(CUser $oUser)
	{
		$aResult = api_AContainer::DbUpdateArray($oUser, $this->oHelper);

		$sSql = 'UPDATE %sawm_settings SET %s WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $oUser->IdUser);
	}

	/**
	 * @param CCalUser $oCalUser
	 * @return string
	 */
	public function UpdateCalUser(CCalUser $oCalUser)
	{
		$aResult = api_AContainer::DbUpdateArray($oCalUser, $this->oHelper);

		$sSql = 'UPDATE %sacal_users_data SET %s WHERE settings_id = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $oCalUser->IdCalUser);
	}

	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function deleteIdentitiesByWhere($sWhere)
	{
		$sSql = 'DELETE FROM %sawm_identities WHERE %s';
		return sprintf($sSql, $this->Prefix(), $sWhere);
	}

	/**
	 * @param int $iIdentityId
	 * @return string
	 */
	public function DeleteIdentity($iIdentityId)
	{
		return $this->deleteIdentitiesByWhere(sprintf('%s = %d', 'id_identity', $iIdentityId));
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteIdentitiesByUserId($iUserId)
	{
		return $this->deleteIdentitiesByWhere(sprintf('%s = %d', 'id_user', $iUserId));
	}

	/**
	 * @param int $iAccountId
	 * @return string
	 */
	public function DeleteIdentitiesByAccountId($iAccountId)
	{
		return $this->deleteIdentitiesByWhere(sprintf('%s = %d', 'id_acct', $iAccountId));
	}

	/**
	 * @param int $iAccountsId
	 * @return string
	 */
	public function DeleteAccount($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_accounts WHERE id_acct = %d';
		return sprintf($sSql, $this->Prefix(), $iAccountId);
	}

	/**
	 * @param int $iAccountsId
	 * @return string
	 */
	public function DeleteAccountMessages($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_messages WHERE id_acct = %d';
		return sprintf($sSql, $this->Prefix(), $iAccountId);
	}

	/**
	 * @param int $iAccountsId
	 * @return string
	 */
	public function DeleteAccountMessageBodies($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_messages_body WHERE id_acct = %d';
		return sprintf($sSql, $this->Prefix(), $iAccountId);
	}

	/**
	 * @param int $iAccountsId
	 * @return string
	 */
	public function DeleteAccountFilters($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_filters WHERE id_acct = %d';
		return sprintf($sSql, $this->Prefix(), $iAccountId);
	}

	/**
	 * @param int $iAccountsId
	 * @return string
	 */
	public function DeleteAccountReads($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_reads WHERE id_acct = %d';
		return sprintf($sSql, $this->Prefix(), $iAccountId);
	}

	/**
	 * @param int $iAccountsId
	 * @return string
	 */
	public function DeleteAccountFoldersTree($iAccountId)
	{
		// TODO must be overridden
		$sSql = 'SELECT FROM %sawm_folders WHERE id_acct = %d';
		return sprintf($sSql, $this->Prefix(), $iAccountId);
	}

	/**
	 * @param int $iAccountsId
	 * @return string
	 */
	public function DeleteAccountFolders($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_folders WHERE id_acct = %d';
		return sprintf($sSql, $this->Prefix(), $iAccountId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteCalUserByUserId($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_users_data WHERE user_id = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteUser($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_settings WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteAUser($iUserId)
	{
		$sSql = 'DELETE FROM %sa_users WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteGroupsContacts($iUserId)
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
	public function DeleteContacts($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_addr_book WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteGroups($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_addr_groups WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteSenders($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_senders WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteCalendarEvents($iUserId)
	{
		$sSql = 'DELETE %sacal_events
FROM %sacal_events, %sacal_calendars
WHERE %sacal_events.calendar_id = %sacal_calendars.calendar_id
AND %sacal_calendars.user_id = %d';
		return sprintf($sSql, $this->Prefix(), $this->Prefix(), $this->Prefix(),
			$this->Prefix(), $this->Prefix(), $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteCalendarCalendars($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_calendars WHERE user_id = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteCalendarUserData($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_users_data WHERE user_id = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteCalendarPublications($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_publications WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function DeleteCalendarSharing($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_sharing WHERE id_user = %d';
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param string $sEmail
	 * @return string
	 */
	public function DeleteFnblPimContact($sEmail)
	{
		$sSql = 'DELETE FROM fnbl_pim_contact WHERE userid = %s';
		return sprintf($sSql, $this->escapeString($sEmail));
	}

	/**
	 * @param string $sEmail
	 * @return string
	 */
	public function DeleteFnblPimCalendar($sEmail)
	{
		$sSql = 'DELETE FROM fnbl_pim_calendar WHERE userid = %s';
		return sprintf($sSql, $this->escapeString($sEmail));
	}

	/**
	 * @param int $iDomainId
	 * @param string $sSearchDesc = ''
	 * @return string
	 */
	public function GetUserCount($iDomainId, $sSearchDesc = '')
	{
		$sWhere = '';
		if (!empty($sSearchDesc))
		{
			$sWhere = ' AND (email LIKE '.$this->escapeString('%'.strtolower($sSearchDesc).'%').' OR friendly_nm LIKE '.$this->escapeString('%'.$sSearchDesc.'%').')';
		}

		$sSql = 'SELECT COUNT(id_acct) as users_count FROM %sawm_accounts WHERE def_acct = 1 AND id_domain = %d%s';

		return sprintf($sSql, $this->Prefix(), $iDomainId, $sWhere);
	}

	/**
	 * @param int $iRealmId
	 * @return string
	 */
	public function GetUserCountByRealmId($iRealmId)
	{
		$sSql = 'SELECT COUNT(id_acct) as users_count FROM %sawm_accounts WHERE def_acct = 1 AND id_realm = %d';
		return sprintf($sSql, $this->Prefix(), $iRealmId);
	}

	/**
	 * @param string $sEmail
	 * @return string
	 */
	public function GetAccountOnLogin($sEmail)
	{
		return $this->getAccountByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('def_acct'), true,
			$this->escapeColumn('email'),
			strtolower($this->escapeString($sEmail)))
		);
	}

	/**
	 * @param int $iAccountId
	 * @return string
	 */
	public function GetAccountById($iAccountId)
	{
		return $this->getAccountByWhere(sprintf('%s = %d', $this->escapeColumn('id_acct'), $iAccountId));
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function GetCalUserByUserId($iUserId)
	{
		return $this->getCalUserByWhere(sprintf('%s = %d', $this->escapeColumn('user_id'), $iUserId));
	}

	/**
	 * @param int $iIdentityId
	 * @return string
	 */
	public function GetIdentity($iIdentityId)
	{
		$aMap = api_AContainer::DbReadKeys(CIdentity::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_identities WHERE id_identity = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $iIdentityId);
	}

	/**
	 * @param CAccount $oAccount
	 * @return string
	 */
	public function GetIdentities($oAccount)
	{
		$aMap = api_AContainer::DbReadKeys(CIdentity::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_identities WHERE id_acct = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $oAccount->IdAccount);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function GetUserById($iUserId)
	{
		return $this->getUserByWhere(sprintf('%s = %d', $this->escapeColumn('id_user'), $iUserId));
	}

	/**
	 * @param int $iAccountId
	 * @return string
	 */
	public function GetAccountInfo($iAccountId)
	{
		$sSql = 'SELECT id_acct, id_user, mailing_list, def_acct, email FROM %sawm_accounts WHERE id_acct = %d';

		return sprintf($sSql, $this->Prefix(), $iAccountId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function GetDefaultAccountDomainId($iUserId)
	{
		$sSql = 'SELECT id_domain FROM %sawm_accounts WHERE def_acct = 1 AND id_user = %d';

		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function GetDefaultAccountId($iUserId)
	{
		$sSql = 'SELECT id_acct FROM %sawm_accounts WHERE def_acct = 1 AND id_user = %d';

		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param string $sEmail
	 * @param int $niExceptAccountId = null
	 * @return string
	 */
	public function AccountExists($sEmail, $sLogin, $niExceptAccountId = null)
	{
		$sAddSql = (is_integer($niExceptAccountId)) ? ' AND id_acct <> '.$niExceptAccountId : '';

		$sSql = 'SELECT COUNT(id_acct) as acct_count FROM %sawm_accounts
WHERE def_acct = 1 AND %s = %s AND %s = %s %s';

		return sprintf(trim($sSql), $this->Prefix(),
			$this->escapeColumn('email'), $this->escapeString(strtolower($sEmail)),
			$this->escapeColumn('mail_inc_login'), $this->escapeString($sLogin),
			$sAddSql);
	}

	/**
	 * @param int $iMailingListId
	 * @return string
	 */
	public function ClearMailingListMembers($iMailingListId)
	{
		$sSql = 'DELETE FROM %sawm_mailinglists WHERE %s = %d';

		return sprintf($sSql, $this->Prefix(), $this->escapeColumn('id_acct'), $iMailingListId);
	}

	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getAccountByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CAccount::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_accounts WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}

	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getCalUserByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CCalUser::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sacal_users_data WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}

	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getUserByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CUser::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_settings WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function GetUserIdList($iUserId)
	{
		$sSql = 'SELECT id_acct FROM %sawm_accounts WHERE id_user = %d';

		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param string $sEmail
	 * @return string
	 */
	public function GetAppointmentAccount($sEmail)
	{
		$sEmail = $this->escapeString($sEmail);

		return $this->getAccountByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('def_acct'), true,
			$this->escapeColumn('email'), strtolower($sEmail)
		));
	}

	/**
	 * @return string
	 */
	public function GetCurrentNumberOfUsers()
	{
		$sSql = 'SELECT COUNT(id_acct) as users_count FROM %sawm_accounts WHERE def_acct = 1';

		return sprintf($sSql, $this->Prefix());
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function GetUserAccountListInformation($iUserId)
	{
		$sSql = 'SELECT id_acct, email, def_acct FROM %sawm_accounts WHERE id_user = %d';

		return sprintf($sSql, $this->Prefix(), $iUserId);
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return string
	 */
	public function GetSafetySender($iUserId, $sEmail)
	{
		$sSql = 'SELECT safety FROM %sawm_senders WHERE id_user = %d AND email = %s';

		return sprintf($sSql, $this->Prefix(), $iUserId, $this->escapeString($sEmail));
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return string
	 */
	public function InsertSafetySender($iUserId, $sEmail)
	{
		$sSql = 'INSERT INTO %sawm_senders (id_user, email, safety) VALUES (%d, %s, %d)';

		return sprintf($sSql, $this->Prefix(), $iUserId, $this->escapeString($sEmail), 1);
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function ClearSafetySenders($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_senders WHERE id_user = %d';
		
		return sprintf($sSql, $this->Prefix(), $iUserId);
	}
}

/**
 * @package Users
 */
class CApiUsersCommandCreatorMySQL extends CApiUsersCommandCreator
{
	/**
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @return string
	 */
	public function GetUserListIdWithOutOrder($iDomainId, $iPage, $iUsersPerPage)
	{
		$sSql = 'SELECT id_acct FROM %sawm_accounts
WHERE def_acct = 1 AND id_domain = %d LIMIT %d, %d';

		return sprintf($sSql, $this->Prefix(), $iDomainId, ($iPage > 0) ? ($iPage - 1) * $iUsersPerPage : 0, $iUsersPerPage);
	}

	/**
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @param string $sOrderBy = 'email'
	 * @param bool $bOrderType = true
	 * @param string $sSearchDesc = ''
	 * @return string
	 */
	public function GetUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy = 'email', $bOrderType = true, $sSearchDesc = '')
	{
		$sWhere = '';
		if (!empty($sSearchDesc))
		{
			$sWhere = ' AND (email LIKE '.$this->escapeString('%'.strtolower($sSearchDesc).'%').' OR friendly_nm LIKE '.$this->escapeString('%'.$sSearchDesc.'%').')';
		}

		$sOrderBy = empty($sOrderBy) ? 'email' : $sOrderBy;

		$sSql = 'SELECT id_user, id_acct, email, mailing_list, friendly_nm, deleted, quota FROM %sawm_accounts
WHERE def_acct = 1 AND id_domain = %d%s ORDER BY %s %s LIMIT %d, %d';

		return sprintf($sSql, $this->Prefix(), $iDomainId, $sWhere, $sOrderBy, ((bool) $bOrderType) ? 'ASC' : 'DESC',
			($iPage > 0) ? ($iPage - 1) * $iUsersPerPage : 0, $iUsersPerPage);
	}

	/**
	 * @param int $iAccountId
	 * @return string
	 */
	public function GetAccountInfo($iAccountId)
	{
		return parent::GetAccountInfo($iAccountId).' LIMIT 1';
	}

	/**
	 * @param string $sEmail
	 * @param int $niExceptAccountId = null
	 * @return string
	 */
	public function AccountExists($sEmail, $sLogin, $niExceptAccountId = null)
	{
		return parent::AccountExists($sEmail, $sLogin, $niExceptAccountId).' LIMIT 1';
	}

	/**
	 * @param int $iAccountsId
	 * @return string
	 */
	public function DeleteAccountFoldersTree($iAccountId)
	{
		$sSql = 'DELETE %sawm_folders_tree
FROM %sawm_folders, %sawm_folders_tree
WHERE %sawm_folders.id_folder = %sawm_folders_tree.id_folder
AND %sawm_folders.id_acct = %d';

		return sprintf($sSql, $this->Prefix(), $this->Prefix(), $this->Prefix(),
			$this->Prefix(), $this->Prefix(), $this->Prefix(), $iAccountId);
	}
}
