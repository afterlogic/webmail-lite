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
	 * @param string $sEmail
	 * @return CAccount
	 */
	public function GetAccountOnLogin($sEmail)
	{
		return $this->getAccountBySql($this->oCommandCreator->GetAccountOnLogin($sEmail));
	}

	/**
	 * @param string $sEmail
	 * @return CAccount
	 */
	public function GetAccountBySocialEmail($sEmail)
	{
		return $this->getAccountBySql($this->oCommandCreator->GetAccountBySocialEmail($sEmail));
	}
	
	/**
	 * @param string $sEmail
	 * @return CAccount
	 */
	public function GetAppointmentAccount($sEmail)
	{
		return $this->getAccountBySql($this->oCommandCreator->GetAppointmentAccount($sEmail));
	}

	/**
	 * @param int $iAccountId
	 * @return CAccount
	 */
	public function GetAccountById($iAccountId)
	{
		return (is_int($iAccountId) && 0 < $iAccountId)
			? $this->getAccountBySql($this->oCommandCreator->GetAccountById($iAccountId)) : null;
	}

	/**
	 * @param int $iUserId
	 * @return CUser | false
	 */
	public function GetUserById($iUserId, $oDomain = null)
	{
		$oUser = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetUserById($iUserId)))
		{
			$oUser = null;

			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				if (null === $oDomain)
				{
					/* @var $oApiDomainsManager CApiDomainsManager */
					$oApiDomainsManager = CApi::Manager('domains');
					$oDomain = $oApiDomainsManager->GetDefaultDomain();
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
	 * @param int $iUserId
	 * @return int
	 */
	public function GetDefaultAccountDomainId($iUserId)
	{
		$iResult = -1;
		if ($this->oConnection->Execute($this->oCommandCreator->GetDefaultAccountDomainId($iUserId)))
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
	 * @param int $iUserId
	 * @return int
	 */
	public function GetDefaultAccountId($iUserId)
	{
		$iResult = -1;
		if ($this->oConnection->Execute($this->oCommandCreator->GetDefaultAccountId($iUserId)))
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
	 * @param string $sEmail
	 * @return int
	 */
	public function GetAccountUsedSpaceInKBytesByEmail($sEmail)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->GetAccountUsedSpaceInKBytesByEmail($sEmail)))
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
	 * @param int $iIdentityId
	 * @return CIdentity | bool
	 */
	public function GetIdentity($iIdentityId)
	{
		$oIdentity = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetIdentity($iIdentityId)))
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
	 * @param CAccount $oAccount
	 * @return array | bool
	 */
	public function GetIdentities($oAccount)
	{
		$aIdentities = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetIdentities($oAccount)))
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
	 * @param CAccount $oAccount
	 * @return array | bool
	 */
	public function GetIdentitiesByUserID($oAccount)
	{
		$aIdentities = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetIdentitiesByUserID($oAccount)))
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
	 * @param string $sSql
	 * @return CAccount
	 */
	protected function getAccountBySql($sSql)
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
					$oDomain = $oApiDomainsManager->GetDomainById($iDomainId);
				}
				else
				{
					$oDomain = $oApiDomainsManager->GetDefaultDomain();
				}

				if ($oDomain)
				{
					$oAccount = new CAccount($oDomain);
					$oAccount->InitByDbRow($oRow);

					$oUser = $this->GetUserById($oAccount->IdUser, $oDomain);
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
	 * @param string $sSql
	 * @return CCalUser
	 */
	protected function getCalUserBySql($sSql)
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
	 * @param CIdentity &$oIdentity
	 * @return bool
	 */
	public function CreateIdentity(CIdentity &$oIdentity)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->CreateIdentity($oIdentity)))
		{
			$oIdentity->IdIdentity = $this->oConnection->GetLastInsertId('awm_identities', 'id_identity');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CAccount &$oAccount
	 * @return bool
	 */
	public function CreateAccount(CAccount &$oAccount)
	{
		$bResult = false;
		$bUserExist = true;
		$iUserId = $oAccount->IdUser;
		
		if (0 === $iUserId && !$oAccount->IsMailingList)
		{
			$bUserExist = false;
			if ($this->oConnection->Execute($this->oCommandCreator->CreateAUser()))
			{
				$iUserId = $this->oConnection->GetLastInsertId('a_users', 'id_user');
			}

			if (0 < $iUserId)
			{
				$oAccount->IdUser = $iUserId;
				$oAccount->User->IdUser = $iUserId;
				$oAccount->User->CreatedTime = time();

				$bUserExist = $this->oConnection->Execute(
					$this->oCommandCreator->CreateUser($oAccount->User));
			}
		}

		if ($bUserExist)
		{
			if ($this->oConnection->Execute($this->oCommandCreator->CreateAccount($oAccount)))
			{
				$oAccount->IdAccount = $this->oConnection->GetLastInsertId('awm_accounts', 'id_acct');
				$bResult = true;
			}
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param array $aAccountsIds
	 * @param bool $bIsEnabled
	 * @return bool
	 */
	public function EnableAccounts($aAccountsIds, $bIsEnabled)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->EnableAccounts($aAccountsIds, $bIsEnabled));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function UpdateAccount(CAccount $oAccount)
	{
		$bResult = (bool) (
			$this->oConnection->Execute($this->oCommandCreator->UpdateAccount($oAccount)) &&
			$this->oConnection->Execute($this->oCommandCreator->UpdateUser($oAccount->User))
		);
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CIdentity $oIdentity
	 * @return bool
	 */
	public function UpdateIdentity(CIdentity $oIdentity)
	{
		$bResult = (bool) $this->oConnection->Execute(
			$this->oCommandCreator->UpdateIdentity($oIdentity));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CIdentity $oIdentity
	 * @return bool
	 */
	public function UpdateIdentitiesDefaults($iIdentityId, $iIdAccount)
	{
		$bResult = (bool) $this->oConnection->Execute(
			$this->oCommandCreator->UpdateIdentitiesDefaults($iIdentityId, $iIdAccount));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @return bool
	 */
	public function UpdateAccountLastLoginAndCount($iUserId)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->UpdateAccountLastLoginAndCount($iUserId));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function AccountExists(CAccount $oAccount)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->AccountExists(
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
	 * @param int $iIdentityId
	 * @return bool
	 */
	public function DeleteIdentity($iIdentityId)
	{
		return $this->oConnection->Execute($this->oCommandCreator->DeleteIdentity($iIdentityId));
	}

	/**
	 * @param int $iAccountId
	 * @return bool
	 */
	public function DeleteAccount($iAccountId)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetAccountInfo($iAccountId)))
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
					$bResult = $this->oConnection->Execute($this->oCommandCreator->DeleteAccount($iAccountId));
					$bResult &= $this->oConnection->Execute($this->oCommandCreator->ClearMailingListMembers($iAccountId));
				}
				else
				{
					$bResult = false;
					if ($bIsDefaultAccount)
					{
						$aAccountsId = $this->GetUserIdList($iUserId);
						if (is_array($aAccountsId) && 0 < count($aAccountsId))
						{
							$bResult = true;
							foreach ($aAccountsId as $iAccountIdItem)
							{
								$bResult &= $this->deleteAccountRequests($iAccountIdItem);
							}

							// Webmail
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteUser($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAUser($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteSenders($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteIdentitiesByUserId($iUserId));

							// Calendar
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteCalendarEvents($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteCalendarCalendars($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteCalendarUserData($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteCalendarPublications($iUserId));
							$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteCalendarSharing($iUserId));

							$this->throwDbExceptionIfExist();
						}
					}
					else
					{
						$bResult = $this->deleteAccountRequests($iAccountId);
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
	 * @param int $iAccountId
	 * @return bool
	 */
	protected function deleteAccountRequests($iAccountId)
	{
		$bResult = true;
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAccount($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAccountMessages($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAccountMessageBodies($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAccountFilters($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAccountReads($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAccountFoldersTree($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteAccountFolders($iAccountId));
		$bResult &= $this->oConnection->Execute($this->oCommandCreator->DeleteIdentitiesByAccountId($iAccountId));
		return (bool) $bResult;
	}

	/**
	 * @param int $iUserId
	 * @return array | false
	 */
	public function GetUserIdList($iUserId)
	{
		$aAccountsIds = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetUserIdList($iUserId)))
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
	 * @param int $iUserId
	 * @return array | false [IdAccount => [IsDefault, Email]]
	 */
	public function GetUserAccountListInformation($iUserId)
	{
		$aResult = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetUserAccountListInformation($iUserId)))
		{
			$oRow = null;
			$aResult = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aResult[$oRow->id_acct] = array(
					(bool) $oRow->def_acct, $oRow->email, trim($oRow->friendly_nm), trim($oRow->signature), (int) $oRow->signature_type, (int) $oRow->signature_opt
				);
			}
		}
		$this->throwDbExceptionIfExist();
		return $aResult;
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return int | false
	 */
	public function GetUserAccountId($iUserId, $sEmail)
	{
		$iResult = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetUserAccountId($iUserId, $sEmail)))
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
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @param string $sOrderBy = 'email'
	 * @param bool $bOrderType = true
	 * @param string $sSearchDesc = ''
	 * @return array | false [IdAccount => [IsMailingList, Email, FriendlyName, IsDisabled, IdUser, StorageQuota, LastLogin]]
	 */
	public function GetUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy = 'email', $bOrderType = true, $sSearchDesc = '')
	{
		$aUsers = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetUserList($iDomainId, $iPage, $iUsersPerPage,
				$this->dbOrderBy($sOrderBy), $bOrderType, $sSearchDesc)))
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
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @return array | false
	 */
	public function GetUserListIdWithOutOrder($iDomainId, $iPage, $iUsersPerPage)
	{
		$aUserIds = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetUserListIdWithOutOrder($iDomainId, $iPage, $iUsersPerPage)))
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
	 * @return array | false
	 */
	public function GetUserFullList()
	{
		$aUsers = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetUserFullList()))
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
	 * @return array|false
	 */
	public function GetUserTwilioNumbers($iTenantId)
	{
		$aUsers = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetUserTwilioNumbers($iTenantId)))
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
	 * @param int $iDomainId
	 * @param string $sSearchDesc = ''
	 * @return int | false
	 */
	public function GetUserCount($iDomainId, $sSearchDesc = '')
	{
		$iResultCount = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetUserCount($iDomainId, $sSearchDesc)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResultCount = (int) $oRow->users_count;
			}

			$this->oConnection->FreeResult();
		}
		$this->throwDbExceptionIfExist();
		return $iResultCount;
	}

	/**
	 * @param int $iTenantId
	 * @return int | false
	 */
	public function GetUserCountByTenantId($iTenantId)
	{
		$iResultCount = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetUserCountByTenantId($iTenantId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResultCount = (int) $oRow->users_count;
			}

			$this->oConnection->FreeResult();
		}
		$this->throwDbExceptionIfExist();
		return $iResultCount;
	}

	/**
	 * @return int
	 */
	public function GetCurrentNumberOfUsers()
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->GetCurrentNumberOfUsers()))
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
	 * @param CCalUser &$oCalUser
	 * @return bool
	 */
	public function CreateCalUser(CCalUser &$oCalUser)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->CreateCalUser($oCalUser)))
		{
			$oCalUser->IdCalUser = $this->oConnection->GetLastInsertId('acal_users_data', 'settings_id');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @return CCalUser
	 */
	public function GetCalUserByUserId($iUserId)
	{
		return (is_int($iUserId) && 0 < $iUserId)
			? $this->getCalUserBySql($this->oCommandCreator->GetCalUserByUserId($iUserId)) : null;
	}

	/**
	 * @param int $iCalUserId
	 * @return bool
	 */
	public function DeleteCalUserByUserId($iUserId)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->DeleteCalUserByUserId($iUserId));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CCalUser $oCalUser
	 * @return bool
	 */
	public function UpdateCalUser(CCalUser $oCalUser)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->UpdateCalUser($oCalUser));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return bool
	 */
	public function GetSafetySender($iUserId, $sEmail)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetSafetySender($iUserId, $sEmail)))
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
	 * @param int $iUserId
	 * @param string $sEmail
	 * @return bool
	 */
	public function SetSafetySender($iUserId, $sEmail)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetSafetySender($iUserId, $sEmail)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if (!$oRow)
			{
				$bResult = $this->oConnection->Execute($this->oCommandCreator->InsertSafetySender($iUserId, $sEmail));
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @return bool
	 */
	public function ClearSafetySenders($iUserId)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->ClearSafetySenders($iUserId));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param string $sOrderBy
	 * @return string
	 */
	protected function dbOrderBy($sOrderBy)
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