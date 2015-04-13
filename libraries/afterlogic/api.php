<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
			CApi::Inc('common.functions');
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

			CApi::Inc('common.social');
			CApi::Inc('common.twilio');

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

			$oHtml = \MailSo\Base\Http::SingletonInstance();
			$sHost = $oHtml->GetHost();
			
			if (0 < \strlen($sHost))
			{
				$sDomainSettingsFile = CApi::DataPath().'/settings/'.$sHost.'.config.php';
				if (@file_exists($sDomainSettingsFile))
				{
					$aDomainAppConfig = include $sDomainSettingsFile;
					if (is_array($aDomainAppConfig))
					{
						CApi::$aConfig = array_merge(CApi::$aConfig, $aDomainAppConfig);
					}
				}
			}

			CApi::$oManager = new CApiGlobalManager();
			CApi::$oPlugin = new CApiPluginManager(CApi::$oManager);
			CApi::$bIsValid = CApi::validateApi();

			CApi::$oManager->PrepareStorageMap();

			require_once CApi::RootPath().'DAV/autoload.php';
		}
	}

	/**
	 * @return string
	 */
	static public function EncodeKeyValues(array $aValues, $iSaltLen = 32)
	{
		return api_Utils::UrlSafeBase64Encode(
			api_Crypt::XxteaEncrypt(serialize($aValues), substr(md5(self::$sSalt), 0, $iSaltLen)));
	}

	/**
	 * @return array
	 */
	public static function DecodeKeyValues($sEncodedValues, $iSaltLen = 32)
	{
		$aResult = unserialize(
			api_Crypt::XxteaDecrypt(
				api_Utils::UrlSafeBase64Decode($sEncodedValues), substr(md5(self::$sSalt), 0, $iSaltLen)));

		return is_array($aResult) ? $aResult : array();
	}

	public static function PostRun()
	{
		CApi::Manager('users');
		CApi::Manager('domains');
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
	 * @return \MailSo\Cache\CacheClient
	 */
	public static function Cacher()
	{
		static $oCacher = null;
		if (null === $oCacher)
		{
			$oCacher = \MailSo\Cache\CacheClient::NewInstance();
			$oCacher->SetDriver(\MailSo\Cache\Drivers\File::NewInstance(CApi::DataPath().'/cache'));
			$oCacher->SetCacheIndex(self::Version());
		}

		return $oCacher;
	}

	/**
	 * @return api_Settings
	 */
	public static function &GetSettings()
	{
		return CApi::$oManager->GetSettings();
	}

	/**
	 * @param string $sKey
	 *
	 * @return mixed
	 */
	public static function GetSettingsConf($sKey)
	{
		$oSettings =& CApi::GetSettings();
		return $oSettings->GetConf($sKey);
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

		$sDbPort = '';
		$sUnixSocket = '';

		$iDbType = $oSettings->GetConf('Common/DBType');
		$sDbHost = $oSettings->GetConf('Common/DBHost');
		$sDbName = $oSettings->GetConf('Common/DBName');
		$sDbLogin = $oSettings->GetConf('Common/DBLogin');
		$sDbPassword = $oSettings->GetConf('Common/DBPassword');

		$iPos = strpos($sDbHost, ':');
		if (false !== $iPos && 0 < $iPos)
		{
			$sAfter = substr($sDbHost, $iPos + 1);
			$sDbHost = substr($sDbHost, 0, $iPos);

			if (is_numeric($sAfter))
			{
				$sDbPort = $sAfter;
			}
			else
			{
				$sUnixSocket = $sAfter;
			}
		}

		$oPdo = false;
		if (class_exists('PDO'))
		{
			try
			{
				$oPdo = @new PDO((EDbType::PostgreSQL === $iDbType ? 'pgsql' : 'mysql').':dbname='.$sDbName.
					(empty($sDbHost) ? '' : ';host='.$sDbHost).
					(empty($sDbPort) ? '' : ';port='.$sDbPort).
					(empty($sUnixSocket) ? '' : ';unix_socket='.$sUnixSocket), $sDbLogin, $sDbPassword);

				if ($oPdo)
				{
					$oPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}
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
	public static function ManagerInc($sManagerName, $sFileName, $bDoExitOnError = true)
	{
		$sManagerName = preg_replace('/[^a-z]/', '', strtolower($sManagerName));
		return CApi::Inc('common.managers.'.$sManagerName.'.'.$sFileName, $bDoExitOnError);
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
			exit('FILE NOT EXISTS = '.$sFileFullPath);
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
	 * @param CAccount|string|null $mAccount
	 */
	public static function LogEvent($sDesc, $mAccount = null)
	{
		$oSettings =& CApi::GetSettings();
		if ($oSettings && $oSettings->GetConf('Common/EnableEventLogging'))
		{
			$sDate = gmdate('H:i:s');
			$iIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
			
			$sAccount = $mAccount instanceof \CAccount ? $mAccount->Email :
				(is_string($mAccount) ? $mAccount : 'unknown');

			CApi::Log('Event: '.$sAccount.' > '.$sDesc);
			CApi::LogOnly('['.$sDate.']['.$iIp.']['.$sAccount.'] > '.$sDesc, CApi::GetConf('log.event-file', 'event.txt'));
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
	 * @param Exception $mObject
	 * @param int $iLogLevel = ELogLevel::Error
	 * @param string $sFilePrefix = ''
	 */
	public static function LogException($mObject, $iLogLevel = ELogLevel::Error, $sFilePrefix = '')
	{
		CApi::Log((string) $mObject, $iLogLevel, $sFilePrefix);
	}

	/**
	 * @param string $sFilePrefix = ''
	 *
	 * @return string
	 */
	public static function GetLogFileName($sFilePrefix = '')
	{
		return $sFilePrefix.CApi::GetConf('log.log-file', 'log.txt');
	}

	/**
	 * @param bool $bOn = true
	 */
	public static function SpecifiedUserLogging($bOn = true)
	{
		if ($bOn)
		{
			@setcookie('SpecifiedUserLogging', '1', 0, CApi::GetConf('labs.app-cookie-path', '/'), null, null, true);
		}
		else
		{
			@setcookie('SpecifiedUserLogging', '0', 0, CApi::GetConf('labs.app-cookie-path', '/'), null, null, true);
		}
	}
	
	/**
	 * @return \MailSo\Log\Logger
	 */
	public static function MailSoLogger()
	{
		static $oLogger = null;
		if (null === $oLogger)
		{
			$oLogger = \MailSo\Log\Logger::NewInstance()
				->Add(
					\MailSo\Log\Drivers\Callback::NewInstance(function ($sDesc) {
						CApi::Log($sDesc);
					})->DisableTimePrefix()->DisableGuidPrefix()
				)
				->AddForbiddenType(\MailSo\Log\Enumerations\Type::TIME)
			;
		}

		return $oLogger;
	}

	/**
	 * @param string $sDesc
	 * @param string $sLogFile
	 */
	private static function dbDebugBacktrace($sDesc, $sLogFile)
	{
		static $iDbBacktraceCount = null;
		
		if (null === $iDbBacktraceCount)
		{
			$iDbBacktraceCount = (int) CApi::GetConf('labs.db-debug-backtrace-limit', 0);
			if (!function_exists('debug_backtrace') || version_compare(PHP_VERSION, '5.4.0') < 0)
			{
				$iDbBacktraceCount = 0;
			}
		}

		if (0 < $iDbBacktraceCount && is_string($sDesc) && (false !== strpos($sDesc, 'DB[') || false !== strpos($sDesc, 'DB ERROR')))
		{
			$bSkip = true;
			$sLogData = '';
			$iCount = $iDbBacktraceCount;

			foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20) as $aData)
			{
				if ($aData && isset($aData['function']) && !in_array(strtolower($aData['function']), array(
					'log', 'logonly', 'logend', 'logevent', 'logexception', 'logobject', 'dbdebugbacktrace'
				)))
				{
					$bSkip = false;
				}

				if (!$bSkip)
				{
					$iCount--;
					if (isset($aData['class'], $aData['type'], $aData['function']))
					{
						$sLogData .= $aData['class'].$aData['type'].$aData['function'];
					}
					else if (isset($aData['function']))
					{
						$sLogData .= $aData['function'];
					}

					if (isset($aData['file']))
					{
						$sLogData .= ' ../'.basename($aData['file']);
					}
					if (isset($aData['line']))
					{
						$sLogData .= ' *'.$aData['line'];
					}

					$sLogData .= "\n";
				}

				if (0 === $iCount)
				{
					break;
				}
			}

			if (0 < strlen($sLogData))
			{
				try
				{
					@error_log('['.\MailSo\Log\Logger::Guid().'][DB/backtrace]'.API_CRLF.trim($sLogData).API_CRLF, 3, $sLogFile);
				}
				catch (Exception $oE) {}
			}
		}
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

		if ($oSettings && $oSettings->GetConf('Common/EnableLogging') &&
			($iLogLevel <= $oSettings->GetConf('Common/LoggingLevel') ||
			(ELogLevel::Spec === $oSettings->GetConf('Common/LoggingLevel') &&
				isset($_COOKIE['SpecifiedUserLogging']) && '1' === (string) $_COOKIE['SpecifiedUserLogging'])))
		{
			$sLogFile = self::GetLogFileName($sFilePrefix);

			$sGuid = \MailSo\Log\Logger::Guid();
			$aMicro = explode('.', microtime(true));
			$sDate = gmdate('H:i:s.').str_pad((isset($aMicro[1]) ? substr($aMicro[1], 0, 2) : '0'), 2, '0');
			if ($bIsFirst)
			{
				$sUri = api_Utils::RequestUri();
				$bIsFirst = false;
				$sPost = (isset($_POST) && count($_POST) > 0) ? '[POST('.count($_POST).')]' : '[GET]';

				CApi::LogOnly(API_CRLF.'['.$sDate.']['.$sGuid.'] '.$sPost.'[ip:'.(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown').'] '.$sUri, $sLogFile);
				if (!empty($sPost))
				{
					if (CApi::GetConf('labs.log.post-view', false))
					{
						CApi::LogOnly('['.$sDate.']['.$sGuid.'] POST > '.print_r($_POST, true), $sLogFile);
					}
					else
					{
						CApi::LogOnly('['.$sDate.']['.$sGuid.'] POST > ['.implode(', ', array_keys($_POST)).']', $sLogFile);
					}
				}
				CApi::LogOnly('['.$sDate.']['.$sGuid.']', $sLogFile);

//				@register_shutdown_function('CApi::LogEnd');
			}

			CApi::LogOnly('['.$sDate.']['.$sGuid.'] '.(is_string($sDesc) ? $sDesc : print_r($sDesc, true)), $sLogFile);
		}
	}

	/**
	 * @param string $sDesc
	 * @param string $sLogFile
	 */
	public static function LogOnly($sDesc, $sLogFile)
	{
		static $bDir = null;
		static $sLogDir = null;

		if (null === $sLogDir)
		{
			$sS = CApi::GetConf('log.custom-full-path', '');
			$sLogDir = empty($sS) ? CApi::DataPath().'/logs/' : rtrim(trim($sS), '\\/').'/';
		}
		
		if (null === $bDir)
		{
			$bDir = true;
			if (!@is_dir($sLogDir))
			{
				@mkdir($sLogDir, 0777);
			}
		}

		try
		{
			@error_log($sDesc.API_CRLF, 3, $sLogDir.$sLogFile);
		}
		catch (Exception $oE) {}

		self::dbDebugBacktrace($sDesc, $sLogDir.$sLogFile);
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
		return preg_replace('/[^0-9a-z]/', '', CApi::Version().
			(CApi::GetConf('labs.cache.static', true) ? '' : '-'.md5(time())));
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
	 * @param string $sEmail
	 * @param string $sPassword
	 * @param string $sLogin = ''
	 * @return string
	 */
	public static function GenerateSsoToken($sEmail, $sPassword, $sLogin = '')
	{
		$sSsoHash = \md5($sEmail.$sPassword.$sLogin.\microtime(true).\rand(10000, 99999));
		
		return CApi::Cacher()->Set('SSO:'.$sSsoHash, CApi::EncodeKeyValues(array(
			'Email' => $sEmail,
			'Password' => $sPassword,
			'Login' => $sLogin
		))) ? $sSsoHash : '';
	}

	/**
	 * @param string $sLangFile
	 * @return array
	 */
	public static function convertIniToLang($sLangFile)
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
	private static function processTranslateParams($mLang, $sData, $aParams = null, $iPlural = null)
	{
		$sResult = $sData;
		if ($mLang && isset($mLang[$sData]))
		{
			$sResult = $mLang[$sData];
		}

		if (isset($iPlural))
		{
			$aPluralParts = explode('|', $sResult);

			$sResult = ($aPluralParts && $aPluralParts[$iPlural]) ? $aPluralParts[$iPlural] : (
			$aPluralParts && $aPluralParts[0] ? $aPluralParts[0] : $sResult);
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
	public static function ClientI18N($sData, $oAccount = null, $aParams = null, $iPluralCount = null)
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

//		return self::processTranslateParams($aLang, $sData, $aParams);
		return isset($iPluralCount) ? self::processTranslateParams($aLang, $sData, $aParams, self::getPlural($sLanguage, $iPluralCount)) : self::processTranslateParams($aLang, $sData, $aParams);
	}

	public static function getPlural($sLang = '', $iNumber = 0)
	{
		$iResult = 0;
		$iNumber = (int) $iNumber;

		switch ($sLang)
		{
			case 'Arabic':
				$iResult = ($iNumber === 0 ? 0 : $iNumber === 1 ? 1 : ($iNumber === 2 ? 2 : ($iNumber % 100 >= 3 && $iNumber % 100 <= 10 ? 3 : ($iNumber % 100 >= 11 ? 4 : 5))));
				break;
			case 'Bulgarian':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Chinese-Simplified':
				$iResult = 0;
				break;
			case 'Chinese-Traditional':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Czech':
				$iResult = ($iNumber === 1) ? 0 : (($iNumber >= 2 && $iNumber <= 4) ? 1 : 2);
				break;
			case 'Danish':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Dutch':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'English':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Estonian':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Finish':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'French':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'German':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Greek':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Hebrew':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Hungarian':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Italian':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Japanese':
				$iResult = 0;
				break;
			case 'Korean':
				$iResult = 0;
				break;
			case 'Latvian':
				$iResult = ($iNumber % 10 === 1 && $iNumber % 100 !== 11 ? 0 : ($iNumber !== 0 ? 1 : 2));
				break;
			case 'Lithuanian':
				$iResult = ($iNumber % 10 === 1 && $iNumber % 100 !== 11 ? 0 : ($iNumber % 10 >= 2 && ($iNumber % 100 < 10 || $iNumber % 100 >= 20) ? 1 : 2));
				break;
			case 'Norwegian':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Persian':
				$iResult = 0;
				break;
			case 'Polish':
				$iResult = ($iNumber === 1 ? 0 : ($iNumber % 10 >= 2 && $iNumber % 10 <= 4 && ($iNumber % 100 < 10 || $iNumber % 100 >= 20) ? 1 : 2));
				break;
			case 'Portuguese-Portuguese':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Portuguese-Brazil':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Romanian':
				$iResult = ($iNumber === 1 ? 0 : (($iNumber === 0 || ($iNumber % 100 > 0 && $iNumber % 100 < 20)) ? 1 : 2));
				break;
			case 'Russian':
				$iResult = ($iNumber % 10 === 1 && $iNumber % 100 !== 11 ? 0 : ($iNumber % 10 >= 2 && $iNumber % 10 <= 4 && ($iNumber % 100 < 10 || $iNumber % 100 >= 20) ? 1 : 2));
				break;
			case 'Serbian':
				$iResult = ($iNumber % 10 === 1 && $iNumber % 100 !== 11 ? 0 : ($iNumber % 10 >= 2 && $iNumber % 10 <= 4 && ($iNumber % 100 < 10 || $iNumber % 100 >= 20) ? 1 : 2));
				break;
			case 'Spanish':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Swedish':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Thai':
				$iResult = 0;
				break;
			case 'Turkish':
				$iResult = ($iNumber === 1 ? 0 : 1);
				break;
			case 'Ukrainian':
				$iResult = ($iNumber % 10 === 1 && $iNumber % 100 !== 11 ? 0 : ($iNumber % 10 >= 2 && $iNumber % 10 <= 4 && ($iNumber % 100 < 10 || $iNumber % 100 >= 20) ? 1 : 2));
				break;
			default:
				$iResult = 0;
				break;
		}

		return $iResult;
	}

	/**
	 * @param string $sMimeType
	 * @param string $sFileName = ''
	 * @return bool
	 */
	public static function isExpandMimeTypeSupported($sMimeType, $sFileName = '')
	{
		$bResult = false;
		\CApi::Plugin()->RunHook('webmail.supports-expanding-attachments', array(&$bResult, $sMimeType, $sFileName));
		return $bResult;
	}

	/**
	 * @param string $sMimeType
	 * @param string $sFileName = ''
	 * @return bool
	 */
	public static function isIframedMimeTypeSupported($sMimeType, $sFileName = '')
	{
		$bResult = /*!$this->oHttp->IsLocalhost() &&*/ // TODO
			\CApi::GetConf('labs.allow-officeapps-viewer', true) &&
			!!preg_match('/\.(doc|docx|docm|dotm|dotx|xlsx|xlsb|xls|xlsm|pptx|ppsx|ppt|pps|pptm|potm|ppam|potx|ppsm)$/', strtolower(trim($sFileName)));

		\CApi::Plugin()->RunHook('webmail.supports-iframed-attachments', array(&$bResult, $sMimeType, $sFileName));
		return $bResult;
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

	public static function GetCsrfToken($sTokenName='p7token')
	{
		$sToken = !empty($_COOKIE[$sTokenName]) ? $_COOKIE[$sTokenName] : null;

		if (null === $sToken)
		{
			$sToken = md5(rand(1000, 9999).self::$sSalt.microtime(true));
			@setcookie($sTokenName, $sToken, time() + 60 * 60 * 24 * 30, self::GetConf('labs.app-cookie-path', '/'), null, null, true);
		}

		return $sToken;
	}
}

CApi::Run();
CApi::PostRun();
