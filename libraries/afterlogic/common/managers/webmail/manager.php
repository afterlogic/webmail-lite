<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package WebMail
 */
class CApiWebmailManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('webmail', $oManager, $sForcedStorage);
	}

	/**
	 * @param array $aFiles
	 * @return string
	 */
	public function JsPacker($aFiles)
	{
		$this->inc('classes.jspacker');

		$oJsPacker = new CJsPacker();
		return $oJsPacker->JsFilesCompress($aFiles);
	}

	/**
	 * @param string $sEmail
	 * @param string $sPassword
	 * @param string $sChangeLang = ''
	 * @param array $aExtValues = null
	 * @return CAccount | false
	 */
	public function CreateAccountProcess($sEmail, $sPassword, $sChangeLang = '', $aExtValues = null)
	{
		$mResult = false;
		try
		{
			/* @var $oApiDomainsManager CApiDomainsManager */
			$oApiDomainsManager = CApi::Manager('domains');

			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');

			$sDomainName = api_Utils::GetDomainFromEmail($sEmail);

			$oDomain = /* @var $oDomain CDomain */ $oApiDomainsManager->GetDomainByName($sDomainName);
			if (!$oDomain)
			{
				$oDomain = $oApiDomainsManager->GetDefaultDomain();
			}

			if ($oDomain && $oDomain->AllowNewUsersRegister || 'nodb' === CApi::GetManager()->GetStorageByType('webmail'))
			{
				if ($oDomain && !$oDomain->AllowWebMail)
				{
					throw new CApiManagerException(Errs::WebMailManager_AccountWebmailDisabled);
				}
				else if ($oDomain && $oDomain->IsInternal)
				{
					throw new CApiManagerException(Errs::WebMailManager_NewUserRegistrationDisabled);
				}
				else if ($oDomain)
				{
					$oAccountToCreate = new CAccount($oDomain);
					$oAccountToCreate->Email = $sEmail;
					
					$oAccountToCreate->IncomingMailLogin = (isset($aExtValues['Login'])) ? $aExtValues['Login'] :
						(($this->oSettings->GetConf('WebMail/UseLoginAsEmailAddress'))
							? $sEmail : api_Utils::GetAccountNameFromEmail($sEmail));
					
					$oAccountToCreate->IncomingMailPassword = $sPassword;

					if (0 < strlen($sChangeLang) && $sChangeLang !== $oAccountToCreate->User->DefaultLanguage)
					{
						$oAccountToCreate->User->DefaultLanguage = $sChangeLang;
					}

					if ($oDomain->IsDefaultDomain && isset(
						$aExtValues['IncProtocol'], $aExtValues['IncHost'], $aExtValues['IncPort'],
						$aExtValues['OutHost'], $aExtValues['OutPort'], $aExtValues['OutAuth']))
					{
						$oAccountToCreate->IncomingMailProtocol = (int) $aExtValues['IncProtocol'];
						$oAccountToCreate->IncomingMailServer = trim($aExtValues['IncHost']);
						$oAccountToCreate->IncomingMailPort = (int) trim($aExtValues['IncPort']);

						$oAccountToCreate->OutgoingMailServer = trim($aExtValues['OutHost']);
						$oAccountToCreate->OutgoingMailPort = (int) trim($aExtValues['OutPort']);
						$oAccountToCreate->OutgoingMailAuth = ((bool) $aExtValues['OutAuth'])
							? ESMTPAuthType::AuthCurrentUser : ESMTPAuthType::NoAuth;

						// TODO
						$oAccountToCreate->IncomingMailUseSSL = in_array($oAccountToCreate->IncomingMailPort, array(993, 995));
						$oAccountToCreate->OutgoingMailUseSSL = in_array($oAccountToCreate->OutgoingMailPort, array(465));
					}

					CApi::Plugin()->RunHook('api-pre-create-account-process-call', array(&$oAccountToCreate));

					if ($oApiUsersManager->CreateAccount($oAccountToCreate, !$oAccountToCreate->IsInternal))
					{
						CApi::Plugin()->RunHook('api-success-post-create-account-process-call', array(&$oAccountToCreate));

						$mResult = $oAccountToCreate;
					}
					else
					{
						$oException = $oApiUsersManager->GetLastException();

						CApi::Plugin()->RunHook('api-error-post-create-account-process-call', array(&$oAccountToCreate, &$oException));

						throw (is_object($oException))
							? $oException
							: new CApiManagerException(Errs::WebMailManager_AccountCreateOnLogin);
					}
				}
				else
				{
					throw new CApiManagerException(Errs::WebMailManager_DomainDoesNotExist);
				}
			}
			else
			{
				throw new CApiManagerException(Errs::WebMailManager_NewUserRegistrationDisabled);
			}
		}
		catch (CApiBaseException $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}

		return $mResult;
	}

	/**
	 * @param array $aConnectErrors
	 * @param int $iMailProtocol
	 * @param string $sLogin
	 * @param string $sPassword
	 * @param string $sHost
	 * @param int $iPort
	 * @param bool $bUseSsl = false
	 * @param bool &$sNamespace = null
	 * @return bool
	 */
	public function TestConnectionWithMailServer(&$aConnectErrors,
		$iMailProtocol, $sLogin, $sPassword, $sHost, $iPort, $bUseSsl = false, &$sNamespace = null)
	{
		$bResult = false;
		$aConnectErrors = array(false, false);
		$oMail = $this->oManager->GetSimpleMailProtocol($iMailProtocol, $sHost, $iPort, $bUseSsl);
		if ($oMail)
		{
			$bResult = $oMail->Connect();
			if ($bResult)
			{
				$bResult = $oMail->Login($sLogin, $sPassword);
				if ($bResult)
				{
					if (null !== $sNamespace)
					{
						$sNamespace = $oMail->GetNamespace();
					}

					$oMail->Logout();
				}
				else
				{
					$aConnectErrors[1] = true;
				}

				$oMail->Disconnect();
			}
			else
			{
				$aConnectErrors[0] = true;
			}

		}
		return $bResult;
	}

	/**
	 * @return array
	 */
	public function GetSkinList()
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
					if ('.' !== $sFile{0} && @file_exists($sDir.'/'.$sFile.'/styles.css'))
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
	 * @param string $sValue
	 * @return string
	 */
	protected function jsLanguageQuot($sValue)
	{
		return str_replace('\'', '\\\'', str_replace('\\', '\\\\', $sValue));
	}

	/**
	 * @param string $sValue
	 * @return string
	 */
	public function loaderArrayMapAddRootPathToFileName($sValue)
	{
		if (API_P7)
		{
			return CApi::WebMailPath().'legacy/'.$sValue;
		}

		return CApi::WebMailPath().$sValue;
	}

	/**
	 * @return array | bool
	 */
	public function GetJsFilesFullMap()
	{
		static $aJsFilesMap = null;
		if (null === $aJsFilesMap)
		{
			include $this->path('classes.jsfiles-map');
		}

		if (!is_array($aJsFilesMap))
		{
			$aJsFilesMap = false;
		}

		return $aJsFilesMap;
	}

	/**
	 * @param string $mType
	 * @return array
	 */
	public function GetJsFilesList($mType)
	{
		$aResult = array();
		if (is_array($mType))
		{
			foreach ($mType as $sType)
			{
				$aList = $this->GetJsFilesListByType($sType);
				if (is_array($aList) && 0 < count($aList))
				{
					$aResult = array_merge($aResult, $aList);
				}
			}
		}
		else if (is_string($mType))
		{
			$aResult = $this->GetJsFilesListByType($mType);
		}

		return $aResult;
	}

	/**
	 * @param string $sType
	 * @return array
	 */
	public function GetJsFilesListByType($sType)
	{
		$aJsFilesMap = $this->GetJsFilesFullMap();
		if (isset($aJsFilesMap[$sType]) && is_array($aJsFilesMap[$sType]))
		{
			if (CApi::GetConf('js.use-js-gzip', false) && api_Utils::IsGzipSupported())
			{
				return array('cache-loader.php?v='.CApi::VersionJs().'&t='.$sType);
			}
			else
			{
				return $aJsFilesMap[$sType];
			}
		}

		return array();
	}

	/**
	 * @param string $sType
	 * @return string
	 */
	public function GetJsSource($sType)
	{
		$aJsFilesMap = $this->GetJsFilesFullMap();
		if (isset($aJsFilesMap[$sType]) && is_array($aJsFilesMap[$sType]))
		{
			$aFiles = $aJsFilesMap[$sType];
			$aFiles = array_map(array(&$this, 'loaderArrayMapAddRootPathToFileName'), $aFiles);
			return $this->JsPacker($aFiles);
		}

		return '';
	}

	/**
	 * @param string $sPassword
	 * @return bool
	 */
	public function ValidateMasterPassword($sPassword)
	{
		$sSettingsPassword = $this->oSettings->GetConf('Common/AdminPassword');
		return $sSettingsPassword === $sPassword || md5($sPassword) === $sSettingsPassword;
	}

	/**
	 * @param string $sLang
	 * @param string $sType = 'webmail'
	 * @return string
	 */
	public function GetLanguageJsSource($sType = 'webmail')
	{
		$aResult = array();

		$aLangMap = null;
		include $this->path('classes.'.(('webmail' === $sType)
			? 'jslang-webmail-map' : 'jslang-calendar-map'));

		if (is_array($aLangMap))
		{
			foreach ($aLangMap as $sKey => $sValue)
			{
				$aResult[] = "\t".$sKey.': \''.$this->jsLanguageQuot($sValue).'\'';
			}

			if ('webmail' === $sType)
			{
				$aLangNames = CApi::GetConf('langs.names', array());
				foreach ($aLangNames as $sName => $sLocalName)
				{
					$aResult[] = "\t".'Language'.$this->jsLanguageQuot($sName).': \''.$this->jsLanguageQuot($sLocalName).'\'';
				}
			}
		}

		$sResutl = 'Lang = {'."\r\n".implode(",\r\n", $aResult)."\r\n".'};';
		if ('webmail' === $sType)
		{
			$sResutl .= '

Lang.Monthes = [Lang.Month, Lang.January, Lang.February, Lang.March, Lang.April, Lang.May, Lang.June, Lang.July, Lang.August, Lang.September, Lang.October, Lang.November, Lang.December];
';
		}

		return $sResutl;
	}

	/**
	 * @return bool
	 */
	public function ClearTempFiles()
	{
		$sTempPath = CApi::DataPath().'/temp';
		if (@is_dir($sTempPath))
		{
			$iNow = time();

			$iTime2Run = CApi::GetConf('temp.cron-time-to-run', 10800);
			$iTime2Kill = CApi::GetConf('temp.cron-time-to-kill', 10800);
			$sDataFile = CApi::GetConf('temp.cron-time-file', '.clear.dat');

			$iFiletTime = -1;
			if (@file_exists(CApi::DataPath().'/'.$sDataFile))
			{
				$iFiletTime = (int) @file_get_contents(CApi::DataPath().'/'.$sDataFile);
			}

			if ($iFiletTime === -1 || $iNow - $iFiletTime > $iTime2Run)
			{
				$this->recTimeDirRemove($sTempPath, $iTime2Kill, $iNow);
				@file_put_contents( CApi::DataPath().'/'.$sDataFile, $iNow);
			}
		}

		return true;
	}

	protected function recTimeDirRemove($sTempPath, $iTime2Kill, $iNow)
	{
		$iFileCount = 0;
		if (@is_dir($sTempPath))
		{
			$rDirH = @opendir($sTempPath);
			if ($rDirH)
			{
				while (($sFile = @readdir($rDirH)) !== false)
				{
					if ('.' !== $sFile && '..' !== $sFile)
					{
						if (@is_dir($sTempPath.'/'.$sFile))
						{
							$this->recTimeDirRemove($sTempPath.'/'.$sFile, $iTime2Kill, $iNow);
						}
						else
						{
							$iFileCount++;
						}
					}
				}
				@closedir($rDirH);
			}

			if ($iFileCount > 0)
			{
				if ($this->timeFilesRemove($sTempPath, $iTime2Kill, $iNow))
				{
					@rmdir($sTempPath);
				}
			}
			else
			{
				@rmdir($sTempPath);
			}
		}
	}

	protected function timeFilesRemove($sTempPath, $iTime2Kill, $iNow)
	{
		$bResult = true;
		if (@is_dir($sTempPath))
		{
			$rDirH = @opendir($sTempPath);
			if ($rDirH)
			{
				while (($sFile = @readdir($rDirH)) !== false)
				{
					if ($sFile !== '.' && $sFile !== '..')
					{
						if ($iNow - filemtime($sTempPath.'/'.$sFile) > $iTime2Kill)
						{
							@unlink($sTempPath.'/'.$sFile);
						}
						else
						{
							$bResult = false;
						}
					}
				}
				@closedir($rDirH);
			}
		}
		return $bResult;
	}
}
