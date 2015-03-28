<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

abstract class ap_Module
{
	/**
	 * @var array
	 */
	protected $aTabs;

	/**
	 * @var string
	 */
	protected $sPath;
	
	/**
	 * @var string
	 */
	protected $sWebModulePath;

	/**
	 * @var	CAdminPanel
	 */
	protected $oAdminPanel;

	/**
	 * @var ap_CoreModuleHelper
	 */
	protected $oPopulateData;
	
	/**
	 * @var ap_CoreModuleHelper
	 */
	protected $oStandardPostAction;

	/**
	 * @var ap_CoreModuleHelper
	 */
	protected $oTableAjaxAction;
	
	/**
	 * @var ap_CoreModuleHelper
	 */
	protected $oStandardPopAction;
	
	/**
	 * @var ap_CoreModuleHelper
	 */
	protected $oBlankAction;

	/**
	 * @var ap_CoreModuleHelper
	 */
	protected $oTableList;

	/**
	 * @var array
	 */
	protected $aQueryActions;
	
	/**
	 * @var int
	 */
	protected $lastErrorCode;
	
	/**
	 * @var string
	 */
	protected $lastErrorMessage;

	/**
	 * @param CAdminPanel $oAdminPanel
	 * @param string $sPath
	 * @return ap_Module
	 */
	public function __construct(CAdminPanel &$oAdminPanel, $sPath)
	{
		$this->aTabs = array();
		$this->aQueryActions = array();
		
		$this->oAdminPanel =& $oAdminPanel;
		$this->sPath = $sPath;
		$this->sWebModulePath = 'modules/'.basename($sPath);
		
		$this->lastErrorCode = 0;
		$this->lastErrorMessage = '';

		$this->initInclude();

		$this->oPopulateData = null;
		$this->oStandardPostAction = null;
		$this->oStandardPopAction = null;
		$this->oTableAjaxAction = null;

		$this->oTableList = null;
	}
	
	/**
	 * @return int
	 */
	public function GetLastErrorCode()
	{
		return $this->lastErrorCode;
	}
	
	/**
	 * @return string
	 */
	public function GetLastErrorMessage()
	{
		return $this->lastErrorMessage;
	}

	/**
	 * @param string $sTab
	 * @return bool
	 */
	public function IsInTab($sTab)
	{
		return in_array($sTab, $this->aTabs);
	}

	/**
	 * @param string $sTab
	 * @return bool
	 */
	public function PType()
	{
		return $this->oAdminPanel->PType();
	}

	/**
	 * @param string $sInitType
	 * @param ap_Screen $oScreen
	 * @return bool
	 */
	public function InitScreen($sInitType, ap_Screen &$oScreen)
	{
		$sTab = $this->oAdminPanel->Tab();
		if ($oScreen instanceof ap_Standard_Screen)
		{
			if ('first' === $sInitType)
			{
				$this->initStandardMenuByTab($sTab, $oScreen);
			}
			else if ('second' === $sInitType)
			{
				$this->initStandardMainByTab($sTab, $oScreen);
			}
			else if ('third' === $sInitType)
			{
				#
			}
		}
		else if ($oScreen instanceof ap_Table_Screen)
		{
			if ('first' === $sInitType)
			{
				$this->initTableTopMenu($sTab, $oScreen);
				$this->initTableListHeaders($sTab, $oScreen);
				$this->initTableMainSwitchersPre($sTab, $oScreen);
				$this->initTableListFilter($sTab, $oScreen);
			}
			else if ('second' === $sInitType)
			{
				$this->initTableList($sTab, $oScreen);
				$this->initTableMainSwitchers($sTab, $oScreen);
				$this->initTableMainPopulateData($sTab, $oScreen);
			}
			else if ('third' === $sInitType)
			{
				$this->initTableMainSwitchersPost($sTab, $oScreen);
			}
		}
	}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableTopMenu($sTab, ap_Screen &$oScreen) {}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableListHeaders($sTab, ap_Screen &$oScreen) {}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableList($sTab, ap_Screen &$oScreen) {}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableListFilter($sTab, ap_Screen &$oScreen) {}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableMainSwitchers($sTab, ap_Screen &$oScreen) {}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableMainSwitchersPre($sTab, ap_Screen &$oScreen) {}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableMainSwitchersPost($sTab, ap_Screen &$oScreen) {}

	/**
	 * @param string $sTab
	 * @param string $sMode
	 * @param ap_Screen $oScreen
	 */
	protected function initTableMainPopulateData($sTab, ap_Screen &$oScreen)
	{
		if ($this->oPopulateData && $this->IsInTab($sTab))
		{
			$sTabMode = ucfirst($sTab).'Main'.ucfirst($this->getQueryAction());
			
			$oScreen->Data->SetValue('sysTab', $sTab);
			$oScreen->Data->SetValue('sysQueryAction', $this->getQueryAction());
				
			CApi::Log('call '.get_class($this).'->PopulateData->'.$sTabMode.'()');
			if (method_exists($this->oPopulateData, $sTabMode))
			{
				$this->oPopulateData->{$sTabMode}($oScreen);
			}
		}
	}

	/**
	 * @return string
	 */
	protected function getQueryAction()
	{
		$sResult = '';
		foreach ($this->aQueryActions as $sKey)
		{
			if (isset($_GET[$sKey]))
			{
				$sResult = $sKey;
				break;
			}
		}
		return $sResult;
	}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initStandardMenuByTab($sTab, ap_Screen &$oScreen) {}

	/**
	 * @param string $sTab
	 * @param string $sMode
	 * @param ap_Screen $oScreen
	 */
	protected function initStandardMainByTab($sTab, ap_Screen &$oScreen)
	{
		if ($this->oPopulateData && $this->IsInTab($sTab))
		{
			$sMode = $oScreen->GetCurrentMode();
			$sTabMode = ucfirst($sTab).ucfirst($sMode);

			CApi::Log('call '.get_class($this).'->PopulateData->'.$sTabMode.'()');
			if (method_exists($this->oPopulateData, $sTabMode))
			{
				$oScreen->Data->SetValue('sysTab', $sTab);
				$oScreen->Data->SetValue('sysMode', $sMode);

				$this->oPopulateData->{$sTabMode}($oScreen);
			}
		}
	}

	/**
	 * @return string
	 */
	public function InitPostAction($sTab)
	{
		$sResult = false;
		if ($this->oStandardPostAction && $this->IsInTab($sTab))
		{
			$sResult = '?root';
			$sPostId = CPost::Get('form_id', 'null');
			$sPostActionFunction = ucfirst($sTab).ucfirst($sPostId);
			$sPostActionFunction .= ('collection' === $sPostId && null !== CPost::Get('action', null))
				? ucfirst(CPost::Get('action', '')) : '';

			CApi::Log('call '.get_class($this).'->StandardPostAction->'.$sPostActionFunction.'()');
			if (!empty($sPostId) && method_exists($this->oStandardPostAction, $sPostActionFunction))
			{
				$sActionResult = $this->oStandardPostAction->$sPostActionFunction();
				if (!empty($this->oStandardPostAction->LastError))
				{
					$this->oAdminPanel->ShowError($this->oStandardPostAction->LastError);
				}
				else if (!empty($this->oStandardPostAction->LastMessage))
				{
					$this->oAdminPanel->ShowMessage($this->oStandardPostAction->LastMessage);
				}

				if (empty($sActionResult))
				{
					$sResult = $sActionResult;
				}
			}
		}
		return $sResult;
	}

	/**
	 * @return void
	 */
	public function InitPopAction($sTab)
	{
		if ($this->oStandardPostAction && $this->IsInTab($sTab))
		{
			$sPopActionFunction = ucfirst($sTab);
			
			CApi::Log('call '.get_class($this).'->StandardPopAction->'.$sPopActionFunction.'()');
			if (method_exists($this->oStandardPopAction, $sPopActionFunction))
			{
				$this->oStandardPopAction->$sPopActionFunction();
			}
		}
	}
	
	/**
	 * @return void
	 */
	public function initBlankAction($sTab)
	{
		if ($this->oBlankAction && $this->IsInTab($sTab))
		{
			$sType = isset($_GET['type']) ? $_GET['type'] : null;
			$sBlankActionFunction = ucfirst($sTab).ucfirst($sType);

			CApi::Log('call '.get_class($this).'->BlankAction->'.$sBlankActionFunction.'()');
			if (method_exists($this->oBlankAction, $sBlankActionFunction))
			{
				$this->oBlankAction->$sBlankActionFunction();
			}
		}
	}

	/**
	 * @return void
	 */
	public function InitAjaxAction($sPostName, $sTab, &$sMessage, &$sError, &$sRef)
	{
		if ($this->oTableAjaxAction && $this->IsInTab($sTab))
		{
			$sQueryAction = CPost::Get('QueryAction', '');

			$sAjaxActionFunction = ucfirst($sTab).ucfirst($sQueryAction).
				((empty($sPostName)) ? '' : '_'.$sPostName);

			if (method_exists($this->oTableAjaxAction, $sAjaxActionFunction))
			{
				CApi::Log('call '.get_class($this).'->TableAjaxAction->'.$sAjaxActionFunction.'()');
				$this->oTableAjaxAction->$sAjaxActionFunction();

				if (!empty($this->oTableAjaxAction->LastError))
				{
					$sError = $this->oTableAjaxAction->LastError;
				}
				else if (!empty($this->oTableAjaxAction->LastMessage))
				{
					$sMessage = $this->oTableAjaxAction->LastMessage;
				}

				if (!empty($this->oTableAjaxAction->Ref))
				{
					$sRef = $this->oTableAjaxAction->Ref;
				}
				
				if (!empty($sError))
				{
					CApi::Log('function '.$sAjaxActionFunction.'() return $sError = '.$sError, ELogLevel::Error);
				}
			}
		}
	}

	/**
	 * @return void
	 */
	abstract protected function initInclude();

	/**
	 * @param string $sCssFile
	 */
	public function CssAddFile($sCssFile)
	{
		$this->oAdminPanel->CssAddFile($this->WebModulePath().'/js/'.$sCssFile);
	}

	/**
	 * @param string $sJsFile
	 */
	public function JsAddFile($sJsFile)
	{
		$this->oAdminPanel->JsAddFile($this->WebModulePath().'/js/'.$sJsFile);
	}

	/**
	 * @return string
	 */
	public function WebModulePath()
	{
		return $this->sWebModulePath;
	}

	/**
	 * @return CAdminPanel
	 */
	public function &GetAp()
	{
		return $this->oAdminPanel;
	}
}

class ap_CoreModuleHelper
{
	/**
	 * @var CAdminPanel
	 */
	protected $oAdminPanel;

	/**
	 * @var ap_Module
	 */
	protected $oModule;

	/**
	 * @var api_Settings
	 */
	protected $oSettings;

	/**
	 * @var string
	 */
	public $LastError;

	/**
	 * @var string
	 */
	public $LastMessage;

	/**
	 * @var string
	 */
	public $Ref;

	/**
	 * @param ap_Module $oModule
	 */
	public function  __construct(ap_Module &$oModule)
	{
		$this->oModule =& $oModule;
		$this->oAdminPanel =& $oModule->GetAp();

		$this->oSettings =& CApi::GetSettings();

		$this->LastError = '';
		$this->LastMessage = '';
		$this->Ref = '';
	}

	/**
	 * @return bool
	 */
	protected function saveSettingsXmlWithMessage()
	{
		return $this->checkBolleanWithMessage($this->oSettings->SaveToXml());
	}

	/**
	 * @param bool $bValue
	 * @return bool
	 */
	protected function checkBolleanWithMessage($bValue)
	{
		if ($bValue)
		{
			$this->LastMessage = AP_LANG_SAVESUCCESSFUL;
		}
		else
		{
			$this->LastError = AP_LANG_SAVEUNSUCCESSFUL;
		}
		
		return $bValue;
	}

	/**
	 * @param bool $bValue
	 */
	protected function checkBolleanDeleteWithMessage($bValue)
	{
		if ($bValue)
		{
			$this->LastMessage = AP_LANG_DELETE_SUCCESSFUL;
		}
		else
		{
			$this->LastError = AP_LANG_DELETE_UNSUCCESSFUL;
		}
	}

	/**
	 * @return bool
	 */
	protected function isStandartSubmit()
	{
		return isset($_POST['submit_btn']);
	}
}