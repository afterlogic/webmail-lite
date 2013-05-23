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
interface IDbHelper
{
	/**
	 * @param string $sValue
	 * @return string
	 */
	public function EscapeString($sValue);

	/**
	 * @param string $sValue
	 * @return string
	 */
	public function EscapeColumn($sValue);

	/**
	 * @param int $iTimeStamp
	 * @return string
	 */
	public function TimeStampToDateFormat($iTimeStamp);

	/**
	 * @param string $sFieldName
	 * @return string
	 */
	public function GetDateFormat($sFieldName);

	/**
	 * @param string $sFieldName
	 * @return string
	 */
	public function UpdateDateFormat($sFieldName);
}