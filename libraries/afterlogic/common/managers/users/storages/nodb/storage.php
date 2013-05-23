<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Users
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
	 * @param int $iAccountId
	 * @return CAccount
	 */
	public function GetAccountById($iAccountId)
	{
		$oAccount = CSession::Get(CApiUsersNodbStorage::SESS_ACCOUNT_STORAGE, null);
		return ($oAccount && $iAccountId === $oAccount->IdAccount) ? clone $oAccount : null;
	}

	/**
	 * @param CAccount &$oAccount
	 * @return bool
	 */
	public function CreateAccount(CAccount &$oAccount)
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
	 * @param int $iUserId
	 * @return array | false
	 */
	public function GetUserIdList($iUserId)
	{
		$oAccount = CSession::Get(CApiUsersNodbStorage::SESS_ACCOUNT_STORAGE, null);
		return ($oAccount && $iUserId === $oAccount->IdUser) ? array($iUserId) : false;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function AccountExists(CAccount $oAccount)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function UpdateAccount(CAccount $oAccount)
	{
		$oAccount->PreviousMailPassword = '';
		$oAccount->FlushObsolete('PreviousMailPassword');

		CSession::Set(CApiUsersNodbStorage::SESS_ACCOUNT_STORAGE, $oAccount);

		return true;
	}

	/**
	 * @param CCalUser &$oCalUser
	 * @return bool
	 */
	public function CreateCalUser(CCalUser &$oCalUser)
	{
		$oCalUser->IdCalUser = 1;
		$oCalUser->IdUser = 1;

		CSession::Set(CApiUsersNodbStorage::SESS_CAL_USERS_STORAGE, $oCalUser);

		return true;
	}

	/**
	 * @param int $iUserId
	 * @return CCalUser
	 */
	public function GetCalUserByUserId($iUserId)
	{
		$oCalUser = CSession::Get(CApiUsersNodbStorage::SESS_ACCOUNT_STORAGE, null);
		return ($oCalUser && $iCalUserId === $oCalUser->IdUser) ? clone $oCalUser : null;
	}

	/**
	 * @param CCalUser $oCalUser
	 * @return bool
	 */
	public function UpdateCalUser(CCalUser $oCalUser)
	{
		CSession::Set(CApiUsersNodbStorage::SESS_CAL_USERS_STORAGE, $oCalUser);

		return true;
	}
}
