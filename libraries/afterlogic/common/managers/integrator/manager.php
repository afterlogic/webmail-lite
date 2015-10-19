<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiIntegratorManager class summary
 *
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
	const MOBILE_KEY = 'p7mobile';

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
	 * @type string
	 */
	const TOKEN_SKIP_MOBILE_CHECK = 'p7skipmobile';

	/**
	 * @var $bCache bool
	 */
	private $bCache;

	/**
	 * Creates a new instance of the object.
	 *
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
	 *
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
	 * @param bool $bMobile Default value is **false**.
	 *
	 * @return string
	 */
	private function compileTemplates($sTheme, $bMobile = false)
	{
		$bMobile = false;
		$sHash = CApi::Plugin()->Hash();
		
		$sCacheFileName = '';
		if (CApi::GetConf('labs.cache.templates', $this->bCache))
		{
			$sCacheFileName = 'templates-'.md5(CApi::Version().$sHash).($bMobile ? '-mobile' : '').'.cache';
			$sCacheFullFileName = CApi::DataPath().'/cache/'.$sCacheFileName;
			if (file_exists($sCacheFullFileName))
			{
				return file_get_contents($sCacheFullFileName);
			}
		}

		$sResult = '';
		$sT = 'templates/'.($bMobile ? 'mobile' : 'views').'';
		$iL = strlen($sT) + 1;

		$sDirName = CApi::WebMailPath().$sT;
		$aList = $this->folderFiles($sDirName, '.html');

		foreach ($aList as $sFileName)
		{
			$sName = '';
			$iPos = strpos($sFileName, $sT.'/');
			if (false !== $iPos && 0 < $iPos)
			{
				$sName = substr($sFileName, $iPos + $iL);
			}
			else
			{
				$sName = '@errorName'.md5(rand(10000, 20000));
			}

			$sTemplateID = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(array('/', '\\'), '_', substr($sName, 0, -5)));
			$sTemplateHtml = file_get_contents($sFileName);

			$sTemplateHtml = CApi::Plugin()->ParseTemplate($sTemplateID, $sTemplateHtml);
			$sTemplateHtml = preg_replace('/\{%INCLUDE-START\/[a-zA-Z\-_]+\/INCLUDE-END%\}/', '', $sTemplateHtml);

			$sTemplateHtml = preg_replace('/<script([^>]*)>/', '&lt;script$1&gt;', $sTemplateHtml);
			$sTemplateHtml = preg_replace('/<\/script>/', '&lt;/script&gt;', $sTemplateHtml);

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

			$sTemplateHtml = preg_replace('/<script([^>]*)>/', '&lt;script$1&gt;', $sTemplateHtml);
			$sTemplateHtml = preg_replace('/<\/script>/', '&lt;/script&gt;', $sTemplateHtml);

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
	 *
	 * @return string
	 */
	private function validatedThemeValue($sTheme)
	{
		if ('' === $sTheme || !in_array($sTheme, $this->getThemeList()))
		{
			$sTheme = 'Default';
		}

		return $sTheme;
	}

	/**
	 * @param string $sLanguage
	 *
	 * @return string
	 */
	private function validatedLanguageValue($sLanguage)
	{
		if ('' === $sLanguage || !in_array($sLanguage, $this->getLanguageList()))
		{
			$sLanguage = 'English';
		}

		return $sLanguage;
	}

	/**
	 * @param string $sLanguage
	 *
	 * @return string
	 */
	private function compileLanguage($sLanguage)
	{

		$sLanguage = $this->validatedLanguageValue($sLanguage);
		
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
		if ($sLanguage === 'Arabic' || $sLanguage === 'Persian')
		{
			$sMoment = 'window.moment && window.moment.locale && window.moment.locale(\'en\');';
		}
		else
		{
			$sMoment = 'window.moment && window.moment.locale && window.moment.locale(\'' . $sMomentLanguage . '\');';
		}

		$aLang = null;
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

		CApi::Plugin()->ParseLangs($sLanguage, $aResultLang);
		
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
	public function getLoginLanguage()
	{
		$sLanguage = empty($_COOKIE[self::TOKEN_LANGUAGE]) ? '' : $_COOKIE[self::TOKEN_LANGUAGE];
		return '' === $sLanguage ? '' : $this->validatedLanguageValue($sLanguage);
	}

	/**
	 * @param string $sLanguage
	 */
	public function setLoginLanguage($sLanguage)
	{
		$sLanguage = $this->validatedLanguageValue($sLanguage);
		@setcookie(self::TOKEN_LANGUAGE, $sLanguage, 0, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @param string $sAuthToken Default value is empty string.
	 *
	 * @return int
	 */
	public function getLogginedUserId($sAuthToken = '')
	{
		$iUserId = 0;
		$sKey = '';
		if (strlen($sAuthToken) !== 0)
		{
			$sKey = \CApi::Cacher()->get('AUTHTOKEN:'.$sAuthToken);
		}
		else
		{
			$sKey = empty($_COOKIE[self::AUTH_KEY]) ? '' : $_COOKIE[self::AUTH_KEY];
		}
		if (!empty($sKey) && is_string($sKey))
		{
			$aAccountHashTable = CApi::DecodeKeyValues($sKey);
			if (is_array($aAccountHashTable) && isset($aAccountHashTable['token']) &&
				'auth' === $aAccountHashTable['token'] && 0 < strlen($aAccountHashTable['id']) && 
					is_int($aAccountHashTable['id']) && isset($aAccountHashTable['email']) && isset($aAccountHashTable['hash']))
			{
				$oApiUsersManager = \CApi::Manager('users');
				
				$oAccount = $oApiUsersManager->getAccountByEmail($aAccountHashTable['email']);
				if ($oAccount && $oAccount->IdUser == $aAccountHashTable['id'] && $aAccountHashTable['hash'] === sha1($oAccount->IncomingMailPassword . \CApi::$sSalt))
				{
					$iUserId = $aAccountHashTable['id'];
				}
			}
			CApi::Plugin()->RunHook('api-integrator-get-loggined-user-id', array(&$iUserId));
		}

		return $iUserId;
	}

	/**
	 * @return int
	 */
	public function getLogginedHelpdeskUserId()
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
	 * @return CAccount|null
	 */
	public function getLogginedDefaultAccount()
	{
		$oResult = null;
		$iUserId = $this->getLogginedUserId();
		if (0 < $iUserId)
		{
			$oApiUsers = CApi::Manager('users');
			if ($oApiUsers)
			{
				$iAccountId = $oApiUsers->getDefaultAccountId($iUserId);
				if (0 < $iAccountId)
				{
					$oAccount = $oApiUsers->getAccountById($iAccountId);
					$oResult = $oAccount instanceof \CAccount ? $oAccount : null;
				}
			}
		}
		else 
		{
			$this->logoutAccount();
		}

		return $oResult;
	}

	/**
	 * @return string
	 */
	public function getCsrfToken()
	{
		static $sToken = null;
		if (null === $sToken)
		{
			$sToken = !empty($_COOKIE[self::TOKEN_KEY]) ? $_COOKIE[self::TOKEN_KEY] : null;
		}

		if (null === $sToken)
		{
			$sToken = md5(rand(1000, 9999).CApi::$sSalt.microtime(true));
			@setcookie(self::TOKEN_KEY, $sToken, time() + 60 * 60 * 24 * 30, $this->getCookiePath(), null, null, true);
		}

		return $sToken;
	}

	/**
	 * @param int $iCode
	 */
	public function setLastErrorCode($iCode)
	{
		@setcookie(self::TOKEN_LAST_CODE, $iCode, 0, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @return int
	 */
	public function getLastErrorCode()
	{
		return isset($_COOKIE[self::TOKEN_LAST_CODE]) ? (int) $_COOKIE[self::TOKEN_LAST_CODE] : 0;
	}

	public function clearLastErrorCode()
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
	public function validateCsrfToken($sToken)
	{
		return isset($_COOKIE[self::TOKEN_KEY]) ? $sToken === $this->getCsrfToken() : true;
	}

	/**
	 * @param string $sAuthToken Default value is empty string.
	 * 
 	 * @return bool
	 */
	public function logoutAccount($sAuthToken = '')
	{
		if (strlen($sAuthToken) !== 0)
		{
			$sKey = \CApi::Cacher()->Delete('AUTHTOKEN:'.$sAuthToken);
		}
		
		@setcookie(self::AUTH_KEY, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
		@setcookie(self::TOKEN_LANGUAGE, '', 0, $this->getCookiePath());
		return true;
	}

	/**
	 * @param int $iThreadID
	 * @param string $sThreadAction Default value is empty string.
	 */
	public function setThreadIdFromRequest($iThreadID, $sThreadAction = '')
	{
		$aHashTable = array(
			'token' => 'thread_id',
			'id' => (int) $iThreadID,
			'action' => (string) $sThreadAction
		);

		CApi::LogObject($aHashTable);

		$_COOKIE[self::TOKEN_HD_THREAD_ID] = CApi::EncodeKeyValues($aHashTable);
		@setcookie(self::TOKEN_HD_THREAD_ID, CApi::EncodeKeyValues($aHashTable), 0, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @return array
	 */
	public function getThreadIdFromRequestAndClear()
	{
		$aHdThreadId = array();
		$sKey = empty($_COOKIE[self::TOKEN_HD_THREAD_ID]) ? '' : $_COOKIE[self::TOKEN_HD_THREAD_ID];
		if (!empty($sKey) && is_string($sKey))
		{
			$aUserHashTable = CApi::DecodeKeyValues($sKey);
			if (is_array($aUserHashTable) && isset($aUserHashTable['token'], $aUserHashTable['id']) &&
				'thread_id' === $aUserHashTable['token'] && 0 < strlen($aUserHashTable['id']) && is_int($aUserHashTable['id']))
			{
				$aHdThreadId['id'] = (int) $aUserHashTable['id'];
				$aHdThreadId['action'] = isset($aUserHashTable['action']) ? (string) $aUserHashTable['action'] : '';
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

		return $aHdThreadId;
	}

	public function removeUserAsActivated()
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
	 * @param bool $bForgot Default value is **false**.
	 *
	 * @return void
	 */
	public function setUserAsActivated($oHelpdeskUser, $bForgot = false)
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
	 * @return int
	 */
	public function getActivatedUserEmailAndClear()
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
	 * @param bool $bSignMe Default value is **false**.
	 *
	 * @return string
	 */
	public function setAccountAsLoggedIn(CAccount $oAccount, $bSignMe = false)
	{
		$aAccountHashTable = array(
			'token' => 'auth',
			'sign-me' => $bSignMe,
			'id' => $oAccount->IdUser,
			'email' => $oAccount->Email,
			'hash' => sha1($oAccount->IncomingMailPassword . \CApi::$sSalt)
		);
		
		$iTime = $bSignMe ? time() + 60 * 60 * 24 * 30 : 0;
		$sAccountHashTable = \CApi::EncodeKeyValues($aAccountHashTable);
		$_COOKIE[self::AUTH_KEY] = $sAccountHashTable;
		@setcookie(self::AUTH_KEY, $sAccountHashTable, $iTime, $this->getCookiePath(), null, null, true);
		
		$sAuthToken = \md5($oAccount->IdUser.$oAccount->IncomingMailLogin.\microtime(true).\rand(10000, 99999));
		
		return \CApi::Cacher()->Set('AUTHTOKEN:'.$sAuthToken, $sAccountHashTable) ? $sAuthToken : '';
	}

	/**
	 * @param CHelpdeskUser $oUser
	 * @param bool $bSignMe Default value is **false**.
	 */
	public function setHelpdeskUserAsLoggedIn(CHelpdeskUser $oUser, $bSignMe = false)
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
	public function logoutHelpdeskUser()
	{
		@setcookie(self::AUTH_HD_KEY, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
		return true;
	}

	public function skipMobileCheck()
	{
		@setcookie(self::TOKEN_SKIP_MOBILE_CHECK, '1', 0, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @return int
	 */
	public function isMobile()
	{
		if (isset($_COOKIE[self::TOKEN_SKIP_MOBILE_CHECK]) && '1' === (string) $_COOKIE[self::TOKEN_SKIP_MOBILE_CHECK])
		{
			@setcookie(self::TOKEN_SKIP_MOBILE_CHECK, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
			return 0;
		}

		return isset($_COOKIE[self::MOBILE_KEY]) ? ('1' === (string) $_COOKIE[self::MOBILE_KEY] ? 1 : 0) : -1;
	}

	/**
	 * @param bool $bMobile
	 *
	 * @return bool
	 */
	public function setMobile($bMobile)
	{
		@setcookie(self::MOBILE_KEY, $bMobile ? '1' : '0', time() + 60 * 60 * 24 * 200, $this->getCookiePath());
		return true;
	}

	public function resetCookies()
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
	 * @param string $sEmail
	 * @param string $sIncPassword
	 * @param string $sIncLogin Default value is empty string.
	 * @param string $sLanguage Default value is empty string.
	 *
	 * @throws CApiManagerException(Errs::WebMailManager_AccountDisabled) 1501
	 * @throws CApiManagerException(Errs::Mail_AccountAuthentication) 4002
	 * @throws CApiManagerException(Errs::WebMailManager_AccountCreateOnLogin) 1503
	 *
	 * @return CAccount|null|bool
	 */
	public function loginToAccount($sEmail, $sIncPassword, $sIncLogin = '', $sLanguage = '')
	{
		$oResult = null;

		/* @var $oApiUsersManager CApiUsersManager */
		$oApiUsersManager = CApi::Manager('users');

		/* @var $oApiWebmailManager CApiWebmailManager */
		$oApiWebmailManager = CApi::Manager('webmail');

		$bAuthResult = false;
		CApi::Plugin()->RunHook('api-integrator-login-to-account', array(&$sEmail, &$sIncPassword, &$sIncLogin, &$sLanguage, &$bAuthResult));

		$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);
		if ($oAccount instanceof CAccount)
		{
			if ($oAccount->IsDisabled || ($oAccount->Domain && $oAccount->Domain->IsDisabled))
			{
				throw new CApiManagerException(Errs::WebMailManager_AccountDisabled);
			}
			
			if (0 < $oAccount->IdTenant)
			{
				$oApiTenantsManager = /* @var $oApiTenantsManager CApiTenantsManager */ CApi::Manager('tenants');
				if ($oApiTenantsManager)
				{
					$oTenant = $oApiTenantsManager->getTenantById($oAccount->IdTenant);
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

			if ($oAccount->Domain->AllowWebMail && $oAccount->AllowMail)
			{
				if ($sIncPassword !== $oAccount->IncomingMailPassword)
				{
					$oAccount->IncomingMailPassword = $sIncPassword;
				}
				$oApiMailManager = CApi::Manager('mail');
				try
				{
					$oApiMailManager->validateAccountConnection($oAccount);
				}
				catch (Exception $oException)
				{
					\CApi::Plugin()->RunHook('api-integrator-login-authentication-error', array($sEmail)); 
					throw $oException;
				}
			}
			else if ($sIncPassword !== $oAccount->IncomingMailPassword)
			{
				\CApi::Plugin()->RunHook('api-integrator-login-authentication-error', array($sEmail)); 
				throw new CApiManagerException(Errs::Mail_AccountAuthentication);
			}

			$sObsoleteIncPassword = $oAccount->GetObsoleteValue('IncomingMailPassword');
			$sObsoleteLanguage = $oAccount->User->GetObsoleteValue('DefaultLanguage');
			if (null !== $sObsoleteIncPassword && $sObsoleteIncPassword !== $oAccount->IncomingMailPassword ||
				null !== $sObsoleteLanguage && $sObsoleteLanguage !== $oAccount->User->DefaultLanguage ||
				$oAccount->ForceSaveOnLogin)
			{
				$oApiUsersManager->updateAccount($oAccount);
			}

			$oApiUsersManager->updateAccountLastLoginAndCount($oAccount->IdUser);

			$oResult = $oAccount;
		}
		else if (null === $oAccount)
		{
			$aExtValues = array();
			if (0 < strlen($sIncLogin))
			{
				$aExtValues['Login'] = $sIncLogin;
			}
			$aExtValues['ApiIntegratorLoginToAccountResult'] = $bAuthResult;

			$oAccount = $oApiWebmailManager->createAccount($sEmail, $sIncPassword, $sLanguage, $aExtValues);
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
	 * @param int $iIdTenant
	 * @param string $sEmail
	 * @param string $sPassword
	 *
	 * @throws CApiManagerException(Errs::HelpdeskManager_AccountSystemAuthentication) 6008
	 * @throws CApiManagerException(Errs::HelpdeskManager_UnactivatedUser) 6010
	 * @throws CApiManagerException(Errs::HelpdeskManager_AccountAuthentication) 6004
	 *
	 * @return CHelpdeskUser|null|bool
	 */
	public function loginToHelpdeskAccount($iIdTenant, $sEmail, $sPassword)
	{
		$oResult = null;

		CApi::Plugin()->RunHook('api-integrator-login-to-helpdesk-user', array(&$sEmail, &$sPassword));

		$oApiHelpdeskManager = /* @var $oApiHelpdeskManager CApiHelpdeskManager */ CApi::Manager('helpdesk');
		$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');
		$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
		if (!$oApiHelpdeskManager || !$oApiUsersManager || !$oApiCapabilityManager ||
			!$oApiCapabilityManager->isHelpdeskSupported())
		{
			return false;
		}

		$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);
		if ($oAccount && $oAccount->IdTenant === $iIdTenant && $oApiCapabilityManager->isHelpdeskSupported($oAccount) &&
			$oAccount->IncomingMailPassword === $sPassword)
		{
			$this->setAccountAsLoggedIn($oAccount);
			$this->setThreadIdFromRequest(0);
			throw new CApiManagerException(Errs::HelpdeskManager_AccountSystemAuthentication);
		}

		$oUser = /* @var $oUser CHelpdeskUser */ $oApiHelpdeskManager->getUserByEmail($iIdTenant, $sEmail);
		if ($oUser instanceof CHelpdeskUser && $oUser->validatePassword($sPassword) && $iIdTenant === $oUser->IdTenant)
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
	 * @param int $iIdTenant
	 * @param string $sEmail
	 * @param string $sName
	 * @param string $sPassword
	 * @param bool $bCreateFromFetcher Default value is **false**.
	 *
	 * @throws CApiManagerException(Errs::HelpdeskManager_UserAlreadyExists) 6001
	 * @throws CApiManagerException(Errs::HelpdeskManager_UserCreateFailed) 6002
	 *
	 * @return CHelpdeskUser|bool
	 */
	public function registerHelpdeskAccount($iIdTenant, $sEmail, $sName, $sPassword, $bCreateFromFetcher = false)
	{
		$mResult = false;

		$oApiHelpdeskManager = /* @var $oApiHelpdeskManager CApiHelpdeskManager */ CApi::Manager('helpdesk');
		$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');
		$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
		if (!$oApiHelpdeskManager || !$oApiUsersManager || !$oApiCapabilityManager ||
			!$oApiCapabilityManager->isHelpdeskSupported())
		{
			return $mResult;
		}

		$oUser = /* @var $oUser CHelpdeskUser */ $oApiHelpdeskManager->getUserByEmail($iIdTenant, $sEmail);
		if (!$oUser)
		{
			$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);
			if ($oAccount && $oAccount->IdTenant === $iIdTenant && $oApiCapabilityManager->isHelpdeskSupported($oAccount))
			{
				throw new CApiManagerException(Errs::HelpdeskManager_UserAlreadyExists);
			}

			$oUser = new CHelpdeskUser();
			$oUser->Activated = false;
			$oUser->Email = $sEmail;
			$oUser->Name = $sName;
			$oUser->IdTenant = $iIdTenant;
			$oUser->IsAgent = false;

			$oUser->setPassword($sPassword, $bCreateFromFetcher);

			$oApiHelpdeskManager->createUser($oUser, $bCreateFromFetcher);
			if (!$oUser || 0 === $oUser->IdHelpdeskUser)
			{
				throw new CApiManagerException(Errs::HelpdeskManager_UserCreateFailed);
			}
			else
			{
				$mResult = $oUser;
			}
		}
		else
		{
			throw new CApiManagerException(Errs::HelpdeskManager_UserAlreadyExists);
		}

		return $mResult;
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sTenantHash
	 * @param string $sNotificationEmail
	 * @param string $sSocialId
	 * @param string $sSocialType
	 * @param string $sSocialName
	 *
	 * @throws CApiManagerException(Errs::HelpdeskManager_UserAlreadyExists) 6001
	 * @throws CApiManagerException(Errs::HelpdeskManager_UserCreateFailed) 6002
	 *
	 * @return bool
	 */
	public function registerSocialAccount($iIdTenant, $sTenantHash, $sNotificationEmail, $sSocialId, $sSocialType, $sSocialName)
	{
		$bResult = false;

		$oApiHelpdeskManager = /* @var $oApiHelpdeskManager CApiHelpdeskManager */ CApi::Manager('helpdesk');
		$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');
		$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
		if (!$oApiHelpdeskManager || !$oApiUsersManager || !$oApiCapabilityManager ||
			!$oApiCapabilityManager->isHelpdeskSupported())
		{
			return $bResult;
		}

		$oUser = /* @var $oUser CHelpdeskUser */ $oApiHelpdeskManager->getUserBySocialId($iIdTenant, $sSocialId);
		if (!$oUser)
		{
			$oAccount = $this->getAhdSocialUser($sTenantHash, $sSocialId);
			if ($oAccount && $oAccount->IdTenant === $iIdTenant && $oApiCapabilityManager->isHelpdeskSupported($oAccount))
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
			$oApiHelpdeskManager->createUser($oUser);
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
	 * @param string $sTenantHash
	 *
	 * @return int|bool
	 */
	public function getTenantIdByHash($sTenantHash)
	{
		$iResult = 0;

		$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
		if (0 === strlen($sTenantHash)/* && $oApiCapabilityManager->isHelpdeskSupported() && !$oApiCapabilityManager->isTenantsSupported()*/)
		{
			return 0;
		}
		else if (0 < strlen($sTenantHash))
		{
			$oApiTenantsManager = /* @var $oApiTenantsManager CApiTenantsManager */ CApi::Manager('tenants');
			if ($oApiTenantsManager)
			{
				$oTenant = $oApiTenantsManager->getTenantByHash($sTenantHash);
				if ($oTenant && $oTenant->isHelpdeskSupported())
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

		$oDomain = ($oAccount && $oAccount->IsDefaultAccount) ? $oAccount->Domain : $oApiDomainsManager->getDefaultDomain();
		if (null === $oDomain)
		{
			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');

			$iDomainId = $oApiUsersManager->getDefaultAccountDomainId($oAccount->IdUser);
			if (0 < $iDomainId)
			{
				$oDomain = $oApiDomainsManager->getDomainById($iDomainId);
			}
			else
			{
				$oDomain = $oApiDomainsManager->getDefaultDomain();
			}
		}

		return $oDomain;
	}

	/**
	 * @param CDomain $oDomain
	 * @param CAccount $oDefaultAccount Default value is **null**.
	 *
	 * @return array
	 */
	private function appDataDomainSettings($oDomain, $oDefaultAccount = null)
	{
		$aResult = array();
		if ($oDomain)
		{
			$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');

			$oSettings =& CApi::GetSettings();

			$aResult['AllowUsersChangeInterfaceSettings'] = (bool) $oDomain->AllowUsersChangeInterfaceSettings;
			$aResult['AllowUsersChangeEmailSettings'] = (bool) $oDomain->AllowUsersChangeEmailSettings;
			$aResult['AllowUsersAddNewAccounts'] = (bool) $oDomain->AllowUsersAddNewAccounts;
			$aResult['AllowOpenPGP'] = (bool) $oDomain->AllowOpenPGP;
			$aResult['AllowWebMail'] = (bool) $oDomain->AllowWebMail;
			$aResult['DefaultTab'] = $oDomain->DefaultTab;
			$aResult['AllowIosProfile'] = $oApiCapabilityManager->isIosProfileSupported();
			$aResult['DefaultTab'] = $oDomain->DefaultTab;

			$aResult['PasswordMinLength'] = $oSettings->GetConf('Common/PasswordMinLength')/*$oDomain->PasswordMinLength*/;
			$aResult['PasswordMustBeComplex'] = (bool) $oSettings->GetConf('Common/PasswordMustBeComplex')/*$oDomain->PasswordMustBeComplex*/;

			if (!\CApi::GetConf('labs.open-pgp', true))
			{
				$aResult['AllowOpenPGP'] = false;
			}

			$aRegistrationDomains = array();
			$aRegistrationQuestions = array();

			$aResult['AllowRegistration'] = (bool) $oSettings->GetConf('Common/AllowRegistration');
			$aResult['AllowPasswordReset'] = (bool) $oSettings->GetConf('Common/AllowPasswordReset');

			if ($aResult['AllowRegistration'])
			{
				$sRegistrationDomains = (string) $oSettings->GetConf('Common/RegistrationDomains');
				$aRegistrationDomains = explode(',', strtolower($sRegistrationDomains));
				$aRegistrationDomains = array_map('trim', $aRegistrationDomains);

				$sRegistrationQuestions = (string) $oSettings->GetConf('Common/RegistrationQuestions');
				$aRegistrationQuestions = explode('|', $sRegistrationQuestions);
				$aRegistrationQuestions = array_map('trim', $aRegistrationQuestions);
			}

			$aResult['RegistrationDomains'] = $aRegistrationDomains;
			$aResult['RegistrationQuestions'] = $aRegistrationQuestions;

			list($sLanguage, $sTheme, $sSiteName) = $this->getThemeAndLanguage();

			$aResult['SiteName'] = $sSiteName;
			$aResult['DefaultLanguage'] = $sLanguage;
			$aResult['DefaultLanguageShort'] = api_Utils::ConvertLanguageNameToShort($aResult['DefaultLanguage']);
			$aResult['DefaultTheme'] = $sTheme;

			$aResult['Languages'] = array();
			$aLangs = $this->getLanguageList();
			foreach ($aLangs as $sLang)
			{
				$aResult['Languages'][] = array(
					'name' => $this->getLanguageName($sLang),
					'value' => $sLang
				);
			}

			$aResult['Themes'] = array();
			$aThemes = $this->getThemeList();
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
			$aResult['JoinReplyPrefixes'] = (bool) CApi::GetConf('webmail.join-reply-prefixes', true);

			$aResult['AllowAppRegisterMailto'] = (bool) CApi::GetConf('webmail.allow-app-register-mailto', true);
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

			$aResult['AllowContactsSharing'] = $oApiCapabilityManager->isSharedContactsSupported($oDefaultAccount);

			if ($aResult['IosDetectOnLogin'])
			{
				$aResult['IosDetectOnLogin'] = isset($_COOKIE['skip_ios']) &&
					'1' === (string) $_COOKIE['skip_ios'] ? false : true;
			}

			$sCustomLanguage = $this->getLoginLanguage();
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
	 *
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
			$aResult['HasPassword'] = !!$oUser->PasswordHash;
		}

		return $aResult;
	}

	/**
	 * @param CAccount $oAccount
	 *
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
			$aResult['Layout'] = 0;
			$aResult['LoginsCount'] = $oAccount->User->LoginsCount;
			$aResult['CanLoginWithPassword'] = $oAccount->canLoginWithPassword();
			
			if ($oAccount->User->LoginsCount === 1)
			{
				$oApiSocialManager = /* @var $oApiSocialManager CApiSocialManager */ CApi::Manager('social');
				$aSocials = $oApiSocialManager->getSocials($oAccount->IdAccount);
				if (count($aSocials) > 0)
				{
					$aResult['SocialName'] = $aSocials[0]->Name;
				}
			}

			$aResult['DefaultTheme'] = $oAccount->User->DefaultSkin;
			$aResult['DefaultLanguage'] = $oAccount->User->DefaultLanguage;
			$aResult['DefaultLanguageShort'] = api_Utils::ConvertLanguageNameToShort($oAccount->User->DefaultLanguage);
			$aResult['DefaultDateFormat'] = $oAccount->User->DefaultDateFormat;
			$aResult['DefaultTimeFormat'] = $oAccount->User->DefaultTimeFormat;

			$aResult['AllowCompose'] = (bool) $oAccount->AllowCompose;
			$aResult['AllowReply'] = (bool) $oAccount->AllowReply;
			$aResult['AllowForward'] = (bool) $oAccount->AllowForward;

			$aFetcherDomains = CApi::GetConf('labs.fetchers.domains', array());
			$aResult['AllowFetcher'] = CApi::GetConf('labs.fetchers', false) &&
				($oAccount->Domain->IsInternal || \in_array($oAccount->IncomingMailServer, $aFetcherDomains));

			$iSaveMail = $oSettings->GetConf('WebMail/SaveMail');
			$iSaveMail = ESaveMail::Always !== $iSaveMail ? $oAccount->User->SaveMail : ESaveMail::Always;
			$aResult['SaveMail'] = (int) $iSaveMail;

			$aResult['ThreadsEnabled'] = !!$oAccount->Domain->UseThreads;
			$aResult['UseThreads'] = false;
			$aResult['SaveRepliedMessagesToCurrentFolder'] = false;
			$aResult['DesktopNotifications'] = (bool) $oAccount->User->DesktopNotifications;
			$aResult['AllowChangeInputDirection'] = (bool) $oAccount->User->AllowChangeInputDirection;
			$aResult['EnableOpenPgp'] = (bool) $oAccount->User->EnableOpenPgp;
			$aResult['AllowAutosaveInDrafts'] = (bool) $oAccount->User->AllowAutosaveInDrafts;
			$aResult['AutosignOutgoingEmails'] = (bool) $oAccount->User->AutosignOutgoingEmails;

			$aResult['EmailNotification'] = !empty($oAccount->User->EmailNotification) ? $oAccount->User->EmailNotification : $oAccount->Email;

			if ($aResult['ThreadsEnabled'])
			{
				$aResult['UseThreads'] = (bool) $oAccount->User->UseThreads;
				$aResult['SaveRepliedMessagesToCurrentFolder'] = (bool) $oAccount->User->SaveRepliedMessagesToCurrentFolder;
			}

			$aResult['OutlookSyncEnable'] = $oApiCapabilityManager->isOutlookSyncSupported($oAccount);
			$aResult['MobileSyncEnable'] = $oApiCapabilityManager->isMobileSyncSupported($oAccount);

			$aResult['ShowPersonalContacts'] = $oApiCapabilityManager->isPersonalContactsSupported($oAccount);
			$aResult['ShowGlobalContacts'] = $oApiCapabilityManager->isGlobalContactsSupported($oAccount, true);

			$aResult['IsCollaborationSupported'] = $oApiCapabilityManager->isCollaborationSupported();
			$aResult['AllowFilesSharing'] = (bool) CApi::GetConf('labs.files-sharing', false);
			$aResult['IsFilesSupported'] = $oApiCapabilityManager->isFilesSupported($oAccount);
			$aResult['IsHelpdeskSupported'] = $oApiCapabilityManager->isHelpdeskSupported($oAccount);
			$aResult['IsHelpdeskAgent'] = $aResult['IsHelpdeskSupported']; // TODO
			$aResult['AllowHelpdeskNotifications'] = (bool) $oAccount->User->AllowHelpdeskNotifications;
			$aResult['HelpdeskSignature'] = (string) $oAccount->User->HelpdeskSignature;
			$aResult['HelpdeskSignatureEnable'] = (bool) $oAccount->User->HelpdeskSignatureEnable;

			$aResult['LastLogin'] = 0;
			if ($oSettings->GetConf('WebMail/EnableLastLoginNotification'))
			{
				$aResult['LastLogin'] = $oAccount->User->LastLogin;
			}

			$aResult['AllowVoice'] = false;
			$aResult['VoiceProvider'] = '';
			$aResult['SipRealm'] = '';
			$aResult['SipWebsocketProxyUrl'] = '';
			$aResult['SipOutboundProxyUrl'] = '';
			$aResult['SipCallerID'] = '';
			$aResult['TwilioNumber'] = '';
			$aResult['TwilioEnable'] = true;
			$aResult['SipEnable'] = true;
			$aResult['SipImpi'] = '';
			$aResult['SipPassword'] = '';
			$aResult['FilesEnable'] = $oAccount->User->FilesEnable;

			$oApiTenants = CApi::Manager('tenants');
			/* @var $oApiTenants CApiTenantsManager */

			if ($oApiTenants)
			{
				$oTenant = 0 < $oAccount->IdTenant ?
					$oApiTenants->getTenantById($oAccount->IdTenant) : $oApiTenants->getDefaultGlobalTenant();

				if ($oTenant)
				{
					if ($oTenant->SipAllowConfiguration && $oTenant->SipAllow &&
						$oTenant->isSipSupported() &&
						$oApiCapabilityManager->isSipSupported($oAccount))
					{
						$aResult['AllowVoice'] = $oTenant->SipAllow;
						if ($aResult['AllowVoice']) {
							$aResult['AllowVoice'] = $oAccount->User->SipEnable;
						}
						$aResult['VoiceProvider'] = 'sip';
						$aResult['SipRealm'] = (string) $oTenant->SipRealm;
						$aResult['SipWebsocketProxyUrl'] = (string) $oTenant->SipWebsocketProxyUrl;
						$aResult['SipOutboundProxyUrl'] = (string) $oTenant->SipOutboundProxyUrl;
						$aResult['SipCallerID'] = (string) $oTenant->SipCallerID;

						$aResult['SipEnable'] = $oAccount->User->SipEnable;
						$aResult['SipImpi'] = $oAccount->User->SipImpi;
						$aResult['SipPassword'] = $oAccount->User->SipPassword;
					}
					else if ($oTenant->TwilioAllowConfiguration && $oTenant->TwilioAllow &&
						$oTenant->isTwilioSupported() &&
						$oApiCapabilityManager->isTwilioSupported($oAccount))
					{
						$aResult['AllowVoice'] = $oTenant->TwilioAllow;
						if ($aResult['AllowVoice']) {
							$aResult['AllowVoice'] = $oAccount->User->TwilioEnable;
						}
						$aResult['VoiceProvider'] = 'twilio';

						$aResult['TwilioNumber'] = $oAccount->User->TwilioNumber;
						$aResult['TwilioEnable'] = $oAccount->User->TwilioEnable;
					}
				}

				if ($aResult['VoiceProvider'] === 'sip' && (0 === strlen($aResult['SipRealm']) || 0 === strlen($aResult['SipWebsocketProxyUrl'])))
				{
					$aResult['AllowVoice'] = false;
					$aResult['SipRealm'] = '';
					$aResult['SipWebsocketProxyUrl'] = '';
					$aResult['SipOutboundProxyUrl'] = '';
					$aResult['SipCallerID'] = '';
					$aResult['SipEnable'] = false;
					$aResult['SipImpi'] = '';
					$aResult['SipPassword'] = '';
				}
			}

			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');

			/* @var $oApiDavManager CApiDavManager */
			$oApiDavManager = CApi::Manager('dav');

			$aResult['AllowCalendar'] = $oApiCapabilityManager->isCalendarSupported($oAccount);

			$aResult['Calendar'] = null;
			if ($aResult['AllowCalendar'] && $oApiDavManager && $oAccount->IsDefaultAccount)
			{
				/* @var $oCalUser CCalUser */
				$oCalUser = $oApiUsersManager->getOrCreateCalUser($oAccount->IdUser);
				if ($oCalUser)
				{
					$aResult['Calendar'] = array();
					$aResult['Calendar']['ShowWeekEnds'] = (bool) $oCalUser->ShowWeekEnds;
					$aResult['Calendar']['ShowWorkDay'] = (bool) $oCalUser->ShowWorkDay;
					$aResult['Calendar']['WorkDayStarts'] = (int) $oCalUser->WorkDayStarts;
					$aResult['Calendar']['WorkDayEnds'] = (int) $oCalUser->WorkDayEnds;
					$aResult['Calendar']['WeekStartsOn'] = (int) $oCalUser->WeekStartsOn;
					$aResult['Calendar']['DefaultTab'] = (int) $oCalUser->DefaultTab;

					$aResult['Calendar']['SyncLogin'] = (string) $oApiDavManager->getLogin($oAccount);
					$aResult['Calendar']['DavServerUrl'] = (string) $oApiDavManager->getServerUrl($oAccount);
					$aResult['Calendar']['DavPrincipalUrl'] = (string) $oApiDavManager->getPrincipalUrl($oAccount);
					$aResult['Calendar']['AllowReminders'] = true;
				}
			}

			$aResult['CalendarSharing'] = false;
			$aResult['CalendarAppointments'] = false;

			$aResult['AllowCalendar'] = null === $aResult['Calendar'] ? false : $aResult['AllowCalendar'];
			if ($aResult['AllowCalendar'])
			{
				$aResult['CalendarSharing'] = $oApiCapabilityManager->isCalendarSharingSupported($oAccount);
				$aResult['CalendarAppointments'] = $oApiCapabilityManager->isCalendarAppointmentsSupported($oAccount);
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
	public function getLanguageList()
	{
		static $aList = null;
		if (null === $aList)
		{
			$aList = array();

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
									array_unshift($aList, substr($sFile, 0, -4));
								}
								else
								{
									$aList[] = substr($sFile, 0, -4);
								}
							}
						}
					}
					@closedir($rDirH);
				}
			}
		}

		return $aList;
	}

	/**
	 * @return array
	 */
	public function getThemeList()
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
	 * @param CDomain $oDomain
	 *
	 * @return array
	 */
	public function getTabList($oDomain)
	{
		$aList = array();

		if ($oDomain->AllowWebMail)
		{
			$aList['mailbox'] = CApi::ClientI18N('TITLE/MAILBOX_TAB');
		}
		if ($oDomain->AllowContacts)
		{
			$aList['contacts'] = CApi::ClientI18N('TITLE/CONTACTS');
		}
		if ($oDomain->AllowCalendar)
		{
			$aList['calendar'] = CApi::ClientI18N('TITLE/CALENDAR');
		}
		if ($oDomain->AllowFiles)
		{
			$aList['files'] = CApi::ClientI18N('TITLE/FILESTORAGE');
		}
		if ($oDomain->AllowHelpdesk)
		{
			$aList['helpdesk'] = CApi::ClientI18N('TITLE/HELPDESK');
		}

		return $aList;
	}

	/**
	 * @param bool $bHelpdesk Default value is **false**.
	 * @param int $iHelpdeskIdTenant Default value is **null**.
	 * @param string $sHelpdeskTenantHash Default value is empty string.
	 * @param string $sCalendarPubHash Default value is empty string.
	 * @param string $sFileStoragePubHash Default value is empty string.
	 * @param string $sAuthToken Default value is empty string.
	 *
	 * @return array
	 */
	public function appData($bHelpdesk = false, $iHelpdeskIdTenant = null, $sHelpdeskTenantHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '', $sAuthToken = '')
	{
		$aAppData = array(
			'Auth' => false,
			'User' => null,
			'TenantHash' => $sHelpdeskTenantHash,
			'IsMobile' => 0,
			'AllowMobile' => false,
			'IsMailsuite' => false,
			'HelpdeskSiteName' => '',
			'HelpdeskIframeUrl' => '',
			'HelpdeskRedirect' => false,
			'HelpdeskThreadId' => 0,
			'HelpdeskActivatedEmail' => '',
			'HelpdeskForgotHash' => '',
			'ClientDebug' => \CApi::GetConf('labs.webmail-client-debug', false),
			'MailExpandFolders' => \CApi::GetConf('labs.mail-expand-folders', false),
			'HtmlEditorDefaultFontName' => \CApi::GetConf('labs.htmleditor-default-font-name', ''),
			'HtmlEditorDefaultFontSize' => \CApi::GetConf('labs.htmleditor-default-font-size', ''),
			'AllowSaveAsPdf' => !!\CApi::GetConf('labs.allow-save-as-pdf', false),
			'LastErrorCode' => $this->getLastErrorCode(),
			'Token' => $this->getCsrfToken(),
			'ZipAttachments' => !!class_exists('ZipArchive'),
			'AllowIdentities' => !!$this->oSettings->GetConf('WebMail/AllowIdentities'),
			'SocialEmail' => '',
			'SocialIsLoggedIn' => false,
			'Links' => array(
				'ImportingContacts' => \CApi::GetConf('links.importing-contacts', ''),
				'OutlookSyncPlugin32' => \CApi::GetConf('links.outlook-sync-plugin-32', ''),
				'OutlookSyncPlugin64' => \CApi::GetConf('links.outlook-sync-plugin-64', ''),
				'OutlookSyncPluginReadMore' => \CApi::GetConf('links.outlook-sync-read-more', '')
			)
		);
		
		CApi::Plugin()->RunHook('api-pre-app-data', array(&$aAppData));

		$oApiCapability = \CApi::Manager('capability');
		if ($oApiCapability)
		{
			if ($oApiCapability->isNotLite())
			{
				$aAppData['IsMobile'] = $this->isMobile();
				$aAppData['AllowMobile'] = true;
			}

			$aAppData['IsMailsuite'] = $oApiCapability->isMailsuite();
		}

		$iIdTenant = 0;

/*		TODO: sash
		if (\CApi::GetConf('labs.allow-social-integration', true))
		{
			\api_Social::init($aAppData, $sHelpdeskTenantHash);
		}
*/
		if (0 < $aAppData['LastErrorCode'])
		{
			$this->clearLastErrorCode();
		}

		$oAccount = null;
		if (!empty($sCalendarPubHash))
		{
			$oAccount = $this->getLogginedDefaultAccount();
			if ($oAccount)
			{
				$aAppData['Auth'] = true;
				$aAppData['User'] = $this->appDataUserSettings($oAccount);
			}

			$aAppData['CalendarPubHash'] = $sCalendarPubHash;
			$aAppData['IsMobile'] = 0;

			return $aAppData;
		}

		if (!empty($sFileStoragePubHash))
		{
			$aAppData['FileStoragePubHash'] = $sFileStoragePubHash;

			$oMin = \CApi::Manager('min');
			$mMin = $oMin->getMinByHash($sFileStoragePubHash);

			$aAppData['FileStoragePubParams'] = array();
			if (!empty($mMin['__hash__']))
			{
				$aAppData['FileStoragePubParams'] = $mMin;
			}

			$aAppData['IsMobile'] = 0;
			return $aAppData;
		}

		$oApiHelpdeskManager = CApi::Manager('helpdesk');
		/* @var $oApiHelpdeskManager CApiHelpdeskManager */

		$oApiTenant = CApi::Manager('tenants');
		/* @var $oApiTenant CApiTenantsManager */

		$oTenant = $oApiTenant ? $oApiTenant->getDefaultGlobalTenant() : null;

		$aAppData['LoginStyleImage'] = '';
		$aAppData['AppStyleImage'] = '';
		$aAppData['HelpdeskSiteName'] = '';
		$aAppData['HelpdeskStyleImage'] = '';

		if ($oTenant)
		{
			$aAppData['LoginStyleImage'] = $oTenant->LoginStyleImage;
			$aAppData['AppStyleImage'] = $oTenant->AppStyleImage;
		}

		$aThreadId = $this->getThreadIdFromRequestAndClear();
		$mThreadId = isset($aThreadId['id']) ? $aThreadId['id'] : null;
		$sThreadAction = isset($aThreadId['action']) ? $aThreadId['action'] : '';
		if ($bHelpdesk)
		{
			$aHelpdeskMainData = null;
			$aAppData['TenantHash'] = $sHelpdeskTenantHash;
			$aAppData['IsMobile'] = 0;

			$iUserId = $this->getLogginedHelpdeskUserId();
			if (0 < $iUserId && $oApiHelpdeskManager)
			{
				$oHelpdeskUser = $oApiHelpdeskManager->getUserById($iHelpdeskIdTenant, $iUserId);
				if ($oHelpdeskUser)
				{
					$aHelpdeskMainData = $oApiHelpdeskManager->getHelpdeskMainSettings($oHelpdeskUser->IdTenant);

					$aAppData['Auth'] = true;
					$aAppData['HelpdeskIframeUrl'] = $oHelpdeskUser->IsAgent ? $aHelpdeskMainData['AgentIframeUrl'] : $aHelpdeskMainData['ClientIframeUrl'];
					$aAppData['HelpdeskSiteName'] = isset($aHelpdeskMainData['SiteName']) ? $aHelpdeskMainData['SiteName'] : '';
					$aAppData['User'] = $this->appDataHelpdeskUserSettings($oHelpdeskUser);
				}
			}

			if (!$aHelpdeskMainData && $oApiHelpdeskManager)
			{
				$iIdTenant = $this->getTenantIdByHash($sHelpdeskTenantHash);
				$aHelpdeskMainData = $oApiHelpdeskManager->getHelpdeskMainSettings($iIdTenant);
				$aAppData['HelpdeskSiteName'] = isset($aHelpdeskMainData['SiteName']) ? $aHelpdeskMainData['SiteName'] : '';
				$aAppData['HelpdeskStyleImage'] = isset($aHelpdeskMainData['StyleImage']) &&
				isset($aHelpdeskMainData['StyleAllow']) ? $aHelpdeskMainData['StyleImage'] : '';
			}

			$oHttp = \MailSo\Base\Http::SingletonInstance();

			$aAppData['HelpdeskForgotHash'] = $oHttp->GetRequest('forgot', '');
			if (0 === strlen($aAppData['HelpdeskForgotHash']))
			{
				$aAppData['HelpdeskThreadId'] = null === $mThreadId ? 0 : $mThreadId;
				$aAppData['HelpdeskActivatedEmail'] = $this->getActivatedUserEmailAndClear();
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
			$aAppData['HelpdeskThreadAction'] = $sThreadAction ? $sThreadAction : '';
		}

		$oDefaultAccount = null;
		$oDomain = null;

		$iUserId = $this->getLogginedUserId($sAuthToken);
		if (0 < $iUserId)
		{
			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');

			$aInfo = $oApiUsersManager->getUserAccounts($iUserId);
			
			if (is_array($aInfo) && 0 < count($aInfo))
			{
				$aAppData['Auth'] = true;

				$iDefault = 0;
				$iDefaultIndex = 0;
				$aAccounts = array();
				$aDefaultAccount = array();
				foreach ($aInfo as $iAccountId => $aData)
				{
					if (is_array($aData) && !empty($aData[1]))
					{
						$aAccount = array(
							'AccountID' => $iAccountId,
							'Email' => $aData[1],
							'FriendlyName' => $aData[2],
							'Signature' => array(
								'Signature' => $aData[3],
								'Type' => $aData[4],
								'Options' => $aData[5]
							),
							'IsPasswordSpecified' => $aData[6],
							'AllowMail' => $aData[7]
						);

						if ($aData[0])
						{
							$aDefaultAccount = $aAccount;
							$iDefault = $iAccountId;
							$iDefaultIndex = count($aAccounts);
						}
						else
						{
							$aAccounts[] = $aAccount;
						}
					}
				}

				$aAppData['Default'] = $iDefault;

				$oDefaultAccount = $oApiUsersManager->getAccountById($iDefault);
				if ($oDefaultAccount)
				{
					$aAppData['User'] = $this->appDataUserSettings($oDefaultAccount);
					if ($oApiHelpdeskManager)
					{
						$aData = $oApiHelpdeskManager->getHelpdeskMainSettings($oDefaultAccount->IdTenant);
						$aAppData['HelpdeskIframeUrl'] = isset($aAppData['User']['IsHelpdeskAgent']) && $aAppData['User']['IsHelpdeskAgent'] ?
							$aData['AgentIframeUrl'] : $aData['ClientIframeUrl'];
					}
				}
			}
		}

		if ($aAppData['Auth'])
		{
			if (0 < $oDefaultAccount->IdTenant)
			{
				$aAppData['AppStyleImage'] = '';
				$oAccountTenant = $oApiTenant ? (0 < $oDefaultAccount->IdTenant ? $oApiTenant->getTenantById($oDefaultAccount->IdTenant) : $oApiTenant->getDefaultGlobalTenant()) : null;
				if ($oAccountTenant)
				{
					$aAppData['AppStyleImage'] = $oAccountTenant->AppStyleImage;
				}
			}
		}

		$oDomain = $this->getDefaultAccountDomain($oDefaultAccount);
		if ($oDefaultAccount)
		{
			array_splice($aAccounts, $iDefaultIndex, 0, array($aDefaultAccount));
			$aAppData['Accounts'] = $aAccounts;
		}
		$aAppData['App'] = $this->appDataDomainSettings($oDomain, $oDefaultAccount);
		if (!isset($aAppData['Plugins']))
		{
			$aAppData['Plugins'] = array();
		}

		$aAppData['HelpdeskThreadId'] = null === $aAppData['HelpdeskThreadId'] ? 0 : $aAppData['HelpdeskThreadId'];
		
		CApi::Plugin()->RunHook('api-app-data', array($oDefaultAccount, &$aAppData));

		return $aAppData;
	}

	/**
	 * @param string $sHelpdeskTenantHash Default value is empty string.
	 * @param string $sUserId Default value is empty string.
	 *
	 * @throws \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter) 103
	 *
	 * @return CUser|bool
	 */
	public function getAhdSocialUser($sHelpdeskTenantHash = '', $sUserId = '')
	{
		$sTenantHash = $sHelpdeskTenantHash;
		$iIdTenant = $this->getTenantIdByHash($sTenantHash);
		if (!is_int($iIdTenant))
		{
			throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::InvalidInputParameter);
		}
		$oApiHelpdeskManager = CApi::Manager('helpdesk');
		$oUser = $oApiHelpdeskManager->getUserBySocialId($iIdTenant, $sUserId);

		return $oUser;
	}

	/**
	 * @param bool $bHelpdesk Default value is **false**.
	 * @param int $iHelpdeskIdTenant Default value is **null**.
	 * @param string $sHelpdeskHash Default value is empty string.
	 * @param string $sCalendarPubHash Default value is empty string.
	 * @param string $sFileStoragePubHash Default value is empty string.
	 *
	 * @return string
	 */
	private function compileAppData($bHelpdesk = false, $iHelpdeskIdTenant = null, $sHelpdeskHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '')
	{
		return '<script>window.pSevenAppData='.@json_encode($this->appData($bHelpdesk, $iHelpdeskIdTenant, $sHelpdeskHash, $sCalendarPubHash, $sFileStoragePubHash)).';</script>';
	}

	/**
	 * @return array
	 */
	private function getThemeAndLanguage()
	{
		static $sLanguage = false;
		static $sTheme = false;
		static $sSiteName = false;

		if (false === $sLanguage && false === $sTheme && false === $sSiteName)
		{
			$oSettings =& CApi::GetSettings();
			
			$sSiteName = $oSettings->GetConf('Common/SiteName');
			$sLanguage = $oSettings->GetConf('Common/DefaultLanguage');
			$sTheme = $oSettings->GetConf('WebMail/DefaultSkin');

			$oAccount = $this->getLogginedDefaultAccount();
			if ($oAccount)
			{
				$sSiteName = $oAccount->Domain->SiteName;
				$sTheme = $oAccount->User->DefaultSkin;
				$sLanguage = $oAccount->User->DefaultLanguage;
			}
			else
			{
				/* @var $oApiDomainsManager CApiDomainsManager */
				$oApiDomainsManager = CApi::Manager('domains');

				$oInput = new api_Http();
				$oDomain = /* @var $oDomain CDomain */ $oApiDomainsManager->getDomainByUrl($oInput->GetHost());
				
				if ($oDomain)
				{
					$sTheme = $oDomain->DefaultSkin;
					$sLanguage = $this->getLoginLanguage();

					if (empty($sLanguage))
					{
						$sLanguage = $this->getBrowserLanguage();
					}
					
					if (empty($sLanguage))
					{
						$sLanguage = $oDomain->DefaultLanguage;
					}

					$sSiteName = $oDomain->SiteName;
				}
			}

			$sLanguage = $this->validatedLanguageValue($sLanguage);
            $this->setLoginLanguage($sLanguage); // todo: sash
			$sTheme = $this->validatedThemeValue($sTheme);
		}
		
		/*** temporary fix to the problems in mobile version in rtl mode ***/
		
		/* @var $oApiIntegrator \CApiIntegratorManager */
		$oApiIntegrator = \CApi::Manager('integrator');

		/* @var $oApiCapability \CApiCapabilityManager */
		$oApiCapability = \CApi::Manager('capability');
		
		if (in_array($sLanguage, array('Arabic', 'Hebrew', 'Persian')) && $oApiIntegrator && $oApiCapability && $oApiCapability->isNotLite() && 1 === $oApiIntegrator->isMobile())
		{
			$sLanguage = 'English';
		}
		
		/*** end of temporary fix to the problems in mobile version in rtl mode ***/

		return array($sLanguage, $sTheme, $sSiteName);
	}

	private function getBrowserLanguage()
	{
		$aLanguages = array(
			'ar-dz' => 'Arabic', 'ar-bh' => 'Arabic', 'ar-eg' => 'Arabic', 'ar-iq' => 'Arabic', 'ar-jo' => 'Arabic', 'ar-kw' => 'Arabic',
			'ar-lb' => 'Arabic', 'ar-ly' => 'Arabic', 'ar-ma' => 'Arabic', 'ar-om' => 'Arabic', 'ar-qa' => 'Arabic', 'ar-sa' => 'Arabic',
			'ar-sy' => 'Arabic', 'ar-tn' => 'Arabic', 'ar-ae' => 'Arabic', 'ar-ye' => 'Arabic', 'ar' => 'Arabic',
			'bg' => 'Bulgarian',
			'zh-cn' => 'Chinese-Simplified', 'zh-hk' => 'Chinese-Simplified', 'zh-mo' => 'Chinese-Simplified', 'zh-sg' => 'Chinese-Simplified',
			'zh-tw' => 'Chinese-Simplified', 'zh' => 'Chinese-Simplified',
			'cs' => 'Czech',
			'da' => 'Danish',
			'nl-be' => 'Dutch', 'nl' => 'Dutch',
			'en-au' => 'English', 'en-bz' => 'English ', 'en-ca' => 'English', 'en-ie' => 'English', 'en-jm' => 'English',
			'en-nz' => 'English', 'en-ph' => 'English', 'en-za' => 'English', 'en-tt' => 'English', 'en-gb' => 'English',
			'en-us' => 'English', 'en-zw' => 'English', 'en' => 'English', 'us' => 'English',
			'et' => 'Estonian', 'fi' => 'Finnish',
			'fr-be' => 'French', 'fr-ca' => 'French', 'fr-lu' => 'French', 'fr-mc' => 'French', 'fr-ch' => 'French', 'fr' => 'French',
			'de-at' => 'German', 'de-li' => 'German', 'de-lu' => 'German', 'de-ch' => 'German', 'de' => 'German',
			'el' => 'Greek', 'he' => 'Hebrew', 'hu' => 'Hungarian', 'it-ch' => 'Italian', 'it' => 'Italian',
			'ja' => 'Japanese', 'ko' => 'Korean', 'lv' => 'Latvian', 'lt' => 'Lithuanian',
			'nb-no' => 'Norwegian', 'nn-no' => 'Norwegian', 'no' => 'Norwegian', 'pl' => 'Polish',
			'pt-br' => 'Portuguese-Brazil', 'pt' => 'Portuguese-Portuguese', 'pt-pt' => 'Portuguese-Portuguese',
			'ro-md' => 'Romanian', 'ro' => 'Romanian',
			'ru-md' => 'Russian', 'ru' => 'Russian', 'sr' => 'Serbian',
			'es-ar' => 'Spanish', 'es-bo' => 'Spanish', 'es-cl' => 'Spanish', 'es-co' => 'Spanish', 'es-cr' => 'Spanish',
			'es-do' => 'Spanish', 'es-ec' => 'Spanish', 'es-sv' => 'Spanish', 'es-gt' => 'Spanish', 'es-hn' => 'Spanish)',
			'es-mx' => 'Spanish', 'es-ni' => 'Spanish', 'es-pa' => 'Spanish', 'es-py' => 'Spanish', 'es-pe' => 'Spanish',
			'es-pr' => 'Spanish', 'es-us' => 'Spanish ', 'es-uy' => 'Spanish', 'es-ve' => 'Spanish', 'es' => 'Spanish',
			'sv-fi' => 'Swedish', 'sv' => 'Swedish', 'th' => 'Thai', 'tr' => 'Turkish', 'uk' => 'Ukrainian', 'vi' => 'Vietnamese', 'sl' => 'Slovenian'
		);
		
		$sLanguage = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']) : 'en';
		$aTempLanguages = preg_split('/[,;]+/', $sLanguage);
		$sLanguage = !empty($aTempLanguages[0]) ? $aTempLanguages[0] : 'en';

		$sLanguageShort = substr($sLanguage, 0, 2);
		
		return \array_key_exists($sLanguage, $aLanguages) ? $aLanguages[$sLanguage] :
			(\array_key_exists($sLanguageShort, $aLanguages) ? $aLanguages[$sLanguageShort] : '');
	}

	/**
	 * @param bool $bHelpdesk Default value is **false**.
	 *
	 * @return string
	 */
	public function getAppDirValue($bHelpdesk = false)
	{
		list($sLanguage, $sTheme, $sSiteName) = $this->getThemeAndLanguage();
		return \in_array($sLanguage, array('Arabic', 'Hebrew', 'Persian')) ? 'rtl' : 'ltr';
	}

	/**
	 * @param string $sWebPath Default value is **'.'**.
	 * @param bool $bHelpdesk Default value is **false**.
	 * @param int $iHelpdeskIdTenant Default value is **null**.
	 * @param string $sHelpdeskHash Default value is empty string.
	 * @param string $sCalendarPubHash Default value is empty string.
	 * @param string $sFileStoragePubHash Default value is empty string.
	 * @param bool $bMobile Default value is **false**.
	 * @return string
	 */
	public function buildHeadersLink($sWebPath = '.', $bHelpdesk = false, $iHelpdeskIdTenant = null, $sHelpdeskHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '', $bMobile = false)
	{
		list($sLanguage, $sTheme, $sSiteName) = $this->getThemeAndLanguage($bHelpdesk);
		
		$sMobileSuffix = $bMobile ? '-mobile' : '';
		
		$sVersionJs = '?'.CApi::VersionJs();
		$sWebPath = empty($sWebPath) ? '.' : $sWebPath;

		if ($bHelpdesk)
		{
			$sS = 
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/libs.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/skins/'.$sTheme.'/styles'.$sMobileSuffix.'.css'.$sVersionJs.'" />';

			$oApiTenant = /* @var $oApiTenant CApiTenantsManager */ CApi::Manager('tenants');

			$oTenant = $oApiTenant && null !== $iHelpdeskIdTenant ?
				(0 < $iHelpdeskIdTenant ? $oApiTenant->getTenantById($iHelpdeskIdTenant) : $oApiTenant->getDefaultGlobalTenant()) : null;

			if ($oTenant && $oTenant->HelpdeskStyleAllow)
			{
				$sS .= '<style>'.strip_tags($oTenant->getHelpdeskStyleText()).'</style>';
			}

			return $sS;

		}
		else if (!empty($sCalendarPubHash))
		{
			return
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/libs.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/skins/'.$sTheme.'/styles'.$sMobileSuffix.'.css'.$sVersionJs.'" />';
		}
		else if (!empty($sFileStoragePubHash))
		{
			return
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/libs.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/skins/'.$sTheme.'/styles'.$sMobileSuffix.'.css'.$sVersionJs.'" />';
		}
		else
		{
			$sS =
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/libs.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/skins/'.$sTheme.'/styles'.$sMobileSuffix.'.css'.$sVersionJs.'" />';
			
			$sS .= '<style>'.\CApi::Plugin()->CompileCss().'</style>';
			return $sS;
		}
	}

	/**
	 * @param string $sWebPath Default value is **'.'**.
	 * @param bool $bHelpdesk Default value is **false**.
	 * @param int $iHelpdeskIdTenant Default value is **null**.
	 * @param string $sHelpdeskHash Default value is empty string.
	 * @param string $sCalendarPubHash Default value is empty string.
	 * @param string $sFileStoragePubHash Default value is empty string.
	 * @param bool $bMobile Default value is **false**.
	 *
	 * @return string
	 */
	public function buildBody($sWebPath = '.', $bHelpdesk = false, $iHelpdeskIdTenant = null, $sHelpdeskHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '', $bMobile = false)
	{
		list($sLanguage, $sTheme, $sSiteName) = $this->getThemeAndLanguage();

		$sMobileSuffix = $bMobile && !$bHelpdesk ? '-mobile' : '';
		$sWebPath = empty($sWebPath) ? '.' : $sWebPath;
		return
'<div class="pSevenMain"><div id="pSevenLoading"></div><div id="pSevenContent"></div><div id="pSevenHidden"></div>'.
'<div>'.
$this->compileTemplates($sTheme, $bMobile).
'<script src="'.$sWebPath.'/static/js/libs.js?'.CApi::VersionJs().'"></script>'.
$this->compileLanguage($sLanguage).
$this->compileAppData($bHelpdesk, $iHelpdeskIdTenant, $sHelpdeskHash, $sCalendarPubHash, $sFileStoragePubHash).
'<script src="'.$sWebPath.'/static/js/app'.$sMobileSuffix.($bHelpdesk ? '-helpdesk' :
	(empty($sCalendarPubHash) ? (empty($sFileStoragePubHash) ? '' : '-filestorage-pub') : '-calendar-pub')).(CApi::GetConf('labs.use-app-min-js', false) ? '.min' : '').'.js?'.CApi::VersionJs().'"></script>'.
	(CApi::Plugin()->HasJsFiles() ? '<script src="?/Plugins/js/'.CApi::Plugin()->Hash().'/"></script>' : '').
'</div></div>'."\r\n".'<!-- '.CApi::Version().' -->'
		;
	}
	
	public static function getAuthKey()
	{
		return self::AUTH_KEY;
	}
	
}
