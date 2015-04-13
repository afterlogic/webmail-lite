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
class CPdoPostgresHelper implements IDbHelper
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
			$sResult = str_replace('\'', '\'\'', $sValue);
		}
		else
		{
			$sResult = 0 === strlen($sValue) ? '\'\'' : '\''.str_replace('\'', '\'\'', $sValue).'\'';
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
		return '"'.str_replace('"', '\\"', trim($sValue)).'"';
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
	public function DropIndexRequest($sIndexesName, $sTableName)
	{
		return sprintf('DROP INDEX %s', $sIndexesName);
	}

	/**
	 * @return string
	 */
	public function CreateIndexRequest($iIndexType, $sTableName, $sIndexName, $aFields)
	{
		$sResult = '';
		if (CDbKey::TYPE_INDEX === $iIndexType)
		{
			$aValues = array_map(array(&$this, 'EscapeColumn'), $aFields);
			$sResult = 'CREATE INDEX '.$this->EscapeColumn($sIndexName).
				' ON '.$sTableName.' ('.implode(', ', $aValues).')';
		}
		else if (CDbKey::TYPE_UNIQUE_KEY === $iIndexType)
		{
			$aValues = array_map(array(&$this, 'EscapeColumn'), $aFields);
			$sResult = 'CREATE UNIQUE INDEX '.$this->EscapeColumn($sIndexName).
				' ON '.$sTableName.' ('.implode(', ', $aValues).')';
		}

		return $sResult;
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
				$sResult .= 'serial NOT NULL';
				break;
			case CDbField::AUTO_INT_BIG:
				$sResult .= 'bigserial NOT NULL';
				break;
			case CDbField::AUTO_INT_UNSIGNED:
				$sResult .= 'serial NOT NULL';
				break;
			case CDbField::AUTO_INT_BIG_UNSIGNED:
				$sResult .= 'bigserial NOT NULL';
				break;

			case CDbField::BIT:
				$sResult .= 'smallint';
				break;
			case CDbField::INT:
				$sResult .= 'integer';
				break;
			case CDbField::INT_UNSIGNED:
				$sResult .= 'bigint';
				break;
			case CDbField::INT_SHORT:
				$sResult .= 'smallint';
				break;
			case CDbField::INT_SHORT_SMALL:
				$sResult .= 'smallint';
				break;
			case CDbField::INT_SMALL:
				$sResult .= 'integer';
				break;
			case CDbField::INT_BIG:
				$sResult .= 'bigint';
				break;
			case CDbField::INT_UNSIGNED:
				$sResult .= 'bigint';
				break;
			case CDbField::INT_BIG_UNSIGNED:
				$sResult .= 'bigint';
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
				$sResult .= 'text';
				break;
			case CDbField::TEXT_MEDIUM:
				$sResult .= 'text';
				break;
			case CDbField::BLOB:
				$sResult .= 'bytea';
				break;
			case CDbField::BLOB_LONG:
				$sResult .= 'bytea';
				break;

			case CDbField::DATETIME:
				$sResult .= 'timestamp';
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
		return \strtolower($sTableName.'_'.$sFiledName.'_seq');
	}
}
