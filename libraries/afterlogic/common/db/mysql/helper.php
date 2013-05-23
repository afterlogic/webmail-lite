<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

CApi::Inc('common.db.helper');

/**
 * @package Api
 * @subpackage Db
 */
class CMySqlHelper implements IDbHelper
{
	/**
	 * @param string $sValue
	 * @return string
	 */
	public function EscapeString($sValue, $bWithOutQuote = false)
	{
		if ($bWithOutQuote)
		{
			return addslashes($sValue);
		}
		else
		{
			return empty($sValue) ? '\'\'' : '\''.addslashes($sValue).'\'';
		}
	}

	/**
	 * @param string $sValue
	 * @return string
	 */
	public function EscapeColumn($sValue)
	{
		return empty($sValue) ? $sValue : '`'.$sValue.'`';
	}

	/**
	 * @param int $iTimeStamp
	 * @param bool $bAsInsert = false
	 * @return string
	 */
	public function TimeStampToDateFormat($iTimeStamp, $bAsInsert = false)
	{
		$sResult = (string) gmdate('Y-m-d H:i:s', $iTimeStamp);
		return ($bAsInsert) ? $this->UpdateDateFormat($sResult) : $sResult;
	}

	/**
	 * @param string $sFieldName
	 * @return string
	 */
	public function GetDateFormat($sFieldName)
	{
		return 'DATE_FORMAT('.$sFieldName.', "%Y-%m-%d %T")';
	}

	/**
	 * @param string $sFieldName
	 * @return string
	 */
	public function UpdateDateFormat($sFieldName)
	{
		return $this->EscapeString($sFieldName);
	}

	/**
	 * @return string
	 */
	public function CreateTableLastLine()
	{
		return '/*!40101 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */';
	}
}
