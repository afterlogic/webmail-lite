<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Api
 */
class CApiPluginManager
{
	/**
	 * @var array
	 */
	private $_aHooks;

	/**
	 * @var array
	 */
	private $_aXmlHooks;

	/**
	 * @var array
	 */
	private $_aPlugins;

	/**
	 * @var mixed
	 */
	private $_mState;

	/**
	 * @var bool
	 */
	protected $bIsEnabled;

	/**
	 * @var CApiGlobalManager
	 */
	protected $_oApiGlobalManager;

	public function __construct(CApiGlobalManager $oApiGlobalManager)
	{
		$this->_aHooks = array();
		$this->_aXmlHooks = array();
		$this->_aPlugins = array();
		$this->_mState = null;

		$this->_oApiGlobalManager = $oApiGlobalManager;

		$this->bIsEnabled = (bool) CApi::GetConf('plugins', false);

		if ($this->bIsEnabled)
		{
			$sPluginsPath = CApi::DataPath().'/plugins/';
			if (@is_dir($sPluginsPath))
			{
				if (false !== ($rDirHandle = @opendir($sPluginsPath)))
				{
					while (false !== ($sFile = @readdir($rDirHandle)))
					{
						if ('.' !== $sFile{0} && preg_match('/^[a-z0-9\-]+$/', $sFile) &&
							(CApi::GetConf('plugins.config.include-all', false) ||
								CApi::GetConf('plugins.'.$sFile, false)) &&
							@file_exists($sPluginsPath.$sFile.'/index.php'))
						{
							$oPlugin = include $sPluginsPath.$sFile.'/index.php';
							if ($oPlugin instanceof AApiPlugin)
							{
								$oPlugin->SetName($sFile);
								$oPlugin->Init();
//								$oPlugin->Log('INIT > '.get_class($oPlugin));
								$this->_aPlugins[] = $oPlugin;
							}
						}
					}
					@closedir($rDirHandle);
				}
			}
		}
	}

	/**
	 * @return CApiGlobalManager
	 */
	public function GlobalManager()
	{
		return $this->_oApiGlobalManager;
	}

	/**
	 * @param string $sHookName
	 * @param mixed $mHookCallbak
	 */
	public function AddHook($sHookName, $mHookCallbak)
	{
		if ($this->bIsEnabled)
		{
			if (!isset($this->_aHooks[$sHookName]))
			{
				$this->_aHooks[$sHookName] = array();
			}

			$this->_aHooks[$sHookName][] = $mHookCallbak;
		}
	}

	/**
	 * @param string $sAction
	 * @param string $sRequest
	 * @param mixed $mXmlHookCallbak
	 */
	public function AddXmlHook($sXmlHookName, $mXmlHookCallbak)
	{
		if ($this->bIsEnabled)
		{
			if (!isset($this->_aXmlHooks[$sXmlHookName]))
			{
				$this->_aXmlHooks[$sXmlHookName] = array();
			}

			$this->_aXmlHooks[$sXmlHookName][] = $mXmlHookCallbak;
		}
	}

	/**
	 * @param string $sAction
	 * @param array $aArg = array()
	 */
	public function RunHook($sAction, $aArg = array())
	{
		if ($this->bIsEnabled)
		{
			if (isset($this->_aHooks[$sAction]))
			{
				foreach ($this->_aHooks[$sAction] as $mHookCallbak)
				{
					$this->logCallback('HOOK', $sAction, $mHookCallbak);
					call_user_func_array($mHookCallbak, $aArg);
				}
			}
		}
	}

	/**
	 * @param string $sHookName
	 * @return bool
	 */
	public function HookExist($sHookName)
	{
		return isset($this->_aHooks[$sHookName]);
	}

	/**
	 * @param string $sXmlHookName
	 * @return bool
	 */
	public function XmlHookExist($sXmlHookName)
	{
		return isset($this->_aXmlHooks[$sXmlHookName]);
	}

	/**
	 * @param CAppServer $oServer
	 * @param string $sXmlHookName
	 */
	public function RunXmlHook(&$oServer, $sXmlHookName)
	{
		if ($this->bIsEnabled)
		{
			if (isset($this->_aXmlHooks[$sXmlHookName]))
			{
				foreach ($this->_aXmlHooks[$sXmlHookName] as $mHookCallbak)
				{
					$this->logCallback('XMLHOOK', $sXmlHookName, $mHookCallbak);
					call_user_func_array($mHookCallbak, array(&$oServer));
				}
			}
		}
	}

	/**
	 * @param string $sLogPrefix
	 * @param string $sName
	 * @param mixed $mHookCallbak
	 */
	protected function logCallback($sLogPrefix, $sName, $mHookCallbak)
	{
		CApi::Log($sLogPrefix.' > '.
			(is_string($mHookCallbak) ? $mHookCallbak :
			(is_array($mHookCallbak)
				&& is_object($mHookCallbak[0])
				&& is_string($mHookCallbak[1]) ?
					get_class($mHookCallbak[0]).'->'.$mHookCallbak[1] :
					'action = '.$sName)));
	}
}

/**
 * @package Api
 */
abstract class AApiPlugin
{
	/**
	 * @var string
	 */
	protected $sName;

	/**
	 * @var string
	 */
	protected $sVersion;

	/**
	 * @var CApiPluginManager
	 */
	protected $oPluginManager;

	/**
	 * @param string $oPluginManager
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct($sVersion, CApiPluginManager $oPluginManager)
	{
		$this->sVersion = (string) $sVersion;
		$this->oPluginManager = $oPluginManager;
	}

	public function Init()
	{
	}

	/**
	 * @staticvar string $oApiSieveManager
	 * @return CApiSieveManager
	 */
	protected function getSieveManager()
	{
		static $oApiSieveManager = null;
		if (null === $oApiSieveManager)
		{
			$oApiSieveManager = CApi::Manager('sieve');
		}
		return $oApiSieveManager;
	}

	/**
	 * @param string $sName
	 */
	final public function SetName($sName)
	{
		$this->sName = $sName;
	}

	/**
	 * @return string
	 */
	public function GetName()
	{
		return $this->sName;
	}

	/**
	 * @return string
	 */
	public function GetVersion()
	{
		return $this->sVersion;
	}

	/**
	 * @return string
	 */
	public function GetFullName()
	{
		return $this->sName.'-'.$this->sVersion;
	}

	/**
	 * @param string $sHookName
	 * @param string $sFunctionName
	 */
	public function AddHook($sHookName, $sFunctionName)
	{
		$this->oPluginManager->AddHook($sHookName, array(&$this, $sFunctionName));
	}

	/**
	 * @param string $sXmlHookName
	 * @param string $sFunctionName
	 */
	public function AddXmlHook($sXmlHookName, $sFunctionName)
	{
		$this->oPluginManager->AddXmlHook($sXmlHookName, array(&$this, $sFunctionName));
	}

	/**
	 * @param string $sDesc
	 * @param int $iLogLevel = ELogLevel::Full
	 */
	public function Log($sDesc, $iLogLevel = ELogLevel::Full)
	{
		CApi::Log('PLUGIN > '.$sDesc, $iLogLevel);
	}
}
