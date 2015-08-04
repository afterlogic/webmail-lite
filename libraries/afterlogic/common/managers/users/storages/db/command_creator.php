<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @internal
 * 
 * @package Users
 * @subpackage Storages
 */
class CApiUsersCommandCreator extends api_CommandCreator
{
	/**
	 * Returns query-string for creating user in a_users table.
	 * 
	 * @return string
	 */
	public function createAUserQuery()
	{
		$sSql = 'INSERT INTO %s ( %s ) VALUES ( 0 )';
		return sprintf($sSql, $this->escapeColumn($this->prefix().'a_users'), $this->escapeColumn('deleted'));
	}

	/**
	 * Returns query-string for creating identity in account.
	 * 
	 * @param CIdentity &$oIdentity Identity to create.
	 * 
	 * @return string
	 */
	public function createIdentityQuery(CIdentity $oIdentity)
	{
		$aResults = api_AContainer::DbInsertArrays($oIdentity, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_identities ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		return '';
	}

	/**
	 * Returns query-string for Creating calendar user in storage.
	 * 
	 * @param CCalUser &$oCalUser CCalUser object.
	 * 
	 * @return string
	 */
	public function createCalUserQuery(CCalUser $oCalUser)
	{
		$aResults = api_AContainer::DbInsertArrays($oCalUser, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sacal_users_data ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * Returns query-string for creating WebMail account.
	 * 
	 * @param CAccount &$oAccount Object instance with prepopulated account properties.
	 * 
	 * @return string
	 */
	public function createAccountQuery(CAccount $oAccount)
	{
		$aResults = api_AContainer::DbInsertArrays($oAccount, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_accounts ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		return '';
	}

	/**
	 * Returns query-string for creating user settings and data in awm_settings table.
	 * 
	 * @param CUser $oUser CUser object.
	 * 
	 * @return string
	 */
	public function createUserQuery(CUser $oUser)
	{
		$aResults = api_AContainer::DbInsertArrays($oUser, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_settings ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		return '';
	}

	/**
	 * Returns query-string for enabling/disabling one or several WebMail Pro accounts. 
	 * 
	 * @param array $aAccountsIds List of accounts to be enabled/disabled.
	 * @param bool $bIsEnabled true for enabling accounts, false for disabling them.
	 * 
	 * @return string
	 */
	public function enableAccountsQuery($aAccountsIds, $bIsEnabled)
	{
		$sSql = 'UPDATE %sawm_accounts SET deleted = %d WHERE id_acct IN (%s)';
		return sprintf($sSql, $this->prefix(), !$bIsEnabled, implode(', ', $aAccountsIds));
	}

	/**
	 * Returns query-string for saving changes made to the identity.
	 * 
	 * @param CIdentity &$oIdentity Identity object containing data to be saved.
	 * 
	 * @return string
	 */
	public function updateIdentityQuery(CIdentity $oIdentity)
	{
		$aResult = api_AContainer::DbUpdateArray($oIdentity, $this->oHelper);

		$sSql = 'UPDATE %sawm_identities SET %s WHERE id_identity = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult), $oIdentity->IdIdentity);
	}

	/**
	 * Returns query-string for updatinf value of default identity for account.
	 * 
	 * @param CIdentity $oIdentity New default identity.
	 * @param int $iIdAccount Account identifier.
	 * 
	 * @return bool
	 */
	public function updateIdentitiesDefaultsQuery($iIdentityId, $iIdAccount)
	{
		//$sSql = 'UPDATE %sawm_identities SET def_identity = %s WHERE id_identity <> %d';
		$sSql = 'UPDATE %sawm_identities SET def_identity = %s WHERE id_identity <> %d AND id_acct = %d';
		return sprintf($sSql, $this->prefix(), 0, $iIdentityId, $iIdAccount);
	}

	/**
	 * Returns query-string for saving changes made to the account.
	 * 
	 * @param CAccount &$oAccount Account object containing data to be saved.
	 * 
	 * @return string
	 */
	public function updateAccountQuery(CAccount $oAccount)
	{
		$aResult = api_AContainer::DbUpdateArray($oAccount, $this->oHelper);

		$sSql = 'UPDATE %sawm_accounts SET %s WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult), $oAccount->IdAccount);
	}

	/**
	 * Returns query-string for updating login-related information including time of last login. 
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function updateAccountLastLoginAndCountQuery($iUserId)
	{
		$sSql = 'UPDATE %sawm_settings SET last_login = last_login_now, last_login_now = %s, logins_count = logins_count + 1 WHERE id_user = %d';

		return sprintf($sSql, $this->prefix(),
			$this->escapeString($this->oHelper->TimeStampToDateFormat(gmdate('U'))),
			$iUserId);
	}

	/**
	 * Returns query-string for updating user settings and data in awm_settings table.
	 * 
	 * @param CUser $oUser CUser object.
	 * 
	 * @return string
	 */
	public function updateUserQuery(CUser $oUser)
	{
		$aResult = api_AContainer::DbUpdateArray($oUser, $this->oHelper);

		$sSql = 'UPDATE %sawm_settings SET %s WHERE id_user = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult), $oUser->IdUser);
	}

	/**
	 * Returns query-string for updating calendar user settings.
	 * 
	 * @param CCalUser $oCalUser CCalUser object.
	 * 
	 * @return string
	 */
	public function updateCalUserQuery(CCalUser $oCalUser)
	{
		$aResult = api_AContainer::DbUpdateArray($oCalUser, $this->oHelper);

		$sSql = 'UPDATE %sacal_users_data SET %s WHERE settings_id = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult), $oCalUser->IdCalUser);
	}

	/**
	 * Returns query-string for removing identities by specified condition.
	 * 
	 * @param string $sWhere Specified condition.
	 * 
	 * @return string
	 */
	protected function _deleteIdentitiesByWhereQuery($sWhere)
	{
		$sSql = 'DELETE FROM %sawm_identities WHERE %s';
		return sprintf($sSql, $this->prefix(), $sWhere);
	}

	/**
	 * Returns query-string for deleting identity.
	 * 
	 * @param int $iIdentityId Identity identifier.
	 * 
	 * @return string
	 */
	public function deleteIdentityQuery($iIdentityId)
	{
		return $this->_deleteIdentitiesByWhereQuery(sprintf('%s = %d', 'id_identity', $iIdentityId));
	}

	/**
	 * Returns query-string for deleting identities by specified user identifier.
	 * 
	 * @param int $iUserId Identifier of user wich contains identities to delete.
	 * 
	 * @return string
	 */
	public function deleteIdentitiesByUserIdQuery($iUserId)
	{
		return $this->_deleteIdentitiesByWhereQuery(sprintf('%s = %d', 'id_user', $iUserId));
	}

	/**
	 * Returns query-string for deleting identities by specified account identifier.
	 * 
	 * @param int $iAccountId Identifier of account wich contains identities to delete.
	 * 
	 * @return string
	 */
	public function deleteIdentitiesByAccountIdQuery($iAccountId)
	{
		return $this->_deleteIdentitiesByWhereQuery(sprintf('%s = %d', 'id_acct', $iAccountId));
	}

	/**
	 * Returns query-string for deleting account from WebMail Pro database.
	 * 
	 * @param int $iAccountId Identifier of account to remove.
	 * 
	 * @return string
	 */
	public function deleteAccountQuery($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_accounts WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $iAccountId);
	}

	/**
	 * Returns query-string for deleting account messages from WebMail Pro database.
	 * 
	 * @param int $iAccountId Identifier of account to remove.
	 * 
	 * @return string
	 */
	public function deleteAccountMessagesQuery($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_messages WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $iAccountId);
	}

	/**
	 * Returns query-string for deleting account messages bodies from WebMail Pro database.
	 * 
	 * @param int $iAccountId Identifier of account to remove.
	 * 
	 * @return string
	 */
	public function deleteAccountMessageBodiesQuery($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_messages_body WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $iAccountId);
	}

	/**
	 * Returns query-string for deleting account filters from WebMail Pro database.
	 * 
	 * @param int $iAccountId Identifier of account to remove.
	 * 
	 * @return string
	 */
	public function deleteAccountFiltersQuery($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_filters WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $iAccountId);
	}

	/**
	 * Returns query-string for deleting account reads from WebMail Pro database.
	 * 
	 * @param int $iAccountId Identifier of account to remove.
	 * 
	 * @return string
	 */
	public function deleteAccountReadsQuery($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_reads WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $iAccountId);
	}

	/**
	 * Returns query-string for deleting account folders tree from WebMail Pro database.
	 * 
	 * @param int $iAccountId Identifier of account to remove.
	 * 
	 * @return string
	 */
	public function deleteAccountFoldersTreeQuery($iAccountId)
	{
		// TODO must be overridden
		$sSql = 'SELECT FROM %sawm_folders WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $iAccountId);
	}

	/**
	 * Returns query-string for deleting account folders from WebMail Pro database.
	 * 
	 * @param int $iAccountId Identifier of account to remove.
	 * 
	 * @return string
	 */
	public function deleteAccountFoldersQuery($iAccountId)
	{
		$sSql = 'DELETE FROM %sawm_folders WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $iAccountId);
	}

	/**
	 * Returns query-string for deleting calendar user settings from the storage. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteCalUserQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_users_data WHERE user_id = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for deleting user settings and data from database. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteUserQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_settings WHERE id_user = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for deleting user from database. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteAUserQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sa_users WHERE id_user = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * @ignore
	 * @todo not used
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteGroupsContactsQuery($iUserId)
	{
		$sSql = 'DELETE %sawm_addr_groups_contacts
FROM %sawm_addr_groups_contacts, %sawm_addr_groups
WHERE %sawm_addr_groups_contacts.id_group = %sawm_addr_groups.id_group
AND %sawm_addr_groups.id_user = %d';
		return sprintf($sSql, $this->prefix(), $this->prefix(), $this->prefix(),
			$this->prefix(), $this->prefix(), $this->prefix(), $iUserId);
	}

	/**
	 * @ignore
	 * @todo not used
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteContactsQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_addr_book WHERE id_user = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * @ignore
	 * @todo not used
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteGroupsQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_addr_groups WHERE id_user = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for deleting user senders from database. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteSendersQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_senders WHERE id_user = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for deleting user calendar events from database. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteCalendarEventsQuery($iUserId)
	{
		$sSql = 'DELETE %sacal_events
FROM %sacal_events, %sacal_calendars
WHERE %sacal_events.calendar_id = %sacal_calendars.calendar_id
AND %sacal_calendars.user_id = %d';
		return sprintf($sSql, $this->prefix(), $this->prefix(), $this->prefix(),
			$this->prefix(), $this->prefix(), $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for deleting user calendars from database. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteCalendarsQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_calendars WHERE user_id = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for deleting user calendars' data from database. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteCalendarsDataQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_users_data WHERE user_id = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for deleting user calendars' publications from database. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteCalendarsPublicationsQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_publications WHERE id_user = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for deleting user calendars' sharings from database. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function deleteCalendarsSharingsQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sacal_sharing WHERE id_user = %d';
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for obtaining twilio numbers for default accounts with allowed twilio. Tenant identifier is used for look up.
	 * 
	 * @param $iTenantId Tenant identifier.
	 * 
	 * @return string
	 */
	public function getTwilioNumbersQuery($iTenantId)
	{
		$sSql = 'SELECT sett.twilio_number, sett.twilio_default_number FROM %sawm_settings AS sett '.
			'INNER JOIN %sawm_accounts AS acct ON acct.id_user = sett.id_user '.
			'WHERE sett.twilio_enable = 1 AND sett.twilio_number != \'\' AND acct.def_acct = 1 AND acct.id_tenant = %d';

		return sprintf($sSql, $this->prefix(), $this->prefix(), $iTenantId);
	}
	
	/**
	 * Returns query-string for determining how many users are in particular domain, with optional filtering. Domain identifier is used for look up.
	 * 
	 * @param int $iDomainId Domain identifier.
	 * @param string $sSearchDesc = '' If not empty, only users matching this pattern are counted.
	 * 
	 * @return string
	 */
	public function getUsersCountForDomainQuery($iDomainId, $sSearchDesc = '')
	{
		$sWhere = '';
		if (0 < strlen($sSearchDesc))
		{
			$sSearchDesc = '\'%'.$this->escapeString($sSearchDesc, true, true).'%\'';
			$sWhere = ' AND (email LIKE '.$sSearchDesc.' OR friendly_nm LIKE '.$sSearchDesc.')';
		}

		$sSql = 'SELECT COUNT(id_acct) as users_count FROM %sawm_accounts WHERE def_acct = 1 AND id_domain = %d%s';

		return sprintf($sSql, $this->prefix(), $iDomainId, $sWhere);
	}

	/**
	 * Returns query-string for determining how many users are in particular tenant. Tenant identifier is used for look up.
	 * 
	 * @api
	 * 
	 * @param int $iTenantId Tenant identifier.
	 * 
	 * @return string
	 */
	public function getUsersCountForTenantQuery($iTenantId)
	{
		$sSql = 'SELECT COUNT(id_acct) as users_count FROM %sawm_accounts WHERE def_acct = 1 AND id_tenant = %d';
		return sprintf($sSql, $this->prefix(), $iTenantId);
	}

	/**
	 * Returns query-string for retrieving information about account wich is specified as default. Email address is used for look up.
	 * The method is especially useful in case if your product configuration allows for adding multiple accounts per user.
	 * 
	 * @param string $sEmail Email address associated with the account.
	 * 
	 * @return string
	 */
	public function getAccountByEmailQuery($sEmail)
	{
		return $this->_getAccountByWhereQuery(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('def_acct'), true,
			$this->escapeColumn('email'),
			strtolower($this->escapeString($sEmail)))
		);
	}
	
	/**
	 * Returns query-string for retrieving information on WebMail Pro account. Account ID is used for look up.
	 * 
	 * @param int $mAccountId Account identifier.
	 * @param bool $bIdIsMd5 Default value is **false**.
	 * 
	 * @return string
	 */
	public function getAccountByIdQuery($mAccountId, $bIdIsMd5 = false)
	{
		if ($bIdIsMd5)
		{
			return $this->_getAccountByWhereQuery(sprintf('SUBSTR(MD5(CONCAT(id_acct, %s)),1,8) = %s',
				$this->escapeString(CApi::$sSalt),
				$this->escapeString($mAccountId))
			);
		}
		else
		{
			return $this->_getAccountByWhereQuery(sprintf('%s = %d', 
				$this->escapeColumn('id_acct'), 
				$mAccountId)
			);
		}
	}

	/**
	 * Returns query-string for obtaining CCalUser object that contains calendar settings for specified user. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function getCalUserQuery($iUserId)
	{
		return $this->_getCalUserByWhereQuery(sprintf('%s = %d', $this->escapeColumn('user_id'), $iUserId));
	}

	/**
	 * Returns query-string for obtaining identity.
	 * 
	 * @param int $iIdentityId Indentity identifier.
	 * 
	 * @return string
	 */
	public function getIdentityQuery($iIdentityId)
	{
		$aMap = api_AContainer::DbReadKeys(CIdentity::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_identities WHERE id_identity = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(), $iIdentityId);
	}

	/**
	 * Returns query-string for obtaining list of identities belonging to account.
	 * 
	 * @param int $IdAccount Identifier of account that contains identities to get.
	 * 
	 * @return string
	 */
	public function getAccountIdentitiesQuery($IdAccount)
	{
		$aMap = api_AContainer::DbReadKeys(CIdentity::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_identities WHERE id_acct = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(), $IdAccount);
	}

	/**
	 * Returns query-string for obtaining list of identities belonging to user.
	 * 
	 * @param int $IdUser Identifier of user that contains identities to get.
	 * 
	 * @return string
	 */
	public function getUserIdentitiesQuery($IdUser)
	{
		$aMap = api_AContainer::DbReadKeys(CIdentity::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_identities WHERE id_user = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(), $IdUser);
	}

	/**
	 * Returns query-string for retrieving information on particular WebMail Pro user. 
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function getUserByIdQuery($iUserId)
	{
		return $this->_getUserByWhereQuery(sprintf('%s = %d', $this->escapeColumn('id_user'), $iUserId));
	}

	/**
	 * Returns query-string for obtaining account information.
	 * 
	 * @param int $iAccountId Account identifier.
	 * 
	 * @return string
	 */
	public function getAccountInfoQuery($iAccountId)
	{
		$sSql = 'SELECT id_acct, id_user, mailing_list, def_acct, email FROM %sawm_accounts WHERE id_acct = %d';

		return sprintf($sSql, $this->prefix(), $iAccountId);
	}

	/**
	 * Returns query-string for obtaining domain identifier for primary user account.
	 * 
	 * @param int $iUserId WebMail Pro user identifier (not to be confused with account ID).
	 * 
	 * @return string
	 */
	public function getDefaultAccountDomainIdQuery($iUserId)
	{
		$sSql = 'SELECT id_domain FROM %sawm_accounts WHERE def_acct = 1 AND id_user = %d';

		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for obtaining identifier of primary user account. 
	 * 
	 * @param int $iUserId WebMail Pro user identifier.
	 * 
	 * @return string
	 */
	public function getDefaultAccountIdQuery($iUserId)
	{
		$sSql = 'SELECT id_acct FROM %sawm_accounts WHERE def_acct = 1 AND id_user = %d';

		return sprintf($sSql, $this->prefix(), $iUserId);
	}
	
	/**
	 * Returns query-string for checking if particular account exists. 
	 * 
	 * @param string $sEmail Account email.
	 * @param string $sLogin Account login.
	 * @param int $niExceptAccountId = null Identifier of account wich should be excluded from the search.
	 * 
	 * @return string
	 */
	public function accountExistsQuery($sEmail, $sLogin, $niExceptAccountId = null)
	{
		$sAddSql = (is_integer($niExceptAccountId)) ? ' AND id_acct <> '.$niExceptAccountId : '';

		$sSql = 'SELECT COUNT(id_acct) as acct_count FROM %sawm_accounts
WHERE def_acct = 1 AND %s = %s AND %s = %s %s';

		return sprintf(trim($sSql), $this->prefix(),
			$this->escapeColumn('email'), $this->escapeString(strtolower($sEmail)),
			$this->escapeColumn('mail_inc_login'), $this->escapeString($sLogin),
			$sAddSql);
	}

	/**
	 * Returns query-string for clearing mailing list members.
	 * 
	 * @param int $iMailingListId Mailing list identifier.
	 * 
	 * @return string
	 */
	public function clearMailingListMembersQuery($iMailingListId)
	{
		$sSql = 'DELETE FROM %sawm_mailinglists WHERE %s = %d';

		return sprintf($sSql, $this->prefix(), $this->escapeColumn('id_acct'), $iMailingListId);
	}

	/**
	 * Returns query-string for obtaining account by specified condition.
	 * 
	 * @param string $sWhere Specified condition.
	 * 
	 * @return string
	 */
	protected function _getAccountByWhereQuery($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CAccount::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_accounts WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(), $sWhere);
	}

	/**
	 * Returns query-string for obtaining calendar user by specified condition.
	 * 
	 * @param string $sWhere Specified condition.
	 * 
	 * @return string
	 */
	protected function _getCalUserByWhereQuery($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CCalUser::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sacal_users_data WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(), $sWhere);
	}

	/**
	 * Returns query-string for obtaining user by specified condition.
	 * 
	 * @param string $sWhere Specified condition.
	 * 
	 * @return string
	 */
	protected function _getUserByWhereQuery($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CUser::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_settings WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(), $sWhere);
	}

	/**
	 * Returns query-string for retrieving list of accounts' identifier for given WebMail Pro user. 
	 * 
	 * @param int $iUserId User identifier. 
	 * 
	 * @return string
	 */
	public function getAccountIdListQuery($iUserId)
	{
		$sSql = 'SELECT id_acct FROM %sawm_accounts WHERE id_user = %d';

		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for calculating total number of users registered in WebMail Pro.
	 * 
	 * @return string
	 */
	public function getTotalUsersCountQuery()
	{
		$sSql = 'SELECT COUNT(id_acct) as users_count FROM %sawm_accounts WHERE def_acct = 1';

		return sprintf($sSql, $this->prefix());
	}

	/**
	 * Returns query-string for retrieving list of information about email accounts for specific user.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function getUserAccountsQuery($iUserId)
	{
		$sSql = 'SELECT id_acct, email, def_acct, friendly_nm, signature, signature_opt, signature_type, is_password_specified, allow_mail FROM %sawm_accounts WHERE id_user = %d';

		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for obtaining account identifier for specific user and account email.
	 * 
	 * @param int $iUserId Identifier of user that contains account.
	 * @param string $sEmail Email of account that is looked up.
	 * 
	 * @return int
	 */
	public function getUserAccountIdQuery($iUserId, $sEmail)
	{
		$sSql = 'SELECT id_acct, email, def_acct FROM %sawm_accounts WHERE id_user = %d AND def_acct = 0 AND email = %s';

		return sprintf($sSql, $this->prefix(), $iUserId, $this->escapeString($sEmail));
	}

	/**
	 * Returns query-string for checkinf whether specific address is in safelist for particular user.
	 * 
	 * @param string $iUserId User identifier.
	 * @param string $sEmail Email of sender.
	 * 
	 * @return string
	 */
	public function getSafetySenderQuery($iUserId, $sEmail)
	{
		$sSql = 'SELECT safety FROM %sawm_senders WHERE id_user = %d AND email = %s';

		return sprintf($sSql, $this->prefix(), $iUserId, $this->escapeString($sEmail));
	}

	/**
	 * Returns query-string for inserting safety sender.
	 * 
	 * @param int $iUserId User identifier.
	 * @param string $sEmail Email of safety sender.
	 * 
	 * @return string
	 */
	public function insertSafetySenderQuery($iUserId, $sEmail)
	{
		$sSql = 'INSERT INTO %sawm_senders (id_user, email, safety) VALUES (%d, %s, %d)';

		return sprintf($sSql, $this->prefix(), $iUserId, $this->escapeString($sEmail), 1);
	}

	/**
	 * Returns query-string for purging all entries in safelist of particular user.
	 * 
	 * @param string $iUserId User identifier.
	 * 
	 * @return string
	 */
	public function clearSafetySendersQuery($iUserId)
	{
		$sSql = 'DELETE FROM %sawm_senders WHERE id_user = %d';
		
		return sprintf($sSql, $this->prefix(), $iUserId);
	}

	/**
	 * Returns query-string for obtaining account used space in Kb.
	 * 
	 * @param string $sEmail Email address associated with the account.
	 * 
	 * @return string
	 */
	public function getAccountUsedSpaceQuery($sEmail)
	{
		$sSql = 'SELECT DISTINCT quota_usage_bytes as main_usage FROM %sawm_account_quotas WHERE %s = %s';

		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('name'), $this->escapeString(strtolower($sEmail))
		);
	}
}

/**
 * @package Users
 * @subpackage Storages
 */
class CApiUsersCommandCreatorMySQL extends CApiUsersCommandCreator
{
	/**
	 * Returns query-string for obtaining list of accounts which are specified as default.
	 * 
	 * @return string
	 */
	public function getDefaultAccountListQuery()
	{
		$sSql = 'SELECT id_acct, email, friendly_nm FROM %sawm_accounts
WHERE def_acct = 1';

		return sprintf($sSql, $this->prefix());
	}

	/**
	 * Returns query-string for obtaining list of identifiers of accounts which are specified as default.
	 * Domain identifier is used for look up.
	 * 
	 * @param int $iDomainId Domain identifier.
	 * @param int $iPage List page.
	 * @param int $iUsersPerPage Number of identifiers on a single page.
	 * 
	 * @return string
	 */
	public function getDefaultAccountIdListQuery($iDomainId, $iPage, $iUsersPerPage)
	{
		$sSql = 'SELECT id_acct FROM %sawm_accounts
WHERE def_acct = 1 AND id_domain = %d LIMIT %d OFFSET %d';

		return sprintf($sSql, $this->prefix(), $iDomainId, $iUsersPerPage,
			($iPage > 0) ? ($iPage - 1) * $iUsersPerPage : 0
		);
	}

	/**
	 * Returns query-string for obtaining list of information about users for specific domain.
	 * Domain identifier is used for look up.
	 * 
	 * @param int $iDomainId Domain identifier.
	 * @param int $iPage List page.
	 * @param int $iUsersPerPage Number of users on a single page.
	 * @param string $sOrderBy = 'email'. Field by which to sort.
	 * @param bool $bAscOrderType = true. If **true** the sort order type is ascending.
	 * @param string $sSearchDesc = ''. If specified, the search goes on by substring in the name and email of default account.
	 * 
	 * @return string
	 */
	public function getSelectUserListQuery($iDomainId, $iPage, $iUsersPerPage, $sOrderBy = 'email', $bAscOrderType = true, $sSearchDesc = '')
	{
		$sWhere = '';
		if (!empty($sSearchDesc))
		{
			$sSearchDesc = '\'%'.$this->escapeString($sSearchDesc, true, true).'%\'';
			$sWhere = ' AND (acc.email LIKE '.$sSearchDesc.' OR acc.friendly_nm LIKE '.$sSearchDesc.')';
		}

		if ('last_login' === $sOrderBy)
		{
			$sOrderBy = 'sett.last_login';
		}
		else
		{
			$sOrderBy = empty($sOrderBy) ? 'acc.email' : 'acc.'.$sOrderBy;
		}

		$sSql = 'SELECT acc.id_user, acc.id_acct, acc.email, acc.mailing_list, acc.friendly_nm, acc.deleted, acc.quota, sett.last_login FROM %sawm_accounts AS acc
LEFT JOIN %sawm_settings AS sett ON sett.id_user = acc.id_user
WHERE acc.def_acct = 1 AND acc.id_domain = %d%s ORDER BY %s %s LIMIT %d OFFSET %d';

		return sprintf($sSql, $this->prefix(), $this->prefix(), $iDomainId, $sWhere, $sOrderBy, ((bool) $bAscOrderType) ? 'ASC' : 'DESC',
			$iUsersPerPage, ($iPage > 0) ? ($iPage - 1) * $iUsersPerPage : 0);
	}

	/**
	 * Returns query-string for obtaining account information.
	 * 
	 * @param int $iAccountId Account identifier.
	 * 
	 * @return string
	 */
	public function getAccountInfoQuery($iAccountId)
	{
		return parent::getAccountInfoQuery($iAccountId).' LIMIT 1';
	}

	/**
	 * Returns query-string for checking if particular account exists. 
	 * 
	 * @param string $sEmail Account email.
	 * @param string $sLogin Account login.
	 * @param int $niExceptAccountId = null Identifier of account wich should be excluded from the search.
	 * 
	 * @return string
	 */
	public function accountExistsQuery($sEmail, $sLogin, $niExceptAccountId = null)
	{
		return parent::accountExistsQuery($sEmail, $sLogin, $niExceptAccountId).' LIMIT 1';
	}

	/**
	 * Returns query-string for deleting account folders tree from WebMail Pro database.
	 * 
	 * @param int $iAccountId Identifier of account to remove.
	 * 
	 * @return string
	 */
	public function deleteAccountFoldersTreeQuery($iAccountId)
	{
		$sSql = 'DELETE %sawm_folders_tree
FROM %sawm_folders, %sawm_folders_tree
WHERE %sawm_folders.id_folder = %sawm_folders_tree.id_folder
AND %sawm_folders.id_acct = %d';

		return sprintf($sSql, $this->prefix(), $this->prefix(), $this->prefix(),
			$this->prefix(), $this->prefix(), $this->prefix(), $iAccountId);
	}

	/**
	 * Returns query-string for obtaining account used space in Kb.
	 * 
	 * @param string $sEmail Email address associated with the account.
	 * 
	 * @return string
	 */
	public function getAccountUsedSpaceQuery($sEmail)
	{
		return parent::getAccountUsedSpaceQuery($sEmail).' LIMIT 1';
	}
}

/**
 * @package Users
 * @subpackage Storages
 */
class CApiUsersCommandCreatorPostgreSQL extends CApiUsersCommandCreatorMySQL
{
	// TODO
}

