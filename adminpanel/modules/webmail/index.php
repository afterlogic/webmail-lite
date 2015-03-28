<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

//$bDisabled = true;
$iSortIndex = 20;
$sCurrentModule = 'CWebMailModule';
class CWebMailModule extends ap_Module
{
	/**
	 * @var CApiWebmailManager
	 */
	protected $oWebmailApi;

	/**
	 * @var CApiCapabilityManager
	 */
	protected $oCapabilityApi;

	/**
	 * @var CApiUsersManager
	 */
	protected $oUsersApi;

	/**
	 * @var CApiLoggerManager
	 */
	protected $oLoggerApi;

	/**
	 * @param CAdminPanel $oAdminPanel
	 * @param string $sPath
	 * @return CWebMailModule
	 */
	public function __construct(CAdminPanel &$oAdminPanel, $sPath)
	{
		parent::__construct($oAdminPanel, $sPath);

		$this->aTabs[] = AP_TAB_DOMAINS;
		$this->aTabs[] = AP_TAB_USERS;
		$this->aTabs[] = AP_TAB_SYSTEM;

		$this->oCapabilityApi = CApi::Manager('capability');
		$this->oWebmailApi = CApi::Manager('webmail');
		$this->oUsersApi = CApi::Manager('users');
		$this->oLoggerApi = CApi::Manager('logger');

		$this->aQueryActions[] = 'new';
		$this->aQueryActions[] = 'edit';

		$this->oPopulateData = new CWebMailPopulateData($this);
		$this->oStandardPostAction = new CWebMailPostAction($this);
		$this->oStandardPopAction = new CWebMailPopAction($this);
		$this->oStandardPopAction = new CWebMailPopAction($this);
		$this->oTableAjaxAction = new CWebMailAjaxAction($this);
		$this->oBlankAction = new CWebMailBlankAction($this);
	}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initStandardMenuByTab($sTab, ap_Screen &$oScreen)
	{
		switch ($sTab)
		{
			case AP_TAB_SYSTEM:
				$this->JsAddFile('system.js');
				$oScreen->AddMenuItem(WM_MODE_LOGGING, WM_MODE_LOGGING_NAME, $this->sPath.'/templates/logging.php',
					array()
				);

				$oScreen->SetDefaultMode(WM_MODE_LOGGING);
				break;
		}
	}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableTopMenu($sTab, ap_Screen &$oScreen)
	{
		switch ($sTab)
		{
			case AP_TAB_DOMAINS:
				$this->JsAddFile('domains.js');
				break;
		}
	}	
	
	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableMainSwitchers($sTab, ap_Screen &$oScreen)
	{
		$sMainAction = $this->getQueryAction();
		if (AP_TAB_DOMAINS === $sTab)
		{
			switch ($sMainAction)
			{
				case 'edit':
					/* @var $oDomain CDomain */
					$oDomain =& $this->oAdminPanel->GetMainObject('domain_edit');
					if ($oDomain)
					{
						if (!$oDomain->IsInternal && 0 === $oDomain->IdTenant)
						{
							$oScreen->Main->AddTopSwitcher($this->sPath.'/templates/main-top-edit-domain-webmail-inc-server.php');
							$oScreen->Main->AddTopSwitcher($this->sPath.'/templates/main-top-edit-domain-webmail-out-server.php');
						}

						$oScreen->Main->AddSwitcher(
							WM_SWITCHER_MODE_EDIT_DOMAIN_GENERAL, WM_SWITCHER_MODE_EDIT_DOMAIN_GENERAL_NAME,
							$this->sPath.'/templates/main-edit-domain-general-webmail-top.php');
						$oScreen->Main->AddSwitcher(
							WM_SWITCHER_MODE_EDIT_DOMAIN_WEBMAIL, WM_SWITCHER_MODE_EDIT_DOMAIN_WEBMAIL_NAME,
							$this->sPath.'/templates/main-edit-domain-webmail-top.php');
						$oScreen->Main->AddSwitcher(
							WM_SWITCHER_MODE_EDIT_DOMAIN_WEBMAIL, WM_SWITCHER_MODE_EDIT_DOMAIN_WEBMAIL_NAME,
							$this->sPath.'/templates/main-edit-domain-webmail-end.php');
						$oScreen->Main->AddSwitcher(
							WM_SWITCHER_MODE_EDIT_DOMAIN_ADDRESS_BOOK, WM_SWITCHER_MODE_EDIT_DOMAIN_ADDRESS_BOOK_NAME,
							$this->sPath.'/templates/main-edit-domain-address-book.php');
					}
					break;
			}
		}
	}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableMainSwitchersPost($sTab, ap_Screen &$oScreen)
	{

	}


	/**
	 * @return bool
	 */
	public function HasSslSupport()
	{
		return $this->oCapabilityApi->HasSslSupport();
	}

	/**
	 * @return array
	 */
	public function GetLogsSize()
	{
		$aResult = array(
			$this->oLoggerApi->CurrentLogSize(),
			$this->oLoggerApi->CurrentUserActivityLogSize()
		);

		if (false === $aResult[0])
		{
			$aResult[0] = 0;
		}

		if (false === $aResult[1])
		{
			$aResult[1] = 0;
		}

		return $aResult;
	}

	/**
	 * @return void
	 */
	protected function initInclude()
	{
		include $this->sPath.'/inc/constants.php';
		include $this->sPath.'/inc/populate.php';
		include $this->sPath.'/inc/post.php';
		include $this->sPath.'/inc/pop.php';
		include $this->sPath.'/inc/ajax.php';
		include $this->sPath.'/inc/blank.php';
	}
}
