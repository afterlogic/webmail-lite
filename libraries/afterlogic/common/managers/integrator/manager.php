<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Integrator
 */
class CApiIntegratorManager extends AApiManager
{
	/**
	 * @type string
	 */
	const AUTH_KEY = 'p7auth';

	/**
	 * @type string
	 */
	const AUTH_HD_KEY = 'p7hdauth';

	/**
	 * @type string
	 */
	const TOKEN_KEY = 'p7token';

	/**
	 * @type string
	 */
	const TOKEN_LAST_CODE = 'p7lastcode';

	/**
	 * @type string
	 */
	const TOKEN_LANGUAGE = 'p7lang';

	/**
	 * @type string
	 */
	const TOKEN_HD_THREAD_ID = 'p7hdthread';

	/**
	 * @type string
	 */
	const TOKEN_HD_ACTIVATED = 'p7hdactivated';

	/**
	 * @var bool
	 */
	private $bCache;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		$this->bCache = false;
		parent::__construct('integrator', $oManager);
	}

	/**
	 * @param string $sDir
	 * @param string $sType
	 * @return array
	 */
	private function folderFiles($sDir, $sType)
	{
		$aResult = array();
		if (is_dir($sDir))
		{
			$aFiles = api_Utils::GlobRecursive($sDir.'/*'.$sType);
			foreach ($aFiles as $sFile)
			{
				if ((empty($sType) || $sType === substr($sFile, -strlen($sType))) && is_file($sFile))
				{
					$aResult[] = $sFile;
				}
			}
		}

		return $aResult;
	}

	/**
	 * @param string $sTheme
	 * @return string
	 */
	private function compileTemplates($sTheme)
	{
		$sHash = CApi::Plugin()->Hash();
		
		$sCacheFileName = '';
		if (CApi::GetConf('labs.cache.templates', $this->bCache))
		{
			$sCacheFileName = 'templates-'.md5(CApi::Version().$sHash).'.cache';
			$sCacheFullFileName = CApi::DataPath().'/cache/'.$sCacheFileName;
			if (file_exists($sCacheFullFileName))
			{
				return file_get_contents($sCacheFullFileName);
			}
		}

		$sResult = '';
		
		$sDirName = CApi::WebMailPath().'templates/views';
		$aList = $this->folderFiles($sDirName, '.html');

		foreach ($aList as $sFileName)
		{
			$sName = '';
			$iPos = strpos($sFileName, 'templates/views/');
			if (false !== $iPos && 0 < $iPos)
			{
				$sName = substr($sFileName, $iPos + 16);
			}
			else
			{
				$sName = '@errorName'.md5(rand(10000, 20000));
			}

			$sThemeFileName = '';
			if (0 < strlen($sTheme))
			{
				$iPos = strpos($sFileName, 'templates/views/');
				if (false !== $iPos && 0 < $iPos)
				{
					$sThemeFileName = substr($sFileName, $iPos + 16);
				}
			}

			if (0 < strlen($sThemeFileName))
			{
				$sThemeFileName = CApi::WebMailPath().'skins/'.$sTheme.'/templates/'.$sThemeFileName;
				if (file_exists($sThemeFileName))
				{
					$sFileName = $sThemeFileName;
				}
			}

			$sTemplateID = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(array('/', '\\'), '_', substr($sName, 0, -5)));
			$sTemplateHtml = file_get_contents($sFileName);

			$sTemplateHtml = CApi::Plugin()->ParseTemplate($sTemplateID, $sTemplateHtml);
			$sTemplateHtml = preg_replace('/\{%INCLUDE-START\/[a-zA-Z\-_]+\/INCLUDE-END%\}/', '', $sTemplateHtml);

			$sResult .= '<script id="'.$sTemplateID.'" type="text/html">'.
				preg_replace('/[\r\n\t]+/', ' ', $sTemplateHtml).'</script>';
		}

		$aPluginsTemplates = CApi::Plugin()->GetPluginsTemplates();
		foreach ($aPluginsTemplates as $aData)
		{
			$sTemplateID = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(array('/', '\\'), '_', $aData[0]));
			$sTemplateHtml = file_get_contents($aData[1]);
			
			$sTemplateHtml = CApi::Plugin()->ParseTemplate($sTemplateID, $sTemplateHtml);
			$sTemplateHtml = preg_replace('/\{%INCLUDE-START\/[a-zA-Z\-_]+\/INCLUDE-END%\}/', '', $sTemplateHtml);

			$sResult .= '<script id="'.$sTemplateID.'" type="text/html">'.
				preg_replace('/[\r\n\t]+/', ' ', $sTemplateHtml).'</script>';
		}

		$sResult = trim($sResult);
		if (CApi::GetConf('labs.cache.templates', $this->bCache))
		{
			if (!is_dir(dirname($sCacheFullFileName)))
			{
				mkdir(dirname($sCacheFullFileName), 0777, true);
			}
			
			$sResult = '<!-- '.$sCacheFileName.' -->'.$sResult;
			file_put_contents($sCacheFullFileName, $sResult);
		}

		return $sResult;
	}

	/**
	 * @param string $sTheme
	 * @return string
	 */
	private function validatedThemeValue($sTheme)
	{
		if ('' === $sTheme || !in_array($sTheme, $this->GetThemeList()))
		{
			$sTheme = 'Default';
		}

		return $sTheme;
	}

	/**
	 * @param string $sLanguage
	 * @return string
	 */
	private function validatedLanguageValue($sLanguage)
	{
		if ('' === $sLanguage || !in_array($sLanguage, $this->GetLanguageList()))
		{
			$sLanguage = 'English';
		}

		return $sLanguage;
	}

	/**
	 * @param string $sLanguage
	 * @return string
	 */
	private function compileLanguage($sLanguage)
	{
		$sCacheFileName = '';
		if (CApi::GetConf('labs.cache.i18n', $this->bCache))
		{
			$sCacheFileName = 'i18n-'.$sLanguage.'-'.md5(CApi::Version()).'.cache';
			$sCacheFullFileName = CApi::DataPath().'/cache/'.$sCacheFileName;
			if (file_exists($sCacheFullFileName))
			{
				return file_get_contents($sCacheFullFileName);
			}
		}

		$aResultLang = array();
		$sMomentLanguage = api_Utils::ConvertLanguageNameToShort($sLanguage);
		$sFileName = CApi::WebMailPath().'i18n/'.$sLanguage.'.ini';
		$sMomentFileName = CApi::WebMailPath().'i18n/moment/'.$sMomentLanguage.'.js';
		
		$sMoment = 'window.moment && window.moment.lang && window.moment.lang(\'en\');';
		if (file_exists($sMomentFileName))
		{
			$sMoment = file_get_contents($sMomentFileName);
			$sMoment = preg_replace('/\/\/[^\n]+\n/', '', $sMoment);
		}

		$sData = @file_get_contents($sFileName);
		if (false !== $sData)
		{
			$aLang = @parse_ini_string(trim($sData), true);
		}

		if (is_array($aLang))
		{
			foreach ($aLang as $sKey => $mValue)
			{
				if (is_array($mValue))
				{
					foreach ($mValue as $sSecKey => $mSecValue)
					{
						$aResultLang[$sKey.'/'.$sSecKey] = $mSecValue;
					}
				}
				else
				{
					$aResultLang[$sKey] = $mValue;
				}
			}
		}

		$sLangJs = '';
		$aLangKeys = array_keys($aResultLang);
		foreach ($aLangKeys as $sKey)
		{
			$sString = isset($aResultLang[$sKey]) ? $aResultLang[$sKey] : $sKey;

			$sLangJs .= '"'.str_replace('"', '\\"', str_replace('\\', '\\\\', $sKey)).'":'
				.'"'.str_replace(array("\r", "\n", "\t"), array('\r', '\n', '\t'), str_replace('"', '\\"', str_replace('\\', '\\\\', $sString))).'",';
		}

		$sResult = empty($sLangJs) ? 'null' : '{'.substr($sLangJs, 0, -1).'}';
		$sResult = '<script>window.pSevenLang=\''.$sLanguage.'\';window.pSevenI18N='.$sResult.';'.$sMoment.'</script>';

		if (CApi::GetConf('labs.cache.i18n', $this->bCache))
		{
			if (!is_dir(dirname($sCacheFullFileName)))
			{
				mkdir(dirname($sCacheFullFileName), 0777);
			}

			$sResult = '<!-- '.$sCacheFileName.' -->'.$sResult;
			@file_put_contents($sCacheFullFileName, $sResult);
		}

		return $sResult;
	}

	/**
	 * @return string|null
	 */
	private function getCookiePath()
	{
		static $sPath = false;
		if (false === $sPath)
		{
			$sPath = CApi::GetConf('labs.app-cookie-path', '/');
		}

		return $sPath;
	}

	/**
	 * @return string
	 */
	public function GetLoginLanguage()
	{
		$sLanguage = empty($_COOKIE[self::TOKEN_LANGUAGE]) ? '' : $_COOKIE[self::TOKEN_LANGUAGE];
		return '' === $sLanguage ? '' : $this->validatedLanguageValue($sLanguage);
	}

	/**
	 * @param string $sLanguage
	 */
	public function SetLoginLanguage($sLanguage)
	{
		$sLanguage = $this->validatedLanguageValue($sLanguage);
		@setcookie(self::TOKEN_LANGUAGE, $sLanguage, 0, $this->getCookiePath(), null, null, true);
	}
	
	/**
	 * @return int
	 */
	public function GetLogginedUserId()
	{
		$iUserId = 0;
		$sKey = empty($_COOKIE[self::AUTH_KEY]) ? '' : $_COOKIE[self::AUTH_KEY];
		if (!empty($sKey) && is_string($sKey))
		{
			$aAccountHashTable = CApi::DecodeKeyValues($sKey);
			if (is_array($aAccountHashTable) && isset($aAccountHashTable['token']) &&
				'auth' === $aAccountHashTable['token'] && 0 < strlen($aAccountHashTable['id']) && is_int($aAccountHashTable['id']))
			{
				$iUserId = $aAccountHashTable['id'];
			}
		}

		return $iUserId;
	}

	/**
	 * @return int
	 */
	public function GetLogginedHelpdeskUserId()
	{
		$iHdUserId = 0;
		$sKey = empty($_COOKIE[self::AUTH_HD_KEY]) ? '' : $_COOKIE[self::AUTH_HD_KEY];
		if (!empty($sKey) && is_string($sKey))
		{
			$aUserHashTable = CApi::DecodeKeyValues($sKey);
			if (is_array($aUserHashTable) && isset($aUserHashTable['token']) &&
				'hd_auth' === $aUserHashTable['token'] && 0 < strlen($aUserHashTable['id']) && is_int($aUserHashTable['id']))
			{
				$iHdUserId = (int) $aUserHashTable['id'];
			}
		}

		return $iHdUserId;
	}

	/**
	 * @return CAccount | null
	 */
	public function GetLogginedDefaultAccount()
	{
		$oResult = null;
		$iUserId = $this->GetLogginedUserId();
		if (0 < $iUserId)
		{
			$oApiUsers = CApi::Manager('users');
			if ($oApiUsers)
			{
				$iAccountId = $oApiUsers->GetDefaultAccountId($iUserId);
				if (0 < $iAccountId)
				{
					$oAccount = $oApiUsers->GetAccountById($iAccountId);
					$oResult = $oAccount instanceof \CAccount ? $oAccount : null;
				}
			}
		}

		return $oResult;
	}

	/**
	 * @return string
	 */
	public function GetCsrfToken()
	{
		$sToken = !empty($_COOKIE[self::TOKEN_KEY]) ? $_COOKIE[self::TOKEN_KEY] : null;
		if (null === $sToken)
		{
			$sToken = md5(rand(1000, 9999).CApi::$sSalt.microtime(true));
			@setcookie(self::TOKEN_KEY, $sToken, time() + 60 * 60 * 24 * 30, $this->getCookiePath(), null, null, true);
		}

		return $sToken;
	}

	/**
	 * @param int $iCode
	 * 
	 * @return void
	 */
	public function SetLastErrorCode($iCode)
	{
		@setcookie(self::TOKEN_LAST_CODE, $iCode, 0, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @return int
	 */
	public function GetLastErrorCode()
	{
		return isset($_COOKIE[self::TOKEN_LAST_CODE]) ? (int) $_COOKIE[self::TOKEN_LAST_CODE] : 0;
	}
	
	/**
	 * @return void
	 */
	public function ClearLastErrorCode()
	{
		if (isset($_COOKIE[self::TOKEN_LAST_CODE]))
		{
			unset($_COOKIE[self::TOKEN_LAST_CODE]);
		}
		
		@setcookie(self::TOKEN_LAST_CODE, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
	}

	/**
	 * @param string $sToken
	 *
	 * @return bool
	 */
	public function ValidateCsrfToken($sToken)
	{
		return $sToken === $this->GetCsrfToken();
	}

	/**
	 * @return bool
	 */
	public function LogoutAccount()
	{
		@setcookie(self::AUTH_KEY, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
		return true;
	}

	/**
	 * @param int $iThreadID
	 *
	 * @return void
	 */
	public function SetThreadIdFromRequest($iThreadID)
	{
		$aHashTable = array(
			'token' => 'thread_id',
			'id' => (int) $iThreadID
		);

		CApi::LogObject($aHashTable);

		$_COOKIE[self::TOKEN_HD_THREAD_ID] = CApi::EncodeKeyValues($aHashTable);
		@setcookie(self::TOKEN_HD_THREAD_ID, CApi::EncodeKeyValues($aHashTable), 0, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @return int|null
	 */
	public function GetThreadIdFromRequestAndClear()
	{
		$mHdThreadId = null;
		$sKey = empty($_COOKIE[self::TOKEN_HD_THREAD_ID]) ? '' : $_COOKIE[self::TOKEN_HD_THREAD_ID];
		if (!empty($sKey) && is_string($sKey))
		{
			$aUserHashTable = CApi::DecodeKeyValues($sKey);
			if (is_array($aUserHashTable) && isset($aUserHashTable['token'], $aUserHashTable['id']) &&
				'thread_id' === $aUserHashTable['token'] && 0 < strlen($aUserHashTable['id']) && is_int($aUserHashTable['id']))
			{
				$mHdThreadId = (int) $aUserHashTable['id'];
			}
		}

		if (0 < strlen($sKey))
		{
			if (isset($_COOKIE[self::TOKEN_HD_THREAD_ID]))
			{
				unset($_COOKIE[self::TOKEN_HD_THREAD_ID]);
			}

			@setcookie(self::TOKEN_HD_THREAD_ID, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
		}

		return $mHdThreadId;
	}

	/**
	 * @return void
	 */
	public function RemoveUserAsActivated()
	{
		if (isset($_COOKIE[self::TOKEN_HD_ACTIVATED]))
		{
			$_COOKIE[self::TOKEN_HD_ACTIVATED] = '';
			unset($_COOKIE[self::TOKEN_HD_ACTIVATED]);
			@setcookie(self::TOKEN_HD_ACTIVATED, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
		}
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 *
	 * @return void
	 */
	public function SetUserAsActivated($oHelpdeskUser, $bForgot = false)
	{
		$aHashTable = array(
			'token' => 'hd_activated_email',
			'forgot' => $bForgot,
			'email' => $oHelpdeskUser->Email
		);

		$_COOKIE[self::TOKEN_HD_ACTIVATED] = CApi::EncodeKeyValues($aHashTable);
		@setcookie(self::TOKEN_HD_ACTIVATED, CApi::EncodeKeyValues($aHashTable), 0, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @param bool $bForgot = false
	 *
	 * @return int
	 */
	public function GetActivatedUserEmailAndClear()
	{
		$sEmail = '';
		$sKey = empty($_COOKIE[self::TOKEN_HD_ACTIVATED]) ? '' : $_COOKIE[self::TOKEN_HD_ACTIVATED];
		if (!empty($sKey) && is_string($sKey))
		{
			$aUserHashTable = CApi::DecodeKeyValues($sKey);
			if (is_array($aUserHashTable) && isset($aUserHashTable['token']) &&
				'hd_activated_email' === $aUserHashTable['token'] && 0 < strlen($aUserHashTable['email']))
			{
				$sEmail = $aUserHashTable['email'];
			}
		}

		if (0 < strlen($sKey))
		{
			if (isset($_COOKIE[self::TOKEN_HD_ACTIVATED]))
			{
				unset($_COOKIE[self::TOKEN_HD_ACTIVATED]);
			}

			@setcookie(self::TOKEN_HD_THREAD_ID, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
		}

		return $sEmail;
	}

	/**
	 * @param CAccount $oAccount
	 * @param bool $bSignMe = false
	 *
	 * @return void
	 */
	public function SetAccountAsLoggedIn(CAccount $oAccount, $bSignMe = false)
	{
		$aAccountHashTable = array(
			'token' => 'auth',
			'sign-me' => $bSignMe,
			'id' => $oAccount->IdUser
		);

		$iTime = $bSignMe ? time() + 60 * 60 * 24 * 30 : 0;
		$_COOKIE[self::AUTH_KEY] = CApi::EncodeKeyValues($aAccountHashTable);
		@setcookie(self::AUTH_KEY, CApi::EncodeKeyValues($aAccountHashTable), $iTime, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @param CHelpdeskUser $oUser
	 * @param bool $bSignMe = false
	 *
	 * @return void
	 */
	public function SetHelpdeskUserAsLoggedIn(CHelpdeskUser $oUser, $bSignMe = false)
	{
		$aUserHashTable = array(
			'token' => 'hd_auth',
			'sign-me' => $bSignMe,
			'id' => $oUser->IdHelpdeskUser
		);

		$iTime = $bSignMe ? time() + 60 * 60 * 24 * 30 : 0;
		$_COOKIE[self::AUTH_HD_KEY] = CApi::EncodeKeyValues($aUserHashTable);
		@setcookie(self::AUTH_HD_KEY, CApi::EncodeKeyValues($aUserHashTable), $iTime, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @return bool
	 */
	public function LogoutHelpdeskUser()
	{
		@setcookie(self::AUTH_HD_KEY, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
		return true;
	}

	public function ResetCookies()
	{
		$sAccountHash = !empty($_COOKIE[self::AUTH_KEY]) ? $_COOKIE[self::AUTH_KEY] : '';
		if (0 < strlen($sAccountHash))
		{
			$aAccountHashTable = CApi::DecodeKeyValues($sAccountHash);
			if (isset($aAccountHashTable['sign-me']) && $aAccountHashTable['sign-me'])
			{
				@setcookie(self::AUTH_KEY, CApi::EncodeKeyValues($aAccountHashTable),
					time() + 60 * 60 * 24 * 30, $this->getCookiePath(), null, null, true);
			}

			$sToken = !empty($_COOKIE[self::TOKEN_KEY]) ? $_COOKIE[self::TOKEN_KEY] : null;
			if (null !== $sToken)
			{
				@setcookie(self::TOKEN_KEY, $sToken, time() + 60 * 60 * 24 * 30, $this->getCookiePath(), null, null, true);
			}
		}

		$sHelpdeskHash = !empty($_COOKIE[self::AUTH_HD_KEY]) ? $_COOKIE[self::AUTH_HD_KEY] : '';
		if (0 < strlen($sHelpdeskHash))
		{
			$aHelpdeskHashTable = CApi::DecodeKeyValues($sHelpdeskHash);
			if (isset($aHelpdeskHashTable['sign-me']) && $aHelpdeskHashTable['sign-me'])
			{
				@setcookie(self::AUTH_HD_KEY, CApi::EncodeKeyValues($aHelpdeskHashTable), 
					time() + 60 * 60 * 24 * 30, $this->getCookiePath(), null, null, true);
			}
		}
	}

	/**
	 * @return CAccount | null | bool
	 */
	public function LoginToAccount($sEmail, $sIncPassword, $sIncLogin = '', $sLanguage = '')
	{
		$oResult = null;

		/* @var $oApiUsersManager CApiUsersManager */
		$oApiUsersManager = CApi::Manager('users');

		/* @var $oApiWebmailManager CApiWebmailManager */
		$oApiWebmailManager = CApi::Manager('webmail');

		CApi::Plugin()->RunHook('api-integrator-login-to-account', array(&$sEmail, &$sIncPassword, &$sIncLogin, &$sLanguage));

		$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);
		if ($oAccount instanceof CAccount)
		{
			if ($sIncPassword !== $oAccount->IncomingMailPassword)
			{
				$oAccount->IncomingMailPassword = $sIncPassword;
			}
			
			if ($oAccount->IsDisabled || ($oAccount->Domain && $oAccount->Domain->IsDisabled))
			{
				throw new CApiManagerException(Errs::WebMailManager_AccountDisabled);
			}
			else if (!$oAccount->Domain->AllowWebMail)
			{
				throw new CApiManagerException(Errs::WebMailManager_AccountWebmailDisabled);
			}

			if (0 < $oAccount->IdTenant)
			{
				$oApiTenantsManager = /* @var $oApiTenantsManager CApiTenantsManager */ CApi::Manager('tenants');
				if ($oApiTenantsManager)
				{
					$oTenant = $oApiTenantsManager->GetTenantById($oAccount->IdTenant);
					if ($oTenant && ($oTenant->IsDisabled || (0 < $oTenant->Expared && $oTenant->Expared < \time())))
					{
						throw new CApiManagerException(Errs::WebMailManager_AccountDisabled);
					}
				}
			}
			
			if (0 < strlen($sLanguage) && $sLanguage !== $oAccount->User->DefaultLanguage)
			{
				$oAccount->User->DefaultLanguage = $sLanguage;
			}

			$oApiMailManager = CApi::Manager('mail');
			$oApiMailManager->ValidateAccountConnection($oAccount);

			$sObsoleteIncPassword = $oAccount->GetObsoleteValue('IncomingMailPassword');
			$sObsoleteLanguage = $oAccount->User->GetObsoleteValue('DefaultLanguage');
			if (null !== $sObsoleteIncPassword && $sObsoleteIncPassword !== $oAccount->IncomingMailPassword ||
				null !== $sObsoleteLanguage && $sObsoleteLanguage !== $oAccount->User->DefaultLanguage ||
				$oAccount->ForceSaveOnLogin)
			{
				$oApiUsersManager->UpdateAccount($oAccount);
			}

			$oApiUsersManager->UpdateAccountLastLoginAndCount($oAccount->IdUser);

			$oResult = $oAccount;
		}
		else if (null === $oAccount)
		{
			$aExtValues = array();
			if (0 < strlen($sIncLogin))
			{
				$aExtValues['Login'] = $sIncLogin;
			}

			$oAccount = $oApiWebmailManager->CreateAccountProcess($sEmail, $sIncPassword, $sLanguage, $aExtValues);
			if ($oAccount instanceof CAccount)
			{
				CApi::Plugin()->RunHook('api-integrator-login-success-post-create-account-call', array(&$oAccount));

				$oResult = $oAccount;
			}
			else
			{
				$oException = $oApiWebmailManager->GetLastException();

				CApi::Plugin()->RunHook('api-integrator-login-error-post-create-account-call', array(&$oException));

				throw (is_object($oException))
					? $oException
					: new CApiManagerException(Errs::WebMailManager_AccountCreateOnLogin);
			}
		}
		else
		{
			$oException = $oApiUsersManager->GetLastException();

			CApi::Plugin()->RunHook('api-integrator-login-error-post-create-account-call', array(&$oException));

			throw (is_object($oException))
				? $oException
				: new CApiManagerException(Errs::WebMailManager_AccountCreateOnLogin);
		}

		if ($oResult instanceof CAccount)
		{
			CApi::Plugin()->RunHook('statistics.login', array(&$oResult, null));
		}

		CApi::Plugin()->RunHook('api-integrator-login-to-account-result', array(&$oResult));

		return $oResult;
	}
	
	/**
	 * @return CHelpdeskUser|null|bool
	 */
	public function LoginToHelpdeskAccount($iIdTenant, $sEmail, $sPassword)
	{
		$oResult = null;

		CApi::Plugin()->RunHook('api-integrator-login-to-helpdesk-user', array(&$sEmail, &$sPassword));

		$oApiHelpdeskManager = /* @var $oApiHelpdeskManager CApiHelpdeskManager */ CApi::Manager('helpdesk');
		$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');
		$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
		if (!$oApiHelpdeskManager || !$oApiUsersManager || !$oApiCapabilityManager ||
			!$oApiCapabilityManager->IsHelpdeskSupported())
		{
			return false;
		}

		$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);
		if ($oAccount && $oAccount->IdTenant === $iIdTenant && $oApiCapabilityManager->IsHelpdeskSupported($oAccount) &&
			$oAccount->IncomingMailPassword === $sPassword)
		{
			$this->SetAccountAsLoggedIn($oAccount);
			$this->SetThreadIdFromRequest(0);
			throw new CApiManagerException(Errs::HelpdeskManager_AccountSystemAuthentication);
			return null;
		}

		$oUser = /* @var $oUser CHelpdeskUser */ $oApiHelpdeskManager->GetUserByEmail($iIdTenant, $sEmail);
		if ($oUser instanceof CHelpdeskUser && $oUser->ValidatePassword($sPassword) && $iIdTenant === $oUser->IdTenant)
		{
			if (!$oUser->Activated)
			{
				throw new CApiManagerException(Errs::HelpdeskManager_UnactivatedUser);
			}

			$oResult = $oUser;
			CApi::Plugin()->RunHook('statistics.helpdesk-login', array(&$oResult, null));
		}
		else
		{
			throw new CApiManagerException(Errs::HelpdeskManager_AccountAuthentication);
		}

		if ($oResult instanceof CHelpdeskUser)
		{
			CApi::Plugin()->RunHook('statistics.helpdesk-login', array(&$oResult, null));
		}

		CApi::Plugin()->RunHook('api-integrator-login-to-helpdesk-user-result', array(&$oResult));

		return $oResult;
	}

	/**
	 * @return bool
	 */
	public function RegisterHelpdeskAccount($iIdTenant, $sEmail, $sName, $sPassword)
	{
		$bResult = false;

		$oApiHelpdeskManager = /* @var $oApiHelpdeskManager CApiHelpdeskManager */ CApi::Manager('helpdesk');
		$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');
		$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
		if (!$oApiHelpdeskManager || !$oApiUsersManager || !$oApiCapabilityManager ||
			!$oApiCapabilityManager->IsHelpdeskSupported())
		{
			return $bResult;
		}

		$oUser = /* @var $oUser CHelpdeskUser */ $oApiHelpdeskManager->GetUserByEmail($iIdTenant, $sEmail);
		if (!$oUser)
		{
			$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);
			if ($oAccount && $oAccount->IdTenant === $iIdTenant && $oApiCapabilityManager->IsHelpdeskSupported($oAccount))
			{
				throw new CApiManagerException(Errs::HelpdeskManager_UserAlreadyExists);
			}

			$oUser = new CHelpdeskUser();
			$oUser->Activated = false;
			$oUser->Email = $sEmail;
			$oUser->Name = $sName;
			$oUser->IdTenant = $iIdTenant;
			$oUser->IsAgent = false;

			$oUser->SetPassword($sPassword);
			
			$oApiHelpdeskManager->CreateUser($oUser);
			if (!$oUser || 0 === $oUser->IdHelpdeskUser)
			{
				throw new CApiManagerException(Errs::HelpdeskManager_UserCreateFailed);
			}
			else
			{
				$bResult = true;
			}
		}
		else
		{
			throw new CApiManagerException(Errs::HelpdeskManager_UserAlreadyExists);
		}

		return $bResult;
	}

	public function RegisterSocialAccount($iIdTenant, $sTenantHash, $sNotificationEmail, $sSocialId, $sSocialType, $sSocialName)
	{
		$bResult = false;

		$oApiHelpdeskManager = /* @var $oApiHelpdeskManager CApiHelpdeskManager */ CApi::Manager('helpdesk');
		$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');
		$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
		if (!$oApiHelpdeskManager || !$oApiUsersManager || !$oApiCapabilityManager ||
			!$oApiCapabilityManager->IsHelpdeskSupported())
		{
			return $bResult;
		}

		$oUser = /* @var $oUser CHelpdeskUser */ $oApiHelpdeskManager->GetUserBySocialId($iIdTenant, $sSocialId);
		if (!$oUser)
		{
			$oAccount = $this->GetAhdSocialUser($sTenantHash, $sSocialId);
			if ($oAccount && $oAccount->IdTenant === $iIdTenant && $oApiCapabilityManager->IsHelpdeskSupported($oAccount))
			{
				throw new CApiManagerException(Errs::HelpdeskManager_UserAlreadyExists);
			}

			$oUser = new CHelpdeskUser();
			$oUser->Activated = true;
			$oUser->Name = $sSocialName;
			$oUser->NotificationEmail = $sNotificationEmail;
			$oUser->SocialId = $sSocialId;
			$oUser->SocialType = $sSocialType;
			$oUser->IdTenant = $iIdTenant;
			$oUser->IsAgent = false;
			$oApiHelpdeskManager->CreateUser($oUser);
			if (!$oUser || 0 === $oUser->IdHelpdeskUser)
			{
				throw new CApiManagerException(Errs::HelpdeskManager_UserCreateFailed);
			}
			else
			{
				$bResult = true;
			}
		}
		else
		{
			throw new CApiManagerException(Errs::HelpdeskManager_UserAlreadyExists);
		}

		return $bResult;
	}

	/**
	 * @return int|bool
	 */
	public function GetTenantIdByHash($sTenantHash)
	{
		$iResult = 0;

		$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
		if (0 === strlen($sTenantHash) && $oApiCapabilityManager->IsHelpdeskSupported() && !$oApiCapabilityManager->IsTenantsSupported())
		{
			return 0;
		}
		else if (0 < strlen($sTenantHash))
		{
			$oApiTenantsManager = /* @var $oApiTenantsManager CApiTenantsManager */ CApi::Manager('tenants');
			if ($oApiTenantsManager)
			{
				$oTenant = $oApiTenantsManager->GetTenantByHash($sTenantHash);
				if ($oTenant && $oTenant->IsHelpdeskSupported())
				{
					$iResult = $oTenant->IdTenant;
				}
			}
		}
		
		return 0 < $iResult ? $iResult : false;
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return CDomain
	 */
	private function getDefaultAccountDomain($oAccount)
	{
		/* @var $oApiDomainsManager CApiDomainsManager */
		$oApiDomainsManager = CApi::Manager('domains');

		$oDomain = ($oAccount && $oAccount->IsDefaultAccount) ? $oAccount->Domain : $oApiDomainsManager->GetDefaultDomain();
		if (null === $oDomain)
		{
			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');

			$iDomainId = $oApiUsersManager->GetDefaultAccountDomainId($oAccount->IdUser);
			if (0 < $iDomainId)
			{
				$oDomain = $oApiDomainsManager->GetDomainById($iDomainId);
			}
			else
			{
				$oDomain = $oApiDomainsManager->GetDefaultDomain();
			}
		}

		return $oDomain;
	}

	/**
	 * @param CDomain $oDomain
	 * @return array
	 */
	private function appDataDomainSettings($oDomain)
	{
		$aResult = array();
		if ($oDomain)
		{
			$oSettings =& CApi::GetSettings();

			$aResult['AllowUsersChangeInterfaceSettings'] = (bool) $oDomain->AllowUsersChangeInterfaceSettings;
			$aResult['AllowUsersChangeEmailSettings'] = (bool) $oDomain->AllowUsersChangeEmailSettings;
			$aResult['AllowUsersAddNewAccounts'] = (bool) $oDomain->AllowUsersAddNewAccounts;
			$aResult['AllowFetcher'] = (bool) $oDomain->IsInternal && CApi::GetConf('labs.fetchers', false);

			$aResult['SiteName'] = $oDomain->SiteName;

			$aResult['DefaultLanguage'] = $oDomain->DefaultLanguage;
			$aResult['DefaultLanguageShort'] = api_Utils::ConvertLanguageNameToShort($oDomain->DefaultLanguage);
			$aResult['DefaultTheme'] = $oDomain->DefaultSkin;

			$aResult['Languages'] = array();
			$aLangs = $this->GetLanguageList();
			foreach ($aLangs as $sLang)
			{
				$aResult['Languages'][] = array(
					'name' => $this->getLanguageName($sLang),
					'value' => $sLang
				);
			}

			$aResult['Themes'] = array();
			$aThemes = $this->GetThemeList();
			foreach ($aThemes as $sTheme)
			{
				$aResult['Themes'][] = $sTheme;
			}

			$aResult['DateFormats'] = array();
			foreach (array(EDateFormat::MMDDYYYY, EDateFormat::DDMMYYYY, EDateFormat::DD_MONTH_YYYY) as $sDateFmtName)
			{
				$aResult['DateFormats'][] = $sDateFmtName;
			}

			$iAttachmentSizeLimit = ((bool) $oSettings->GetConf('WebMail/EnableAttachmentSizeLimit'))
				? (int) $oSettings->GetConf('WebMail/AttachmentSizeLimit') : 0;
			
			$iImageUploadSizeLimit = ((bool) $oSettings->GetConf('WebMail/EnableAttachmentSizeLimit'))
				? (int) $oSettings->GetConf('WebMail/ImageUploadSizeLimit') : 0;
			
			$iFileSizeLimit = ((bool) $oSettings->GetConf('Files/EnableSizeLimit'))
				? (int) $oSettings->GetConf('Files/SizeLimit') : 0;

			$aResult['AttachmentSizeLimit'] = $iAttachmentSizeLimit;
			$aResult['ImageUploadSizeLimit'] = $iImageUploadSizeLimit;
			$aResult['FileSizeLimit'] = $iFileSizeLimit;
			$aResult['AutoSave'] = (bool) CApi::GetConf('webmail.autosave', true);

			$aResult['IdleSessionTimeout'] = (int) $oSettings->GetConf('WebMail/IdleSessionTimeout');
			$aResult['AllowInsertImage'] = (bool) $oSettings->GetConf('WebMail/AllowInsertImage');
			$aResult['AllowBodySize'] = (bool) $oSettings->GetConf('WebMail/AllowBodySize');
			$aResult['MaxBodySize'] = (int) $oSettings->GetConf('WebMail/MaxBodySize');
			$aResult['MaxSubjectSize'] = (int) $oSettings->GetConf('WebMail/MaxSubjectSize');
			
			$aResult['AllowPrefetch'] = (bool) CApi::GetConf('webmail.use-prefetch', true);
			$aResult['AllowLanguageOnLogin'] = (bool) $oSettings->GetConf('WebMail/AllowLanguageOnLogin');
			$aResult['FlagsLangSelect'] = (bool) $oSettings->GetConf('WebMail/FlagsLangSelect');
			
			$aResult['LoginFormType'] = (int) $oSettings->GetConf('WebMail/LoginFormType');
			$aResult['LoginSignMeType'] = (int) $oSettings->GetConf('WebMail/LoginSignMeType');
			$aResult['LoginAtDomainValue'] = (string) $oSettings->GetConf('WebMail/LoginAtDomainValue');
			
			$aResult['DemoWebMail'] = (bool) CApi::GetConf('demo.webmail.enable', false);
			$aResult['DemoWebMailLogin'] = CApi::GetConf('demo.webmail.login', '');
			$aResult['DemoWebMailPassword'] = CApi::GetConf('demo.webmail.password', '');
			$aResult['GoogleAnalyticsAccount'] = CApi::GetConf('labs.google-analytic.account', '');
			$aResult['CustomLoginUrl'] = (string) CApi::GetConf('labs.webmail.custom-login-url', '');
			$aResult['CustomLogoutUrl'] = (string) CApi::GetConf('labs.webmail.custom-logout-url', '');
			$aResult['ShowQuotaBar'] = (bool) $oSettings->GetConf('WebMail/ShowQuotaBar');
			$aResult['ServerUseUrlRewrite'] = (bool) CApi::GetConf('labs.server-use-url-rewrite', false);
			$aResult['ServerUrlRewriteBase'] = (string) CApi::GetConf('labs.server-url-rewrite-base', '');
			$aResult['IosDetectOnLogin'] = (bool) CApi::GetConf('labs.webmail.ios-detect-on-login', true);

			if ($aResult['IosDetectOnLogin'])
			{
				$aResult['IosDetectOnLogin'] = isset($_COOKIE['skip_ios']) &&
					'1' === (string) $_COOKIE['skip_ios'] ? false : true;
			}

			$sCustomLanguage = $this->GetLoginLanguage();
			if (!empty($sCustomLanguage))
			{
				$aResult['DefaultLanguage'] = $sCustomLanguage;
				$aResult['DefaultLanguageShort'] = api_Utils::ConvertLanguageNameToShort($sCustomLanguage);
			}

			$aResult['LoginDescription'] = '';
			
			CApi::Plugin()->RunHook('api-app-domain-data', array($oDomain, &$aResult));
		}

		return $aResult;
	}

	/**
	 * @param CHelpdeskUser $oUser
	 * @return array
	 */
	private function appDataHelpdeskUserSettings($oUser)
	{
		$aResult = array();
		if ($oUser)
		{
			$aResult['Name'] = $oUser->Name;
			$aResult['Email'] = $oUser->Email;
			$aResult['Language'] = $oUser->Language;
			$aResult['DateFormat'] = $oUser->DateFormat;
			$aResult['TimeFormat'] = $oUser->TimeFormat;
			$aResult['IsHelpdeskAgent'] = $oUser->IsAgent;
		}

		return $aResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return array
	 */
	private function appDataUserSettings($oAccount)
	{
		$aResult = array();
		if ($oAccount)
		{
			$oSettings =& CApi::GetSettings();

			$oApiCapabilityManager = CApi::Manager('capability');
			/* @var $oApiCapabilityManager CApiCapabilityManager */

			$aResult['IdUser'] = $oAccount->User->IdUser;
			$aResult['MailsPerPage'] = (int) $oAccount->User->MailsPerPage;
			$aResult['ContactsPerPage'] = (int) $oAccount->User->ContactsPerPage;
			$aResult['AutoCheckMailInterval'] = (int) $oAccount->User->AutoCheckMailInterval;
			$aResult['DefaultEditor'] = (int) $oAccount->User->DefaultEditor;
//			$aResult['Layout'] = (int) $oAccount->User->Layout;
			$aResult['Layout'] = 0;

			$aResult['DefaultTheme'] = $oAccount->User->DefaultSkin;
			$aResult['DefaultLanguage'] = $oAccount->User->DefaultLanguage;
			$aResult['DefaultDateFormat'] = $oAccount->User->DefaultDateFormat;
			$aResult['DefaultTimeFormat'] = $oAccount->User->DefaultTimeFormat;

			$aResult['AllowCompose'] = (bool) $oAccount->AllowCompose;
			$aResult['AllowReply'] = (bool) $oAccount->AllowReply;
			$aResult['AllowForward'] = (bool) $oAccount->AllowForward;

			$iSaveMail = $oSettings->GetConf('WebMail/SaveMail');
			$iSaveMail = ESaveMail::Always !== $iSaveMail ? $oAccount->User->SaveMail : ESaveMail::Always;
			$aResult['SaveMail'] = (int) $iSaveMail;

			$aResult['ThreadsEnabled'] = !!$oSettings->GetConf('WebMail/UseThreadsIfSupported');
			$aResult['UseThreads'] = false;
			$aResult['SaveRepliedMessagesToCurrentFolder'] = false;
			
			if ($aResult['ThreadsEnabled'])
			{
				$aResult['UseThreads'] = (bool) $oAccount->User->UseThreads;
				$aResult['SaveRepliedMessagesToCurrentFolder'] = (bool) $oAccount->User->SaveRepliedMessagesToCurrentFolder;
			}
			
			$aResult['OutlookSyncEnable'] = $oApiCapabilityManager->IsOutlookSyncSupported($oAccount);
			$aResult['MobileSyncEnable'] = $oApiCapabilityManager->IsMobileSyncSupported($oAccount);
			
			$aResult['ShowPersonalContacts'] = $oApiCapabilityManager->IsPersonalContactsSupported($oAccount);
			$aResult['ShowGlobalContacts'] = $oApiCapabilityManager->IsGlobalContactsSupported($oAccount, true);
			
			$aResult['IsFilesSupported'] = $oApiCapabilityManager->IsFilesSupported($oAccount);
			$aResult['IsHelpdeskSupported'] = $oApiCapabilityManager->IsHelpdeskSupported($oAccount);
			$aResult['IsHelpdeskAgent'] = $aResult['IsHelpdeskSupported']; // TODO
			$aResult['AllowHelpdeskNotifications'] = (bool) $oAccount->User->AllowHelpdeskNotifications;

			$aResult['LastLogin'] = 0;
			if ($oSettings->GetConf('WebMail/EnableLastLoginNotification'))
			{
				$aResult['LastLogin'] = $oAccount->User->LastLogin;
			}

			$aResult['AllowVoice'] = false;
			$aResult['VoiceProvider'] = '';
			$aResult['VoiceRealm'] = '';
			$aResult['VoiceWebsocketProxyUrl'] = '';
			$aResult['VoiceOutboundProxyUrl'] = '';
			$aResult['VoiceCallerID'] = '';
			$aResult['TwilioNumber'] = '';
			$aResult['VoiceImpi'] = '';
			$aResult['VoicePassword'] = '';
			
//			$aResult['VoiceAccountSID'] = '';
//			$aResult['VoiceAuthToken'] = '';
//			$aResult['VoiceAppSID'] = '';
			
			$oApiTenants = CApi::Manager('tenants');
			/* @var $oApiTenants CApiTenantsManager */

			if ($oApiTenants)
			{
				$oTenant = 0 < $oAccount->IdTenant ?
					$oApiTenants->GetTenantById($oAccount->IdTenant) : $oApiTenants->GetDefaultGlobalTenant();
				if ($oTenant)
				{
					if ($oTenant->SipAllowConfiguration && $oTenant->SipAllow &&
						$oTenant->IsSipSupported() &&
						$oApiCapabilityManager->IsSipSupported($oAccount))
					{
						$aResult['AllowVoice'] = $oTenant->SipAllow;
						$aResult['VoiceProvider'] = 'sip';
						$aResult['VoiceRealm'] = (string) $oTenant->SipRealm;
						$aResult['VoiceWebsocketProxyUrl'] = (string) $oTenant->SipWebsocketProxyUrl;
						$aResult['VoiceOutboundProxyUrl'] = (string) $oTenant->SipOutboundProxyUrl;
						$aResult['VoiceCallerID'] = (string) $oTenant->SipCallerID;

						$aResult['VoiceImpi'] = $oAccount->User->SipImpi;
						$aResult['VoicePassword'] = $oAccount->User->SipPassword;
					}
					else if ($oTenant->TwilioAllowConfiguration && $oTenant->TwilioAllow &&
						$oTenant->IsTwilioSupported() &&
						$oApiCapabilityManager->IsTwilioSupported($oAccount))
					{
						$aResult['AllowVoice'] = $oTenant->TwilioAllow;
						$aResult['VoiceProvider'] = 'twilio';
//						$aResult['VoiceAccountSID'] = (string) $oTenant->TwilioAccountSID;
//						$aResult['VoiceAuthToken'] = (string) $oTenant->TwilioAuthToken;
//						$aResult['VoiceAppSID'] = (string) $oTenant->TwilioAppSID;

						$aResult['TwilioNumber'] = $oAccount->User->TwilioNumber;
					}
				}

				if ($aResult['VoiceProvider'] === 'sip' && (0 === strlen($aResult['VoiceRealm']) || 0 === strlen($aResult['VoiceWebsocketProxyUrl'])))
				{
					$aResult['AllowVoice'] = false;
					$aResult['VoiceRealm'] = '';
					$aResult['VoiceWebsocketProxyUrl'] = '';
					$aResult['VoiceOutboundProxyUrl'] = '';
					$aResult['VoiceCallerID'] = '';
					$aResult['VoiceImpi'] = '';
					$aResult['VoicePassword'] = '';

//					$aResult['VoiceAccountSID'] = '';
//					$aResult['VoiceAuthToken'] = '';
//					$aResult['VoiceAppSID'] = '';
				}
			}

			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');

			/* @var $oApiDavManager CApiDavManager */
			$oApiDavManager = CApi::Manager('dav');

			$aResult['AllowCalendar'] = $oApiCapabilityManager->IsCalendarSupported($oAccount);

			$aResult['Calendar'] = null;
			if ($aResult['AllowCalendar'] && $oApiDavManager && $oAccount->IsDefaultAccount)
			{
				/* @var $oCalUser CCalUser */
				$oCalUser = $oApiUsersManager->GetOrCreateCalUserByUserId($oAccount->IdUser);
				if ($oCalUser)
				{
					$aResult['Calendar'] = array();
					$aResult['Calendar']['ShowWeekEnds'] = (bool) $oCalUser->ShowWeekEnds;
					$aResult['Calendar']['ShowWorkDay'] = (bool) $oCalUser->ShowWorkDay;
					$aResult['Calendar']['WorkDayStarts'] = (int) $oCalUser->WorkDayStarts;
					$aResult['Calendar']['WorkDayEnds'] = (int) $oCalUser->WorkDayEnds;
					$aResult['Calendar']['WeekStartsOn'] = (int) $oCalUser->WeekStartsOn;
					$aResult['Calendar']['DefaultTab'] = (int) $oCalUser->DefaultTab;

					$aResult['Calendar']['SyncLogin'] = (string) $oApiDavManager->GetLogin($oAccount);
					$aResult['Calendar']['DavServerUrl'] = (string) $oApiDavManager->GetServerUrl($oAccount);
					$aResult['Calendar']['DavPrincipalUrl'] = (string) $oApiDavManager->GetPrincipalUrl($oAccount);
					$aResult['Calendar']['AllowReminders'] = true;
				}
			}

			$aResult['CalendarSharing'] = false;
			$aResult['CalendarAppointments'] = false;
			
			$aResult['AllowCalendar'] = null === $aResult['Calendar'] ? false : $aResult['AllowCalendar'];
			if ($aResult['AllowCalendar'])
			{
				$aResult['CalendarSharing'] = $oApiCapabilityManager->IsCalendarSharingSupported($oAccount);
				$aResult['CalendarAppointments'] = $oApiCapabilityManager->IsCalendarAppointmentsSupported($oAccount);
			}

			$bIsDemo = false;
			CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
			$aResult['IsDemo'] = $bIsDemo;
			
			CApi::Plugin()->RunHook('api-app-user-data', array(&$oAccount, &$aResult));

		}

		return $aResult;
	}

	/**
	 * @param string $sLang
	 *
	 * @return string
	 */
	private function getLanguageName($sLang)
	{
		static $aCache = null;
		if (null === $aCache)
		{
			$aCache = CApi::GetConf('langs.names', array());
			if (is_array($aCache))
			{
				$aCache = array_change_key_case($aCache, CASE_LOWER);
			}
		}

		if (isset($aCache[strtolower($sLang)]))
		{
			return $aCache[strtolower($sLang)];
		}

		return $sLang;
	}

	/**
	 * @return array
	 */
	public function GetLanguageList()
	{
		$sList = array();
		$sDir = CApi::WebMailPath().'i18n';
		if (@is_dir($sDir))
		{
			$rDirH = @opendir($sDir);
			if ($rDirH)
			{
				while (($sFile = @readdir($rDirH)) !== false)
				{
					if ('.' !== $sFile{0} && is_file($sDir.'/'.$sFile) && '.ini' === substr($sFile, -4))
					{
						$sLang = strtolower(substr($sFile, 0, -4));
						if (0 < strlen($sLang))
						{
							if ('english' === $sLang)
							{
								array_unshift($sList, substr($sFile, 0, -4));
							}
							else
							{
								$sList[] = substr($sFile, 0, -4);
							}
						}
					}
				}
				@closedir($rDirH);
			}
		}
		return $sList;
	}

	/**
	 * @return array
	 */
	public function GetThemeList()
	{
		static $sList = null;
		if (null === $sList)
		{
			$sList = array();

			$aThemes = CApi::GetConf('themes', array());
			$sDir = CApi::WebMailPath().'skins';

			if (is_array($aThemes))
			{
				foreach ($aThemes as $sTheme)
				{
					if (file_exists($sDir.'/'.$sTheme.'/styles.css'))
					{
						$sList[] = $sTheme;
					}
				}
			}
		}
		
		return $sList;
	}

	/**
	 * @param bool $bHelpdesk = false
	 * @param int $iHelpdeskIdTenant = null
	 * @param string $sHelpdeskTenantHash = ''
	 * @param string $sCalendarPubHash = ''
	 * @param string $sFileStoragePubHash = ''
	 * @return array
	 */
	public function AppData($bHelpdesk = false, $iHelpdeskIdTenant = null, $sHelpdeskTenantHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '')
	{
		$aAppData = array(
			'Auth' => false,
			'User' => null,
			'TenantHash' => '',
			'HelpdeskSiteName' => '',
			'HelpdeskIframeUrl' => '',
			'HelpdeskRedirect' => false,
			'HelpdeskThreadId' => 0,
			'HelpdeskActivatedEmail' => '',
			'HelpdeskForgotHash' => '',
			'ClientDebug' => \CApi::GetConf('labs.webmail-client-debug', false),
			'LastErrorCode' => $this->GetLastErrorCode(),
			'Token' => $this->GetCsrfToken(),
			'ZipAttachments' => !!class_exists('ZipArchive'),
			'AllowIdentities' => !!$this->oSettings->GetConf('WebMail/AllowIdentities'),
			'SocialFacebook' => false,
			'SocialGoogle' => false,
			'SocialTwitter' => false,
			'SocialEmail' => '',
			'SocialIsLoggined' => false
		);

		if(\CApi::GetConf('labs.allow-social-integration', false))
		{
			$aAppData = \api_Social::Init($aAppData, $sHelpdeskTenantHash);
		}

		if (0 < $aAppData['LastErrorCode'])
		{
			$this->ClearLastErrorCode();
		}

		if (!empty($sCalendarPubHash))
		{
			$aAppData['CalendarPubHash'] = $sCalendarPubHash;
			$aAppData['CalendarPubParams'] = CApi::DecodeKeyValues($sCalendarPubHash);
			return $aAppData;
		}

		if (!empty($sFileStoragePubHash))
		{
			$aAppData['FileStoragePubHash'] = $sFileStoragePubHash;
			$oMin = \CApi::Manager('min');
			$mMin = $oMin->GetMinByHash($sFileStoragePubHash);
			$aAppData['FileStoragePubParams'] = array();
			if (!empty($mMin['__hash__']))
			{
				$aAppData['FileStoragePubParams'] = $mMin;
			}
			return $aAppData;
		}

		$oApiHelpdeskManager = CApi::Manager('helpdesk');
		/* @var $oApiHelpdeskManager CApiHelpdeskManager */

		$mThreadId = $this->GetThreadIdFromRequestAndClear();
		if ($bHelpdesk)
		{
			$aHelpdeskMainData = null;
			$aAppData['TenantHash'] = $sHelpdeskTenantHash;

			$iUserId = $this->GetLogginedHelpdeskUserId();
			if (0 < $iUserId && $oApiHelpdeskManager)
			{
				$oHelpdeskUser = $oApiHelpdeskManager->GetUserById($iHelpdeskIdTenant, $iUserId);
				if ($oHelpdeskUser)
				{
					$aHelpdeskMainData = $oApiHelpdeskManager->GetHelpdesMainSettings($oHelpdeskUser->IdTenant);

					$aAppData['Auth'] = true;
					$aAppData['HelpdeskIframeUrl'] = $oHelpdeskUser->IsAgent ? $aHelpdeskMainData['AgentIframeUrl'] : $aHelpdeskMainData['ClientIframeUrl'];
					$aAppData['HelpdeskSiteName'] = isset($aHelpdeskMainData['SiteName']) ? $aHelpdeskMainData['SiteName'] : '';
					$aAppData['User'] = $this->appDataHelpdeskUserSettings($oHelpdeskUser);
				}
			}

			if (!$aHelpdeskMainData && $oApiHelpdeskManager)
			{
				$iIdTenant = $this->GetTenantIdByHash($sHelpdeskTenantHash);
				if (0 < $iIdTenant)
				{
					$aHelpdeskMainData = $oApiHelpdeskManager->GetHelpdesMainSettings($iIdTenant);
					$aAppData['HelpdeskSiteName'] = isset($aHelpdeskMainData['SiteName']) ? $aHelpdeskMainData['SiteName'] : '';
				}
			}

			$oHttp = \MailSo\Base\Http::SingletonInstance();

			$aAppData['HelpdeskForgotHash'] = $oHttp->GetRequest('forgot', '');
			if (0 === strlen($aAppData['HelpdeskForgotHash']))
			{
				$aAppData['HelpdeskThreadId'] = null === $mThreadId ? 0 : $mThreadId;
				$aAppData['HelpdeskActivatedEmail'] = $this->GetActivatedUserEmailAndClear();
			}

			$aAppData['App'] = array();
			$aAppData['App']['DateFormats'] = array();
			foreach (array(EDateFormat::MMDDYYYY, EDateFormat::DDMMYYYY, EDateFormat::DD_MONTH_YYYY) as $sDateFmtName)
			{
				$aAppData['App']['DateFormats'][] = $sDateFmtName;
			}
			
			return $aAppData;
		}
		else
		{
			$aAppData['HelpdeskRedirect'] = is_int($mThreadId);
			$aAppData['HelpdeskThreadId'] = null === $mThreadId ? 0 : $mThreadId;
		}

		$oDefaultAccount = null;
		$oDomain = null;

		$iUserId = $this->GetLogginedUserId();
		if (0 < $iUserId)
		{
			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');

			$aInfo = $oApiUsersManager->GetUserAccountListInformation($iUserId);
			if (is_array($aInfo) && 0 < count($aInfo))
			{
				$aAppData['Auth'] = true;

				$iDefault = 0;
				$aAccounts = array();
				foreach ($aInfo as $iAccountId => $aData)
				{
					if (is_array($aData) && !empty($aData[1]))
					{
						$aAccounts[] = array(
							'AccountID' => $iAccountId,
							'Email' => $aData[1],
							'FriendlyName' => $aData[2],
							'Signature' => array(
								'Signature' => $aData[3],
								'Type' => $aData[4],
								'Options' => $aData[5]
							)
						);

						if ($aData[0])
						{
							$iDefault = $iAccountId;
						}
					}
				}

				$aAppData['Default'] = $iDefault;
				$aAppData['Accounts'] = $aAccounts;

				$oDefaultAccount = $oApiUsersManager->GetAccountById($iDefault);
				if ($oDefaultAccount)
				{
					$aAppData['User'] = $this->appDataUserSettings($oDefaultAccount);
					if ($oApiHelpdeskManager)
					{
						$aData = $oApiHelpdeskManager->GetHelpdesMainSettings($oDefaultAccount->IdTenant);
						$aAppData['HelpdeskIframeUrl'] = isset($aAppData['User']['IsHelpdeskAgent']) && $aAppData['User']['IsHelpdeskAgent'] ?
							$aData['AgentIframeUrl'] : $aData['ClientIframeUrl'];
					}
				}
			}
		}

		$oDomain = $this->getDefaultAccountDomain($oDefaultAccount);
		$aAppData['App'] = $this->appDataDomainSettings($oDomain);
		$aAppData['Plugins'] = array();

		$aAppData['HelpdeskThreadId'] = null === $aAppData['HelpdeskThreadId'] ? 0 : $aAppData['HelpdeskThreadId'];

		CApi::Plugin()->RunHook('api-app-data', array($oDefaultAccount, &$aAppData));

		return $aAppData;
	}

	public function GetAhdSocialUser($sHelpdeskTenantHash = '', $sUserId = '')
	{
		$sTenantHash = $sHelpdeskTenantHash;
		$iIdTenant = $this->GetTenantIdByHash($sTenantHash);
		if (!is_int($iIdTenant))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}
		$oApiHelpdeskManager = CApi::Manager('helpdesk');
		$oUser = $oApiHelpdeskManager->GetUserBySocialId($iIdTenant, $sUserId);

		return $oUser;
	}

	/**
	 * @param bool $bHelpdesk = false
	 * @param int $iHelpdeskIdTenant = null
	 * @param string $sHelpdeskHash = ''
	 * @param string $sCalendarPubHash = ''
	 * @param string $sFileStoragePubHash = ''
	 *
	 * @return string
	 */
	private function compileAppData($bHelpdesk = false, $iHelpdeskIdTenant = null, $sHelpdeskHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '')
	{
		return '<script>window.pSevenAppData='.@json_encode($this->AppData($bHelpdesk, $iHelpdeskIdTenant, $sHelpdeskHash, $sCalendarPubHash, $sFileStoragePubHash)).';</script>';
	}

	/**
	 * @return array
	 */
	private function getThemeAndLanguage()
	{
		static $sLanguage = false;
		static $sTheme = false;
		if (false === $sLanguage && false === $sTheme)
		{
			$oSettings =& CApi::GetSettings();
			$sLanguage = $oSettings->GetConf('Common/DefaultLanguage');
			$sTheme = $oSettings->GetConf('WebMail/DefaultSkin');

			$oAccount = $this->GetLogginedDefaultAccount();
			if ($oAccount)
			{
				$sTheme = $oAccount->User->DefaultSkin;
				$sLanguage = $oAccount->User->DefaultLanguage;
			}
			else
			{
				/* @var $oApiDomainsManager CApiDomainsManager */
				$oApiDomainsManager = CApi::Manager('domains');

				$oInput = new api_Http();
				$oDomain = /* @var $oDomain CDomain */ $oApiDomainsManager->GetDomainByUrl($oInput->GetHost());
				if ($oDomain)
				{
					$sLanguage = $oDomain->DefaultLanguage;
					$sTheme = $oDomain->DefaultSkin;

					$sCustomLanguage = $this->GetLoginLanguage();
					if (!empty($sCustomLanguage))
					{
						$sLanguage = $sCustomLanguage;
					}
				}
			}

			$sTheme = $this->validatedThemeValue($sTheme);
		}

		return array($sLanguage, $sTheme);
	}

	/**
	 * @param string $sCalendarPubHash = ''
	 * @return string
	 */
	public function GetAppDirValue($bHelpdesk = false)
	{
		list($sLanguage, $sTheme) = $this->getThemeAndLanguage();
		return \in_array($sLanguage, array('Arabic', 'Hebrew', 'Persian')) ? 'rtl' : 'ltr';
	}

	/**
	 * @param string $sWebPath = '.'
	 * @param bool $bHelpdesk = false
	 * @param string $sCalendarPubHash = ''
	 * @param string $sFileStoragePubHash = ''
	 * @return string
	 */
	public function BuildHeadersLink($sWebPath = '.', $bHelpdesk = false, $sCalendarPubHash = '', $sFileStoragePubHash = '')
	{
		list($sLanguage, $sTheme) = $this->getThemeAndLanguage($bHelpdesk);
		
		$sVersionJs = '?'.CApi::VersionJs();
		$sWebPath = empty($sWebPath) ? '.' : $sWebPath;

		if ($bHelpdesk)
		{
			return
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/libs.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/skins/'.$sTheme.'/styles.css'.$sVersionJs.'" />';
		}
		else if (!empty($sCalendarPubHash))
		{
			return
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/libs.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/skins/'.$sTheme.'/styles.css'.$sVersionJs.'" />';
		}
		else if (!empty($sFileStoragePubHash))
		{
			return
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/libs.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/skins/'.$sTheme.'/styles.css'.$sVersionJs.'" />';
		}
		else
		{
			return
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/libs.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/skins/'.$sTheme.'/styles.css'.$sVersionJs.'" />';
		}
	}

	/**
	 * @param string $sWebPath = '.'
	 * @param bool $bHelpdesk = false
	 * @param int $iHelpdeskIdTenant = null
	 * @param string $sHelpdeskHash = ''
	 * @param string $sCalendarPubHash = ''
	 * @param string $sFileStoragePubHash = ''
	 *
	 * @return string
	 */
	public function BuildBody($sWebPath = '.', $bHelpdesk = false, $iHelpdeskIdTenant = null, $sHelpdeskHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '')
	{
		list($sLanguage, $sTheme) = $this->getThemeAndLanguage();

		$sWebPath = empty($sWebPath) ? '.' : $sWebPath;
		return
'<div class="pSevenMain"><div id="pSevenLoading"></div><div id="pSevenContent"></div><div id="pSevenHidden"></div>'.
'<div>'.
$this->compileTemplates($sTheme).
'<script src="'.$sWebPath.'/static/js/libs.js?'.CApi::VersionJs().'"></script>'.
$this->compileLanguage($sLanguage).
$this->compileAppData($bHelpdesk, $iHelpdeskIdTenant, $sHelpdeskHash, $sCalendarPubHash, $sFileStoragePubHash).
'<script src="'.$sWebPath.'/static/js/app'.($bHelpdesk ? '-helpdesk' :
	(empty($sCalendarPubHash) ? (empty($sFileStoragePubHash) ? '' : '-filestorage-pub') : '-calendar-pub')).(CApi::GetConf('labs.use-app-min-js', false) ? '.min' : '').'.js?'.CApi::VersionJs().'"></script>'.
	(CApi::Plugin()->HasJsFiles() ? '<script src="?/Plugins/js/'.CApi::Plugin()->Hash().'/"></script>' : '').
'</div></div>'."\r\n".'<!-- '.CApi::Version().' -->'
		;
	}
}
