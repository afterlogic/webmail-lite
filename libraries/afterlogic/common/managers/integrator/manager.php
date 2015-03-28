<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
	 * @param bool $bMobile = false
	 * @return string
	 */
	private function compileTemplates($sTheme, $bMobile = false)
	{
		$bMobile = false; // false
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

//			$sThemeFileName = '';
//			if (0 < strlen($sTheme))
//			{
//				$iPos = strpos($sFileName, $sT.'/');
//				if (false !== $iPos && 0 < $iPos)
//				{
//					$sThemeFileName = substr($sFileName, $iPos + $iL);
//				}
//			}
//
//			if (0 < strlen($sThemeFileName))
//			{
//				$sThemeFileName = CApi::WebMailPath().'skins/'.$sTheme.'/templates/'.$sThemeFileName;
//				if (file_exists($sThemeFileName))
//				{
//					$sFileName = $sThemeFileName;
//				}
//			}

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
		$sMoment = 'window.moment && window.moment.lang && window.moment.lang(\'' . $sMomentLanguage . '\');';

/*		
		$sMomentFileName = CApi::WebMailPath().'i18n/moment/'.$sMomentLanguage.'.js';
		if (file_exists($sMomentFileName))
		{
			$sMoment = file_get_contents($sMomentFileName);
			$sMoment = preg_replace('/\/\/[^\n]+\n/', '', $sMoment);
		}
*/
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
	 * @param string $sAuthToken
	 * @return int
	 */
	public function GetLogginedUserId($sAuthToken = '')
	{
		$iUserId = 0;
		$sKey = '';
		if (strlen($sAuthToken) !== 0)
		{
			$sKey = \CApi::Cacher()->Get('AUTHTOKEN:'.$sAuthToken);
		}
		else
		{
			$sKey = empty($_COOKIE[self::AUTH_KEY]) ? '' : $_COOKIE[self::AUTH_KEY];
		}
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
	 * @param string $sAuthToken = ''
	 * 
 	 * @return bool
	 */
	public function LogoutAccount($sAuthToken)
	{
		if (strlen($sAuthToken) !== 0)
		{
			$sKey = \CApi::Cacher()->Delete('AUTHTOKEN:'.$sAuthToken);
		}
		
		@setcookie(self::AUTH_KEY, '', time() - 60 * 60 * 24 * 30, $this->getCookiePath());
		return true;
	}

	/**
	 * @param int $iThreadID
	 *
	 * @return void
	 */
	public function SetThreadIdFromRequest($iThreadID, $sThreadAction = '')
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
	public function GetThreadIdFromRequestAndClear()
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
	 * @return string
	 */
	public function SetAccountAsLoggedIn(CAccount $oAccount, $bSignMe = false)
	{
		$aAccountHashTable = array(
			'token' => 'auth',
			'sign-me' => $bSignMe,
			'id' => $oAccount->IdUser
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

	public function SkipMobileCheck()
	{
		@setcookie(self::TOKEN_SKIP_MOBILE_CHECK, '1', 0, $this->getCookiePath(), null, null, true);
	}

	/**
	 * @return int
	 */
	public function IsMobile()
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
	 * @return bool
	 */
	public function SetMobile($bMobile)
	{
		@setcookie(self::MOBILE_KEY, $bMobile ? '1' : '0', time() + 60 * 60 * 24 * 200, $this->getCookiePath());
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
	 * @param string $sEmail
	 * @param string $sIncPassword
	 * @param string $sIncLogin = ''
	 * @param string $sLanguage = ''
	 * @return CAccount | null | bool
	 */
	public function LoginToAccount($sEmail, $sIncPassword, $sIncLogin = '', $sLanguage = '')
	{
		$oResult = null;

		/* @var $oApiUsersManager CApiUsersManager */
		$oApiUsersManager = CApi::Manager('users');

		/* @var $oApiWebmailManager CApiWebmailManager */
		$oApiWebmailManager = CApi::Manager('webmail');

		$bAuthResult = false;
		CApi::Plugin()->RunHook('api-integrator-login-to-account', array(&$sEmail, &$sIncPassword, &$sIncLogin, &$sLanguage, &$bAuthResult));

		$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);
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

			if ($oAccount->Domain->AllowWebMail)
			{
				if ($sIncPassword !== $oAccount->IncomingMailPassword)
				{
					$oAccount->IncomingMailPassword = $sIncPassword;
				}
				$oApiMailManager = CApi::Manager('mail');
				$oApiMailManager->ValidateAccountConnection($oAccount);
			}
			else if ($sIncPassword !== $oAccount->IncomingMailPassword)
			{
				throw new CApiManagerException(Errs::Mail_AccountAuthentication);
			}

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
			$aExtValues['ApiIntegratorLoginToAccountResult'] = $bAuthResult;

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
	 * @return CHelpdeskUser|bool
	 */
	public function RegisterHelpdeskAccount($iIdTenant, $sEmail, $sName, $sPassword, $bCreateFromFetcher = false)
	{
		$mResult = false;

		$oApiHelpdeskManager = /* @var $oApiHelpdeskManager CApiHelpdeskManager */ CApi::Manager('helpdesk');
		$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');
		$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
		if (!$oApiHelpdeskManager || !$oApiUsersManager || !$oApiCapabilityManager ||
			!$oApiCapabilityManager->IsHelpdeskSupported())
		{
			return $mResult;
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

			$oUser->SetPassword($sPassword, $bCreateFromFetcher);

			$oApiHelpdeskManager->CreateUser($oUser, $bCreateFromFetcher);
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
	 * @param CAccount $oDefaultAccount = null
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
			$aResult['AllowWebMail'] = !$oDomain->IsDefaultTenantDomain ? (bool) $oDomain->AllowWebMail : false;
			$aResult['DefaultTab'] = $oDomain->DefaultTab;
			$aResult['AllowIosProfile'] = $oApiCapabilityManager->IsIosProfileSupported();
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

//			$aResult['SiteName'] = $oDomain->SiteName;
//			$aResult['DefaultLanguage'] = $oDomain->DefaultLanguage;
//			$aResult['DefaultLanguageShort'] = api_Utils::ConvertLanguageNameToShort($aResult['DefaultLanguage']);
//			$aResult['DefaultTheme'] = $oDomain->DefaultSkin;

			$aResult['SiteName'] = $sSiteName;
			$aResult['DefaultLanguage'] = $sLanguage;
			$aResult['DefaultLanguageShort'] = api_Utils::ConvertLanguageNameToShort($aResult['DefaultLanguage']);
			$aResult['DefaultTheme'] = $sTheme;

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

			$aResult['AllowContactsSharing'] = $oApiCapabilityManager->IsSharedContactsSupported($oDefaultAccount);

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

			if ($aResult['ThreadsEnabled'])
			{
				$aResult['UseThreads'] = (bool) $oAccount->User->UseThreads;
				$aResult['SaveRepliedMessagesToCurrentFolder'] = (bool) $oAccount->User->SaveRepliedMessagesToCurrentFolder;
			}

			$aResult['OutlookSyncEnable'] = $oApiCapabilityManager->IsOutlookSyncSupported($oAccount);
			$aResult['MobileSyncEnable'] = $oApiCapabilityManager->IsMobileSyncSupported($oAccount);

			$aResult['ShowPersonalContacts'] = $oApiCapabilityManager->IsPersonalContactsSupported($oAccount);
			$aResult['ShowGlobalContacts'] = $oApiCapabilityManager->IsGlobalContactsSupported($oAccount, true);

			$aResult['IsCollaborationSupported'] = $oApiCapabilityManager->IsCollaborationSupported();
			$aResult['AllowFilesSharing'] = (bool) CApi::GetConf('labs.files-sharing', false);
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
						if ($aResult['AllowVoice']) {
							$aResult['AllowVoice'] = $oAccount->User->SipEnable;
						}
						$aResult['VoiceProvider'] = 'sip';
						$aResult['SipRealm'] = (string) $oTenant->SipRealm;
						$aResult['SipWebsocketProxyUrl'] = (string) $oTenant->SipWebsocketProxyUrl;
						$aResult['SipOutboundProxyUrl'] = (string) $oTenant->SipOutboundProxyUrl;
						$aResult['SipCallerID'] = (string) $oTenant->SipCallerID;

						$aResult['SipEnable'] = $oAccount->User->SipEnable;
//						$aResult['VoiceImpi'] = $oAccount->User->SipImpi;
						$aResult['SipImpi'] = $oAccount->User->SipImpi;
//						$aResult['VoicePassword'] = $oAccount->User->SipPassword;
						$aResult['SipPassword'] = $oAccount->User->SipPassword;
					}
					else if ($oTenant->TwilioAllowConfiguration && $oTenant->TwilioAllow &&
						$oTenant->IsTwilioSupported() &&
						$oApiCapabilityManager->IsTwilioSupported($oAccount))
					{
						$aResult['AllowVoice'] = $oTenant->TwilioAllow;
						if ($aResult['AllowVoice']) {
							$aResult['AllowVoice'] = $oAccount->User->TwilioEnable;
						}
						$aResult['VoiceProvider'] = 'twilio';

//						$aResult['VoiceAccountSID'] = (string) $oTenant->TwilioAccountSID;
//						$aResult['VoiceAuthToken'] = (string) $oTenant->TwilioAuthToken;
//						$aResult['VoiceAppSID'] = (string) $oTenant->TwilioAppSID;

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
			$oApiSocial /* @var $oApiSocial \CApiSocialManager */ = \CApi::Manager('social');
			$aSocials = $oApiSocial->GetSocials($oAccount->IdAccount);
			$aResult['SocialAccounts'] = array();
			foreach ($aSocials as $oSocial)
			{
				if ($oSocial && $oSocial instanceof \CSocial)
				{
					$aResult['SocialAccounts'][] = $oSocial->ToArray();
				}
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
	 * @param CDomain $oDomain
	 *
	 * @return array
	 */
	public function GetTabList($oDomain)
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
	 * @param bool $bHelpdesk = false
	 * @param int $iHelpdeskIdTenant = null
	 * @param string $sHelpdeskTenantHash = ''
	 * @param string $sCalendarPubHash = ''
	 * @param string $sFileStoragePubHash = ''
	 * @param string $sAuthToken = ''
	 * @return array
	 */
	public function AppData($bHelpdesk = false, $iHelpdeskIdTenant = null, $sHelpdeskTenantHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '', $sAuthToken = '')
	{
		$aAppData = array(
			'Auth' => false,
			'User' => null,
			'TenantHash' => $sHelpdeskTenantHash,
			'IsMobile' => 0,
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
			'LastErrorCode' => $this->GetLastErrorCode(),
			'Token' => $this->GetCsrfToken(),
			'ZipAttachments' => !!class_exists('ZipArchive'),
			'AllowIdentities' => !!$this->oSettings->GetConf('WebMail/AllowIdentities'),
			'SocialEmail' => '',
			'SocialIsLoggined' => false,
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
			if ($oApiCapability->IsNotLite())
			{
				$aAppData['IsMobile'] = $this->IsMobile();
			}

			$aAppData['IsMailsuite'] = $oApiCapability->IsMailsuite();
		}

		$iIdTenant = 0;

/*		TODO: sash
		if (\CApi::GetConf('labs.allow-social-integration', true))
		{
			\api_Social::Init($aAppData, $sHelpdeskTenantHash);
		}
*/
		if (0 < $aAppData['LastErrorCode'])
		{
			$this->ClearLastErrorCode();
		}

		$oAccount = null;
		if (!empty($sCalendarPubHash))
		{
			$oAccount = $this->GetLogginedDefaultAccount();
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
			$mMin = $oMin->GetMinByHash($sFileStoragePubHash);

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

		$oTenant = $oApiTenant ? $oApiTenant->GetDefaultGlobalTenant() : null;

		$aAppData['LoginStyleImage'] = '';
		$aAppData['AppStyleImage'] = '';
		$aAppData['HelpdeskSiteName'] = '';
		$aAppData['HelpdeskStyleImage'] = '';

		if ($oTenant)
		{
			$aAppData['LoginStyleImage'] = $oTenant->LoginStyleImage;
			$aAppData['AppStyleImage'] = $oTenant->AppStyleImage;
		}

		$aThreadId = $this->GetThreadIdFromRequestAndClear();
		$mThreadId = isset($aThreadId['id']) ? $aThreadId['id'] : null;
		$sThreadAction = isset($aThreadId['action']) ? $aThreadId['action'] : '';
		if ($bHelpdesk)
		{
			$aHelpdeskMainData = null;
			$aAppData['TenantHash'] = $sHelpdeskTenantHash;
			$aAppData['IsMobile'] = 0;

			$iUserId = $this->GetLogginedHelpdeskUserId();
			if (0 < $iUserId && $oApiHelpdeskManager)
			{
				$oHelpdeskUser = $oApiHelpdeskManager->GetUserById($iHelpdeskIdTenant, $iUserId);
				if ($oHelpdeskUser)
				{
					$aHelpdeskMainData = $oApiHelpdeskManager->GetHelpdeskMainSettings($oHelpdeskUser->IdTenant);

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
					$aHelpdeskMainData = $oApiHelpdeskManager->GetHelpdeskMainSettings($iIdTenant);
					$aAppData['HelpdeskSiteName'] = isset($aHelpdeskMainData['SiteName']) ? $aHelpdeskMainData['SiteName'] : '';
					$aAppData['HelpdeskStyleImage'] = isset($aHelpdeskMainData['StyleImage']) &&
						isset($aHelpdeskMainData['StyleAllow']) ? $aHelpdeskMainData['StyleImage'] : '';
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
			$aAppData['HelpdeskThreadAction'] = $sThreadAction ? $sThreadAction : '';
		}

		$oDefaultAccount = null;
		$oDomain = null;

		$iUserId = $this->GetLogginedUserId($sAuthToken);
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
						$aData = $oApiHelpdeskManager->GetHelpdeskMainSettings($oDefaultAccount->IdTenant);
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
				$oAccountTenant = $oApiTenant ? (0 < $oDefaultAccount->IdTenant ? $oApiTenant->GetTenantById($oDefaultAccount->IdTenant) : $oApiTenant->GetDefaultGlobalTenant()) : null;
				if ($oAccountTenant)
				{
					$aAppData['AppStyleImage'] = $oAccountTenant->AppStyleImage;
				}
			}
		}

		$oDomain = $this->getDefaultAccountDomain($oDefaultAccount);
		$aAppData['App'] = $this->appDataDomainSettings($oDomain, $oDefaultAccount);
		if (!isset($aAppData['Plugins']))
		{
			$aAppData['Plugins'] = array();
		}

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
		static $sSiteName = false;

		if (false === $sLanguage && false === $sTheme && false === $sSiteName)
		{
			$oSettings =& CApi::GetSettings();
			
			$sSiteName = $oSettings->GetConf('Common/SiteName');
			$sLanguage = $oSettings->GetConf('Common/DefaultLanguage');
			$sTheme = $oSettings->GetConf('WebMail/DefaultSkin');

			$oAccount = $this->GetLogginedDefaultAccount();
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
				$oDomain = /* @var $oDomain CDomain */ $oApiDomainsManager->GetDomainByUrl($oInput->GetHost());
				
				if ($oDomain)
				{
					$sTheme = $oDomain->DefaultSkin;
					$sLanguage = $this->GetLoginLanguage();

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
            $this->SetLoginLanguage($sLanguage); // todo: sash
			$sTheme = $this->validatedThemeValue($sTheme);
		}
		
		/*** temporary fix to the problems in mobile version in rtl mode ***/
		
		/* @var $oApiIntegrator \CApiIntegratorManager */
		$oApiIntegrator = \CApi::Manager('integrator');

		/* @var $oApiCapability \CApiCapabilityManager */
		$oApiCapability = \CApi::Manager('capability');
		
		if (in_array($sLanguage, array('Arabic', 'Hebrew', 'Persian')) && $oApiIntegrator && $oApiCapability && $oApiCapability->IsNotLite() && 1 === $oApiIntegrator->IsMobile())
		{
			$sLanguage = 'English';
		}
		
		/*** end of temporary fix to the problems in mobile version in rtl mode ***/

		return array($sLanguage, $sTheme, $sSiteName);
	}

	private function getBrowserLanguage()
	{
//		$aLanguages = array('af'=>'Afrikaans', 'sq'=>'Albanian', 'ar-dz'=>'Arabic (Algeria)', 'ar-bh'=>'Arabic (Bahrain)', 'ar-eg'=>'Arabic (Egypt)', 'ar-iq'=>'Arabic (Iraq)', 'ar-jo'=>'Arabic (Jordan)', 'ar-kw'=>'Arabic (Kuwait)', 'ar-lb'=>'Arabic (Lebanon)', 'ar-ly'=>'Arabic (libya)', 'ar-ma'=>'Arabic (Morocco)', 'ar-om'=>'Arabic (Oman)', 'ar-qa'=>'Arabic (Qatar)', 'ar-sa'=>'Arabic (Saudi Arabia)', 'ar-sy'=>'Arabic (Syria)', 'ar-tn'=>'Arabic (Tunisia)', 'ar-ae'=>'Arabic (U.A.E.)', 'ar-ye'=>'Arabic (Yemen)', 'ar'=>'Arabic', 'hy'=>'Armenian', 'as'=>'Assamese', 'az'=>'Azeri', 'eu'=>'Basque', 'be'=>'Belarusian', 'bn'=>'Bengali', 'bg'=>'Bulgarian', 'ca'=>'Catalan', 'zh-cn'=>'Chinese (China)', 'zh-hk'=>'Chinese (Hong Kong SAR)', 'zh-mo'=>'Chinese (Macau SAR)', 'zh-sg'=>'Chinese (Singapore)', 'zh-tw'=>'Chinese (Taiwan)', 'zh'=>'Chinese', 'hr'=>'Croatian', 'cs'=>'Czech', 'da'=>'Danish', 'div'=>'Divehi', 'nl-be'=>'Dutch (Belgium)', 'nl'=>'Dutch (Netherlands)', 'en-au'=>'English (Australia)', 'en-bz'=>'English (Belize)', 'en-ca'=>'English (Canada)', 'en-ie'=>'English (Ireland)', 'en-jm'=>'English (Jamaica)', 'en-nz'=>'English (New Zealand)', 'en-ph'=>'English (Philippines)', 'en-za'=>'English (South Africa)', 'en-tt'=>'English (Trinidad)', 'en-gb'=>'English (United Kingdom)', 'en-us'=>'English (United States)', 'en-zw'=>'English (Zimbabwe)', 'en'=>'English', 'us'=>'English (United States)', 'et'=>'Estonian', 'fo'=>'Faeroese', 'fa'=>'Farsi', 'fi'=>'Finnish', 'fr-be'=>'French (Belgium)', 'fr-ca'=>'French (Canada)', 'fr-lu'=>'French (Luxembourg)', 'fr-mc'=>'French (Monaco)', 'fr-ch'=>'French (Switzerland)', 'fr'=>'French (France)', 'mk'=>'FYRO Macedonian', 'gd'=>'Gaelic', 'ka'=>'Georgian', 'de-at'=>'German (Austria)', 'de-li'=>'German (Liechtenstein)', 'de-lu'=>'German (Luxembourg)', 'de-ch'=>'German (Switzerland)', 'de'=>'German (Germany)', 'el'=>'Greek', 'gu'=>'Gujarati', 'he'=>'Hebrew', 'hi'=>'Hindi', 'hu'=>'Hungarian', 'is'=>'Icelandic', 'id'=>'Indonesian', 'it-ch'=>'Italian (Switzerland)', 'it'=>'Italian (Italy)', 'ja'=>'Japanese', 'kn'=>'Kannada', 'kk'=>'Kazakh', 'kok'=>'Konkani', 'ko'=>'Korean', 'kz'=>'Kyrgyz', 'lv'=>'Latvian', 'lt'=>'Lithuanian', 'ms'=>'Malay', 'ml'=>'Malayalam', 'mt'=>'Maltese', 'mr'=>'Marathi', 'mn'=>'Mongolian (Cyrillic)', 'ne'=>'Nepali (India)', 'nb-no'=>'Norwegian (Bokmal)', 'nn-no'=>'Norwegian (Nynorsk)', 'no'=>'Norwegian (Bokmal)', 'or'=>'Oriya', 'pl'=>'Polish', 'pt-br'=>'Portuguese (Brazil)', 'pt'=>'Portuguese (Portugal)', 'pa'=>'Punjabi', 'rm'=>'Rhaeto-Romanic', 'ro-md'=>'Romanian (Moldova)', 'ro'=>'Romanian', 'ru-md'=>'Russian (Moldova)', 'ru'=>'Russian', 'sa'=>'Sanskrit', 'sr'=>'Serbian', 'sk'=>'Slovak', 'ls'=>'Slovenian', 'sb'=>'Sorbian', 'es-ar'=>'Spanish (Argentina)', 'es-bo'=>'Spanish (Bolivia)', 'es-cl'=>'Spanish (Chile)', 'es-co'=>'Spanish (Colombia)', 'es-cr'=>'Spanish (Costa Rica)', 'es-do'=>'Spanish (Dominican Republic)', 'es-ec'=>'Spanish (Ecuador)', 'es-sv'=>'Spanish (El Salvador)', 'es-gt'=>'Spanish (Guatemala)', 'es-hn'=>'Spanish (Honduras)', 'es-mx'=>'Spanish (Mexico)', 'es-ni'=>'Spanish (Nicaragua)', 'es-pa'=>'Spanish (Panama)', 'es-py'=>'Spanish (Paraguay)', 'es-pe'=>'Spanish (Peru)', 'es-pr'=>'Spanish (Puerto Rico)', 'es-us'=>'Spanish (United States)', 'es-uy'=>'Spanish (Uruguay)', 'es-ve'=>'Spanish (Venezuela)', 'es'=>'Spanish (Traditional Sort)', 'sx'=>'Sutu', 'sw'=>'Swahili', 'sv-fi'=>'Swedish (Finland)', 'sv'=>'Swedish', 'syr'=>'Syriac', 'ta'=>'Tamil', 'tt'=>'Tatar', 'te'=>'Telugu', 'th'=>'Thai', 'ts'=>'Tsonga', 'tn'=>'Tswana', 'tr'=>'Turkish', 'uk'=>'Ukrainian', 'ur'=>'Urdu', 'uz'=>'Uzbek', 'vi'=>'Vietnamese', 'xh'=>'Xhosa', 'yi'=>'Yiddish', 'zu'=>'Zulu');

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
			'sv-fi' => 'Swedish', 'sv' => 'Swedish', 'th' => 'Thai', 'tr' => 'Turkish', 'uk' => 'Ukrainian'
		);
		
		$sLanguage = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']) : 'en';
		$aTempLanguages = preg_split('/[,;]+/', $sLanguage);
		$sLanguage = !empty($aTempLanguages[0]) ? $aTempLanguages[0] : 'en';

		$sLanguageShort = substr($sLanguage, 0, 2);
		
		return \array_key_exists($sLanguage, $aLanguages) ? $aLanguages[$sLanguage] :
			(\array_key_exists($sLanguageShort, $aLanguages) ? $aLanguages[$sLanguageShort] : '');
	}

	/**
	 * @param bool $bHelpdesk = false
	 * @return string
	 */
	public function GetAppDirValue($bHelpdesk = false)
	{
		list($sLanguage, $sTheme, $sSiteName) = $this->getThemeAndLanguage();
		return \in_array($sLanguage, array('Arabic', 'Hebrew', 'Persian')) ? 'rtl' : 'ltr';
	}

	/**
	 * @param string $sWebPath = '.'
	 * @param bool $bHelpdesk = false
	 * @param int $iHelpdeskIdTenant = null
	 * @param string $sHelpdeskHash = ''
	 * @param string $sCalendarPubHash = ''
	 * @param string $sFileStoragePubHash = ''
	 * @param bool $bMobile = false
	 * @return string
	 */
	public function BuildHeadersLink($sWebPath = '.', $bHelpdesk = false, $iHelpdeskIdTenant = null,
		$sHelpdeskHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '', $bMobile = false)
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
				(0 < $iHelpdeskIdTenant ? $oApiTenant->GetTenantById($iHelpdeskIdTenant) : $oApiTenant->GetDefaultGlobalTenant()) : null;

			if ($oTenant && $oTenant->HelpdeskStyleAllow)
			{
				$sS .= '<style>'.strip_tags($oTenant->GetHelpdeskStyleText()).'</style>';
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
	 * @param string $sWebPath = '.'
	 * @param bool $bHelpdesk = false
	 * @param int $iHelpdeskIdTenant = null
	 * @param string $sHelpdeskHash = ''
	 * @param string $sCalendarPubHash = ''
	 * @param string $sFileStoragePubHash = ''
	 * @param bool $bMobile = false
	 *
	 * @return string
	 */
	public function BuildBody($sWebPath = '.', $bHelpdesk = false,
		$iHelpdeskIdTenant = null, $sHelpdeskHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '', $bMobile = false)
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
}
