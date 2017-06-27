<?php

/*
 * Copyright 2004-2017, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

CApi::Inc('common.db.helper');

/**
 * @package Api
 * @subpackage Db
 */
class CPdoSQLiteHelper implements IDbHelper
{
	/**
	 * @param string $sValue
	 * @param bool $bWithOutQuote = false
	 * @param bool $bSearch = false
	 * @return string
	 */
	public function EscapeString($sValue, $bWithOutQuote = false, $bSearch = false)
	{
		$sResult = '';
		if ($bWithOutQuote)
		{
			$sResult = addslashes($sValue);
		}
		else
		{
			$sResult = 0 === strlen($sValue) ? '\'\'' : '\''.addslashes($sValue).'\'';
		}

		if ($bSearch)
		{
			$sResult = str_replace(array("%", "_"), array("\\%", "\\_"), $sResult);
		}

		return $sResult;
	}

	/**
	 * @param string $sValue
	 * @return string
	 */
	public function EscapeColumn($sValue)
	{
		return 0 === strlen($sValue) ? $sValue : '`'.$sValue.'`';
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
	 * @return bool
	 */
	public function UseSingleIndexRequest()
	{
		return true;
	}

	/**
	 * @return string
	 */
	public function CreateIndexRequest($iIndexType, $sTableName, $sIndexName, $aFields)
	{
		$sResult = '';
		$aValues = array_map(array(&$this, 'EscapeColumn'), $aFields);
		$sResult = 'CREATE INDEX '.$this->EscapeColumn($sIndexName).
			' ON '.$sTableName.' ('.implode(', ', $aValues).')';
		return $sResult;
	}

	/**
	 * @return string
	 */
	public function DropIndexRequest($sIndexesName, $sTableName)
	{
		return sprintf('DROP INDEX %s ON %s', $sIndexesName, $sTableName);
	}

	/**
	 * @param string $sName
	 * @param int $iFieldType
	 * @return string
	 */
	public function FieldToString($sName, $iFieldType, $mDefault = null, $iCustomLen = null, $bNotNullWithOutDefault = false)
	{
		$sResult = $this->EscapeColumn($sName).' ';
		switch ($iFieldType)
		{
			case CDbField::AUTO_INT:
			case CDbField::AUTO_INT_BIG:
			case CDbField::AUTO_INT_UNSIGNED:
			case CDbField::AUTO_INT_BIG_UNSIGNED:
				$sResult .= 'INTEGER NOT NULL';
				break;

			case CDbField::BIT:
			case CDbField::INT:
			case CDbField::INT_UNSIGNED:
			case CDbField::INT_SHORT:
			case CDbField::INT_SHORT_SMALL:
			case CDbField::INT_SMALL:
			case CDbField::INT_BIG:
			case CDbField::INT_UNSIGNED:
			case CDbField::INT_BIG_UNSIGNED:
				$sResult .= 'INTEGER';
				break;

			case CDbField::CHAR:
			case CDbField::VAR_CHAR:
			case CDbField::TEXT:
			case CDbField::TEXT_LONG:
			case CDbField::TEXT_MEDIUM:
				$sResult .= 'TEXT';
				break;

			case CDbField::BLOB:
			case CDbField::BLOB_LONG:
				$sResult .= 'BLOB';
				break;

			case CDbField::DATETIME:
				$sResult .= 'DATETIME';
				break;
		}

		if (in_array($iFieldType, array(CDbField::AUTO_INT, CDbField::AUTO_INT_BIG,
			CDbField::AUTO_INT_UNSIGNED, CDbField::AUTO_INT_BIG_UNSIGNED,
			CDbField::TEXT, CDbField::TEXT_LONG, CDbField::BLOB, CDbField::BLOB_LONG)))
		{
			// no need default
		}
		else if (null !== $mDefault)
		{
			$sResult .= ' NOT NULL default ';
			if (is_string($mDefault))
			{
				$sResult .= $this->EscapeString($mDefault);
			}
			else if (is_numeric($mDefault))
			{
				$sResult .= (string) $mDefault;
			}
		}
		else
		{
			$sResult .= $bNotNullWithOutDefault ? ' NOT NULL' : ' default NULL';
		}

		return trim($sResult);
	}

	/**
	 * @return string
	 */
	public function CreateTableLastLine()
	{
		return '';
	}

	/**
	 * @return string
	 */
	public function GenerateLastIdSeq($sTableName, $sFiledName)
	{
		return null;
	}
}
