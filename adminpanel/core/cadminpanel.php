<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

class CAdminPanel
{
	/**
	 * @var bool
	 */
	public $PType;

	/**
	 * @var bool
	 */
	public $LType;

	/**
	 * @var bool
	 */
	public $XType;

	/**
	 * @var bool
	 */
	public $RType;

	/**
	 * @var array
	 */
	protected $aModules;

	/**
	 * @var object
	 */
	protected $oCurrentScreen;

	/**
	 * @var array
	 */
	protected $aJsFiles;

	/**
	 * @var array
	 */
	protected $aCssFiles;

	/**
	 * @var array
	 */
	protected $aInitJsText;

	/**
	 * @var bool
	 */
	protected $bIsAuth;

	/**
	 * @var int
	 */
	protected $iAuthType;

	/**
	 * @var array
	 */
	protected $aAuthDomains;

	/**
	 * @var int
	 */
	protected $iRealmId;

	/**
	 * @var array
	 */
	protected $aTabs;

	/**
	 * @var string
	 */
	protected $sTabsInfo;

	/**
	 * @var string
	 */
	protected $sTab;

	/**
	 * @var bool
	 */
	protected $bShowScreen;

	/**
	 * @var array
	 */
	protected $aMainObjectList;

	/**
	 * @var array
	 */
	protected $aTabsSort;

	/**
	 * @return CAdminPanel
	 */
	public function __construct($sIndexFileName = null)
	{
		$GLOBALS['WM-ADMINPANEL-RUN'] = true; // TODO global is evil

		$this->aModules = array();
		$this->bShowScreen = true;

		$this->bIsAuth = false;
		$this->PType = false;
		$this->XType = false;
		$this->RType = false;

		$this->iAuthType = null;
		$this->aAuthDomains = null;
		$this->iRealmId = 0;

		$this->bSessionIsStarted = false;

		$this->aCssFiles = array();
		$this->aJsFiles = array();
		$this->aInitJsText = array();
		$this->aTabs = array();
		$this->aModules = array();
		$this->sTab = '';
		$this->sTabsInfo = '';

		$this->aMainObjectList = array();
		$this->aTabsSort = array();

		if (null !== $sIndexFileName)
		{
			$sIndexFileName = basename($sIndexFileName);
			if (strlen($sIndexFileName) > 3)
			{
				define('AP_INDEX_FILE', $sIndexFileName);
			}
		}

		defined('AP_INDEX_FILE') || define('AP_INDEX_FILE', 'index.php');

		$this->bSessionIsStarted = self::initInclude();

		self::initDataPath();

		$this->initAdminPanel();
	}

	public function Title()
	{
		echo 'Admin Panel';
	}

	public function End()
	{
		return $this;
	}

	public function Run()
	{
		@ob_start();
		if ($this->bShowScreen)
		{
			include self::RootPath().'core/templates/main.php';
		}
		return $this;
	}

	/**
	 * @return void
	 */
	protected function resortTabs()
	{
		$bResult = $aTemp = array();
		foreach ($this->aTabs as $aTab)
		{
			$aTemp[$aTab[1]] = $aTab;
		}

		foreach ($this->aTabsSort as $sTab)
		{
			if (in_array($sTab, array_keys($aTemp)))
			{
				$bResult[] = $aTemp[$sTab];
			}
		}

		$this->aTabs = $bResult;
	}

	/**
	 * @return object
	 */
	protected function initScreen($sTab)
	{
		$oScreen = null;
		if ($this->IsAuth())
		{
			$aScreenMap = array(
				AP_TAB_SERVICES => 'ap_Standard_Screen',
				AP_TAB_DOMAINS => 'ap_Table_Screen',
				AP_TAB_USERS => 'ap_Table_Screen',
				AP_TAB_REALMS => 'ap_Table_Screen',
				AP_TAB_CHANNELS => 'ap_Table_Screen',
				AP_TAB_SYSTEM => 'ap_Standard_Screen'
			);

			if (isset($aScreenMap[$sTab]))
			{
				$oScreen = new $aScreenMap[$sTab]($this);
				$oScreen->SetScreenName('screen'.ucfirst($sTab));
			}

			if (empty($_COOKIE['FTE_']))
			{
				if (@is_dir(self::GetWebMailPath().'install'))
				{
					$this->JsAddInitText('OnlineMsgError(\'Please delete install folder.\');');
				}

				$oLicApiManager = /* @var $oLicApiManager CApiLicensingManager */ CApi::Manager('licensing');
				if ($oLicApiManager && $oLicApiManager->IsValidKey())
				{
					$iType = $oLicApiManager->GetLicenseType();
					$iExpiredSeconds = 0;
					if (!$oLicApiManager->IsValidLimit(true))
					{
						$this->JsAddInitText('OnlineMsgError(\'User count limit reached. Consider upgrading your license.\');');
					}
					else if (in_array($iType, array(3, 10)) && $oLicApiManager->IsAboutToExpire($iExpiredSeconds))
					{
						$this->JsAddInitText('OnlineMsgError(\'Your current license will expire in '.
							ceil($iExpiredSeconds / 60 / 60 / 24).' day(s)\');');
					}
				}

				@setcookie('FTE_', true, time() + 600);
			}
		}
		else
		{
			include_once self::RootPath().'core/screens/login.php';
			$oScreen = new ap_Login_Screen($this);
			$oScreen->SetScreenName('screenLogin');
		}

		return $oScreen;
	}

	/**
	 * @return void
	 */
	public function IncludeScreen()
	{
		if (!$this->oCurrentScreen)
		{
			$this->oCurrentScreen = new ap_Simple_Screen($this, 'error.php');
			$this->oCurrentScreen->Data->SetValue('ErrorDesc', 'Admin Panel internal error.');
		}

		$this->oCurrentScreen->Run();
	}

	/**
	 * @return void
	 */
	public function IncludeCss()
	{
		foreach ($this->aCssFiles as $sCssFile)
		{
			echo '<link href="'.$sCssFile.'?'.$this->ClearAdminVersion().'" rel="stylesheet" type="text/css" />';
		}
	}

	/**
	 * @return void
	 */
	public function IncludeJs()
	{
		echo AP_CRLF;
		foreach ($this->aJsFiles as $sJsFile)
		{
			echo '<script type="text/javascript" src="'.$sJsFile.'?'.$this->ClearAdminVersion().'"></script>';
		}
		echo '<script type="text/javascript">$(function(){';
		foreach ($this->aInitJsText as $sJsText)
		{
			echo $sJsText;
		}
		echo '});</script>'.AP_CRLF;
	}

	/**
	 * @param string $sJsFile
	 */
	public function CssAddFile($sCssFile)
	{
		$this->aCssFiles[] = $sCssFile;
	}

	/**
	 * @param string $sJsFile
	 */
	public function JsAddFile($sJsFile)
	{
		$this->aJsFiles[] = $sJsFile;
	}

	/**
	 * @param string $sJsInitText
	 */
	public function JsAddInitText($sJsInitText)
	{
		$this->aInitJsText[] = $sJsInitText;
	}

	public function WriteTabs()
	{
		echo '<div class="wm_tabslist" id="accountslist">';

		$this->resortTabs();

		$bIsFirst = true;
		foreach ($this->aTabs as $aTab)
		{
			$sClassToAdd = '';
			if (true === $bIsFirst)
			{
				$sClassToAdd = ' first';
				$bIsFirst = false;
			}

			$sClass = ($this->sTab == $aTab[1]) ? 'wm_tabslist_item wm_active_tab' : 'wm_tabslist_item';
			$sClass .= $sClassToAdd;

			echo '<div class="'.$sClass.'"><a href="'.AP_INDEX_FILE.'?tab='.$aTab[1].'">'.$aTab[0].'</a></div>';
		}

		if (0 < strlen($this->sTabsInfo))
		{
			echo $this->sTabsInfo;
		}

		echo '<div class="wm_tabslist_item_small last"><a href="'.AP_INDEX_FILE.'?logout">Logout</a></div>
<div class="wm_tabslist_item_small"><a href="'.AP_INDEX_FILE.'?help" target="_blank">Help</a></div></div>';

	}

	/**
	 * @param CRealm $oRealm
	 */
	public function SetRealmTabsInfo($oRealm)
	{
		$this->sTabsInfo = '';
		if ($oRealm)
		{
			$this->sTabsInfo = '<div class="tabs_info">';
			$this->sTabsInfo .= 'Resource usage: Users - ';
			$this->sTabsInfo .= $oRealm->GetUserCount();

			if (0 < $oRealm->UserCountLimit)
			{
				$this->sTabsInfo .= ' ('.$oRealm->UserCountLimit.' max)';
			}

			$iUsed = 0;
			if (0 < $oRealm->QuotaInMB)
			{
				$iUsed = floor(($oRealm->AllocatedSpaceInMB / $oRealm->QuotaInMB) * 100);
				$this->sTabsInfo .='; Disk space - '. $iUsed.'% ('.$oRealm->AllocatedSpaceInMB.' MB) of '.$oRealm->QuotaInMB.' MB allocated';
			}
			else
			{
				$this->sTabsInfo .='; Disk space - '. $oRealm->AllocatedSpaceInMB.' MB allocated';
			}

			$this->sTabsInfo .= '</div>';
		}
	}

	/**
	 * @return string
	 */
	public function Tab()
	{
		return $this->sTab;
	}

	/**
	 * @return array
	 */
	public function &GetTabs()
	{
		return $this->aTabs;
	}

	/**
	 * @return array
	 */
	public function RemoveTabs($aTabNames)
	{
		if (is_array($aTabNames))
		{
			$aNewTabs = array();
			$aTabs = $this->aTabs;

			foreach ($aTabs as $aTabItem)
			{
				if (!in_array($aTabItem[1], $aTabNames))
				{
					$aNewTabs[] = $aTabItem;
				}
			}

			$this->aTabs = $aNewTabs;
		}
	}

	/**
	 * @return void
	 */
	protected function initAdminPanel()
	{
		$this->RType = (bool) CApi::GetConf('realm', false);

		$this->aTabsSort = array(
			AP_TAB_SERVICES,
			AP_TAB_DOMAINS,
			AP_TAB_USERS,
			AP_TAB_REALMS,
			AP_TAB_CHANNELS,
			AP_TAB_SYSTEM
		);

		$this->aTabs[] = array('System', AP_TAB_SYSTEM);

		$GLOBALS[AP_START_TIME] = ap_Utils::Microtime();
		$GLOBALS[AP_DB_COUNT] = 0;

		if (isset($_GET['help']))
		{
			CApi::Location('http://www.afterlogic.com/wiki/WebMail_Pro_6_PHP_documentation');
		}

		if (isset($_GET['logout']))
		{
			CSession::ClearAll();
			CApi::Location(AP_INDEX_FILE.'?login');
		}

		if (isset($_GET['tab']) && strlen($_GET['tab']) > 0)
		{
			CSession::Set(AP_SESS_TAB, $_GET['tab']);
		}
		else
		{
			CSession::Set(AP_SESS_TAB, CSession::Get(AP_SESS_TAB, AP_TAB_DEFAULT));
		}

		$this->sTab = CSession::Get(AP_SESS_TAB, AP_TAB_DEFAULT);

		try
		{
			$this->CssAddFile('static/styles/style.css');
			$this->JsAddFile('static/js/common.js');
			$this->JsAddFile('static/js/jquery.js');

			if (!CApi::IsValid())
			{
				return false;
			}

			$this->initModules();
			$this->initType();
			$this->initAuth();

			$bResetToDefault = true;
			foreach ($this->aTabs as $aTab)
			{
				if (isset($aTab[1]) && (string) $aTab[1] === (string) $this->sTab)
				{
					$bResetToDefault = false;
					break;
				}
			}

			if ($bResetToDefault)
			{
				$this->sTab = $this->IsRealmAuthType() ? AP_TAB_REALM_DEFAULT : AP_TAB_DEFAULT;
				CSession::Set(AP_SESS_TAB, $this->sTab);
			}

			if (isset($_GET['submit']) && isset($_POST) && 0 < count($_POST))
			{
				$this->bShowScreen = false;
				$sReturnRef = $this->initPostActionModules($this->sTab);
				CApi::Location(AP_INDEX_FILE.$sReturnRef);
			}
			else if (isset($_GET['pop']))
			{
				$this->bShowScreen = false;
				$this->initPopActionModules($this->sTab);
			}
			else if (isset($_GET['blank']))
			{
				$this->bShowScreen = false;
				$this->initBlankActionModules($this->sTab);
			}
			else if (isset($_GET['ajax']))
			{
				$this->bShowScreen = false;
				$this->initAjaxActionModules($this->sTab);
			}
			else
			{
				$this->oCurrentScreen = $this->initScreen($this->sTab);
				if ($this->oCurrentScreen)
				{
					$this->oCurrentScreen->PreModuleInit();
					$this->initCurrentScreenByModules('first', $this->sTab, $this->oCurrentScreen);
					$this->oCurrentScreen->MiddleModuleInit();
					$this->initCurrentScreenByModules('second', $this->sTab, $this->oCurrentScreen);
					$this->oCurrentScreen->EndModuleInit();
					$this->initCurrentScreenByModules('third', $this->sTab, $this->oCurrentScreen);
				}

				if (CSession::Has(AP_SESS_ERROR))
				{
					$this->JsAddInitText('OnlineMsgError("'.
						ap_Utils::ReBuildStringToJavaScript(
							nl2br(CSession::Get(AP_SESS_ERROR, '')), '"').'");');

					CSession::Clear(AP_SESS_ERROR);
				}
				else if (CSession::Has(AP_SESS_MESSAGE))
				{
					$this->JsAddInitText('OnlineMsgInfo("'.
						ap_Utils::ReBuildStringToJavaScript(
							nl2br(CSession::Get(AP_SESS_MESSAGE, '')), '"').'");');

					CSession::Clear(AP_SESS_MESSAGE);
				}
			}
		}
		catch (Exception $oExeption)
		{
			$this->oCurrentScreen = new ap_Simple_Screen($this, 'error.php', array(
				'ErrorDesc' => 'Admin Panel internal error.'
			));
		}
	}

	/**
	 * @return void
	 */
	protected function initAuth()
	{
		$this->iAuthType = AP_SESS_AUTH_TYPE_NONE;
		if ((isset($_GET['login']) || isset($_POST['login'])) &&
			(CPost::Has('AdmloginInput') || CGet::Has('AdmloginInput')) &&CPost::Has('AdmpasswordInput'))
		{
			$sAdmloginInput = CPost::Get('AdmloginInput');
			if (CGet::Has('AdmloginInput'))
			{
				$sAdmloginInput = CGet::Get('AdmloginInput');
			}

			if ($this->CallModuleFunction('CCommonModule', 'AuthLogin',
				array($sAdmloginInput, CPost::Get('AdmpasswordInput'))))
			{
				CApi::Location(AP_INDEX_FILE.'?enter');
			}
			else
			{
				CSession::Destroy();
				CApi::Location(AP_INDEX_FILE.'?auth_error');
			}

			exit();
		}
		else
		{
			if ($this->bSessionIsStarted)
			{
				$this->CallModuleFunction('CCommonModule', 'AuthCheckSet');
				if ($this->IsRealmAuthType())
				{
					$aTabs =& $this->GetTabs();
					$aNewTabs = array();

					foreach ($aTabs as $aTabValue)
					{
						if (in_array($aTabValue[0], array('Domains', 'Users')))
						{
							$aNewTabs[] = $aTabValue;
						}
					}

					$aTabs = $aNewTabs;
				}
			}
			else
			{
				CSession::Destroy();
				CApi::Location(AP_INDEX_FILE.'?sess_error');
				exit();
			}
		}
	}

	/**
	 * @return void
	 */
	protected function initType()
	{
		$this->CallModuleFunction('CProModule', 'InitAdminPanel', array(&$this));
		$this->CallModuleFunction('CBundleModule', 'InitAdminPanel', array(&$this));
	}

	/**
	 * @return void
	 */
	protected function initModules()
	{
		$aLocalModules = array();
		$this->aModules = array();

		$sModulePath = self::RootPath().'modules/';
		if (is_dir($sModulePath))
		{
			if (false !== ($rDirHandle = opendir($sModulePath)))
			{
				$bIsMailSuite = (bool) CApi::GetConf('mailsuite', false);
				while (false !== ($sFile = readdir($rDirHandle)))
				{
					if ('.' !== $sFile{0} && @file_exists($sModulePath.$sFile.'/index.php'))
					{
						if (!$bIsMailSuite && 'bundle' === $sFile)
						{
							continue;
						}

						$bDisabled = false;
						$iSortIndex = null;
						$sCurrentModule = null;
						include $sModulePath.$sFile.'/index.php';

						if (!$bDisabled && null !== $sCurrentModule && null !== $iSortIndex && class_exists($sCurrentModule))
						{
							while (isset($aLocalModules[$iSortIndex]))
							{
								$iSortIndex++;
							}

							$aLocalModules[$iSortIndex] = new $sCurrentModule($this, $sModulePath.$sFile);
						}
					}
				}

				closedir($rDirHandle);
			}
		}

		ksort($aLocalModules);

		foreach ($aLocalModules as $oModule)
		{
			$this->aModules[get_class($oModule)] = $oModule;
		}
	}

	/**
	 * @param int $iDomainId
	 * @return bool
	 */
	public function HasAccessDomain($iDomainId)
	{
		$iDomainId = '' === $iDomainId ? 0 : (int) $iDomainId;

		$bResult = false;
		if (AP_SESS_AUTH_TYPE_SUPER_ADMIN_ONLYREAD === $this->iAuthType)
		{
			CApi::Plugin()->RunHook('adminpanel-demo-domain-access', array($iDomainId, &$bResult));
		}
		else
		{
			$bResult = true;
		}

		return $bResult;
	}

	/**
	 * @param string $sName
	 * @return mixed
	 */
	public function &GetMainObject($sName)
	{
		$oResult = null;
		if ($this->IsMainObjectExist($sName))
		{
			$oResult =& $this->aMainObjectList[$sName];
		}
		else
		{
			CApi::Log('Main object not exist ["'.$sName.'"]');
		}
		return $oResult;
	}

	/**
	 * @param string $sName
	 * @param bool
	 */
	public function IsMainObjectExist($sName)
	{
		return isset($this->aMainObjectList[$sName]);
	}

	/**
	 * @param string $sName
	 * @param mixed $oObject
	 */
	public function SetMainObject($sName, &$oObject)
	{
		CApi::Log('Set main object ["'.$sName.'", '.get_class($oObject).' $oObject]');
		$this->aMainObjectList[$sName] = $oObject;
	}

	/**
	 * @param string $sName
	 */
	public function DeleteMainObject($sName)
	{
		if (isset($this->aMainObjectList[$sName]))
		{
			CApi::Log('Delete main object ["'.$sName.'"]');
			unset($this->aMainObjectList[$sName]);
		}
	}

	/**
	 * @param string $sInitType
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 * @return void
	 */
	protected function initCurrentScreenByModules($sInitType, $sTab, ap_Screen &$oScreen)
	{
		foreach ($this->aModules as /* @var $oModule ap_Module */ $oModule)
		{
			if ($oModule->IsInTab($sTab))
			{
				$oModule->InitScreen($sInitType, $oScreen);
			}
		}
	}

	/**
	 * @param string $sTab
	 * @return string
	 */
	protected function initPostActionModules($sTab)
	{
		$sResult = '?root';
		if (!$this->IsOnlyReadAuthType())
		{
			foreach ($this->aModules as /* @var $oModule ap_Module */ $oModule)
			{
				if ($oModule->IsInTab($sTab))
				{
					$sModuleResult = $oModule->InitPostAction($sTab);
					if (false !== $sModuleResult)
					{
						$sResult = $sModuleResult;
					}
				}
			}
		}
		else
		{
			$this->ShowError(AP_LANG_ADMIN_ONLY_READ);
		}

		return $sResult;
	}

	/**
	 * @param string $sTab
	 * @return void
	 */
	protected function initPopActionModules($sTab)
	{
		if ($this->IsAuth())
		{
			if (!$this->IsOnlyReadAuthType())
			{
				foreach ($this->aModules as /* @var $oModule ap_Module */ $oModule)
				{
					if ($oModule->IsInTab($sTab))
					{
						$oModule->InitPopAction($sTab);
					}
				}
			}
			else
			{
				echo AP_LANG_ADMIN_ONLY_READ;
			}
		}
		else
		{
			echo AP_LANG_LOGIN_ACCESS_ERROR;
		}
	}

	/**
	 * @param string $sTab
	 * @return void
	 */
	protected function initBlankActionModules($sTab)
	{
		if ($this->IsAuth())
		{
			if (!$this->IsOnlyReadAuthType())
			{
				foreach ($this->aModules as /* @var $oModule ap_Module */ $oModule)
				{
					if ($oModule->IsInTab($sTab))
					{
						$oModule->initBlankAction($sTab);
					}
				}
			}
			else
			{
				echo AP_LANG_ADMIN_ONLY_READ;
			}
		}
		else
		{
			echo AP_LANG_LOGIN_ACCESS_ERROR;
		}
	}

	/**
	 * @param string $sTab
	 * @return void
	 */
	protected function initAjaxActionModules($sTab)
	{
		$sError = '';
		$sMessage = '';
		$sRef = '';
		if ($this->IsAuth())
		{
			if (!$this->IsOnlyReadAuthType())
			{
				try
				{
					foreach ($this->aModules as /* @var $oModule ap_Module */ $oModule)
					{
						if ($oModule->IsInTab($sTab))
						{
							$oModule->InitAjaxAction('Pre', $sTab, $sMessage, $sError, $sRef);
						}
					}
					if (empty($sError))
					{
						foreach ($this->aModules as /* @var $oModule ap_Module */ $oModule)
						{
							if ($oModule->IsInTab($sTab))
							{
								$oModule->InitAjaxAction('', $sTab, $sMessage, $sError, $sRef);
							}
						}
					}
					if (empty($sError))
					{
						foreach ($this->aModules as /* @var $oModule ap_Module */ $oModule)
						{
							if ($oModule->IsInTab($sTab))
							{
								$oModule->InitAjaxAction('Post', $sTab, $sMessage, $sError, $sRef);
							}
						}
					}
				}
				catch (Exception $oException)
				{
					if (empty($sError))
					{
						$sError = $oException->getMessage();
					}
				}
			}
			else
			{
				$sError = AP_LANG_ADMIN_ONLY_READ;
			}
		}
		else
		{
			$sError = AP_LANG_LOGIN_ACCESS_ERROR;
		}

		$sOb = @ob_get_contents();
		@ob_clean();

		if (0 < strlen($sOb))
		{
			CApi::Log(AP_CRLF.$sOb, ELogLevel::Error);
			if (!empty($sError))
			{
				$sError = 'Json parse error';
			}
		}

		$sAjaxResult = '{ "null": "null"';
		if (!empty($sError))
		{
			$sAjaxResult .= ', "error": "'.ap_Utils::ReBuildStringToJavaScript($sError, '"').'"';
		}
		else if (!empty($sMessage))
		{
			$sAjaxResult .= ', "message": "'.ap_Utils::ReBuildStringToJavaScript($sMessage, '"').'"';
		}
		if (!empty($sRef))
		{
			$sAjaxResult .= ', "ref": "'.ap_Utils::ReBuildStringToJavaScript($sRef, '"').'"';
			if (!empty($sError))
			{
				$this->ShowError($sError);
			}
			else if (!empty($sMessage))
			{
				$this->ShowMessage($sMessage);
			}
		}
		$sAjaxResult .= ' }';

		echo $sAjaxResult;

		CApi::Log('AJAX: '.$sAjaxResult);
	}

	/**
	 * @param string $sModuleName
	 * @param string $sModulFunction
	 * @param array $aArg = array()
	 * @return mixed
	 */
	public function CallModuleFunction($sModuleName, $sModulFunction, $aArg = array())
	{
		$mResult = false;
		if (isset($this->aModules[$sModuleName]) && is_callable(array(&$this->aModules[$sModuleName], $sModulFunction)))
		{
			$mResult = call_user_func_array(array(&$this->aModules[$sModuleName], $sModulFunction), $aArg);
		}
		return $mResult;
	}

	/**
	 * @param string $sModuleName
	 * @return bool
	 */
	public function IsModuleInit($sModuleName)
	{
		return isset($this->aModules[$sModuleName]);
	}

	/**
	 * @return bool
	 */
	protected static function initInclude()
	{
		static $bIsInclude = false;

		$bResult = true;
		if (!$bIsInclude)
		{
			$sRp = self::RootPath();
			include_once $sRp.'/../libraries/afterlogic/api.php';
			CSession::$sSessionName = 'PHPWMADMINSESSID';

			include_once $sRp.'/core/constants.php';
			include_once $sRp.'/core/utils.php';
			include_once $sRp.'/core/modules.php';
			include_once $sRp.'/core/screens.php';

			include_once $sRp.'/core/screens/simple.php';
			include_once $sRp.'/core/screens/standard.php';
			include_once $sRp.'/core/screens/table.php';

			$sApVersion = @file_get_contents(self::RootPath().'VERSION');
			define('AP_VERSION', (false === $sApVersion) ? '0.0.0' : $sApVersion);
			$bIsInclude = true;
			$bResult = true;
		}

		return $bResult;
	}

	/**
	 * @return void
	 */
	protected static function initDataPath()
	{
		$dataPath = 'data';
		if (!defined('AP_DATA_FOLDER') && @file_exists(self::GetWebMailPath().'inc_settings_path.php'))
		{
			include self::GetWebMailPath().'inc_settings_path.php';
		}

		if (!defined('AP_DATA_FOLDER') && null !== $dataPath)
		{
			define('AP_DATA_FOLDER', ap_Utils::GetFullPath($dataPath, self::GetWebMailPath()));
		}
	}

	/**
	 * @param string $sDesc
	 */
	public function ShowMessage($sDesc)
	{
		CSession::Set(AP_SESS_MESSAGE, $sDesc);
	}

	/**
	 * @param string $sDesc
	 */
	public function ShowError($sDesc)
	{
		CSession::Set(AP_SESS_ERROR, $sDesc);
	}

	/**
	 * @return bool
	 */
	public function IsAuth()
	{
		return $this->bIsAuth;
	}

	/**
	 * @param bool $bIsAuth
	 */
	public function SetIsAuth($bIsAuth)
	{
		$this->bIsAuth = $bIsAuth;
	}

	/**
	 * @return int
	 */
	public function AuthType()
	{
		return $this->iAuthType;
	}

	/**
	 * @return int
	 */
	public function RealmId()
	{
		return $this->iRealmId;
	}

	/**
	 * @return bool
	 */
	public function IsSuperAdminAuthType()
	{
		return AP_SESS_AUTH_TYPE_SUPER_ADMIN === $this->iAuthType;
	}

	/**
	 * @return bool
	 */
	public function IsRealmAuthType()
	{
		return AP_SESS_AUTH_TYPE_REALM === $this->iAuthType;
	}

	/**
	 * @return bool
	 */
	public function IsOnlyReadAuthType()
	{
		return AP_SESS_AUTH_TYPE_SUPER_ADMIN_ONLYREAD === $this->iAuthType;
	}

	/**
	 * @param int $iAuthType
	 */
	public function SetAuthType($iAuthType)
	{
		$this->iAuthType = $iAuthType;
	}

	/**
	 * @param array $aDomainsIds
	 */
	public function SetAuthDomains($aDomainsIds)
	{
		$this->aAuthDomains = $aDomainsIds;
	}

	/**
	 * @param int $iRealmId
	 */
	public function SetAuthRealmId($iRealmId)
	{
		$this->iRealmId = $iRealmId;
	}

	/**
	 * @return string | false
	 */
	public static function AdminDataFolder()
	{
		return defined('AP_DATA_FOLDER') ? AP_DATA_FOLDER : false;
	}

	/**
	 * @return string
	 */
	public static function ClearAdminVersion()
	{
		return preg_replace('/[^\d]/', '', AP_VERSION);
	}

	/**
	 * @return string
	 */
	public static function GetWebMailPath()
	{
		return self::RootPath().'/../';
	}

	/**
	 * @return string
	 */
	public static function RootPath()
	{
		defined('AP_ROOTPATH') || define('AP_ROOTPATH', rtrim(dirname(__FILE__).'/../', '/\\').'/');
		return AP_ROOTPATH;
	}

	/**
	 * @return bool
	 */
	public function PType()
	{
		return $this->PType;
	}

	/**
	 * @return bool
	 */
	public function XType()
	{
		return $this->XType;
	}

	/**
	 * @return bool
	 */
	public function RType()
	{
		return $this->RType;
	}
}