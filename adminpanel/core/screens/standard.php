<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

class ap_Standard_Screen extends ap_Screen
{
	const SESS_MODE = 'mode';

	/**
	 * @var array
	 */
	protected $aMenu;

	/**
	 * @var string
	 */
	protected $sMode;

	/**
	 * @var string
	 */
	protected $aMenuDefMode;

	/**
	 * @param CAdminPanel $oAdminPanel
	 * @return ap_Standard_Screen
	 */
	public function __construct(CAdminPanel &$oAdminPanel)
	{
		parent::__construct($oAdminPanel, CAdminPanel::RootPath().'core/templates/standard.php');
		$this->CssAddFile('static/styles/screens/standard.css');

		$this->aMenu = array();
		$this->sMode = '';
		$this->aMenuDefMode = '';
	}

	/**
	 * @return void
	 */
	public function MiddleModuleInit()
	{
		$sScreenName = $this->GetScreenName();
		if (isset($_GET['mode']) && isset($this->aMenu[$_GET['mode']]))
		{
			CSession::Set($sScreenName.self::SESS_MODE, $_GET['mode']);
		}

		$this->sMode = CSession::Get($sScreenName.self::SESS_MODE, $this->aMenuDefMode);
	}

	/**
	 * @param string $sMode
	 * @param string $sModeName
	 * @param string $sModeTeplatePath
	 * @param array $aToolTips = array()
	 * @param string $sAddAfterMode = null
	 * @return void
	 */
	public function AddMenuItem($sMode, $sModeName, $sModeTeplatePath, $aToolTips = array(), $sAddAfterMode = null, $bAddInFoot = true)
	{
		if (!isset($this->aMenu[$sMode]))
		{
			if (null !== $sAddAfterMode && isset($this->aMenu[$sAddAfterMode]))
			{
				$aItems = null;
				$iIndex = 1;
				foreach ($this->aMenu as $sMenuMode => $aItems)
				{
					if ($sAddAfterMode === $sMenuMode)
					{
						break;
					}

					$iIndex++;
				}

				$this->aMenu = array_slice($this->aMenu, 0, $iIndex) +
					array($sMode => array()) +
					array_slice($this->aMenu, $iIndex);
			}
			else
			{
				$this->aMenu[$sMode] = array();
			}
		}

		if ($bAddInFoot)
		{
			array_push($this->aMenu[$sMode], new ap_Standard_Screen_MenuItem($sMode, $sModeName, $sModeTeplatePath, $aToolTips));
		}
		else
		{
			array_unshift($this->aMenu[$sMode], new ap_Standard_Screen_MenuItem($sMode, $sModeName, $sModeTeplatePath, $aToolTips));
		}
	}

	/**
	 * @param string $sMode
	 * @param bool $bForce = false
	 * @return void
	 */
	public function SetDefaultMode($sMode, $bForce = false)
	{
		if ($bForce || empty($this->aMenuDefMode))
		{
			$this->aMenuDefMode = $sMode;
		}
	}

	/**
	 * @return string
	 */
	public function GetCurrentMode()
	{
		if (!isset($this->aMenu[$this->sMode]))
		{
			$this->sMode = $this->aMenuDefMode;
		}

		if (empty($this->sMode))
		{
			$sScreenName = $this->GetScreenName();
			foreach (array_keys($this->aMenu) as $sKeyName)
			{
				$this->sMode = $sKeyName;
				CSession::Set($sScreenName.self::SESS_MODE, $this->sMode);
				break;
			}
		}

		return $this->sMode;
	}

	/**
	 * @return void
	 */
	public function WriteMenu()
	{
		$sCurrentMode = $this->GetCurrentMode();

		echo '<div style="width:215px; height:1px; overflow:hidden; padding: 0px"></div>';
		foreach ($this->aMenu as $sMode => /* @var $oItem ap_Standard_Screen_MenuItem */ $aItems)
		{
			$sClass = 'wm_settings_item';
			if ($sCurrentMode === $sMode)
			{
				$sClass = 'wm_selected_settings_item';
				foreach ($aItems as $oAddItem)
				{
					$aToolTips = $oAddItem->ToolTips();
					if (is_array($aToolTips) && 0 < count($aToolTips))
					{
						foreach($aToolTips as $sId => $sToolTip)
						{
							$this->JsAddInitText('JAddToolTip("'.ap_Utils::ReBuildStringToJavaScript($sId, '"').
								'", "'.ap_Utils::ReBuildStringToJavaScript($sToolTip, '"').'");');
						}
					}
				}
			}

			$oItem = isset($aItems[0]) ? $aItems[0] : null;
			if ($oItem)
			{
				echo '
<div class="'.$sClass.'" id="'.$oItem->Mode().'_div"><a href="'.AP_INDEX_FILE.'?mode='.$oItem->Mode().'">'.$oItem->Name().'</a></div>';
			}
		}
	}

	/**
	 * @return void
	 */
	public function WriteMain()
	{
		$sCurrentMode = $this->GetCurrentMode();
		foreach ($this->aMenu as $sMode => /* @var $oItem ap_Standard_Screen_MenuItem */ $aItems)
		{
			if ($sMode === $sCurrentMode && is_array($aItems) && 0 < count($aItems))
			{
				echo '<form autocomplete="off" action="'.AP_INDEX_FILE.'?submit" method="POST" id="'.$sMode.'_form"><input type="hidden" name="form_id" value="'.$sMode.'"><table class="wm_settings_common" width="650">';
				foreach ($aItems as $oItem)
				{
					if (@file_exists($oItem->Template()))
					{
						include $oItem->Template();
					}
				}
				echo '</table>
<table class="wm_settings_common" width="100%">
	<tr>
		<td>
			<hr />
		</td>
	</tr>
	<tr>
		<td align="right">
			<span class="wm_secondary_info" style="float: left; display: none;">* required fields</span>
			<input type="submit" name="submit_btn" value="Save" class="wm_button" style="width: 100px">
		</td>
	</tr>
</table></form>';

			}
		}
	}
}


class ap_Standard_Screen_MenuItem
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
	 * @var	array
	 */
	protected $aToolTips;

	/**
	 * @param string $sMode
	 * @param string $sName
	 * @param string $sTemplate
	 * @param array $aToolTips = array()
	 */
	public function __construct($sMode, $sName, $sTemplate, $aToolTips = array())
	{
		$this->sMode = $sMode;
		$this->sName = $sName;
		$this->sTemplate = $sTemplate;
		$this->aToolTips = $aToolTips;
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
	public function Template()
	{
		return $this->sTemplate;
	}

	/**
	 * @retun array
	 */
	public function ToolTips()
	{
		return $this->aToolTips;
	}
}