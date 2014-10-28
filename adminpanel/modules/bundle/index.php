<?php

//$bDisabled = true;
$iSortIndex = 90;
$sCurrentModule = 'CBundleModule';
class CBundleModule extends ap_Module
{
	/**
	 * @var CApiUsersManager
	 */
	protected $oUsersApi;

	/**
	 * @var CApiDomainsManager
	 */
	protected $oDomainsApi;

	/**
	 * @var CApiMailsuiteManager
	 */
	public $oMailsuiteApi;

	/**
	 * @param CAdminPanel $oAdminPanel
	 * @param string $sPath
	 * @return CBundleModule
	 */
	public function __construct(CAdminPanel &$oAdminPanel, $sPath)
	{
		parent::__construct($oAdminPanel, $sPath);

		$this->oUsersApi = CApi::Manager('users');
		$this->oDomainsApi = CApi::Manager('domains');
		$this->oMailsuiteApi = CApi::Manager('mailsuite');

		$this->aTabs[] = AP_TAB_DOMAINS;
		$this->aTabs[] = AP_TAB_USERS;
		$this->aTabs[] = AP_TAB_SYSTEM;

		$this->aQueryActions[] = 'new';
		$this->aQueryActions[] = 'edit';
		$this->aQueryActions[] = 'list';

		$this->oPopulateData = new CBundlePopulateData($this);
		$this->oStandardPostAction = new CBundlePostAction($this);
		$this->oStandardPopAction = new CBundlePopAction($this);
		$this->oTableAjaxAction = new CBundleAjaxAction($this);
	}

	/**
	 * @param CAdminPanel $oAdminPanel
	 */
	public function InitAdminPanel(CAdminPanel &$oAdminPanel)
	{
		$oAdminPanel->XType = true;
	}

	/**
	 * @param CMailingList &$oMailingList
	 * @return bool
	 */
	public function CreateMailingList(CMailingList &$oMailingList)
	{
		$bResult = false;
		if ($this->oMailsuiteApi)
		{
			$bResult = $this->oMailsuiteApi->CreateMailingList($oMailingList);
			if (!$bResult)
			{
				$this->lastErrorCode = $this->oMailsuiteApi->GetLastErrorCode();
				$this->lastErrorMessage = $this->oMailsuiteApi->GetLastErrorMessage();
			}
		}

		return $bResult;
	}

	/**
	 * @param CMailingList &$oMailingList
	 * @return bool
	 */
	public function DeleteMailingList(CMailingList &$oMailingList)
	{
		$bResult = false;
		if ($this->oMailsuiteApi)
		{
			$bResult = $this->oMailsuiteApi->DeleteMailingList($oMailingList);
			if (!$bResult)
			{
				$this->lastErrorCode = $this->oMailsuiteApi->GetLastErrorCode();
				$this->lastErrorMessage = $this->oMailsuiteApi->GetLastErrorMessage();
			}
		}

		return $bResult;
	}

	/**
	 * @param CMailingList &$oMailingList
	 * @return bool
	 */
	public function UpdateMailingList(CMailingList &$oMailingList)
	{
		$bResult = false;
		if ($this->oMailsuiteApi)
		{
			$bResult = $this->oMailsuiteApi->UpdateMailingList($oMailingList);
			if (!$bResult)
			{
				$this->lastErrorCode = $this->oMailsuiteApi->GetLastErrorCode();
				$this->lastErrorMessage = $this->oMailsuiteApi->GetLastErrorMessage();
			}
		}

		return $bResult;
	}

	/**
	 * @param CMailAliases &$oMailAliases
	 * @return bool
	 */
	public function UpdateMailAliases(CMailAliases &$oMailAliases)
	{
		$bResult = false;
		if ($this->oMailsuiteApi)
		{
			$bResult = $this->oMailsuiteApi->UpdateMailAliases($oMailAliases);
			if (!$bResult)
			{
				$this->lastErrorCode = $this->oMailsuiteApi->GetLastErrorCode();
				$this->lastErrorMessage = $this->oMailsuiteApi->GetLastErrorMessage();
			}
		}

		return $bResult;
	}

	/**
	 * @param CMailForwards &$oMailForwards
	 * @return bool
	 */
	public function UpdateMailForwards(CMailForwards &$oMailForwards)
	{
		$bResult = false;
		if ($this->oMailsuiteApi)
		{
			$bResult = $this->oMailsuiteApi->UpdateMailForwards($oMailForwards);
			if (!$bResult)
			{
				$this->lastErrorCode = $this->oMailsuiteApi->GetLastErrorCode();
				$this->lastErrorMessage = $this->oMailsuiteApi->GetLastErrorMessage();
			}
		}

		return $bResult;
	}

	/**
	 * @param int $iMailingListId
	 * @return CMailingList
	 */
	public function GetMailingList($iMailingListId)
	{
		if ($this->oMailsuiteApi && is_numeric($iMailingListId) && 0 < $iMailingListId)
		{
			return $this->oMailsuiteApi->GetMailingListById($iMailingListId);
		}
		return null;
	}

	/**
	 * @param CAccount $oAccount
	 * @return CMailAliases
	 */
	public function GetMailAliases($oAccount)
	{
		if ($oAccount && $this->oMailsuiteApi)
		{
			$oMailAliases = new CMailAliases($oAccount);
			$this->oMailsuiteApi->InitMailAliases($oMailAliases);
			return $oMailAliases;
		}
		return null;
	}

	/**
	 * @param CAccount $oAccount
	 * @return CMailForwards
	 */
	public function GetMailForwards($oAccount)
	{
		if ($oAccount && $this->oMailsuiteApi)
		{
			$oMailForwards = new CMailForwards($oAccount);
			$this->oMailsuiteApi->InitMailForwards($oMailForwards);
			return $oMailForwards;
		}
		return null;
	}

	/**
	 * @param int $iAccountId
	 * @return CAccount
	 */
	public function GetAccount($iAccountId)
	{
		if (is_numeric($iAccountId) && 0 < $iAccountId)
		{
			return $this->oUsersApi->GetAccountById($iAccountId);
		}
		return null;
	}

	/**
	 * @param int $iDomainId
	 * @return CDomain
	 */
	public function GetDomain($iDomainId)
	{
		if (0 < $iDomainId)
		{
			return $this->oDomainsApi->GetDomainById($iDomainId);
		}
		return null;
	}

	/**
	 * @param string $sTab
	 * @param ap_Screen $oScreen
	 */
	protected function initTableTopMenu($sTab, ap_Screen &$oScreen)
	{
		switch ($sTab)
		{
			case AP_TAB_USERS:
				$this->JsAddFile('users.js');

				$oScreen->AddTopMenuButton(CApi::I18N('ADMIN_PANEL/TOOLBAR_BUTTON_NEW_LIST'), 'new_contact.gif', 'IdUsersNewMailingListButton',
						null, 'IdUsersNewUserButton');

				if (in_array((string) $oScreen->GetFilterIndex(), array('', '0')))
				{
					$oScreen->DeleteTopMenuButton('IdUsersNewMailingListButton');
					$oScreen->DeleteTopMenuButton('IdUsersNewUserButton');
					$oScreen->DeleteTopMenuButton('IdUsersEnableUserButton');
					$oScreen->DeleteTopMenuButton('IdUsersDisableUserButton');
					$oScreen->DeleteTopMenuButton('IdUsersDeleteButton');
				}
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
					$oDomain =& /* @var $oDomain CDomain */ $this->oAdminPanel->GetMainObject('domain_edit');
					if ($oDomain && !$oDomain->IsDefaultDomain)
					{
						/*
						$oScreen->Main->AddSwitcher(
							BU_SWITCHER_MODE_EDIT_DOMAIN_GENERAL, BU_SWITCHER_MODE_EDIT_DOMAIN_GENERAL_NAME,
							$this->sPath.'/templates/main-edit-domain-general-sign-up.php');
						 */
					}
					break;
			}
		}
		else if (AP_TAB_USERS === $sTab)
		{
			switch ($sMainAction)
			{
				case 'list':
					$oScreen->Main->AddSwitcher(
						BU_SWITCHER_MODE_NEW_MAIL_LIST, BU_SWITCHER_MODE_NEW_MAIL_LIST_NAME,
						$this->sPath.'/templates/main-new-list.php');
					break;

				case 'edit':
					$oAccount =& $this->oAdminPanel->GetMainObject('account_edit');
					$iUid = isset($_GET['uid']) ? (int) $_GET['uid'] : null;

					if ($oAccount)
					{
						$this->oAdminPanel->SetMainObject('account_edit', $oAccount);

						$oScreen->Main->AddSwitcher(
							BU_SWITCHER_MODE_EDIT_USER_GENERAL, BU_SWITCHER_MODE_EDIT_USER_GENERAL_NAME,
								$this->sPath.'/templates/main-edit-user-general.php');

						if ($oAccount->Domain->IsInternal)
						{
							$oMailAliases =& $this->oAdminPanel->GetMainObject('aliases_edit');
							if (!$oMailAliases)
							{
								$oMailAliases = $this->GetMailAliases($oAccount);
								if ($oMailAliases)
								{
									$this->oAdminPanel->SetMainObject('aliases_edit', $oMailAliases);
								}
							}
							$oMailForwards =& $this->oAdminPanel->GetMainObject('forwards_edit');
							if (!$oMailForwards)
							{
								$oMailForwards = $this->GetMailForwards($oAccount);
								if ($oMailForwards)
								{
									$this->oAdminPanel->SetMainObject('forwards_edit', $oMailForwards);
								}
							}

							$oScreen->Main->AddSwitcher(
								BU_SWITCHER_MODE_EDIT_USER_ALIASES, BU_SWITCHER_MODE_EDIT_USER_ALIASES_NAME,
									$this->sPath.'/templates/main-edit-user-aliases.php');

//							$oScreen->Main->AddSwitcher(
//								BU_SWITCHER_MODE_EDIT_USER_FORWARDS, BU_SWITCHER_MODE_EDIT_USER_FORWARDS_NAME,
//									$this->sPath.'/templates/main-edit-user-forwards.php');
						}
					}

					if (!$oAccount)
					{
						$oMailingList =& $this->oAdminPanel->GetMainObject('mailinglist_edit');

						if (!$oMailingList && null !== $iUid && 0 < $iUid)
						{
							$oMailingList = $this->GetMailingList($iUid);
							if ($oMailingList)
							{
								$this->oAdminPanel->SetMainObject('mailinglist_edit', $oMailingList);

								$oScreen->Main->AddSwitcher(
									BU_SWITCHER_MODE_EDIT_LIST_GENERAL, BU_SWITCHER_MODE_EDIT_LIST_GENERAL_NAME,
									$this->sPath.'/templates/main-edit-list-general.php');
							}
						}
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
	 * @return void
	 */
	protected function initInclude()
	{
		include $this->sPath.'/inc/constants.php';
		include $this->sPath.'/inc/populate.php';
		include $this->sPath.'/inc/post.php';
		include $this->sPath.'/inc/pop.php';
		include $this->sPath.'/inc/ajax.php';
	}
}
