<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

abstract class ap_Screen
{
	/**
	 * @var	CAdminPanel
	 */
	protected $oAdminPanel;

	/**
	 * @var	string
	 */
	protected $sScreenTemplate;

	/**
	 * @var string
	 */
	protected $sScreenName;

	/**
	 * @var	ap_Screen_Data
	 */
	public $Data;

	/**
	 * @param CAdminPanel $oAdminPanel
	 * @param string $sScreenTemplate
	 * @return ap_Screen
	 */
	public function __construct(CAdminPanel &$oAdminPanel, $sScreenTemplate)
	{
		$this->oAdminPanel =& $oAdminPanel;
		$this->Data = new ap_Screen_Data();
		$this->sScreenTemplate = $sScreenTemplate;
		$this->sScreenName = 'screen';
	}

	/**
	 * @return void
	 */
	public function PreModuleInit() {}

	/**
	 * @return void
	 */
	public function MiddleModuleInit() {}
	
	/**
	 * @return void
	 */
	public function EndModuleInit() {}

	/**
	 * @param string $sName
	 * @return void
	 */
	public function SetScreenName($sName)
	{
		$this->sScreenName = $sName;
	}

	/**
	 * @return string
	 */
	public function GetScreenName()
	{
		return $this->sScreenName;
	}

	/**
	 * @return string
	 */
	public function Tab()
	{
		return $this->oAdminPanel->Tab();
	}

	/**
	 * @param string $sCssFile
	 */
	public function CssAddFile($sCssFile)
	{
		$this->oAdminPanel->CssAddFile($sCssFile);
	}

	/**
	 * @param string $sJsFile
	 */
	public function JsAddFile($sJsFile)
	{
		$this->oAdminPanel->JsAddFile($sJsFile);
	}

	/**
	 * @param string $sJsInitText
	 */
	public function JsAddInitText($sJsInitText)
	{
		$this->oAdminPanel->JsAddInitText($sJsInitText);
	}

	/**
	 * @return void
	 */
	public function WriteTabs()
	{
		$this->oAdminPanel->WriteTabs();
	}

	public function Run()
	{
		if (@file_exists($this->sScreenTemplate))
		{
			include $this->sScreenTemplate;
		}
	}
}

class ap_Screen_Data
{
	/**
	 * @var	array
	 */
	protected $aData;

	/**
	 * @return ap_Screen_Data
	 */
	public function __construct()
	{
		$this->aData = array();
	}

	/**
	 * @param string $sName
	 * @param mixed $mValue
	 */
	function SetValue($sName, $mValue)
	{
		$this->aData[$sName] = $mValue;
	}

	/**
	 * @param string $sName
	 * @return mixed
	 */
	function GetValue($sName)
	{
		return $this->ValueExist($sName) ? $this->aData[$sName] : null;
	}

	/**
	 * @param string $sName
	 * @return bool
	 */
	function ValueExist($sName)
	{
		return isset($this->aData[$sName]);
	}

	/**
	 * @param string $sName
	 * @return string
	 */
	function GetValueAsString($sName)
	{
		return (string) $this->GetValue($sName);
	}

	/**
	 * @param string $sName
	 * @return int
	 */
	function GetValueAsInt($sName)
	{
		return api_Utils::GetGoodBigInt($this->GetValue($sName));
	}

	/**
	 * @param string $sName
	 * @return bool
	 */
	function GetValueAsBool($sName)
	{
		return (bool) $this->GetValue($sName);
	}

	/**
	 * @param string $sName
	 * @return string
	 */
	function GetInputValue($sName)
	{
		return ap_Utils::AttributeQuote($this->GetValueAsString($sName));
	}

	/**
	 * @param string $sName
	 */
	function PrintCheckedValue($sName)
	{
		echo ($this->GetValueAsBool($sName)) ? ' checked="checked" ' : '';
	}
	
	function ConvertBoolToChecked($bValue)
	{
		echo $bValue ? ' checked="checked" ' : '';
	}

	/**
	 * @param string $sName
	 */
	function PrintSelectedValue($sName)
	{
		echo ($this->GetValueAsBool($sName)) ? ' selected="selected" ' : '';
	}

	/**
	 * @param string $sName
	 */
	function PrintDisabledValue($sName)
	{
		echo ($this->GetValueAsBool($sName)) ? ' disabled="disabled" ' : '';
	}

	/**
	 * @param string $sName
	 */
	function PrintInputValue($sName)
	{
		echo $this->GetInputValue($sName);
	}

	/**
	 * @param string $sName
	 */
	function PrintClearValue($sName)
	{
		echo $this->GetValueAsString($sName);
	}

	/**
	 * @param string $sName
	 */
	function PrintEncodedHtmlValue($sName)
	{
		echo api_Utils::EncodeSpecialXmlChars($this->GetValueAsString($sName));
	}

	/**
	 * @param string $sName
	 */
	function PrintValue($sName)
	{
		echo $this->GetValueAsString($sName);
	}

	/**
	 * @param string $sName
	 */
	function PrintIntValue($sName)
	{
		echo $this->GetValueAsInt($sName);
	}

	/**
	 * @param string $name
	 */
	function PrintJsValue($sName)
	{
		echo ap_Utils::ReBuildStringToJavaScript($this->GetValueAsString($sName), '"');
	}
}
