<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Users
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
	public function GetAccountOnLogin($sEmail)
	{
		return null;
	}

	/**
	 * @param string $sEmail
	 * @return CAccount
	 */
	public function GetAppointmentAccount($sEmail)
	{
		return null;
	}

	/**
	 * @param int $iAccountId
	 * @return CAccount
	 */
	public function GetAccountById($iAccountId)
	{
		return null;
	}

	/**
	 * @param int $iUserId
	 * @return CUser
	 */
	public function GetUserById($iUserId)
	{
		return null;
	}

	/**
	 * @param int $iUserId
	 * @return int
	 */
	public function GetDefaultAccountDomainId($iUserId)
	{
		return -1;
	}

	/**
	 * @param int $iUserId
	 * @return int
	 */
	public function GetDefaultAccountId($iUserId)
	{
		return -1;
	}

	/**
	 * @param string $sEmail
	 * @return int
	 */
	public function GetAccountUsedSpaceInKBytesByEmail($sEmail)
	{
		return 0;
	}

	/**
	 * @param CAccount $oAccount
	 * @return array | bool
	 */
	public function GetIdentities($oAccount)
	{
		return false;
	}

	/**
	 * @param int $iIdentityId
	 * @return CIdentity | bool
	 */
	public function GetIdentity($iIdentityId)
	{
		return false;
	}

	/**
	 * @param CIdentity &$oIdentity
	 * @return bool
	 */
	public function CreateIdentity(CIdentity &$oIdentity)
	{
		return false;
	}

	/**
	 * @param int $iIdentityId
	 * @return bool
	 */
	public function DeleteIdentity($iIdentityId)
	{
		return false;
	}

	/**
	 * @param CIdentity $oIdentity
	 * @return bool
	 */
	public function UpdateIdentity(CIdentity $oIdentity)
	{
		return false;
	}

	/**
	 * @param CAccount &$oAccount
	 * @return bool
	 */
	public function CreateAccount(CAccount &$oAccount)
	{
		return false;
	}

	/**
	 * @param array $aAccountsIds
	 * @param bool $bIsEnabled
	 * @return bool
	 */
	public function EnableAccounts($aAccountsIds, $bIsEnabled)
	{
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function UpdateAccount(CAccount $oAccount)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @return bool
	 */
	public function UpdateAccountLastLoginAndCount($iUserId)
	{
		return false;
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
	 * @param array $iAccountsId
	 * @return bool
	 */
	public function DeleteAccount($iAccountsId)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @return array | false
	 */
	public function GetUserIdList($iUserId)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @return array | false [IdAccount => [IsDefault, Email]]
	 */
	public function GetUserAccountListInformation($iUserId)
	{
		return false;
	}

	/**
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @param string $sOrderBy = 'email'
	 * @param bool $bOrderType = true
	 * @param string $sSearchDesc = ''
	 * @return array | false [IdAccount => [IsMailingList, Email, FriendlyName, IsDisabled, IdUser, StorageQuota, LastLogin]]
	 */
	public function GetUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy = 'name', $bOrderType = true, $sSearchDesc = '')
	{
		return array();
	}

	/**
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @return array | false
	 */
	public function GetUserListIdWithOutOrder($iDomainId, $iPage, $iUsersPerPage)
	{
		return array();
	}

	/**
	 * @return array | false
	 */
	public function GetUserFullList()
	{
		return array();
	}

	/**
	 * @param int $iDomainId
	 * @param string $sSearchDesc = ''
	 * @return int | false
	 */
	public function GetUserCount($iDomainId, $sSearchDesc = '')
	{
		return 0;
	}

	/**
	 * @param int $iTenantId
	 * @return int | false
	 */
	public function GetUserCountByTenantId($iTenantId)
	{
		return 0;
	}

	/**
	 * @return int
	 */
	public function GetCurrentNumberOfUsers()
	{
		return 0;
	}

	/**
	 * @param CCalUser &$oCalUser
	 * @return bool
	 */
	public function CreateCalUser(CCalUser &$oCalUser)
	{
		return false;
	}

	/**
	 * @param int $iUserId
	 * @return CCalUser
	 */
	public function GetCalUserByUserId($iUserId)
	{
		return null;
	}

	/**
	 * @param int $iUserId
	 * @return bool
	 */
	public function DeleteCalUserByUserId($iUserId)
	{
		return false;
	}

	/**
	 * @param CCalUser $oCalUser
	 * @return bool
	 */
	public function UpdateCalUser(CCalUser $oCalUser)
	{
		return false;
	}
}