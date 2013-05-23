<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	/**
	 * @param api_Http $oInput
	 */
	function RestoreAccountSessionFromAutoload($oInput)
	{
		CApi::Log('00');
		$iAccountId = CSession::Get(APP_SESSION_ACCOUNT_ID, false);
		if (false === $iAccountId)
		{
			$iAwmAutoLoginId = $oInput->GetCookie('awm_autologin_id', false);
			$iAwmAutoLoginSubId = $oInput->GetCookie('awm_autologin_subid', false);
			$sAwmAutoLoginData = $oInput->GetCookie('awm_autologin_data', false);

			CApi::Log('$iAwmAutoLoginId = '.$iAwmAutoLoginId);
			CApi::Log('$iAwmAutoLoginSubId = '.$iAwmAutoLoginSubId);
			CApi::Log('$sAwmAutoLoginData = '.$sAwmAutoLoginData);

			if (false !== $sAwmAutoLoginData && false !== $iAwmAutoLoginId)
			{
				$oAccount = $oDefAccount = null;
				if (false !== $iAwmAutoLoginSubId && (int) $iAwmAutoLoginId !== (int) $iAwmAutoLoginSubId)
				{
					CApi::Log('01');
					$oDefAccount = AppGetAccount($iAwmAutoLoginId);
					$oAccount = AppGetAccount($iAwmAutoLoginSubId);
					if (!$oDefAccount || !$oAccount || $oDefAccount->IdUser !== $oAccount->IdUser)
					{
						CApi::Log('02');
						$oAccount = null;
						$oDefAccount = null;
					}
				}
				else
				{
					CApi::Log('03');
					$oAccount = AppGetAccount($iAwmAutoLoginId);
					$oDefAccount = $oAccount;
				}

				CApi::Log('04');
				if ($oAccount && $oDefAccount && $sAwmAutoLoginData === md5(md5('AwM'.api_Utils::EncodePassword($oDefAccount->IncomingMailPassword))))
				{
					CApi::Log('05');
					/* @var $oApiWebmailManager CApiWebmailManager */
					$oApiWebmailManager = CApi::Manager('webmail');

					$aConnectErrors = array(false, false);
					if ($oApiWebmailManager->TestConnectionWithMailServer($aConnectErrors,
						$oAccount->IncomingMailProtocol, $oAccount->IncomingMailLogin, $oAccount->IncomingMailPassword,
						$oAccount->IncomingMailServer, $oAccount->IncomingMailPort, $oAccount->IncomingMailUseSSL))
					{
						CApi::Log('10');
						$oAccount->FillSession();
						$oApiWebmailManager->JumpToWebMail('webmail.php?check=1');
					}
					else
					{
						CApi::Log('11');
					}
				}
				else
				{
					CApi::Log('06');
				}
			}
		}
		else
		{
			CApi::Log('07');
			$oAccount = AppGetAccount($iAccountId);
			if ($oAccount)
			{
				CApi::Log('08');
				/* @var $oApiWebmailManager CApiWebmailManager */
				$oApiWebmailManager = CApi::Manager('webmail');
				$oApiWebmailManager->JumpToWebMail('webmail.php?check=1');
			}
			else
			{
				CApi::Log('09');
			}
		}
	}

	/**
	 * @staticvar bool $bOnceRun
	 * @param string $sLanguageName
	 */
	function AppIncludeLanguage($sLanguageName)
	{
		static $bOnceRun = false;
		if (!$bOnceRun)
		{
			$bOnceRun = true;

			if (!$sLanguageName || !preg_match('/^[a-zA-Z0-9\-]+$/', $sLanguageName) ||
				!@file_exists(WM_ROOTPATH.'lang/'.$sLanguageName.'.php'))
			{
				$oSettings =& CApi::GetSettings();
				$sLanguageName = $oSettings->GetConf('Common/DefaultLanguage');
				$sLanguageName = @file_exists(WM_ROOTPATH.'lang/'.$sLanguageName.'.php')
					? $sLanguageName: 'English';
			}

			include_once WM_ROOTPATH.'lang/'.$sLanguageName.'.php';
		}
	}

	/**
	 * @return bool
	 */
	function IsAdminLogin()
	{
		return (bool) CSession::Get(EAccountSessKey::AdminLogin, false);
	}

	/**
	 * @param CAccount $oAccount
	 * @return CDomain
	 */
	function AppGetDomain($oAccount = null)
	{
		if ($oAccount)
		{
			return $oAccount->Domain;
		}

		/* @var $oApiDomainsManager CApiDomainsManager */
		$oApiDomainsManager = CApi::Manager('domains');

		$oInput = new api_Http();
		return $oApiDomainsManager->GetDomainByUrl($oInput->GetHost());
	}

	/**
	 * @param string $sToken
	 *
	 * @return bool
	 */
	function AppValidateCsrfToken($sToken)
	{
		if (CApi::GetConf('labs.webmail.csrftoken-protection', false))
		{
			return !(!empty($_COOKIE[APP_COOKIE_CSRF_TOKEN_KEY]) && $sToken !== $_COOKIE[APP_COOKIE_CSRF_TOKEN_KEY]);
		}

		return true;
	}

	/**
	 * @param CAccount $oAccount = null
	 *
	 * @return string
	 */
	function AppGetSiteName($oAccount = null)
	{
		$sSiteName = null;

		/* @var $oApiDomainsManager CApiDomainsManager */
		$oApiDomainsManager = CApi::Manager('domains');

		$oInput = new api_Http();
		$oDomain = $oApiDomainsManager->GetDomainByUrl($oInput->GetHost());

		if (!$oDomain->IsDefaultDomain)
		{
			$sSiteName = $oDomain->SiteName;
		}

		if (null === $sSiteName && $oAccount)
		{
			$sSiteName = $oAccount->Domain->SiteName;
		}

		if (null === $sSiteName)
		{
			$sSiteName = $oDomain->SiteName;
		}

		return trim(null === $sSiteName ? '' : $sSiteName);
	}

	/**
	 * @param int $iAccountId
	 * @return CAccount
	 */
	function AppGetAccount($iAccountId)
	{
		/* @var $oApiUsersManager CApiUsersManager */
		$oApiUsersManager = CApi::Manager('users');
		return $oApiUsersManager->GetAccountById($iAccountId);
	}

	/**
	 * @param CAccount $oAccount
	 * @param int $iSessionUserId = null
	 * @return array
	 */
	function AppGetAccounts($oAccount, $iSessionUserId = null)
	{
		$iUserId = (null !== $oAccount)
			? $oAccount->IdUser : $iSessionUserId;

		$aAccounts = array();

		if (null !== $iUserId)
		{
			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');
			$aAccountsIds = $oApiUsersManager->GetUserIdList($iUserId);

			if (is_array($aAccountsIds))
			{
				foreach ($aAccountsIds as $iAccountId)
				{
					if (null === $oAccount)
					{
						$aAccounts[$iAccountId] = AppGetAccount($iAccountId);
					}
					else
					{
						$aAccounts[$iAccountId] = ($iAccountId === $oAccount->IdAccount)
							? $oAccount : AppGetAccount($iAccountId);
					}

					if (!is_object($aAccounts[$iAccountId]))
					{
						unset($aAccounts[$iAccountId]);
					}
				}
			}
		}

		return $aAccounts;
	}

	if (!function_exists('json_encode'))
	{
		include_once WM_ROOTPATH.'libraries/other/json.php';

		/**
		 * @staticvar Services_JSON $json
		 * @return &Services_JSON
		 */
		function &getServicesJSON()
		{
			static $json = null;
			if (null === $json)
			{
				$json = new Services_JSON();
			}

			return $json;
		}

		/**
		 * @param mixed $val
		 * @return string
		 */
		function json_encode($val)
		{
			$json =& getServicesJSON();
			return $json ? $json->encode($val) : '';
		}

		/**
		 * @param string $val
		 * @return mixed
		 */
		function json_decode($val)
		{
			$json =& getServicesJSON();
			return $json ? $json->decode($val) : null;
		}

		if (!function_exists('json_last_error'))
		{
			/**
			 * @return int
			 */
			function json_last_error()
			{
				return 0;
			}
		}
	}
