<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Api
 * @subpackage Db
 */
abstract class api_CommandCreator
{
	/**
	 * @var CMySqlHelper
	 */
	protected $oHelper;

	/**
	 * @var string
	 */
	protected $sPrefix;

	/**
	 * @param CMySqlHelper $oHelper
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
	 * @return string
	 */
	protected function escapeString($sValue)
	{
		return $this->oHelper->EscapeString($sValue);
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
