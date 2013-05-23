<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Users
 */
class CApiUsersManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('users', $oManager, $sForcedStorage);

		$this->inc('classes.enum');
		$this->inc('classes.user');
		$this->inc('classes.account');
		$this->inc('classes.caluser');
		$this->inc('classes.identity');
	}

	/**
	 * @param string $sEmail
	 * @return CAccount
	 */
	public function GetAccountOnLogin($sEmail)
	{
		$oAccount = null;
		try
		{
			CApi::Plugin()->RunHook('api-get-account-on-login-precall', array(&$sEmail, &$oAccount));
			if (null === $oAccount)
			{
				$oAccount = $this->oStorage->GetAccountOnLogin($sEmail);
			}
			CApi::Plugin()->RunHook('api-change-account-on-login', array(&$oAccount));
		}
		catch (CApiBaseException $oException)
		{
			$oAccount = false;
			$this->setLastException($oException);
		}
		return $oAccount;
	}

	/**
	 * @param int $iAccountId
	 * @return CAccount
	 */
	public function GetAccountById($iAccountId)
	{
		$oAccount = null;
		try
		{
			if (is_numeric($iAccountId))
			{
				$iAccountId = (int) $iAccountId;
				CApi::Plugin()->RunHook('api-get-account-by-id-precall', array(&$iAccountId, &$oAccount));
				if (null === $oAccount)
				{
					$oAccount = $this->oStorage->GetAccountById($iAccountId);
				}

				// Defautl account extension
				if ($oAccount instanceof CAccount)
				{
					if ($oAccount->IsInternal)
					{
						$oAccount->EnableExtension(CAccount::DisableAccountDeletion);
						$oAccount->EnableExtension(CAccount::ChangePasswordExtension);
					}

					if (EMailProtocol::IMAP4 === $oAccount->IncomingMailProtocol)
					{
						$oAccount->EnableExtension(CAccount::SpamFolderExtension);
						$oAccount->EnableExtension(CAccount::SpamLearningExtension);
					}
				}

				CApi::Plugin()->RunHook('api-change-account-by-id', array(&$oAccount));
			}
			else
			{
				throw new CApiBaseException(Errs::Validation_InvalidParameters);
			}
		}
		catch (CApiBaseException $oException)
		{
			$oAccount = false;
			$this->setLastException($oException);
		}
		return $oAccount;
	}

	/**
	 * @param int $iUserId
	 * @return CUser | false
	 */
	public function GetUserById($iUserId)
	{
		$oUser = null;
		try
		{
			if (is_numeric($iUserId))
			{
				$iUserId = (int) $iUserId;
				CApi::Plugin()->RunHook('api-get-user-by-id-precall', array(&$iUserId, &$oUser));
				if (null === $oUser)
				{
					$oUser = $this->oStorage->GetUserById($iUserId);
				}
				CApi::Plugin()->RunHook('api-change-user-by-id', array(&$oUser));
			}
			else
			{
				throw new CApiBaseException(Errs::Validation_InvalidParameters);
			}
		}
		catch (CApiBaseException $oException)
		{
			$oUser = false;
			$this->setLastException($oException);
		}
		return $oUser;
	}

	/**
	 * @param int $iUserId
	 * @return int
	 */
	public function GetDefaultAccountDomainId($iUserId)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetDefaultAccountDomainId($iUserId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * @param int $iUserId
	 * @return int
	 */
	public function GetDefaultAccountId($iUserId)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetDefaultAccountId($iUserId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * @param CIdentity &$oIdentity
	 * @return bool
	 */
	public function CreateIdentity(CIdentity &$oIdentity)
	{
		$bResult = false;
		try
		{
			if ($oIdentity->Validate())
			{
				if (!$this->oSettings->GetConf('WebMail/AllowIdentities') ||
					$oIdentity->Virtual || !$this->oStorage->CreateIdentity($oIdentity))
				{
					throw new CApiManagerException(Errs::UserManager_IdentityCreateFailed);
				}

				$bResult = true;
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CAccount &$oAccount
	 * @param bool $bWithMailConnection = true
	 * @return bool
	 */
	public function CreateAccount(CAccount &$oAccount, $bWithMailConnection = true)
	{
		$bResult = false;
		try
		{
			if ($oAccount->Validate())
			{
				if (!$this->AccountExists($oAccount))
				{
					$oAccount->IncomingMailUseSSL = in_array($oAccount->IncomingMailPort, array(993, 995));
					$oAccount->OutgoingMailUseSSL = in_array($oAccount->OutgoingMailPort, array(465));

					/* @var $oApiLicensingManager CApiLicensingManager */
					$oApiLicensingManager = CApi::Manager('licensing');
					if ($oApiLicensingManager)
					{
						$isValidKey = $oApiLicensingManager->IsValidKey() ;
						if (!$isValidKey && in_array($oApiLicensingManager->GetLicenseType(), array(11, 13)))
						{
							throw new CApiManagerException(Errs::UserManager_LicenseKeyIsOutdated);
						}
						else if (!$isValidKey)
						{
							throw new CApiManagerException(Errs::UserManager_LicenseKeyInvalid);
						}

						if ($oAccount->IsDefaultAccount && !$oApiLicensingManager->IsValidLimit(true))
						{
							throw new CApiManagerException(Errs::UserManager_AccountCreateUserLimitReached);
						}
					}

					if (0 < $oAccount->Domain->IdRealm && CApi::GetConf('realm', false))
					{
						/* @var $oRealmsApi CApiRealmsManager */
						$oRealmsApi = CApi::Manager('realms');
						if ($oRealmsApi)
						{
							/* @var $oRealm CRealm */
							$oRealm = $oRealmsApi->GetRealmById($oAccount->Domain->IdRealm);
							if (!$oRealm)
							{
								throw new CApiManagerException(Errs::RealmsManager_RealmDoesNotExist);
							}
							else
							{
								if (0 < $oRealm->UserCountLimit &&
									$oRealm->UserCountLimit <= $oRealm->GetUserCount())
								{
									throw new CApiManagerException(Errs::RealmsManager_AccountCreateUserLimitReached);
								}
							}

							if (0 < $oRealm->QuotaInMB)
							{
								$iSize = $oRealmsApi->GetRealmAllocatedSize($oRealm->IdRealm);
								if (((int) ($oAccount->RealQuotaSize()/ 1024)) + $iSize > $oRealm->QuotaInMB)
								{
									throw new CApiManagerException(Errs::RealmsManager_QuotaLimitExided);
								}
							}
						}
					}

					/* @var $oApiWebmailManager CApiWebmailManager */
					$oApiWebmailManager = CApi::Manager('webmail');

					$bConnectValid = true;
					$aConnectErrors = array(false, false);
					if ($bWithMailConnection && !$oAccount->IsMailingList && !$oAccount->IsInternal)
					{
						$sNamespace = '';
						$bConnectValid = $oApiWebmailManager->TestConnectionWithMailServer($aConnectErrors,
							$oAccount->IncomingMailProtocol, $oAccount->IncomingMailLogin, $oAccount->IncomingMailPassword,
							$oAccount->IncomingMailServer, $oAccount->IncomingMailPort, $oAccount->IncomingMailUseSSL, $sNamespace);

						$oAccount->Namespace = $sNamespace;
					}

					if ($oAccount->IsInternal)
					{
						$oAccount->Namespace = 'INBOX.';
						$oAccount->Delimiter = '.';
					}

					if ($bConnectValid)
					{
						if (!$this->oStorage->CreateAccount($oAccount))
						{
							throw new CApiManagerException(Errs::UserManager_AccountCreateFailed);
						}

						$oAccount->UpdateInternalStorageQuota(true);

						CApi::Plugin()->RunHook('statistics.signup', array(&$oAccount));
					}
					else
					{
						if ($aConnectErrors[0])
						{
							throw new CApiManagerException(Errs::UserManager_AccountConnectToMailServerFailed);
						}
						else
						{
							throw new CApiManagerException(Errs::UserManager_AccountAuthenticationFailed);
						}
					}

				}
				else
				{
					throw new CApiManagerException(Errs::UserManager_AccountAlreadyExists);
				}
			}

			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param array $aAccountsIds
	 * @param bool $bIsEnabled
	 * @return bool
	 */
	public function EnableAccounts($aAccountsIds, $bIsEnabled)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->EnableAccounts($aAccountsIds, $bIsEnabled);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CAccount &$oAccount
	 * @return bool
	 */
	public function UpdateAccount(CAccount &$oAccount)
	{
		$bResult = false;
		try
		{
			if ($oAccount->Validate())
			{
				$oAccount->IncomingMailUseSSL = in_array($oAccount->IncomingMailPort, array(993, 995));
				$oAccount->OutgoingMailUseSSL = in_array($oAccount->OutgoingMailPort, array(465));

				if (0 < $oAccount->Domain->IdRealm && CApi::GetConf('realm', false) && null !== $oAccount->GetObsoleteValue('StorageQuota'))
				{
					/* @var $oRealmsApi CApiRealmsManager */
					$oRealmsApi = CApi::Manager('realms');
					if ($oRealmsApi)
					{
						/* @var $oRealm CRealm */
						$oRealm = $oRealmsApi->GetRealmById($oAccount->Domain->IdRealm);
						if (!$oRealm)
						{
							throw new CApiManagerException(Errs::RealmsManager_RealmDoesNotExist);
						}
						else
						{
							if (0 < $oRealm->QuotaInMB)
							{
								$iSize = $oRealmsApi->GetRealmAllocatedSize($oRealm->IdRealm);
								if (((int) ($oAccount->RealQuotaSize()/ 1024)) + $iSize > $oRealm->QuotaInMB)
								{
									throw new CApiManagerException(Errs::RealmsManager_QuotaLimitExided);
								}
							}
						}
					}
				}

				$bUseOnlyHookUpdate = false;
				CApi::Plugin()->RunHook('api-update-account', array(&$oAccount, &$bUseOnlyHookUpdate));
				if ($bUseOnlyHookUpdate || $this->oStorage->UpdateAccount($oAccount))
				{
					if (!$bUseOnlyHookUpdate)
					{
						$oAccount->UpdateInternalStorageQuota(false);
					}
				}
				else
				{
					$this->moveStorageExceptionToManager();
					throw new CApiManagerException(Errs::UserManager_AccountUpdateFailed);
				}
			}

			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CIdentity &$oIdentity
	 * @return bool
	 */
	public function UpdateIdentity(CIdentity &$oIdentity)
	{
		$bResult = false;
		try
		{
			if ($oIdentity->Validate())
			{
				$bUseOnlyHookUpdate = false;
				CApi::Plugin()->RunHook('api-update-identity', array(&$oIdentity, &$bUseOnlyHookUpdate));

				if ($bUseOnlyHookUpdate)
				{
				}
				else if ($oIdentity->Virtual)
				{
					$oAccount = $this->GetAccountById($oIdentity->IdAccount);
					if ($oAccount && $oIdentity->IdUser === $oAccount->IdUser)
					{
						$oAccount->FriendlyName = $oIdentity->FriendlyName;
						$oAccount->Signature = $oIdentity->Signature;
						$oAccount->SignatureType = $oIdentity->SignatureType;
						$oAccount->SignatureOptions = $oIdentity->UseSignature
							? EAccountSignatureOptions::AddToAll : EAccountSignatureOptions::DontAdd;

						$bResult = $this->UpdateAccount($oAccount);
					}
				}
				else if (!$this->oStorage->UpdateIdentity($oIdentity))
				{
					$this->moveStorageExceptionToManager();
					throw new CApiManagerException(Errs::UserManager_IdentityUpdateFailed);
				}
			}

			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @return bool
	 */
	public function UpdateAccountLastLoginAndCount($iUserId)
	{
		$bResult = false;
		try
		{
			if (!$this->oStorage->UpdateAccountLastLoginAndCount($iUserId))
			{
				$this->moveStorageExceptionToManager();
				throw new CApiManagerException(Errs::UserManager_AccountUpdateFailed);
			}

			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function AccountExists(CAccount $oAccount)
	{
		$bResult = false;
		try
		{
			if ($oAccount->IsDefaultAccount)
			{
				$bResult = $this->oStorage->AccountExists($oAccount);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param int $iIdentityId
	 * @return bool
	 */
	public function DeleteIdentity($iIdentityId)
	{
		$bResult = false;
		try
		{
			if (0 < $iIdentityId)
			{
				$bResult = $this->oStorage->DeleteIdentity($iIdentityId);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function DeleteAccount($oAccount)
	{
		$bResult = false;
		try
		{
			if ($oAccount && $this->oStorage->DeleteAccount($oAccount->IdAccount))
			{
				if ($oAccount->IsInternal)
				{
					/* @var $oApiMailSuiteManager CApiMailSuiteManager */
					$oApiMailSuiteManager = CApi::Manager('mailsuite');
					if ($oApiMailSuiteManager)
					{
						$oApiMailSuiteManager->DeleteMailAliases($oAccount);
						$oApiMailSuiteManager->DeleteMailForwards($oAccount);
						$oApiMailSuiteManager->DeleteMailDir($oAccount);
					}
				}

				if ($oAccount->IsDefaultAccount)
				{
					/* @var $oApiContactsManager CApiContactsManager */
					$oApiContactsManager = CApi::Manager('contacts');
					if ($oApiContactsManager)
					{
						$oApiContactsManager->ClearAllContactsAndGroups($oAccount);
					}

					/* @var $oApiCalendarManager CApiCalendarManager */
					$oApiCalendarManager = CApi::Manager('calendar');
					if ($oApiCalendarManager)
					{
						$oApiCalendarManager->ClearAllCalendars($oAccount);
					}

					/* @var $oApiDavManager CApiDavManager */
					$oApiDavManager = CApi::Manager('dav');
					if ($oApiDavManager)
					{
						$oApiDavManager->DeletePrincipal($oAccount);
					}
				}

				CApi::Log('FS: Delete "/mail/" and "/temp/" folders');

				// TODO move in storage
				$sMailRoot = CApi::DataPath().'/mail/';
				$sTmpRoot = CApi::DataPath().'/temp/';
				$sPath = strtolower($oAccount->Email.'.'.$oAccount->IdAccount);
				$sPath = $sPath{0}.'/'.$sPath;

				api_Utils::RecRmdir($sMailRoot.$sPath);
				api_Utils::RecRmdir($sTmpRoot.$sPath);
				$bResult = true;
			}
			else if (null === $oAccount)
			{
				$this->setLastException(new CApiManagerException(Errs::UserManager_AccountDoesNotExist));
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param int $iAccountId
	 * @return bool
	 */
	public function DeleteAccountById($iAccountId)
	{
		$bResult = false;
		$oAccount = $this->GetAccountById((int) $iAccountId);

		if ($oAccount)
		{
			$bResult = $this->DeleteAccount($oAccount);
		}
		else
		{
			/* @var $oApiMailSuiteManager CApiMailSuiteManager */
			$oApiMailSuiteManager = CApi::Manager('mailsuite');
			if ($oApiMailSuiteManager)
			{
				$oMailingList = $oApiMailSuiteManager->GetMailingListById((int) $iAccountId);
				if ($oMailingList)
				{
					$bResult = $oApiMailSuiteManager->DeleteMailingList($oMailingList);
				}
			}
		}

		return $bResult;
	}

	/**
	 * @param string $sAccountToDelete
	 * @return bool
	 */
	public function DeleteAccountByEmail($sAccountToDelete)
	{
		$oAccount = $this->GetAccountOnLogin($sAccountToDelete);
		return $this->DeleteAccount($oAccount);
	}

	/**
	 * @param string $iUserId
	 * @param string $sEmail
	 * @return bool
	 */
	public function SetSafetySender($iUserId, $sEmail)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->SetSafetySender($iUserId, $sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * @param string $iUserId
	 * @param string $sEmail
	 * @return bool
	 */
	public function GetSafetySender($iUserId, $sEmail, $bUseChache = false)
	{
		static $aCache = array();
		if ($bUseChache && isset($aCache[$sEmail.'/'.$iUserId]))
		{
			return $aCache[$sEmail.'/'.$iUserId];
		}

		$bResult = false;
		try
		{
			$bResult = $this->oStorage->GetSafetySender($iUserId, $sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		if ($bUseChache)
		{
			$aCache[$sEmail.'/'.$iUserId] = $bResult;
		}

		return $bResult;
	}

	/**
	 * @param string $iUserId
	 * @return bool
	 */
	public function ClearSafetySenders($iUserId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->ClearSafetySenders($iUserId, $sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * @param int $iUserId
	 * @return array | false
	 */
	public function GetUserIdList($iUserId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetUserIdList($iUserId);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iIdentityId
	 * @return CIdentity | bool
	 */
	public function GetIdentity($iIdentityId)
	{
		$oResult = false;
		try
		{
			$oResult = $this->oStorage->GetIdentity($iIdentityId);
		}
		catch (CApiBaseException $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param int $iIdentityType = EIdentityType::Normal
	 * @return array | bool
	 */
	public function GetIdentities($oAccount, $iIdentityType = EIdentityType::Normal)
	{
		$aResult = false;
		try
		{
			$aResult = array();
			if (EIdentityType::Virtual === $iIdentityType)
			{
				$oIdentity = new CIdentity();
				$oIdentity->IdIdentity = -1;
				$oIdentity->IdUser = $oAccount->IdUser;
				$oIdentity->IdAccount = $oAccount->IdAccount;
				$oIdentity->Virtual = true;
				$oIdentity->Email = $oAccount->Email;
				$oIdentity->FriendlyName = $oAccount->FriendlyName;
				$oIdentity->Signature = $oAccount->Signature;
				$oIdentity->SignatureType = $oAccount->SignatureType;
				$oIdentity->UseSignature = EAccountSignatureOptions::DontAdd !== $oAccount->SignatureOptions;

				array_unshift($aResult, $oIdentity);
			}
			else
			{
				$aResult = $this->oStorage->GetIdentities($oAccount);
			}
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iUserId
	 *
	 * @return array | false [IdAccount => [IsDefault, Email]]
	 */
	public function GetUserAccountListInformation($iUserId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetUserAccountListInformation($iUserId);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @param string $sOrderBy = 'email'
	 * @param bool $bOrderType = true
	 * @param string $sSearchDesc = ''
	 * @return array | false [IdAccount => [IsMailingList, Email, FriendlyName, IsDisabled, IdUser, StorageQuota]]
	 */
	public function GetUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy = 'email', $bOrderType = true, $sSearchDesc = '')
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy, $bOrderType, $sSearchDesc);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iDomainId
	 * @param int $iPage
	 * @param int $iUsersPerPage
	 * @return array | false
	 */
	public function GetUserListIdWithOutOrder($iDomainId, $iPage, $iUsersPerPage)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetUserListIdWithOutOrder($iDomainId, $iPage, $iUsersPerPage);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iDomainId
	 * @param string $sSearchDesc = ''
	 * @return int | false
	 */
	public function GetUserCount($iDomainId, $sSearchDesc = '')
	{
		$iResult = false;
		try
		{
			$iResult = $this->oStorage->GetUserCount($iDomainId, $sSearchDesc);
		}
		catch (CApiBaseException $oException)
		{
			$iResult = false;
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * @param int $iRealmId
	 * @return int | false
	 */
	public function GetUserCountByRealmId($iRealmId)
	{
		$iResult = false;
		try
		{
			$iResult = $this->oStorage->GetUserCountByRealmId($iRealmId);
		}
		catch (CApiBaseException $oException)
		{
			$iResult = false;
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * @param string $sEmail
	 * @return CAccount
	 */
	public function GetAppointmentAccount($sEmail)
	{
		$oAccount = null;
		try
		{
			CApi::Plugin()->RunHook('api-get-appointment-account-precall', array(&$sEmail, &$oAccount));
			if (null === $oAccount)
			{
				$oAccount = $this->oStorage->GetAppointmentAccount($sEmail);
			}
			CApi::Plugin()->RunHook('api-change-appointment-account', array(&$oAccount));
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $oAccount;
	}

	/**
	 * @return int
	 */
	public function GetCurrentNumberOfUsers()
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetCurrentNumberOfUsers();
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * @param int $iUserId
	 * @return CCalUser | false
	 */
	public function GetCalUserByUserId($iUserId)
	{
		$oCalUser = null;
		try
		{
			if (is_numeric($iUserId))
			{
				$iUserId = (int) $iUserId;
				CApi::Plugin()->RunHook('api-get-cal-user-by-id-precall', array(&$iUserId, &$oCalUser));
				if (null === $oCalUser)
				{
					$oCalUser = $this->oStorage->GetCalUserByUserId($iUserId);
				}

				CApi::Plugin()->RunHook('api-change-cal-user-by-id', array(&$oCalUser));
			}
			else
			{
				throw new CApiBaseException(Errs::Validation_InvalidParameters);
			}
		}
		catch (CApiBaseException $oException)
		{
			$oCalUser = false;
			$this->setLastException($oException);
		}
		return $oCalUser;
	}

	/**
	 * @param CCalUser &$oCalUser
	 * @return bool
	 */
	public function CreateCalUser(CCalUser &$oCalUser)
	{
		$bResult = false;
		try
		{
			if ($oCalUser->Validate())
			{
				$oExCalUser = $this->GetCalUserByUserId($oCalUser->IdUser);
				if ($oExCalUser instanceof CCalUser)
				{
					throw new CApiManagerException(Errs::UserManager_CalUserCreateFailed);
				}

				if (!$this->oStorage->CreateCalUser($oCalUser))
				{
					throw new CApiManagerException(Errs::UserManager_CalUserCreateFailed);
				}
			}

			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @return CCalUser | false
	 */
	public function GetOrCreateCalUserByUserId($iUserId)
	{
		$oCalUser = $this->GetCalUserByUserId($iUserId);
		if (null === $oCalUser)
		{
			$oCalUser = new CCalUser($iUserId);
			CApi::Plugin()->RunHook('api-create-cal-user', array(&$iUserId, &$oCalUser));

			if ($oCalUser && !$this->CreateCalUser($oCalUser))
			{
				$oCalUser = false;
			}

			if ($oCalUser)
			{
				CApi::Plugin()->RunHook('api-create-cal-user-success', array(&$iUserId, &$oCalUser));
			}
		}

		return $oCalUser;
	}

	/**
	 * @param CCalUser $oCalUser
	 * @return bool
	 */
	public function UpdateCalUser(CCalUser $oCalUser)
	{
		$bResult = false;
		try
		{
			if ($oCalUser->Validate())
			{
				$bUseOnlyHookUpdate = false;
				CApi::Plugin()->RunHook('api-update-cal-user', array(&$oCalUser, &$bUseOnlyHookUpdate));
				if (!$bUseOnlyHookUpdate)
				{
					if (!$this->oStorage->UpdateCalUser($oCalUser))
					{
						$this->moveStorageExceptionToManager();
						throw new CApiManagerException(Errs::UserManager_CalUserUpdateFailed);
					}
				}
			}

			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param int $iUserId
	 * @return bool
	 */
	public function DeleteCalUserByUserId($iUserId)
	{
		$bResult = false;
		try
		{
			$this->oStorage->DeleteCalUserByUserId($iUserId);
			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}
}
