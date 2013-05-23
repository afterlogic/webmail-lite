<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

class ap_Table_Screen extends ap_Screen
{
	const SESS_SEARCH = 'search';
	const SESS_PAGE = 'page';

	const SESS_ORDERBY = 'orderby';
	const SESS_ORDERTYPE = 'ordertype';

	/**
	 * @var int
	 */
	protected $iLinesPerPage;

	/**
	 * @var int
	 */
	protected $iPage;

	/**
	 * @var int
	 */
	protected $iAllListCount;

	/**
	 * @var bool
	 */
	protected $bUseSort;

	/**
	 * @var array
	 */
	protected $aTopMenu;

	/**
	 * @var string
	 */
	protected $sLowToolBarText;

	/**
	 * @var array
	 */
	protected $aHeaders;

	/**
	 * @var array
	 */
	protected $aListItems;

	/**
	 * @var string
	 */
	protected $sOrderField;

	/**
	 * @var bool
	 */
	protected $bOrderType;

	/**
	 * @var string
	 */
	protected $sEmptyListDesc;

	/**
	 * @var string
	 */
	protected $sEmptySearchDesc;

	/**
	 * @var string
	 */
	protected $bSearchEnabled;

	/**
	 * @var string
	 */
	protected $sSearchDesc;

	/**
	 * @var ap_Table_Screen_Main
	 */
	public $Main;

	/**
	 * @var ap_Table_Screen_ListFilter
	 */
	public $Filter;

	/**
	 * @param CAdminPanel $oAdminPanel
	 * @return ap_Table_Screen
	 */
	public function __construct(CAdminPanel &$oAdminPanel)
	{
		parent::__construct($oAdminPanel, CAdminPanel::RootPath().'core/templates/table.php');
		$this->CssAddFile('static/styles/screens/table.css');
		$this->JsAddFile('static/js/screens/table.js');

		$this->aTopMenu = array();
		$this->sLowToolBarText = '';

		$this->aHeaders = array();
		$this->aListItems = array();
		$this->aListItemsWithoutCheckbox = array();

		$this->iLinesPerPage = AP_LINES_PER_PAGE;
		$this->iPage = 1;
		$this->iAllListCount = 0;
		$this->sOrderField = '';
		$this->sDefaultOrderField = '';
		$this->bOrderType = true;
		$this->bUseSort = false;

		$this->bSearchEnabled = true ;
		$this->sSearchDesc = '';
		$this->sEmptyListDesc = 'Empty';
		$this->sEmptySearchDesc = 'Not found';

		$this->Main = new ap_Table_Screen_Main($this);
		$this->Filter = null;
	}

	/**
	 * @return void
	 */
	public function PreModuleInit()
	{
		parent::PreModuleInit();

		$this->AddHeader('Null', 100);

		$sScreenName = $this->GetScreenName();
		if (isset($_GET['search']) && Cpost::Has('searchdesc'))
		{
			$sSearchDesc = Cpost::Get('searchdesc', '');
			if (empty($sSearchDesc))
			{
				CSession::Clear($sScreenName.self::SESS_SEARCH);
			}
			else
			{
				CSession::Set($sScreenName.self::SESS_SEARCH, $sSearchDesc);
			}

			CSession::Set($sScreenName.self::SESS_PAGE, 1);
		}
		else if (isset($_GET['reset_search']))
		{
			CSession::Clear($sScreenName.self::SESS_SEARCH);
			CSession::Set($sScreenName.self::SESS_PAGE, 1);
		}

		if (isset($_GET['page']) && is_numeric($_GET['page']))
		{
			CSession::Set($sScreenName.self::SESS_PAGE, (int) $_GET['page']);
		}

		if (CSession::Has($sScreenName.self::SESS_PAGE))
		{
			$this->iPage = (int) CSession::Get($sScreenName.self::SESS_PAGE, 1);
		}

		if (CSession::Has($sScreenName.self::SESS_SEARCH))
		{
			$this->sSearchDesc = CSession::Get($sScreenName.self::SESS_SEARCH, '');
		}

		if (isset($_GET['page']) && is_numeric($_GET['page']))
		{
			CSession::Set($sScreenName.self::SESS_PAGE, (int) $_GET['page']);
		}

		if (isset($_GET['scolumn']) && 0 < strlen($_GET['scolumn']))
		{
			CSession::Set($sScreenName.self::SESS_ORDERBY, $_GET['scolumn']);
		}
		if (isset($_GET['sorder']) && is_numeric($_GET['sorder']))
		{
			CSession::Set($sScreenName.self::SESS_ORDERTYPE, (int) $_GET['sorder']);
		}

		if (CSession::Has($sScreenName.self::SESS_ORDERBY))
		{
			$this->sOrderField = CSession::Get($sScreenName.self::SESS_ORDERBY, '');
		}
		if (CSession::Has($sScreenName.self::SESS_ORDERTYPE))
		{
			$this->bOrderType = CSession::Get($sScreenName.self::SESS_ORDERTYPE, 0);
		}
	}

	/**
	 * @return void
	 */
	public function EndModuleInit()
	{
		parent::EndModuleInit();

		$this->JsAddInitText('
PageSwitcher = new CPageSwitcher();
PageSwitcher.Build();
PageSwitcher.Show('.$this->iPage.', '.$this->iLinesPerPage.', '.$this->iAllListCount.', "PageSwitcherPager(", ");");

List = new CList();
InitList("list");

ResizeElements("all");
$(window).resize(function(){ ResizeElements("all"); });
');

		if (isset($_GET['edit'], $_GET['uid']) && is_numeric($_GET['uid']))
		{
			$iId = (int) $_GET['uid'];
			$this->oAdminPanel->JsAddInitText('Selection.CheckLine("uid'.$iId.'");');
		}
	}

	/**
	 * @param int $iCount
	 * @return void
	 */
	public function SetAllListCount($iCount)
	{
		$this->iAllListCount = (int) $iCount;
	}

	/**
	 * @param string $sText
	 * @return void
	 */
	public function SetLowToolBar($sText)
	{
		$this->sLowToolBarText = $sText;
	}

	/**
	 * @param string $sText
	 * @return void
	 */
	public function SetEmptySearch($sText)
	{
		$this->sEmptySearchDesc = $sText;
	}

	/**
	 * @param string $sText
	 * @return void
	 */
	public function SetEmptyList($sText)
	{
		$this->sEmptyListDesc = $sText;
	}

	/**
	 * @return void
	 */
	public function ClearHeaders()
	{
		$this->aHeaders = array();
	}

	/**
	 * @param string $sName
	 * @param int $iSize
	 * @param bool $bIsOrderedField = false
	 * @return void
	 */
	public function AddHeader($sName, $iSize, $bIsOrderedField = false)
	{
		$this->aHeaders[$sName] = $iSize;
		if ($bIsOrderedField)
		{
			$this->sDefaultOrderField = $sName;
		}
	}

	/**
	 * @param string $sHref
	 * @param array $aValues
	 * @param bool $bWithoutCheckbox = false
	 * @return void
	 */
	public function AddListItem($sHref, $aValues, $bWithoutCheckbox = false)
	{
		$this->aListItems[$sHref] = $aValues;
		if ($bWithoutCheckbox)
		{
			$this->aListItemsWithoutCheckbox[$sHref] = true;
		}
	}

	/**
	 * @param string $sName
	 * @param string $sImage
	 * @param string $sClickId
	 * @param string $sTitle = null
	 * @return void
	 */
	public function AddTopMenuButton($sName, $sImage, $sClickId, $sTitle = null, $sAddAfter = null)
	{
		if (null !== $sAddAfter && isset($this->aTopMenu[$sAddAfter]))
		{
			$iIndex = 1;
			$aTopMenuKeys = array_keys($this->aTopMenu);
			foreach ($aTopMenuKeys as $sMenuMode)
			{
				if ($sAddAfter === $sMenuMode)
				{
					break;
				}
				$iIndex++;
			}

			$this->aTopMenu = array_slice(
				$this->aTopMenu, 0, $iIndex, true) +
				array($sClickId => new ap_Table_Screen_TopMenuItem($sName, $sImage, $sClickId, $sTitle)) +
				array_slice($this->aTopMenu, $iIndex, NULL, true);
		}
		else
		{
			$this->aTopMenu[$sClickId] = new ap_Table_Screen_TopMenuItem($sName, $sImage, $sClickId, $sTitle);
		}
	}

	/**
	 * @param string $sClickId
	 * @return void
	 */
	public function DeleteTopMenuButton($sClickId)
	{
		if (isset($this->aTopMenu[$sClickId]))
		{
			unset($this->aTopMenu[$sClickId]);
		}
	}

	public function WriteTopMenu()
	{
		echo '<div class="wm_toolbar" id="toolbar">';
		if (0 < count($this->aTopMenu))
		{
			foreach ($this->aTopMenu as /* @var $oMenuItem ap_Table_Screen_TopMenuItem */ $oMenuItem)
			{
				if ($oMenuItem instanceof ap_Table_Screen_TopMenuItem)
				{
					echo $oMenuItem->ToString();
				}
			}

			echo '<span class="wm_last_toolbar_item"></span>';
		}

		echo '</div>';
	}

	/**
	 * @return mixed
	 */
	public function GetFilterIndex()
	{
		$mResult = -1;
		if ($this->Filter)
		{
			$mResult = $this->Filter->GetSelectedItemKey();
		}
		return $mResult;
	}

	/**
	 * @param mixed $mFilterIndex
	 * @return mixed
	 */
	public function GetFilterItem($mFilterIndex)
	{
		$mResult = null;
		if ($this->Filter)
		{
			$mResult = $this->Filter->GetFilterItem($mFilterIndex);
		}
		return $mResult;
	}

	/**
	 * @return void
	 */
	public function InitFilter($sName)
	{
		if (!$this->Filter)
		{
			$this->Filter = new ap_Table_Screen_ListFilter($this->Tab(), $sName, $this);
		}
	}

	/**
	 * @return void
	 */
	public function AddFilter($mIndex, $sName, $sClass)
	{
		if ($this->Filter)
		{
			$this->Filter->Add($mIndex, $sName, $sClass);
		}
	}

	/**
	 * @return void
	 */
	public function WriteFilter()
	{
		if ($this->Filter)
		{
			$this->Filter->Write();
		}
	}

	/**
	* @return void
	*/
	public function WriteSearch()
	{
		if ($this->bSearchEnabled)
		{
			echo '<div id="list_top_search" class="wm_contact_list_div_top">
					<form autocomplete="off" id="searchform" action="'; echo AP_INDEX_FILE; echo '?search" method="POST">' ;
						$this->WriteFilter() ;
						echo '<div class="wm_toolbar_search_item" id="search_control">
							<span>Search:</span>
							<input type="text" id="searchdesc" name="searchdesc" class="wm_search_input" value="'; echo $this->GetSearchDesc(); echo '" /><span onclick="document.getElementById(\'searchform\').submit();" class="wm_search_icon_standard" style="background-position: -560px 0px;">&nbsp;</span>
						</div>
					</form>
					<div class="clear"></div>
					<div id="search_desc" style="border-right: 0px none;">'; echo $this->GetSearchFullDesc(); echo '</div>
				</div>
				';
		}
	}

	public function EnableSearch( $bEnabled ) {
		$this->bSearchEnabled = $bEnabled ;
	}


	/**
	 * @return int
	 */
	public function GetLinesPerPage()
	{
		return $this->iLinesPerPage;
	}

	/**
	 * @return int
	 */
	public function GetPage()
	{
		return $this->iPage;
	}

	/**
	 * @return string
	 */
	public function GetOrderBy()
	{
		$this->sOrderField = (empty($this->sOrderField)) ? $this->sDefaultOrderField : $this->sOrderField;
		return $this->sOrderField;
	}
	/**
	 * @return bool
	 */
	public function GetOrderType()
	{
		return $this->bOrderType;
	}

	/**
	 * @return string
	 */
	public function GetSearchDesc()
	{
		return $this->sSearchDesc;
	}

	public function GetSearchFullDesc()
	{
		return (!empty($this->sSearchDesc)) ? '<br />Search results for: "<b>'.$this->sSearchDesc.'</b>"
<br /><a href="'.AP_INDEX_FILE.'?reset_search">Reset search</a>' : '';
	}

	protected function getOrderTypeImg($bOrderType)
	{
		return ($bOrderType) ? 'order_arrow_down.gif' : 'order_arrow_up.gif';
	}

	public function WriteList()
	{
		if (0 < count($this->aListItems))
		{
			$this->bUseSort = true;
		}

		$sResult = '';
		$iHeadersCount = count($this->aHeaders);
		if (0 < $iHeadersCount)
		{
			$sResult .= '
<tr id="contact_list_headers" class="wm_inbox_headers">
	<td style="text-align: center; padding-top: 0pt; padding-left: 2px; padding-right: 2px; width: 22px">
		<input type="checkbox" id="tableAllCheck" class="wm_checkbox" />
	</td>
';
			$iHeadersCountCacl = $iHeadersCount;
			foreach ($this->aHeaders as $sName => $sSize)
			{
				$iHeadersCountCacl--;

				$sSizeStyle = ($iHeadersCountCacl == 0) ? '' : 'style="width: '.$sSize.'px"';
				$iCurrentOrderType = 0;
				if ($this->GetOrderBy() === $sName)
				{
					$iCurrentOrderType = (int) (!$this->bOrderType);
				}

				$sClass = 'wm_inbox_headers_from_subject';
				$sClass .= ($this->bUseSort) ? ' wm_control' : '';
				$sOnClick = ($this->bUseSort) ? 'onclick="document.location=\''.AP_INDEX_FILE.'?scolumn='.urlencode(ap_Utils::AttributeQuote($sName)).'&sorder='.urlencode($iCurrentOrderType).'\'"' : '';

				$sOrderTypeImg = ($this->bUseSort)
					? ($this->GetOrderBy() === $sName)
						? '<img src="static/images/menu/'.$this->getOrderTypeImg($this->bOrderType).'">'
						: ''
					: '';

				$sResult .= '
<td class="wm_inbox_headers_separate_noresize" style="width: 1px"></td>
<td id="'.$sName.'" class="'.$sClass.'" '.$sSizeStyle.' '.$sOnClick.'>
	<nobr>'.$sName.$sOrderTypeImg.'</nobr>
</td>
';
			}

			$iListCount = count($this->aListItems);
			if (0 < $iListCount && $this->GetLinesPerPage() >= $iListCount)
			{
				foreach ($this->aListItems as $sHref => $aValues)
				{
					$sInput = (isset($this->aListItemsWithoutCheckbox[$sHref])) ? ''
						: '<input name="chCollection[]" type="checkbox" value="'.ap_Utils::AttributeQuote($sHref).'" class="wm_checkbox" />';

					$sResult .= '
<tr id="uid'.ap_Utils::AttributeQuote(urlencode($sHref)).'" class="wm_inbox_read_item">
	<td class="wm_inbox_none">'.$sInput.'</td>';
					$sHeadersNames = array_keys($this->aHeaders);
					foreach ($sHeadersNames as $sName)
					{
						if (isset($aValues[$sName]))
						{
							$sLineValue = ('0' === (string) $sHref)
								? $aValues[$sName] : $this->highlightSearchValue($aValues[$sName]);
							$sResult .= '<td></td><td class="wm_inbox_from_subject" style="overflow:hidden;">'.$sLineValue.'</td>';
						}
						else
						{
							$sResult .= '<td></td><td class="wm_inbox_from_subject" style="padding-left: 4px;"></td>';
						}
					}
					$sResult .= '</tr>';
				}
			}
			else
			{
				$sResult .= '
<tr>
	<td colspan="'.(($iHeadersCount * 2) + 1).'">
		<div class="wm_inbox_info_message">'.(
			(empty($this->sSearchDesc))
				? $this->sEmptyListDesc
				: $this->sEmptySearchDesc
		).'</div>
	</td>
</tr>';
			}
		}

		echo $sResult;
	}

	/**
	 * @param string $sValue
	 * @return string
	 */
	protected function highlightSearchValue($sValue)
	{
		if (preg_match('/<img[^>]+>/im', $sValue))
		{
			return $sValue;
		}

		return str_replace($this->sSearchDesc, '<b>'.$this->sSearchDesc.'</b>', $sValue);
	}

	/**
	 * @return void
	 */
	public function WriteCard()
	{
		$this->Main->ToString();
	}

	/**
	 * @return void
	 */
	public function WriteLowToolBar()
	{
		echo $this->sLowToolBarText;
	}

	/**
	 * @return void
	 */
	public function ClearSwitchers()
	{
		if ($this->Main)
		{
			$this->Main->ClearSwitchers();
		}
	}
}

class ap_Table_Screen_TopMenuItem
{
	/**
	 * @var string
	 */
	protected $sName;

	/**
	 * @var string
	 */
	protected $sTitle;

	/**
	 * @var string
	 */
	protected $sImage;

	/**
	 * @var string
	 */
	protected $sClickId;

	/**
	 * @param string $sName
	 * @param string $sImage
	 * @param string $sClickId
	 * @param string $sTitle = null
	 * @return ap_Table_Screen_TopMenuItem
	 */
	public function __construct($sName, $sImage, $sClickId, $sTitle = null)
	{
		$this->sName = $sName;
		$this->sImage = $sImage;
		$this->sClickId = $sClickId;
		$this->sTitle = ($sTitle === null) ? $sName : $sTitle;
	}

	/**
	 * @return string
	 */
	function ToString()
	{
		return '<span id="'.ap_Utils::AttributeQuote($this->sClickId).'" class="wm_toolbar_item" onmouseover="this.className=\'wm_toolbar_item_over\'" onmouseout="this.className=\'wm_toolbar_item\'">
	<img title="'.ap_Utils::AttributeQuote($this->sTitle).'" src="static/images/menu/'.ap_Utils::AttributeQuote($this->sImage).'" />
	<span>'.$this->sName.'</span>
</span>';
	}
}

class ap_Table_Screen_Main
{
	/**
	 * @var array
	 */
	protected $aSwitchers;

	/**
	 * @var array
	 */
	protected $aTopSwitchers;

	/**
	 * @var	ap_Screen_Data
	 */
	public $Data;

	/**
	 * @return ap_Table_Screen_Main
	 */
	public function __construct(ap_Table_Screen &$oScreen)
	{
		$this->oTableScreen =& $oScreen;
		$this->aSwitchers = array();
		$this->aTopSwitchers = array();
		$this->Data =& $oScreen->Data;
	}

	/**
	 * @param string $sMode
	 * @param string $sModeName
	 * @param string $sModeTeplatePath
	 */
	public function AddSwitcher($sMode, $sModeName, $sModeTeplatePath)
	{
		if (!isset($this->aSwitchers[$sMode]))
		{
			$this->aSwitchers[$sMode] = array();
		}

		$this->aSwitchers[$sMode][] = new ap_Table_Screen_MainSwitcher($sMode, $sModeName, $sModeTeplatePath);
	}

	/**
	 * @param string $sModeTeplatePath
	 */
	public function AddTopSwitcher($sModeTeplatePath)
	{
		$this->aTopSwitchers[] = new ap_Table_Screen_MainTopSwitcher($sModeTeplatePath);
	}

	/**
	 * @retun void
	 */
	public function ClearSwitchers()
	{
		$this->aSwitchers = array();
		$this->aTopSwitchers = array();
	}

	/**
	 * @retun void
	 */
	public function ToString()
	{
		if (0 < count($this->aSwitchers) || 0 < count($this->aTopSwitchers))
		{
			include CAdminPanel::RootPath().'/core/templates/table-main.php';
		}
	}

	/**
	 * @retun void
	 */
	public function SwitchersToString()
	{
		$sQueryAction = $this->oTableScreen->Data->GetValue('sysQueryAction');
		echo '<form autocomplete="off" action="'.AP_INDEX_FILE.'?ajax" method="POST" id="main_form"><input type="hidden" name="QueryAction" value="'.$sQueryAction.'">';
		$this->oTableScreen->JsAddInitText('$(\'#main_form\').submit(MainAjaxRequest);');

		if (0 < count($this->aTopSwitchers))
		{
			echo '<div id="top_switchers_content_div">';
			foreach ($this->aTopSwitchers as $oTopSwitcher)
			{
				if (@file_exists($oTopSwitcher->Template()))
				{
					include $oTopSwitcher->Template();
				}
			}
			echo '</div>';
		}

		echo '<div id="main_tab_container">';
		if (1 < count($this->aSwitchers))
		{
			echo '<div id="switchers_tab_div" class="wm_settings_accounts_info" style="width: 100%; margin: 15px 0px">';
			echo '<div class="wm_settings_switcher_indent"></div>';
			end($this->aSwitchers);
			while (false !== ($aModeSwithers = current($this->aSwitchers)))
			{
				$oFirstItem = (isset($aModeSwithers[0])) ? $aModeSwithers[0] : null;
				if ($oFirstItem)
				{
					$oFirstItem->WriteTabName(false === prev($this->aSwitchers));
					$this->oTableScreen->JsAddInitText('$(\'#switcher_tab_id_'.$oFirstItem->Mode().'\').click(SwitcherTabHandler);');
				}
			}
			echo '</div>';
		}

		$bIsFirst = true;
		reset($this->aSwitchers);

		echo '<div id="switchers_content_div">';
		foreach ($this->aSwitchers as $sMode => $aModeSwithers)
		{
			$sHideStyle = 'display:none;';
			if ($bIsFirst)
			{
				$sHideStyle = '';
				$bIsFirst = false;
			}

			echo '<div id="content_custom_tab_'.$sMode.'" class="" style="'.$sHideStyle.'">';

			foreach ($aModeSwithers as $oItem)
			{
				if (@file_exists($oItem->Template()))
				{
					include $oItem->Template();
				}
			}

			echo '</div>';
		}

		echo '</div></div>';

		echo '<br /><br /><hr /><div align="right">
<span class="wm_secondary_info" style="float: left;">* required fields</span>
<input type="submit" class="wm_button" style="width: 100px;" value="Save">
</div>';

		echo '</form>';
	}
}

class ap_Table_Screen_MainSwitcher
{
	/**
	 * @var	string
	 */
	protected $sMode;

	/**
	 * @var	string
	 */
	protected $sName;

	/**
	 * @var	string
	 */
	protected $sTemplate;

	/**
	 * @param string $sMode
	 * @param string $sName
	 * @param string $sTemplate
	 * @return ap_Table_Screen_MainSwitcher
	 */
	public function __construct($sMode, $sName, $sTemplate)
	{
		$this->sMode = $sMode;
		$this->sName = $sName;
		$this->sTemplate = $sTemplate;
	}

	/**
	 * @retun string
	 */
	public function Mode()
	{
		return $this->sMode;
	}

	/**
	 * @retun string
	 */
	public function Name()
	{
		return $this->sName;
	}

	/**
	 * @retun string
	 */
	public function WriteTabName($bIsSelected = false)
	{
		$sClass = ($bIsSelected) ? 'wm_settings_switcher_select_item' : 'wm_settings_switcher_item';
		echo '<div id="switcher_tab_id_'.$this->Mode().'" rel="content_custom_tab_'.$this->Mode().'" class="'.$sClass.'">'.$this->Name().'</div>';
	}

	/**
	 * @retun string
	 */
	public function Template()
	{
		return $this->sTemplate;
	}
}

class ap_Table_Screen_MainTopSwitcher
{
	/**
	 * @var	string
	 */
	protected $sTemplate;

	/**
	 * @param string $sTemplate
	 * @return ap_Table_Screen_MainTopSwitcher
	 */
	public function __construct($sTemplate)
	{
		$this->sTemplate = $sTemplate;
	}

	/**
	 * @retun string
	 */
	public function Template()
	{
		return $this->sTemplate;
	}
}

class ap_Table_Screen_ListFilter
{
	const SESS_FILTER = 'filter';

	/**
	 * @var	string
	 */
	protected $sTag;

	/**
	 * @var	string
	 */
	protected $sName;

	/**
	 * @var	array
	 */
	protected $aList;

	/**
	 * @var	string
	 */
	protected $sSelectedItem;

	/**
	 * @var	ap_Screen_Data
	 */
	public $Data;

	/**
	 * @param string $sTab
	 * @param string $sName
	 * @return ap_Table_Screen_Main
	 */
	public function __construct($sTab, $sName, ap_Table_Screen &$oScreen)
	{
		$this->sTag = $sTab;
		$this->sName = $sName;
		$this->aList = array();

		$this->oTableScreen =& $oScreen;
		$this->Data =& $oScreen->Data;

		$sScreenName = $this->oTableScreen->GetScreenName();
		if (isset($_GET['filter']) && 0 < strlen($_GET['filter']))
		{
			CSession::Set($sScreenName.self::SESS_FILTER, $_GET['filter']);
		}

		$this->sSelectedItem = CSession::Get($sScreenName.self::SESS_FILTER, '');
	}

	/**
	 * @return	string
	 */
	public function GetSelectedItemKey()
	{
		$aTemp = array();
		foreach (array_keys($this->aList) as $mIndex)
		{
			if ($mIndex == $this->sSelectedItem)
			{
				return $this->sSelectedItem;
			}
			$aTemp[] = $mIndex;
		}

		$sScreenName = $this->oTableScreen->GetScreenName();
		CSession::Set($sScreenName.self::SESS_FILTER, (0 < count($aTemp)) ? $aTemp[0] : '');
		$this->sSelectedItem = CSession::Get($sScreenName.self::SESS_FILTER, '');

		return $this->sSelectedItem;
	}

	/**
	 * @param mixed $mFilterIndex
	 * @return mixed
	 */
	public function GetFilterItem($mFilterIndex)
	{
		return (isset($this->aList[$mFilterIndex])) ? $this->aList[$mFilterIndex] : null;
	}

	/**
	 * @param mixed $mIndex
	 * @param string $sName
	 * @param string $sClass
	 * @return void
	 */
	public function Add($mIndex, $sName, $sClass)
	{
		$this->aList[$mIndex] = array($sName, $sClass);
	}

	/**
	 * @return	int
	 */
	public function Count()
	{
		return count($this->aList);
	}

	/**
	 * @return void
	 */
	public function Write()
	{
		$sActiv = '';
		$sOptions = '';
		$iC = 0;
		$iLimit = 999;
		$bHide = false;

		foreach ($this->aList as $mIndex => $aValue)
		{
			if (1 < count($aValue))
			{
				$sActiv = ($iC === 0)
					? '<a class="l1" title="'.ap_Utils::AttributeQuote($aValue[0]).'" href="javascript:void(0);"><div class="link '.$aValue[1].'"><div>'.$aValue[0].'</div></div>'
					: $sActiv;

				$addClass = ((empty($this->sSelectedItem) && $iC === 0) || $mIndex == $this->sSelectedItem) ? ' SelectedDomain' : '';
				$iC++;
				$sActiv = ($mIndex == $this->sSelectedItem)
					? '<a class="l1" title="'.ap_Utils::AttributeQuote($aValue[0]).'" href="javascript:void(0);"><div class="link '.$aValue[1].'"><div>'.$aValue[0].'</div></div>'
					: $sActiv;

				if (!$bHide)
				{
					$mHref = AP_INDEX_FILE.'?tab='.ap_Utils::AttributeQuote($this->sTag).
						'&filter='.ap_Utils::AttributeQuote($mIndex);

					$sOptions .= '<a class="l2" title="'.ap_Utils::AttributeQuote($aValue[0]).'" href="'.$mHref.'"><div class="'.$aValue[1].$addClass.'">'.$aValue[0].'</div></a>';
				}

				if ($iLimit === $iC)
				{
					$sOptions .= '<div style="text-align: center; margin: 2px;">...</div>';
					$bHide = true;
				}
			}
		}

		echo (0 === count($this->aList)) ? '' : '
<div style="float: left; height: 16px; padding: 4px;"><span>'.$this->sName.':</span></div>
<div class="menu_select">
'.$sActiv.'<!--[if gte IE 7]><!--></a><!--<![endif]-->
	<div class="dd">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					'.$sOptions.'
				</td>
			</tr>
		</table>
	</div>
	<!--[if lte IE 6]></a><![endif]-->
</div>';

	}
}