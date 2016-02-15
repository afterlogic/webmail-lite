<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace ProjectCore;

/**
 * @category ProjectCore
 */
class Actions extends ActionsBase
{
	/**
	 * @var \CApiUsersManager
	 */
	protected $oApiUsers;

	/**
	 * @var \CApiTenantsManager
	 */
	protected $oApiTenants;
	
	/**
	 * @var \CApiWebmailManager
	 */
	protected $oApiWebMail;

	/**
	 * @var \CApiIntegratorManager
	 */
	protected $oApiIntegrator;

	/**
	 * @var \CApiMailManager
	 */
	protected $oApiMail;

	/**
	 * @var \CApiFilecacheManager
	 */
	protected $oApiFileCache;

	/**
	 * @var \CApiSieveManager
	 */
	protected $oApiSieve;

	/**
	 * @var \CApiFilestorageManager
	 */
	protected $oApiFilestorage;

	/**
	 * @var \CApiFetchersManager
	 */
	protected $oApiFetchers;

	/**
	 * @var \CApiCalendarManager
	 */
	protected $oApiCalendar;

	/**
	 * @var \CApiCapabilityManager
	 */
	protected $oApiCapability;

	/**
	 * @var \CApiHelpdeskManager
	 */
	protected $oApiHelpdesk;

	protected $oApiTwilio;

	/**
	 * @return void
	 */
	protected function __construct()
	{
		$this->oHttp = null;

		$this->oApiUsers = \CApi::Manager('users');
		$this->oApiTenants = \CApi::Manager('tenants');
		$this->oApiWebMail = \CApi::Manager('webmail');
		$this->oApiIntegrator = \CApi::Manager('integrator');
		$this->oApiMail = \CApi::Manager('mail');
		$this->oApiFileCache = \CApi::Manager('filecache');
		$this->oApiSieve = \CApi::Manager('sieve');
		$this->oApiFilestorage = \CApi::Manager('filestorage');
		$this->oApiCalendar = \CApi::Manager('calendar');
		$this->oApiCapability = \CApi::Manager('capability');

		$this->oApiFetchers = null;
		$this->oApiHelpdesk = null;

		$this->oApiTwilio = \api_Twilio::NewInstance();
	}

	/**
	 * @return \ProjectCore\Actions
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @return \CApiFetchersManager
	 */
	public function ApiFetchers()
	{
		if (null === $this->oApiFetchers)
		{
			$this->oApiFetchers = \CApi::Manager('fetchers');
		}
		
		return $this->oApiFetchers;
	}

	/**
	 * @return \CApiFilecacheManager
	 */
	public function ApiFileCache()
	{
		return $this->oApiFileCache;
	}

	/**
	 * @return \CApiHelpdeskManager
	 */
	public function ApiHelpdesk()
	{
		if (null === $this->oApiHelpdesk)
		{
			$this->oApiHelpdesk = \CApi::Manager('helpdesk');
		}

		return $this->oApiHelpdesk;
	}

	/**
	 * @return \CApiSieveManager
	 */
	public function ApiSieve()
	{
		return $this->oApiSieve;
	}

	/**
	 * @param string $sToken
	 *
	 * @return bool
	 */
	public function validateCsrfToken($sToken)
	{
		return $this->oApiIntegrator->validateCsrfToken($sToken);
	}

	/**
	 * @param int $iAccountId
	 * @param bool $bVerifyLogginedUserId = true
	 * @param string $sAuthToken = ''
	 * @return CAccount | null
	 */
	public function getAccount($iAccountId, $bVerifyLogginedUserId = true, $sAuthToken = '')
	{
		$oResult = null;
		$iUserId = $bVerifyLogginedUserId ? $this->oApiIntegrator->getLogginedUserId($sAuthToken) : 1;
		if (0 < $iUserId)
		{
			$oAccount = $this->oApiUsers->getAccountById($iAccountId);
			if ($oAccount instanceof \CAccount && 
				($bVerifyLogginedUserId && $oAccount->IdUser === $iUserId || !$bVerifyLogginedUserId) && !$oAccount->IsDisabled)
			{
				$oResult = $oAccount;
			}
		}

		return $oResult;
	}

	/**
	 * @param string $sAuthToken = ''
	 * @return \CAccount | null
	 */
	public function GetDefaultAccount($sAuthToken = '')
	{
		$oResult = null;
		$iUserId = $this->oApiIntegrator->getLogginedUserId($sAuthToken);
		if (0 < $iUserId)
		{
			$iAccountId = $this->oApiUsers->getDefaultAccountId($iUserId);
			if (0 < $iAccountId)
			{
				$oAccount = $this->oApiUsers->getAccountById($iAccountId);
				if ($oAccount instanceof \CAccount && !$oAccount->IsDisabled)
				{
					$oResult = $oAccount;
				}
			}
		}

		return $oResult;
	}

	/**
	 * @return \CAccount|null
	 */
	public function GetCurrentAccount($bThrowAuthExceptionOnFalse = true)
	{
		return $this->getAccountFromParam($bThrowAuthExceptionOnFalse);
	}

	/**
	 * @param \CAccount $oAccount
	 * 
	 * @return \CHelpdeskUser|null
	 */
	public function GetHelpdeskAccountFromMainAccount(&$oAccount)
	{
		$oResult = null;
		$oApiHelpdesk = $this->ApiHelpdesk();
		if ($oAccount && $oAccount->IsDefaultAccount && $oApiHelpdesk && $this->oApiCapability->isHelpdeskSupported($oAccount))
		{
			if (0 < $oAccount->User->IdHelpdeskUser)
			{
				$oHelpdeskUser = $oApiHelpdesk->getUserById($oAccount->IdTenant, $oAccount->User->IdHelpdeskUser);
				$oResult = $oHelpdeskUser instanceof \CHelpdeskUser ? $oHelpdeskUser : null;
			}

			if (!($oResult instanceof \CHelpdeskUser))
			{
				$oHelpdeskUser = $oApiHelpdesk->getUserByEmail($oAccount->IdTenant, $oAccount->Email);
				$oResult = $oHelpdeskUser instanceof \CHelpdeskUser ? $oHelpdeskUser : null;
				
				if ($oResult instanceof \CHelpdeskUser)
				{
					$oAccount->User->IdHelpdeskUser = $oHelpdeskUser->IdHelpdeskUser;
					$this->oApiUsers->updateAccount($oAccount);
				}
			}

			if (!($oResult instanceof \CHelpdeskUser))
			{
				$oHelpdeskUser = new \CHelpdeskUser();
				$oHelpdeskUser->Email = $oAccount->Email;
				$oHelpdeskUser->Name = $oAccount->FriendlyName;
				$oHelpdeskUser->IdSystemUser = $oAccount->IdUser;
				$oHelpdeskUser->IdTenant = $oAccount->IdTenant;
				$oHelpdeskUser->Activated = true;
				$oHelpdeskUser->IsAgent = true;
				$oHelpdeskUser->Language = $oAccount->User->DefaultLanguage;
				$oHelpdeskUser->DateFormat = $oAccount->User->DefaultDateFormat;
				$oHelpdeskUser->TimeFormat = $oAccount->User->DefaultTimeFormat;

				$oHelpdeskUser->setPassword($oAccount->IncomingMailPassword);

				if ($oApiHelpdesk->createUser($oHelpdeskUser))
				{
					$oAccount->User->IdHelpdeskUser = $oHelpdeskUser->IdHelpdeskUser;
					$this->oApiUsers->updateAccount($oAccount);

					$oResult = $oHelpdeskUser;
				}
			}
		}

		return $oResult;
	}

	/**
	 * @param bool $bThrowAuthExceptionOnFalse Default value is **true**.
	 * @param bool $bVerifyLogginedUserId Default value is **true**.
	 *
	 * @return \CAccount|null
	 */
	protected function getAccountFromParam($bThrowAuthExceptionOnFalse = true, $bVerifyLogginedUserId = true)
	{
		$oResult = null;
		$sAuthToken = (string) $this->getParamValue('AuthToken', '');
		$sAccountID = (string) $this->getParamValue('AccountID', '');
		if (0 === strlen($sAccountID) || !is_numeric($sAccountID))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oResult = $this->getAccount((int) $sAccountID, $bVerifyLogginedUserId, $sAuthToken);

		if ($bThrowAuthExceptionOnFalse && !($oResult instanceof \CAccount))
		{
			$oExc = $this->oApiUsers->GetLastException();
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AuthError,
				$oExc ? $oExc : null, $oExc ? $oExc->getMessage() : '');
		}

		return $oResult;
	}
	
	/**
	 * @param bool $bThrowAuthExceptionOnFalse Default value is **true**.
	 *
	 * @return \CAccount|null
	 */
	protected function getDefaultAccountFromParam($bThrowAuthExceptionOnFalse = true)
	{
		$sAuthToken = (string) $this->getParamValue('AuthToken', '');
		$oResult = $this->GetDefaultAccount($sAuthToken);
		if ($bThrowAuthExceptionOnFalse && !($oResult instanceof \CAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AuthError);
		}

		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param bool $bThrowAuthExceptionOnFalse Default value is **true**.
	 *
	 * @return \CHelpdeskUser|null
	 */
	protected function getHelpdeskAccountFromParam($oAccount, $bThrowAuthExceptionOnFalse = true)
	{
		$oResult = null;
		$oAccount = null;

		if ('0' === (string) $this->getParamValue('IsExt', '1'))
		{
			$oAccount = $this->getDefaultAccountFromParam($bThrowAuthExceptionOnFalse);
			if ($oAccount && $this->oApiCapability->isHelpdeskSupported($oAccount))
			{
				$oResult = $this->GetHelpdeskAccountFromMainAccount($oAccount);
			}
		}
		else
		{
			$mTenantID = $this->oApiIntegrator->getTenantIdByHash($this->getParamValue('TenantHash', ''));
			if (is_int($mTenantID))
			{
				$oResult = \api_Utils::GetHelpdeskAccount($mTenantID);
			}
		}

		if (!$oResult && $bThrowAuthExceptionOnFalse)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::UnknownError);
		}

		return $oResult;
	}

	/**
	 * @return \CHelpdeskUser|null
	 */
	protected function getExtHelpdeskAccountFromParam($bThrowAuthExceptionOnFalse = true)
	{
		$oResult = $this->GetExtHelpdeskAccount();
		if (!$oResult)
		{
			$oResult = null;
			if ($bThrowAuthExceptionOnFalse)
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AuthError);
			}
		}

		return $oResult;
	}

	/**
	 * @return array
	 */
	public function AjaxSystemNoop()
	{
		return $this->TrueResponse(null, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxSystemPing()
	{
		return $this->DefaultResponse(null, __FUNCTION__, 'Pong');
	}

	/**
	 * @return array
	 */
	public function AjaxFilesCheckUrl()
	{
		$oAccount = $this->GetDefaultAccount();
		$mResult = false;

		if ($oAccount)
		{
			$sUrl = trim($this->getParamValue('Url', ''));

			if (!empty($sUrl))
			{
				$iLinkType = \api_Utils::GetLinkType($sUrl);
				if ($iLinkType === \EFileStorageLinkType::GoogleDrive)
				{
					if ($this->oApiTenants)
					{
						$oTenant = (0 < $oAccount->IdTenant) ? $this->oApiTenants->getTenantById($oAccount->IdTenant) :
							$this->oApiTenants->getDefaultGlobalTenant();
					}
					$oSocial = $oTenant->getSocialByName('google');
					if ($oSocial)
					{
						$oInfo = \api_Utils::GetGoogleDriveFileInfo($sUrl, $oSocial->SocialApiKey);
						if ($oInfo)
						{
							$mResult['Size'] = 0;
							if (isset($oInfo->fileSize))
							{
								$mResult['Size'] = $oInfo->fileSize;
							}
							else
							{
								$aRemoteFileInfo = \api_Utils::GetRemoteFileInfo($sUrl);
								$mResult['Size'] = $aRemoteFileInfo['size'];
							}
							$mResult['Name'] = isset($oInfo->title) ? $oInfo->title : '';
							$mResult['Thumb'] = isset($oInfo->thumbnailLink) ? $oInfo->thumbnailLink : null;
						}
					}
				}
				else
				{
					//$sUrl = \api_Utils::GetRemoteFileRealUrl($sUrl);
					$oInfo = \api_Utils::GetOembedFileInfo($sUrl);
					if ($oInfo)
					{
						$mResult['Size'] = isset($oInfo->fileSize) ? $oInfo->fileSize : '';
						$mResult['Name'] = isset($oInfo->title) ? $oInfo->title : '';
						$mResult['LinkType'] = $iLinkType;
						$mResult['Thumb'] = isset($oInfo->thumbnail_url) ? $oInfo->thumbnail_url : null;
					}
					else
					{
						if (\api_Utils::GetLinkType($sUrl) === \EFileStorageLinkType::DropBox)
						{
							$sUrl = str_replace('?dl=0', '', $sUrl);
						}

						$sUrl = \api_Utils::GetRemoteFileRealUrl($sUrl);
						if ($sUrl)
						{
							$aRemoteFileInfo = \api_Utils::GetRemoteFileInfo($sUrl);
							$sFileName = basename($sUrl);
							$sFileExtension = \api_Utils::GetFileExtension($sFileName);

							if (empty($sFileExtension))
							{
								$sFileExtension = \api_Utils::GetFileExtensionFromMimeContentType($aRemoteFileInfo['content-type']);
								$sFileName .= '.'.$sFileExtension;
							}

							if ($sFileExtension === 'htm')
							{
								$oCurl = curl_init();
								\curl_setopt_array($oCurl, array(
									CURLOPT_URL => $sUrl,
									CURLOPT_FOLLOWLOCATION => true,
									CURLOPT_ENCODING => '',
									CURLOPT_RETURNTRANSFER => true,
									CURLOPT_AUTOREFERER => true,
									CURLOPT_SSL_VERIFYPEER => false, //required for https urls
									CURLOPT_CONNECTTIMEOUT => 5,
									CURLOPT_TIMEOUT => 5,
									CURLOPT_MAXREDIRS => 5
								));
								$sContent = curl_exec($oCurl);
								//$aInfo = curl_getinfo($oCurl);
								curl_close($oCurl);

								preg_match('/<title>(.*?)<\/title>/s', $sContent, $aTitle);
								$sTitle = isset($aTitle['1']) ? trim($aTitle['1']) : '';
							}

							$mResult['Name'] = isset($sTitle) && strlen($sTitle)> 0 ? $sTitle : urldecode($sFileName);
							$mResult['Size'] = $aRemoteFileInfo['size'];
						}
					}
				}
			}
		}
		
		return $this->DefaultResponse(null, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxSystemIsAuth()
	{
		$mResult = false;
		$oAccount = $this->getAccountFromParam(false);
		if ($oAccount)
		{
			$sClientTimeZone = trim($this->getParamValue('ClientTimeZone', ''));
			if ('' !== $sClientTimeZone)
			{
				$oAccount->User->ClientTimeZone = $sClientTimeZone;
				$this->oApiUsers->updateAccount($oAccount);
			}

			$mResult = array();
			$mResult['Extensions'] = array();

			// extensions
			if ($oAccount->isExtensionEnabled(\CAccount::IgnoreSubscribeStatus) &&
				!$oAccount->isExtensionEnabled(\CAccount::DisableManageSubscribe))
			{
				$oAccount->enableExtension(\CAccount::DisableManageSubscribe);
			}

			$aExtensions = $oAccount->getExtensionList();
			foreach ($aExtensions as $sExtensionName)
			{
				if ($oAccount->isExtensionEnabled($sExtensionName))
				{
					$mResult['Extensions'][] = $sExtensionName;
				}
			}
		}

		return $this->DefaultResponse(null, __FUNCTION__, $mResult);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param \CFetcher $oFetcher
	 * @param bool $bUpdate = false
	 */
	private function populateFetcherFromHttpPost($oAccount, &$oFetcher, $bUpdate = false)
	{
		if ($oFetcher)
		{
			$oFetcher->IdAccount = $oAccount->IdAccount;
			$oFetcher->IdUser = $oAccount->IdUser;
			$oFetcher->IdDomain = $oAccount->IdDomain;
			$oFetcher->IdTenant = $oAccount->IdTenant;

			if (!$bUpdate)
			{
				$oFetcher->IncomingMailServer = (string) $this->oHttp->GetPost('IncomingMailServer', $oFetcher->IncomingMailServer);
				$oFetcher->IncomingMailPort = (int) $this->oHttp->GetPost('IncomingMailPort', $oFetcher->IncomingMailPort);
				$oFetcher->IncomingMailLogin = (string) $this->oHttp->GetPost('IncomingMailLogin', $oFetcher->IncomingMailLogin);
			
				$oFetcher->IncomingMailSecurity = ('1' === (string) $this->oHttp->GetPost('IncomingMailSsl', $oFetcher->IncomingMailSecurity === \MailSo\Net\Enumerations\ConnectionSecurityType::SSL ? '1' : '0')) ?
						\MailSo\Net\Enumerations\ConnectionSecurityType::SSL : \MailSo\Net\Enumerations\ConnectionSecurityType::NONE;
			}

			$sIncomingMailPassword = (string) $this->oHttp->GetPost('IncomingMailPassword', $oFetcher->IncomingMailPassword);
			if ('******' !== $sIncomingMailPassword)
			{
				$oFetcher->IncomingMailPassword = $sIncomingMailPassword;
			}

			$oFetcher->Folder = (string) $this->oHttp->GetPost('Folder', $oFetcher->Folder);
			
			$oFetcher->IsEnabled = '1' === (string) $this->oHttp->GetPost('IsEnabled', $oFetcher->IsEnabled ? '1' : '0');

			$oFetcher->LeaveMessagesOnServer = '1' === (string) $this->oHttp->GetPost('LeaveMessagesOnServer', $oFetcher->LeaveMessagesOnServer ? '1' : '0');
			$oFetcher->Name = (string) $this->oHttp->GetPost('Name', $oFetcher->Name);
			$oFetcher->Email = (string) $this->oHttp->GetPost('Email', $oFetcher->Email);
			$oFetcher->Signature = (string) $this->oHttp->GetPost('Signature', $oFetcher->Signature);
			$oFetcher->SignatureOptions = (string) $this->oHttp->GetPost('SignatureOptions', $oFetcher->SignatureOptions);

			$oFetcher->IsOutgoingEnabled = '1' === (string) $this->oHttp->GetPost('IsOutgoingEnabled', $oFetcher->IsOutgoingEnabled ? '1' : '0');
			$oFetcher->OutgoingMailServer = (string) $this->oHttp->GetPost('OutgoingMailServer', $oFetcher->OutgoingMailServer);
			$oFetcher->OutgoingMailPort = (int) $this->oHttp->GetPost('OutgoingMailPort', $oFetcher->OutgoingMailPort);
			$oFetcher->OutgoingMailAuth = '1' === (string) $this->oHttp->GetPost('OutgoingMailAuth', $oFetcher->OutgoingMailAuth ? '1' : '0');
			
			$oFetcher->OutgoingMailSecurity = ('1' === (string) $this->oHttp->GetPost('OutgoingMailSsl', $oFetcher->OutgoingMailSecurity === \MailSo\Net\Enumerations\ConnectionSecurityType::SSL ? '1' : '0')) ?
					\MailSo\Net\Enumerations\ConnectionSecurityType::SSL : (587 === $oFetcher->OutgoingMailPort ?
						\MailSo\Net\Enumerations\ConnectionSecurityType::STARTTLS : \MailSo\Net\Enumerations\ConnectionSecurityType::NONE);
		}
	}

	/**
	 * @return array
	 */
	public function AjaxAccountFetcherGetList()
	{
		$oAccount = $this->getAccountFromParam();
		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->ApiFetchers()->getFetchers($oAccount));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountFetcherCreate()
	{
		$oAccount = $this->getAccountFromParam();
		$oFetcher = null;

		$this->ApiFetchers();

		$oFetcher = new \CFetcher($oAccount);
		$this->populateFetcherFromHttpPost($oAccount, $oFetcher);

		$bResult = $this->ApiFetchers()->createFetcher($oAccount, $oFetcher);
		if ($bResult)
		{
			$sStartScript = '/opt/afterlogic/scripts/webshell-mailfetch-on-create.sh'; // TODO
			if (@\file_exists($sStartScript))
			{
				@\shell_exec($sStartScript.' '.$oAccount->Email.' '.$oFetcher->IdFetcher);
			}

			return $this->TrueResponse($oAccount, __FUNCTION__);
		}

		$oExc = $this->ApiFetchers()->GetLastException();
		if ($oExc && $oExc instanceof \CApiBaseException)
		{
			switch ($oExc->getCode())
			{
				case \CApiErrorCodes::Fetcher_ConnectToMailServerFailed:
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FetcherConnectError);
				case \CApiErrorCodes::Fetcher_AuthError:
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FetcherAuthError);
			}

			return $this->ExceptionResponse($oAccount, __FUNCTION__, $oExc);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);

	}
	
	/**
	 * @return array
	 */
	public function AjaxAccountFetcherUpdate()
	{
		$oAccount = $this->getAccountFromParam();
		$oFetcher = null;

		$this->ApiFetchers();

		$iFetcherID = (int) $this->getParamValue('FetcherID', 0);
		if (0 < $iFetcherID)
		{
			$aFetchers = $this->ApiFetchers()->getFetchers($oAccount);
			if (is_array($aFetchers) && 0 < count($aFetchers))
			{
				foreach ($aFetchers as /* @var $oFetcherItem \CFetcher */ $oFetcherItem)
				{
					if ($oFetcherItem && $iFetcherID === $oFetcherItem->IdFetcher && $oAccount->IdUser === $oFetcherItem->IdUser)
					{
						$oFetcher = $oFetcherItem;
						break;
					}
				}
			}
		}

		if ($oFetcher && $iFetcherID === $oFetcher->IdFetcher)
		{
			$this->populateFetcherFromHttpPost($oAccount, $oFetcher, false);
		}

		$bResult = $oFetcher ? $this->ApiFetchers()->updateFetcher($oAccount, $oFetcher) : false;
		if ($bResult || !$oFetcher)
		{
			return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
		}

		$oExc = $this->ApiFetchers()->GetLastException();
		if ($oExc && $oExc instanceof \CApiBaseException)
		{
			switch ($oExc->getCode())
			{
				case \CApiErrorCodes::Fetcher_ConnectToMailServerFailed:
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FetcherConnectError);
				case \CApiErrorCodes::Fetcher_AuthError:
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FetcherAuthError);
			}

			return $this->ExceptionResponse($oAccount, __FUNCTION__, $oExc);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}
	
	/**
	 * @return array
	 */
	public function AjaxAccountFetcherDelete()
	{
		$oAccount = $this->getAccountFromParam();

		$iFetcherID = (int) $this->getParamValue('FetcherID', 0);
		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->ApiFetchers()->deleteFetcher($oAccount, $iFetcherID));
	}
	
	/**
	 * @return array
	 */
	public function AjaxDomainGetDataByEmail()
	{
		$oAccount = $this->getAccountFromParam();

		$sEmail = (string) $this->getParamValue('Email', '');
		$sDomainName = \MailSo\Base\Utils::GetDomainFromEmail($sEmail);
		if (empty($sEmail) || empty($sDomainName))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oApiDomains = /* @var $oApiDomains \CApiDomainsManager */ \CApi::Manager('domains');
		$oDomain = $oApiDomains->getDomainByName($sDomainName);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oDomain ? array(
			'IsInternal' => $oDomain->IsInternal,
			'IncomingMailServer' => $oDomain->IncomingMailServer,
			'IncomingMailPort' => $oDomain->IncomingMailPort,
			'OutgoingMailServer' => $oDomain->OutgoingMailServer,
			'OutgoingMailPort' => $oDomain->OutgoingMailPort,
			'OutgoingMailAuth' => $oDomain->OutgoingMailAuth,
			'IncomingMailSsl' => $oDomain->IncomingMailUseSSL,
			'OutgoingMailSsl' => $oDomain->OutgoingMailUseSSL,
			
		) : false);
	}

	/**
	 * @return array
	 */
	public function AjaxFoldersGetList()
	{
		$oAccount = $this->getAccountFromParam();
		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiMail->getFolders($oAccount));
	}

	/**
	 * @return array
	 */
	public function AjaxFileExpand()
	{
		$self = $this;
		$mResult = false;
		$oAccount = null;
		
		$sHash = \md5(\microtime(true).\rand(1000, 9999));
		$this->rawCallback((string) $this->getParamValue('RawKey', ''), function ($oAccount, $sContentType, $sFileName, $rResource) use ($self, $sHash, &$mResult) {

			if ($self->ApiFileCache()->putFile($oAccount, $sHash, $rResource))
			{
				$sFullFilePath = $self->ApiFileCache()->generateFullFilePath($oAccount, $sHash);

				$aExpand = array();
				\CApi::Plugin()->RunHook('webmail.expand-attachment',
					array($oAccount, $sContentType, $sFileName, $sFullFilePath, &$aExpand, $self->ApiFileCache()));

				if (is_array($aExpand) && 0 < \count($aExpand))
				{
					foreach ($aExpand as $aItem)
					{
						if ($aItem && isset($aItem['FileName'], $aItem['MimeType'], $aItem['Size'], $aItem['TempName']))
						{
							$mResult[] = array(
								'FileName' => $aItem['FileName'],
								'MimeType' => $aItem['MimeType'],
								'EstimatedSize' => $aItem['Size'],
								'CID' => '',
								'Thumb' => \CApi::GetConf('labs.allow-thumbnail', true) &&
									\api_Utils::IsGDImageMimeTypeSuppoted($aItem['MimeType'], $aItem['FileName']),
								'Expand' => \CApi::isExpandMimeTypeSupported($aItem['MimeType'], $aItem['FileName']),
								'Iframed' =>\CApi::isIframedMimeTypeSupported($aItem['MimeType'], $aItem['FileName']),
								'IsInline' => false,
								'IsLinked' => false,
								'Hash' => \CApi::EncodeKeyValues(array(
									'TempFile' => true,
									'Iframed' =>\CApi::isIframedMimeTypeSupported($aItem['MimeType'], $aItem['FileName']),
									'AccountID' => $oAccount->IdAccount,
									'Name' => $aItem['FileName'],
									'TempName' => $aItem['TempName']
								))
							);
						}
					}
				}
				
				$self->ApiFileCache()->clear($oAccount, $sHash);
			}

		}, false, $oAccount);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxFoldersSetupSystem()
	{
		$oAccount = $this->getAccountFromParam();
		
		$sSent = (string) $this->getParamValue('Sent', '');
		$sDrafts = (string) $this->getParamValue('Drafts', '');
		$sTrash = (string) $this->getParamValue('Trash', '');
		$sSpam = (string) $this->getParamValue('Spam', '');

		$aData = array();
		if (0 < strlen(trim($sSent)))
		{
			$aData[$sSent] = \EFolderType::Sent;
		}
		if (0 < strlen(trim($sDrafts)))
		{
			$aData[$sDrafts] = \EFolderType::Drafts;
		}
		if (0 < strlen(trim($sTrash)))
		{
			$aData[$sTrash] = \EFolderType::Trash;
		}
		if (0 < strlen(trim($sSpam)))
		{
			$aData[$sSpam] = \EFolderType::Spam;
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiMail->setSystemFolderNames($oAccount, $aData));
	}

	/**
	 * @return array
	 */
	public function AjaxFoldersGetRelevantInformation()
	{
		$aFolders = $this->getParamValue('Folders', '');
		$sInboxUidnext = $this->getParamValue('InboxUidnext', '');
		
		if (!is_array($aFolders) || 0 === count($aFolders))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$aResult = array();
		$oAccount = null;

		try
		{
			$oAccount = $this->getAccountFromParam();
			$oReturnInboxNewData = \ProjectCore\Base\DataByRef::createInstance(array());
			$aResult = $this->oApiMail->getFolderListInformation($oAccount, $aFolders, $sInboxUidnext, $oReturnInboxNewData);
		}
		catch (\MailSo\Net\Exceptions\ConnectionException $oException)
		{
			throw $oException;
		}
		catch (\MailSo\Imap\Exceptions\LoginException $oException)
		{
			throw $oException;
		}
		catch (\Exception $oException)
		{
			\CApi::Log((string) $oException);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'Counts' => $aResult,
			'New' => $oReturnInboxNewData->GetData()
		));
	}

	/**
	 * @return array
	 */
	public function AjaxFolderCreate()
	{
		$sFolderNameInUtf8 = trim((string) $this->getParamValue('FolderNameInUtf8', ''));
		$sDelimiter = trim((string) $this->getParamValue('Delimiter', ''));
		$sFolderParentFullNameRaw = (string) $this->getParamValue('FolderParentFullNameRaw', '');

		if (0 === strlen($sFolderNameInUtf8) || 1 !== strlen($sDelimiter))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->createFolder($oAccount, $sFolderNameInUtf8, $sDelimiter, $sFolderParentFullNameRaw);

		if (!$oAccount->isExtensionEnabled(\CAccount::DisableFoldersManualSort))
		{
			$aFoldersOrderList = $this->oApiMail->getFoldersOrder($oAccount);
			if (is_array($aFoldersOrderList) && 0 < count($aFoldersOrderList))
			{
				$aFoldersOrderListNew = $aFoldersOrderList;

				$sFolderNameInUtf7Imap = \MailSo\Base\Utils::ConvertEncoding($sFolderNameInUtf8,
					\MailSo\Base\Enumerations\Charset::UTF_8,
					\MailSo\Base\Enumerations\Charset::UTF_7_IMAP);

				$sFolderFullNameRaw = (0 < strlen($sFolderParentFullNameRaw) ? $sFolderParentFullNameRaw.$sDelimiter : '').
					$sFolderNameInUtf7Imap;

				$sFolderFullNameUtf8 = \MailSo\Base\Utils::ConvertEncoding($sFolderFullNameRaw,
					\MailSo\Base\Enumerations\Charset::UTF_7_IMAP,
					\MailSo\Base\Enumerations\Charset::UTF_8);

				$aFoldersOrderListNew[] = $sFolderFullNameRaw;

				$aFoldersOrderListUtf8 = array_map(function ($sValue) {
					return \MailSo\Base\Utils::ConvertEncoding($sValue,
						\MailSo\Base\Enumerations\Charset::UTF_7_IMAP,
						\MailSo\Base\Enumerations\Charset::UTF_8);
				}, $aFoldersOrderListNew);

				usort($aFoldersOrderListUtf8, 'strnatcasecmp');
				
				$iKey = array_search($sFolderFullNameUtf8, $aFoldersOrderListUtf8, true);
				if (is_int($iKey) && 0 < $iKey && isset($aFoldersOrderListUtf8[$iKey - 1]))
				{
					$sUpperName = $aFoldersOrderListUtf8[$iKey - 1];

					$iUpperKey = array_search(\MailSo\Base\Utils::ConvertEncoding($sUpperName,
						\MailSo\Base\Enumerations\Charset::UTF_8,
						\MailSo\Base\Enumerations\Charset::UTF_7_IMAP), $aFoldersOrderList, true);

					if (is_int($iUpperKey) && isset($aFoldersOrderList[$iUpperKey]))
					{
						\CApi::Log('insert order index:'.$iUpperKey);
						array_splice($aFoldersOrderList, $iUpperKey + 1, 0, $sFolderFullNameRaw);
						$this->oApiMail->updateFoldersOrder($oAccount, $aFoldersOrderList);
					}
				}
			}
		}

		return $this->TrueResponse($oAccount, __FUNCTION__);
	}
	
	/**
	 * @return array
	 */
	public function AjaxFolderRename()
	{
		$sPrevFolderFullNameRaw = (string) $this->getParamValue('PrevFolderFullNameRaw', '');
		$sNewFolderNameInUtf8 = trim($this->getParamValue('NewFolderNameInUtf8', ''));
		
		if (0 === strlen($sPrevFolderFullNameRaw) || 0 === strlen($sNewFolderNameInUtf8))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$mResult = $this->oApiMail->renameFolder($oAccount, $sPrevFolderFullNameRaw, $sNewFolderNameInUtf8);

		return $this->DefaultResponse($oAccount, __FUNCTION__, 0 < strlen($mResult) ? array(
			'FullName' => $mResult,
			'FullNameHash' => md5($mResult)
		) : false);
	}

	/**
	 * @return array
	 */
	public function AjaxFolderDelete()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');

		if (0 === strlen(trim($sFolderFullNameRaw)))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->deleteFolder($oAccount, $sFolderFullNameRaw);

		return $this->TrueResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxFolderSubscribe()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$bSetAction = '1' === (string) $this->getParamValue('SetAction', '0');

		if (0 === strlen(trim($sFolderFullNameRaw)))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		if (!$oAccount->isExtensionEnabled(\CAccount::DisableManageSubscribe))
		{
			$this->oApiMail->subscribeFolder($oAccount, $sFolderFullNameRaw, $bSetAction);
			return $this->TrueResponse($oAccount, __FUNCTION__);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxFoldersUpdateOrder()
	{
		$aFolderList = $this->getParamValue('FolderList', null);
		if (!is_array($aFolderList))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();
		if ($oAccount->isExtensionEnabled(\CAccount::DisableFoldersManualSort))
		{
			return $this->FalseResponse($oAccount, __FUNCTION__);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__,
			$this->oApiMail->updateFoldersOrder($oAccount, $aFolderList));
	}

	/**
	 * @return array
	 */
	public function AjaxFolderClear()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');

		if (0 === strlen(trim($sFolderFullNameRaw)))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->clearFolder($oAccount, $sFolderFullNameRaw);

		return $this->TrueResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxMessagesGetList()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$sOffset = trim((string) $this->getParamValue('Offset', ''));
		$sLimit = trim((string) $this->getParamValue('Limit', ''));
		$sSearch = trim((string) $this->getParamValue('Search', ''));
		$bUseThreads = '1' === (string) $this->getParamValue('UseThreads', '0');
		$sInboxUidnext = $this->getParamValue('InboxUidnext', '');
		
		$aFilters = array();
		$sFilters = strtolower(trim((string) $this->getParamValue('Filters', '')));
		if (0 < strlen($sFilters))
		{
			$aFilters = array_filter(explode(',', $sFilters), function ($sValue) {
				return '' !== trim($sValue);
			});
		}

		$iOffset = 0 < strlen($sOffset) && is_numeric($sOffset) ? (int) $sOffset : 0;
		$iLimit = 0 < strlen($sLimit) && is_numeric($sLimit) ? (int) $sLimit : 0;

		if (0 === strlen(trim($sFolderFullNameRaw)) || 0 > $iOffset || 0 >= $iLimit || 200 < $sLimit)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$oMessageList = $this->oApiMail->getMessageList(
			$oAccount, $sFolderFullNameRaw, $iOffset, $iLimit, $sSearch, $bUseThreads, $aFilters, $sInboxUidnext);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oMessageList);
	}
	
	/**
	 * @return array
	 */
	public function AjaxMessagesGetListByUids()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$aUids = $this->getParamValue('Uids', array());

		if (0 === strlen(trim($sFolderFullNameRaw)) || !is_array($aUids))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$oMessageList = $this->oApiMail->getMessageListByUids($oAccount, $sFolderFullNameRaw, $aUids);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oMessageList);
	}

	/**
	 * @return array
	 */
	public function AjaxMessagesGetFlags()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$aUids = $this->getParamValue('Uids', array());

		if (0 === strlen(trim($sFolderFullNameRaw)) || !is_array($aUids))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$aMessageFlags = $this->oApiMail->getMessagesFlags($oAccount, $sFolderFullNameRaw, $aUids);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aMessageFlags);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageAttachmentsZip()
	{
		$aHashes = $this->getParamValue('Hashes', null);
		if (!is_array($aHashes) || 0 === count($aHashes) || !class_exists('ZipArchive'))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$mResult = false;
		$oAccount = $this->getAccountFromParam();

		$self = $this;
		$aAdd = array();
		foreach ($aHashes as $sHash)
		{
			$this->rawCallback($sHash, function ($oAccount, $sContentType, $sFileName, $rResource) use ($self, $sHash, &$aAdd) {

				$sHash = md5($sHash.rand(1000, 9999));
				if ($self->ApiFileCache()->putFile($oAccount, $sHash, $rResource))
				{
					$sFullFilePath = $self->ApiFileCache()->generateFullFilePath($oAccount, $sHash);
					$aAdd[] = array($sFullFilePath, $sFileName, $sContentType);
				}

			}, false, $oAccount);
		}

		if (0 < count($aAdd))
		{
			include_once PSEVEN_APP_ROOT_PATH.'libraries/other/Zip.php';

			$oZip = new \Zip();
			
			$sZipHash = md5(implode(',', $aHashes).rand(1000, 9999));
			foreach ($aAdd as $aItem)
			{
				$oZip->addFile(fopen($aItem[0], 'r'), $aItem[1]);
			}

			$self->ApiFileCache()->putFile($oAccount, $sZipHash, $oZip->getZipFile());
			$mResult = \CApi::EncodeKeyValues(array(
				'TempFile' => true,
				'AccountID' => $oAccount->IdAccount,
				'Name' => 'attachments.zip',
				'TempName' => $sZipHash
			));
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageMove()
	{
		$sFromFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$sToFolderFullNameRaw = (string) $this->getParamValue('ToFolder', '');
		$aUids = \ProjectCore\Base\Utils::ExplodeIntUids((string) $this->getParamValue('Uids', ''));

		if (0 === strlen(trim($sFromFolderFullNameRaw)) || 0 === strlen(trim($sToFolderFullNameRaw)) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		try
		{
			$this->oApiMail->moveMessage(
				$oAccount, $sFromFolderFullNameRaw, $sToFolderFullNameRaw, $aUids);
		}
		catch (\MailSo\Imap\Exceptions\NegativeResponseException $oException)
		{
			$oResponse = /* @var $oResponse \MailSo\Imap\Response */ $oException->GetLastResponse();
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CanNotMoveMessageQuota, $oException,
				$oResponse instanceof \MailSo\Imap\Response ? $oResponse->Tag.' '.$oResponse->StatusOrIndex.' '.$oResponse->HumanReadable : '');
		}
		catch (\Exception $oException)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CanNotMoveMessage, $oException,
				$oException->getMessage());
		}

		return $this->TrueResponse($oAccount, __FUNCTION__);
	}

	public function AjaxMessageCopy()
	{
		$sFromFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$sToFolderFullNameRaw = (string) $this->getParamValue('ToFolder', '');
		$aUids = \ProjectCore\Base\Utils::ExplodeIntUids((string) $this->getParamValue('Uids', ''));

		if (0 === strlen(trim($sFromFolderFullNameRaw)) || 0 === strlen(trim($sToFolderFullNameRaw)) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		try
		{
			$this->oApiMail->copyMessage(
				$oAccount, $sFromFolderFullNameRaw, $sToFolderFullNameRaw, $aUids);
		}
		catch (\MailSo\Imap\Exceptions\NegativeResponseException $oException)
		{
			$oResponse = /* @var $oResponse \MailSo\Imap\Response */ $oException->GetLastResponse();
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CanNotCopyMessageQuota, $oException,
				$oResponse instanceof \MailSo\Imap\Response ? $oResponse->Tag.' '.$oResponse->StatusOrIndex.' '.$oResponse->HumanReadable : '');
		}
		catch (\Exception $oException)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CanNotCopyMessage, $oException,
				$oException->getMessage());
		}

		return $this->TrueResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageDelete()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		
		$aUids = \ProjectCore\Base\Utils::ExplodeIntUids((string) $this->getParamValue('Uids', ''));

		if (0 === strlen(trim($sFolderFullNameRaw)) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->deleteMessage($oAccount, $sFolderFullNameRaw, $aUids);

		return $this->TrueResponse($oAccount, __FUNCTION__);
	}

	/**
	 * When using a memory stream and the read
	 * filter "convert.base64-encode" the last 
	 * character is missing from the output if 
	 * the base64 conversion needs padding bytes. 
	 * 
	 * @return bool
	 */
	private function FixBase64EncodeOmitsPaddingBytes($sRaw)
	{
		$rStream = fopen('php://memory','r+');
		fwrite($rStream, '0');
		rewind($rStream);
		$rFilter = stream_filter_append($rStream, 'convert.base64-encode');
		
		if (0 === strlen(stream_get_contents($rStream)))
		{
			$iFileSize = \strlen($sRaw);
			$sRaw = str_pad($sRaw, $iFileSize + ($iFileSize % 3));
		}
		
		return $sRaw;
	}
	
	/**
	 * @param \CAccount $oAccount
	 * @param \CFetcher $oFetcher = null
	 * @param bool $bWithDraftInfo = true
	 * @param \CIdentity $oIdentity = null
	 *
	 * @return \MailSo\Mime\Message
	 */
	private function buildMessage($oAccount, $oFetcher = null, $bWithDraftInfo = true, $oIdentity = null)
	{
		$sTo = $this->getParamValue('To', '');
		$sCc = $this->getParamValue('Cc', '');
		$sBcc = $this->getParamValue('Bcc', '');
		$sSubject = $this->getParamValue('Subject', '');
		$bTextIsHtml = '1' === $this->getParamValue('IsHtml', '0');
		$sText = $this->getParamValue('Text', '');
		$aAttachments = $this->getParamValue('Attachments', null);

		$aDraftInfo = $this->getParamValue('DraftInfo', null);
		$sInReplyTo = $this->getParamValue('InReplyTo', '');
		$sReferences = $this->getParamValue('References', '');

		$sImportance = $this->getParamValue('Importance', ''); // 1 3 5
		$sSensitivity = $this->getParamValue('Sensitivity', ''); // 0 1 2 3 4
		$bReadingConfirmation = '1' === $this->getParamValue('ReadingConfirmation', '0');

		$oMessage = \MailSo\Mime\Message::NewInstance();
		$oMessage->RegenerateMessageId();

		$sXMailer = \CApi::GetConf('webmail.xmailer-value', '');
		if (0 < strlen($sXMailer))
		{
			$oMessage->SetXMailer($sXMailer);
		}

		if ($oIdentity)
		{
			$oFrom = \MailSo\Mime\Email::NewInstance($oIdentity->Email, $oIdentity->FriendlyName);
		}
		else
		{
			$oFrom = $oFetcher
				? \MailSo\Mime\Email::NewInstance($oFetcher->Email, $oFetcher->Name)
				: \MailSo\Mime\Email::NewInstance($oAccount->Email, $oAccount->FriendlyName);
		}

		$oMessage
			->SetFrom($oFrom)
			->SetSubject($sSubject)
		;

		$oToEmails = \MailSo\Mime\EmailCollection::NewInstance($sTo);
		if ($oToEmails && $oToEmails->Count())
		{
			$oMessage->SetTo($oToEmails);
		}

		$oCcEmails = \MailSo\Mime\EmailCollection::NewInstance($sCc);
		if ($oCcEmails && $oCcEmails->Count())
		{
			$oMessage->SetCc($oCcEmails);
		}

		$oBccEmails = \MailSo\Mime\EmailCollection::NewInstance($sBcc);
		if ($oBccEmails && $oBccEmails->Count())
		{
			$oMessage->SetBcc($oBccEmails);
		}

		if ($bWithDraftInfo && is_array($aDraftInfo) && !empty($aDraftInfo[0]) && !empty($aDraftInfo[1]) && !empty($aDraftInfo[2]))
		{
			$oMessage->SetDraftInfo($aDraftInfo[0], $aDraftInfo[1], $aDraftInfo[2]);
		}

		if (0 < strlen($sInReplyTo))
		{
			$oMessage->SetInReplyTo($sInReplyTo);
		}

		if (0 < strlen($sReferences))
		{
			$oMessage->SetReferences($sReferences);
		}
		
		if (0 < strlen($sImportance) && in_array((int) $sImportance, array(
			\MailSo\Mime\Enumerations\MessagePriority::HIGH,
			\MailSo\Mime\Enumerations\MessagePriority::NORMAL,
			\MailSo\Mime\Enumerations\MessagePriority::LOW
		)))
		{
			$oMessage->SetPriority((int) $sImportance);
		}

		if (0 < strlen($sSensitivity) && in_array((int) $sSensitivity, array(
			\MailSo\Mime\Enumerations\Sensitivity::NOTHING,
			\MailSo\Mime\Enumerations\Sensitivity::CONFIDENTIAL,
			\MailSo\Mime\Enumerations\Sensitivity::PRIVATE_,
			\MailSo\Mime\Enumerations\Sensitivity::PERSONAL,
		)))
		{
			$oMessage->SetSensitivity((int) $sSensitivity);
		}

		if ($bReadingConfirmation)
		{
			$oMessage->SetReadConfirmation($oFetcher ? $oFetcher->Email : $oAccount->Email);
		}

		$aFoundCids = array();

		\CApi::Plugin()->RunHook('webmail.message-text-html-raw', array($oAccount, &$oMessage, &$sText, &$bTextIsHtml));

		if ($bTextIsHtml)
		{
			$sTextConverted = \MailSo\Base\HtmlUtils::ConvertHtmlToPlain($sText);
			\CApi::Plugin()->RunHook('webmail.message-plain-part', array($oAccount, &$oMessage, &$sTextConverted));
			$oMessage->AddText($sTextConverted, false);
		}

		$mFoundDataURL = array();
		$aFoundedContentLocationUrls = array();

		$sTextConverted = $bTextIsHtml ? 
			\MailSo\Base\HtmlUtils::BuildHtml($sText, $aFoundCids, $mFoundDataURL, $aFoundedContentLocationUrls) : $sText;
		
		\CApi::Plugin()->RunHook($bTextIsHtml ? 'webmail.message-html-part' : 'webmail.message-plain-part',
			array($oAccount, &$oMessage, &$sTextConverted));

		$oMessage->AddText($sTextConverted, $bTextIsHtml);

		if (is_array($aAttachments))
		{
			foreach ($aAttachments as $sTempName => $aData)
			{
				if (is_array($aData) && isset($aData[0], $aData[1], $aData[2], $aData[3]))
				{
					$sFileName = (string) $aData[0];
					$sCID = (string) $aData[1];
					$bIsInline = '1' === (string) $aData[2];
					$bIsLinked = '1' === (string) $aData[3];
					$sContentLocation = isset($aData[4]) ? (string) $aData[4] : '';

					$rResource = $this->ApiFileCache()->getFile($oAccount, $sTempName);
					if (is_resource($rResource))
					{
						$iFileSize = $this->ApiFileCache()->fileSize($oAccount, $sTempName);

						$sCID = trim(trim($sCID), '<>');
						$bIsFounded = 0 < strlen($sCID) ? in_array($sCID, $aFoundCids) : false;

						if (!$bIsLinked || $bIsFounded)
						{
							$oMessage->Attachments()->Add(
								\MailSo\Mime\Attachment::NewInstance($rResource, $sFileName, $iFileSize, $bIsInline,
									$bIsLinked, $bIsLinked ? '<'.$sCID.'>' : '', array(), $sContentLocation)
							);
						}
					}
				}
			}
		}

		if ($mFoundDataURL && \is_array($mFoundDataURL) && 0 < \count($mFoundDataURL))
		{
			foreach ($mFoundDataURL as $sCidHash => $sDataUrlString)
			{
				$aMatch = array();
				$sCID = '<'.$sCidHash.'>';
				if (\preg_match('/^data:(image\/[a-zA-Z0-9]+\+?[a-zA-Z0-9]+);base64,(.+)$/i', $sDataUrlString, $aMatch) &&
					!empty($aMatch[1]) && !empty($aMatch[2]))
				{
					$sRaw = \MailSo\Base\Utils::Base64Decode($aMatch[2]);
					$iFileSize = \strlen($sRaw);
					if (0 < $iFileSize)
					{
						$sFileName = \preg_replace('/[^a-z0-9]+/i', '.', \MailSo\Base\Utils::NormalizeContentType($aMatch[1]));
						
						// fix bug #68532 php < 5.5.21 or php < 5.6.5
						$sRaw = $this->FixBase64EncodeOmitsPaddingBytes($sRaw);
						
						$rResource = \MailSo\Base\ResourceRegistry::CreateMemoryResourceFromString($sRaw);

						$sRaw = '';
						unset($sRaw);
						unset($aMatch);

						$oMessage->Attachments()->Add(
							\MailSo\Mime\Attachment::NewInstance($rResource, $sFileName, $iFileSize, true, true, $sCID)
						);
					}
				}
			}
		}

		\CApi::Plugin()->RunHook('webmail.build-message', array(&$oMessage));

		return $oMessage;
	}

	/**
	 * @param \CAccount $oAccount
	 *
	 * @return \MailSo\Mime\Message
	 *
	 * @throws \MailSo\Base\Exceptions\InvalidArgumentException
	 */
	private function buildConfirmationMessage($oAccount)
	{
		$sConfirmation = $this->getParamValue('Confirmation', '');
		$sSubject = $this->getParamValue('Subject', '');
		$sText = $this->getParamValue('Text', '');

		if (0 === strlen($sConfirmation) || 0 === strlen($sSubject) || 0 === strlen($sText))
		{
			throw new \MailSo\Base\Exceptions\InvalidArgumentException();
		}

		$oMessage = \MailSo\Mime\Message::NewInstance();
		$oMessage->RegenerateMessageId();

		$sXMailer = \CApi::GetConf('webmail.xmailer-value', '');
		if (0 < strlen($sXMailer))
		{
			$oMessage->SetXMailer($sXMailer);
		}

		$oTo = \MailSo\Mime\EmailCollection::Parse($sConfirmation);
		if (!$oTo || 0 === $oTo->Count())
		{
			throw new \MailSo\Base\Exceptions\InvalidArgumentException();
		}

		$sFrom = 0 < strlen($oAccount->FriendlyName) ? '"'.$oAccount->FriendlyName.'"' : '';
		if (0 < strlen($sFrom))
		{
			$sFrom .= ' <'.$oAccount->Email.'>';
		}
		else
		{
			$sFrom .= $oAccount->Email;
		}
		
		$oMessage
			->SetFrom(\MailSo\Mime\Email::NewInstance($sFrom))
			->SetTo($oTo)
			->SetSubject($sSubject)
		;

		$oMessage->AddText($sText, false);

		return $oMessage;
	}
	
	/**
	 * @return array
	 */
	public function AjaxMessageSend()
	{
		$oAccount = $this->getAccountFromParam();
		
		$sSentFolder = $this->getParamValue('SentFolder', '');
		$sDraftFolder = $this->getParamValue('DraftFolder', '');
		$sDraftUid = $this->getParamValue('DraftUid', '');
		$aDraftInfo = $this->getParamValue('DraftInfo', null);
		
		$sFetcherID = $this->getParamValue('FetcherID', '');
		$sIdIdentity = $this->getParamValue('IdentityID', '');

		$oFetcher = null;
		if (!empty($sFetcherID) && is_numeric($sFetcherID) && 0 < (int) $sFetcherID)
		{
			$iFetcherID = (int) $sFetcherID;

			$oApiFetchers = $this->ApiFetchers();
			$aFetchers = $oApiFetchers->getFetchers($oAccount);
			if (is_array($aFetchers) && 0 < count($aFetchers))
			{
				foreach ($aFetchers as /* @var $oFetcherItem \CFetcher */ $oFetcherItem)
				{
					if ($oFetcherItem && $iFetcherID === $oFetcherItem->IdFetcher && $oAccount->IdUser === $oFetcherItem->IdUser)
					{
						$oFetcher = $oFetcherItem;
						break;
					}
				}
			}
		}

		$oIdentity = null;
		if (!empty($sIdIdentity) && is_numeric($sIdIdentity) && 0 < (int) $sIdIdentity)
		{
			$oIdentity = $this->oApiUsers->getIdentity((int) $sIdIdentity);
		}

		$oMessage = $this->buildMessage($oAccount, $oFetcher, false, $oIdentity);
		if ($oMessage)
		{
			\CApi::Plugin()->RunHook('webmail.validate-message-for-send', array(&$oAccount, &$oMessage));

			try
			{
				\CApi::Plugin()->RunHook('webmail.build-message-for-send', array(&$oMessage));

				$mResult = $this->oApiMail->sendMessage($oAccount, $oMessage, $oFetcher, $sSentFolder, $sDraftFolder, $sDraftUid);
			}
			catch (\CApiManagerException $oException)
			{
				$iCode = \ProjectCore\Notifications::CanNotSendMessage;
				switch ($oException->getCode())
				{
					case \Errs::Mail_InvalidRecipients:
						$iCode = \ProjectCore\Notifications::InvalidRecipients;
						break;
					case \Errs::Mail_CannotSendMessage:
						$iCode = \ProjectCore\Notifications::CanNotSendMessage;
						break;
					case \Errs::Mail_CannotSaveMessageInSentItems:
						$iCode = \ProjectCore\Notifications::CannotSaveMessageInSentItems;
						break;
					case \Errs::Mail_MailboxUnavailable:
						$iCode = \ProjectCore\Notifications::MailboxUnavailable;
						break;
				}

				throw new \ProjectCore\Exceptions\ClientException($iCode, $oException, $oException->GetPreviousMessage(), $oException->GetObjectParams());
			}

			if ($mResult)
			{
				\CApi::Plugin()->RunHook('webmail.message-success-send', array(&$oAccount, &$oMessage));

				$oApiContacts = $this->ApiContacts();
				if ($oApiContacts)
				{
					$aCollection = $oMessage->GetRcpt();

					$aEmails = array();
					$aCollection->ForeachList(function ($oEmail) use (&$aEmails) {
						$aEmails[strtolower($oEmail->GetEmail())] = trim($oEmail->GetDisplayName());
					});

					if (is_array($aEmails))
					{
						\CApi::Plugin()->RunHook('webmail.message-suggest-email', array(&$oAccount, &$aEmails));
						
						$oApiContacts->updateSuggestTable($oAccount->IdUser, $aEmails);
					}
				}
			}

			if (is_array($aDraftInfo) && 3 === count($aDraftInfo))
			{
				$sDraftInfoType = $aDraftInfo[0];
				$sDraftInfoUid = $aDraftInfo[1];
				$sDraftInfoFolder = $aDraftInfo[2];

				try
				{
					switch (strtolower($sDraftInfoType))
					{
						case 'reply':
						case 'reply-all':
							$this->oApiMail->setMessageFlag($oAccount,
								$sDraftInfoFolder, array($sDraftInfoUid),
								\MailSo\Imap\Enumerations\MessageFlag::ANSWERED,
								\EMailMessageStoreAction::Add);
							break;
						case 'forward':
							$this->oApiMail->setMessageFlag($oAccount,
								$sDraftInfoFolder, array($sDraftInfoUid),
								'$Forwarded',
								\EMailMessageStoreAction::Add);
							break;
					}
				}
				catch (\Exception $oException) {}
			}
		}

		\CApi::LogEvent(\EEvents::MessageSend, $oAccount);
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxMessageSendConfirmation()
	{
		$oAccount = $this->getAccountFromParam();
		
		$oMessage = $this->buildConfirmationMessage($oAccount);
		if ($oMessage)
		{
			try
			{
				$mResult = $this->oApiMail->sendMessage($oAccount, $oMessage);
			}
			catch (\CApiManagerException $oException)
			{
				$iCode = \ProjectCore\Notifications::CanNotSendMessage;
				switch ($oException->getCode())
				{
					case \Errs::Mail_InvalidRecipients:
						$iCode = \ProjectCore\Notifications::InvalidRecipients;
						break;
					case \Errs::Mail_CannotSendMessage:
						$iCode = \ProjectCore\Notifications::CanNotSendMessage;
						break;
				}

				throw new \ProjectCore\Exceptions\ClientException($iCode, $oException);
			}

			$sConfirmFolderFullNameRaw = $this->getParamValue('ConfirmFolder', '');
			$sConfirmUid = $this->getParamValue('ConfirmUid', '');

			if (0 < \strlen($sConfirmFolderFullNameRaw) && 0 < \strlen($sConfirmUid))
			{
				try
				{
					$mResult = $this->oApiMail->setMessageFlag($oAccount, $sConfirmFolderFullNameRaw, array($sConfirmUid), '$ReadConfirm', 
						\EMailMessageStoreAction::Add, false, true);
				}
				catch (\Exception $oException) {}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageAttachmentsSaveToFiles()
	{
		$oDefAccount = null;
		$oAccount = $this->getAccountFromParam();

		if ($oAccount && $oAccount->IsDefaultAccount)
		{
			$oDefAccount = $oAccount;
		}
		else
		{
			$oDefAccount = $this->getDefaultAccountFromParam();
		}

		if (!$this->oApiCapability->isFilesSupported($oDefAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}

		$mResult = false;
		$self = $this;

		$oApiFilestorage = $self->oApiFilestorage;

		try
		{
			$aAttachments = $this->getParamValue('Attachments', array());
			if (is_array($aAttachments) && 0 < count($aAttachments) && $oApiFilestorage)
			{
				$mResult = array();
				foreach ($aAttachments as $sAttachment)
				{
					$aValues = \CApi::DecodeKeyValues($sAttachment);
					if (is_array($aValues))
					{
						$sFolder = isset($aValues['Folder']) ? $aValues['Folder'] : '';
						$iUid = (int) isset($aValues['Uid']) ? $aValues['Uid'] : 0;
						$sMimeIndex = (string) isset($aValues['MimeIndex']) ? $aValues['MimeIndex'] : '';

						$this->oApiMail->directMessageToStream($oAccount,
							function($rResource, $sContentType, $sFileName, $sMimeIndex = '') use ($oDefAccount, &$mResult, $sAttachment, $self, $oApiFilestorage) {

								$sTempName = \md5(\time().\rand(1000, 9999).$sFileName);

								if (is_resource($rResource) &&
									$self->ApiFileCache()->putFile($oDefAccount, $sTempName, $rResource))
								{
									$sContentType = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
									$sFileName = $self->clearFileName($sFileName, $sContentType, $sMimeIndex);

									$rSubResource = $self->ApiFileCache()->getFile($oDefAccount, $sTempName);
									if (is_resource($rSubResource))
									{
										$mResult[$sAttachment] = $oApiFilestorage->createFile(
											$oDefAccount, \EFileStorageTypeStr::Personal, '', $sFileName, $rSubResource, false);
									}

									$self->ApiFileCache()->clear($oDefAccount, $sTempName);
								}
							}, $sFolder, $iUid, $sMimeIndex);
					}
				}
			}
		}
		catch (\Exception $oException)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::MailServerError, $oException);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageAttachmentsUpload()
	{
		$oAccount = $this->getAccountFromParam();

		$mResult = false;
		$self = $this;

		try
		{
			$aAttachments = $this->getParamValue('Attachments', array());
			if (is_array($aAttachments) && 0 < count($aAttachments))
			{
				$mResult = array();
				foreach ($aAttachments as $sAttachment)
				{
					$aValues = \CApi::DecodeKeyValues($sAttachment);
					if (is_array($aValues))
					{
						$sFolder = isset($aValues['Folder']) ? $aValues['Folder'] : '';
						$iUid = (int) isset($aValues['Uid']) ? $aValues['Uid'] : 0;
						$sMimeIndex = (string) isset($aValues['MimeIndex']) ? $aValues['MimeIndex'] : '';

						$sTempName = md5($sAttachment);
						if (!$this->ApiFileCache()->isFileExists($oAccount, $sTempName))
						{
							$this->oApiMail->directMessageToStream($oAccount,
								function($rResource, $sContentType, $sFileName, $sMimeIndex = '') use ($oAccount, &$mResult, $sTempName, $sAttachment, $self) {
									if (is_resource($rResource))
									{
										$sContentType = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
										$sFileName = $self->clearFileName($sFileName, $sContentType, $sMimeIndex);

										if ($self->ApiFileCache()->putFile($oAccount, $sTempName, $rResource))
										{
											$mResult[$sTempName] = $sAttachment;
										}
									}
								}, $sFolder, $iUid, $sMimeIndex);
						}
						else
						{
							$mResult[$sTempName] = $sAttachment;
						}
					}
				}
			}
		}
		catch (\Exception $oException)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::MailServerError, $oException);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageSave()
	{
		$mResult = false;

		$oAccount = $this->getAccountFromParam();

		$sDraftFolder = $this->getParamValue('DraftFolder', '');
		$sDraftUid = $this->getParamValue('DraftUid', '');

		$sFetcherID = $this->getParamValue('FetcherID', '');
		$sIdIdentity = $this->getParamValue('IdentityID', '');

		if (0 === strlen($sDraftFolder))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oFetcher = null;
		if (!empty($sFetcherID) && is_numeric($sFetcherID) && 0 < (int) $sFetcherID)
		{
			$iFetcherID = (int) $sFetcherID;

			$oApiFetchers = $this->ApiFetchers();
			$aFetchers = $oApiFetchers->getFetchers($oAccount);
			if (is_array($aFetchers) && 0 < count($aFetchers))
			{
				foreach ($aFetchers as /* @var $oFetcherItem \CFetcher */ $oFetcherItem)
				{
					if ($oFetcherItem && $iFetcherID === $oFetcherItem->IdFetcher && $oAccount->IdUser === $oFetcherItem->IdUser)
					{
						$oFetcher = $oFetcherItem;
						break;
					}
				}
			}
		}

		$oIdentity = null;
		if (!empty($sIdIdentity) && is_numeric($sIdIdentity) && 0 < (int) $sIdIdentity)
		{
			$oIdentity = $this->oApiUsers->getIdentity((int) $sIdIdentity);
		}

		$oMessage = $this->buildMessage($oAccount, $oFetcher, true, $oIdentity);
		if ($oMessage)
		{
			try
			{
				\CApi::Plugin()->RunHook('webmail.build-message-for-save', array(&$oMessage));
				
				$mResult = $this->oApiMail->saveMessage($oAccount, $oMessage, $sDraftFolder, $sDraftUid);
			}
			catch (\CApiManagerException $oException)
			{
				$iCode = \ProjectCore\Notifications::CanNotSaveMessage;
				throw new \ProjectCore\Exceptions\ClientException($iCode, $oException);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	private function ajaxMessageFlagSet($sFlagName, $sFunctionName)
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$bSetAction = '1' === (string) $this->getParamValue('SetAction', '0');
		$aUids = \ProjectCore\Base\Utils::ExplodeIntUids((string) $this->getParamValue('Uids', ''));

		if (0 === strlen(trim($sFolderFullNameRaw)) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->setMessageFlag($oAccount, $sFolderFullNameRaw, $aUids, $sFlagName,
			$bSetAction ? \EMailMessageStoreAction::Add : \EMailMessageStoreAction::Remove);

		return $this->TrueResponse($oAccount, $sFunctionName);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageSetFlagged()
	{
		return $this->ajaxMessageFlagSet(\MailSo\Imap\Enumerations\MessageFlag::FLAGGED, 'MessageSetFlagged');
	}

	/**
	 * @return array
	 */
	public function AjaxMessageSetSeen()
	{
		return $this->ajaxMessageFlagSet(\MailSo\Imap\Enumerations\MessageFlag::SEEN, 'MessageSetSeen');
	}

	/**
	 * @return array
	 */
	public function AjaxMessagesSetAllSeen()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$bSetAction = '1' === (string) $this->getParamValue('SetAction', '0');

		if (0 === strlen(trim($sFolderFullNameRaw)))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->setMessageFlag($oAccount, $sFolderFullNameRaw, array('1'),
			\MailSo\Imap\Enumerations\MessageFlag::SEEN,
			$bSetAction ? \EMailMessageStoreAction::Add : \EMailMessageStoreAction::Remove, true);

		return $this->TrueResponse($oAccount, 'MessagesSetAllSeen');
	}

	/**
	 * @return array
	 */
	public function AjaxAccountGetQuota()
	{
		$oAccount = $this->getAccountFromParam();

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiMail->getQuota($oAccount));
	}

	/**
	 * @return bool
	 */
	public function AjaxMessageGetPdfFromHtml()
	{
		$oAccount = $this->getAccountFromParam();
		if ($oAccount)
		{
			$sSubject = (string) $this->getParamValue('Subject', '');
			$sHtml = (string) $this->getParamValue('Html', '');

			$sFileName = $sSubject.'.pdf';
			$sMimeType = 'application/pdf';

			$sSavedName = 'pdf-'.$oAccount->IdAccount.'-'.md5($sFileName.microtime(true)).'.pdf';
			
			include_once PSEVEN_APP_ROOT_PATH.'libraries/other/CssToInlineStyles.php';

			$oCssToInlineStyles = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($sHtml);
			$oCssToInlineStyles->setEncoding('utf-8');
			$oCssToInlineStyles->setUseInlineStylesBlock(true);

			$sExec = \CApi::DataPath().'/system/wkhtmltopdf/linux/wkhtmltopdf';
			if (!file_exists($sExec))
			{
				$sExec = \CApi::DataPath().'/system/wkhtmltopdf/win/wkhtmltopdf.exe';
				if (!file_exists($sExec))
				{
					$sExec = '';
				}
			}

			if (0 < strlen($sExec))
			{
				$oSnappy = new \Knp\Snappy\Pdf($sExec);
				$oSnappy->setOption('quiet', true);
				$oSnappy->setOption('disable-javascript', true);

				$oSnappy->generateFromHtml($oCssToInlineStyles->convert(),
					$this->ApiFileCache()->generateFullFilePath($oAccount, $sSavedName), array(), true);

				return $this->DefaultResponse($oAccount, __FUNCTION__, array(
					'Name' => $sFileName,
					'TempName' => $sSavedName,
					'MimeType' => $sMimeType,
					'Size' =>  (int) $this->ApiFileCache()->fileSize($oAccount, $sSavedName),
					'Hash' => \CApi::EncodeKeyValues(array(
						'TempFile' => true,
						'AccountID' => $oAccount->IdAccount,
						'Name' => $sFileName,
						'TempName' => $sSavedName
					))
				));
			}
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxEmailSetSafety()
	{
		$sEmail = (string) $this->getParamValue('Email', '');
		if (0 === strlen(trim($sEmail)))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiUsers->setSafetySender($oAccount->IdUser, $sEmail);

		return $this->DefaultResponse($oAccount, __FUNCTION__, true);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageGet()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$sUid = trim((string) $this->getParamValue('Uid', ''));
		$sRfc822SubMimeIndex = trim((string) $this->getParamValue('Rfc822MimeIndex', ''));

		$iUid = 0 < strlen($sUid) && is_numeric($sUid) ? (int) $sUid : 0;

		if (0 === strlen(trim($sFolderFullNameRaw)) || 0 >= $iUid)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$oMessage = $this->oApiMail->getMessage($oAccount, $sFolderFullNameRaw, $iUid, $sRfc822SubMimeIndex, true, true, 600000);
		if (!($oMessage instanceof \CApiMailMessage))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CanNotGetMessage);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oMessage);
	}

	/**
	 * @return array
	 */
	public function AjaxMessagesGetBodies()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$aUids = $this->getParamValue('Uids', null);

		if (0 === strlen(trim($sFolderFullNameRaw)) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$aList = array();
		foreach ($aUids as $iUid)
		{
			if (is_numeric($iUid))
			{
				$oMessage = $this->oApiMail->getMessage($oAccount, $sFolderFullNameRaw, (int) $iUid, '', true, true, 600000);
				if ($oMessage instanceof \CApiMailMessage)
				{
					$aList[] = $oMessage;
				}

				unset($oMessage);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aList);
	}

	/**
	 * @return array
	 */
	public function AjaxSystemUpdateLanguageOnLogin()
	{
		setcookie('aft-cache-ctrl', '', time() - 3600);
		$bResult = false;
		
		$sLanguage = (string) $this->getParamValue('Language', '');
		if (!empty($sLanguage))
		{
			$oApiIntegrator = \CApi::Manager('integrator');
			if ($oApiIntegrator)
			{
				$oApiIntegrator->setLoginLanguage($sLanguage);
				$bResult = true;
			}
		}
		
		return $this->DefaultResponse(null, __FUNCTION__, $bResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountSignatureGet()
	{
		$oAccount = $this->getAccountFromParam();
		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'Type' => $oAccount->SignatureType,
			'Options' => $oAccount->SignatureOptions,
			'Signature' => $oAccount->Signature
		));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountSignatureUpdate()
	{
		$oAccount = $this->getAccountFromParam();

		$oAccount->Signature = (string) $this->oHttp->GetPost('Signature', $oAccount->Signature);
		$oAccount->SignatureType = (string) $this->oHttp->GetPost('Type', $oAccount->SignatureType);
		$oAccount->SignatureOptions = (string) $this->oHttp->GetPost('Options', $oAccount->SignatureOptions);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->updateAccount($oAccount));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountIdentityLoyalUpdate()
	{
		$oAccount = $this->getAccountFromParam();

		$oAccount->FriendlyName = (string) $this->oHttp->GetPost('FriendlyName', $oAccount->FriendlyName);
		$oAccount->Signature = (string) $this->oHttp->GetPost('Signature', $oAccount->Signature);
		$oAccount->SignatureType = (string) $this->oHttp->GetPost('Type', $oAccount->SignatureType);
		$oAccount->SignatureOptions = (string) $this->oHttp->GetPost('Options', $oAccount->SignatureOptions);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->updateAccount($oAccount, ('1' === (string) $this->oHttp->GetPost('Default', '0'))));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountDelete()
	{
		$bResult = false;
		$oAccount = $this->getDefaultAccountFromParam();

		$iAccountIDToDelete = (int) $this->oHttp->GetPost('AccountIDToDelete', 0);
		if (0 < $iAccountIDToDelete)
		{
			$oAccountToDelete = null;
			if ($oAccount->IdAccount === $iAccountIDToDelete)
			{
				$oAccountToDelete = $oAccount;
			}
			else
			{
				$oAccountToDelete = $this->oApiUsers->getAccountById($iAccountIDToDelete);
			}

			if ($oAccountToDelete instanceof \CAccount &&
				$oAccountToDelete->IdUser === $oAccount->IdUser &&
				!$oAccountToDelete->IsInternal &&
				((0 < $oAccount->IdDomain && $oAccount->Domain->AllowUsersChangeEmailSettings) || 
						!$oAccountToDelete->IsDefaultAccount || 
						0 === $oAccount->IdDomain || 
						-1 === $oAccount->IdDomain)
			)
			{
				$bResult = $this->oApiUsers->deleteAccount($oAccountToDelete);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
	}

	/**
	 * @param bool $bIsUpdate
	 * @param \CAccount $oAccount
	 */
	private function populateAccountFromHttpPost($bIsUpdate, &$oAccount)
	{
		if ($bIsUpdate && $oAccount->IsDefaultAccount && !$oAccount->Domain->AllowUsersChangeEmailSettings)
		{
			$oAccount->FriendlyName = (string) $this->oHttp->GetPost('FriendlyName', $oAccount->FriendlyName);
		}
		else
		{
			$oAccount->FriendlyName = (string) $this->oHttp->GetPost('FriendlyName', $oAccount->FriendlyName);

			if (!$oAccount->IsInternal)
			{
				$oAccount->IncomingMailPort = (int) $this->oHttp->GetPost('IncomingMailPort');
				$oAccount->OutgoingMailPort = (int) $this->oHttp->GetPost('OutgoingMailPort');
				$oAccount->OutgoingMailAuth = ('2' === (string) $this->oHttp->GetPost('OutgoingMailAuth', '2'))
					? \ESMTPAuthType::AuthCurrentUser : \ESMTPAuthType::NoAuth;

				$oAccount->IncomingMailServer = (string) $this->oHttp->GetPost('IncomingMailServer', '');
				$oAccount->IncomingMailLogin = (string) $this->oHttp->GetPost('IncomingMailLogin', '');

				$oAccount->OutgoingMailServer = (string) $this->oHttp->GetPost('OutgoingMailServer', '');

				$sIncomingMailPassword = (string) $this->oHttp->GetPost('IncomingMailPassword', '');
				$sIncomingMailPassword = trim($sIncomingMailPassword);
				if (API_DUMMY !== $sIncomingMailPassword && !empty($sIncomingMailPassword))
				{
					$oAccount->IncomingMailPassword = $sIncomingMailPassword;
				}
				if (!$bIsUpdate)
				{
					$oAccount->IncomingMailProtocol = \EMailProtocol::IMAP4;

					$oAccount->OutgoingMailLogin = (string) $this->oHttp->GetPost('OutgoingMailLogin', '');
					$sOutgoingMailPassword = (string) $this->oHttp->GetPost('OutgoingMailPassword', '');
					if (API_DUMMY !== $sOutgoingMailPassword)
					{
						$oAccount->OutgoingMailPassword = $sOutgoingMailPassword;
					}
				}
				
				$oAccount->IncomingMailUseSSL = '1' === (string) $this->oHttp->GetPost('IncomingMailSsl', '0');
				$oAccount->OutgoingMailUseSSL = '1' === (string) $this->oHttp->GetPost('OutgoingMailSsl', '0');
			}

			if (!$bIsUpdate)
			{
				$oAccount->Email = (string) $this->oHttp->GetPost('Email', '');
			}
		}
	}

	/**
	 * @return array
	 */
	public function AjaxAccountUpdatePassword()
	{
		$bResult = false;
		$oAccount = $this->getAccountFromParam();
		$sResetPasswordHash = $this->getParamValue('Hash', '');
		
		if (!empty($sResetPasswordHash))
		{
			if ($sResetPasswordHash !== $oAccount->User->PasswordResetHash)
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CanNotChangePassword);
			}
			else
			{
				$oAccount->User->PasswordResetHash = '';
			}
		}

		$sCurrentIncomingMailPassword = (string) $this->oHttp->GetPost('CurrentIncomingMailPassword', '');
		$sNewIncomingMailPassword = (string) $this->oHttp->GetPost('NewIncomingMailPassword', '');

		$bAllowChangePassword = $oAccount->isExtensionEnabled(\CAccount::ChangePasswordExtension) || !$oAccount->IsPasswordSpecified || !$oAccount->AllowMail;

		\CApi::Plugin()->RunHook('account-update-password', array(&$bAllowChangePassword));

		if ($bAllowChangePassword && 0 < strlen($sNewIncomingMailPassword) &&
			($sCurrentIncomingMailPassword == $oAccount->IncomingMailPassword || !$oAccount->IsPasswordSpecified))
		{
			$oAccount->PreviousMailPassword = $oAccount->IncomingMailPassword;
			$oAccount->IncomingMailPassword = $sNewIncomingMailPassword;
			$oAccount->IsPasswordSpecified = true;

			try
			{
				$bResult = $this->oApiUsers->updateAccount($oAccount);
			}
			catch (\Exception $oException)
			{
				if ($oException && $oException instanceof \CApiErrorCodes &&
					\CApiErrorCodes::UserManager_AccountOldPasswordNotCorrect === $oException->getCode())
				{
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AccountOldPasswordNotCorrect, $oException);
				}
				
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CanNotChangePassword, $oException);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountCreate()
	{
		$mResult = false;
		$oNewAccount = null;
		$oAccount = $this->getDefaultAccountFromParam();

		$oApiDomains = \CApi::Manager('domains');
		$oDomain = $oApiDomains->getDefaultDomain();
		if ($oDomain)
		{
			$oNewAccount = new \CAccount($oDomain);
			$oNewAccount->IdUser = $oAccount->IdUser;
			$oNewAccount->IsDefaultAccount = false;

			$this->populateAccountFromHttpPost(false, $oNewAccount);
			
			if ($this->oApiUsers->createAccount($oNewAccount))
			{
				$mResult = true;
			}
			else
			{
				$iClientErrorCode = \ProjectCore\Notifications::CanNotCreateAccount;
				$oException = $this->oApiUsers->GetLastException();
				if ($oException)
				{
					switch ($oException->getCode())
					{
						case \Errs::WebMailManager_AccountDisabled:
						case \Errs::UserManager_AccountAuthenticationFailed:
						case \Errs::WebMailManager_AccountAuthentication:
						case \Errs::WebMailManager_NewUserRegistrationDisabled:
						case \Errs::WebMailManager_AccountWebmailDisabled:
							$iClientErrorCode = \ProjectCore\Notifications::AuthError;
							break;
						case \Errs::UserManager_AccountConnectToMailServerFailed:
						case \Errs::WebMailManager_AccountConnectToMailServerFailed:
							$iClientErrorCode = \ProjectCore\Notifications::MailServerError;
							break;
						case \Errs::UserManager_LicenseKeyInvalid:
						case \Errs::UserManager_AccountCreateUserLimitReached:
						case \Errs::UserManager_LicenseKeyIsOutdated:
							$iClientErrorCode = \ProjectCore\Notifications::LicenseProblem;
							break;
						case \Errs::Db_ExceptionError:
							$iClientErrorCode = \ProjectCore\Notifications::DataBaseError;
							break;
					}
				}

				return $this->FalseResponse($oAccount, __FUNCTION__, $iClientErrorCode);
			}
		}
		
		if ($mResult && $oNewAccount)
		{
			$aExtensions = $oAccount->getExtensionList();
			$mResult = array(
				'IdAccount' => $oNewAccount->IdAccount,
				'Extensions' => $aExtensions
			);
		}
		else
		{
			$mResult = false;
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxAccountConfigureMail()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();

		$this->populateAccountFromHttpPost(true, $oAccount);

		$oAccount->AllowMail = true;
		
		$iConnectTimeOut = \CApi::GetConf('socket.connect-timeout', 10);
		$iSocketTimeOut = \CApi::GetConf('socket.get-timeout', 20);

		\CApi::Plugin()->RunHook('webmail-imap-update-socket-timeouts',
			array(&$iConnectTimeOut, &$iSocketTimeOut));

		$aConnectErrors = array(false, false);
		$bConnectValid = false;
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
		
		if ($bConnectValid)
		{
			if ($this->oApiUsers->updateAccount($oAccount))
			{
				$mResult = true;
			}
			else
			{
				$iClientErrorCode = \ProjectCore\Notifications::CanNotCreateAccount;
				$oException = $this->oApiUsers->GetLastException();
				if ($oException)
				{
					switch ($oException->getCode())
					{
						case \Errs::WebMailManager_AccountDisabled:
						case \Errs::UserManager_AccountAuthenticationFailed:
						case \Errs::WebMailManager_AccountAuthentication:
						case \Errs::WebMailManager_NewUserRegistrationDisabled:
						case \Errs::WebMailManager_AccountWebmailDisabled:
							$iClientErrorCode = \ProjectCore\Notifications::AuthError;
							break;
						case \Errs::UserManager_AccountConnectToMailServerFailed:
						case \Errs::WebMailManager_AccountConnectToMailServerFailed:
							$iClientErrorCode = \ProjectCore\Notifications::MailServerError;
							break;
						case \Errs::UserManager_LicenseKeyInvalid:
						case \Errs::UserManager_AccountCreateUserLimitReached:
						case \Errs::UserManager_LicenseKeyIsOutdated:
							$iClientErrorCode = \ProjectCore\Notifications::LicenseProblem;
							break;
						case \Errs::Db_ExceptionError:
							$iClientErrorCode = \ProjectCore\Notifications::DataBaseError;
							break;
					}
				}
				return $this->FalseResponse($oAccount, __FUNCTION__, $iClientErrorCode);
			}
		}
		else
		{
			if ($aConnectErrors[0])
			{
				throw new \CApiManagerException(\Errs::UserManager_AccountAuthenticationFailed);
			}
			else
			{
				throw new \CApiManagerException(\Errs::UserManager_AccountConnectToMailServerFailed);
			}
		}
					
		if ($mResult && $oAccount)
		{
			$aExtensions = $oAccount->getExtensionList();
			$mResult = array(
				'IdAccount' => $oAccount->IdAccount,
				'Extensions' => $aExtensions
			);
		}
		else
		{
			$mResult = false;
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);		
	}	

	/**
	 * @return array
	 */
	public function AjaxAccountResetPassword()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		$sUrlHash = $this->getParamValue('UrlHash', '');
		
		$oTenant = null;
		if ($oAccount->Domain->IdTenant > 0)
		{
			$oTenant = $this->oApiTenants->getTenantById($oAccount->Domain->IdTenant);
		}
		else
		{
			$oTenant = $this->oApiTenants->getDefaultGlobalTenant();
		}
		
		if ($oTenant)
		{
			$oNotificationAccount = $this->oApiUsers->GetAccountByEmail($oTenant->InviteNotificationEmailAccount);
			if ($oNotificationAccount)
			{
				$sPasswordResetUrl = rtrim(\api_Utils::GetAppUrl(), '/');
				$sPasswordResetHash = \md5(\time().\rand(1000, 9999).\CApi::$sSalt);
				$oAccount->User->PasswordResetHash = $sPasswordResetHash;
				$this->oApiUsers->updateAccount($oAccount);
				$sSubject = \CApi::ClientI18N('ACCOUNT_PASSWORD_RESET/SUBJECT', $oAccount, array('SITE_NAME' => $oAccount->Domain->SiteName));
				$sBody = \CApi::ClientI18N('ACCOUNT_PASSWORD_RESET/BODY', $oAccount,
					array(
						'SITE_NAME' => $oAccount->Domain->SiteName,
						'PASSWORD_RESET_URL' => $sPasswordResetUrl . '/?reset-pass='.$sPasswordResetHash.'#'.$sUrlHash,
						'EMAIL' => $oAccount->Email
					)
				);

				$oMessage = \MailSo\Mime\Message::NewInstance();
				$oMessage->RegenerateMessageId();
				$oMessage->DoesNotCreateEmptyTextPart();

				$sXMailer = \CApi::GetConf('webmail.xmailer-value', '');
				if (0 < strlen($sXMailer))
				{
					$oMessage->SetXMailer($sXMailer);
				}

				$oMessage
					->SetFrom(\MailSo\Mime\Email::NewInstance($oTenant->InviteNotificationEmailAccount))
					->SetSubject($sSubject)
					->AddText($sBody, true)
				;

				$oToEmails = \MailSo\Mime\EmailCollection::NewInstance($oAccount->Email);
				if ($oToEmails && $oToEmails->Count())
				{
					$oMessage->SetTo($oToEmails);
				}

				if ($oMessage)
				{
					try
					{
						$mResult = $this->oApiMail->sendMessage($oNotificationAccount, $oMessage);
					}
					catch (\CApiManagerException $oException)
					{
						throw $oException;
					}
				}
			}
		}
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);		
	}
	
	
	/**
	 * @return array
	 */
	public function AjaxAccountSettingsGet()
	{
		$oAccount = $this->getAccountFromParam();
		$aResult = array();

		$aResult['IsLinked'] = 0 < $oAccount->IdDomain;
		$aResult['IsInternal'] = (bool) $oAccount->IsInternal;
		$aResult['IsDefault'] = (bool) $oAccount->IsDefaultAccount;

		$aResult['FriendlyName'] = $oAccount->FriendlyName;
		$aResult['Email'] = $oAccount->Email;

		$aResult['IncomingMailServer'] = $oAccount->IncomingMailServer;
		$aResult['IncomingMailPort'] = $oAccount->IncomingMailPort;
		$aResult['IncomingMailLogin'] = $oAccount->IncomingMailLogin;

		$aResult['OutgoingMailServer'] = $oAccount->OutgoingMailServer;
		$aResult['OutgoingMailPort'] = $oAccount->OutgoingMailPort;
		$aResult['OutgoingMailLogin'] = $oAccount->OutgoingMailLogin;
		$aResult['OutgoingMailAuth'] = $oAccount->OutgoingMailAuth;

		$aResult['IncomingMailSsl'] = $oAccount->IncomingMailUseSSL;
		$aResult['OutgoingMailSsl'] = $oAccount->OutgoingMailUseSSL;

		$aResult['Extensions'] = array();

		// extensions
		if ($oAccount->isExtensionEnabled(\CAccount::IgnoreSubscribeStatus) &&
			!$oAccount->isExtensionEnabled(\CAccount::DisableManageSubscribe))
		{
			$oAccount->enableExtension(\CAccount::DisableManageSubscribe);
		}

		$aExtensions = $oAccount->getExtensionList();
		foreach ($aExtensions as $sExtensionName)
		{
			if ($oAccount->isExtensionEnabled($sExtensionName))
			{
				$aResult['Extensions'][] = $sExtensionName;
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResult);
	}

	/**
	 * @return array
	 */
	public function AjaxSystemGetAppData()
	{
		$oApiIntegratorManager = \CApi::Manager('integrator');
		$sAuthToken = (string) $this->getParamValue('AuthToken', '');
		return $this->DefaultResponse(null, __FUNCTION__, 
				$oApiIntegratorManager ? $oApiIntegratorManager->appData(false, null, '', '', '', $sAuthToken) : false);
	}
	
	/**
	 * @return array
	 */
	public function AjaxSystemSetMobile()
	{
		$oApiIntegratorManager = \CApi::Manager('integrator');
		return $this->DefaultResponse(null, __FUNCTION__, $oApiIntegratorManager ?
			$oApiIntegratorManager->setMobile('1' === (string) $this->getParamValue('Mobile', '0')) : false);
	}

	/**
	 * @return array
	 */
	public function AjaxUserSettingsGetSync()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		$aResult = array(
			'Mobile' => $this->mobileSyncSettings($oAccount),
			'Outlook' => $this->outlookSyncSettings($oAccount)
		);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountSettingsUpdate()
	{
		setcookie('aft-cache-ctrl', '', time() - 3600);
		$oAccount = $this->getAccountFromParam();

		$this->populateAccountFromHttpPost(true, $oAccount);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->updateAccount($oAccount));
	}

	/**
	 * @return array
	 */
	public function AjaxUserSettingsUpdate()
	{
		setcookie('aft-cache-ctrl', '', time() - 3600);
		$oAccount = $this->getAccountFromParam();

		$iMailsPerPage = (int) $this->oHttp->GetPost('MailsPerPage', $oAccount->User->MailsPerPage);
		if ($iMailsPerPage < 1)
		{
			$iMailsPerPage = 1;
		}

		$iContactsPerPage = (int) $this->oHttp->GetPost('ContactsPerPage', $oAccount->User->ContactsPerPage);
		if ($iContactsPerPage < 1)
		{
			$iContactsPerPage = 1;
		}

		$iAutoCheckMailInterval = (int) $this->oHttp->GetPost('AutoCheckMailInterval', $oAccount->User->AutoCheckMailInterval);
		if (!in_array($iAutoCheckMailInterval, array(0, 1, 3, 5, 10, 15, 20, 30)))
		{
			$iAutoCheckMailInterval = 0;
		}

		$iLayout = (int) $this->oHttp->GetPost('Layout', $oAccount->User->Layout);
		$iDefaultEditor = (int) $this->oHttp->GetPost('DefaultEditor', $oAccount->User->DefaultEditor);
		$bUseThreads = '1' === (string) $this->oHttp->GetPost('UseThreads', $oAccount->User->UseThreads ? '1' : '0');
		$bSaveRepliedMessagesToCurrentFolder = '1' === (string) $this->oHttp->GetPost('SaveRepliedMessagesToCurrentFolder', $oAccount->User->SaveRepliedMessagesToCurrentFolder ? '1' : '0');
		$bDesktopNotifications = '1' === (string) $this->oHttp->GetPost('DesktopNotifications', $oAccount->User->DesktopNotifications ? '1' : '0');
		$bAllowChangeInputDirection = '1' === (string) $this->oHttp->GetPost('AllowChangeInputDirection', $oAccount->User->AllowChangeInputDirection ? '1' : '0');
		
		$bFilesEnable = '1' === (string) $this->oHttp->GetPost('FilesEnable', $oAccount->User->FilesEnable ? '1' : '0');

		$sTheme = (string) $this->oHttp->GetPost('DefaultTheme', $oAccount->User->DefaultSkin);
//		$sTheme = $this->validateTheme($sTheme);

		$sLang = (string) $this->oHttp->GetPost('DefaultLanguage', $oAccount->User->DefaultLanguage);
//		$sLang = $this->validateLang($sLang);

		$sDateFormat = (string) $this->oHttp->GetPost('DefaultDateFormat', $oAccount->User->DefaultDateFormat);
		$iTimeFormat = (int) $this->oHttp->GetPost('DefaultTimeFormat', $oAccount->User->DefaultTimeFormat);

		$sEmailNotification = (string) $this->oHttp->GetPost('EmailNotification', $oAccount->User->EmailNotification);

		$oAccount->User->MailsPerPage = $iMailsPerPage;
		$oAccount->User->ContactsPerPage = $iContactsPerPage;
		$oAccount->User->Layout = $iLayout;
		$oAccount->User->DefaultSkin = $sTheme;
		$oAccount->User->DefaultEditor = $iDefaultEditor;
		$oAccount->User->DefaultLanguage = $sLang;
		$oAccount->User->DefaultDateFormat = $sDateFormat;
		$oAccount->User->DefaultTimeFormat = $iTimeFormat;
		$oAccount->User->AutoCheckMailInterval = $iAutoCheckMailInterval;
		$oAccount->User->UseThreads = $bUseThreads;
		$oAccount->User->SaveRepliedMessagesToCurrentFolder = $bSaveRepliedMessagesToCurrentFolder;
		$oAccount->User->DesktopNotifications = $bDesktopNotifications;
		$oAccount->User->AllowChangeInputDirection = $bAllowChangeInputDirection;

		$oAccount->User->EnableOpenPgp = '1' === (string) $this->oHttp->GetPost('EnableOpenPgp', $oAccount->User->EnableOpenPgp ? '1' : '0');
		$oAccount->User->AllowAutosaveInDrafts = '1' === (string) $this->oHttp->GetPost('AllowAutosaveInDrafts', $oAccount->User->AllowAutosaveInDrafts ? '1' : '0');
		$oAccount->User->AutosignOutgoingEmails = '1' === (string) $this->oHttp->GetPost('AutosignOutgoingEmails', $oAccount->User->AutosignOutgoingEmails ? '1' : '0');
		$oAccount->User->FilesEnable = $bFilesEnable;
		
		$oAccount->User->EmailNotification = $sEmailNotification;

		// calendar
		$oCalUser = $this->oApiUsers->getOrCreateCalUser($oAccount->IdUser);
		if ($oCalUser)
		{
			$oCalUser->ShowWeekEnds = (bool) $this->oHttp->GetPost('ShowWeekEnds', $oCalUser->ShowWeekEnds);
			$oCalUser->ShowWorkDay = (bool) $this->oHttp->GetPost('ShowWorkDay', $oCalUser->ShowWorkDay);
			$oCalUser->WorkDayStarts = (int) $this->oHttp->GetPost('WorkDayStarts', $oCalUser->WorkDayStarts);
			$oCalUser->WorkDayEnds = (int) $this->oHttp->GetPost('WorkDayEnds', $oCalUser->WorkDayEnds);
			$oCalUser->WeekStartsOn = (int) $this->oHttp->GetPost('WeekStartsOn', $oCalUser->WeekStartsOn);
			$oCalUser->DefaultTab = (int) $this->oHttp->GetPost('DefaultTab', $oCalUser->DefaultTab);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->updateAccount($oAccount) &&
			$oCalUser && $this->oApiUsers->updateCalUser($oCalUser));
	}

	/**
	 * @return array
	 */
	public function AjaxHelpdeskUserSettingsUpdate()
	{
		/*$oAccount = $this->getAccountFromParam();
		$oHelpdeskUser = $this->GetHelpdeskAccountFromMainAccount($oAccount);
		
		$oAccount->User->AllowHelpdeskNotifications =  (bool) $this->getParamValue('AllowHelpdeskNotifications', $oAccount->User->AllowHelpdeskNotifications);
		$oHelpdeskUser->Signature = trim((string) $this->getParamValue('Signature', $oHelpdeskUser->Signature));
		$oHelpdeskUser->SignatureEnable = (bool) $this->getParamValue('SignatureEnable', $oHelpdeskUser->SignatureEnable);

		$bResult = $this->oApiUsers->UpdateAccount($oAccount);
		if ($bResult)
		{
			$bResult = $this->ApiHelpdesk()->updateUser($oHelpdeskUser);
		}
		else
		{
			$this->ApiHelpdesk()->updateUser($oHelpdeskUser);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);*/

		$oAccount = $this->getAccountFromParam();

		$oAccount->User->AllowHelpdeskNotifications =  (bool) $this->getParamValue('AllowHelpdeskNotifications', $oAccount->User->AllowHelpdeskNotifications);
		$oAccount->User->HelpdeskSignature = trim((string) $this->getParamValue('HelpdeskSignature', $oAccount->User->HelpdeskSignature));
		$oAccount->User->HelpdeskSignatureEnable = (bool) $this->getParamValue('HelpdeskSignatureEnable', $oAccount->User->HelpdeskSignatureEnable);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->UpdateAccount($oAccount));
	}

	/**
	 * @return array
	 */
	/*public function AjaxHelpdeskUserSettings()
	{
		$oAccount = $this->getAccountFromParam();
		$oHelpdeskUser = $this->GetHelpdeskAccountFromMainAccount($oAccount);

		$aResult = array(
			'Signature' => $oHelpdeskUser->Signature,
			'SignatureEnable' => $oHelpdeskUser->SignatureEnable
		);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResult);
	}*/

	/**
	 * @return array
	 */
	public function AjaxSystemLogin()
	{
		setcookie('aft-cache-ctrl', '', time() - 3600);
		$sEmail = trim((string) $this->getParamValue('Email', ''));
		$sIncLogin = (string) $this->getParamValue('IncLogin', '');
		$sIncPassword = (string) $this->getParamValue('IncPassword', '');
		$sLanguage = (string) $this->getParamValue('Language', '');

		$bSignMe = '1' === (string) $this->getParamValue('SignMe', '0');

		try
		{
			\CApi::Plugin()->RunHook('webmail-login-custom-data', array($this->getParamValue('CustomRequestData', null)));
		}
		catch (\Exception $oException)
		{
			\CApi::LogEvent(\EEvents::LoginFailed, $sEmail);
			throw $oException;
		}

		$oSettings =& \CApi::GetSettings();
		$sAtDomain = trim($oSettings->GetConf('WebMail/LoginAtDomainValue'));
		if ((\ELoginFormType::Email === (int) $oSettings->GetConf('WebMail/LoginFormType') || \ELoginFormType::Both === (int) $oSettings->GetConf('WebMail/LoginFormType')) && 0 === strlen($sAtDomain) && 0 < strlen($sEmail) && !\MailSo\Base\Validator::EmailString($sEmail))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AuthError);
		}

		if (\ELoginFormType::Login === (int) $oSettings->GetConf('WebMail/LoginFormType') && 0 < strlen($sAtDomain))
		{
			$sEmail = \api_Utils::GetAccountNameFromEmail($sIncLogin).'@'.$sAtDomain;
			$sIncLogin = $sEmail;
		}

		if (0 === strlen($sIncPassword) || 0 === strlen($sEmail.$sIncLogin))
		{
			\CApi::LogEvent(\EEvents::LoginFailed, $sEmail);
			
			\CApi::Log($sEmail, \ELogLevel::Full, 'a-');
			\CApi::Log($sIncLogin, \ELogLevel::Full, 'a-');
			\CApi::Log($sIncPassword, \ELogLevel::Full, 'a-');
			
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		try
		{
			if (0 === strlen($sLanguage))
			{
				$sLanguage = $this->oApiIntegrator->getLoginLanguage();
			}

			$oAccount = $this->oApiIntegrator->loginToAccount(
				$sEmail, $sIncPassword, $sIncLogin, $sLanguage
			);
		}
		catch (\Exception $oException)
		{
			$iErrorCode = \ProjectCore\Notifications::UnknownError;
			if ($oException instanceof \CApiManagerException)
			{
				switch ($oException->getCode())
				{
					case \Errs::WebMailManager_AccountDisabled:
					case \Errs::WebMailManager_AccountWebmailDisabled:
						$iErrorCode = \ProjectCore\Notifications::AuthError;
						break;
					case \Errs::UserManager_AccountAuthenticationFailed:
					case \Errs::WebMailManager_AccountAuthentication:
					case \Errs::WebMailManager_NewUserRegistrationDisabled:
					case \Errs::WebMailManager_AccountCreateOnLogin:
					case \Errs::Mail_AccountAuthentication:
					case \Errs::Mail_AccountLoginFailed:
						$iErrorCode = \ProjectCore\Notifications::AuthError;
						break;
					case \Errs::UserManager_AccountConnectToMailServerFailed:
					case \Errs::WebMailManager_AccountConnectToMailServerFailed:
					case \Errs::Mail_AccountConnectToMailServerFailed:
						$iErrorCode = \ProjectCore\Notifications::MailServerError;
						break;
					case \Errs::UserManager_LicenseKeyInvalid:
					case \Errs::UserManager_AccountCreateUserLimitReached:
					case \Errs::UserManager_LicenseKeyIsOutdated:
					case \Errs::TenantsManager_AccountCreateUserLimitReached:
						$iErrorCode = \ProjectCore\Notifications::LicenseProblem;
						break;
					case \Errs::Db_ExceptionError:
						$iErrorCode = \ProjectCore\Notifications::DataBaseError;
						break;
				}
			}

			\CApi::LogEvent(\EEvents::LoginFailed, $sEmail);
			throw new \ProjectCore\Exceptions\ClientException($iErrorCode, $oException,
				$oException instanceof \CApiBaseException ? $oException->GetPreviousMessage() :
				($oException ? $oException->getMessage() : ''));
		}

		if ($oAccount instanceof \CAccount)
		{
			$sAuthToken = '';
			$bSetAccountAsLoggedIn = true;
			\CApi::Plugin()->RunHook('api-integrator-set-account-as-logged-in', array(&$bSetAccountAsLoggedIn));

			if ($bSetAccountAsLoggedIn)
			{
				\CApi::LogEvent(\EEvents::LoginSuccess, $oAccount);
				$sAuthToken = $this->oApiIntegrator->setAccountAsLoggedIn($oAccount, $bSignMe);
			}
			
			return $this->DefaultResponse($oAccount, __FUNCTION__, array('AuthToken' => $sAuthToken));
		}

		\CApi::LogEvent(\EEvents::LoginFailed, $oAccount);
		throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AuthError);
	}
	
	/**
	 * @return array
	 */
	public function AjaxAccountRegister()
	{
		$sName = trim((string) $this->getParamValue('Name', ''));
		$sEmail = trim((string) $this->getParamValue('Email', ''));
		$sPassword =  trim((string) $this->getParamValue('Password', ''));

		$sQuestion =  trim((string) $this->getParamValue('Question', ''));
		$sAnswer =  trim((string) $this->getParamValue('Answer', ''));

		\CApi::Plugin()->RunHook('webmail-register-custom-data', array($this->getParamValue('CustomRequestData', null)));

		$oSettings =& \CApi::GetSettings();
		if (!$oSettings || !$oSettings->GetConf('Common/AllowRegistration'))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		if (0 === strlen($sPassword) || 0 === strlen($sEmail) || 0 === strlen($sQuestion) || 0 === strlen($sAnswer))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->oApiUsers->getAccountByEmail($sEmail);
		if ($oAccount instanceof \CAccount)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter); // TODO
		}

		try
		{
			$oAccount = $this->oApiWebMail->createAccount($sEmail, $sPassword, '', array(
				'FriendlyName' => $sName,
				'Question1' => $sQuestion,
				'Answer1' => $sAnswer
			), true);
			
			if ($oAccount instanceof \CAccount)
			{
				\CApi::Plugin()->RunHook('api-integrator-login-success-post-create-account-call', array(&$oAccount));
			}
			else
			{
				$oException = $this->oApiWebMail->GetLastException();

				\CApi::Plugin()->RunHook('api-integrator-login-error-post-create-account-call', array(&$oException));

				throw (is_object($oException))
					? $oException
					: new \CApiManagerException(Errs::WebMailManager_AccountCreateOnLogin);
			}
		}
		catch (\Exception $oException)
		{
			$iErrorCode = \ProjectCore\Notifications::UnknownError;
			if ($oException instanceof \CApiManagerException)
			{
				switch ($oException->getCode())
				{
					case \Errs::WebMailManager_AccountDisabled:
					case \Errs::WebMailManager_AccountWebmailDisabled:
						$iErrorCode = \ProjectCore\Notifications::AuthError;
						break;
					case \Errs::UserManager_AccountAuthenticationFailed:
					case \Errs::WebMailManager_AccountAuthentication:
					case \Errs::WebMailManager_NewUserRegistrationDisabled:
					case \Errs::WebMailManager_AccountCreateOnLogin:
					case \Errs::Mail_AccountAuthentication:
					case \Errs::Mail_AccountLoginFailed:
						$iErrorCode = \ProjectCore\Notifications::AuthError;
						break;
					case \Errs::UserManager_AccountConnectToMailServerFailed:
					case \Errs::WebMailManager_AccountConnectToMailServerFailed:
					case \Errs::Mail_AccountConnectToMailServerFailed:
						$iErrorCode = \ProjectCore\Notifications::MailServerError;
						break;
					case \Errs::UserManager_LicenseKeyInvalid:
					case \Errs::UserManager_AccountCreateUserLimitReached:
					case \Errs::UserManager_LicenseKeyIsOutdated:
					case \Errs::TenantsManager_AccountCreateUserLimitReached:
						$iErrorCode = \ProjectCore\Notifications::LicenseProblem;
						break;
					case \Errs::Db_ExceptionError:
						$iErrorCode = \ProjectCore\Notifications::DataBaseError;
						break;
				}
			}

			throw new \ProjectCore\Exceptions\ClientException($iErrorCode, $oException,
				$oException instanceof \CApiBaseException ? $oException->GetPreviousMessage() :
				($oException ? $oException->getMessage() : ''));
		}

		if ($oAccount instanceof \CAccount)
		{
			$this->oApiIntegrator->setAccountAsLoggedIn($oAccount);
			return $this->TrueResponse($oAccount, __FUNCTION__);
		}

		throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AuthError);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountGetForgotQuestion()
	{
		$sEmail = trim((string) $this->getParamValue('Email', ''));

		\CApi::Plugin()->RunHook('webmail-forgot-custom-data', array($this->getParamValue('CustomRequestData', null)));

		$oSettings =& \CApi::GetSettings();
		if (!$oSettings || !$oSettings->GetConf('Common/AllowPasswordReset') || 0 === strlen($sEmail))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->oApiUsers->getAccountByEmail($sEmail);
		if (!($oAccount instanceof \CAccount) || !$oAccount->IsInternal)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter); // TODO
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'Email' => $oAccount->Email,
			'Question' => $oAccount->User->Question1
		));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountValidateForgotQuestion()
	{
		$sEmail = trim((string) $this->getParamValue('Email', ''));
		$sQuestion =  trim((string) $this->getParamValue('Question', ''));
		$sAnswer =  trim((string) $this->getParamValue('Answer', ''));

		$oSettings =& \CApi::GetSettings();
		if (!$oSettings || !$oSettings->GetConf('Common/AllowPasswordReset') ||
			0 === strlen($sEmail) || 0 === strlen($sAnswer) || 0 === strlen($sQuestion))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->oApiUsers->getAccountByEmail($sEmail);
		if (!($oAccount instanceof \CAccount) || !$oAccount->IsInternal)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter); // TODO
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, 
			$oAccount->User->Question1 === $sQuestion && $oAccount->User->Answer1 === $sAnswer);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountChangeForgotPassword()
	{
		$sEmail = trim((string) $this->getParamValue('Email', ''));
		$sQuestion =  trim((string) $this->getParamValue('Question', ''));
		$sAnswer =  trim((string) $this->getParamValue('Answer', ''));
		$sPassword =  trim((string) $this->getParamValue('Password', ''));

		$oSettings =& \CApi::GetSettings();
		if (!$oSettings || !$oSettings->GetConf('Common/AllowPasswordReset') ||
			0 === strlen($sEmail) || 0 === strlen($sAnswer) || 0 === strlen($sQuestion) || 0 === strlen($sPassword))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->oApiUsers->getAccountByEmail($sEmail);
		if (!($oAccount instanceof \CAccount) || !$oAccount->IsInternal ||
			$oAccount->User->Question1 !== $sQuestion || $oAccount->User->Answer1 !== $sAnswer)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter); // TODO
		}

		$oAccount->PreviousMailPassword = $oAccount->IncomingMailPassword;
		$oAccount->IncomingMailPassword = $sPassword;

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->updateAccount($oAccount));
	}

	/**
	 * @return array
	 */
	public function AjaxFilesUploadByLink()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		$oTenant = null;
		if ($this->oApiTenants)
		{
			$oTenant = (0 < $oAccount->IdTenant) ? $this->oApiTenants->getTenantById($oAccount->IdTenant) :
				$this->oApiTenants->getDefaultGlobalTenant();
		}

		$mResult = false;
		$rFile = null;
		
		if ($oTenant)
		{
			$aLinks = $this->getParamValue('Links', null);
			$bLinksAsIds = $this->getParamValue('LinksAsIds', false);
			$sAccessToken = $this->getParamValue('AccessToken', '');
			if (is_array($aLinks) && 0 < count($aLinks))
			{
				$mResult = array();
				foreach ($aLinks as $sLink)
				{
					$bFileSaveResult = false;
					$sTempName = '';
					$aData = array(
						'Type' => 0,
						'Size' => 0,
						'Path' => '',
						'Name' => '',
						'Hash' => ''
					);
					if ($sLink)
					{
						$iLinkType = \api_Utils::GetLinkType($sLink);
						if (\EFileStorageLinkType::GoogleDrive === $iLinkType || $bLinksAsIds)
						{
							$oSocial = $oTenant->getSocialByName('google');
							if ($oSocial)
							{
								$oInfo = \api_Utils::GetGoogleDriveFileInfo($sLink, $oSocial->SocialApiKey, $sAccessToken, $bLinksAsIds);
								if ($oInfo)
								{
									$aData['Name'] = isset($oInfo->title) ? $oInfo->title : $aData['Name'];
									$aData['Size'] = isset($oInfo->fileSize) ? $oInfo->fileSize : $aData['Size'];
									$aData['Hash'] = isset($oInfo->id) ? $oInfo->id : $aData['Hash'];
									if (isset($oInfo->downloadUrl))
									{
										$sTempName = md5('Files/Tmp/'.$aData['Type'].$aData['Path'].$aData['Name'].microtime(true).rand(1000, 9999));
										$rFile = $this->ApiFileCache()->getTempFile($oAccount, $sTempName, 'wb+');

										$aHeaders = array();
										if ($sAccessToken)
										{
											$aHeaders = array(
												'Authorization: Bearer '. $sAccessToken	
											);
										}
										$sContentType = '';
										$iCode = 0;
										$bFileSaveResult = $this->oHttp->SaveUrlToFile($oInfo->downloadUrl, $rFile, '', $sContentType, $iCode,
										null, 10, '', '', $aHeaders);
										if (is_resource($rFile))
										{
											@fclose($rFile);
										}
										$aData['Size'] = $this->ApiFileCache()->fileSize($oAccount, $sTempName);
									}
								}
							}
						}
						else/* if (\EFileStorageLinkType::DropBox === $iLinkType)*/
						{
							$aData['Name'] = urldecode(basename($sLink));
							$aData['Hash'] = $sLink;

							$sTempName = md5('Files/Tmp/'.$aData['Type'].$aData['Path'].$aData['Name'].microtime(true).rand(1000, 9999));
							$rFile = $this->ApiFileCache()->getTempFile($oAccount, $sTempName, 'wb+');
							$bFileSaveResult = $this->oHttp->SaveUrlToFile($sLink, $rFile);
							if (is_resource($rFile))
							{
								@fclose($rFile);
							}
							$aData['Size'] = $this->ApiFileCache()->fileSize($oAccount, $sTempName);
						}
					}

					if ($bFileSaveResult)
					{
						$mResult[] = array(
							'Name' => $aData['Name'],
							'TempName' => $sTempName,
							'Size' => (int) $aData['Size'],
							'MimeType' => '',
							'Hash' => $aData['Hash'],
							'MimeType' => \MailSo\Base\Utils::MimeContentType($aData['Name']),
							'NewHash' => \CApi::EncodeKeyValues(array(
								'TempFile' => true,
								'AccountID' => $oAccount->IdAccount,
								'Name' => $aData['Name'],
								'TempName' => $sTempName
								))
						);
					}
				}
			}
			else
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxFilesUpload()
	{
		$oDefaultAccount = $this->getDefaultAccountFromParam();
		$oAccount = $this->getAccountFromParam();
		$oTenant = null;
		if ($this->oApiTenants)
		{
			$oTenant = (0 < $oDefaultAccount->IdTenant) ? $this->oApiTenants->getTenantById($oDefaultAccount->IdTenant) :
				$this->oApiTenants->getDefaultGlobalTenant();
		}

		$mResult = false;
		if ($this->oApiCapability->isFilesSupported($oDefaultAccount) && $oTenant)
		{
			$aFiles = $this->getParamValue('Hashes', null);
			if (is_array($aFiles) && 0 < count($aFiles))
			{
				$mResult = array();
				foreach ($aFiles as $sHash)
				{
					$aData = \CApi::DecodeKeyValues($sHash);
					if (\is_array($aData) && 0 < \count($aData))
					{
						$oFileInfo = $this->oApiFilestorage->getFileInfo($oDefaultAccount, $aData['Type'], $aData['Path'], $aData['Name']);
						$rFile = null;
						if ($oFileInfo->IsLink)
						{
							if (\EFileStorageLinkType::GoogleDrive === $oFileInfo->LinkType)
							{
								$oSocial = $oTenant->getSocialByName('google');
								if ($oSocial)
								{
									$oInfo = \api_Utils::GetGoogleDriveFileInfo($oFileInfo->LinkUrl, $oSocial->SocialApiKey);
									$aData['Name'] = isset($oInfo->title) ? $oInfo->title : $aData['Name'];
									$aData['Size'] = isset($oInfo->fileSize) ? $oInfo->fileSize : $aData['Size'];

									if (isset($oInfo->downloadUrl))
									{
										$rFile = \MailSo\Base\ResourceRegistry::CreateMemoryResource();
										$this->oHttp->SaveUrlToFile($oInfo->downloadUrl, $rFile);
										rewind($rFile);
									}
								}
							}
							else /*if (\EFileStorageLinkType::DropBox === (int)$aFileInfo['LinkType'])*/
							{
								$rFile = \MailSo\Base\ResourceRegistry::CreateMemoryResource();
								$aData['Name'] = basename($oFileInfo->LinkUrl);
                                                                $aRemoteFileInfo = \api_Utils::GetRemoteFileInfo($oFileInfo->LinkUrl);
								$aData['Size'] = $aRemoteFileInfo['size'];

								$this->oHttp->SaveUrlToFile($oFileInfo->LinkUrl, $rFile);
								rewind($rFile);
							}
						}
						else
						{
							$rFile = $this->oApiFilestorage->getFile($oDefaultAccount, $aData['Type'], $aData['Path'], $aData['Name']);
						}
						
						$sTempName = md5('Files/Tmp/'.$aData['Type'].$aData['Path'].$aData['Name'].microtime(true).rand(1000, 9999));

						if (is_resource($rFile) && $this->ApiFileCache()->putFile($oAccount, $sTempName, $rFile))
						{
							$aItem = array(
								'Name' => $oFileInfo->Name,
								'TempName' => $sTempName,
								'Size' => (int) $aData['Size'],
								'Hash' => $sHash,
								'MimeType' => ''
							);

							$aItem['MimeType'] = \MailSo\Base\Utils::MimeContentType($aItem['Name']);
							$aItem['NewHash'] = \CApi::EncodeKeyValues(array(
								'TempFile' => true,
								'AccountID' => $oAccount->IdAccount,
								'Name' => $aItem['Name'],
								'TempName' => $sTempName
							));

							$mResult[] = $aItem;

							if (is_resource($rFile))
							{
								@fclose($rFile);
							}
						}
					}
				}
			}
			else
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxTwilioGetLogs()
	{
		$oAccount = $this->getAccountFromParam();

		$bTwilioEnable = $oAccount->User->TwilioEnable;

		$oTenant = null;
		if ($this->oApiTenants)
		{
			$oTenant = (0 < $oAccount->IdTenant) ? $this->oApiTenants->getTenantById($oAccount->IdTenant) :
				$this->oApiTenants->getDefaultGlobalTenant();
		}

		if ($oTenant && $this->oApiCapability->isTwilioSupported($oAccount) && $oTenant->isTwilioSupported() && file_exists(PSEVEN_APP_ROOT_PATH.'libraries/Services/Twilio.php'))
		{
			try
			{
				include PSEVEN_APP_ROOT_PATH.'libraries/Services/Twilio.php';

				$sStatus = (string) $this->getParamValue('Status', '');
				$sStartTime = (string) $this->getParamValue('StartTime', '');

				$sAccountSid = $oTenant->TwilioAccountSID;
				$sAuthToken = $oTenant->TwilioAuthToken;
				$sAppSid = $oTenant->TwilioAppSID;

				$sTwilioPhoneNumber = $oTenant->TwilioPhoneNumber;
				$sUserPhoneNumber = $oAccount->User->TwilioNumber;
				$aResult = array();
				$aNumbers = array();
				$aNames = array();

				$client = new \Services_Twilio($sAccountSid, $sAuthToken);

				//$sUserPhoneNumber = '7333';
				if ($sUserPhoneNumber) {
					foreach ($client->account->calls->getIterator(0, 50, array
					(
						"Status" => $sStatus,
						"StartTime>" => $sStartTime,
						"From" => "client:".$sUserPhoneNumber,
					)) as $call)
					{
						//$aResult[$call->status]["outgoing"][] = array
						$aResult[] = array
						(
							"Status" => $call->status,
							"To" => $call->to,
							"ToFormatted" => $call->to_formatted,
							"From" => $call->from,
							"FromFormatted" => $call->from_formatted,
							"StartTime" => $call->start_time,
							"EndTime" => $call->end_time,
							"Duration" => $call->duration,
							"Price" => $call->price,
							"PriceUnit" => $call->price_unit,
							"Direction" => $call->direction,
							"UserDirection" => "outgoing",
							"UserStatus" => $this->oApiTwilio->getCallSimpleStatus($call->status, "outgoing"),
							"UserPhone" => $sUserPhoneNumber,
							"UserName" => '',
							"UserDisplayName" => '',
							"UserEmail" => ''
						);

						$aNumbers[] = $call->to_formatted;
					}

					foreach ($client->account->calls->getIterator(0, 50, array
					(
						"Status" => $sStatus,
						"StartTime>" => $sStartTime,
						"To" => "client:".$sUserPhoneNumber
					)) as $call)
					{
						//$aResult[$call->status]["incoming"][] = array
						$aResult[] = array
						(
							"Status" => $call->status,
							"To" => $call->to,
							"ToFormatted" => $call->to_formatted,
							"From" => $call->from,
							"FromFormatted" => $call->from_formatted,
							"StartTime" => $call->start_time,
							"EndTime" => $call->end_time,
							"Duration" => $call->duration,
							"Price" => $call->price,
							"PriceUnit" => $call->price_unit,
							"Direction" => $call->direction,
							"UserDirection" => "incoming",
							"UserStatus" => $this->oApiTwilio->getCallSimpleStatus($call->status, "incoming"),
							"UserPhone" => $sUserPhoneNumber,
							"UserName" => '',
							"UserDisplayName" => '',
							"UserEmail" => ''

						);

						$aNumbers[] = $call->from_formatted;
					}

					$oApiVoiceManager = \CApi::Manager('voice');

					if ($aResult && $oApiVoiceManager) {

						$aNames = $oApiVoiceManager->getNamesByCallersNumbers($oAccount, $aNumbers);

						foreach ($aResult as &$aCall) {

							if ($aCall['UserDirection'] === 'outgoing')
							{
								$aCall['UserDisplayName'] = isset($aNames[$aCall['ToFormatted']]) ? $aNames[$aCall['ToFormatted']] : '';
							}
							else if ($aCall['UserDirection'] === 'incoming')
							{
								$aCall['UserDisplayName'] = isset($aNames[$aCall['FromFormatted']]) ? $aNames[$aCall['FromFormatted']] : '';
							}
						}
					}
				}
			}
			catch (\Exception $oE)
			{
				\CApi::LogException($oE);
			}
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::VoiceNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResult);
	}

	/**
	 * @return array
	 */
	public function AjaxTwilioGetToken()
	{
		$oAccount = $this->getAccountFromParam();

		$oTenant = null;
		if ($this->oApiTenants)
		{
			$oTenant = (0 < $oAccount->IdTenant) ? $this->oApiTenants->getTenantById($oAccount->IdTenant) : $this->oApiTenants->getDefaultGlobalTenant();
		}
		
		$mToken = false;
		if ($oTenant && $this->oApiCapability->isTwilioSupported($oAccount) && $oTenant->isTwilioSupported() && $oTenant->TwilioAllow && $oAccount->User->TwilioEnable && file_exists(PSEVEN_APP_ROOT_PATH.'libraries/Services/Twilio.php'))
		{
			try
			{
				include PSEVEN_APP_ROOT_PATH.'libraries/Services/Twilio.php';

				// Twilio API credentials
				$sAccountSid = $oTenant->TwilioAccountSID;
				$sAuthToken = $oTenant->TwilioAuthToken;
				// Twilio Application Sid
				$sAppSid = $oTenant->TwilioAppSID;

				$sTwilioPhoneNumber = $oTenant->TwilioPhoneNumber;
				$bUserTwilioEnable = $oAccount->User->TwilioEnable;
				$sUserPhoneNumber = $oAccount->User->TwilioNumber;
				$bUserDefaultNumber = $oAccount->User->TwilioDefaultNumber;

				$oCapability = new \Services_Twilio_Capability($sAccountSid, $sAuthToken);
				$oCapability->allowClientOutgoing($sAppSid);

				\CApi::Log('twilio_debug');
				\CApi::Log('twilio_account_sid-' . $sAccountSid);
				\CApi::Log('twilio_auth_token-' . $sAuthToken);
				\CApi::Log('twilio_app_sid-' . $sAppSid);
				\CApi::Log('twilio_enable-' . $bUserTwilioEnable ? 'true' : 'false');
				\CApi::Log('twilio_user_default_number-' . ($bUserDefaultNumber ? 'true' : 'false'));
				\CApi::Log('twilio_number-' . $sTwilioPhoneNumber);
				\CApi::Log('twilio_user_number-' . $sUserPhoneNumber);
				\CApi::Log('twilio_debug_end');

				//$oCapability->allowClientIncoming('TwilioAftId_'.$oAccount->IdTenant.'_'.$oAccount->User->TwilioNumber);

				if ($bUserTwilioEnable)
				{
					if ($bUserDefaultNumber)
					{
						$oCapability->allowClientIncoming(strlen($sUserPhoneNumber) > 0 ? $sUserPhoneNumber : 'default');
					}
					else if (strlen($sUserPhoneNumber) > 0)
					{
						$oCapability->allowClientIncoming($sUserPhoneNumber);
					}
				}

				$mToken = $oCapability->generateToken(86400000); //Token lifetime set to 24hr (default 1hr)
			}
			catch (\Exception $oE)
			{
				\CApi::LogException($oE);
			}
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::VoiceNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mToken);
	}

	/**
	 * @return array
	 */
	public function AjaxContactVCardUpload()
	{
		$oAccount = $this->getDefaultAccountFromParam();

		$mResult = false;
		if ($this->oApiCapability->isContactsSupported($oAccount))
		{
			$bGlobal = '1' === (string) $this->getParamValue('Global', '0');
			$sContactId = (string) $this->getParamValue('ContactId', '');
			$bSharedWithAll = '1' === (string) $this->getParamValue('SharedWithAll', '0');
			$iTenantId = $bSharedWithAll ? $oAccount->IdTenant : null;

			if ($bGlobal)
			{
				if (!$this->oApiCapability->isGlobalContactsSupported($oAccount, true))
				{
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
				}
			}
			else
			{
				if (!$this->oApiCapability->isPersonalContactsSupported($oAccount))
				{
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
				}
			}

			$oApiContacts = $this->ApiContacts();
			$oApiGContacts = $this->ApiGContacts();

			$oContact = $bGlobal ?
				$oApiGContacts->getContactById($oAccount, $sContactId) :
				$oApiContacts->getContactById($oAccount->IdUser, $sContactId, false, $iTenantId);
			
			if ($oContact)
			{
				$sTempName = md5('VCARD/'.$oAccount->IdUser.'/'.$oContact->IdContact.'/'.($bGlobal ? '1' : '0').'/');

				$oVCard = new \Sabre\VObject\Component\VCard();
				\CApiContactsVCardHelper::UpdateVCardFromContact($oContact, $oVCard);
				$sData = $oVCard->serialize();

				if ($this->ApiFileCache()->put($oAccount, $sTempName, $sData))
				{
					$mResult = array(
						'Name' => 'contact-'.$oContact->IdContact.'.vcf',
						'TempName' => $sTempName,
						'MimeType' => 'text/vcard',
						'Size' => strlen($sData),
						'Hash' => ''
					);

					$mResult['MimeType'] = \MailSo\Base\Utils::MimeContentType($mResult['Name']);
					$mResult['Hash'] = \CApi::EncodeKeyValues(array(
						'TempFile' => true,
						'AccountID' => $oAccount->IdAccount,
						'Name' => $mResult['Name'],
						'TempName' => $sTempName
					));

					return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
				}
			}

			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CanNotGetContact);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxContactGet()
	{
		$oContact = false;
		$oAccount = $this->getDefaultAccountFromParam();

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();

			$sContactId = (string) $this->getParamValue('ContactId', '');
			$bSharedToAll = '1' === (string) $this->getParamValue('SharedToAll', '0');
			$iTenantId = $bSharedToAll ? $oAccount->IdTenant : null;

			$oContact = $oApiContacts->getContactById($oAccount->IdUser, $sContactId, false, $iTenantId);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oContact);
	}

	/**
	 * @return array
	 */
	public function AjaxContactsPhoneNames()
	{
		$oAccount = $this->getDefaultAccountFromParam();

		$aPhones = $this->getParamValue('Phones', null);

		if ($oAccount && is_array($aPhones) && 0 < count($aPhones))
		{
			$oApiVoiceManager = CApi::Manager('voice');
			if ($oApiVoiceManager)
			{
				return $this->DefaultResponse($oAccount, __FUNCTION__,
					$oApiVoiceManager->getNamesByCallersNumbers($oAccount, $aPhones));
			}
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}
	
	/**
	 * @return array
	 */
	public function AjaxContactGetByEmail()
	{
		$oContact = false;
		$oAccount = $this->getDefaultAccountFromParam();
		
		$sEmail = (string) $this->getParamValue('Email', '');

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();
			if ($oApiContacts)
			{
				$oContact = $oApiContacts->getContactByEmail($oAccount->IdUser, $sEmail);
			}
		}

		if (!$oContact && $this->oApiCapability->isGlobalContactsSupported($oAccount, true))
		{
			$oApiGContacts = $this->ApiGContacts();
			if ($oApiGContacts)
			{
				$oContact = $oApiGContacts->getContactByEmail($oAccount, $sEmail);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oContact);
	}

	/**
	 * @return array
	 */
	public function AjaxContactsGetByEmails()
	{
		$aResult = array();
		$oAccount = $this->getDefaultAccountFromParam();

		$sEmails = (string) $this->getParamValue('Emails', '');
		$aEmails = explode(',', $sEmails);

		if (0 < count($aEmails))
		{
			$oApiContacts = $this->ApiContacts();
			$oApiGContacts = $this->ApiGContacts();
			
			$bPab = $oApiContacts && $this->oApiCapability->isPersonalContactsSupported($oAccount);
			$bGab = $oApiGContacts && $this->oApiCapability->isGlobalContactsSupported($oAccount, true);

			foreach ($aEmails as $sEmail)
			{
				$oContact = false;
				$sEmail = trim($sEmail);
				
				if ($bPab)
				{
					$oContact = $oApiContacts->getContactByEmail($oAccount->IdUser, $sEmail);
				}

				if (!$oContact && $bGab)
				{
					$oContact = $oApiGContacts->getContactByEmail($oAccount, $sEmail);
				}

				if ($oContact)
				{
					$aResult[$sEmail] = $oContact;
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResult);
	}

	/**
	 * @return array
	 */
	public function AjaxContactsAddToGroup()
	{
		$oAccount = $this->getAccountFromParam();

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$sGroupId = (string) $this->getParamValue('GroupId', '');

			$aContactsId = $this->getParamValue('ContactsId', null);
			if (!is_array($aContactsId))
			{
				return $this->DefaultResponse($oAccount, __FUNCTION__, false);
			}

			$oApiContacts = $this->ApiContacts();

			$oGroup = $oApiContacts->getGroupById($oAccount->IdUser, $sGroupId);
			if ($oGroup)
			{
				$aLocalContacts = array();
				$aGlobalContacts = array();
				
				foreach ($aContactsId as $aItem)
				{
					if (is_array($aItem) && 2 === count($aItem))
					{
						if ('1' === $aItem[1])
						{
							$aGlobalContacts[] = $aItem[0];
						}
						else
						{
							$aLocalContacts[] = $aItem[0];
						}
					}
				}

				$bRes1 = true;
				if (0 < count($aGlobalContacts))
				{
					$bRes1 = false;
					if (!$this->oApiCapability->isGlobalContactsSupported($oAccount, true))
					{
						throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
					}

					$bRes1 = $oApiContacts->addGlobalContactsToGroup($oAccount, $oGroup, $aGlobalContacts);
				}

				$bRes2 = true;
				if (0 < count($aLocalContacts))
				{
					$bRes2 = $oApiContacts->addContactsToGroup($oGroup, $aLocalContacts);
				}

				return $this->DefaultResponse($oAccount, __FUNCTION__, $bRes1 && $bRes2);
			}
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, false);
	}

	/**
	 * @return array
	 */
	public function AjaxContactsRemoveFromGroup()
	{
		$oAccount = $this->getAccountFromParam();

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount) ||
			$this->oApiCapability->isGlobalContactsSupported($oAccount, true))
		{
			$oApiContacts = $this->ApiContacts();

			$sGroupId = (string) $this->getParamValue('GroupId', '');

			$aContactsId = explode(',', $this->getParamValue('ContactsId', ''));
			$aContactsId = array_map('trim', $aContactsId);

			$oGroup = $oApiContacts->getGroupById($oAccount->IdUser, $sGroupId);
			if ($oGroup)
			{
				return $this->DefaultResponse($oAccount, __FUNCTION__,
					$oApiContacts->removeContactsFromGroup($oGroup, $aContactsId));
			}

			return $this->DefaultResponse($oAccount, __FUNCTION__, false);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, false);
	}

	/**
	 * @return array
	 */
	public function AjaxSystemDoServerInitializations()
	{
		$oAccount = $this->getAccountFromParam();

		$bResult = false;

		$oApiIntegrator = \CApi::Manager('integrator');

		if ($oAccount && $oApiIntegrator)
		{
			$oApiIntegrator->resetCookies();
		}

		$oApiHelpdesk = \CApi::Manager('helpdesk');

		if ($this->oApiCapability->isGlobalContactsSupported($oAccount, true))
		{
			$oApiContacts = $this->ApiContacts();
			if ($oApiContacts && method_exists($oApiContacts, 'synchronizeExternalContacts'))
			{
				$bResult = $oApiContacts->synchronizeExternalContacts($oAccount);
			}
		}

		$oCacher = \CApi::Cacher();

		$bDoGC = false;
		$bDoHepdeskClear = false;
		if ($oCacher && $oCacher->IsInited())
		{
			$iTime = $oCacher->GetTimer('Cache/ClearFileCache');
			if (0 === $iTime || $iTime + 60 * 60 * 24 < time())
			{
				if ($oCacher->SetTimer('Cache/ClearFileCache'))
				{
					$bDoGC = true;
				}
			}

			if ($oApiHelpdesk)
			{
				$iTime = $oCacher->GetTimer('Cache/ClearHelpdeskUsers');
				if (0 === $iTime || $iTime + 60 * 60 * 24 < time())
				{
					if ($oCacher->SetTimer('Cache/ClearHelpdeskUsers'))
					{
						$bDoHepdeskClear = true;
					}
				}
			}
		}

		if ($bDoGC)
		{
			\CApi::Log('GC: FileCache / Start');
			$this->ApiFileCache()->gc();
			$oCacher->gc();
			\CApi::Log('GC: FileCache / End');
		}

		if ($bDoHepdeskClear && $oApiHelpdesk)
		{
			\CApi::Log('GC: clear Unregistred Users');
			$oApiHelpdesk->clearUnregistredUsers();

			\CApi::Log('GC: clear Online');
			$oApiHelpdesk->clearAllOnline();
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
	}
	
	
	public function AjaxCalendarAttendeeUpdateStatus()
	{
		$oAccount = $this->getAccountFromParam();
		
		$mResult = false;

		$sTempFile = (string) $this->getParamValue('File', '');
		$sFromEmail = (string) $this->getParamValue('FromEmail', '');
		
		if (empty($sTempFile) || empty($sFromEmail))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}
		if ($this->oApiCapability->isCalendarAppointmentsSupported($oAccount))
		{
			$oApiFileCache = /* @var $oApiFileCache \CApiFilecacheManager */ \CApi::Manager('filecache');
			$sData = $oApiFileCache->get($oAccount, $sTempFile);
			if (!empty($sData))
			{
				$oCalendarApi = \CApi::Manager('calendar');
				if ($oCalendarApi)
				{
					$mResult = $oCalendarApi->processICS($oAccount, $sData, $sFromEmail, true);
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);		
		
	}
	
	/**
	 * @return array
	 */
	public function AjaxCalendarAppointmentSetAction()
	{
		$oAccount = $this->getAccountFromParam();
		$oDefaultAccount = $this->getDefaultAccountFromParam();
		
		$mResult = false;

		$sCalendarId = (string) $this->getParamValue('CalendarId', '');
		$sEventId = (string) $this->getParamValue('EventId', '');
		$sTempFile = (string) $this->getParamValue('File', '');
		$sAction = (string) $this->getParamValue('AppointmentAction', '');
		$sAttendee = (string) $this->getParamValue('Attendee', '');
		
		if (empty($sAction) || empty($sCalendarId))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		if ($this->oApiCapability->isCalendarAppointmentsSupported($oDefaultAccount))
		{
			$sData = '';
			if (!empty($sEventId))
			{
				$aEventData =  $this->oApiCalendar->getEvent($oDefaultAccount, $sCalendarId, $sEventId);
				if (isset($aEventData) && isset($aEventData['vcal']) && $aEventData['vcal'] instanceof \Sabre\VObject\Component\VCalendar)
				{
					$oVCal = $aEventData['vcal'];
					$oVCal->METHOD = 'REQUEST';
					$sData = $oVCal->serialize();
				}
			}
			else if (!empty($sTempFile))
			{
				$oApiFileCache = /* @var $oApiFileCache \CApiFilecacheManager */ \CApi::Manager('filecache');
				$sData = $oApiFileCache->get($oAccount, $sTempFile);
			}
			if (!empty($sData))
			{
				$oCalendarApi = \CApi::Manager('calendar');
				if ($oCalendarApi)
				{
					$mProcessResult = $oCalendarApi->appointmentAction($oDefaultAccount, $sAttendee, $sAction, $sCalendarId, $sData);
					if ($mProcessResult)
					{
						$mResult = array(
							'Uid' => $mProcessResult
						);
					}
				}
			}
		}

		return $this->DefaultResponse($oDefaultAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxCalendarSaveIcs()
	{
		$oAccount = $this->getAccountFromParam();

		$mResult = false;

		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}

		$sCalendarId = (string) $this->getParamValue('CalendarId', '');
		$sTempFile = (string) $this->getParamValue('File', '');

		if (empty($sCalendarId) || empty($sTempFile))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oApiFileCache = /* @var $oApiFileCache \CApiFilecacheManager */ \CApi::Manager('filecache');
		$sData = $oApiFileCache->get($oAccount, $sTempFile);
		if (!empty($sData))
		{
			$oCalendarApi = \CApi::Manager('calendar');
			if ($oCalendarApi)
			{
				$mCreateEventResult = $oCalendarApi->createEventFromRaw($oAccount, $sCalendarId, null, $sData);
				if ($mCreateEventResult)
				{
					$mResult = array(
						'Uid' => (string) $mCreateEventResult
					);
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxContactsSaveVcf()
	{
		$oAccount = $this->getAccountFromParam();

		$mResult = false;

		if (!$this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		$sTempFile = (string) $this->getParamValue('File', '');
		if (empty($sTempFile))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oApiFileCache = /* @var $oApiFileCache \CApiFilecacheManager */ \CApi::Manager('filecache');
		$sData = $oApiFileCache->get($oAccount, $sTempFile);
		if (!empty($sData))
		{
			$oContactsApi = $this->ApiContacts();
			if ($oContactsApi)
			{
				$oContact = new \CContact();
				$oContact->InitFromVCardStr($oAccount->IdUser, $sData);
				
				if ($oContactsApi->createContact($oContact))
				{
					$mResult = array(
						'Uid' => $oContact->IdContact
					);
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxContactGlobal()
	{
		$oContact = false;
		$oAccount = $this->getDefaultAccountFromParam();

		if ($this->oApiCapability->isGlobalContactsSupported($oAccount, true))
		{
			$oApiGContacts = $this->ApiGContacts();

			$sContactId = (string) $this->getParamValue('ContactId', '');

			$oContact = $oApiGContacts->getContactById($oAccount, $sContactId);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oContact);
	}

	/**
	 * @return array
	 */
	public function AjaxContactsGroup()
	{
		$oGroup = false;
		$oAccount = $this->getDefaultAccountFromParam();

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();

			$sGroupId = (string) $this->getParamValue('GroupId', '');

			$oGroup = $oApiContacts->getGroupById($oAccount->IdUser, $sGroupId);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oGroup);
	}

	/**
	 * @return array
	 */
	public function AjaxContactsGroupFullList()
	{
		$oAccount = $this->getDefaultAccountFromParam();

		$aList = false;
		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();

			$aList = $oApiContacts->getGroupItems($oAccount->IdUser,
				\EContactSortField::Name, \ESortOrder::ASC, 0, 999);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aList);
	}
	
	/**
	 * @return array
	 */
	public function AjaxContactsGroupEvents()
	{
		$aEvents = array();
		$oAccount = $this->getDefaultAccountFromParam();

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();

			$sGroupId = (string) $this->getParamValue('GroupId', '');

			$aEvents = $oApiContacts->getGroupEvents($oAccount->IdUser, $sGroupId);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aEvents);
	}	

	private function populateSortParams(&$iSortField, &$iSortOrder)
	{
		$sSortField = (string) $this->getParamValue('SortField', 'Email');
		$iSortOrder = '1' === (string) $this->getParamValue('SortOrder', '0') ?
			\ESortOrder::ASC : \ESortOrder::DESC;

		switch (strtolower($sSortField))
		{
			case 'email':
				$iSortField = \EContactSortField::EMail;
				break;
			case 'name':
				$iSortField = \EContactSortField::Name;
				break;
			case 'frequency':
				$iSortField = \EContactSortField::Frequency;
				break;
		}
	}

	/**
	 * @return array
	 */
	public function AjaxContactSuggestions()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		$oApiContacts = $this->ApiContacts();

		$sSearch = (string) $this->getParamValue('Search', '');
		$bGlobalOnly = '1' === (string) $this->getParamValue('GlobalOnly', '0');
		$bPhoneOnly = '1' === (string) $this->getParamValue('PhoneOnly', '0');

		$aList = array();
		
		$iSharedTenantId = null;
		if ($this->oApiCapability->isSharedContactsSupported($oAccount) && !$bPhoneOnly)
		{
			$iSharedTenantId = $oAccount->IdTenant;
		}

		if ($this->oApiCapability->isContactsSupported($oAccount))
		{
			$aContacts = $oApiContacts ?
				$oApiContacts->getSuggestItems($oAccount, $sSearch,
					\CApi::GetConf('webmail.suggest-contacts-limit', 20), $bGlobalOnly, $bPhoneOnly, $iSharedTenantId) : null;

			if (is_array($aContacts))
			{
				$aList = $aContacts;
			}
		}

		$aCounts = array(0, 0);
		
		\CApi::Plugin()->RunHook('webmail.change-suggest-list', array($oAccount, $sSearch, &$aList, &$aCounts));

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'Search' => $sSearch,
			'List' => $aList
		));
	}

	public function AjaxContactSuggestionDelete()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();

			$sContactId = (string) $this->getParamValue('ContactId', '');
			$oApiContacts->resetContactFrequency($oAccount->IdUser, $sContactId);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxContactList()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		$oApiContacts = $this->ApiContacts();

		$iOffset = (int) $this->getParamValue('Offset', 0);
		$iLimit = (int) $this->getParamValue('Limit', 20);
		$sGroupId = (string) $this->getParamValue('GroupId', '');
		$sSearch = (string) $this->getParamValue('Search', '');
		$sFirstCharacter = (string) $this->getParamValue('FirstCharacter', '');
		$bSharedToAll = '1' === (string) $this->getParamValue('SharedToAll', '0');
		$bAll = '1' === (string) $this->getParamValue('All', '0');

		$iSortField = \EContactSortField::Name;
		$iSortOrder = \ESortOrder::ASC;
		
		$iTenantId = $bSharedToAll ? $oAccount->IdTenant : null;
		
		$this->populateSortParams($iSortField, $iSortOrder);

		$bAllowContactsSharing = $this->oApiCapability->isSharedContactsSupported($oAccount);
		if ($bAll && !$bAllowContactsSharing &&
			!$this->oApiCapability->isGlobalContactsSupported($oAccount))
		{
			$bAll = false;
		}

		$iCount = 0;
		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$iGroupId = ('' === $sGroupId) ? 0 : (int) $sGroupId;
			
			if ($bAllowContactsSharing && 0 < $iGroupId)
			{
				$iTenantId = $oAccount->IdTenant;
			}
			
			$iCount = $oApiContacts->getContactItemsCount(
				$oAccount->IdUser, $sSearch, $sFirstCharacter, $iGroupId, $iTenantId, $bAll);

			$aList = array();
			if (0 < $iCount)
			{
				$aContacts = $oApiContacts->getContactItems(
					$oAccount->IdUser, $iSortField, $iSortOrder, $iOffset,
					$iLimit, $sSearch, $sFirstCharacter, $sGroupId, $iTenantId, $bAll);

				if (is_array($aContacts))
				{
					$aList = $aContacts;
				}
			}
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'ContactCount' => $iCount,
			'GroupId' => $sGroupId,
			'Search' => $sSearch,
			'FirstCharacter' => $sFirstCharacter,
			'All' => $bAll,
			'List' => $aList
		));
	}

	/**
	 * @return array
	 */
	public function AjaxContactGlobalList()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		$oApiGContacts = $this->ApiGContacts();

		$iOffset = (int) $this->getParamValue('Offset', 0);
		$iLimit = (int) $this->getParamValue('Limit', 20);
		$sSearch = (string) $this->getParamValue('Search', '');

		$iSortField = \EContactSortField::EMail;
		$iSortOrder = \ESortOrder::ASC;

		$this->populateSortParams($iSortField, $iSortOrder);

		$iCount = 0;
		$aList = array();

		if ($this->oApiCapability->isGlobalContactsSupported($oAccount, true))
		{
			$iCount = $oApiGContacts->getContactItemsCount($oAccount, $sSearch);

			if (0 < $iCount)
			{
				$aContacts = $oApiGContacts->getContactItems(
					$oAccount, $iSortField, $iSortOrder, $iOffset,
					$iLimit, $sSearch);

				$aList = (is_array($aContacts)) ? $aContacts : array();
			}
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'ContactCount' => $iCount,
			'Search' => $sSearch,
			'List' => $aList
		));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountIdentitiesGet()
	{
		$oAccount = $this->getAccountFromParam();
		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->getUserIdentities($oAccount->IdUser));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountIdentityCreate()
	{
		$oAccount = $this->getAccountFromParam();
		$sEmail = trim((string) $this->getParamValue('Email', ''));

		if (empty($sEmail))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oIdentity = new \CIdentity();
		$oIdentity->IdAccount = $oAccount->IdAccount;
		$oIdentity->IdUser  = $oAccount->IdUser;
		$oIdentity->Enabled = '1' === (string) $this->getParamValue('Enabled', '1');
		$oIdentity->Email = $sEmail;
		$oIdentity->Signature = (string) $this->getParamValue('Signature', '');
		$oIdentity->UseSignature = '1' === (string) $this->getParamValue('UseSignature', '0');
		$oIdentity->FriendlyName = (string) $this->getParamValue('FriendlyName', '');

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->createIdentity($oIdentity));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountIdentityUpdate()
	{
		$oAccount = $this->getAccountFromParam();

		$iIdentityId = (int)$this->getParamValue('IdIdentity', 0);

		$oIdentity = $this->oApiUsers->getIdentity($iIdentityId);
		if (0 >= $iIdentityId || !$oIdentity || $oIdentity->IdUser !== $oAccount->IdUser || $oIdentity->IdAccount !== $oAccount->IdAccount)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oIdentity->Default = '1' === (string)$this->getParamValue('Default', '0');
		$oIdentity->Enabled = '1' === (string)$this->getParamValue('Enabled', '1');
		$oIdentity->Email = trim((string)$this->getParamValue('Email', ''));
		$oIdentity->Signature = (string)$this->getParamValue('Signature', '');
		$oIdentity->UseSignature = '1' === (string)$this->getParamValue('UseSignature', '0');
		$oIdentity->FriendlyName = (string)$this->getParamValue('FriendlyName', '');

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->updateIdentity($oIdentity));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountIdentityDelete()
	{
		$oAccount = $this->getAccountFromParam();

		$iIdentityId = (int) $this->getParamValue('IdIdentity', 0);
		
		$oIdentity = $this->oApiUsers->getIdentity($iIdentityId);
		if (0 >= $iIdentityId || !$oIdentity || $oIdentity->IdUser !== $oAccount->IdUser || $oIdentity->IdAccount !== $oAccount->IdAccount)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->deleteIdentity($iIdentityId));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountAutoresponderGet()
	{
		$mResult = false;
		$oAccount = $this->getAccountFromParam();
		
		if ($oAccount && $oAccount->isExtensionEnabled(\CAccount::AutoresponderExtension))
		{
			$aAutoResponderValue = $this->ApiSieve()->getAutoresponder($oAccount);
			if (isset($aAutoResponderValue['subject'], $aAutoResponderValue['body'], $aAutoResponderValue['enabled']))
			{
				$mResult = array(
					'Enable' => (bool) $aAutoResponderValue['enabled'],
					'Subject' => (string) $aAutoResponderValue['subject'],
					'Message' => (string) $aAutoResponderValue['body']
				);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountAutoresponderUpdate()
	{
		$bIsDemo = false;
		$mResult = false;
		$oAccount = $this->getAccountFromParam();
		if ($oAccount && $oAccount->isExtensionEnabled(\CAccount::AutoresponderExtension))
		{
			\CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
			if (!$bIsDemo)
			{
				$bIsEnabled = '1' === $this->getParamValue('Enable', '0');
				$sSubject = (string) $this->getParamValue('Subject', '');
				$sMessage = (string) $this->getParamValue('Message', '');

				$mResult = $this->ApiSieve()->setAutoresponder($oAccount, $sSubject, $sMessage, $bIsEnabled);
			}
			else
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::DemoAccount);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountForwardGet()
	{
		$mResult = false;
		$oAccount = $this->getAccountFromParam();
		
		if ($oAccount && $oAccount->isExtensionEnabled(\CAccount::ForwardExtension))
		{
			$aForwardValue = /* @var $aForwardValue array */  $this->ApiSieve()->getForward($oAccount);
			if (isset($aForwardValue['email'], $aForwardValue['enabled']))
			{
				$mResult = array(
					'Enable' => (bool) $aForwardValue['enabled'],
					'Email' => (string) $aForwardValue['email']
				);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountForwardUpdate()
	{
		$mResult = false;
		$bIsDemo = false;
		$oAccount = $this->getAccountFromParam();

		if ($oAccount && $oAccount->isExtensionEnabled(\CAccount::ForwardExtension))
		{
			\CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
			if (!$bIsDemo)
			{
				$bIsEnabled = '1' === $this->getParamValue('Enable', '0');
				$sForwardEmail = (string) $this->getParamValue('Email', '');
		
				$mResult = $this->ApiSieve()->setForward($oAccount, $sForwardEmail, $bIsEnabled);
			}
			else
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::DemoAccount);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountSieveFiltersGet()
	{
		$mResult = false;
		$oAccount = $this->getAccountFromParam();

		if ($oAccount && $oAccount->isExtensionEnabled(\CAccount::SieveFiltersExtension))
		{
			$mResult = $this->ApiSieve()->getSieveFilters($oAccount);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountSieveFiltersUpdate()
	{
		$mResult = false;
		$oAccount = $this->getAccountFromParam();

		if ($oAccount && $oAccount->isExtensionEnabled(\CAccount::SieveFiltersExtension))
		{
			$aFilters = $this->getParamValue('Filters', array());
			$aFilters = is_array($aFilters) ? $aFilters : array();

			$mResult = array();
			foreach ($aFilters as $aItem)
			{
				$oFilter = new \CFilter($oAccount);
				$oFilter->Enable = '1' === (string) (isset($aItem['Enable']) ? $aItem['Enable'] : '1');
				$oFilter->Field = (int) (isset($aItem['Field']) ? $aItem['Field'] : \EFilterFiels::From);
				$oFilter->Filter = (string) (isset($aItem['Filter']) ? $aItem['Filter'] : '');
				$oFilter->Condition = (int) (isset($aItem['Condition']) ? $aItem['Condition'] : \EFilterCondition::ContainSubstring);
				$oFilter->Action = (int) (isset($aItem['Action']) ? $aItem['Action'] : \EFilterAction::DoNothing);
				$oFilter->FolderFullName = (string) (isset($aItem['FolderFullName']) ? $aItem['FolderFullName'] : '');

				$mResult[] = $oFilter;
			}

			$mResult = $this->ApiSieve()->updateSieveFilters($oAccount, $mResult);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @param string $sParamName
	 * @param mixed $oObject
	 *
	 * @return void
	 */
	private function paramToObject($sParamName, &$oObject, $sType = 'string')
	{
		switch ($sType)
		{
			default:
			case 'string':
				$oObject->{$sParamName} = (string) $this->getParamValue($sParamName, $oObject->{$sParamName});
				break;
			case 'int':
				$oObject->{$sParamName} = (int) $this->getParamValue($sParamName, $oObject->{$sParamName});
				break;
			case 'bool':
				$oObject->{$sParamName} = '1' === (string) $this->getParamValue($sParamName, $oObject->{$sParamName} ? '1' : '0');
				break;
		}
	}

	/**
	 * @param mixed $oObject
	 * @param array $aParamsNames
	 */
	private function paramsStrToObjectHelper(&$oObject, $aParamsNames)
	{
		foreach ($aParamsNames as $sName)
		{
			$this->paramToObject($sName, $oObject);
		}
	}

	/**
	 * @param \CContact $oContact
	 * @param bool $bItsMe = false
	 */
	private function populateContactObject(&$oContact, $bItsMe = false)
	{
		$iPrimaryEmail = $oContact->PrimaryEmail;
		switch (strtolower($this->getParamValue('PrimaryEmail', '')))
		{
			case 'home':
			case 'personal':
				$iPrimaryEmail = \EPrimaryEmailType::Home;
				break;
			case 'business':
				$iPrimaryEmail = \EPrimaryEmailType::Business;
				break;
			case 'other':
				$iPrimaryEmail = \EPrimaryEmailType::Other;
				break;
		}

		$oContact->PrimaryEmail = $iPrimaryEmail;

		$this->paramToObject('UseFriendlyName', $oContact, 'bool');

		$this->paramsStrToObjectHelper($oContact, array(
			'Title', 'FullName', 'FirstName', 'LastName', 'NickName', 'Skype', 'Facebook',

			'HomeEmail', 'HomeStreet', 'HomeCity', 'HomeState', 'HomeZip',
			'HomeCountry', 'HomeFax', 'HomePhone', 'HomeMobile', 'HomeWeb',

			'BusinessCompany', 'BusinessJobTitle', 'BusinessDepartment',
			'BusinessOffice', 'BusinessStreet', 'BusinessCity', 'BusinessState',  'BusinessZip',
			'BusinessCountry', 'BusinessFax','BusinessPhone', 'BusinessMobile',  'BusinessWeb',

			'OtherEmail', 'Notes', 'ETag'
		));

		if (!$bItsMe)
		{
			$this->paramToObject('BusinessEmail', $oContact);
		}

		$this->paramToObject('BirthdayDay', $oContact, 'int');
		$this->paramToObject('BirthdayMonth', $oContact, 'int');
		$this->paramToObject('BirthdayYear', $oContact, 'int');

		$aGroupsIds = $this->getParamValue('GroupsIds');
		$aGroupsIds = is_array($aGroupsIds) ? array_map('trim', $aGroupsIds) : array();
		$oContact->GroupsIds = array_unique($aGroupsIds);
	}

	/**
	 * @param \CGroup $oGroup
	 */
	private function populateGroupObject(&$oGroup)
	{
		$this->paramToObject('IsOrganization', $oGroup, 'bool');

		$this->paramsStrToObjectHelper($oGroup, array(
			'Name', 'Email', 'Country', 'City', 'Company', 'Fax', 'Phone',
			'State', 'Street', 'Web', 'Zip'			
		));
	}

	/**
	 * @return array
	 */
	public function AjaxContactCreate()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		$bSharedToAll = '1' === $this->getParamValue('SharedToAll', '0');
		
		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();

			$oContact = new \CContact();
			$oContact->IdUser = $oAccount->IdUser;
			$oContact->IdTenant = $oAccount->IdTenant;
			$oContact->IdDomain = $oAccount->IdDomain;
			$oContact->SharedToAll = $bSharedToAll;

			$this->populateContactObject($oContact);

			$oApiContacts->createContact($oContact);
			return $this->DefaultResponse($oAccount, __FUNCTION__, $oContact ? array(
				'IdContact' => $oContact->IdContact
			) : false);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxContactsGroupCreate()
	{
		$oAccount = $this->getDefaultAccountFromParam();

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();

			$oGroup = new \CGroup();
			$oGroup->IdUser = $oAccount->IdUser;

			$this->populateGroupObject($oGroup);

			$oApiContacts->createGroup($oGroup);
			return $this->DefaultResponse($oAccount, __FUNCTION__, $oGroup ? array(
				'IdGroup' => $oGroup->IdGroup
			) : false);
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxContactDelete()
	{
		$oAccount = $this->getDefaultAccountFromParam();

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();

			$aContactsId = explode(',', $this->getParamValue('ContactsId', ''));
			$aContactsId = array_map('trim', $aContactsId);
			
			$bSharedToAll = '1' === (string) $this->getParamValue('SharedToAll', '0');
			$iTenantId = $bSharedToAll ? $oAccount->IdTenant : null;

			return $this->DefaultResponse($oAccount, __FUNCTION__,
				$oApiContacts->deleteContacts($oAccount->IdUser, $aContactsId, $iTenantId));
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxContactsGroupDelete()
	{
		$oAccount = $this->getDefaultAccountFromParam();

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();

			$sGroupId = $this->getParamValue('GroupId', '');

			return $this->DefaultResponse($oAccount, __FUNCTION__,
				$oApiContacts->deleteGroup($oAccount->IdUser, $sGroupId));
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxContactUpdate()
	{
		$oAccount = $this->getDefaultAccountFromParam();

		$bGlobal = '1' === $this->getParamValue('Global', '0');
		$sContactId = $this->getParamValue('ContactId', '');

		$bSharedToAll = '1' === $this->getParamValue('SharedToAll', '0');
		$iTenantId = $bSharedToAll ? $oAccount->IdTenant : null;

		if ($bGlobal && $this->oApiCapability->isGlobalContactsSupported($oAccount, true))
		{
			$oApiContacts = $this->ApiGContacts();
		}
		else if (!$bGlobal && $this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();
		}

		if ($oApiContacts)
		{
			$oContact = $oApiContacts->getContactById($bGlobal ? $oAccount : $oAccount->IdUser, $sContactId, false, $iTenantId);
			if ($oContact)
			{
				$this->populateContactObject($oContact, $oContact->ItsMe);

				if ($oApiContacts->updateContact($oContact))
				{
					return $this->TrueResponse($oAccount, __FUNCTION__);
				}
				else
				{
					switch ($oApiContacts->getLastErrorCode())
					{
						case \Errs::Sabre_PreconditionFailed:
							throw new \ProjectCore\Exceptions\ClientException(
								\ProjectCore\Notifications::ContactDataHasBeenModifiedByAnotherApplication);
					}
				}
			}
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}
	
	/**
	 * @return array
	 */
	public function AjaxContactUpdateSharedToAll()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		
		$aContactsId = explode(',', $this->getParamValue('ContactsId', ''));
		$aContactsId = array_map('trim', $aContactsId);
		
		$iTenantId = ('1' === $this->getParamValue('SharedToAll', '0')) ? $oAccount->IdTenant : null;

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oApiContacts = $this->ApiContacts();
			if ($oApiContacts && $this->oApiCapability->isSharedContactsSupported($oAccount))
			{
				foreach ($aContactsId as $sContactId)
				{
					$oContact = $oApiContacts->getContactById($oAccount->IdUser, $sContactId, false, $iTenantId);
					if ($oContact)
					{
						if ($oContact->SharedToAll && $oContact->IdUser !== $oAccount->IdUser)
						{
							$oApiContacts->updateContactUserId($oContact, $oAccount->IdUser);
						}

						$oContact->SharedToAll = !$oContact->SharedToAll;
						$oContact->IdUser = $oAccount->IdUser;
						$oContact->IdDomain = $oAccount->IdDomain;
						$oContact->IdTenant = $oAccount->IdTenant;

						if (!$oApiContacts->updateContact($oContact))
						{
							switch ($oApiContacts->getLastErrorCode())
							{
								case \Errs::Sabre_PreconditionFailed:
									throw new \ProjectCore\Exceptions\ClientException(
										\ProjectCore\Notifications::ContactDataHasBeenModifiedByAnotherApplication);
							}
						}
					}
				}

				return $this->TrueResponse($oAccount, __FUNCTION__);
			}
			else
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
			}
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}	

	/**
	 * @return array
	 */
	public function AjaxContactsGroupUpdate()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		$oApiContacts = $this->ApiContacts();

		$sGroupId = $this->getParamValue('GroupId', '');

		if ($this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			$oGroup = $oApiContacts->getGroupById($oAccount->IdUser, $sGroupId);
			if ($oGroup)
			{
				$this->populateGroupObject($oGroup);

				if ($oApiContacts->updateGroup($oGroup))
				{
					return $this->TrueResponse($oAccount, __FUNCTION__);
				}
				else
				{
					switch ($oApiContacts->getLastErrorCode())
					{
						case \Errs::Sabre_PreconditionFailed:
							throw new \ProjectCore\Exceptions\ClientException(
								\ProjectCore\Notifications::ContactDataHasBeenModifiedByAnotherApplication);
					}
				}
			}
		}
		else
		{
			throw new \ProjectCore\Exceptions\ClientException(
				\ProjectCore\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}
	
	public function AjaxFileStoragesExternal()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}
		
		$oResult = array();
		\CApi::Plugin()->RunHook('filestorage.get-external-storages', array($oAccount, &$oResult));

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}

	public function AjaxFiles()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}

		$sPath = $this->getParamValue('Path');
		$sType = $this->getParamValue('Type');
		$sPattern = $this->getParamValue('Pattern');
		
		$oResult = array(
			'Items' => $this->oApiFilestorage->getFiles($oAccount, $sType, $sPath, $sPattern),
			'Quota' => $this->oApiFilestorage->getQuota($oAccount)
		);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}	

	public function AjaxFilesPub()
	{
		$oAccount = null;
		$oResult = array();

		$oMin = \CApi::Manager('min');

		$sHash = $this->getParamValue('Hash');
		$sPath = $this->getParamValue('Path', '');
		
		$mMin = $oMin->getMinByHash($sHash);
		if (!empty($mMin['__hash__']))
		{
			$oAccount = $this->oApiUsers->getAccountById($mMin['Account']);
			if ($oAccount)
			{
				if (!$this->oApiCapability->isFilesSupported($oAccount))
				{
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
				}
				$sType = $mMin['Type'];

				$sPath =  implode('/', array($mMin['Path'], $mMin['Name']))  . $sPath;

				$oResult['Items'] = $this->oApiFilestorage->getFiles($oAccount, $sType, $sPath);
				$oResult['Quota'] = $this->oApiFilestorage->getQuota($oAccount);
				
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}	

	public function AjaxFilesQuota()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}
		
		$oResult = array(
			'Quota' => $this->oApiFilestorage->getQuota($oAccount)
		);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}	

	public function AjaxFilesFolderCreate()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}

		$sType = $this->getParamValue('Type');
		$sPath = $this->getParamValue('Path');
		$sFolderName = $this->getParamValue('FolderName');
		$oResult = null;
		
		$oResult = $this->oApiFilestorage->createFolder($oAccount, $sType, $sPath, $sFolderName);
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}
	
	public function AjaxFilesLinkCreate()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}

		$sType = $this->getParamValue('Type');
		$sPath = $this->getParamValue('Path');
		$sLink = $this->getParamValue('Link');
		$sName = $this->getParamValue('Name');
		$oResult = $this->oApiFilestorage->createLink($oAccount, $sType, $sPath, $sLink, $sName);
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}
	
	public function AjaxFilesDelete()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}

		$sType = $this->getParamValue('Type');
		$aItems = @json_decode($this->getParamValue('Items'), true);
		$oResult = false;
		
		foreach ($aItems as $oItem)
		{
			$oResult = $this->oApiFilestorage->delete($oAccount, $sType, $oItem['Path'], $oItem['Name']);
		}
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}	

	public function AjaxFilesRename()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}
		
		$sType = $this->getParamValue('Type');
		$sPath = $this->getParamValue('Path');
		$sName = $this->getParamValue('Name');
		$sNewName = $this->getParamValue('NewName');
		$bIsLink = !!$this->getParamValue('IsLink');
		$oResult = null;

		$sNewName = \trim(\MailSo\Base\Utils::ClearFileName($sNewName));
		
		$sNewName = $this->oApiFilestorage->getNonExistingFileName($oAccount, $sType, $sPath, $sNewName);
		$oResult = $this->oApiFilestorage->rename($oAccount, $sType, $sPath, $sName, $sNewName, $bIsLink);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}	

	public function AjaxFilesCopy()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}

		$sFromType = $this->getParamValue('FromType');
		$sToType = $this->getParamValue('ToType');
		$sFromPath = $this->getParamValue('FromPath');
		$sToPath = $this->getParamValue('ToPath');
		$aItems = @json_decode($this->getParamValue('Files'), true);
		$oResult = null;
		
		foreach ($aItems as $aItem)
		{
			$bFolderIntoItself = $aItem['IsFolder'] && $sToPath === $sFromPath.'/'.$aItem['Name'];
			if (!$bFolderIntoItself)
			{
				$sNewName = $this->oApiFilestorage->getNonExistingFileName($oAccount, $sToType, $sToPath, $aItem['Name']);
				$oResult = $this->oApiFilestorage->copy($oAccount, $sFromType, $sToType, $sFromPath, $sToPath, $aItem['Name'], $sNewName);
			}
		}
		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}	

	public function AjaxFilesMove()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}
		
		$sFromType = $this->getParamValue('FromType');
		$sToType = $this->getParamValue('ToType');
		$sFromPath = $this->getParamValue('FromPath');
		$sToPath = $this->getParamValue('ToPath');
		$aItems = @json_decode($this->getParamValue('Files'), true);
		$oResult = null;
		
		foreach ($aItems as $aItem)
		{
			$bFolderIntoItself = $aItem['IsFolder'] && $sToPath === $sFromPath.'/'.$aItem['Name'];
			if (!$bFolderIntoItself)
			{
				$sNewName = $this->oApiFilestorage->getNonExistingFileName($oAccount, $sToType, $sToPath, $aItem['Name']);
				$oResult = $this->oApiFilestorage->move($oAccount, $sFromType, $sToType, $sFromPath, $sToPath, $aItem['Name'], $sNewName);
			}
		}
		return $this->DefaultResponse($oAccount, __FUNCTION__, $oResult);
	}	
	
	public function AjaxFilesCreatePublicLink()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}
		
		$sType = $this->getParamValue('Type'); 
		$sPath = $this->getParamValue('Path'); 
		$sName = $this->getParamValue('Name');
		$sSize = $this->getParamValue('Size');
		$bIsFolder = $this->getParamValue('IsFolder', '0') === '1' ? true : false;
		
		$mResult = $this->oApiFilestorage->createPublicLink($oAccount, $sType, $sPath, $sName, $sSize, $bIsFolder);
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}	
	
	public function AjaxFilesPublicLinkDelete()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}
		
		$sType = $this->getParamValue('Type'); 
		$sPath = $this->getParamValue('Path'); 
		$sName = $this->getParamValue('Name');
		
		$mResult = $this->oApiFilestorage->deletePublicLink($oAccount, $sType, $sPath, $sName);
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxCalendarList()
	{
		$mResult = false;
		$bIsPublic = (bool) $this->getParamValue('IsPublic'); 
		$sPublicCalendarId = $this->getParamValue('PublicCalendarId');
		$oAccount = null;
				
		if ($bIsPublic)
		{
			$oCalendar = $this->oApiCalendar->getPublicCalendar($sPublicCalendarId);
			$mResult = array();
			if ($oCalendar instanceof \CCalendar)
			{
				$aCalendar = $oCalendar->toArray($oAccount);
				$mResult = array($aCalendar);
			}
		}
		else
		{
			$oAccount = $this->getDefaultAccountFromParam();
			if (!$this->oApiCapability->isCalendarSupported($oAccount))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
			}
			$mResult = $this->oApiCalendar->getCalendars($oAccount);
		}
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
		
	}

	/**
	 * @return array
	 */
	public function AjaxCalendarCreate()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}
		
		$sName = $this->getParamValue('Name');
		$sDescription = $this->getParamValue('Description'); 
		$sColor = $this->getParamValue('Color'); 
		
		$mCalendarId = $this->oApiCalendar->createCalendar($oAccount, $sName, $sDescription, 0, $sColor);
		if ($mCalendarId)
		{
			$oCalendar = $this->oApiCalendar->getCalendar($oAccount, $mCalendarId);
			if ($oCalendar instanceof \CCalendar)
			{
				$mResult = $oCalendar->toArray($oAccount);
			}
		}
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxCalendarUpdate()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}
		
		$sName = $this->getParamValue('Name');
		$sDescription = $this->getParamValue('Description'); 
		$sColor = $this->getParamValue('Color'); 
		$sId = $this->getParamValue('Id'); 
		
		$mResult = $this->oApiCalendar->updateCalendar($oAccount, $sId, $sName, $sDescription, 0, $sColor);
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}	

	/**
	 * @return array
	 */
	public function AjaxCalendarUpdateColor()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}
		
		$sColor = $this->getParamValue('Color'); 
		$sId = $this->getParamValue('Id'); 
		
		$mResult = $this->oApiCalendar->updateCalendarColor($oAccount, $sId, $sColor);
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}	

	/**
	 * @return array
	 */
	public function AjaxCalendarDelete()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		
		$sCalendarId = $this->getParamValue('Id');
		$mResult = $this->oApiCalendar->deleteCalendar($oAccount, $sCalendarId);
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxCalendarShareUpdate()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		$sCalendarId = $this->getParamValue('Id');
		$bIsPublic = (bool) $this->getParamValue('IsPublic');
		$aShares = @json_decode($this->getParamValue('Shares'), true);
		
		$bShareToAll = (bool) $this->getParamValue('ShareToAll', false);
		$iShareToAllAccess = (int) $this->getParamValue('ShareToAllAccess', \ECalendarPermission::Read);
		
		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}
		
		// Share calendar to all users
		$aShares[] = array(
			'email' => $this->oApiCalendar->getTenantUser($oAccount),
			'access' => $bShareToAll ? $iShareToAllAccess : \ECalendarPermission::RemovePermission
		);
		
		// Public calendar
		$aShares[] = array(
			'email' => $this->oApiCalendar->getPublicUser(),
			'access' => $bIsPublic ? \ECalendarPermission::Read : \ECalendarPermission::RemovePermission
		);
		
		$mResult = $this->oApiCalendar->updateCalendarShares($oAccount, $sCalendarId, $aShares);
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}		
	
	/**
	 * @return array
	 */
	public function AjaxCalendarPublicUpdate()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		$sCalendarId = $this->getParamValue('Id');
		$bIsPublic = (bool) $this->getParamValue('IsPublic');
		
		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}
		
		$mResult = $this->oApiCalendar->publicCalendar($oAccount, $sCalendarId, $bIsPublic);
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}	
	
	/**
	 * @return array
	 */
	public function AjaxCalendarEventList()
	{
		$mResult = false;
		$oAccount = null;
		$aCalendarIds = @json_decode($this->getParamValue('CalendarIds'), true);
		$iStart = $this->getParamValue('Start'); 
		$iEnd = $this->getParamValue('End'); 
		$bIsPublic = (bool) $this->getParamValue('IsPublic'); 
		$iTimezoneOffset = $this->getParamValue('TimezoneOffset'); 
		$sTimezone = $this->getParamValue('Timezone'); 
		
		if ($bIsPublic)
		{
			$oPublicAccount = $this->oApiCalendar->getPublicAccount();
			$oPublicAccount->User->DefaultTimeZone = $iTimezoneOffset;
			$oPublicAccount->User->ClientTimeZone = $sTimezone;
			$mResult = $this->oApiCalendar->getEvents($oPublicAccount, $aCalendarIds, $iStart, $iEnd);
		}
		else
		{
			$oAccount = $this->getDefaultAccountFromParam();
			if (!$this->oApiCapability->isCalendarSupported($oAccount))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
			}
			$mResult = $this->oApiCalendar->getEvents($oAccount, $aCalendarIds, $iStart, $iEnd);
		}
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxCalendarEventBase()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}
		
		$sCalendarId = $this->getParamValue('calendarId');
		$sEventId = $this->getParamValue('uid');
		
		$mResult = $this->oApiCalendar->getBaseEvent($oAccount, $sCalendarId, $sEventId);
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}	
	
	/**
	 * @return array
	 */
	public function AjaxCalendarEventCreate()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}
		
		$oEvent = new \CEvent();

		$oEvent->IdCalendar = $this->getParamValue('newCalendarId');
		$oEvent->Name = $this->getParamValue('subject');
		$oEvent->Description = $this->getParamValue('description');
		$oEvent->Location = $this->getParamValue('location');
		$oEvent->Start = $this->getParamValue('startTS');
		$oEvent->End = $this->getParamValue('endTS');
		$oEvent->AllDay = (bool) $this->getParamValue('allDay');
		$oEvent->Alarms = @json_decode($this->getParamValue('alarms'), true);
		$oEvent->Attendees = @json_decode($this->getParamValue('attendees'), true);

		$aRRule = @json_decode($this->getParamValue('rrule'), true);
		if ($aRRule)
		{
			$oRRule = new \CRRule($oAccount);
			$oRRule->Populate($aRRule);
			$oEvent->RRule = $oRRule;
		}

		$mResult = $this->oApiCalendar->createEvent($oAccount, $oEvent);
		if ($mResult)
		{
			$iStart = $this->getParamValue('selectStart'); 
			$iEnd = $this->getParamValue('selectEnd'); 

			$mResult = $this->oApiCalendar->getExpandedEvent($oAccount, $oEvent->IdCalendar, $mResult, $iStart, $iEnd);
		}
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxCalendarEventUpdate()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}
		
		$sNewCalendarId = $this->getParamValue('newCalendarId'); 
		$oEvent = new \CEvent();

		$oEvent->IdCalendar = $this->getParamValue('calendarId');
		$oEvent->Id = $this->getParamValue('uid');
		$oEvent->Name = $this->getParamValue('subject');
		$oEvent->Description = $this->getParamValue('description');
		$oEvent->Location = $this->getParamValue('location');
		$oEvent->Start = $this->getParamValue('startTS');
		$oEvent->End = $this->getParamValue('endTS');
		$oEvent->AllDay = (bool) $this->getParamValue('allDay');
		$oEvent->Alarms = @json_decode($this->getParamValue('alarms'), true);
		$oEvent->Attendees = @json_decode($this->getParamValue('attendees'), true);
		
		$aRRule = @json_decode($this->getParamValue('rrule'), true);
		if ($aRRule)
		{
			$oRRule = new \CRRule($oAccount);
			$oRRule->Populate($aRRule);
			$oEvent->RRule = $oRRule;
		}
		
		$iAllEvents = (int) $this->getParamValue('allEvents');
		$sRecurrenceId = $this->getParamValue('recurrenceId');
		
		if ($iAllEvents && $iAllEvents === 1)
		{
			$mResult = $this->oApiCalendar->updateExclusion($oAccount, $oEvent, $sRecurrenceId);
		}
		else
		{
			$mResult = $this->oApiCalendar->updateEvent($oAccount, $oEvent);
			if ($mResult && $sNewCalendarId !== $oEvent->IdCalendar)
			{
				$mResult = $this->oApiCalendar->moveEvent($oAccount, $oEvent->IdCalendar, $sNewCalendarId, $oEvent->Id);
				$oEvent->IdCalendar = $sNewCalendarId;
			}
		}
		if ($mResult)
		{
			$iStart = $this->getParamValue('selectStart'); 
			$iEnd = $this->getParamValue('selectEnd'); 

			$mResult = $this->oApiCalendar->getExpandedEvent($oAccount, $oEvent->IdCalendar, $oEvent->Id, $iStart, $iEnd);
		}
			
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}	
	
	/**
	 * @return array
	 */
	public function AjaxCalendarEventAppointmentUpdate()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isCalendarSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CalendarsNotAllowed);
		}
		
		$sCalendarId = $this->getParamValue('calendarId');
		$sEventId = $this->getParamValue('uid');
		$sAttendee = $this->getParamValue('attendee');
		$iAction = (int)$this->getParamValue('actionAppointment');
		
		$sAction = '';
		if ($iAction === \EAttendeeStatus::Accepted)
		{
			$sAction = 'ACCEPTED';
		}
		else if ($iAction === \EAttendeeStatus::Declined)
		{
			$sAction = 'DECLINED';
		}
		else if ($iAction === \EAttendeeStatus::Tentative)
		{
			$sAction = 'TENTATIVE';
		}
		
		$mResult = $this->oApiCalendar->updateAppointment($oAccount, $sCalendarId, $sEventId, $sAttendee, $sAction);
			
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}	
	
	/**
	 * @return array
	 */
	public function AjaxCalendarEventDelete()
	{
		$mResult = false;
		$oAccount = $this->getDefaultAccountFromParam();
		
		$sCalendarId = $this->getParamValue('calendarId');
		$sId = $this->getParamValue('uid');

		$iAllEvents = (int) $this->getParamValue('allEvents');
		
		if ($iAllEvents && $iAllEvents === 1)
		{
			$oEvent = new \CEvent();
			$oEvent->IdCalendar = $sCalendarId;
			$oEvent->Id = $sId;
			
			$sRecurrenceId = $this->getParamValue('recurrenceId');

			$mResult = $this->oApiCalendar->updateExclusion($oAccount, $oEvent, $sRecurrenceId, true);
		}
		else
		{
			$mResult = $this->oApiCalendar->deleteEvent($oAccount, $sCalendarId, $sId);
		}
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxSystemLogout()
	{
		setcookie('aft-cache-ctrl', '', time() - 3600);
		$sAuthToken = (string) $this->getParamValue('AuthToken', '');
		$oAccount = $this->getAccountFromParam(false);

		if ($oAccount && $oAccount->User && 0 < $oAccount->User->IdHelpdeskUser &&
			$this->oApiCapability->isHelpdeskSupported($oAccount))
		{
			$this->oApiIntegrator->logoutHelpdeskUser();
		}

		$sLastErrorCode = $this->getParamValue('LastErrorCode');
		if (0 < strlen($sLastErrorCode) && $this->oApiIntegrator && 0 < (int) $sLastErrorCode)
		{
			$this->oApiIntegrator->setLastErrorCode((int) $sLastErrorCode);
		}

		\CApi::LogEvent(\EEvents::Logout, $oAccount);
		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiIntegrator->logoutAccount($sAuthToken));
	}

	/**
	 * @param string $iError
	 *
	 * @return string
	 */
	public function convertUploadErrorToString($iError)
	{
		$sError = 'unknown';
		switch ($iError)
		{
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$sError = 'size';
				break;
		}

		return $sError;
	}

	/**
	 * @return bool
	 */
	private function rawFiles($bDownload = true, $bThumbnail = false)
	{
		$sRawKey = (string) $this->getParamValue('RawKey', '');
		$aValues = \CApi::DecodeKeyValues($sRawKey);

		if ($bThumbnail)
		{
			$this->verifyCacheByKey($sRawKey);
		}
		
		$sHash = (string) $this->getParamValue('TenantHash', '');
		$oMin = \CApi::Manager('min');

		$mMin = $oMin->getMinByHash($sHash);
		$oAccount = null;
		if (!empty($mMin['__hash__']))
		{
			$oAccount = $this->oApiUsers->getAccountById($mMin['Account']);
		}
		else
		{
			if (isset($aValues['Iframed'], $aValues['Time']) && $aValues['Iframed'] && $aValues['Time'])
			{
				$oAccount = $this->getAccountFromParam(true,
					!($aValues['Time'] > \ProjectCore\Base\Utils::iframedTimestamp())
				);

				if (!$oAccount->IsDefaultAccount)
				{
					$iAccountId = $this->oApiUsers->getDefaultAccountId($oAccount->IdUser);
					if (0 < $iAccountId)
					{
						$oAccount = $this->oApiUsers->getAccountById($iAccountId);
					}
					else
					{
						throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AuthError);
					}
				}
			}
			else
			{
				$oAccount = $this->getDefaultAccountFromParam();
			}
		}

		$oTenant = null;
		if ($oAccount && $this->oApiTenants)
		{
			$oTenant = (0 < $oAccount->IdTenant) ? $this->oApiTenants->getTenantById($oAccount->IdTenant) :
				$this->oApiTenants->getDefaultGlobalTenant();
		}
		
		if ($this->oApiCapability->isFilesSupported($oAccount) && $oTenant &&
			isset($aValues['Type'], $aValues['Path'], $aValues['Name']))
		{
			$mResult = false;
			
			$sFileName = $aValues['Name'];
			$sContentType = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
			
			$oFileInfo = $this->oApiFilestorage->getFileInfo($oAccount, $aValues['Type'], $aValues['Path'], $aValues['Name']);
			if ($oFileInfo->IsLink)
			{
				$iLinkType = \api_Utils::GetLinkType($oFileInfo->LinkUrl);

				if (isset($iLinkType))
				{
					if (\EFileStorageLinkType::GoogleDrive === $iLinkType)
					{
						$oSocial = $oTenant->getSocialByName('google');
						if ($oSocial)
						{
							$oInfo = \api_Utils::GetGoogleDriveFileInfo($oFileInfo->LinkUrl, $oSocial->SocialApiKey);
							$sFileName = isset($oInfo->title) ? $oInfo->title : $sFileName;
							$sContentType = \MailSo\Base\Utils::MimeContentType($sFileName);

							if (isset($oInfo->downloadUrl))
							{
								$mResult = \MailSo\Base\ResourceRegistry::CreateMemoryResource();
								$this->oHttp->SaveUrlToFile($oInfo->downloadUrl, $mResult);
								rewind($mResult);
							}
						}
					}
					else/* if (\EFileStorageLinkType::DropBox === (int)$aFileInfo['LinkType'])*/
					{
						if (\EFileStorageLinkType::DropBox === $iLinkType)
						{
							$oFileInfo->LinkUrl = str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $oFileInfo->LinkUrl);
						}
						$mResult = \MailSo\Base\ResourceRegistry::CreateMemoryResource();
						$sFileName = basename($oFileInfo->LinkUrl);
						$sContentType = \MailSo\Base\Utils::MimeContentType($sFileName);
						
						$this->oHttp->SaveUrlToFile($oFileInfo->LinkUrl, $mResult);
						rewind($mResult);
					}
				}
			}
			else
			{
				$mResult = $this->oApiFilestorage->getFile($oAccount, $aValues['Type'], $aValues['Path'], $aValues['Name']);
			}
			if (false !== $mResult)
			{
				if (is_resource($mResult))
				{
					$sFileName = $this->clearFileName($oFileInfo->Name, $sContentType);
					$sContentType = \MailSo\Base\Utils::MimeContentType($sFileName);
					$this->RawOutputHeaders($bDownload, $sContentType, $sFileName);
			
					if ($bThumbnail)
					{
						$this->cacheByKey($sRawKey);
						$this->thumbResource($oAccount, $mResult, $sFileName);
					}
					else
					{
						if ($sContentType === 'text/html')
						{
							echo(\MailSo\Base\HtmlUtils::ClearHtmlSimple(stream_get_contents($mResult)));
						}
						else
						{
							\MailSo\Base\Utils::FpassthruWithTimeLimitReset($mResult);
						}
					}
					
					@fclose($mResult);
				}

				return true;
			}
		}

		return false;
	}
	
	/**
	 * @return array
	 */
	public function RawFilesDownload()
	{
		return $this->rawFiles(true);
	}
	
	/**
	 * @return array
	 */
	public function RawFilesThumbnail()
	{
		return $this->rawFiles(false, true);
	}

	/**
	 * @return array
	 */
	public function RawFilesView()
	{
		return $this->rawFiles(false);
	}

	/**
	 * @return array
	 */
	public function RawFilesPub()
	{
		return $this->rawFiles(true);
	}

	/**
	 * @return array
	 */
	public function WindowPublicCalendar()
	{
//		$sRawKey = (string) $this->getParamValue('RawKey', '');
//		$aValues = \CApi::DecodeKeyValues($sRawKey);
//		print_r($aValues);
		
		$sUrlRewriteBase = (string) \CApi::GetConf('labs.server-url-rewrite-base', '');
		if (!empty($sUrlRewriteBase))
		{
			$sUrlRewriteBase = '<base href="'.$sUrlRewriteBase.'" />';
		}
		
		return array(
			'Template' => 'templates/CalendarPub.html',
			'{{BaseUrl}}' => $sUrlRewriteBase 
		);
	}
	
	/**
	 * @return array
	 */
	public function MinInfo()
	{
		$mData = $this->getParamValue('Result', false);

		var_dump($mData);
		return true;
	}

	/**
	 * @return array
	 */
	public function MinShare()
	{
		$mData = $this->getParamValue('Result', false);

		if ($mData && isset($mData['__hash__'], $mData['Name'], $mData['Size']))
		{
			$bUseUrlRewrite = (bool) \CApi::GetConf('labs.server-use-url-rewrite', false);			
			$sUrl = '?/Min/Download/';
			if ($bUseUrlRewrite)
			{
				$sUrl = '/download/';
			}
			
			$sUrlRewriteBase = (string) \CApi::GetConf('labs.server-url-rewrite-base', '');
			if (!empty($sUrlRewriteBase))
			{
				$sUrlRewriteBase = '<base href="'.$sUrlRewriteBase.'" />';
			}
		
			return array(
				'Template' => 'templates/FilesPub.html',
				'{{Url}}' => $sUrl.$mData['__hash__'], 
				'{{FileName}}' => $mData['Name'],
				'{{FileSize}}' => \api_Utils::GetFriendlySize($mData['Size']),
				'{{FileType}}' => \api_Utils::GetFileExtension($mData['Name']),
				'{{BaseUrl}}' => $sUrlRewriteBase 
			);
		}
		return false;
	}
	
	public function MinDownload()
	{
		$mData = $this->getParamValue('Result', false);

		if (isset($mData['AccountType']) && 'wm' !== $mData['AccountType'])
		{
			return true;
		}

		$oAccount = $this->oApiUsers->getAccountById((int) $mData['Account']);

		$mResult = false;
		if ($oAccount && $this->oApiCapability->isFilesSupported($oAccount))
		{
			$mResult = $this->oApiFilestorage->getSharedFile($oAccount, $mData['Type'], $mData['Path'], $mData['Name']);
		}
		
		if (false !== $mResult)
		{
			if (is_resource($mResult))
			{
				$sFileName = $mData['Name'];
				$sContentType = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
				$sFileName = $this->clearFileName($sFileName, $sContentType);
				$this->RawOutputHeaders(true, $sContentType, $sFileName);

				\MailSo\Base\Utils::FpassthruWithTimeLimitReset($mResult);
				@fclose($mResult);
			}
		}
		
		return true;
	}

	public function PathInfoDav()
	{
		set_error_handler(function ($errno, $errstr, $errfile, $errline ) {
			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		});

		@set_time_limit(3000);

		$sBaseUri = '/';
		if (false !== \strpos($this->oHttp->GetUrl(), 'index.php/dav/'))
		{
			$aPath = \trim($this->oHttp->GetPath(), '/\\ ');
			$sBaseUri = (0 < \strlen($aPath) ? '/'.$aPath : '').'/index.php/dav/';
		}
		
		$server = \afterlogic\DAV\Server::NewInstance($sBaseUri);
		$server->exec();
	}

	/**
	 * @return array
	 */
	public function _rawContacts($sSyncType)
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if ($this->oApiCapability->isContactsSupported($oAccount))
		{
			$oApiContactsManager = $this->ApiContacts();
			if ($oApiContactsManager)
			{
				$sOutput = $oApiContactsManager->export($oAccount->IdUser, $sSyncType);
				if (false !== $sOutput)
				{
					header('Pragma: public');
					header('Content-Type: text/csv');
					header('Content-Disposition: attachment; filename="export.' . $sSyncType . '";');
					header('Content-Transfer-Encoding: binary');

					echo $sOutput;
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * @return array
	 */
	public function RawContactscsv()
	{
		return $this->_rawContacts('csv');
	}
	
	/**
	 * @return array
	 */
	public function RawContactsvcf()
	{
		return $this->_rawContacts('vcf');
	}	

	/**
	 * @todo
	 * @return array
	 */
	public function RawCalendars()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if ($this->oApiCapability->isCalendarSupported($oAccount))
		{
			$sRawKey = (string) $this->getParamValue('RawKey', '');
			$aValues = \CApi::DecodeKeyValues($sRawKey);

			if (isset($aValues['CalendarId']))
			{
				$sCalendarId = $aValues['CalendarId'];

				$oApiCalendarManager = /* @var $oApiCalendarManager \CApiCalendarManager */ \CApi::Manager('calendar');
				$sOutput = $oApiCalendarManager->exportCalendarToIcs($oAccount, $sCalendarId);
				if (false !== $sOutput)
				{
					header('Pragma: public');
					header('Content-Type: text/calendar');
					header('Content-Disposition: attachment; filename="'.$sCalendarId.'.ics";');
					header('Content-Transfer-Encoding: binary');

					echo $sOutput;
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function UploadContacts()
	{
		$oAccount = $this->getDefaultAccountFromParam();

		if (!$this->oApiCapability->isPersonalContactsSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::ContactsNotAllowed);
		}
		
		$aFileData = $this->getParamValue('FileData', null);
		$sAdditionalData = $this->getParamValue('AdditionalData', '{}');
		$aAdditionalData = @json_decode($sAdditionalData, true);

		$sError = '';
		$aResponse = array(
			'ImportedCount' => 0,
			'ParsedCount' => 0
		);

		if (is_array($aFileData))
		{
			$sFileType = strtolower(\api_Utils::GetFileExtension($aFileData['name']));
			$bIsCsvVcfExtension  = $sFileType === 'csv' || $sFileType === 'vcf';

			if ($bIsCsvVcfExtension)
			{
				$sSavedName = 'import-post-' . md5($aFileData['name'] . $aFileData['tmp_name']);
				
				if (is_resource($aFileData['tmp_name']))
				{
					$this->ApiFileCache()->putFile($oAccount, $sSavedName, $aFileData['tmp_name']);
				}
				else
				{
					$this->ApiFileCache()->moveUploadedFile($oAccount, $sSavedName, $aFileData['tmp_name']);
				}
				if ($this->ApiFileCache()->isFileExists($oAccount, $sSavedName))
				{
					$oApiContactsManager = $this->ApiContacts();
					if ($oApiContactsManager)
					{
						$iParsedCount = 0;

						$iImportedCount = $oApiContactsManager->import(
							$oAccount->IdUser,
							$sFileType,
							$this->ApiFileCache()->generateFullFilePath($oAccount, $sSavedName),
							$iParsedCount,
							$aAdditionalData['GroupId'],
							$aAdditionalData['IsShared']
						);
					}

					if (false !== $iImportedCount && -1 !== $iImportedCount)
					{
						$aResponse['ImportedCount'] = $iImportedCount;
						$aResponse['ParsedCount'] = $iParsedCount;
					}
					else
					{
						$sError = 'unknown';
					}

					$this->ApiFileCache()->clear($oAccount, $sSavedName);
				}
				else
				{
					$sError = 'unknown';
				}
			}
			else
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::IncorrectFileExtension);
			}
		}
		else
		{
			$sError = 'unknown';
		}

		if (0 < strlen($sError))
		{
			$aResponse['Error'] = $sError;
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResponse);
	}
	
	/**
	 * @return array
	 */
	public function UploadCalendars()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		
		$aFileData = $this->getParamValue('FileData', null);
		$sAdditionalData = $this->getParamValue('AdditionalData', '{}');
		$aAdditionalData = @json_decode($sAdditionalData, true);
		
		$sCalendarId = isset($aAdditionalData['CalendarID']) ? $aAdditionalData['CalendarID'] : '';

		$sError = '';
		$aResponse = array(
			'ImportedCount' => 0
		);

		if (is_array($aFileData))
		{
			$bIsIcsExtension  = strtolower(pathinfo($aFileData['name'], PATHINFO_EXTENSION)) === 'ics';

			if ($bIsIcsExtension)
			{
				$sSavedName = 'import-post-' . md5($aFileData['name'] . $aFileData['tmp_name']);
				if (is_resource($aFileData['tmp_name']))
				{
					$this->ApiFileCache()->putFile($oAccount, $sSavedName, $aFileData['tmp_name']);
				}
				else
				{
					$this->ApiFileCache()->moveUploadedFile($oAccount, $sSavedName, $aFileData['tmp_name']);
				}
				if ($this->ApiFileCache()->isFileExists($oAccount, $sSavedName))
				{
					$oApiCalendarManager = $this->oApiCalendar;
					if ($oApiCalendarManager) 
					{
						$iImportedCount = $oApiCalendarManager->importToCalendarFromIcs($oAccount, $sCalendarId, $this->ApiFileCache()->generateFullFilePath($oAccount, $sSavedName));
					}

					if (false !== $iImportedCount && -1 !== $iImportedCount) 
					{
						$aResponse['ImportedCount'] = $iImportedCount;
					} 
					else
					{
						$sError = 'unknown';
					}

					$this->ApiFileCache()->clear($oAccount, $sSavedName);
				}
				else
				{
					$sError = 'unknown';
				}
			}
			else
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::IncorrectFileExtension);
			}
		}
		else
		{
			$sError = 'unknown';
		}

		if (0 < strlen($sError))
		{
			$aResponse['Error'] = $sError;
		}		
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResponse);
	}	

	/**
	 * @return array
	 */
	public function UploadAttachment()
	{
		$oAccount = $this->getAccountFromParam();

		$oSettings =& \CApi::GetSettings();
		$aFileData = $this->getParamValue('FileData', null);

		$iSizeLimit = !!$oSettings->GetConf('WebMail/EnableAttachmentSizeLimit', false) ?
			(int) $oSettings->GetConf('WebMail/AttachmentSizeLimit', 0) : 0;

		$sError = '';
		$aResponse = array();

		if ($oAccount)
		{
			if (is_array($aFileData))
			{
				if (0 < $iSizeLimit && $iSizeLimit < (int) $aFileData['size'])
				{
					$sError = 'size';
				}
				else
				{
					$sSavedName = 'upload-post-'.md5($aFileData['name'].$aFileData['tmp_name']);
					if (is_resource($aFileData['tmp_name']))
					{
						$this->ApiFileCache()->putFile($oAccount, $sSavedName, $aFileData['tmp_name']);
					}
					else
					{
						$this->ApiFileCache()->moveUploadedFile($oAccount, $sSavedName, $aFileData['tmp_name']);
					}
					if ($this->ApiFileCache()->isFileExists($oAccount, $sSavedName))
					{
						$sUploadName = $aFileData['name'];
						$iSize = $aFileData['size'];
						$sMimeType = \MailSo\Base\Utils::MimeContentType($sUploadName);

						$bIframed = \CApi::isIframedMimeTypeSupported($sMimeType, $sUploadName);
						$aResponse['Attachment'] = array(
							'Name' => $sUploadName,
							'TempName' => $sSavedName,
							'MimeType' => $sMimeType,
							'Size' =>  (int) $iSize,
							'Iframed' => $bIframed,
							'Hash' => \CApi::EncodeKeyValues(array(
								'TempFile' => true,
								'AccountID' => $oAccount->IdAccount,
								'Iframed' => $bIframed,
								'Name' => $sUploadName,
								'TempName' => $sSavedName
							))
						);
					}
					else
					{
						$sError = 'unknown';
					}
				}
			}
			else
			{
				$sError = 'unknown';
			}
		}
		else
		{
			$sError = 'auth';
		}

		if (0 < strlen($sError))
		{
			$aResponse['Error'] = $sError;
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResponse);
	}
	
	/**
	 * @return array
	 */
	public function UploadFile()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if (!$this->oApiCapability->isFilesSupported($oAccount))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::FilesNotAllowed);
		}

		$aFileData = $this->getParamValue('FileData', null);
		$mAdditionalData = $this->getParamValue('AdditionalData', '{}');
		$mAdditionalData = @json_decode($mAdditionalData, true);

		$sError = '';
		$aResponse = array();

		if ($oAccount)
		{
			if (is_array($aFileData))
			{
				$sUploadName = $aFileData['name'];
				$iSize = (int) $aFileData['size'];
				$sType = isset($mAdditionalData['Type']) ? $mAdditionalData['Type'] : 'personal';
				$sPath = isset($mAdditionalData['Path']) ? $mAdditionalData['Path'] : '';
				$sMimeType = \MailSo\Base\Utils::MimeContentType($sUploadName);

				$sSavedName = 'upload-post-'.md5($aFileData['name'].$aFileData['tmp_name']);
				$rData = false;
				if (is_resource($aFileData['tmp_name']))
				{
					$rData = $aFileData['tmp_name'];
				}
				else if ($this->ApiFileCache()->moveUploadedFile($oAccount, $sSavedName, $aFileData['tmp_name']))
				{
					$rData = $this->ApiFileCache()->getFile($oAccount, $sSavedName);
				}
				if ($rData)
				{
					$this->oApiFilestorage->createFile($oAccount, $sType, $sPath, $sUploadName, $rData, false);

					$aResponse['File'] = array(
						'Name' => $sUploadName,
						'TempName' => $sSavedName,
						'MimeType' => $sMimeType,
						'Size' =>  (int) $iSize,
						'Hash' => \CApi::EncodeKeyValues(array(
							'TempFile' => true,
							'AccountID' => $oAccount->IdAccount,
							'Name' => $sUploadName,
							'TempName' => $sSavedName
						))
					);
				}
			}
		}
		else
		{
			$sError = 'auth';
		}

		if (0 < strlen($sError))
		{
			$aResponse['Error'] = $sError;
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResponse);
	}

	public function UploadMessage()
	{
		$aFileData = $this->getParamValue('FileData', null);
		$sAccountId = (int) $this->getParamValue('AccountID', '0');
		$sAdditionalData = $this->getParamValue('AdditionalData', '{}');
		$aAdditionalData = @json_decode($sAdditionalData, true);

		$oAccount = $sAccountId ? $this->getAccount($sAccountId, true, $this->getParamValue('AuthToken', null)) : $this->getDefaultAccountFromParam();

		$sError = '';
		$aResponse = array();

		if ($oAccount)
		{
			if (is_array($aFileData))
			{
				$sUploadName = $aFileData['name'];
				$bIsEmlExtension  = strtolower(pathinfo($sUploadName, PATHINFO_EXTENSION)) === 'eml';

				if ($bIsEmlExtension) {
					$sFolder = isset($aAdditionalData['Folder']) ? $aAdditionalData['Folder'] : '';
					$sMimeType = \MailSo\Base\Utils::MimeContentType($sUploadName);

					$sSavedName = 'upload-post-' . md5($aFileData['name'] . $aFileData['tmp_name']);
					if (is_resource($aFileData['tmp_name']))
					{
						$this->ApiFileCache()->putFile($oAccount, $sSavedName, $aFileData['tmp_name']);
					}
					else
					{
						$this->ApiFileCache()->moveUploadedFile($oAccount, $sSavedName, $aFileData['tmp_name']);
					}
					if ($this->ApiFileCache()->isFileExists($oAccount, $sSavedName))
					{
						$sSavedFullName = $this->ApiFileCache()->generateFullFilePath($oAccount, $sSavedName);
						$this->oApiMail->appendMessageFromFile($oAccount, $sSavedFullName, $sFolder);
					} 
					else 
					{
						$sError = 'unknown';
					}
				}
				else
				{
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::IncorrectFileExtension);
				}
			}
		}
		else
		{
			$sError = 'auth';
		}

		if (0 < strlen($sError))
		{
			$aResponse['Error'] = $sError;
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResponse);
	}

	/**
	 * @return array
	 */
	public function AjaxDataAsAttachmentUpload()
	{
		$oAccount = $this->getAccountFromParam();
		$oSettings =& \CApi::GetSettings();
		
		$sData = $this->getParamValue('Data', '');
		$sFileName = $this->getParamValue('FileName', '');
		
		$sError = '';
		$aResponse = array();

		if ($oAccount)
		{
			$iSizeLimit = !!$oSettings->GetConf('WebMail/EnableAttachmentSizeLimit', false) ?
				(int) $oSettings->GetConf('WebMail/AttachmentSizeLimit', 0) : 0;

			$iSize = strlen($sData);
			if (0 < $iSizeLimit && $iSizeLimit < $iSize)
			{
				$sError = 'size';
			}
			else
			{
				$sSavedName = 'data-upload-'.md5($sFileName.microtime(true).rand(10000, 99999));
				if ($this->ApiFileCache()->put($oAccount, $sSavedName, $sData))
				{
					$aResponse['Attachment'] = array(
						'Name' => $sFileName,
						'TempName' => $sSavedName,
						'MimeType' => \MailSo\Base\Utils::MimeContentType($sFileName),
						'Size' =>  $iSize,
						'Hash' => \CApi::EncodeKeyValues(array(
							'TempFile' => true,
							'AccountID' => $oAccount->IdAccount,
							'Name' => $sFileName,
							'TempName' => $sSavedName
						))
					);
				}
				else
				{
					$sError = 'unknown';
				}
			}
		}
		else
		{
			$sError = 'auth';
		}

		if (0 < strlen($sError))
		{
			$aResponse['Error'] = $sError;
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResponse);
	}

	/**
	 * @param bool $bDownload
	 * @param string $sContentType
	 * @param string $sFileName
	 *
	 * @return bool
	 */
	public function RawOutputHeaders($bDownload, $sContentType, $sFileName)
	{
		if ($bDownload)
		{
			header('Content-Type: '.$sContentType, true);
		}
		else
		{
			$aParts = explode('/', $sContentType, 2);
			if (in_array(strtolower($aParts[0]), array('image', 'video', 'audio')) ||
				in_array(strtolower($sContentType), array('application/pdf', 'application/x-pdf', 'text/html')))
			{
				header('Content-Type: '.$sContentType, true);
			}
			else
			{
				header('Content-Type: text/plain', true);
			}
		}

		header('Content-Disposition: '.($bDownload ? 'attachment' : 'inline' ).'; '.
			\trim(\MailSo\Base\Utils::EncodeHeaderUtf8AttributeValue('filename', $sFileName)), true);
		
		header('Accept-Ranges: none', true);
		header('Content-Transfer-Encoding: binary');
	}

	public function thumbResource($oAccount, $rResource, $sFileName)
	{
		$sMd5Hash = md5(rand(1000, 9999));

		$this->ApiFileCache()->putFile($oAccount, 'Raw/Thumbnail/'.$sMd5Hash, $rResource, '_'.$sFileName);
		if ($this->ApiFileCache()->isFileExists($oAccount, 'Raw/Thumbnail/'.$sMd5Hash, '_'.$sFileName))
		{
			try
			{
				$oThumb = new \PHPThumb\GD(
					$this->ApiFileCache()->generateFullFilePath($oAccount, 'Raw/Thumbnail/'.$sMd5Hash, '_'.$sFileName)
				);

				$oThumb->adaptiveResize(120, 100)->show();
			}
			catch (\Exception $oE) {}
		}

		$this->ApiFileCache()->clear($oAccount, 'Raw/Thumbnail/'.$sMd5Hash, '_'.$sFileName);
	}

	/**
	 * @return bool
	 */
	private function rawCallback($sRawKey, $fCallback, $bCache = true, &$oAccount = null, &$oHelpdeskUser = null)
	{
		$aValues = \CApi::DecodeKeyValues($sRawKey);
		
		$sFolder = '';
		$iUid = 0;
		$sMimeIndex = '';

		$oAccount = null;
		$oHelpdeskUser = null;
		$oHelpdeskUserFromAttachment = null;

		if (isset($aValues['HelpdeskUserID'], $aValues['HelpdeskTenantID']))
		{
			$oAccount = null;
			$oHelpdeskUser = $this->getHelpdeskAccountFromParam($oAccount);

			if ($oHelpdeskUser && $oHelpdeskUser->IdTenant === $aValues['HelpdeskTenantID'])
			{
				$oApiHelpdesk = $this->ApiHelpdesk();
				if ($oApiHelpdesk)
				{
					if ($oHelpdeskUser->IdHelpdeskUser === $aValues['HelpdeskUserID'])
					{
						$oHelpdeskUserFromAttachment = $oHelpdeskUser;
					}
					else if ($oHelpdeskUser->IsAgent)
					{
						$oHelpdeskUserFromAttachment = $oApiHelpdesk->getUserById($aValues['HelpdeskTenantID'], $aValues['HelpdeskUserID']);
					}
				}
			}
		}
		else if (isset($aValues['AccountID']))
		{
			$oAccount = $this->getAccountFromParam(true,
				!(isset($aValues['Iframed'], $aValues['Time']) && $aValues['Iframed'] && $aValues['Time'] > \ProjectCore\Base\Utils::iframedTimestamp())
			);
			
			if (!$oAccount || $aValues['AccountID'] !== $oAccount->IdAccount)
			{
				return false;
			}
		}

		if ($oHelpdeskUserFromAttachment && isset($aValues['FilestorageFile'], $aValues['StorageType'], $aValues['Path'], $aValues['Name']))
		{
			if ($bCache)
			{
				$this->verifyCacheByKey($sRawKey);
			}
			
			$bResult = false;
			$mResult = false;
			
			$sStorageType = $aValues['StorageType'];
			if (is_numeric($aValues['StorageType']))
			{
				$iStorageType = (int) $aValues['StorageType'];
				switch ($iStorageType)
				{
					case \EFileStorageType::Personal: 
						$sStorageType = \EFileStorageTypeStr::Personal;
						break;
					case \EFileStorageType::Corporate: 
						$sStorageType = \EFileStorageTypeStr::Corporate;
						break;
					case \EFileStorageType::Shared: 
						$sStorageType = \EFileStorageTypeStr::Shared;
						break;
				}
			}
					
			if ($this->oApiFilestorage->isFileExists(
				$oHelpdeskUserFromAttachment,
				$sStorageType, $aValues['Path'], $aValues['Name']
			))
			{
				$mResult = $this->oApiFilestorage->getFile(
					$oHelpdeskUserFromAttachment,
					$sStorageType, $aValues['Path'], $aValues['Name']
				);
				if (is_resource($mResult))
				{
					if ($bCache)
					{
						$this->cacheByKey($sRawKey);
					}

					$bResult = true;
					$sFileName = $aValues['Name'];

					$sContentType = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
					$sFileName = $this->clearFileName($sFileName, $sContentType);

					call_user_func_array($fCallback, array(
						$oAccount, $sContentType, $sFileName, $mResult, $oHelpdeskUser
					));
				}
			}
			else
			{
				$this->oHttp->StatusHeader(404);
				exit();
			}
			return $bResult;
		}
		else if (isset($aValues['TempFile'], $aValues['TempName'], $aValues['Name']) && ($oHelpdeskUserFromAttachment || $oAccount))
		{
			if ($bCache)
			{
				$this->verifyCacheByKey($sRawKey);
			}

			$bResult = false;
			$mResult = $this->ApiFileCache()->getFile($oHelpdeskUserFromAttachment ? $oHelpdeskUserFromAttachment : $oAccount, $aValues['TempName']);

			if (is_resource($mResult))
			{
				if ($bCache)
				{
					$this->cacheByKey($sRawKey);
				}

				$bResult = true;
				$sFileName = $aValues['Name'];
				$sContentType = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
				$sFileName = $this->clearFileName($sFileName, $sContentType);

				call_user_func_array($fCallback, array(
					$oAccount, $sContentType, $sFileName, $mResult, $oHelpdeskUser
				));
			}

			return $bResult;
		}
		else
		{
			$sFolder = isset($aValues['Folder']) ? $aValues['Folder'] : '';
			$iUid = (int) (isset($aValues['Uid']) ? $aValues['Uid'] : 0);
			$sMimeIndex = (string) (isset($aValues['MimeIndex']) ? $aValues['MimeIndex'] : '');
		}

		if ($bCache && 0 < strlen($sFolder) && 0 < $iUid)
		{
			$this->verifyCacheByKey($sRawKey);
		}

		$sContentTypeIn = (string) (isset($aValues['MimeType']) ? $aValues['MimeType'] : '');
		$sFileNameIn = (string) (isset($aValues['FileName']) ? $aValues['FileName'] : '');

		if (!$oAccount)
		{
			return false;
		}

		$self = $this;
		return $this->oApiMail->directMessageToStream($oAccount,
			function($rResource, $sContentType, $sFileName, $sMimeIndex = '') use ($self, $oAccount, $fCallback, $sRawKey, $bCache, $sContentTypeIn, $sFileNameIn) {
				if (is_resource($rResource))
				{
					$sContentTypeOut = $sContentTypeIn;
					if (empty($sContentTypeOut))
					{
						$sContentTypeOut = $sContentType;
						if (empty($sContentTypeOut))
						{
							$sContentTypeOut = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
						}
					}

					$sFileNameOut = $sFileNameIn;
					if (empty($sFileNameOut) || '.' === $sFileNameOut{0})
					{
						$sFileNameOut = $sFileName;
					}

					$sFileNameOut = $self->clearFileName($sFileNameOut, $sContentType, $sMimeIndex);

					if ($bCache)
					{
						$self->cacheByKey($sRawKey);
					}

					call_user_func_array($fCallback, array(
						$oAccount, $sContentTypeOut, $sFileNameOut, $rResource
					));
				}
			}, $sFolder, $iUid, $sMimeIndex);
	}

	/**
	 * @return bool
	 */
	private function raw($bDownload = true, $bThumbnail = false)
	{
		$self = $this;
		return $this->rawCallback((string) $this->getParamValue('RawKey', ''), 
				function ($oAccount, $sContentType, $sFileName, $rResource, $oHelpdeskUser = null) use ($self, $bDownload, $bThumbnail) {
			
			$self->RawOutputHeaders($bDownload, $sContentType, $sFileName);

			if (!$bDownload && 'text/html' === $sContentType)
			{
				$sHtml = stream_get_contents($rResource);
				if ($sHtml)
				{
					$sCharset = '';
					$aMacth = array();
					if (preg_match('/charset[\s]?=[\s]?([^\s"\']+)/i', $sHtml, $aMacth) && !empty($aMacth[1]))
					{
						$sCharset = $aMacth[1];
					}

					if ('' !== $sCharset && \MailSo\Base\Enumerations\Charset::UTF_8 !== $sCharset)
					{
						$sHtml = \MailSo\Base\Utils::ConvertEncoding($sHtml,
							\MailSo\Base\Utils::NormalizeCharset($sCharset, true), \MailSo\Base\Enumerations\Charset::UTF_8);
					}

					include_once PSEVEN_APP_ROOT_PATH.'libraries/other/CssToInlineStyles.php';

					$oCssToInlineStyles = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($sHtml);
					$oCssToInlineStyles->setEncoding('utf-8');
					$oCssToInlineStyles->setUseInlineStylesBlock(true);

					echo '<html><head></head><body>'.
						\MailSo\Base\HtmlUtils::ClearHtmlSimple($oCssToInlineStyles->convert(), true, true).
						'</body></html>';
				}
			}
			else
			{
				if ($bThumbnail && !$bDownload)
				{
					$self->thumbResource($oAccount ? $oAccount : $oHelpdeskUser, $rResource, $sFileName);
				}
				else
				{
					\MailSo\Base\Utils::FpassthruWithTimeLimitReset($rResource);
				}
			}
			
		}, !$bDownload);
	}

	/**
	 * @return bool
	 */
	public function RawIframe()
	{
		$sEncodedUrl = $this->getParamValue('RawKey', '');
		$sUrl = urldecode($sEncodedUrl);
		$sUrl = trim(trim($sUrl), '/\\?');

		$aParts = null;
		if (!empty($sUrl))
		{
			$aParts = explode('/', $sUrl);
		}

		if (is_array($aParts) && isset($aParts[0], $aParts[1], $aParts[2], $aParts[3]))
		{
			$aValues = \CApi::DecodeKeyValues($aParts[3]);
			
			if (isset($aValues['Iframed'], $aValues['Name'], $aValues['AccountID']) &&
				(!isset($aValues['MimeType']) || !isset($aValues['FileName']))
			)
			{
				$aValues['FileName'] = $aValues['Name'];
				$aValues['MimeType'] = \api_Utils::MimeContentType($aValues['FileName']);
			}

			if (isset($aValues['Iframed'], $aValues['MimeType'], $aValues['FileName']) && $aValues['Iframed'] &&
				\CApi::isIframedMimeTypeSupported($aValues['MimeType'], $aValues['FileName']))
			{
				$sHash = isset($aParts[5]) ? (string) $aParts[5] : '';
				$oMin = \CApi::Manager('min');
				$mMin = $oMin->getMinByHash($sHash);
				$oAccount = null;
				if (!empty($mMin['__hash__']))
				{
					$oAccount = $this->oApiUsers->getAccountById($mMin['Account']);
				}
				else
				{
					$oAccount = $this->getAccountFromParam(false);
				}
				
				if ($oAccount)
				{
					$sNewUrl = '';
					$sNewHash = '';
					$sResultUrl = '';
					
					$aSubParts = \CApi::DecodeKeyValues($aParts[3]);

					if (isset($aSubParts['Iframed']))
					{
						$aSubParts['Time'] = \time();
						$sNewHash = \CApi::EncodeKeyValues($aSubParts);
					}

					if (!empty($sNewHash))
					{
						$aParts[3] = $sNewHash;
						$sNewUrl = rtrim(trim($this->oHttp->GetFullUrl()), '/').'/?/'.implode('/', $aParts);

						\CApi::Plugin()->RunHook('webmail.filter.iframed-attachments-url', array(&$sResultUrl, $sNewUrl, $aValues['MimeType'], $aValues['FileName']));

						if (empty($sResultUrl) && \CApi::GetConf('labs.allow-officeapps-viewer', true))
						{
							$sResultUrl = 'https://view.officeapps.live.com/op/view.aspx?src='.urlencode($sNewUrl);
						}
					}

					if (!empty($sResultUrl))
					{
						header('Content-Type: text/html', true);

						echo '<html style="height: 100%; width: 100%; margin: 0; padding: 0"><head></head><body'.
							' style="height: 100%; width: 100%; margin: 0; padding: 0">'.
							'<iframe style="height: 100%; width: 100%; margin: 0; padding: 0; border: 0" src="'.$sResultUrl.'"></iframe></body></html>';

						return true;
					}
				}
			}
		}
		
		return false;
	}

	/**
	 * @return bool
	 */
	public function RawView()
	{
		return $this->raw(false);
	}

	/**
	 * @return bool
	 */
	public function RawDownload()
	{
		return $this->raw(true);
	}

	/**
	 * @return bool
	 */
	public function RawThumbnail()
	{
		return $this->raw(false, true);
	}

	/**
	 * @return bool
	 */
	public function PostPdfFromHtml()
	{
		$oAccount = $this->getAccountFromParam();
		if ($oAccount)
		{
			$sSubject = (string) $this->getParamValue('Subject', '');
			$sHtml = (string) $this->getParamValue('Html', '');

			include_once PSEVEN_APP_ROOT_PATH.'libraries/other/CssToInlineStyles.php';

			$oCssToInlineStyles = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($sHtml);
			$oCssToInlineStyles->setEncoding('utf-8');
			$oCssToInlineStyles->setUseInlineStylesBlock(true);

			$this->RawOutputHeaders(true, 'application/pdf', $sSubject.'.pdf');

			include_once PSEVEN_APP_ROOT_PATH.'libraries/dompdf/dompdf_config.inc.php';

			$oDomPdf = new \DOMPDF();
			$oDomPdf->load_html('<html><head></head><body>'.
				\MailSo\Base\HtmlUtils::ClearHtmlSimple($oCssToInlineStyles->convert(), true, true).
				'</body></html>');
			
			$oDomPdf->render();
			$oDomPdf->stream($sSubject.'.pdf', array('Attachment' => false));

			return true;
		}
		
		return false;
	}

	/**
	 * @param string $sFileName
	 * @param string $sContentType
	 * @param string $sMimeIndex = ''
	 *
	 * @return string
	 */
	public function clearFileName($sFileName, $sContentType, $sMimeIndex = '')
	{
		$sFileName = 0 === strlen($sFileName) ? preg_replace('/[^a-zA-Z0-9]/', '.', (empty($sMimeIndex) ? '' : $sMimeIndex.'.').$sContentType) : $sFileName;
		$sClearedFileName = preg_replace('/[\s]+/', ' ', preg_replace('/[\.]+/', '.', $sFileName));
		$sExt = \MailSo\Base\Utils::GetFileExtension($sClearedFileName);

		$iSize = 100;
		if ($iSize < strlen($sClearedFileName) - strlen($sExt))
		{
			$sClearedFileName = substr($sClearedFileName, 0, $iSize).(empty($sExt) ? '' : '.'.$sExt);
		}

		return \MailSo\Base\Utils::ClearFileName(\MailSo\Base\Utils::Utf8Clear($sClearedFileName));
	}

	/**
	 * @param string $sKey
	 *
	 * @return void
	 */
	public function cacheByKey($sKey)
	{
		if (!empty($sKey))
		{
			$iUtcTimeStamp = time();
			$iExpireTime = 3600 * 24 * 5;

			header('Cache-Control: private', true);
			header('Pragma: private', true);
			header('Etag: '.md5('Etag:'.md5($sKey)), true);
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $iUtcTimeStamp - $iExpireTime).' UTC', true);
			header('Expires: '.gmdate('D, j M Y H:i:s', $iUtcTimeStamp + $iExpireTime).' UTC', true);
		}
	}

	/**
	 * @param string $sKey
	 *
	 * @return void
	 */
	public function verifyCacheByKey($sKey)
	{
		if (!empty($sKey))
		{
			$sIfModifiedSince = $this->oHttp->GetHeader('If-Modified-Since', '');
			if (!empty($sIfModifiedSince))
			{
				$this->oHttp->StatusHeader(304);
				$this->cacheByKey($sKey);
				exit();
			}
		}
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return array|null
	 */
	private function mobileSyncSettings($oAccount)
	{
		$mResult = null;
		$oApiDavManager = \CApi::Manager('dav');

		if ($oAccount && $oApiDavManager)
		{
			$oApiCapabilityManager = \CApi::Manager('capability');
			/* @var $oApiCapabilityManager \CApiCapabilityManager */

			$oApiCalendarManager = \CApi::Manager('calendar');

			$bEnableMobileSync = $oApiCapabilityManager->isMobileSyncSupported($oAccount);

			$mResult = array();

			$mResult['EnableDav'] = $bEnableMobileSync;

			$sDavLogin = $oApiDavManager->getLogin($oAccount);
			$sDavServer = $oApiDavManager->getServerUrl();

			$mResult['Dav'] = null;
			$mResult['ActiveSync'] = null;
			$mResult['DavError'] = '';

			$oException = $oApiDavManager->GetLastException();
			if (!$oException)
			{
				if ($bEnableMobileSync)
				{
					$mResult['Dav'] = array();
					$mResult['Dav']['Login'] = $sDavLogin;
					$mResult['Dav']['Server'] = $sDavServer;
					$mResult['Dav']['PrincipalUrl'] = '';

					$sPrincipalUrl = $oApiDavManager->getPrincipalUrl($oAccount);
					if ($sPrincipalUrl)
					{
						$mResult['Dav']['PrincipalUrl'] = $sPrincipalUrl;
					}

					$mResult['Dav']['Calendars'] = array();

					$aCalendars = $oApiCalendarManager ? $oApiCalendarManager->getCalendars($oAccount) : null;

//					if (isset($aCalendars['user']) && is_array($aCalendars['user']))
//					{
//						foreach($aCalendars['user'] as $aCalendar)
//						{
//							if (isset($aCalendar['name']) && isset($aCalendar['url']))
//							{
//								$mResult['Dav']['Calendars'][] = array(
//									'Name' => $aCalendar['name'],
//									'Url' => $sDavServer.$aCalendar['url']
//								);
//							}
//						}
//					}

					if (is_array($aCalendars) && 0 < count($aCalendars))
					{
						foreach($aCalendars as $aCalendar)
						{
							if (isset($aCalendar['Name']) && isset($aCalendar['Url']))
							{
								$mResult['Dav']['Calendars'][] = array(
									'Name' => $aCalendar['Name'],
									'Url' => $sDavServer.$aCalendar['Url']
								);
							}
						}
					}

					$mResult['Dav']['PersonalContactsUrl'] = $sDavServer.'/addressbooks/'.$sDavLogin.'/'.\afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME;
					$mResult['Dav']['CollectedAddressesUrl'] = $sDavServer.'/addressbooks/'.$sDavLogin.'/'.\afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME;
					$mResult['Dav']['SharedWithAllUrl'] = $sDavServer.'/addressbooks/'.$sDavLogin.'/'.\afterlogic\DAV\Constants::ADDRESSBOOK_SHARED_WITH_ALL_NAME;
					$mResult['Dav']['GlobalAddressBookUrl'] = $sDavServer.'/gab';
				}
			}
			else
			{
				$mResult['DavError'] = $oException->getMessage();
			}
		}

		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return array|null
	 */
	private function outlookSyncSettings($oAccount)
	{
		$mResult = null;
		if ($oAccount && $this->oApiCapability->isOutlookSyncSupported($oAccount))
		{
			/* @var $oApiDavManager \CApiDavManager */
			$oApiDavManager = \CApi::Manager('dav');

			$sLogin = $oApiDavManager->getLogin($oAccount);
			$sServerUrl = $oApiDavManager->getServerUrl();

			$mResult = array();
			$mResult['Login'] = '';
			$mResult['Server'] = '';
			$mResult['DavError'] = '';

			$oException = $oApiDavManager->GetLastException();
			if (!$oException)
			{
				$mResult['Login'] = $sLogin;
				$mResult['Server'] = $sServerUrl;
			}
			else
			{
				$mResult['DavError'] = $oException->getMessage();
			}
		}

		return $mResult;
	}
	
	/**
	 * @return array
	 */
	public function AjaxHelpdeskThreadsList()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);
		
		$iFilter = (int) $this->getParamValue('Filter', \EHelpdeskThreadFilterType::All);
		$sSearch = (string) $this->getParamValue('Search', '');
		$iOffset = (int) $this->getParamValue('Offset', 0);
		$iLimit = (int) $this->getParamValue('Limit', 10);

		$bIsAgent = $oUser->IsAgent;

		if (0 > $iOffset || 1 > $iLimit)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$aList = array();
		$iCount = $this->ApiHelpdesk()->getThreadsCount($oUser, $iFilter, $sSearch);
		if ($iCount)
		{
			$aList = $this->ApiHelpdesk()->getThreads($oUser, $iOffset, $iLimit, $iFilter, $sSearch);
		}

		$aOwnerIdList = array();
		if (is_array($aList) && 0 < count($aList))
		{
			foreach ($aList as &$oItem)
			{
				$aOwnerIdList[$oItem->IdOwner] = (int) $oItem->IdOwner;
			}
		}

		if (0 < count($aOwnerIdList))
		{
			$aOwnerIdList = array_values($aOwnerIdList);
			$aUserInfo = $this->ApiHelpdesk()->userInformation($oUser, $aOwnerIdList);

			if (is_array($aUserInfo) && 0 < count($aUserInfo))
			{
				foreach ($aList as &$oItem)
				{
					if ($oItem && isset($aUserInfo[$oItem->IdOwner]) && is_array($aUserInfo[$oItem->IdOwner]))
					{
						$sEmail = isset($aUserInfo[$oItem->IdOwner][0]) ? $aUserInfo[$oItem->IdOwner][0] : '';
						$sName = isset($aUserInfo[$oItem->IdOwner][1]) ? $aUserInfo[$oItem->IdOwner][1] : '';

						if (empty($sEmail) && !empty($aUserInfo[$oItem->IdOwner][3]))
						{
							$sEmail = $aUserInfo[$oItem->IdOwner][3];
						}

						if (!$bIsAgent && 0 < strlen($sName))
						{
							$sEmail = '';
						}
						
						$oItem->Owner = array($sEmail, $sName);
					}
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'Search' => $sSearch,
			'Filter' => $iFilter,
			'List' => $aList,
			'Offset' => $iOffset,
			'Limit' => $iLimit,
			'ItemsCount' =>  $iCount
		));
	}
	
	/**
	 * @return array
	 */
	public function AjaxHelpdeskThreadPosts()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		$bIsAgent = $oUser->IsAgent;

		$iThreadId = (int) $this->getParamValue('ThreadId', 0);
		$iStartFromId = (int) $this->getParamValue('StartFromId', 0);
		$iLimit = (int) $this->getParamValue('Limit', 10);
		$iIsExt = (int) $this->getParamValue('IsExt', 1);

		if (1 > $iThreadId || 0 > $iStartFromId || 1 > $iLimit)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oThread = $this->ApiHelpdesk()->getThreadById($oUser, $iThreadId);
		if (!$oThread)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$aList = $this->ApiHelpdesk()->getPosts($oUser, $oThread, $iStartFromId, $iLimit);
		$iExtPostsCount = $iIsExt ? $this->ApiHelpdesk()->getExtPostsCount($oUser, $oThread) : 0;

		$aIdList = array();
		if (is_array($aList) && 0 < count($aList))
		{
			foreach ($aList as &$oItem)
			{
				if ($oItem)
				{
					$aIdList[$oItem->IdOwner] = (int) $oItem->IdOwner;
				}
			}
		}

		$aIdList[$oThread->IdOwner] = (int) $oThread->IdOwner;

		if (0 < count($aIdList))
		{
			$aIdList = array_values($aIdList);
			$aUserInfo = $this->ApiHelpdesk()->userInformation($oUser, $aIdList);

			if (is_array($aUserInfo) && 0 < count($aUserInfo))
			{
				foreach ($aList as &$oItem)
				{
					if ($oItem && isset($aUserInfo[$oItem->IdOwner]) && is_array($aUserInfo[$oItem->IdOwner]))
					{
						$oItem->Owner = array(
							isset($aUserInfo[$oItem->IdOwner][0]) ? $aUserInfo[$oItem->IdOwner][0] : '',
							isset($aUserInfo[$oItem->IdOwner][1]) ? $aUserInfo[$oItem->IdOwner][1] : ''
						);

						if (empty($oItem->Owner[0]))
						{
							$oItem->Owner[0] = isset($aUserInfo[$oItem->IdOwner][3]) ? $aUserInfo[$oItem->IdOwner][3] : '';
						}

						if (!$bIsAgent && 0 < strlen($oItem->Owner[1]))
						{
							$oItem->Owner[0] = '';
						}

						$oItem->IsThreadOwner = $oThread->IdOwner === $oItem->IdOwner;
					}

					if ($oItem)
					{
						$oItem->ItsMe = $oUser->IdHelpdeskUser === $oItem->IdOwner;
					}
				}

				if (isset($aUserInfo[$oThread->IdOwner]) && is_array($aUserInfo[$oThread->IdOwner]))
				{
					$sEmail = isset($aUserInfo[$oThread->IdOwner][0]) ? $aUserInfo[$oThread->IdOwner][0] : '';
					$sName = isset($aUserInfo[$oThread->IdOwner][1]) ? $aUserInfo[$oThread->IdOwner][1] : '';

					if (!$bIsAgent && 0 < strlen($sName))
					{
						$sEmail = '';
					}

					$oThread->Owner = array($sEmail, $sName);
				}
			}
		}

		if ($oThread->HasAttachments)
		{
			$aAttachments = $this->ApiHelpdesk()->getAttachments($oUser, $oThread);
			if (is_array($aAttachments))
			{
				foreach ($aList as &$oItem)
				{
					if (isset($aAttachments[$oItem->IdHelpdeskPost]) && is_array($aAttachments[$oItem->IdHelpdeskPost]) &&
						0 < count($aAttachments[$oItem->IdHelpdeskPost]))
					{
						$oItem->Attachments = $aAttachments[$oItem->IdHelpdeskPost];

						foreach ($oItem->Attachments as $oAttachment)
						{
							if ($oAttachment && '.asc' === \strtolower(\substr(\trim($oAttachment->FileName), -4)))
							{
								$oAttachment->populateContent($oUser, $this->oApiHelpdesk, $this->oApiFilestorage);
							}
						}
					}
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'ThreadId' => $oThread->IdHelpdeskThread,
			'StartFromId' => $iStartFromId,
			'Limit' => $iLimit,
			'ItemsCount' => $iExtPostsCount ? $iExtPostsCount : ($oThread->PostCount > count($aList) ? $oThread->PostCount : count($aList)),
			'List' => $aList
		));
	}

	/**
	 * @return array
	 */
	public function AjaxHelpdeskThreadByIdOrHash()
	{
		$oAccount = null;
		$oThread = false;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		$bIsAgent = $oUser->IsAgent;

		$sThreadId = (int) $this->getParamValue('ThreadId', 0);
		$sThreadHash = (string) $this->getParamValue('ThreadHash', '');
		if (empty($sThreadHash) && $sThreadId === 0)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oApiHelpdesk = \CApi::Manager('helpdesk');
		if ($oApiHelpdesk)
		{
			$mHelpdeskThreadId = $sThreadId ? $sThreadId : $oApiHelpdesk->getThreadIdByHash($oUser->IdTenant, $sThreadHash);
			if (!is_int($mHelpdeskThreadId) || 1 > $mHelpdeskThreadId)
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$oThread = $this->ApiHelpdesk()->getThreadById($oUser, $mHelpdeskThreadId);
			if (!$oThread)
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$aUserInfo = $this->ApiHelpdesk()->userInformation($oUser, array($oThread->IdOwner));
			if (is_array($aUserInfo) && 0 < count($aUserInfo))
			{
				if (isset($aUserInfo[$oThread->IdOwner]) && is_array($aUserInfo[$oThread->IdOwner]))
				{
					$sEmail = isset($aUserInfo[$oThread->IdOwner][0]) ? $aUserInfo[$oThread->IdOwner][0] : '';
					$sName = isset($aUserInfo[$oThread->IdOwner][1]) ? $aUserInfo[$oThread->IdOwner][1] : '';

					if (empty($sEmail) && !empty($aUserInfo[$oThread->IdOwner][3]))
					{
						$sEmail = $aUserInfo[$oThread->IdOwner][3];
					}

					if (!$bIsAgent && 0 < strlen($sName))
					{
						$sEmail = '';
					}

					$oThread->Owner = array($sEmail, $sName);
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oThread);
	}

	/**
	 * @return array
	 */
	public function AjaxHelpdeskPostDelete()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		if (!$oUser)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AccessDenied);
		}

		$iThreadId = (int) $this->getParamValue('ThreadId', 0);
		$iPostId = (int) $this->getParamValue('PostId', 0);
		
		if (0 >= $iThreadId || 0 >= $iPostId)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oThread = $this->ApiHelpdesk()->getThreadById($oUser, $iThreadId);
		if (!$oThread)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		if (!$this->ApiHelpdesk()->verifyPostIdsBelongToUser($oUser, array($iPostId)))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AccessDenied);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__,
			$this->ApiHelpdesk()->deletePosts($oUser, $oThread, array($iPostId)));
	}
	
	/**
	 * @return array
	 */
	public function AjaxHelpdeskThreadDelete()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		if (!$oUser)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AccessDenied);
		}

		$iThreadId = (int) $this->getParamValue('ThreadId', '');

		if (0 < $iThreadId && !$oUser->IsAgent && !$this->ApiHelpdesk()->verifyThreadIdsBelongToUser($oUser, array($iThreadId)))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AccessDenied);
		}

		$bResult = false;
		if (0 < $iThreadId)
		{
			$bResult = $this->ApiHelpdesk()->archiveThreads($oUser, array($iThreadId));
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxHelpdeskThreadChangeState()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		$iThreadId = (int) $this->getParamValue('ThreadId', 0);
		$iThreadType = (int) $this->getParamValue('Type', \EHelpdeskThreadType::None);

		if (1 > $iThreadId || !in_array($iThreadType, array(
			\EHelpdeskThreadType::Pending,
			\EHelpdeskThreadType::Waiting,
			\EHelpdeskThreadType::Answered,
			\EHelpdeskThreadType::Resolved,
			\EHelpdeskThreadType::Deferred
		)))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		if (!$oUser || ($iThreadType !== \EHelpdeskThreadType::Resolved && !$oUser->IsAgent))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AccessDenied);
		}

		$bResult = false;
		$oThread = $this->ApiHelpdesk()->getThreadById($oUser, $iThreadId);
		if ($oThread)
		{
			$oThread->Type = $iThreadType;
			$bResult = $this->ApiHelpdesk()->updateThread($oUser, $oThread);
		}
		
		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
	}

	/**
	 * @return array
	 */
	public function AjaxHelpdeskIsAgent()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oUser && $oUser->IsAgent);
	}

	/**
	 * @return array
	 */
	public function AjaxHelpdeskSettingsUpdate()
	{
		setcookie('aft-cache-ctrl', '', time() - 3600);
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		$sName = (string) $this->oHttp->GetPost('Name', $oUser->Name);
		$sLanguage = (string) $this->oHttp->GetPost('Language', $oUser->Language);
		//$sLanguage = $this->validateLang($sLanguage);

		$sDateFormat = (string) $this->oHttp->GetPost('DateFormat', $oUser->DateFormat);
		$iTimeFormat = (int) $this->oHttp->GetPost('TimeFormat', $oUser->TimeFormat);

		$oUser->Name = trim($sName);
		$oUser->Language = trim($sLanguage);
		$oUser->DateFormat = $sDateFormat;
		$oUser->TimeFormat = $iTimeFormat;
		
		return $this->DefaultResponse($oAccount, __FUNCTION__,
			$this->ApiHelpdesk()->updateUser($oUser));
	}

	/**
	 * @return array
	 */
	public function AjaxHelpdeskUserPasswordUpdate()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		$sCurrentPassword = (string) $this->oHttp->GetPost('CurrentPassword', '');
		$sNewPassword = (string) $this->oHttp->GetPost('NewPassword', '');

		$bResult = false;
		if ($oUser && $oUser->validatePassword($sCurrentPassword) && 0 < strlen($sNewPassword))
		{
			$oUser->setPassword($sNewPassword);
			if (!$this->ApiHelpdesk()->updateUser($oUser))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::CanNotChangePassword);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
	}
	
	public function AjaxHelpdeskPostCreate()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);
		/* @var $oAccount CAccount */

		$iThreadId = (int) $this->getParamValue('ThreadId', 0);
		$sSubject = trim((string) $this->getParamValue('Subject', ''));
		$sText = trim((string) $this->getParamValue('Text', ''));
		$sCc = trim((string) $this->getParamValue('Cc', ''));
		$sBcc = trim((string) $this->getParamValue('Bcc', ''));
		$bIsInternal = '1' === (string) $this->getParamValue('IsInternal', '0');
		$mAttachments = $this->getParamValue('Attachments', null);
		
		if (0 === strlen($sText) || (0 === $iThreadId && 0 === strlen($sSubject)))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$mResult = false;
		$bIsNew = false;

		$oThread = null;
		if (0 === $iThreadId)
		{
			$bIsNew = true;
			
			$oThread = new \CHelpdeskThread();
			$oThread->IdTenant = $oUser->IdTenant;
			$oThread->IdOwner = $oUser->IdHelpdeskUser;
			$oThread->Type = \EHelpdeskThreadType::Pending;
			$oThread->Subject = $sSubject;

			if (!$this->ApiHelpdesk()->createThread($oUser, $oThread))
			{
				$oThread = null;
			}
		}
		else
		{
			$oThread = $this->ApiHelpdesk()->getThreadById($oUser, $iThreadId);
		}

		if ($oThread && 0 < $oThread->IdHelpdeskThread)
		{
			$oPost = new \CHelpdeskPost();
			$oPost->IdTenant = $oUser->IdTenant;
			$oPost->IdOwner = $oUser->IdHelpdeskUser;
			$oPost->IdHelpdeskThread = $oThread->IdHelpdeskThread;
			$oPost->Type = $bIsInternal ? \EHelpdeskPostType::Internal : \EHelpdeskPostType::Normal;
			$oPost->SystemType = \EHelpdeskPostSystemType::None;
			$oPost->Text = $sText;

			$aResultAttachment = array();
			if (is_array($mAttachments) && 0 < count($mAttachments))
			{
				foreach ($mAttachments as $sTempName => $sHash)
				{
					$aDecodeData = \CApi::DecodeKeyValues($sHash);
					if (!isset($aDecodeData['HelpdeskUserID']))
					{
						continue;
					}

					$rData = $this->ApiFileCache()->getFile($oUser, $sTempName);
					if ($rData)
					{
						$iFileSize = $this->ApiFileCache()->fileSize($oUser, $sTempName);

						$sThreadID = (string) $oThread->IdHelpdeskThread;
						$sThreadID = str_pad($sThreadID, 2, '0', STR_PAD_LEFT);
						$sThreadIDSubFolder = substr($sThreadID, 0, 2);

						$sThreadFolderName = API_HELPDESK_PUBLIC_NAME.'/'.$sThreadIDSubFolder.'/'.$sThreadID;

						$this->oApiFilestorage->createFolder($oUser, \EFileStorageTypeStr::Corporate, '',
							$sThreadFolderName);

						$sUploadName = isset($aDecodeData['Name']) ? $aDecodeData['Name'] : $sTempName;

						$this->oApiFilestorage->createFile($oUser,
							\EFileStorageTypeStr::Corporate, $sThreadFolderName, $sUploadName, $rData, false);

						$oAttachment = new \CHelpdeskAttachment();
						$oAttachment->IdHelpdeskThread = $oThread->IdHelpdeskThread;
						$oAttachment->IdHelpdeskPost = $oPost->IdHelpdeskPost;
						$oAttachment->IdOwner = $oUser->IdHelpdeskUser;
						$oAttachment->IdTenant = $oUser->IdTenant;

						$oAttachment->FileName = $sUploadName;
						$oAttachment->SizeInBytes = $iFileSize;
						$oAttachment->encodeHash($oUser, $sThreadFolderName);
						
						$aResultAttachment[] = $oAttachment;
					}
				}

				if (is_array($aResultAttachment) && 0 < count($aResultAttachment))
				{
					$oPost->Attachments = $aResultAttachment;
				}
			}

			$mResult = $this->ApiHelpdesk()->createPost($oUser, $oThread, $oPost, $bIsNew, true, $sCc, $sBcc);

			if ($mResult)
			{
				$mResult = array(
					'ThreadId' => $oThread->IdHelpdeskThread,
					'ThreadIsNew' => $bIsNew
				);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	public function AjaxHelpdeskThreadsPendingCount()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		if (!($oUser instanceof \CHelpdeskUser))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::HelpdeskUnknownUser);
		}


		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->ApiHelpdesk()->getThreadsPendingCount($oUser->IdTenant));
	}

	public function AjaxHelpdeskThreadPing()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		$iThreadId = (int) $this->getParamValue('ThreadId', 0);

		if (0 === $iThreadId)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$this->ApiHelpdesk()->setOnline($oUser, $iThreadId);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->ApiHelpdesk()->getOnline($oUser, $iThreadId));
	}

	public function AjaxHelpdeskThreadSeen()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		$iThreadId = (int) $this->getParamValue('ThreadId', 0);

		if (0 === $iThreadId)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}

		$oThread = $this->ApiHelpdesk()->getThreadById($oUser, $iThreadId);
		if (!$oThread)
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AccessDenied);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->ApiHelpdesk()->setThreadSeen($oUser, $oThread));
	}
	
	public function AjaxHelpdeskLogin()
	{
		setcookie('aft-cache-ctrl', '', time() - 3600);
		$sTenantHash = trim($this->getParamValue('TenantHash', ''));
		if ($this->oApiCapability->isHelpdeskSupported())
		{
			$sEmail = trim($this->getParamValue('Email', ''));
			$sPassword = trim($this->getParamValue('Password', ''));
			$bSignMe = '1' === (string) $this->getParamValue('SignMe', '0');

			if (0 === strlen($sEmail) || 0 === strlen($sPassword))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$mIdTenant = $this->oApiIntegrator->getTenantIdByHash($sTenantHash);
			if (!is_int($mIdTenant))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			try
			{
				$oHelpdeskUser = $this->oApiIntegrator->loginToHelpdeskAccount($mIdTenant, $sEmail, $sPassword);
				if ($oHelpdeskUser && !$oHelpdeskUser->Blocked)
				{
					$this->oApiIntegrator->setHelpdeskUserAsLoggedIn($oHelpdeskUser, $bSignMe);
					return $this->TrueResponse(null, __FUNCTION__);
				}
			}
			catch (\Exception $oException)
			{
				$iErrorCode = \ProjectCore\Notifications::UnknownError;
				if ($oException instanceof \CApiManagerException)
				{
					switch ($oException->getCode())
					{
						case \Errs::HelpdeskManager_AccountSystemAuthentication:
							$iErrorCode = \ProjectCore\Notifications::HelpdeskSystemUserExists;
							break;
						case \Errs::HelpdeskManager_AccountAuthentication:
							$iErrorCode = \ProjectCore\Notifications::AuthError;
							break;
						case \Errs::HelpdeskManager_UnactivatedUser:
							$iErrorCode = \ProjectCore\Notifications::HelpdeskUnactivatedUser;
							break;
						case \Errs::Db_ExceptionError:
							$iErrorCode = \ProjectCore\Notifications::DataBaseError;
							break;
					}
				}

				throw new \ProjectCore\Exceptions\ClientException($iErrorCode);
			}
		}

		return $this->FalseResponse(null, __FUNCTION__);
	}

	public function AjaxHelpdeskLogout()
	{
		setcookie('aft-cache-ctrl', '', time() - 3600);
		if ($this->oApiCapability->isHelpdeskSupported())
		{
			$this->oApiIntegrator->logoutHelpdeskUser();
		}

		return $this->TrueResponse(null, __FUNCTION__);
	}
	
	public function AjaxHelpdeskRegister()
	{
		$sTenantHash = trim($this->getParamValue('TenantHash', ''));
		if ($this->oApiCapability->isHelpdeskSupported())
		{
			$sEmail = trim($this->getParamValue('Email', ''));
			$sName = trim($this->getParamValue('Name', ''));
			$sPassword = trim($this->getParamValue('Password', ''));

			if (0 === strlen($sEmail) || 0 === strlen($sPassword))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$mIdTenant = $this->oApiIntegrator->getTenantIdByHash($sTenantHash);
			if (!is_int($mIdTenant))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$bResult = false;
			try
			{
				$bResult = !!$this->oApiIntegrator->registerHelpdeskAccount($mIdTenant, $sEmail, $sName, $sPassword);
			}
			catch (\Exception $oException)
			{
				$iErrorCode = \ProjectCore\Notifications::UnknownError;
				if ($oException instanceof \CApiManagerException)
				{
					switch ($oException->getCode())
					{
						case \Errs::HelpdeskManager_UserAlreadyExists:
							$iErrorCode = \ProjectCore\Notifications::HelpdeskUserAlreadyExists;
							break;
						case \Errs::HelpdeskManager_UserCreateFailed:
							$iErrorCode = \ProjectCore\Notifications::CanNotCreateHelpdeskUser;
							break;
						case \Errs::Db_ExceptionError:
							$iErrorCode = \ProjectCore\Notifications::DataBaseError;
							break;
					}
				}

				throw new \ProjectCore\Exceptions\ClientException($iErrorCode);
			}

			return $this->DefaultResponse(null, __FUNCTION__, $bResult);
		}

		return $this->FalseResponse(null, __FUNCTION__);
	}

	public function AjaxSocialRegister()
	{
		$sTenantHash = trim($this->getParamValue('TenantHash', ''));
		if ($this->oApiCapability->isHelpdeskSupported())
		{
			$sNotificationEmail = trim($this->getParamValue('NotificationEmail', ''));
			if(isset($_COOKIE["p7social"]))
			{
				$aSocial = \CApi::DecodeKeyValues($_COOKIE["p7social"]);
			}
			else
			{
				$aSocial = array(
					'type' => '',
					'id' => '',
					'name' => '',
					'email' => ''
				);
			}
			$sSocialType = $aSocial['type'];
			$sSocialId = $aSocial['id'];
			$sSocialName = $aSocial['name'];
			$sEmail = $aSocial['email'];

			if (0 !== strlen($sEmail))
			{
				$sNotificationEmail = $sEmail;
			}

			if (0 === strlen($sNotificationEmail))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$mIdTenant = $this->oApiIntegrator->getTenantIdByHash($sTenantHash);
			if (!is_int($mIdTenant))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$bResult = false;
			try
			{
				$bResult = $this->oApiIntegrator->registerSocialAccount($mIdTenant, $sTenantHash, $sNotificationEmail, $sSocialId, $sSocialType, $sSocialName);
			}
			catch (\Exception $oException)
			{
				$iErrorCode = \ProjectCore\Notifications::UnknownError;
				if ($oException instanceof \CApiManagerException)
				{
					switch ($oException->getCode())
					{
						case \Errs::HelpdeskManager_UserAlreadyExists:
							$iErrorCode = \ProjectCore\Notifications::HelpdeskUserAlreadyExists;
							break;
						case \Errs::HelpdeskManager_UserCreateFailed:
							$iErrorCode = \ProjectCore\Notifications::CanNotCreateHelpdeskUser;
							break;
						case \Errs::Db_ExceptionError:
							$iErrorCode = \ProjectCore\Notifications::DataBaseError;
							break;
					}
				}

				throw new \ProjectCore\Exceptions\ClientException($iErrorCode);
			}

			if ($bResult)
			{
				$bResult = false;
				$oUser = \CApi::Manager('integrator')->getAhdSocialUser($sTenantHash, $sSocialId);
				if ($oUser)
				{
					\CApi::Manager('integrator')->setHelpdeskUserAsLoggedIn($oUser, false);
					$bResult = true;
				}
			}

			return $this->DefaultResponse(null, __FUNCTION__, $bResult);
		}

		return $this->FalseResponse(null, __FUNCTION__);
	}
	
	public function AjaxSocialAccountGet()
	{
		$mResult = false;
		$oTenant = null;
		$oAccount = $this->GetDefaultAccount();
		$sType = trim($this->getParamValue('Type', ''));
		
		if ($oAccount && $this->oApiTenants)
		{
			$oTenant = (0 < $oAccount->IdTenant) ? $this->oApiTenants->getTenantById($oAccount->IdTenant) :
				$this->oApiTenants->getDefaultGlobalTenant();
		}
		if ($oTenant)
		{
			$oApiSocial /* @var $oApiSocial \CApiSocialManager */ = \CApi::Manager('social');
			$mResult = $oApiSocial->getSocial($oAccount->IdAccount, $sType);
		}
		return $this->DefaultResponse(null, __FUNCTION__, $mResult);
	}	
	
	public function AjaxHelpdeskForgot()
	{
		$sTenantHash = trim($this->getParamValue('TenantHash', ''));
		if ($this->oApiCapability->isHelpdeskSupported())
		{
			$sEmail = trim($this->getParamValue('Email', ''));

			if (0 === strlen($sEmail))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$mIdTenant = $this->oApiIntegrator->getTenantIdByHash($sTenantHash);
			if (!is_int($mIdTenant))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$oHelpdesk = $this->ApiHelpdesk();
			if ($oHelpdesk)
			{
				$oUser = $oHelpdesk->getUserByEmail($mIdTenant, $sEmail);
				if (!($oUser instanceof \CHelpdeskUser))
				{
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::HelpdeskUnknownUser);
				}

				return $this->DefaultResponse(null, __FUNCTION__, $oHelpdesk->forgotUser($oUser));
			}
		}
		
		return $this->FalseResponse(null, __FUNCTION__);
	}

	public function AjaxHelpdeskForgotChangePassword()
	{
		$sTenantHash = trim($this->getParamValue('TenantHash', ''));
		if ($this->oApiCapability->isHelpdeskSupported())
		{
			$sActivateHash = \trim($this->getParamValue('ActivateHash', ''));
			$sNewPassword = \trim($this->getParamValue('NewPassword', ''));

			if (0 === strlen($sNewPassword) || 0 === strlen($sActivateHash))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$mIdTenant = $this->oApiIntegrator->getTenantIdByHash($sTenantHash);
			if (!is_int($mIdTenant))
			{
				throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
			}

			$oHelpdesk = $this->ApiHelpdesk();
			if ($oHelpdesk)
			{
				$oUser = $oHelpdesk->getUserByActivateHash($mIdTenant, $sActivateHash);
				if (!($oUser instanceof \CHelpdeskUser))
				{
					throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::HelpdeskUnknownUser);
				}

				$oUser->Activated = true;
				$oUser->setPassword($sNewPassword);
				$oUser->regenerateActivateHash();

				return $this->DefaultResponse(null, __FUNCTION__, $oHelpdesk->updateUser($oUser));
			}
		}

		return $this->FalseResponse(null, __FUNCTION__);
	}
	
	/**
	 * @return array
	 */
	public function UploadHelpdeskFile()
	{
		$oAccount = null;
		$oUser = $this->getHelpdeskAccountFromParam($oAccount);

		if (!$this->oApiCapability->isHelpdeskSupported() || !$this->oApiCapability->isFilesSupported())
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AccessDenied);
		}

		$aFileData = $this->getParamValue('FileData', null);

		$iSizeLimit = 0;

		$sError = '';
		$aResponse = array();

		if ($oUser)
		{
			if (is_array($aFileData))
			{
				if (0 < $iSizeLimit && $iSizeLimit < (int) $aFileData['size'])
				{
					$sError = 'size';
				}
				else
				{
					$sSavedName = 'upload-post-'.md5($aFileData['name'].$aFileData['tmp_name']);
					if ($this->ApiFileCache()->moveUploadedFile($oUser, $sSavedName, $aFileData['tmp_name']))
					{
						$sUploadName = $aFileData['name'];
						$iSize = $aFileData['size'];
						$sMimeType = \MailSo\Base\Utils::MimeContentType($sUploadName);

						$aResponse['HelpdeskFile'] = array(
							'Name' => $sUploadName,
							'TempName' => $sSavedName,
							'MimeType' => $sMimeType,
							'Size' =>  (int) $iSize,
							'Hash' => \CApi::EncodeKeyValues(array(
								'TempFile' => true,
								'HelpdeskTenantID' => $oUser->IdTenant,
								'HelpdeskUserID' => $oUser->IdHelpdeskUser,
								'Name' => $sUploadName,
								'TempName' => $sSavedName
							))
						);
					}
					else
					{
						$sError = 'unknown';
					}
				}
			}
			else
			{
				$sError = 'unknown';
			}
		}
		else
		{
			$sError = 'auth';
		}

		if (0 < strlen($sError))
		{
			$aResponse['Error'] = $sError;
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResponse);
	}

	public function GetTwilio()
	{
		return $this->oApiTwilio;
	}
}
