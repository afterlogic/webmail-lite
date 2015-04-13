<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
	 * @param bool $bAllowInternalOnly = false
	 * @return CAccount | false
	 */
	public function CreateAccountProcess($sEmail, $sPassword, $sChangeLang = '', $aExtValues = null, $bAllowInternalOnly = false)
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

			$bApiIntegratorLoginToAccountResult = isset($aExtValues['ApiIntegratorLoginToAccountResult']) ? $aExtValues['ApiIntegratorLoginToAccountResult'] : false;
			if ($oDomain && ($bApiIntegratorLoginToAccountResult || $oDomain->AllowNewUsersRegister || ($oDomain->IsInternal && $bAllowInternalOnly) || 'nodb' === CApi::GetManager()->GetStorageByType('webmail')))
			{
				/*if ($oDomain && !$oDomain->AllowWebMail)
				{
					throw new CApiManagerException(Errs::WebMailManager_AccountWebmailDisabled);
				}
				else */if ($oDomain && $oDomain->IsInternal && !$bAllowInternalOnly)
				{
					throw new CApiManagerException(Errs::WebMailManager_NewUserRegistrationDisabled);
				}
				else if ($oDomain && $bAllowInternalOnly && (!$oDomain->IsInternal || $oDomain->IsDefaultDomain))
				{
					throw new CApiManagerException(Errs::WebMailManager_NewUserRegistrationDisabled);
				}
				else if ($oDomain)
				{
					$oAccountToCreate = new CAccount($oDomain);
					$oAccountToCreate->Email = $sEmail;

//					$oAccountToCreate->IncomingMailLogin = (isset($aExtValues['Login'])) ? $aExtValues['Login'] :
//						(($this->oSettings->GetConf('WebMail/UseLoginWithoutDomain'))
//							? api_Utils::GetAccountNameFromEmail($sEmail) : $sEmail);
										
					$oAccountToCreate->IncomingMailLogin = (isset($aExtValues['Login']) ? $aExtValues['Login'] : $sEmail);
					if ($this->oSettings->GetConf('WebMail/UseLoginWithoutDomain'))
					{
						$oAccountToCreate->IncomingMailLogin = api_Utils::GetAccountNameFromEmail($oAccountToCreate->IncomingMailLogin);
					}

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

					if (isset($aExtValues['FriendlyName']))
					{
						$oAccountToCreate->FriendlyName = $aExtValues['FriendlyName'];
					}

					if (isset($aExtValues['Question1']))
					{
						$oAccountToCreate->User->Question1 = $aExtValues['Question1'];
					}

					if (isset($aExtValues['Question2']))
					{
						$oAccountToCreate->User->Question2 = $aExtValues['Question2'];
					}

					if (isset($aExtValues['Answer1']))
					{
						$oAccountToCreate->User->Answer1 = $aExtValues['Answer1'];
					}

					if (isset($aExtValues['Answer2']))
					{
						$oAccountToCreate->User->Answer2 = $aExtValues['Answer2'];
					}
					
					if ($oApiUsersManager->CreateAccount($oAccountToCreate,
						!($oAccountToCreate->IsInternal || !$oAccountToCreate->Domain->AllowWebMail || $bApiIntegratorLoginToAccountResult)))
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
	 * @param string $sPassword
	 * @return bool
	 */
	public function ValidateMasterPassword($sPassword)
	{
		$sSettingsPassword = $this->oSettings->GetConf('Common/AdminPassword');
		return $sSettingsPassword === $sPassword || md5($sPassword) === $sSettingsPassword;
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
