<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Api
 */
class CApiGlobalManager
{
	/**
	 * @var api_Settings
	 */
	protected $oSettings;

	/**
	 * @var CDbStorage
	 */
	protected $oConnection;

	/**
	 * @var CMySqlHelper
	 */
	protected $oSqlHelper;

	/**
	 * @var array
	 */
	protected $aManagers;

	/**
	 * @var array
	 */
	protected $aStorageMap;

	public function __construct()
	{
		$this->oSettings = null;
		$this->oConnection = null;
		$this->oSqlHelper = null;
		$this->aManagers = array();
		$this->aStorageMap = array(
			'mailsuite' => 'db',
			'db' => 'db',
			'domains' => 'db',
			'realms' => 'db',
			'channels' => 'db',
			'gcontacts' => 'db',
			'users' => 'db',
			'webmail' => 'db',
			'mail' => 'db',
			'filecache' => 'file',
			'contacts' => 'sabredav',
			'calendar' => 'sabredav'
		);
	}

	/**
	 * @return api_Settings
	 */
	public function &GetSettings()
	{
		if (null === $this->oSettings)
		{
			CApi::Inc('common.settings');
			try
			{
				$this->oSettings = new api_Settings(CApi::DataPath());
			}
			catch (CApiBaseException $oException)
			{
				$this->oSettings = false;
			}
		}

		return $this->oSettings;
	}

	protected function setStorageTypeFromSettings($sName, $sSettingsName)
	{
		$oSettings =& $this->GetSettings();
		if ($oSettings)
		{
			$sType = $oSettings->GetConf($sSettingsName);
			if (!empty($sType))
			{
				$this->aStorageMap[$sName] = $sType;
			}
		}
	}

	public function PrepareStorageMap()
	{
		$this->setStorageTypeFromSettings('mailsuite', 'StorageTypes/MailSuite');
		$this->setStorageTypeFromSettings('db', 'StorageTypes/Db');
		$this->setStorageTypeFromSettings('domains', 'StorageTypes/Domains');
		$this->setStorageTypeFromSettings('contacts', 'StorageTypes/Contacts');
		$this->setStorageTypeFromSettings('users', 'StorageTypes/Users');
		$this->setStorageTypeFromSettings('webmail', 'StorageTypes/WebMail');
		$this->setStorageTypeFromSettings('calendar', 'StorageTypes/Calendar');
		$this->setStorageTypeFromSettings('mail', 'StorageTypes/Mail');
		$this->setStorageTypeFromSettings('gcontacts', 'StorageTypes/Gcontacts');

		CApi::Plugin()->RunHook('api-prepare-storage-map', array(&$this->aStorageMap));
	}

	/**
	 * @param string $sManagerName
	 * @return string
	 */
	public function GetStorageByType($sManagerName)
	{
		return isset($this->aStorageMap[$sManagerName]) ? $this->aStorageMap[$sManagerName] : '';
	}

	/**
	 * @return CDbStorage
	 */
	public function &GetConnection()
	{
		if (null === $this->oConnection)
		{
			$oSettings =& $this->GetSettings();

			if ($oSettings)
			{
				$this->oConnection = new CDbStorage($oSettings);
			}
			else
			{
				$this->oConnection = false;
			}
		}

		return $this->oConnection;
	}

	/**
	 * @return CDbStorage
	 */
	public function &GetSqlHelper()
	{
		if (null === $this->oSqlHelper)
		{
			$oSettings =& $this->GetSettings();

			if ($oSettings)
			{
				$this->oSqlHelper = CDbCreator::CreateCommandCreatorHelper($oSettings);
			}
			else
			{
				$this->oSqlHelper = false;
			}
		}

		return $this->oSqlHelper;
	}

	/**
	 * @param bool $iMailProtocol
	 * @return CApiImap4MailProtocol
	 */
	public function GetSimpleMailProtocol($iMailProtocol, $sHost, $iPort, $bUseSsl = false)
	{
		$oMail = null;
		switch ($iMailProtocol)
		{
			case EMailProtocol::IMAP4:
				CApi::Inc('common.net.protocols.imap4');
				$oMail = new CApiImap4MailProtocol($sHost, $iPort, $bUseSsl);
				break;
			case EMailProtocol::POP3:
				CApi::Inc('common.net.protocols.pop3');
				$oMail = new CApiPop3MailProtocol($sHost, $iPort, $bUseSsl);
				break;
		}
		return $oMail;
	}

	public function &GetCommandCreator(AApiManagerStorage &$oStorage, $aCommandCreatorsNames)
	{
		$oSettings =& $oStorage->GetSettings();
		$oCommandCreatorHelper =& $this->GetSqlHelper();

		$oCommandCreator = null;

		if ($oSettings)
		{
			$sDbType = $oSettings->GetConf('Common/DBType');
			$sDbPrefix = $oSettings->GetConf('Common/DBPrefix');

			if (isset($aCommandCreatorsNames[$sDbType]))
			{
				CApi::Inc('common.db.command_creator');
				CApi::StorageInc($oStorage->GetManagerName(), $oStorage->GetStorageName(), 'command_creator');

				$oCommandCreator =
					new $aCommandCreatorsNames[$sDbType]($oCommandCreatorHelper, $sDbPrefix);
			}
		}

		return $oCommandCreator;
	}

	/**
	 * @param string $sManagerType
	 * @param string $sForcedStorage = ''
	 */
	public function GetByType($sManagerType, $sForcedStorage = '')
	{
		$oResult = null;
		if ('dav' === $sManagerType && (version_compare(phpversion(), '5.3.0') <= -1))
		{
			
		}
		else if (CApi::IsValid())
		{
			$sManagerKey = empty($sForcedStorage) ? $sManagerType : $sManagerType.'/'.$sForcedStorage;
			if (isset($this->aManagers[$sManagerKey]))
			{
				$oResult =& $this->aManagers[$sManagerKey];
			}
			else
			{
				$sManagerType = strtolower($sManagerType);
				if (CApi::Inc('common.managers.'.$sManagerType.'.manager', false))
				{
					$sClassName = 'CApi'.ucfirst($sManagerType).'Manager';
					$oMan = new $sClassName($this, $sForcedStorage);
					$sCurrentStorageName = $oMan->GetStorageName();

					$sManagerKey = empty($sCurrentStorageName) ? $sManagerType : $sManagerType.'/'.$sCurrentStorageName;
					$this->aManagers[$sManagerKey] = $oMan;
					$oResult =& $this->aManagers[$sManagerKey];
				}
			}
		}

		return $oResult;
	}

	/**
	 * @param string $sManagerType
	 * @param string $sForcedStorage = ''
	 */
	public function GetByType1($sManagerType, $sForcedStorage = '')
	{
		$oResult = null;
		if (CApi::IsValid())
		{
			if (empty($sForcedStorage))
			{
				if (isset($this->aManagers[$sManagerType]))
				{
					$oResult =& $this->aManagers[$sManagerType];
				}
				else
				{
					$sManagerType = strtolower($sManagerType);
					if (CApi::Inc('common.managers.'.$sManagerType.'.manager', false))
					{
						$sClassName = 'CApi'.ucfirst($sManagerType).'Manager';
						$this->aManagers[$sManagerType] = new $sClassName($this);
						$oResult =& $this->aManagers[$sManagerType];
					}
				}
			}
			else
			{
				if (CApi::Inc('common.managers.'.$sManagerType.'.manager', false))
				{
					$sClassName = 'CApi'.ucfirst($sManagerType).'Manager';
					$oResult = new $sClassName($this, $sForcedStorage);
				}
			}
		}

		return $oResult;
	}
}

/**
 * @package Api
 */
class CApiGlobalManagerException extends CApiBaseException {}

/**
 * @package Api
 */
abstract class AApiManager
{
	/**
	 * @var CApiManagerException
	 */
	protected $oLastException;

	/**
	 * @var string
	 */
	protected $sManagerName;

	/**
	 * @var CApiGlobalManager
	 */
	protected $oManager;

	/**
	 * @var api_Settings
	 */
	protected $oSettings;

	public function __construct($sManagerName, CApiGlobalManager &$oManager)
	{
		$this->sManagerName = strtolower($sManagerName);
		$this->oSettings =& $oManager->GetSettings();
		$this->oManager =& $oManager;
		$this->oLastException = null;
	}

	/**
	 * @return string
	 */
	public function GetManagerName()
	{
		return $this->sManagerName;
	}

	/**
	 * @return string
	 */
	public function GetStorageName()
	{
		return '';
	}

	/**
	 * @return &api_Settings
	 */
	public function &GetSettings()
	{
		return $this->oSettings;
	}

	/**
	 * @param string $sInclude
	 * @return void
	 */
	protected function inc($sInclude)
	{
		CApi::ManagerInc($this->GetManagerName(), $sInclude);
	}

	/**
	 * @param string $sInclude
	 * @return string
	 */
	public function path($sInclude)
	{
		return CApi::ManagerPath($this->GetManagerName(), $sInclude);
	}

	/**
	 * @param Exception $oException
	 * @param bool $bLog = true
	 */
	protected function setLastException(Exception $oException, $bLog = true)
	{
		$this->oLastException = $oException;

		if ($bLog)
		{
			$sFile = str_replace(
				str_replace('\\', '/', strtolower(realpath(CApi::WebMailPath()))), '~ ',
				str_replace('\\', '/', strtolower($oException->getFile())));

			CApi::Log('Exception['.$oException->getCode().']: '.$oException->getMessage().
				API_CRLF.$sFile.' ('.$oException->getLine().')'.
				API_CRLF.'----------------------------------------------------------------------'.
				API_CRLF.$oException->getTraceAsString(), ELogLevel::Error);
		}
	}

	/**
	 * @return Exception
	 */
	public function GetLastException()
	{
		return $this->oLastException;
	}

	/**
	 * @return int
	 */
	public function GetLastErrorCode()
	{
		$iResult = 0;
		if (null !== $this->oLastException)
		{
			$iResult = $this->oLastException->getCode();
		}
		return $iResult;
	}

	/**
	 * @return string
	 */
	public function GetLastErrorMessage()
	{
		$sResult = '';
		if (null !== $this->oLastException)
		{
			$sResult = $this->oLastException->getMessage();
		}
		return $sResult;
	}
}

/**
 * @package Api
 */
abstract class AApiManagerWithStorage extends AApiManager
{
	/**
	 * @var string
	 */
	protected $sStorageName;

	/**
	 * @var AApiManagerStorage
	 */
	protected $oStorage;

	/**
	 * @param string $sManagerName
	 * @param CApiGlobalManager &$oManager
	 * @param string $sForcedStorage = ''
	 * @return AApiManager
	 */
	public function __construct($sManagerName, CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct($sManagerName, $oManager);

		$this->oStorage = null;
		$this->sStorageName = !empty($sForcedStorage)
			? strtolower(trim($sForcedStorage)) : strtolower($oManager->GetStorageByType($sManagerName));

		CApi::Inc('common.managers.'.$this->GetManagerName().'.storages.default');

		if (CApi::Inc('common.managers.'.$this->GetManagerName().'.storages.'.$this->GetStorageName().'.storage', false))
		{
			$sClassName = 'CApi'.ucfirst($this->GetManagerName()).ucfirst($this->GetStorageName()).'Storage';
			$this->oStorage = new $sClassName($oManager);
		}
		else
		{
			$sClassName = 'CApi'.ucfirst($this->GetManagerName()).'Storage';
			$this->oStorage = new $sClassName($this->sStorageName, $oManager);
		}
	}

	/**
	 * @return string
	 */
	public function GetStorageName()
	{
		return $this->sStorageName;
	}

	/**
	 * @return AApiManagerStorage
	 */
	public function &GetStorage()
	{
		return $this->oStorage;
	}

	public function moveStorageExceptionToManager()
	{
		if ($this->oStorage)
		{
			$oException = $this->oStorage->GetStorageException();
			if ($oException)
			{
				$this->oLastException = $oException;
			}
		}
	}
}

/**
 * @package Api
 */
class CApiManagerException extends CApiBaseException {}

/**
 * @package Api
 */
abstract class AApiManagerStorage
{
	/**
	 * @var string
	 */
	protected $sManagerName;

	/**
	 * @var string
	 */
	protected $sStorageName;

	/**
	 * @var api_Settings
	 */
	protected $oSettings;

	/**
	 * @var CApiBaseException
	 */
	protected $oLastException;

	public function __construct($sManagerName, $sStorageName, CApiGlobalManager &$oManager)
	{
		$this->sManagerName = strtolower($sManagerName);
		$this->sStorageName = strtolower($sStorageName);
		$this->oSettings =& $oManager->GetSettings();
		$this->oLastException = null;
	}

	/**
	 * @return string
	 */
	public function GetManagerName()
	{
		return $this->sManagerName;
	}

	/**
	 * @return string
	 */
	public function GetStorageName()
	{
		return $this->sStorageName;
	}

	/**
	 * @return &api_Settings
	 */
	public function &GetSettings()
	{
		return $this->oSettings;
	}

	/**
	 * @return CApiBaseException
	 */
	public function GetStorageException()
	{
		return $this->oLastException;
	}

	/**
	 * @param CApiBaseException $oException
	 */
	public function SetStorageException($oException)
	{
		$this->oLastException = $oException;
	}

	/**
	 * @todo move to db storage
	 */
	protected function throwDbExceptionIfExist()
	{
		// connection in db storage
		if ($this->oConnection)
		{
			$oException = $this->oConnection->GetException();
			if ($oException instanceof CApiDbException)
			{
				throw new CApiBaseException(Errs::Db_ExceptionError, $oException);
			}
		}
	}

	/**
	 * @param string $sInclude
	 * @return void
	 */
	protected function inc($sInclude)
	{
		CApi::StorageInc($this->GetManagerName(), $this->GetStorageName(), $sInclude);
	}
}

/**
 * @package Api
 */
class CApiStorageException extends CApiBaseException {}
