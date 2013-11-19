<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Api
 * @subpackage Db
 */
abstract class api_CommandCreator
{
	/**
	 * @var IDbHelper
	 */
	protected $oHelper;

	/**
	 * @var string
	 */
	protected $sPrefix;

	/**
	 * @param IDbHelper $oHelper
	 * @param string $sPrefix
	 */
	public function __construct($oHelper, $sPrefix)
	{
		$this->oHelper = $oHelper;
		$this->sPrefix = (string) $sPrefix;
	}

	public function Prefix()
	{
		return $this->sPrefix;
	}

	/**
	 * @param string $sValue
	 * @param bool $bWithOutQuote = false
	 * @param bool $bSearch = false
	 * @return string
	 */
	protected function escapeString($sValue, $bWithOutQuote = false, $bSearch = false)
	{
		return $this->oHelper->EscapeString($sValue, $bWithOutQuote, $bSearch);
	}

	/**
	 * @param array $aValue
	 * @return array
	 */
	protected function escapeArray($aValue)
	{
		return array_map(array(&$this->oHelper, 'EscapeString'), $aValue);
	}

	/**
	 * @param string $str
	 * @return string
	 */
	protected function escapeColumn($str)
	{
		return $this->oHelper->EscapeColumn($str);
	}

	/**
	 * @param string $sFieldName
	 * @return string
	 */
	protected function GetDateFormat($sFieldName)
	{
		return $this->oHelper->GetDateFormat($sFieldName);
	}

	/**
	 * @param string $sFieldName
	 * @return string
	 */
	protected function UpdateDateFormat($sFieldName)
	{
		return $this->oHelper->UpdateDateFormat($sFieldName);
	}
}
