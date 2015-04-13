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
class CApiPluginManager
{
	/**
	 * @var array
	 */
	private $_aHooks;

	/**
	 * @var array
	 */
	private $_aServiceHooks;
	
	/**
	 * @var array
	 */
	private $_aQueryHooks;

	/**
	 * @var array
	 */
	private $_aJsFiles;

	/**
	 * @var array
	 */
	private $_aCssFiles;

	/**
	 * @var array
	 */
	private $_aTemplates;

	/**
	 * @var array
	 */
	private $_aTemplatesStrings;

	/**
	 * @var array
	 */
	private $_aAddTemplates;

	/**
	 * @var array
	 */
	private $_aJsonHooks;

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
	 * @var \ProjectSeven\Actions
	 */
	protected $_oActions;

	/**
	 * @var \ProjectS
	 */
	protected $_oApiGlobalManager;

	public function __construct(CApiGlobalManager $oApiGlobalManager)
	{
		$this->_aHooks = array();
		$this->_aServiceHooks = array();
		$this->_aQueryHooks = array();
		$this->_aJsFiles = array();
		$this->_aJsonHooks = array();
		$this->_aCssFiles = array();
		$this->_aPlugins = array();
		$this->_aTemplates = array();
		$this->_aAddTemplates = array();
		$this->_mState = null;

		$this->_oApiGlobalManager = $oApiGlobalManager;
		$this->_oActions = null;

		$this->bIsEnabled = (bool) CApi::GetConf('plugins', false);

		if ($this->bIsEnabled)
		{
			$sPluginsPath = $this->GetPluginsPath();
			if (@is_dir($sPluginsPath))
			{
				if (false !== ($rDirHandle = @opendir($sPluginsPath)))
				{
					while (false !== ($sFile = @readdir($rDirHandle)))
					{
						if (0 < strlen($sFile) && '.' !== $sFile{0} && preg_match('/^[a-z0-9\-]+$/', $sFile) &&
							(CApi::GetConf('plugins.config.include-all', false) ||
								CApi::GetConf('plugins.'.$sFile, false)) &&
							@file_exists($sPluginsPath.$sFile.'/index.php'))
						{
							$oPlugin = include $sPluginsPath.$sFile.'/index.php';
							if ($oPlugin instanceof AApiPlugin)
							{
								$oPlugin->SetName($sFile);
								$oPlugin->SetPath($sPluginsPath.$sFile);
								$oPlugin->Init();
//								$oPlugin->Log('INIT > '.get_class($oPlugin));
								$this->_aPlugins[$sFile] = $oPlugin;
							}
						}
					}
					
					@closedir($rDirHandle);
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function GetPluginsPath()
	{
		return CApi::DataPath().'/plugins/';
	}

	/**
	 * @param string $sName
	 * @return AApiPlugin
	 */
	public function GetPluginByName($sName)
	{
		return (isset($this->_aPlugins[$sName]) &&  $this->_aPlugins[$sName] instanceof AApiPlugin) ? $this->_aPlugins[$sName] : false;
	}

	/**
	 * @return CApiGlobalManager
	 */
	public function SetActions($oActions)
	{
		$this->_oActions = $oActions;
	}

	/**
	 * @return \ProjectSeven\Actions|null
	 */
	public function Actions()
	{
		return $this->_oActions;
	}

	/**
	 * @return CApiGlobalManager
	 */
	public function GlobalManager()
	{
		return $this->_oApiGlobalManager;
	}

	/**
	 * @return string
	 */
	public function Hash()
	{
		$sResult = md5(CApi::Version());
		foreach ($this->_aPlugins as $oPlugin)
		{
			$sResult = md5($sResult.$oPlugin->GetPath().$oPlugin->GetName().$oPlugin->GetHash());
		}

		return $sResult;
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
	 * @param string $sServiceName
	 * @param mixed $mHookCallbak
	 */
	public function AddServiceHook($sServiceName, $mHookCallbak)
	{
		if ($this->bIsEnabled)
		{
			if (!isset($this->_aServiceHooks[$sServiceName]))
			{
				$this->_aServiceHooks[$sServiceName] = array();
			}

			$this->_aServiceHooks[$sServiceName][] = $mHookCallbak;
		}
	}

	/**
	 * @param string $sQueryName
	 * @param mixed $mHookCallbak
	 */
	public function AddQueryHook($sQueryName, $mHookCallbak)
	{
		if ($this->bIsEnabled)
		{
			if (!isset($this->_aQueryHooks[$sQueryName]))
			{
				$this->_aQueryHooks[$sQueryName] = array();
			}

			$this->_aQueryHooks[$sQueryName][] = $mHookCallbak;
		}
	}

	/**
	 * @param string $sJsFileName
	 */
	public function AddJsFile($sJsFileName)
	{
		if ($this->bIsEnabled)
		{
			$this->_aJsFiles[] = $sJsFileName;
		}
	}

	/**
	 * @param string $sCssFileName
	 */
	public function AddCssFile($sCssFileName)
	{
		if ($this->bIsEnabled)
		{
			$this->_aCssFiles[] = $sCssFileName;
		}
	}

	/**
	 * @param string $sParsedTemplateID
	 * @param string $sParsedPlace
	 * @param string $sTemplateFileName
	 */
	public function IncludeTemplate($sParsedTemplateID, $sParsedPlace, $sTemplateFileName)
	{
		if ($this->bIsEnabled)
		{
			if (!isset($this->_aTemplates[$sParsedTemplateID]))
			{
				$this->_aTemplates[$sParsedTemplateID] = array();
			}
			
			$this->_aTemplates[$sParsedTemplateID][] = array(
				$sParsedPlace, $sTemplateFileName
			);
		}
	}

	/**
	 * @param string $sParsedTemplateID
	 * @param string $sParsedPlace
	 * @param string $sTemplateString
	 */
	public function IncludeTemplateAsString($sParsedTemplateID, $sParsedPlace, $sTemplateString)
	{
		if ($this->bIsEnabled)
		{
			if (!isset($this->_aTemplatesStrings[$sParsedTemplateID]))
			{
				$this->_aTemplatesStrings[$sParsedTemplateID] = array();
			}

			$this->_aTemplatesStrings[$sParsedTemplateID][] = array(
				$sParsedPlace, $sTemplateString
			);
		}
	}

	/**
	 * @param string $sTemplateName
	 * @param string $sTemplateFileName
	 */
	public function AddTemplate($sTemplateName, $sTemplateFileName)
	{
		if ($this->bIsEnabled)
		{
			$this->_aAddTemplates[] = array(
				$sTemplateName, $sTemplateFileName
			);
		}
	}

	/**
	 * @return bool
	 */
	public function HasJsFiles()
	{
		return 0 < count($this->_aJsFiles);
	}

	/**
	 * @return string
	 */
	public function CompileJs()
	{
		$aResult = array();
		if ($this->bIsEnabled)
		{
			foreach ($this->_aJsFiles as $sFile)
			{
				if (file_exists($sFile))
				{
					$aResult[] = file_get_contents($sFile);
				}
			}
		}

		return implode("\n", $aResult);
	}
	
	/**
	 * @return bool
	 */
	public function HasCssFiles()
	{
		return 0 < count($this->_aCssFiles);
	}	

	/**
	 * @return string
	 */
	public function CompileCss()
	{
		$aResult = array();
		if ($this->bIsEnabled)
		{
			foreach ($this->_aCssFiles as $sFile)
			{
				if (file_exists($sFile))
				{
					$aResult[] = file_get_contents($sFile);
				}
			}
		}

		return implode("\n", $aResult);
	}	
	
	/**
	 * @return string
	 */
	public function ParseTemplate($sTemplateID, $sTemplateSource)
	{
		if ($this->bIsEnabled)
		{
			if (isset($this->_aTemplates[$sTemplateID]) && is_array($this->_aTemplates[$sTemplateID]))
			{
				foreach ($this->_aTemplates[$sTemplateID] as $aItem)
				{
					if (!empty($aItem[0]) && !empty($aItem[1]) && file_exists($aItem[1]))
					{
						$sTemplateSource = str_replace('{%INCLUDE-START/'.$aItem[0].'/INCLUDE-END%}',
							file_get_contents($aItem[1]).'{%INCLUDE-START/'.$aItem[0].'/INCLUDE-END%}', $sTemplateSource);
					}
				}
			}
			
			if (isset($this->_aTemplatesStrings[$sTemplateID]) && is_array($this->_aTemplatesStrings[$sTemplateID]))
			{
				foreach ($this->_aTemplatesStrings[$sTemplateID] as $aItem)
				{
					if (!empty($aItem[0]) && isset($aItem[1]))
					{
						$sTemplateSource = str_replace('{%INCLUDE-START/'.$aItem[0].'/INCLUDE-END%}',
							$aItem[1].'{%INCLUDE-START/'.$aItem[0].'/INCLUDE-END%}', $sTemplateSource);
					}
				}
			}
		}

		return $sTemplateSource;
	}

	/**
	 * @param string $sFileName
	 *
	 * @return array|bool
	 */
	public function readLangFile($sFileName, &$aLang)
	{
		if (@file_exists($sFileName))
		{
			$aSubLang = @parse_ini_file($sFileName, true);
			if (is_array($aSubLang))
			{
				foreach ($aSubLang as $sKey => $mValue)
				{
					if (\is_array($mValue))
					{
						foreach ($mValue as $sSecKey => $mSecValue)
						{
							$aLang[$sKey.'/'.$sSecKey] = $mSecValue;
						}
					}
					else
					{
						$aLang[$sKey] = $mValue;
					}
				}
			}
		}
	}
	
	public function ParseLangs($sLanguage, &$aLang)
	{
		if ($this->bIsEnabled && !empty($sLanguage))
		{
			foreach ($this->_aPlugins as $oPlugin)
			{
				if ($oPlugin && $oPlugin->GetI18N())
				{
					$sPluginLangs = rtrim(trim($oPlugin->GetPath()), '\\/').'/i18n/';
					if (@is_dir($sPluginLangs))
					{
						$this->readLangFile($sPluginLangs.'English.ini', $aLang);
						if ('English' !== $sLanguage)
						{
							$this->readLangFile($sPluginLangs.$sLanguage.'.ini', $aLang);
						}
					}
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function GetPluginsTemplates()
	{
		$aTemplates = array();
		if ($this->bIsEnabled)
		{
			$aTemplates = $this->_aAddTemplates;
		}

		return $aTemplates;
	}

	/**
	 * @param string $sServiceNameLover
	 * @param array $aParts
	 */
	public function RunServiceHandle($sServiceNameLover, $aParts)
	{
		if (isset($this->_aServiceHooks[$sServiceNameLover]) && is_array($this->_aServiceHooks[$sServiceNameLover]))
		{
			foreach ($this->_aServiceHooks[$sServiceNameLover] as $mCallbak)
			{
				call_user_func_array($mCallbak, $aParts);
			}
		}
	}

	/**
	 * @param string $sQuery
	 */
	public function RunQueryHandle($sQuery)
	{
		$aQuery = array();
		parse_str($sQuery, $aQuery);
		if ($aQuery && is_array($aQuery))
		{
			$aQueryHooks = array_intersect_key($aQuery, $this->_aQueryHooks);
			foreach ($aQueryHooks as $sKey => $aValue)
			{
				if (isset($this->_aQueryHooks[$sKey]) && is_array($this->_aQueryHooks[$sKey]))
				{
					foreach ($this->_aQueryHooks[$sKey] as $mCallbak)
					{
						call_user_func_array($mCallbak, array($aQuery));
					}
				}
			}
		}
		
	}
	/**
	 * @deprecated
	 * @param string $sAction
	 * @param string $sRequest
	 * @param mixed $mXmlHookCallbak
	 */
	public function AddXmlHook($sXmlHookName, $mXmlHookCallbak)
	{
		// @deprecated
	}
	
	/**
	 * @param string $sHookName
	 * @param mixed $mJsonHookCallback
	 */
	public function AddJsonHook($sHookName, $mJsonHookCallback)
	{
		if ($this->bIsEnabled)
		{
			if (!isset($this->_aJsonHooks[$sHookName]))
			{
				$this->_aJsonHooks[$sHookName] = array();
			}

			$this->_aJsonHooks[$sHookName][] = $mJsonHookCallback;
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
					call_user_func_array($mHookCallbak, is_array($aArg) ? $aArg : array());
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
	 * @deprecated
	 * @param string $sXmlHookName
	 * @return bool
	 */
	public function XmlHookExist($sXmlHookName)
	{
		return false;
	}

	/**
	 * @deprecated
	 * @param CAppServer $oServer
	 * @param string $sXmlHookName
	 */
	public function RunXmlHook(&$oServer, $sXmlHookName)
	{
		// deprecated
	}

	/**
	 * @param string $sJsonHookName
	 * @return bool
	 */
	public function JsonHookExists($sJsonHookName)
	{
		return isset($this->_aJsonHooks[$sJsonHookName]);
	}

	/**
	 * @param \ProjectSeven\Actions $oServer
	 * @param string $sJsonHookName
	 */
	public function RunJsonHook(&$oServer, $sJsonHookName)
	{
		if ($this->bIsEnabled)
		{
			if (isset($this->_aJsonHooks[$sJsonHookName]))
			{
				foreach ($this->_aJsonHooks[$sJsonHookName] as $mHookCallbak)
				{
					$this->logCallback('JSONHOOK', $sJsonHookName, $mHookCallbak);
					return call_user_func_array($mHookCallbak, array(&$oServer));
				}
			}
		}

		return null;
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
	protected $sPath;

	/**
	 * @var bool
	 */
	protected $bI18N;

	/**
	 * @var string
	 */
	protected $sVersion;

	/**
	 * @var CApiPluginManager
	 */
	protected $oPluginManager;

	/**
	 * @param string $sVersion
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct($sVersion, CApiPluginManager $oPluginManager)
	{
		$this->sVersion = (string) $sVersion;
		$this->oPluginManager = $oPluginManager;

		$this->sName = '';
		$this->sPath = '';
		$this->bI18N = false;
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
	 * @param string $sPath
	 */
	final public function SetPath($sPath)
	{
		$this->sPath = $sPath;
	}

	/**
	 * @param bool $bI18N
	 */
	final public function SetI18N($bI18N)
	{
		$this->bI18N = !!$bI18N;
	}

	/**
	 * @return string
	 */
	public function GetHash()
	{
		return '';
	}

	/**
	 * @return string
	 */
	public function GetName()
	{
		return $this->sName;
	}

	/**
	 * @return bool
	 */
	public function GetI18N()
	{
		return $this->bI18N;
	}

	/**
	 * @return string
	 */
	public function GetPath()
	{
		return $this->sPath;
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
		$this->Log('deprecated plugin function AddXmlHook', ELogLevel::Warning);
		//$this->oPluginManager->AddXmlHook($sXmlHookName, array(&$this, $sFunctionName));
	}

	/**
	 * @param string $sHookName
	 * @param string $sFunctionName
	 */
	public function AddJsonHook($sHookName, $sFunctionName)
	{
		$this->oPluginManager->AddJsonHook($sHookName, array(&$this, $sFunctionName));
	}

	/**
	 * @param string $sServiceName
	 * @param string $sFunctionName
	 */
	public function AddServiceHook($sServiceName, $sFunctionName)
	{
		$this->oPluginManager->AddServiceHook($sServiceName, array(&$this, $sFunctionName));
	}

	/**
	 * @param string $sQueryName
	 * @param string $sFunctionName
	 */
	public function AddQueryHook($sQueryName, $sFunctionName)
	{
		$this->oPluginManager->AddQueryHook($sQueryName, array(&$this, $sFunctionName));
	}

	/**
	 * @param string $sJsFileName
	 */
	public function AddJsFile($sJsFileName)
	{
		if (file_exists($this->sPath.'/'.$sJsFileName))
		{
			$this->oPluginManager->AddJsFile($this->sPath.'/'.$sJsFileName);
		}
	}

	/**
	 * @param string $sCssFileName
	 */
	public function AddCssFile($sCssFileName)
	{
		if (file_exists($this->sPath.'/'.$sCssFileName))
		{
			$this->oPluginManager->AddCssFile($this->sPath.'/'.$sCssFileName);
		}
	}

	/**
	 * @param string $sParsedTemplateID
	 * @param string $sParsedPlace
	 * @param string $sTemplateFileName
	 */
	public function IncludeTemplate($sParsedTemplateID, $sParsedPlace, $sTemplateFileName)
	{
		if (0 < strlen($sParsedTemplateID) && 0 < strlen($sParsedPlace) && file_exists($this->sPath.'/'.$sTemplateFileName))
		{
			$this->oPluginManager->IncludeTemplate($sParsedTemplateID, $sParsedPlace, $this->sPath.'/'.$sTemplateFileName);
		}
	}

	/**
	 * @param string $sParsedTemplateID
	 * @param string $sParsedPlace
	 * @param string $sTemplateHtml
	 */
	public function IncludeTemplateAsString($sParsedTemplateID, $sParsedPlace, $sTemplateHtml)
	{
		if (0 < strlen($sParsedTemplateID) && 0 < strlen($sParsedPlace))
		{
			$this->oPluginManager->IncludeTemplateAsString($sParsedTemplateID, $sParsedPlace, $sTemplateHtml);
		}
	}

	/**
	 * @param string $sTemplateName
	 * @param string $sTemplateFileName
	 * @param string $sLayoutName = 'Layout'
	 * @param string $sLayoutPosition = 'Screens-Middle'
	 */
	public function AddTemplate($sTemplateName, $sTemplateFileName, $sLayoutName = 'Layout', $sLayoutPosition = 'Screens-Middle', $sClass = 'screen')
	{
		if (0 < strlen($sTemplateName) && file_exists($this->sPath.'/'.$sTemplateFileName))
		{
			$sTemplateName = 'Plugin_'.preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(array('/', '\\'), '_', $sTemplateName));

			$this->IncludeTemplateAsString($sLayoutName, 'Layout-'.$sLayoutPosition,
				'<div data-view-model="'.$sTemplateName.'" class="' . $sClass . '" style="display: none;"></div>');
			
			$this->oPluginManager->AddTemplate($sTemplateName, $this->sPath.'/'.$sTemplateFileName);
		}
	}

	/**
	 * @param string $sDesc
	 * @param int $iLogLevel = ELogLevel::Full
	 */
	public function Log($sDesc, $iLogLevel = ELogLevel::Full)
	{
		CApi::Log('PLUGIN > '.$sDesc, $iLogLevel);
	}
	
	/**
	 * @param string $sFileName
	 * @return string
	 */
	public function GetImage($sFileName)
	{
		$sContentType = \MailSo\Base\Utils::MimeContentType($sFileName);
		if ('image' === \MailSo\Base\Utils::ContentTypeType($sContentType, $sFileName))
		{
			@header('Content-Type: ' . $sContentType);
			$sFilePath = $this->GetPath() . '/images/' . $sFileName;
			if (file_exists($sFilePath))
			{
				return file_get_contents($sFilePath);
			}
			else
			{
					if (function_exists('http_response_code'))
					{
						\http_response_code(404);
					}
					else
					{
						\header("HTTP/1.1 404 Not Found", true, 404);
					}
			}
		}
	}
	
	/**
	 * @param string $sData
	 * @param CAccount $oAccount
	 *
	 * @return string
	 */
	public function I18N($sData)
	{
		$oSettings =& \CApi::GetSettings();
		$sLanguage = $oSettings->GetConf('Common/DefaultLanguage');

		$sLangFile = rtrim(trim($this->GetPath()), '\\/').'/i18n/'.$sLanguage.'.ini';
		if (@file_exists($sLangFile))
		{
			$aLang = CApi::convertIniToLang($sLangFile);
			if (is_array($aLang))
			{
				return isset($aLang[$sData]) ? $aLang[$sData] : '';
			}
		}
	}
}
