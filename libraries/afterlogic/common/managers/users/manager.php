<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiUsersManager class is used for work with essential user's functions.
 * 
 * @api
 * @package Users
 */
class CApiUsersManager extends AApiManagerWithStorage
{
	/**
	 * Creates a new instance of the object.
	 * 
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
	 * Retrieves information about account wich is specified as default. Email address is used for look up.
	 * The method is especially useful in case if your product configuration allows for adding multiple accounts per user.
	 * 
	 * @api
	 * 
	 * @param string $sEmail Email address associated with the account.
	 * 
	 * @return CAccount
	 */
	public function getAccountByEmail($sEmail)
	{
		$oAccount = null;
		try
		{
			CApi::Plugin()->RunHook('api-get-account-on-login-precall', array(&$sEmail, &$oAccount));
			if (null === $oAccount)
			{
				$oAccount = $this->oStorage->getAccountByEmail($sEmail);
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
	 * Retrieves information on WebMail Pro account. Account ID is used for look up.
	 * 
	 * @api
	 * 
	 * @param int $mAccountId Account identifier.
	 * @param bool $bIdIsMd5 Default value is **false**.
	 * 
	 * @return CAccount
	 */
	public function getAccountById($mAccountId, $bIdIsMd5 = false)
	{
		$oAccount = null;
		try
		{
			if (is_numeric($mAccountId))
			{
				$iAccountId = (int) $mAccountId;
				if (CApi::Plugin() !== null)
				{
					CApi::Plugin()->RunHook('api-get-account-by-id-precall', array(&$iAccountId, &$oAccount));
				}
			}
			if (null === $oAccount)
			{
				$oAccount = $this->oStorage->getAccountById($mAccountId, $bIdIsMd5);
			}

			// Default account extension
			if ($oAccount instanceof CAccount)
			{
				if ($oAccount->IsInternal)
				{
					$oAccount->enableExtension(CAccount::DisableAccountDeletion);
					$oAccount->enableExtension(CAccount::ChangePasswordExtension);
				}

				if (EMailProtocol::IMAP4 === $oAccount->IncomingMailProtocol)
				{
					$oAccount->enableExtension(CAccount::SpamFolderExtension);
				}

				if (CApi::GetConf('labs.webmail.disable-folders-manual-sort', false))
				{
					$oAccount->enableExtension(CAccount::DisableFoldersManualSort);
				}

				if (CApi::GetConf('sieve', false))
				{
					$aSieveDomains = CApi::GetConf('sieve.config.domains', array());
					if (!is_array($aSieveDomains))
					{
						$aSieveDomains = array();
					}

					if ($oAccount->IsInternal || (is_array($aSieveDomains) && 0 < count($aSieveDomains)))
					{
						$aSieveDomains = array_map('trim', $aSieveDomains);
						$aSieveDomains = array_map('strtolower', $aSieveDomains);

						if ($oAccount->IsInternal || in_array($oAccount->IncomingMailServer, $aSieveDomains))
						{
							if (CApi::GetConf('sieve.autoresponder', false))
							{
								$oAccount->enableExtension(CAccount::AutoresponderExtension);
							}

							if (CApi::GetConf('sieve.forward', false))
							{
								$oAccount->enableExtension(CAccount::ForwardExtension);
							}

							if (CApi::GetConf('sieve.filters', false))
							{
								$oAccount->enableExtension(CAccount::SieveFiltersExtension);
							}
						}
					}
				}
			}

			CApi::Plugin()->RunHook('api-change-account-by-id', array(&$oAccount));
		}
		catch (CApiBaseException $oException)
		{
			$oAccount = false;
			$this->setLastException($oException);
		}
		return $oAccount;
	}

	/**
	 * Retrieves information on particular WebMail Pro user. 
	 * 
	 * @api
	 * @todo not used
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return CUser|false
	 */
	public function getUserById($iUserId)
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
					$oUser = $this->oStorage->getUserById($iUserId);
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
	 * Returns domain identifier for primary user account. The method is especially useful in case
	 * if your product configuration allows for adding multiple accounts per user. 
	 * 
	 * @api
	 * 
	 * @param int $iUserId WebMail Pro user identifier (not to be confused with account ID).
	 * 
	 * @return int
	 */
	public function getDefaultAccountDomainId($iUserId)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->getDefaultAccountDomainId($iUserId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * Returns identifier of primary user account. 
	 * 
	 * @api
	 * 
	 * @param int $iUserId WebMail Pro user identifier.
	 * 
	 * @return int
	 */
	public function getDefaultAccountId($iUserId)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->getDefaultAccountId($iUserId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}
	
	/**
	 * Returns default account of user.
	 * 
	 * @api
	 * 
	 * @param int $iUserId WebMail Pro user identifier.
	 * 
	 * @return CAccount
	 */
	public function getDefaultAccount($iUserId)
	{
		$iAccountId = $this->getDefaultAccountId($iUserId);
		return $this->getAccountById($iAccountId);
	}	

	/**
	 * Returns account used space in Kb.
	 * 
	 * @api
	 * 
	 * @param string $sEmail Email address associated with the account.
	 * 
	 * @return int
	 */
	public function getAccountUsedSpace($sEmail)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->getAccountUsedSpace($sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * Creates identity in account.
	 * 
	 * @api
	 * 
	 * @param CIdentity &$oIdentity Identity to create.
	 * 
	 * @return bool
	 */
	public function createIdentity(CIdentity &$oIdentity)
	{
		$bResult = false;
		try
		{
			if ($oIdentity->isValid())
			{
				if (!$this->oSettings->GetConf('WebMail/AllowIdentities') ||
					$oIdentity->Virtual || !$this->oStorage->createIdentity($oIdentity))
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
	 * @todo not used
	 * 
	 * @api
	 * @param CAccount $oAccount
	 * @param CTenant $oTenant
	 * @param bool $bCreate Default value is **false**.
	 *
	 * @return bool
	 *
	 * @throws CApiManagerException
	 */
	private function _validateAccountSubscriptionLimits(&$oAccount, $oTenant, $bCreate = false)
	{
		// TODO subscriptions
//		if (CApi::GetConf('capa', false) && $oAccount && $oTenant)
//		{
//			$oSubscriptionsApi = CApi::Manager('subscriptions');
//			/* @var $oSubscriptionsApi CApiSubscriptionsManager */
//
//			$oTenantsApi = CApi::Manager('tenants');
//			/* @var $oTenantsApi CApiTenantsManager */
//
//			if ($oSubscriptionsApi && $oTenantsApi && $oAccount->IsDefaultAccount && !$oAccount->IsDisabled)
//			{
//				if (0 < $oAccount->User->IdSubscription)
//				{
//					$oSub = $oSubscriptionsApi->getSubscriptionById($oAccount->User->IdSubscription);
//					if (/* @var $oSub CSubscription */ $oSub)
//					{
//						$aUsage = $oTenantsApi->getSubscriptionUserUsage($oTenant->IdTenant,
//							$bCreate ? null : $oAccount->IdUser);
//
//						$iLimit = is_array($aUsage) && isset($aUsage[$oAccount->User->IdSubscription])
//							? $aUsage[$oAccount->User->IdSubscription] : 0;
//
//						if ($iLimit + 1 <= $oSub->Limit)
//						{
//							if ($bCreate)
//							{
//								$oAccount->User->Capa = $oSub->Capa;
//							}
//
//							return true;
//						}
//					}
//
//					if ($bCreate)
//					{
//						throw new CApiManagerException(Errs::TenantsManager_AccountCreateUserLimitReached);
//					}
//					else
//					{
//						throw new CApiManagerException(Errs::TenantsManager_AccountUpdateUserLimitReached);
//					}
//				}
//			}
//		}

		return false;
	}

	/**
	 * Creates WebMail account. In most cases, using simpler loginToAccount wrapper is recommended.
	 * 
	 * @api
	 * 
	 * @param CAccount &$oAccount Object instance with prepopulated account properties.
	 * @param bool $bWithMailConnection Default value is **true**. Defines whether account credentials should be verified against mail server.
	 * 
	 * @return bool
	 */
	public function createAccount(CAccount &$oAccount, $bWithMailConnection = true)
	{
		$bResult = false;
		try
		{
			if ($oAccount->isValid())
			{
				if (!$this->accountExists($oAccount))
				{
					/* @var $oApiLicensingManager CApiLicensingManager */
					$oApiLicensingManager = CApi::Manager('licensing');
					if ($oApiLicensingManager)
					{
						$isValidKey = $oApiLicensingManager->IsValidKey() ;
						if (!$isValidKey && in_array($oApiLicensingManager->GetLicenseType(), array(11, 13, 14)))
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

					if (0 < $oAccount->Domain->IdTenant && CApi::GetConf('tenant', false))
					{
						/* @var $oTenantsApi CApiTenantsManager */
						$oTenantsApi = CApi::Manager('tenants');
						if ($oTenantsApi)
						{
							/* @var $oTenant CTenant */
							$oTenant = $oTenantsApi->getTenantById($oAccount->Domain->IdTenant);
							if (!$oTenant)
							{
								throw new CApiManagerException(Errs::TenantsManager_TenantDoesNotExist);
							}
							else
							{
								if (0 < $oTenant->UserCountLimit && $oTenant->UserCountLimit <= $oTenant->getUserCount())
								{
									throw new CApiManagerException(Errs::TenantsManager_AccountCreateUserLimitReached);
								}

								$this->_validateAccountSubscriptionLimits($oAccount, $oTenant, true);
							}

							if (0 < $oTenant->QuotaInMB)
							{
								$iSize = $oTenantsApi->getTenantAllocatedSize($oTenant->IdTenant);
								if (((int) ($oAccount->getRealQuotaSize() / 1024)) + $iSize > $oTenant->QuotaInMB)
								{
									throw new CApiManagerException(Errs::TenantsManager_QuotaLimitExided);
								}
							}
						}
					}

					$bConnectValid = true;
					$aConnectErrors = array(false, false);
					if ($bWithMailConnection && !$oAccount->IsMailingList && !$oAccount->IsInternal && !$oAccount->Domain->IsDefaultTenantDomain)
					{
						$bConnectValid = false;
						$iConnectTimeOut = CApi::GetConf('socket.connect-timeout', 10);
						$iSocketTimeOut = CApi::GetConf('socket.get-timeout', 20);

						CApi::Plugin()->RunHook('webmail-imap-update-socket-timeouts',
							array(&$iConnectTimeOut, &$iSocketTimeOut));

						try
						{
							$oImapClient = \MailSo\Imap\ImapClient::NewInstance();
							$oImapClient->SetTimeOuts($iConnectTimeOut, $iSocketTimeOut);
							$oImapClient->SetLogger(\CApi::MailSoLogger());

							$oImapClient->Connect($oAccount->IncomingMailServer, $oAccount->IncomingMailPort,
								$oAccount->IncomingMailUseSSL
									? \MailSo\Net\Enumerations\ConnectionSecurityType::SSL
									: \MailSo\Net\Enumerations\ConnectionSecurityType::NONE);

							$aConnectErrors[0] = true;

							$sProxyAuthUser = !empty($oAccount->CustomFields['ProxyAuthUser'])
								? $oAccount->CustomFields['ProxyAuthUser'] : '';

							$oImapClient->Login($oAccount->IncomingMailLogin, $oAccount->IncomingMailPassword, $sProxyAuthUser);

							$aConnectErrors[1] = true;
							$bConnectValid = true;

							$oImapClient->LogoutAndDisconnect();
						}
						catch (\Exception $oExceprion) {}
					}

					if ($bConnectValid)
					{
						if (!$this->oStorage->createAccount($oAccount))
						{
							throw new CApiManagerException(Errs::UserManager_AccountCreateFailed);
						}

						if ($oAccount && $oAccount->IsDefaultAccount)
						{
							/* @var $oApiContactsManager CApiContactsManager */
							$oApiContactsManager = CApi::Manager('contactsmain');

							if ($oApiContactsManager && 'db' === CApi::GetManager()->GetStorageByType('contactsmain'))
							{
								$oContact = $oApiContactsManager->createContactObject();
								$oContact->BusinessEmail = $oAccount->Email;
								$oContact->PrimaryEmail = EPrimaryEmailType::Business;
								$oContact->FullName = $oAccount->FriendlyName;
								$oContact->Type = EContactType::GlobalAccounts;

								$oContact->IdTypeLink = $oAccount->IdUser;
								$oContact->IdDomain = 0 < $oAccount->IdDomain ? $oAccount->IdDomain : 0;
								$oContact->IdTenant = $oAccount->Domain ? $oAccount->Domain->IdTenant : 0;

								$oApiContactsManager->createContact($oContact);
							}
						}

						CApi::Plugin()->RunHook('statistics.signup', array(&$oAccount));
					}
					else
					{
						if ($aConnectErrors[0])
						{
							throw new CApiManagerException(Errs::UserManager_AccountAuthenticationFailed);
						}
						else
						{
							throw new CApiManagerException(Errs::UserManager_AccountConnectToMailServerFailed);
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
	 * Enable/disable one or several WebMail Pro accounts. 
	 * 
	 * @api
	 * 
	 * @param array $aAccountsIds List of accounts to be enabled/disabled.
	 * @param bool $bIsEnabled true for enabling accounts, false for disabling them.
	 * 
	 * @return bool
	 */
	public function enableAccounts($aAccountsIds, $bIsEnabled)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->enableAccounts($aAccountsIds, $bIsEnabled);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * Saves changes made to the account.
	 * 
	 * @api
	 * 
	 * @param CAccount &$oAccount Account object containing data to be saved.
	 * @param bool $bSetIdentityDefault Default value is **false**.. If **true** account identity needs treatting as default.
	 * 
	 * @return bool
	 */
	public function updateAccount(CAccount &$oAccount, $bSetIdentityDefault = false)
	{
		$bResult = false;
		try
		{
			if ($oAccount->isValid())
			{
				$oAccount->IncomingMailUseSSL = in_array($oAccount->IncomingMailPort, array(993, 995));
				$oAccount->OutgoingMailUseSSL = in_array($oAccount->OutgoingMailPort, array(465));

				if (0 < $oAccount->Domain->IdTenant && CApi::GetConf('tenant', false) && null !== $oAccount->GetObsoleteValue('StorageQuota'))
				{
					/* @var $oTenantsApi CApiTenantsManager */
					$oTenantsApi = CApi::Manager('tenants');
					if ($oTenantsApi)
					{
						/* @var $oTenant CTenant */
						$oTenant = $oTenantsApi->getTenantById($oAccount->Domain->IdTenant);
						if (!$oTenant)
						{
							throw new CApiManagerException(Errs::TenantsManager_TenantDoesNotExist);
						}
						else
						{
							$this->_validateAccountSubscriptionLimits($oAccount, $oTenant, false);
							
							if (0 < $oTenant->QuotaInMB)
							{
								$iAccountStorageQuota = $oAccount->GetObsoleteValue('StorageQuota');
								$iSize = $oTenantsApi->getTenantAllocatedSize($oTenant->IdTenant);
								$iSize -= (int) ($iAccountStorageQuota / 1024);
								
								if (((int) ($oAccount->getRealQuotaSize() / 1024)) + $iSize > $oTenant->QuotaInMB)
								{
									throw new CApiManagerException(Errs::TenantsManager_QuotaLimitExided);
								}
							}
						}
					}
				}

				$bUseOnlyHookUpdate = false;
				CApi::Plugin()->RunHook('api-update-account', array(&$oAccount, &$bUseOnlyHookUpdate));
				if (!$bUseOnlyHookUpdate)
				{
					if (!$this->oStorage->updateAccount($oAccount))
					{
						$this->moveStorageExceptionToManager();
						throw new CApiManagerException(Errs::UserManager_AccountUpdateFailed);
					}
				}

				if ($oAccount->IsDefaultAccount && 0 < $oAccount->User->IdHelpdeskUser)
				{
					/* @var $oApiHelpdeskManager CApiHelpdeskManager */
					$oApiHelpdeskManager = CApi::Manager('helpdesk');
					if ($oApiHelpdeskManager)
					{
						$oHelpdeskUser = $oApiHelpdeskManager->getUserById($oAccount->IdTenant, $oAccount->User->IdHelpdeskUser);
						if ($oHelpdeskUser)
						{
							$oHelpdeskUser->MailNotifications = $oAccount->User->AllowHelpdeskNotifications;
							$oHelpdeskUser->Signature = $oAccount->User->HelpdeskSignature;
							$oHelpdeskUser->SignatureEnable = $oAccount->User->HelpdeskSignatureEnable;
							$oHelpdeskUser->Name = $oAccount->FriendlyName;
							$oApiHelpdeskManager->updateUser($oHelpdeskUser);
						}
					}
				}

				if ($oAccount->IsDefaultAccount && (
					(null !== $oAccount->GetObsoleteValue('FriendlyName') && $oAccount->GetObsoleteValue('FriendlyName') !== $oAccount->FriendlyName) ||
					(null !== $oAccount->GetObsoleteValue('HideInGAB') && $oAccount->GetObsoleteValue('HideInGAB') !== $oAccount->HideInGAB)
				))
				{
					/* @var $oApiGContactsManager CApiGcontactsManager */
					$oApiGContactsManager = CApi::Manager('gcontacts');
					if ($oApiGContactsManager)
					{
						$oContact = $oApiGContactsManager->getContactByTypeId($oAccount, $oAccount->IdUser, true);
						if ($oContact)
						{
							$oContact->FullName = $oAccount->FriendlyName;
							$oContact->HideInGAB = !!$oAccount->HideInGAB;
							
							$oApiGContactsManager->updateContact($oContact);
						}
					}
				}

				if ($bSetIdentityDefault)
				{
					$this->oStorage->updateIdentitiesDefaults(null, $oAccount->IdAccount); //TODO remove this from there
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
	 * Saves changes made to the identity.
	 * 
	 * @api
	 * 
	 * @param CIdentity &$oIdentity Identity object containing data to be saved.
	 * 
	 * @return bool
	 */
	public function updateIdentity(CIdentity &$oIdentity)
	{
		$bResult = false;
		try
		{
			if ($oIdentity->isValid())
			{
				$bUseOnlyHookUpdate = false;
				CApi::Plugin()->RunHook('api-update-identity', array(&$oIdentity, &$bUseOnlyHookUpdate));

				if ($bUseOnlyHookUpdate)
				{
				}
				else if ($oIdentity->Virtual)
				{
					$oAccount = $this->getAccountById($oIdentity->IdAccount);
					if ($oAccount && $oIdentity->IdUser === $oAccount->IdUser)
					{
						$oAccount->FriendlyName = $oIdentity->FriendlyName;
						$oAccount->Signature = $oIdentity->Signature;
						$oAccount->SignatureType = $oIdentity->SignatureType;
						$oAccount->SignatureOptions = $oIdentity->UseSignature
							? EAccountSignatureOptions::AddToAll : EAccountSignatureOptions::DontAdd;

						$bResult = $this->updateAccount($oAccount);
					}
				}
				else
				{
					if ($this->oStorage->updateIdentity($oIdentity))
					{
						if ($oIdentity->Default)
						{
							$this->oStorage->updateIdentitiesDefaults($oIdentity->IdIdentity, $oIdentity->IdAccount);
						}
					}
					else
					{
						$this->moveStorageExceptionToManager();
						throw new CApiManagerException(Errs::UserManager_IdentityUpdateFailed);
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
	 * For the given user, updates login-related information including time of last login. 
	 * 
	 * @api
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return bool
	 */
	public function updateAccountLastLoginAndCount($iUserId)
	{
		$bResult = false;
		try
		{
			if (!$this->oStorage->updateAccountLastLoginAndCount($iUserId))
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
	 * Checks if particular account exists. 
	 * 
	 * @api
	 * 
	 * @param CAccount $oAccount Object instance with prepopulated account properties. 
	 * 
	 * @return bool
	 */
	public function accountExists(CAccount $oAccount)
	{
		$bResult = false;
		try
		{
			if ($oAccount->IsDefaultAccount)
			{
				$bResult = $this->oStorage->accountExists($oAccount);
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
	 * Deletes identity.
	 * 
	 * @api
	 * 
	 * @param int $iIdentityId Identity identifier.
	 * 
	 * @return bool
	 */
	public function deleteIdentity($iIdentityId)
	{
		$bResult = false;
		try
		{
			if (0 < $iIdentityId)
			{
				$bResult = $this->oStorage->deleteIdentity($iIdentityId);
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
	 * Deletes account from WebMail Pro database. 
	 * 
	 * @api
	 * 
	 * @param CAccount $oAccount Object instance with prepopulated account properties.
	 * 
	 * @return bool
	 */
	public function deleteAccount($oAccount)
	{
		$bResult = false;
		try
		{
			if (!$oAccount)
			{
				$this->setLastException(new CApiManagerException(Errs::Main_UnknownError));
				return false;
			}

			if ($oAccount->IsDefaultAccount)
			{
				if (0 === $oAccount->IdTenant && \strtolower($oAccount->Email) === \strtolower($this->oSettings->GetConf('Helpdesk/AdminEmailAccount')))
				{
					$this->setLastException(new CApiManagerException(Errs::HelpdeskManager_AccountCannotBeDeleted));
					return false;
				}
				else if (0 < $oAccount->IdTenant)
				{
					$oApiTenantsManager = CApi::Manager('tenants');
					/* @var $oApiTenantsManager CApiTenantsManager */
					if ($oApiTenantsManager)
					{
						$oTenant = $oApiTenantsManager->getTenantById($oAccount->IdTenant);
						/* @var $oTenant CTenant */
						if (\strtolower($oAccount->Email) === $oTenant->HelpdeskAdminEmailAccount)
						{
							$this->setLastException(new CApiManagerException(Errs::HelpdeskManager_AccountCannotBeDeleted));
							return false;
						}
					}
				}
			}

			if ($oAccount && $this->oStorage->deleteAccount($oAccount->IdAccount))
			{
				if ($oAccount->IsInternal)
				{
					/* @var $oApiMailSuiteManager CApiMailSuiteManager */
					$oApiMailSuiteManager = CApi::Manager('mailsuite');
					if ($oApiMailSuiteManager)
					{
						$oApiMailSuiteManager->deleteMailAliases($oAccount);
						$oApiMailSuiteManager->deleteMailForwards($oAccount);
						$oApiMailSuiteManager->deleteMailDir($oAccount);
					}
				}

				if ($oAccount->IsDefaultAccount)
				{
					/* @var $oApiContactsManager CApiContactsManager */
					$oApiContactsManager = CApi::Manager('contacts');
					if ($oApiContactsManager)
					{
						$oApiContactsManager->clearAllContactsAndGroups($oAccount);
					}

					/* @var $oApiCalendarManager CApiCalendarManager */
					$oApiCalendarManager = CApi::Manager('calendar');
					if ($oApiCalendarManager)
					{
						$oApiCalendarManager->clearAllCalendars($oAccount);
					}

					/* @var $oApiDavManager CApiDavManager */
					$oApiDavManager = CApi::Manager('dav');
					if ($oApiDavManager)
					{
						$oApiDavManager->deletePrincipal($oAccount);
					}
					
					/* @var $oApiFilestorageManager CApiFilestorageManager */
					$oApiFilestorageManager = CApi::Manager('filestorage');
					if ($oApiFilestorageManager)
					{
						$oApiFilestorageManager->clearAllFiles($oAccount);
					}
					
					/* @var $oApiSocialManager CApiSocialManager */
					$oApiSocialManager = CApi::Manager('social');
					if ($oApiSocialManager)
					{
						$oApiSocialManager->deleteSocialByAccountId($oAccount->IdAccount);
					}

					if (0 < $oAccount->User->IdHelpdeskUser)
					{
						/* @var $oApiHelpdeskManager CApiHelpdeskManager */
						$oApiHelpdeskManager = CApi::Manager('helpdesk');
						if ($oApiHelpdeskManager)
						{
							//$oApiHelpdeskManager->setUserAsBlocked($oAccount->IdTenant, $oAccount->User->IdHelpdeskUser);
							$oApiHelpdeskManager->deleteUser($oAccount->IdTenant, $oAccount->User->IdHelpdeskUser);
						}
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
	 * Deletes account from WebMail Pro database. Account ID is used for look up. 
	 * 
	 * @api
	 * 
	 * @param int $iAccountId Identifier of the account to delete.
	 * 
	 * @return bool
	 */
	public function deleteAccountById($iAccountId)
	{
		$bResult = false;
		$oAccount = $this->getAccountById((int) $iAccountId);

		if ($oAccount)
		{
			$bResult = $this->deleteAccount($oAccount);
		}
		else
		{
			/* @var $oApiMailSuiteManager CApiMailSuiteManager */
			$oApiMailSuiteManager = CApi::Manager('mailsuite');
			if ($oApiMailSuiteManager)
			{
				$oMailingList = $oApiMailSuiteManager->getMailingListById((int) $iAccountId);
				if ($oMailingList)
				{
					$bResult = $oApiMailSuiteManager->deleteMailingList($oMailingList);
				}
			}
		}

		return $bResult;
	}

	/**
	 * Deletes account from WebMail Pro database. Email address is used for look up.
	 * 
	 * @api
	 * 
	 * @param string $sAccountToDelete Email address of the account to delete.
	 * 
	 * @return bool
	 */
	public function deleteAccountByEmail($sAccountToDelete)
	{
		$oAccount = $this->getAccountByEmail($sAccountToDelete);
		return $this->deleteAccount($oAccount);
	}

	/**
	 * Updates a list of senders wich are considered safe to show external images coming from.
	 * 
	 * @api
	 * 
	 * @param string $iUserId User identifier.
	 * @param string $sEmail Email of sender wich is considered safe.
	 * 
	 * @return bool
	 */
	public function setSafetySender($iUserId, $sEmail)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->setSafetySender($iUserId, $sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * Checks whether specific address is in safelist for particular user.
	 * 
	 * @api
	 * 
	 * @param string $iUserId User identifier.
	 * @param string $sEmail Email of sender.
	 * @param bool $bUseCache Default value is **false**. If **true** value of sender safety will be retrieved from cache.
	 * 
	 * @return bool
	 */
	public function getSafetySender($iUserId, $sEmail, $bUseCache = false)
	{
		static $aCache = array();
		if ($bUseCache && isset($aCache[$sEmail.'/'.$iUserId]))
		{
			return $aCache[$sEmail.'/'.$iUserId];
		}

		$bResult = false;
		try
		{
			$bResult = $this->oStorage->getSafetySender($iUserId, $sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		if ($bUseCache)
		{
			$aCache[$sEmail.'/'.$iUserId] = $bResult;
		}

		return $bResult;
	}

	/**
	 * Purges all entries in safelist of particular user.
	 * 
	 * @api
	 * @todo not used
	 * 
	 * @param string $iUserId User identifier.
	 * 
	 * @return bool
	 */
	public function clearSafetySenders($iUserId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->clearSafetySenders($iUserId);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * Retrieves list of accounts for given WebMail Pro user. 
	 * 
	 * @api
	 * @todo not used
	 * 
	 * @param int $iUserId User identifier. 
	 * 
	 * @return array|false array holding a list of account IDs, or false
	 */
	public function getAccountIdList($iUserId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getAccountIdList($iUserId);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * Returns identity.
	 * 
	 * @api
	 * 
	 * @param int $iIdentityId Indentity identifier.
	 * 
	 * @return CIdentity|bool
	 */
	public function getIdentity($iIdentityId)
	{
		$oResult = false;
		try
		{
			$oResult = $this->oStorage->getIdentity($iIdentityId);
		}
		catch (CApiBaseException $oException)
		{
			$oResult = false;
			$this->setLastException($oException);
		}
		return $oResult;
	}

	/**
	 * Returns list of identities belonging to account.
	 * 
	 * @api
	 * @todo not used
	 * 
	 * @param int $IdAccount Identifier of account that contains identities to get.
	 * 
	 * @return array|bool
	 */
	public function getAccountIdentities($IdAccount)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getAccountIdentities($IdAccount);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * Returns list of identities belonging to user.
	 * 
	 * @api
	 * 
	 * @param int $IdUser Identifier of user that contains identities to get.
	 * 
	 * @return array|bool
	 */
	public function getUserIdentities($IdUser)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getUserIdentities($IdUser);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * Retrieves list of information about email accounts for specific user.
	 * 
	 * @api
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return array|false array(int IdAccount => array(bool isDefaultAccount, string email, string friendlyName, string signature, int isSignatureHtml, int isSignatureAdded))
	 */
	public function getUserAccounts($iUserId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getUserAccounts($iUserId);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * Returns account identifier for specific user and account email.
	 * 
	 * @api
	 * 
	 * @param int $iUserId Identifier of user that contains account.
	 * @param string $sEmail Email of account that is looked up.
	 * 
	 * @return int|false
	 */
	public function getUserAccountId($iUserId, $sEmail)
	{
		$iResult = false;
		try
		{
			$iResult = $this->oStorage->getUserAccountId($iUserId, $sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * Obtains list of information about users for specific domain. Domain identifier is used for look up.
	 * The answer contains information only about default account of founded user.
	 * 
	 * @api
	 * 
	 * @param int $iDomainId Domain identifier.
	 * @param int $iPage List page.
	 * @param int $iUsersPerPage Number of users on a single page.
	 * @param string $sOrderBy Default value is **'email'**.. Field by which to sort.
	 * @param bool $bAscOrderType Default value is **true**. If **true** the sort order type is ascending.
	 * @param string $sSearchDesc Default value is empty string. If specified, the search goes on by substring in the name and email of default account.
	 * 
	 * @return array | false [IdAccount => [IsMailingList, Email, FriendlyName, IsDisabled, IdUser, StorageQuota, LastLogin]]
	 */
	public function getUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy = 'email', $bAscOrderType = true, $sSearchDesc = '')
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy, $bAscOrderType, $sSearchDesc);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * Obtains list of identifiers of accounts which are specified as default. Domain identifier is used for look up.
	 * 
	 * @api
	 * 
	 * @param int $iDomainId Domain identifier.
	 * @param int $iPage List page.
	 * @param int $iUsersPerPage Number of identifiers on a single page.
	 * 
	 * @return array|false
	 */
	public function getDefaultAccountIdList($iDomainId, $iPage, $iUsersPerPage)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getDefaultAccountIdList($iDomainId, $iPage, $iUsersPerPage);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}
	
	/**
	 * Obtains list of accounts which are specified as default.
	 * 
	 * @api
	 * 
	 * @return array|false
	 */
	public function getDefaultAccountList()
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getDefaultAccountList();
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * Obtains twilio numbers for default accounts with allowed twilio. Tenant identifier is used for look up.
	 * 
	 * @api
	 * 
	 * @param int $iTenantId Tenant identifier.
	 * 
	 * @return array|false
	 */
	public function getTwilioNumbers($iTenantId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getTwilioNumbers($iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * Determines how many users are in particular domain, with optional filtering. Domain identifier is used for look up.
	 * 
	 * @api
	 * 
	 * @param int $iDomainId Domain identifier.
	 * @param string $sSearchDesc Default value is empty string. If not empty, only users matching this pattern are counted.
	 * 
	 * @return int|false
	 */
	public function getUsersCountForDomain($iDomainId, $sSearchDesc = '')
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getUsersCountForDomain($iDomainId, $sSearchDesc);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * Determines how many users are in particular tenant. Tenant identifier is used for look up.
	 * 
	 * @api
	 * 
	 * @param int $iTenantId Tenant identifier.
	 * 
	 * @return int|false
	 */
	public function getUsersCountForTenant($iTenantId)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getUsersCountForTenant($iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * Calculates total number of users registered in WebMail Pro.
	 * 
	 * @api
	 * 
	 * @return int
	 */
	public function getTotalUsersCount()
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->getTotalUsersCount();
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * Obtains CCalUser object that contains calendar settings for specified user. User identifier is used for look up.
	 * 
	 * @api
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return CCalUser|false
	 */
	public function getCalUser($iUserId)
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
					$oCalUser = $this->oStorage->getCalUser($iUserId);
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
	 * Creates calendar user in storage.
	 * 
	 * @api
	 * 
	 * @param CCalUser &$oCalUser CCalUser object.
	 * 
	 * @return bool
	 */
	public function createCalUser(CCalUser &$oCalUser)
	{
		$bResult = false;
		try
		{
			if ($oCalUser->isValid())
			{
				$oExCalUser = $this->getCalUser($oCalUser->IdUser);
				if ($oExCalUser instanceof CCalUser)
				{
					throw new CApiManagerException(Errs::UserManager_CalUserCreateFailed);
				}

				if (!$this->oStorage->createCalUser($oCalUser))
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
	 * Obtains CCalUser object that contains calendar settings for specified user. User identifier is used for look up.
	 * If CCalUser object is missing in the storage calendar user will be created in it.
	 * 
	 * @api
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return CCalUser|false
	 */
	public function getOrCreateCalUser($iUserId)
	{
		$oCalUser = $this->getCalUser($iUserId);
		if (null === $oCalUser)
		{
			$oCalUser = new CCalUser($iUserId);
			CApi::Plugin()->RunHook('api-create-cal-user', array(&$iUserId, &$oCalUser));

			if ($oCalUser && !$this->createCalUser($oCalUser))
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
	 * Updates calendar user settings.
	 * 
	 * @api
	 * 
	 * @param CCalUser $oCalUser CCalUser object.
	 * 
	 * @return bool
	 */
	public function updateCalUser(CCalUser $oCalUser)
	{
		$bResult = false;
		try
		{
			if ($oCalUser->isValid())
			{
				$bUseOnlyHookUpdate = false;
				CApi::Plugin()->RunHook('api-update-cal-user', array(&$oCalUser, &$bUseOnlyHookUpdate));
				if (!$bUseOnlyHookUpdate)
				{
					if (!$this->oStorage->updateCalUser($oCalUser))
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
	 * Deletes calendar user settings from the storage. User identifier is used for look up.
	 * 
	 * @api
	 * 
	 * @param int $iUserId User identifier.
	 * 
	 * @return bool
	 */
	public function deleteCalUser($iUserId)
	{
		$bResult = false;
		try
		{
			$this->oStorage->deleteCalUser($iUserId);
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
