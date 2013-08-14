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
		$sCacheFileName = '';
		if (CApi::GetConf('labs.cache.templates', $this->bCache))
		{
			$sCacheFileName = 'templates-'.md5(CApi::Version()).'.cache';
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
// TODO
//			if (0 < strlen($sThemeFileName))
//			{
//				$sThemeFileName = CApi::WebMailPath().'skins/'.$sTheme.'/templates/'.$sThemeFileName;
//				if (file_exists($sThemeFileName))
//				{
//					$sFileName = $sThemeFileName;
//				}
//			}

			$sResult .= '<script id="'.preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(array('/', '\\'), '_', substr($sName, 0, -5))).'" type="text/html">'.
				preg_replace('/[\r\n\t]+/', ' ', file_get_contents($sFileName)).'</script>';
		}

		$sResult = trim($sResult);
		if (CApi::GetConf('labs.cache.templates', $this->bCache))
		{
			if (!is_dir(dirname($sCacheFullFileName)))
			{
				mkdir(dirname($sCacheFullFileName), 0777);
			}
			
			$sResult = '<!-- '.$sCacheFileName.' -->'.$sResult;
			file_put_contents($sCacheFullFileName, $sResult);
		}

		return $sResult;
	}

	/**
	 * @param string $sLanguage
	 * @return string
	 */
	private function convertLanguageNameToMomentName($sLanguage)
	{
		$aList = array(
			'english' => 'en',
			'polish' => 'pl',
			'estonian' => 'et',
			'bulgarian' => 'bg',
			'ukrainian' => 'uk',
			'thai' => 'th',
			'swedish' => 'sv',
			'spanish' => 'es',
			'russian' => 'ru',
			'romanian' => 'ro',
			'portuguese-brazil' => 'pt-br',
//			'persian' => '', // TODO name? Farsi
			'latvian' => 'lv',
			'korean' => 'ko',
			'japanese' => 'ja',
			'italian' => 'it',
			'hungarian' => 'hu',
			'hebrew' => 'he',
			'german' => 'de',
			'french' => 'fr',
			'finnish' => 'fi',
			'dutch' => 'nl',
			'danish' => 'da',
			'chinese-Traditional' => 'zh-tw',
			'chinese-Simplified' => 'zh-cn',
			'arabic' => 'ar',
			'turkish' => 'tr',
			'norwegian' => 'nb',
//			'lithuanian' => 'lt',  // TODO
			'greek' => 'el',
			'czech' => 'cs'
		);

		return isset($aList[strtolower($sLanguage)]) ? $aList[strtolower($sLanguage)] : $sLanguage;
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
		$sMomentLanguage = $this->convertLanguageNameToMomentName($sLanguage);
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
			@setcookie(self::TOKEN_KEY, $sToken, 0, $this->getCookiePath(), null, null, true);
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
		@setcookie(self::AUTH_KEY, CApi::EncodeKeyValues($aAccountHashTable), $iTime, $this->getCookiePath(), null, null, true);
	}

	public function ResetCookies()
	{
		$sAccountHash = !empty($_COOKIE[self::AUTH_KEY]) ? $_COOKIE[self::AUTH_KEY] : '';
		if (0 < strlen($sAccountHash))
		{
			$aAccountHashTable = CApi::DecodeKeyValues($sAccountHash);
			$bSignMe = isset($aAccountHashTable['sign-me']) ? !!$aAccountHashTable['sign-me'] : false;

			$iTime = $bSignMe ? time() + 60 * 60 * 24 * 30 : 0;
			@setcookie(self::AUTH_KEY, CApi::EncodeKeyValues($aAccountHashTable), $iTime, $this->getCookiePath(), null, null, true);
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
			else if (!$oAccount->User->AllowWebmail || !$oAccount->User->GetCapa('WEBMAIL'))
			{
				throw new CApiManagerException(Errs::WebMailManager_AccountWebmailDisabled);
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
				null !== $sObsoleteLanguage && $sObsoleteLanguage !== $oAccount->User->DefaultLanguage)
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

	private function appDataDomainSettings($oDomain)
	{
		$aResult = array();
		if ($oDomain)
		{
			$oSettings =& CApi::GetSettings();

			/* @var $oApiDavManager CApiDavManager */
			$oApiDavManager = CApi::Manager('dav');

			$aResult['AllowUsersChangeInterfaceSettings'] = (bool) $oDomain->AllowUsersChangeInterfaceSettings;
			$aResult['AllowUsersChangeEmailSettings'] = (bool) $oDomain->AllowUsersChangeEmailSettings;
			$aResult['AllowUsersAddNewAccounts'] = (bool) $oDomain->AllowUsersAddNewAccounts;

			$aResult['DefaultLanguage'] = $oDomain->DefaultLanguage;
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
			foreach (array(EDateFormat::MMDDYYYY, EDateFormat::DDMMYYYY) as $sDateFmtName)
			{
				$aResult['DateFormats'][] = $sDateFmtName;
			}

			$iAttachmentSizeLimit = ((bool) $oSettings->GetConf('WebMail/EnableAttachmentSizeLimit'))
				? (int) $oSettings->GetConf('WebMail/AttachmentSizeLimit') : 0;

			$aResult['AttachmentSizeLimit'] = $iAttachmentSizeLimit;
			$aResult['AutoSave'] = (bool) CApi::GetConf('webmail.autosave', true);

			$sUrl = $oApiDavManager ? $oApiDavManager->GetServerUrl() : '';

			$aResult['IdleSessionTimeout'] = (int) $oSettings->GetConf('WebMail/IdleSessionTimeout');
			$aResult['AllowInsertImage'] = (bool) $oSettings->GetConf('WebMail/AllowInsertImage');
			$aResult['AllowBodySize'] = (bool) $oSettings->GetConf('WebMail/AllowBodySize');
			$aResult['MaxBodySize'] = (int) $oSettings->GetConf('WebMail/MaxBodySize');
			$aResult['MaxSubjectSize'] = (int) $oSettings->GetConf('WebMail/MaxSubjectSize');
			$aResult['EnableMobileSync'] = (!empty($sUrl) && (bool) $oSettings->GetConf('Common/EnableMobileSync'));
			
			$aResult['AllowPrefetch'] = (bool) CApi::GetConf('webmail.use-prefetch', true);
			$aResult['AllowLanguageOnLogin'] = (bool) $oSettings->GetConf('WebMail/AllowLanguageOnLogin');
			$aResult['FlagsLangSelect'] = (bool) $oSettings->GetConf('WebMail/FlagsLangSelect');
			
			$aResult['DemoWebMail'] = (bool) CApi::GetConf('demo.webmail.enable', false);
			$aResult['DemoWebMailLogin'] = CApi::GetConf('demo.webmail.login', '');
			$aResult['DemoWebMailPassword'] = CApi::GetConf('demo.webmail.password', '');
			$aResult['GoogleAnalyticsAccount'] = CApi::GetConf('labs.google-analytic.account', '');
			$aResult['CustomLogoutUrl'] = CApi::GetConf('labs.webmail.custom-logout-url', '');
			$aResult['ShowQuotaBar'] = (bool) $oSettings->GetConf('WebMail/ShowQuotaBar');

			$sCustomLanguage = $this->GetLoginLanguage();
			if (!empty($sCustomLanguage))
			{
				$aResult['DefaultLanguage'] = $sCustomLanguage;
			}

			$aResult['LoginDescription'] = '';
			
			CApi::Plugin()->RunHook('api-app-domain-data', array($oDomain, &$aResult));
		}

		return $aResult;
	}

	private function appDataUserSettings($oAccount)
	{
		$aResult = array();
		if ($oAccount)
		{
			$oSettings =& CApi::GetSettings();

			/* @var $oApiCapabilityManager CApiCapabilityManager */
			$oApiCapabilityManager = CApi::Manager('capability');

			/* @var $oApiCollaborationManager CApiCollaborationManager */
			$oApiCollaborationManager = CApi::Manager('collaboration');

			$aResult['IdUser'] = $oAccount->User->IdUser;
			$aResult['MailsPerPage'] = (int) $oAccount->User->MailsPerPage;
			$aResult['ContactsPerPage'] = (int) $oAccount->User->ContactsPerPage;
			$aResult['AutoCheckMailInterval'] = (int) $oAccount->User->AutoCheckMailInterval;
			$aResult['DefaultEditor'] = (int) $oAccount->User->DefaultEditor;
			$aResult['Layout'] = (int) $oAccount->User->Layout;
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

			$aResult['OutlookSyncEnable'] = (bool) ($oAccount->User->GetCapa('OUTLOOK_SYNC') && $oApiCollaborationManager);

			$iPab = (int) ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'));
			$iGab = (int) ($oAccount->User->AllowContacts &&
				$oSettings->GetConf('Contacts/ShowGlobalContactsInAddressBook') &&
				$oAccount->User->GetCapa('GAB') &&
				$oApiCollaborationManager &&
				$oApiCollaborationManager->IsContactsGlobalSupported());

			$aResult['ShowPersonalContacts'] = (bool) $iPab;
			$aResult['ShowGlobalContacts'] = (bool) $iGab;

			$aResult['LastLogin'] = 0;
			if ($oSettings->GetConf('WebMail/EnableLastLoginNotification'))
			{
				$sLastLogin = (string) CSession::Get(EAccountSessKey::LastLogin, '');
				if (!empty($sLastLogin))
				{
					$aResult['LastLogin'] = (int) $sLastLogin;
				}
			}

			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');

			/* @var $oApiDavManager CApiDavManager */
			$oApiDavManager = CApi::Manager('dav');

			$aResult['AllowCalendar'] = (bool)
				($oApiCapabilityManager->IsCalendarSupported() && $oAccount->User->AllowCalendar && $oAccount->User->GetCapa('CALENDAR'));

			$aResult['Calendar'] = null;
			if ($oApiDavManager && $oAccount->IsDefaultAccount)
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

			$aResult['AllowCalendar'] = null === $aResult['Calendar'] ? false : $aResult['AllowCalendar'];

			$bIsDemo = false;
			CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
			$aResult['IsDemo'] = $bIsDemo;
			
			CApi::Plugin()->RunHook('api-app-user-data', $oAccount, $aResult);

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
		$sList = array();
		$sDir = CApi::WebMailPath().'skins';
		if (@is_dir($sDir))
		{
			$rDirH = @opendir($sDir);
			if ($rDirH)
			{
				while (($sFile = @readdir($rDirH)) !== false)
				{
					if ('.' !== $sFile{0} && is_dir($sDir.'/'.$sFile) && file_exists($sDir.'/'.$sFile.'/styles.css'))
					{
						$sList[] = $sFile;
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
	public function AppData()
	{
		$aAppData = array(
			'Auth' => false,
			'User' => null,
			'LastErrorCode' => $this->GetLastErrorCode(),
			'Token' => $this->GetCsrfToken()
		);

		if (0 < $aAppData['LastErrorCode'])
		{
			$this->ClearLastErrorCode();
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
				}
			}
		}

		$oDomain = $this->getDefaultAccountDomain($oDefaultAccount);
		$aAppData['App'] = $this->appDataDomainSettings($oDomain);

		CApi::Plugin()->RunHook('api-app-data', $oDefaultAccount, $aAppData);

		return $aAppData;
	}

	/**
	 * @return string
	 */
	private function compileAppData()
	{
		return '<script>window.pSevenAppData='.json_encode($this->AppData()).';</script>';
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
	 * @return string
	 */
	public function GetAppDirValue()
	{
		list($sLanguage, $sTheme) = $this->getThemeAndLanguage();
		return \in_array($sLanguage, array('Arabic', 'Hebrew')) ? 'rtl' : 'ltr';
	}

	/**
	 * @param string $sWebPath = '.'
	 * @return string
	 */
	public function BuildHeadersLink($sWebPath = '.')
	{
		list($sLanguage, $sTheme) = $this->getThemeAndLanguage();
		
		$sVersionJs = '?'.CApi::VersionJs();
		$sWebPath = empty($sWebPath) ? '.' : $sWebPath;
		return
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/libs.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/static/css/app.css'.$sVersionJs.'" />'.
'<link type="text/css" rel="stylesheet" href="'.$sWebPath.'/skins/'.$sTheme.'/styles.css'.$sVersionJs.'" />';
	}

	/**
	 * @param string $sWebPath = '.'
	 * @return string
	 */
	public function BuildBody($sWebPath = '.')
	{
		list($sLanguage, $sTheme) = $this->getThemeAndLanguage();

		$sWebPath = empty($sWebPath) ? '.' : $sWebPath;
		return
'<div class="pSevenMain"><div id="pSevenLoading"></div><div id="pSevenContent"></div><div id="pSevenHidden"></div>'.
'<div>'.
$this->compileTemplates($sTheme).
$this->compileLanguage($sLanguage).
$this->compileAppData().
'<script src="'.$sWebPath.'/static/js/libs.js?'.CApi::VersionJs().'"></script>'.
'<script src="'.$sWebPath.'/static/js/app'.(CApi::GetConf('labs.use-app-min-js', false) ? '.min' : '').'.js?'.CApi::VersionJs().'"></script>'.
'</div></div>'."\r\n".'<!-- '.CApi::Version().' -->'
		;
	}
}
