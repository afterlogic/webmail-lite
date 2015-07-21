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
class CApiUsersDbStorage extends CApiUsersStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * @var CApiUsersCommandCreator
	 */
	protected $oCommandCreator;

	/**
	 * Creates a new instance of the object.
	 * 
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('db', $oManager);

		$this->oConnection =& $oManager->GetConnection();
		$this->oCommandCreator =& $oManager->GetCommandCreator(
			$this, array(
				EDbType::MySQL => 'CApiUsersCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiUsersCommandCreatorPostgreSQL'
			)
		);
	}

	/**
	 * Retrieves information about account wich is specified as default. Email address is used for look up.
	 * The method is especially useful in case if your product configuration allows for adding multiple accounts per user.
	 * 
	 * @param string $sEmail Email address associated with the account.
	 * 
	 * @return CAccount
	 */
	public function getAccountByEmail($sEmail)
	{
		return $this->_getAccountBySql($this->oCommandCreator->getAccountByEmailQuery($sEmail));
	}
	
	/**
	 * Retrieves information on WebMail Pro account. Account ID is used for look up.
	 * 
	 * @param int $mAccountId Account identifier.
	 * @param mixed $mAccountId
	 * @param bool $bIdIsMd5 Default value is **false**.
	 * 
	 * @return CAccount
	 */
	public function getAccountById($mAccountId, $bIdIsMd5 = false)
	{
		if (!$bIdIsMd5)
		{
			$mAccountId = (int) $mAccountId;
		}
		return $this->_getAccountBySql($this->oCommandCreator->getAccountByIdQuery($mAccountId, $bIdIsMd5));
	}

	/**
	 * Retrieves information on particular WebMail Pro user. 
	 * 
	 * @param int $iUserId User identifier.
	 * @param CDomain $oDomain
	 * 
	 * @return CUser | false
	 */
	public function getUserById($iUserId, $oDomain = null)
	{
		$oUser = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getUserByIdQuery($iUserId)))
		{
			$oUser = null;

			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				if (null === $oDomain)
				{
					/* @var $oApiDomainsManager CApiDomainsManager */
					$oApiDomainsManager = CApi::Manager('domains');
					$oDomain = $oApiDomainsManager->getDefaultDomain();
				}

				$oUser = new CUser($oDomain);
				$oUser->InitByDbRow($oRow);
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $oUser;
	}

	/**
	 * Returns domain identifier for primary user account. The method is especially useful in case
	 * if your product configuration allows for adding multiple accounts per user. 
	 * 
	 * @param int $iUserId WebMail Pro user identifier (not to be confused with account ID).
	 * 
	 * @return int
	 */
	public function getDefaultAccountDomainId($iUserId)
	{
		$iResult = -1;
		if ($this->oConnection->Execute($this->oCommandCreator->getDefaultAccountDomainIdQuery($iUserId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->id_domain;
				if (1 > $iResult)
				{
					$iResult = -1;
				}
			}

			$this->oConnection->FreeResult();
		}
		return $iResult;
	}

	/**
	 * Returns identifier of primary user account. 
	 * 
	 * @param int $iUserId WebMail Pro user identifier.
	 * 
	 * @return int
	 */
	public function getDefaultAccountId($iUserId)
	{
		$iResult = -1;
		if ($this->oConnection->Execute($this->oCommandCreator->getDefaultAccountIdQuery($iUserId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->id_acct;
				if (1 > $iResult)
				{
					$iResult = -1;
				}
			}

			$this->oConnection->FreeResult();
		}
		return $iResult;
	}

	/**
	 * Returns account used space in Kb.
	 * 
	 * @param string $sEmail Email address associated with the account.
	 * 
	 * @return int
	 */
	public function getAccountUsedSpace($sEmail)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->getAccountUsedSpaceQuery($sEmail)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$sQuotaUsageBytes = (string) $oRow->main_usage;
				if (0 < strlen($sQuotaUsageBytes) && is_numeric($sQuotaUsageBytes))
				{
					$iResult = (int) ($sQuotaUsageBytes / 1024);
				}
			}

			$this->oConnection->FreeResult();
		}
		return $iResult;
	}

	/**
	 * Returns identity.
	 * 
	 * @param int $iIdentityId Indentity identifier.
	 * 
	 * @return CIdentity | bool
	 */
	public function getIdentity($iIdentityId)
	{
		$oIdentity = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getIdentityQuery($iIdentityId)))
		{
			$oIdentity = $oRow = null;
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oIdentity = new CIdentity();
				$oIdentity->InitByDbRow($oRow);
			}
		}

		$this->throwDbExceptionIfExist();
		return $oIdentity;
	}

	/**
	 * Returns list of identities belonging to account.
	 * 
	 * @param int $IdAccount Identifier of account that contains identities to get.
	 * 
	 * @return array|bool
	 */
	public function getAccountIdentities($IdAccount)
	{
		$aIdentities = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getAccountIdentitiesQuery($IdAccount)))
		{
			$aIdentities = array();

			$oRow = null;
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oIdentity = new CIdentity();
				$oIdentity->InitByDbRow($oRow);
				$aIdentities[] = $oIdentity;
			}
		}

		$this->throwDbExceptionIfExist();
		return $aIdentities;
	}

	/**
	 * Returns list of identities belonging to user.
	 * 
	 * @param int $IdUser Identifier of user that contains identities to get.
	 * 
	 * @return array|bool
	 */
	public function getUserIdentities($IdUser)
	{
		$aIdentities = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getUserIdentitiesQuery($IdUser)))
		{
			$aIdentities = array();

			$oRow = null;
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oIdentity = new CIdentity();
				$oIdentity->InitByDbRow($oRow);
				$aIdentities[] = $oIdentity;
			}
		}

		$this->throwDbExceptionIfExist();
		return $aIdentities;
	}

	/**
	 * Obtains CAccount object by query-string.
	 * 
	 * @param string $sSql Query-string for obtaining CAccount object.
	 * 
	 * @return CAccount
	 */
	protected function _getAccountBySql($sSql)
	{
		$oAccount = false;
		if ($this->oConnection->Execute($sSql))
		{
			$oAccount = null;

			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$this->oConnection->FreeResult();
				
				/* @var $oApiDomainsManager CApiDomainsManager */
				$oApiDomainsManager = CApi::Manager('domains');

				$oDomain = null;
				$iDomainId = $oRow->id_domain;
				if (0 < $iDomainId)
				{
					$oDomain = $oApiDomainsManager->getDomainById($iDomainId);
				}
				else
				{
					$oDomain = $oApiDomainsManager->getDefaultDomain();
				}

				if ($oDomain)
				{
					$oAccount = new CAccount($oDomain);
					$oAccount->initByDbRow($oRow);

					$oUser = $this->getUserById($oAccount->IdUser, $oDomain);
					if ($oUser)
					{
						$oAccount->User = $oUser;
					}
					else
					{
						$oAccount = null;
					}
				}
			}
			else
			{
				$this->oConnection->FreeResult();
			}
		}

		$this->throwDbExceptionIfExist();
		return $oAccount;
	}

	/**
	 * Obtains CCalUser object by query-string.
	 * 
	 * @param string $sSql Query-string for obtaining CCalUser object.
	 * 
	 * @return CCalUser
	 */
	protected function _getCalUserBySql($sSql)
	{
		$oCalUser = false;
		if ($this->oConnection->Execute($sSql))
		{
			$oCalUser = null;

			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oCalUser = new CCalUser(0);
				$oCalUser->InitByDbRow($oRow);
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $oCalUser;
	}

	/**
	 * Creates identity in account.
	 * 
	 * @param CIdentity &$oIdentity Identity to create.
	 * 
	 * @return bool
	 */
	public function createIdentity(CIdentity &$oIdentity)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createIdentityQuery($oIdentity)))
		{
			$oIdentity->IdIdentity = $this->oConnection->GetLastInsertId('awm_identities', 'id_identity');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
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
		$bResult = false;
		$bUserExist = true;
		$iUserId = $oAccount->IdUser;
		
		if (0 === $iUserId && !$oAccount->IsMailingList)
		{
			$bUserExist = false;
			if ($this->oConnection->Execute($this->oCommandCreator->createAUserQuery()))
			{
				$iUserId = $this->oConnection->GetLastInsertId('a_users', 'id_user');
			}

			if (0 < $iUserId)
			{
				$oAccount->IdUser = $iUserId;
				$oAccount->User->IdUser = $iUserId;
				$oAccount->User->CreatedTime = time();

				$bUserExist = $this->oConnection->Execute(
					$this->oCommandCreator->createUserQuery($oAccount->User));
			}
		}

		if ($bUserExist)
		{
			if ($this->oConnection->Execute($this->oCommandCreator->createAccountQuery($oAccount)))
			{
				$oAccount->IdAccount = $this->oConnection->GetLastInsertId('awm_accounts', 'id_acct');
				$bResult = true;
			}
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * Enable/disable one or several WebMail Pro accounts. 
	 * 
	 * @param array $aAccountsIds List of accounts to be enabled/disabled.
	 * @param bool $bIsEnabled true for enabling accounts, false for disabling them.
	 * 
	 * @return bool
	 */
	public function enableAccounts($aAccountsIds, $bIsEnabled)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->enableAccountsQuery($aAccountsIds, $bIsEnabled));
		$this->throwDbExceptionIfExist();
		return $bResult;
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
		$bResult = (bool) (
			$this->oConnection->Execute($this->oCommandCreator->updateAccountQuery($oAccount)) &&
			$this->oConnection->Execute($this->oCommandCreator->updateUserQuery($oAccount->User))
		);
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * Saves changes made to the identity.
	 * 
	 * @param CIdentity &$oIdentity Identity object containing data to be saved.
	 * 
	 * @return bool
	 */
	public function updateIdentity(CIdentity $oIdentity)
	{
		$bResult = (bool) $this->oConnection->Execute(
			$this->oCommandCreator->updateIdentityQuery($oIdentity));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * Updates value of default identity for account.
	 * 
	 * @param CIdentity $oIdentity New default identity.
	 * @param int $iIdAccount Account identifier.
	 * 
	 * @return bool
	 */
	public function updateIdentitiesDefaults($iIdentityId, $iIdAccount)
	{
		$bResult = (bool) $this->oConnection->Execute(
			$this->oCommandCreator->updateIdentitiesDefaultsQuery($iIdentityId, $iIdAccount));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * For the given user, updates login-related information including time of last login. 
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return bool
	 */
	public function updateAccountLastLoginAndCount($iUserId)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->updateAccountLastLoginAndCountQuery($iUserId));

		$this->throwDbExceptionIfExist();
		return $bResult;
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
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->accountExistsQuery(
			$oAccount->Email, $oAccount->IncomingMailLogin, (0 < $oAccount->IdAccount) ? $oAccount->IdAccount : null)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow && 0 < (int) $oRow->acct_count)
			{
				$bResult = true;
			}
			
			$this->oConnection->FreeResult();
		}
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * Deletes identity.
	 * 
	 * @param int $iIdentityId Identity identifier.
	 * 
	 * @return bool
	 */
	public function deleteIdentity($iIdentityId)
	{
		return $this->oConnection->Execute($this->oCommandCreator->deleteIdentityQuery($iIdentityId));
	}

	/**
	 * Deletes account from WebMail Pro database. 
	 * 
	 * @param CAccount $oAccount Object instance with prepopulated account properties.
	 * 
	 * @return bool
	 */
	public function deleteAccount($iAccountId)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getAccountInfoQuery($iAccountId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$bIsDefaultAccount = (bool) $oRow->def_acct;
				$bIsMailingList = (bool) $oRow->mailing_list;
				$iUserId = (int) $oRow->id_user;

				$this->oConnection->FreeResult();

				if ($bIsMailingList)
				{
					$bResult = $this->oConnection->Execute($this->oCommandCreator->deleteAccountQuery($iAccountId));
					$bResult &= $this->oConnection->Execute($this->oCommandCreator->clearMailingListMembersQuery($iAccountId));
				}
				else
				{
					$bResult = false;
					if ($bIsDefaultAccount)
					{
						$aAccountsId = $this->getAccountIdList($iUserId);
						if (is_array($aAccountsId) && 0 < count($aAccountsId))
						{
							$bResult = true;
							foreach ($aAccountsId as $iAccountIdItem)
							{
								$bResult &= $this->_deleteAccountRequests($iAccountIdItem);
							}

							// Webmail
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteUserQuery($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteAUserQuery($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteSendersQuery($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteIdentitiesByUserIdQuery($iUserId));

							// Calendar
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteCalendarEventsQuery($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteCalendarsQuery($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteCalendarsDataQuery($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteCalendarsPublicationsQuery($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteCalendarsSharingsQuery($iUserId));

							$this->throwDbExceptionIfExist();
						}
					}
					else
					{
						$bResult = $this->_deleteAccountRequests($iAccountId);
					}
				}
			}
			else
			{
				$this->oConnection->FreeResult();
			}
		}

		$this->throwDbExceptionIfExist();
		return (bool) $bResult;
	}

	/**
	 * Removes all account data from database.
	 * 
	 * @param int $iAccountId Identifier of account to remove.
	 * 
	 * @return bool
	 */
	protected function _deleteAccountRequests($iAccountId)
	{
		$bResult = true;
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteAccountQuery($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteAccountMessagesQuery($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteAccountMessageBodiesQuery($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteAccountFiltersQuery($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteAccountReadsQuery($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteAccountFoldersTreeQuery($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteAccountFoldersQuery($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->deleteIdentitiesByAccountIdQuery($iAccountId));
		return (bool) $bResult;
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
		$aAccountsIds = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getAccountIdListQuery($iUserId)))
		{
			$oRow = null;
			$aAccountsIds = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aAccountsIds[] = (int) $oRow->id_acct;
			}
		}
		$this->throwDbExceptionIfExist();
		return $aAccountsIds;
	}

	/**
	 * Retrieves list of information about email accounts for specific user.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return array | false array(int IdAccount => array(bool isDefaultAccount, string email, string friendlyName, string signature, int isSignatureHtml, int isSignatureAdded))
	 */
	public function getUserAccounts($iUserId)
	{
		$aResult = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getUserAccountsQuery($iUserId)))
		{
			$oRow = null;
			$aResult = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aResult[$oRow->id_acct] = array(
					(bool) $oRow->def_acct, 
					$oRow->email, 
					trim($oRow->friendly_nm), 
					trim($oRow->signature), 
					(int) $oRow->signature_type, 
					(int) $oRow->signature_opt, 
					(bool) $oRow->is_password_specified,
					(bool) $oRow->allow_mail
				);
			}
		}
		$this->throwDbExceptionIfExist();
		return $aResult;
	}

	/**
	 * Returns account identifier for specific user and account email.
	 * 
	 * @param int $iUserId Identifier of user that contains account.
	 * @param string $sEmail Email of account that is looked up.
	 * 
	 * @return int | false
	 */
	public function getUserAccountId($iUserId, $sEmail)
	{
		$iResult = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getUserAccountIdQuery($iUserId, $sEmail)))
		{
			$oRow = null;
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$iResult = $oRow->id_acct;
			}
		}
		$this->throwDbExceptionIfExist();
		return $iResult;
	}

	/**
	 * Obtains list of information about users for specific domain. Domain identifier is used for look up.
	 * The answer contains information only about default account of founded user.
	 * 
	 * @param int $iDomainId Domain identifier.
	 * @param int $iPage List page.
	 * @param int $iUsersPerPage Number of users on a single page.
	 * @param string $sOrderBy = 'email'. Field by which to sort.
	 * @param bool $bAscOrderType = true. If **true** the sort order type is ascending.
	 * @param string $sSearchDesc = ''. If specified, the search goes on by substring in the name and email of default account.
	 * 
	 * @return array | false [IdAccount => [IsMailingList, Email, FriendlyName, IsDisabled, IdUser, StorageQuota, LastLogin]]
	 */
	public function getUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy = 'email', $bAscOrderType = true, $sSearchDesc = '')
	{
		$aUsers = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getSelectUserListQuery($iDomainId, $iPage, $iUsersPerPage,
				$this->_getDbOrderBy($sOrderBy), $bAscOrderType, $sSearchDesc)))
		{
			$oRow = null;
			$aUsers = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aUsers[$oRow->id_acct] = array(
					(bool) $oRow->mailing_list, $oRow->email, $oRow->friendly_nm, (bool) $oRow->deleted,
					$oRow->id_user, $oRow->quota, $oRow->last_login);
			}
		}
		$this->throwDbExceptionIfExist();
		return $aUsers;
	}

	/**
	 * Obtains list of identifiers of accounts which are specified as default. Domain identifier is used for look up.
	 * 
	 * @param int $iDomainId Domain identifier.
	 * @param int $iPage List page.
	 * @param int $iUsersPerPage Number of identifiers on a single page.
	 * 
	 * @return array | false
	 */
	public function getDefaultAccountIdList($iDomainId, $iPage, $iUsersPerPage)
	{
		$aUserIds = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getDefaultAccountIdListQuery($iDomainId, $iPage, $iUsersPerPage)))
		{
			$oRow = null;
			$aUserIds = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aUserIds[$oRow->id_acct] = $oRow->id_acct;
			}
		}
		$this->throwDbExceptionIfExist();
		return $aUserIds;
	}
	
	/**
	 * Obtains list of accounts which are specified as default.
	 * 
	 * @return array | false
	 */
	public function getDefaultAccountList()
	{
		$aUsers = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getDefaultAccountListQuery()))
		{
			$oRow = null;
			$aUsers = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aUsers[$oRow->id_acct] = $oRow;
			}
		}
		$this->throwDbExceptionIfExist();
		return $aUsers;
	}

	/**
	 * Obtains twilio numbers for default accounts with allowed twilio. Tenant identifier is used for look up.
	 * 
	 * @param $iTenantId Tenant identifier.
	 * 
	 * @return array | false
	 */
	public function getTwilioNumbers($iTenantId)
	{
		$aUsers = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getTwilioNumbersQuery($iTenantId)))
		{
			$oRow = null;
			$aUsers = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aUsers[$oRow->twilio_number] = !!$oRow->twilio_default_number;
			}
		}
		$this->throwDbExceptionIfExist();
		return $aUsers;
	}

	/**
	 * Determines how many users are in particular domain, with optional filtering. Domain identifier is used for look up.
	 * 
	 * @param int $iDomainId Domain identifier.
	 * @param string $sSearchDesc = '' If not empty, only users matching this pattern are counted.
	 * 
	 * @return int | false
	 */
	public function getUsersCountForDomain($iDomainId, $sSearchDesc = '')
	{
		$mResultCount = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getUsersCountForDomainQuery($iDomainId, $sSearchDesc)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$mResultCount = (int) $oRow->users_count;
			}

			$this->oConnection->FreeResult();
		}
		$this->throwDbExceptionIfExist();
		return $mResultCount;
	}

	/**
	 * Determines how many users are in particular tenant. Tenant identifier is used for look up.
	 * 
	 * @param int $iTenantId Tenant identifier.
	 * 
	 * @return int | false
	 */
	public function getUsersCountForTenant($iTenantId)
	{
		$mResultCount = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getUsersCountForTenantQuery($iTenantId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$mResultCount = (int) $oRow->users_count;
			}

			$this->oConnection->FreeResult();
		}
		$this->throwDbExceptionIfExist();
		return $mResultCount;
	}

	/**
	 * Calculates total number of users registered in WebMail Pro.
	 * 
	 * @return int
	 */
	public function getTotalUsersCount()
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->getTotalUsersCountQuery()))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->users_count;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iResult;
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
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createCalUserQuery($oCalUser)))
		{
			$oCalUser->IdCalUser = $this->oConnection->GetLastInsertId('acal_users_data', 'settings_id');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
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
		return (is_int($iUserId) && 0 < $iUserId)
			? $this->_getCalUserBySql($this->oCommandCreator->getCalUserQuery($iUserId)) : null;
	}

	/**
	 * Deletes calendar user settings from the storage. User identifier is used for look up.
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return bool
	 */
	public function deleteCalUser($iUserId)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->deleteCalUserQuery($iUserId));
		$this->throwDbExceptionIfExist();
		return $bResult;
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
		$bResult = $this->oConnection->Execute($this->oCommandCreator->updateCalUserQuery($oCalUser));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * Checks whether specific address is in safelist for particular user.
	 * 
	 * @param string $iUserId User identifier.
	 * @param string $sEmail Email of sender.
	 * 
	 * @return bool
	 */
	public function getSafetySender($iUserId, $sEmail)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getSafetySenderQuery($iUserId, $sEmail)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$bResult = true;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * Updates a list of senders wich are considered safe to show external images coming from.
	 * 
	 * @param string $iUserId User identifier.
	 * @param string $sEmail Email of sender wich is considered safe.
	 * 
	 * @return bool
	 */
	public function setSafetySender($iUserId, $sEmail)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getSafetySenderQuery($iUserId, $sEmail)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if (!$oRow)
			{
				$bResult = $this->oConnection->Execute($this->oCommandCreator->insertSafetySenderQuery($iUserId, $sEmail));
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * Purges all entries in safelist of particular user.
	 * 
	 * @param string $iUserId User identifier.
	 * 
	 * @return bool
	 */
	public function clearSafetySenders($iUserId)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->clearSafetySendersQuery($iUserId));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * Returns the real name of the field to sort.
	 * 
	 * @param string $sOrderBy Name of the field to sort.
	 * 
	 * @return string
	 */
	protected function _getDbOrderBy($sOrderBy)
	{
		$sResult = $sOrderBy;
		switch (strtolower($sOrderBy))
		{
			case 'name':
			case 'friendly name':
				$sResult = 'friendly_nm';
				break;
			default:
			case 'email':
				$sResult = 'email';
				break;
			case 'last login':
				$sResult = 'last_login';
				break;
		}
		return $sResult;
	}
}