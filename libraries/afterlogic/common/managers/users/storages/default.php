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
class CApiUsersStorage extends AApiManagerStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct($sStorageName, CApiGlobalManager &$oManager)
	{
		parent::__construct('users', $sStorageName, $oManager);
	}

	/**
	 * @param string $sEmail
	 * @return CAccount
	 */
	public function getAccountByEmail($sEmail)
	{
		return null;
	}

	/**
	 * @param int $iAccountId
	 * @param bool $bIdIsMd5
	 * 
	 * @return CAccount
	 */
	public function getAccountById($iAccountId, $bIdIsMd5 = false)
	{
		return null;
	}

	/**
	 * @param int $iUserId
	 * @return CUser
	 */
	public function getUserById($iUserId)
	{
		return null;
	}

	/**
	 * @param int $iUserId
	 * @return int
	 */
	public function getDefaultAccountDomainId($iUserId)
	{
		return -1;
	}

	/**
	 * @param int $iUserId
	 * @return int
	 */
	public function getDefaultAccountId($iUserId)
	{
		return -1;
	}

	/**
	 * @param string $sEmail
	 * @return int
	 */
	public function getAccountUsedSpace($sEmail)
	{
		return 0;
	}

	/**
	 * @param int $IdAccount
	 * @return array|bool
	 */
	public function getAccountIdentities($IdAccount)
	{
		return false;
	}

	/**
	 * @param int $iIdentityId
	 * @return CIdentity | bool
	 */
	public function getIdentity($iIdentityId)
	{
		return false;
	}

	/**
	 * @param CIdentity &$oIdentity
	 * @return bool
	 */
	public function createIdentity(CIdentity &$oIdentity)
	{
		return false;
	}

	/**
	 * @param int $iIdentityId
	 * @return bool
	 */
	public function deleteIdentity($iIdentityId)
	{
		return false;
	}

	/**
	 * @param CIdentity $oIdentity
	 * @return bool
	 */
	public function updateIdentity(CIdentity $oIdentity)
	{
		return false;
	}

	/**
	 * @param CAccount &$oAccount
	 * @return bool
	 */
	public function createAccount(CAccount &$oAccount)
	{
		return false;
	}

	/**
	 * @param array $aAccountsIds
	 * @param bool $bIsEnabled
	 * @return bool
	 */
	public function enableAccounts($aAccountsIds, $bIsEnabled)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function updateAccount(CAccount $oAccount)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @return bool
	 */
	public function updateAccountLastLoginAndCount($iUserId)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function accountExists(CAccount $oAccount)
	{
		return false;
	}

	/**
	 * @param array $iAccountsId
	 * @return bool
	 */
	public function deleteAccount($iAccountsId)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @return array | false
	 */
	public function getAccountIdList($iUserId)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @return array | false array(int IdAccount => array(bool isDefaultAccount, string email, string friendlyName, string signature, int isSignatureHtml, int isSignatureAdded))
	 */
	public function getUserAccounts($iUserId)
	{
		return false;
	}

	/**
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @param string $sOrderBy = 'email'
	 * @param bool $bAscOrderType = true
	 * @param string $sSearchDesc = ''
	 * @return array | false [IdAccount => [IsMailingList, Email, FriendlyName, IsDisabled, IdUser, StorageQuota, LastLogin]]
	 */
	public function getUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy = 'name', $bAscOrderType = true, $sSearchDesc = '')
	{
		return array();
	}

	/**
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @return array | false
	 */
	public function getDefaultAccountIdList($iDomainId, $iPage, $iUsersPerPage)
	{
		return array();
	}

	/**
	 * @return array | false
	 */
	public function getDefaultAccountList()
	{
		return array();
	}

	/**
	 * @param int $iDomainId
	 * @param string $sSearchDesc = ''
	 * @return int | false
	 */
	public function getUsersCountForDomain($iDomainId, $sSearchDesc = '')
	{
		return 0;
	}

	/**
	 * @param int $iTenantId
	 * @return int | false
	 */
	public function getUsersCountForTenant($iTenantId)
	{
		return 0;
	}

	/**
	 * @return int
	 */
	public function getTotalUsersCount()
	{
		return 0;
	}

	/**
	 * @param CCalUser &$oCalUser
	 * @return bool
	 */
	public function createCalUser(CCalUser &$oCalUser)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @return CCalUser
	 */
	public function getCalUser($iUserId)
	{
		return null;
	}

	/**
	 * @param int $iUserId
	 * @return bool
	 */
	public function deleteCalUser($iUserId)
	{
		return false;
	}

	/**
	 * @param CCalUser $oCalUser
	 * @return bool
	 */
	public function updateCalUser(CCalUser $oCalUser)
	{
		return false;
	}
}