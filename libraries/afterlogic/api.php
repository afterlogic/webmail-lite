<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Api
 */
class CApi
{
	/**
	 * @var CApiGlobalManager
	 */
	static $oManager;

	/**
	 * @var CApiPluginManager
	 */
	static $oPlugin;

	/**
	 * @var array
	 */
	static $aConfig;

	/**
	 * @var bool
	 */
	static $bIsValid;

	/**
	 * @var string
	 */
	static $sSalt;

	/**
	 * @var array
	 */
	static $aI18N;

	/**
	 * @var array
	 */
	static $aClientI18N;

	/**
	 * @var bool
	 */
	static $bUseDbLog = true;

	public static function Run()
	{
		include_once self::LibrariesPath().'MailSo/MailSo.php';

		CApi::$aI18N = null;
		CApi::$aClientI18N = array();

		if (!is_object(CApi::$oManager))
		{
			CApi::Inc('common.constants');
			CApi::Inc('common.enum');
			CApi::Inc('common.exception');
			CApi::Inc('common.utils');
			CApi::Inc('common.crypt');
			CApi::Inc('common.container');
			CApi::Inc('common.manager');
			CApi::Inc('common.xml');
			CApi::Inc('common.plugin');

			CApi::Inc('common.utils.get');
			CApi::Inc('common.utils.post');
			CApi::Inc('common.utils.session');

			CApi::Inc('common.http');

			CApi::Inc('common.db.storage');

			$sSalt = '';
			$sSaltFile = CApi::DataPath().'/salt.php';
			if (!@file_exists($sSaltFile))
			{
				$sSaltDesc = '<?php #'.md5(microtime(true).rand(1000, 9999)).md5(microtime(true).rand(1000, 9999));
				@file_put_contents($sSaltFile, $sSaltDesc);
			}
			else
			{
				$sSalt = md5(file_get_contents($sSaltFile));
			}

			CApi::$sSalt = $sSalt;
			CApi::$oManager = new CApiGlobalManager();
			CApi::$aConfig = include CApi::RootPath().'common/config.php';

			$sSettingsFile = CApi::DataPath().'/settings/config.php';
			if (@file_exists($sSettingsFile))
			{
				$aAppConfig = include $sSettingsFile;
				if (is_array($aAppConfig))
				{
					CApi::$aConfig = array_merge(CApi::$aConfig, $aAppConfig);
				}
			}

			CApi::$oPlugin = new CApiPluginManager(CApi::$oManager);
			CApi::$bIsValid = CApi::validateApi();

			CApi::$oManager->PrepareStorageMap();

			require_once CApi::RootPath().'DAV/autoload.php';
		}
	}

	/**
	 * @return string
	 */
	static public function EncodeKeyValues(array $aValues)
	{
		return api_Utils::UrlSafeBase64Encode(
			api_Crypt::XxteaEncrypt(serialize($aValues), md5(self::$sSalt)));
	}

	/**
	 * @return array
	 */
	public static function DecodeKeyValues($sEncodedValues)
	{
		$aResult = unserialize(
			api_Crypt::XxteaDecrypt(
				api_Utils::UrlSafeBase64Decode($sEncodedValues), md5(self::$sSalt)));

		return is_array($aResult) ? $aResult : array();
	}

	public static function PostRun()
	{
		CApi::Manager('users');
		CApi::Manager('domains');
	}

	/**
	 * @return bool
	 */
	public static function IsValidPhpVersion()
	{
		if (!defined('APP_VALID_VERSION'))
		{
			define('APP_VALID_VERSION', version_compare(phpversion(), '5.2.3') > -1);
		}

		return APP_VALID_VERSION;
	}

	/**
	 * @return bool
	 */
	public static function IsValidFullSupportPhpVersion()
	{
		if (!defined('APP_VALID_FULL_VERSION'))
		{
			define('APP_VALID_FULL_VERSION', version_compare(phpversion(), '5.3.0') > -1);
		}

		return APP_VALID_FULL_VERSION;
	}

	/**
	 * @return bool
	 */
	public static function IsValidOutdatedPhpVersion()
	{
		if (!defined('APP_OUTDATED_VERSION'))
		{
			define('APP_OUTDATED_VERSION',
				self::IsValidPhpVersion() &&
				!self::IsValidFullSupportPhpVersion()
			);
		}

		return APP_OUTDATED_VERSION;
	}

	/**
	 * @return CApiPluginManager
	 */
	public static function Plugin()
	{
		return CApi::$oPlugin;
	}

	/**
	 * @param string $sManagerType
	 * @param string $sForcedStorage = ''
	 */
	public static function Manager($sManagerType, $sForcedStorage = '')
	{
		return CApi::$oManager->GetByType($sManagerType, $sForcedStorage);
	}

	/**
	 * @return CApiGlobalManager
	 */
	public static function GetManager()
	{
		return CApi::$oManager;
	}

	/**
	 * @return api_Settings
	 */
	public static function &GetSettings()
	{
		return CApi::$oManager->GetSettings();
	}

	/**
	 * @param api_Http $oInput
	 * @return string
	 */
	public static function CsrfBrowserToken(api_Http $oInput)
	{
		$sUserAgent = $oInput->GetServer('HTTP_USER_AGENT', '');
		return md5('awm'.__FILE__.md5($sUserAgent).'awm');
	}

	/**
	 * @return PDO|false
	 */
	public static function GetPDO()
	{
		static $oPdoCache = null;
		if (null !== $oPdoCache)
		{
			return $oPdoCache;
		}

		$oSettings =& CApi::GetSettings();

		$sDbHost = $oSettings->GetConf('Common/DBHost');
		$sDbName = $oSettings->GetConf('Common/DBName');
		$sDbLogin = $oSettings->GetConf('Common/DBLogin');
		$sDbPassword = $oSettings->GetConf('Common/DBPassword');

		$sUnixSocket = '';
		$iPos = strpos($sDbHost, '/');
		if (false !== $iPos)
		{
			$sUnixSocket = substr($sDbHost, $iPos);
			$sDbHost = rtrim(substr($sDbHost, 0, $iPos), ':');
		}

		$oPdo = false;
		if (class_exists('PDO'))
		{
			try
			{
				$oPdo = new PDO('mysql:dbname='.$sDbName.';host='.$sDbHost.
					(empty($sUnixSocket) ? '' : ';unix_socket='.$sUnixSocket), $sDbLogin, $sDbPassword);

				$oPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch (Exception $oException)
			{
				self::Log($oException->getMessage(), ELogLevel::Error);
				self::Log($oException->getTraceAsString(), ELogLevel::Error);
				$oPdo = false;
			}
		}
		else
		{
			self::Log('Class PDO dosn\'t exist', ELogLevel::Error);
		}

		if (false !== $oPdo)
		{
			$oPdoCache = $oPdo;
		}

		return $oPdo;
	}

	/**
	 * @param string $sKey
	 * @param mixed $mDefault = null
	 * @return mixed
	 */
	public static function GetConf($sKey, $mDefault = null)
	{
		return (isset(CApi::$aConfig[$sKey])) ? CApi::$aConfig[$sKey] : $mDefault;
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 * @return void
	 */
	public static function SetConf($sKey, $mValue)
	{
		CApi::$aConfig[$sKey] = $mValue;
	}

	/**
	 * @return bool
	 */
	public static function ManagerInc($sManagerName, $sFileName)
	{
		$sManagerName = preg_replace('/[^a-z]/', '', strtolower($sManagerName));
		return CApi::Inc('common.managers.'.$sManagerName.'.'.$sFileName);
	}

	/**
	 * @return bool
	 */
	public static function ManagerPath($sManagerName, $sFileName)
	{
		$sManagerName = preg_replace('/[^a-z]/', '', strtolower($sManagerName));
		return CApi::IncPath('common.managers.'.$sManagerName.'.'.$sFileName);
	}

	/**
	 * @return bool
	 */
	public static function StorageInc($sManagerName, $sStorageName, $sFileName)
	{
		$sManagerName = preg_replace('/[^a-z]/', '', strtolower($sManagerName));
		$sStorageName = preg_replace('/[^a-z]/', '', strtolower($sStorageName));
		return CApi::Inc('common.managers.'.$sManagerName.'.storages.'.$sStorageName.'.'.$sFileName);
	}

	/**
	 * @return bool
	 */
	public static function IncPath($sFileName)
	{
		$sFileName = preg_replace('/[^a-z0-9\._\-]/', '', strtolower($sFileName));
		$sFileName = preg_replace('/[\.]+/', '.', $sFileName);
		$sFileName = str_replace('.', '/', $sFileName);

		return CApi::RootPath().$sFileName.'.php';
	}
	/**
	 * @param string $sFileName
	 * @param bool $bDoExitOnError = true
	 * @return bool
	 */
	public static function Inc($sFileName, $bDoExitOnError = true)
	{
		static $aCache = array();

		$sFileFullPath = '';
		$sFileName = preg_replace('/[^a-z0-9\._\-]/', '', strtolower($sFileName));
		$sFileName = preg_replace('/[\.]+/', '.', $sFileName);
		$sFileName = str_replace('.', '/', $sFileName);
		if (isset($aCache[$sFileName]))
		{
			return true;
		}
		else
		{
			$sFileFullPath = CApi::RootPath().$sFileName.'.php';
			if (@file_exists($sFileFullPath))
			{
				$aCache[$sFileName] = true;
				include_once $sFileFullPath;
				return true;
			}
		}

		if ($bDoExitOnError)
		{
			exit('FILE NOT EXITS = '.$sFileFullPath);
		}
		return false;
	}

	/**
	 * @param string $sNewLocation
	 */
	public static function Location($sNewLocation)
	{
		CApi::Log('Location: '.$sNewLocation);
		@header('Location: '.$sNewLocation);
	}

	/**
	 * @param string $sDesc
	 * @param CAccount $oAccount
	 */
	public static function LogEvent($sDesc, CAccount $oAccount)
	{
		$oSettings =& CApi::GetSettings();

		if ($oSettings && $oSettings->GetConf('Common/EnableEventLogging'))
		{
			$sDate = gmdate('H:i:s');
			CApi::Log('Event: '.$oAccount->Email.' > '.$sDesc);
			CApi::LogOnly('['.$sDate.'] '.$oAccount->Email.' > '.$sDesc, CApi::GetConf('log.event-file', 'event.txt'));
		}
	}

	/**
	 * @param mixed $mObject
	 * @param int $iLogLevel = ELogLevel::Full
	 * @param string $sFilePrefix = ''
	 */
	public static function LogObject($mObject, $iLogLevel = ELogLevel::Full, $sFilePrefix = '')
	{
		CApi::Log(print_r($mObject, true), $iLogLevel, $sFilePrefix);
	}

	/**
	 * @param string $sFilePrefix = ''
	 *
	 * @return string
	 */
	public static function GetLogFileName($sFilePrefix = '')
	{
		$sLogFile = $sFilePrefix.CApi::GetConf('log.log-file', 'log.txt');
		if (CApi::GetConf('labs.log.specified-by-user', false) && !empty($_COOKIE['user-log']))
		{
			$sLogFile = substr(preg_replace('/[^a-z0-9]/', '', $_COOKIE['user-log']), 0, 20).'-'.$sLogFile;
		}

		return $sLogFile;
	}

	/**
	 * @param string $sDesc
	 * @param int $iLogLevel = ELogLevel::Full
	 * @param string $sFilePrefix = ''
	 * @param bool $bIdDb = false
	 */
	public static function Log($sDesc, $iLogLevel = ELogLevel::Full, $sFilePrefix = '')
	{
		static $bIsFirst = true;

		$oSettings =& CApi::GetSettings();
		$sLogFile = self::GetLogFileName($sFilePrefix);
		$bSpecifidedByUser = CApi::GetConf('labs.log.specified-by-user', false) && !empty($_COOKIE['user-log']);

		if ($oSettings && $oSettings->GetConf('Common/EnableLogging')
			&& ($iLogLevel <= $oSettings->GetConf('Common/LoggingLevel') ||
				$bSpecifidedByUser ||
				(ELogLevel::Spec === $oSettings->GetConf('Common/LoggingLevel') &&
					isset($_COOKIE['spec-log']) && '1' === (string) $_COOKIE['spec-log'])))
		{
			$aMicro = explode('.', microtime(true));
			$sDate = gmdate('H:i:s.').str_pad((isset($aMicro[1]) ? substr($aMicro[1], 0, 2) : '0'), 2, '0');
			if ($bIsFirst)
			{
				$sUri = api_Utils::RequestUri();
				$bIsFirst = false;
				$sPost = (isset($_POST) && count($_POST) > 0) ? ' [POST('.count($_POST).')]' : '';

				CApi::LogOnly(API_CRLF.'['.$sDate.']'.$sPost.' '.$sUri, $sLogFile);
				if (!empty($sPost))
				{
					if (CApi::GetConf('labs.log.post-view', false))
					{
						CApi::LogOnly('['.$sDate.'] POST > '.print_r($_POST, true), $sLogFile);
					}
					else
					{
						CApi::LogOnly('['.$sDate.'] POST > ['.implode(', ', array_keys($_POST)).']', $sLogFile);
					}
				}
				CApi::LogOnly('['.$sDate.']', $sLogFile);

//				@register_shutdown_function('CApi::LogEnd');
			}

			CApi::LogOnly('['.$sDate.'] '.$sDesc, $sLogFile);
		}
	}

	/**
	 * @param string $sDesc
	 * @param string $sLogFile
	 */
	public static function LogOnly($sDesc, $sLogFile)
	{
		static $bDir = null;
		if (null === $bDir)
		{
			$bDir = true;
			if (!@is_dir(CApi::DataPath().'/logs/'))
			{
				@mkdir(CApi::DataPath().'/logs/', 0777);
			}
		}

		try
		{
			@error_log($sDesc.API_CRLF, 3, CApi::DataPath().'/logs/'.$sLogFile);
		}
		catch (Exception $oE) {}
	}

	public static function LogEnd()
	{
		CApi::Log('# script shutdown');
	}

	/**
	 * @return string
	 */
	public static function RootPath()
	{
		defined('API_ROOTPATH') || define('API_ROOTPATH', rtrim(dirname(__FILE__), '/\\').'/');
		return API_ROOTPATH;
	}

	/**
	 * @return string
	 */
	public static function WebMailPath()
	{
		return CApi::RootPath().ltrim(API_PATH_TO_WEBMAIL, '/');
	}

	/**
	 * @return string
	 */
	public static function LibrariesPath()
	{
		return CApi::RootPath().'../';
	}

	/**
	 * @return string
	 */
	public static function Version()
	{
		static $sVersion = null;
		if (null === $sVersion)
		{
			$sAppVersion = @file_get_contents(CApi::WebMailPath().'VERSION');
			$sVersion = (false === $sAppVersion) ? '0.0.0' : $sAppVersion;
		}
		return $sVersion;
	}

	/**
	 * @return string
	 */
	public static function VersionJs()
	{
		return preg_replace('/[^0-9a-z]/', '', CApi::Version());
	}

	/**
	 * @return string
	 */
	public static function DataPath()
	{
		$dataPath = 'data';
		if (!defined('API_DATA_FOLDER') && @file_exists(CApi::WebMailPath().'inc_settings_path.php'))
		{
			include CApi::WebMailPath().'inc_settings_path.php';
		}

		if (!defined('API_DATA_FOLDER') && isset($dataPath) && null !== $dataPath)
		{
			define('API_DATA_FOLDER', api_Utils::GetFullPath($dataPath, CApi::WebMailPath()));
		}

		return defined('API_DATA_FOLDER') ? API_DATA_FOLDER : '';
	}

	/**
	 * @return bool
	 */
	protected static function validateApi()
	{
		$iResult = 1;

		$oSettings =& CApi::GetSettings();
		$iResult &= $oSettings && ($oSettings instanceof api_Settings);

		return (bool) $iResult;
	}

	/**
	 * @return bool
	 */
	public static function IsValid()
	{
		return (bool) CApi::$bIsValid;
	}

	/**
	 * @param string $sLangFile
	 * @return array
	 */
	private static function convertIniToLang($sLangFile)
	{
		$aResultLang = false;
		
		$aLang = @parse_ini_string(file_get_contents($sLangFile), true);
		if (is_array($aLang))
		{
			$aResultLang = array();
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

		return $aResultLang;
	}

	/**
	 * @param mixed $mLang
	 * @param string $sData
	 * @param array|null $aParams = null
	 * @return array
	 */
	private static function processTranslateParams($mLang, $sData, $aParams = null)
	{
		$sResult = $sData;
		if ($mLang && isset($mLang[$sData]))
		{
			$sResult = $mLang[$sData];
		}

		if (null !== $aParams && is_array($aParams))
		{
			foreach ($aParams as $sKey => $sValue)
			{
				$sResult = str_replace('%'.$sKey.'%', $sValue, $sResult);
			}
		}

		return $sResult;
	}

	/**
	 * @param string $sData
	 * @param CAccount $oAccount
	 * @param array $aParams = null
	 *
	 * @return string
	 */
	public static function ClientI18N($sData, $oAccount = null, $aParams = null)
	{
		$sLanguage = $oAccount ? $oAccount->User->DefaultLanguage : '';
		if (empty($sLanguage))
		{
			$oSettings =& \CApi::GetSettings();
			$sLanguage = $oSettings->GetConf('Common/DefaultLanguage');
		}

		$aLang = null;
		if (isset(CApi::$aClientI18N[$sLanguage]))
		{
			$aLang = CApi::$aClientI18N[$sLanguage];
		}
		else
		{
			CApi::$aClientI18N[$sLanguage] = false;
				
			$sLangFile = CApi::WebMailPath().'i18n/'.$sLanguage.'.ini';
			if (!@file_exists($sLangFile))
			{
				$sLangFile = CApi::WebMailPath().'i18n/English.ini';
				$sLangFile = @file_exists($sLangFile) ? $sLangFile : '';
			}

			if (0 < strlen($sLangFile))
			{
				$aLang = self::convertIniToLang($sLangFile);
				if (is_array($aLang))
				{
					CApi::$aClientI18N[$sLanguage] = $aLang;
				}
			}
		}

		return self::processTranslateParams($aLang, $sData, $aParams);
	}

	/**
	 * @param string $sData
	 * @param array $aParams = null
	 *
	 * @return string
	 */
	public static function I18N($sData, $aParams = null, $sForceCustomInitialisationLang = '')
	{
		if (null === CApi::$aI18N)
		{
			CApi::$aI18N = false;

			if ('' !== $sForceCustomInitialisationLang)
			{
				$sLang = $sForceCustomInitialisationLang;
			}
			else
			{
				$sLang = CApi::GetConf('labs.i18n', '');
			}
			
			$sLangFile = '';
			if (0 < strlen($sLang))
			{
				$sLangFile = CApi::RootPath().'common/i18n/'.$sLang.'.ini';
			}

			if (0 === strlen($sLangFile) || !@file_exists($sLangFile))
			{
				$sLangFile = CApi::RootPath().'common/i18n/English.ini';
			}

			if (0 < strlen($sLangFile) && @file_exists($sLangFile))
			{
				$aResultLang = self::convertIniToLang($sLangFile);
				if (is_array($aResultLang))
				{
					CApi::$aI18N = $aResultLang;
				}
			}
		}

		return self::processTranslateParams(CApi::$aI18N, $sData, $aParams);
	}
}

CApi::Run();
CApi::PostRun();
