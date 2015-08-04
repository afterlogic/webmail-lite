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
class CApiUsersNodbStorage extends CApiUsersStorage
{
	const SESS_ACCOUNT_STORAGE = 'sess-acct-storage';
	const SESS_CAL_USERS_STORAGE = 'sess-cal-user-storage';

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('nodb', $oManager);

		CSession::$sSessionName = API_SESSION_WEBMAIL_NAME;
	}

	/**
	 * Retrieves information on WebMail Pro account. Account ID is used for look up.
	 * 
	 * @param int $iAccountId Account identifier.
	 * 
	 * @return CAccount
	 */
	public function getAccountById($iAccountId)
	{
		$oAccount = CSession::get(CApiUsersNodbStorage::SESS_ACCOUNT_STORAGE, null);
		return ($oAccount && $iAccountId === $oAccount->IdAccount) ? clone $oAccount : null;
	}

	/**
	 * Creates WebMail account.
	 * 
	 * @param CAccount &$oAccount Object instance with prepopulated account properties.
	 * 
	 * @return bool
	 */
	public function createAccount(CAccount &$oAccount)
	{
		$oAccount->IdAccount = 1;
		$oAccount->IdUser = 1;
		$oAccount->User->IdUser = 1;

		$oAccount->PreviousMailPassword = '';
		$oAccount->FlushObsolete('PreviousMailPassword');

		CSession::Set(CApiUsersNodbStorage::SESS_ACCOUNT_STORAGE, $oAccount);

		return true;
	}

	/**
	 * Retrieves list of accounts for given WebMail Pro user. 
	 * 
	 * @param int $iUserId User identifier. 
	 * 
	 * @return array | false
	 */
	public function getAccountIdList($iUserId)
	{
		$oAccount = CSession::get(CApiUsersNodbStorage::SESS_ACCOUNT_STORAGE, null);
		return ($oAccount && $iUserId === $oAccount->IdUser) ? array($iUserId) : false;
	}

	/**
	 * Checks if particular account exists. 
	 * 
	 * @param CAccount $oAccount Object instance with prepopulated account properties. 
	 * 
	 * @return bool
	 */
	public function accountExists(CAccount $oAccount)
	{
		return false;
	}

	/**
	 * Saves changes made to the account.
	 * 
	 * @param CAccount &$oAccount Account object containing data to be saved.
	 * 
	 * @return bool
	 */
	public function updateAccount(CAccount $oAccount)
	{
		$oAccount->PreviousMailPassword = '';
		$oAccount->FlushObsolete('PreviousMailPassword');

		CSession::Set(CApiUsersNodbStorage::SESS_ACCOUNT_STORAGE, $oAccount);

		return true;
	}

	/**
	 * Creates calendar user in storage.
	 * 
	 * @param CCalUser &$oCalUser CCalUser object.
	 * 
	 * @return bool
	 */
	public function createCalUser(CCalUser &$oCalUser)
	{
		$oCalUser->IdCalUser = 1;
		$oCalUser->IdUser = 1;

		CSession::Set(CApiUsersNodbStorage::SESS_CAL_USERS_STORAGE, $oCalUser);

		return true;
	}

	/**
	 * Obtains CCalUser object that contains calendar settings for specified user. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return CCalUser
	 */
	public function getCalUser($iUserId)
	{
		$oCalUser = CSession::get(CApiUsersNodbStorage::SESS_ACCOUNT_STORAGE, null);
		return ($oCalUser && $iUserId === $oCalUser->IdUser) ? clone $oCalUser : null;
	}

	/**
	 * Updates calendar user settings.
	 * 
	 * @param CCalUser $oCalUser CCalUser object.
	 * 
	 * @return bool
	 */
	public function updateCalUser(CCalUser $oCalUser)
	{
		CSession::Set(CApiUsersNodbStorage::SESS_CAL_USERS_STORAGE, $oCalUser);

		return true;
	}
}
