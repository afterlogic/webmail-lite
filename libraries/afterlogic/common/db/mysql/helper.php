<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
				$sResult .= 'int(11) NOT NULL auto_increment';
				break;
			case CDbField::AUTO_INT_BIG:
				$sResult .= 'bigint(20) NOT NULL auto_increment';
				break;
			case CDbField::AUTO_INT_UNSIGNED:
				$sResult .= 'int(11) unsigned NOT NULL auto_increment';
				break;
			case CDbField::AUTO_INT_BIG_UNSIGNED:
				$sResult .= 'bigint(20) unsigned NOT NULL auto_increment';
				break;

			case CDbField::BIT:
				$sResult .= 'tinyint(1)';
				break;
			case CDbField::INT:
				$sResult .= 'int(11)';
				break;
			case CDbField::INT_UNSIGNED:
				$sResult .= 'int(11) unsigned';
				break;
			case CDbField::INT_SHORT:
				$sResult .= 'tinyint(4)';
				break;
			case CDbField::INT_SHORT_SMALL:
				$sResult .= 'tinyint(2)';
				break;
			case CDbField::INT_SMALL:
				$sResult .= 'smallint(6)';
				break;
			case CDbField::INT_BIG:
				$sResult .= 'bigint(20)';
				break;
			case CDbField::INT_UNSIGNED:
				$sResult .= 'int(11) UNSIGNED';
				break;
			case CDbField::INT_BIG_UNSIGNED:
				$sResult .= 'bigint UNSIGNED';
				break;

			case CDbField::CHAR:
				$sResult .= 'varchar(1)';
				break;
			case CDbField::VAR_CHAR:
				$sResult .= (null === $iCustomLen)
					? 'varchar(255)' : 'varchar('.((int) $iCustomLen).')';
				break;
			case CDbField::TEXT:
				$sResult .= 'text';
				break;
			case CDbField::TEXT_LONG:
				$sResult .= 'longtext';
				break;
			case CDbField::TEXT_MEDIUM:
				$sResult .= 'mediumtext';
				break;
			case CDbField::BLOB:
				$sResult .= 'blob';
				break;
			case CDbField::BLOB_LONG:
				$sResult .= 'longblob';
				break;

			case CDbField::DATETIME:
				$sResult .= 'datetime';
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
			$sResult .= $this->bNotNullWithOutDefault ? ' NOT NULL' : ' default NULL';
		}

		return trim($sResult);
	}

	/**
	 * @return string
	 */
	public function CreateTableLastLine()
	{
		return '/*!40101 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */';
	}
}
