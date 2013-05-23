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
class CDbTable
{
	const CRLF = "\r\n";
	const TAB = "\t";

	/**
	 * @var string
	 */
	protected $sName;

	/**
	 * @var string
	 */
	protected $sPrefix;

	/**
	 * @var array
	 */
	protected $aFields;

	/**
	 * @var array
	 */
	protected $aKeys;

	/**
	 * @param string $sName
	 * @param string $sPrefix
	 * @param array $aFields
	 * @param array $aKeys = array()
	 */
	public function __construct($sName, $sPrefix, array $aFields, $aKeys = array())
	{
		$this->sName = $sName;
		$this->sPrefix = $sPrefix;
		$this->aFields = $aFields;
		$this->aKeys = $aKeys;
	}

	/**
	 * @return string
	 */
	public function Name($nWithPrefix = true)
	{
		return (($nWithPrefix) ? $this->sPrefix : '').$this->sName;
	}

	/**
	 * @return array
	 */
	public function GetFieldNames()
	{
		$aField = array();
		foreach ($this->aFields as /* @var $oField CDbField */ $oField)
		{
			$aField[] = $oField->Name();
		}
		return $aField;
	}

	/**
	 * @return array
	 */
	public function GetIndexesFieldsNames()
	{
		$aKeyLines = array();
		foreach ($this->aKeys as /* @var $oKey CDbKey */ $oKey)
		{
			if (CDbKey::TYPE_PRIMARY_KEY !== $oKey->GetType())
			{
				$aKeyFields = $oKey->GetIndexesFields();
				if (is_array($aKeyFields) && 0 < count($aKeyFields))
				{
					$aKeyLines[] = $aKeyFields;
				}
			}
		}
		return $aKeyLines;
	}

	/**
	 * @param string $sName
	 * @return CDbField
	 */
	public function GetFieldByName($sName)
	{
		$oResultField = false;
		foreach ($this->aFields as /* @var $oField CDbField */ $oField)
		{
			if ($sName === $oField->Name())
			{
				$oResultField = $oField;
				break;
			}
		}
		return $oResultField;
	}

	/**
	 * @param CMySqlHelper $oHelper
	 * @param bool $bAddDropTable = false
	 * @return string
	 */
	public function ToString(CMySqlHelper $oHelper, $bAddDropTable = false)
	{
		$sResult = '';
		if ($bAddDropTable)
		{
			$sResult .= 'DROP TABLE IF EXISTS '.$oHelper->EscapeColumn($this->Name()).';'.CDbTable::CRLF;
		}

		$sResult .= 'CREATE TABLE '.$oHelper->EscapeColumn($this->Name())
			.' ('.CDbTable::CRLF.CDbTable::TAB;

		$aFieldLines = array();
		foreach ($this->aFields as /* @var $oField CDbField */ $oField)
		{
			$aFieldLines[] = $oField->ToString($oHelper);
		}

		$sResult .= implode(','.CDbTable::CRLF.CDbTable::TAB, $aFieldLines);
		unset($aFieldLines);

		$aKeyLines = array();
		foreach ($this->aKeys as /* @var $oKey CDbKey */ $oKey)
		{
			$sLine = $oKey->ToString($oHelper, $this->Name());
			if (!empty($sLine))
			{
				$aKeyLines[] = $sLine;
			}
		}

		if (0 < count($aKeyLines))
		{
			$sResult .= ','.CDbTable::CRLF.CDbTable::TAB.
				implode(','.CDbTable::CRLF.CDbTable::TAB, $aKeyLines);
		}
		unset($aKeyLines);

		return trim($sResult.CDbTable::CRLF.') '.$oHelper->CreateTableLastLine());
	}

	/**
	 * @param CMySqlHelper $oHelper
	 * @param array $aFieldsToAdd
	 * @return string
	 */
	public function GetAlterAddFields(CMySqlHelper $oHelper, $aFieldsToAdd)
	{
		if (0 < count($aFieldsToAdd))
		{
			$aLines = array();
			foreach ($this->aFields as /* @var $oField CDbField */ $oField)
			{
				if (in_array($oField->Name(), $aFieldsToAdd))
				{
					$aLines[] = 'ADD '.$oField->ToString($oHelper);
				}
			}

			return sprintf('ALTER TABLE %s %s', $oHelper->EscapeColumn($this->Name()), implode(', ', $aLines));
		}

		return false;
	}

	/**
	 * @param CMySqlHelper $oHelper
	 * @param array $aFieldsToDelete
	 * @return string
	 */
	public function GetAlterDeleteFields(CMySqlHelper $oHelper, $aFieldsToDelete)
	{
		if (0 < count($aFieldsToDelete))
		{
			$aLines = array();
			foreach ($aFieldsToDelete as $sFieldName)
			{
				$aLines[] = 'DROP '.$oHelper->EscapeColumn($sFieldName);
			}

			return sprintf('ALTER TABLE %s %s', $oHelper->EscapeColumn($this->Name()), implode(', ', $aLines));
		}

		return false;
	}

	/**
	 * @param CMySqlHelper $oHelper
	 * @param array $aIndexesToCreate
	 * @return string
	 */
	public function GetAlterCreateIndexes(CMySqlHelper $oHelper, $aIndexesToCreate)
	{
		if (0 < count($aIndexesToCreate))
		{
			$sName = strtoupper('awm_'.$this->Name().'_'.implode('_', $aIndexesToCreate).'_index');
			$aIndexesToCreate = array_map(array($oHelper, 'EscapeColumn'), $aIndexesToCreate);

			return sprintf('CREATE INDEX %s ON %s (%s)', $sName,
				$oHelper->EscapeColumn($this->Name()), implode(', ', $aIndexesToCreate));
		}

		return false;
	}

	/**
	 * @param CMySqlHelper $oHelper
	 * @param string $sIndexesName
	 * @return string
	 */
	public function GetAlterDeleteIndexes(CMySqlHelper $oHelper, $sIndexesName)
	{
		if (!empty($sIndexesName))
		{
			return sprintf('DROP INDEX %s ON %s', $sIndexesName, $oHelper->EscapeColumn($this->Name()));
		}

		return false;
	}
}

/**
 * @package Api
 * @subpackage Db
 */
class CDbField
{
	const AUTO_INT = 10;
	const AUTO_INT_BIG = 11;
	const AUTO_INT_UNSIGNED = 12;
	const AUTO_INT_BIG_UNSIGNED = 13;

	const BIT = 20;
	const INT = 21;
	const INT_SHORT = 22;
	const INT_SMALL = 23;
	const INT_BIG = 24;
	const INT_UNSIGNED = 25;
	const INT_SHORT_SMALL = 26;

	const CHAR = 31;
	const VAR_CHAR = 32;
	const TEXT = 33;
	const TEXT_LONG = 34;
	const BLOB = 35;
	const BLOB_LONG = 36;

	const DATETIME = 40;

	/**
	 * @var string
	 */
	protected $sName;

	/**
	 * @var int
	 */
	protected $iType;

	/**
	 * @var mixed
	 */
	protected $mDefault;

	/**
	 * @var int
	 */
	protected $iCustomLen;

	/**
	 * @var bool
	 */
	protected $bNotNullWithOutDefault;

	/**
	 * @param string $sName
	 * @param int $iType
	 * @param mixed $mDefault = null
	 * @param int $iCustomLen = null
	 * @param bool $bNotNullWithOutDefault = false
	 */
	public function __construct($sName, $iType, $mDefault = null, $iCustomLen = null, $bNotNullWithOutDefault = false)
	{
		$this->sName = $sName;
		$this->iType = $iType;
		$this->mDefault = $mDefault;
		$this->iCustomLen = $iCustomLen;
		$this->bNotNullWithOutDefault = $bNotNullWithOutDefault;
	}

	/**
	 * @return string
	 */
	public function Name()
	{
		return $this->sName;
	}

	/**
	 * @param string $sTableName
	 * @param CMySqlHelper $oHelper
	 * @return string
	 */
	public function ToAlterString($sTableName, CMySqlHelper $oHelper)
	{
		return sprintf('ALTER TABLE %s ADD %s',
			$oHelper->EscapeColumn($sTableName), $this->ToString($oHelper));
	}

	/**
	 * @return striong
	 */
	public function ToString(CMySqlHelper $oHelper)
	{
		$sResult = $oHelper->EscapeColumn($this->sName).' ';
		switch ($this->iType)
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

			case CDbField::CHAR:
				$sResult .= 'varchar(1)';
				break;
			case CDbField::VAR_CHAR:
				$sResult .= (null === $this->iCustomLen)
					? 'varchar(255)' : 'varchar('.((int) $this->iCustomLen).')';
				break;
			case CDbField::TEXT:
				$sResult .= 'text';
				break;
			case CDbField::TEXT_LONG:
				$sResult .= 'longtext';
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

		if (in_array($this->iType, array(CDbField::AUTO_INT, CDbField::AUTO_INT_BIG,
			CDbField::AUTO_INT_UNSIGNED, CDbField::AUTO_INT_BIG_UNSIGNED,
			CDbField::TEXT, CDbField::TEXT_LONG, CDbField::BLOB, CDbField::BLOB_LONG)))
		{
			// no need default
		}
		else if (null !== $this->mDefault)
		{
			$sResult .= ' NOT NULL default ';
			if (is_string($this->mDefault))
			{
				$sResult .= $oHelper->EscapeString($this->mDefault);
			}
			else if (is_numeric($this->mDefault))
			{
				$sResult .= (string) $this->mDefault;
			}
		}
		else
		{
			$sResult .= $this->bNotNullWithOutDefault ? ' NOT NULL' : ' default NULL';
		}

		return trim($sResult);
	}
}

/**
 * @package Api
 * @subpackage Db
 */
class CDbKey
{
	const TYPE_KEY = 0;
	const TYPE_UNIQUE_KEY = 1;
	const TYPE_PRIMARY_KEY = 2;
	const TYPE_INDEX = 3;
	const TYPE_FULLTEXT = 4;

	/**
	 * @var int
	 */
	protected $iType;

	/**
	 * @var array
	 */
	protected $aFields;

	/**
	 * @param string $sName
	 * @param int $iType
	 * @param array $aFields
	 */
	public function __construct($iType, array $aFields)
	{
		$this->iType = $iType;
		$this->aFields = $aFields;
	}

	/**
	 * @return int
	 */
	public function GetType()
	{
		return $this->iType;
	}

	/**
	 * @param string $sTableName
	 * @return string
	 */
	public function GetName($sTableName)
	{
		$aList = $this->aFields;
		sort($aList);
		return strtoupper($sTableName.'_'.implode('_', $aList).'_INDEX');
	}

	/**
	 * @return array
	 */
	public function GetIndexesFields()
	{
		return $this->aFields;
	}

	/**
	 * @return striong
	 */
	public function ToString(CMySqlHelper $oHelper, $sTableName)
	{
		$sResult = '';
		if (0 < count($this->aFields))
		{
			switch ($this->iType)
			{
				case CDbKey::TYPE_PRIMARY_KEY:
					$sResult .= 'PRIMARY KEY';
					break;
				case CDbKey::TYPE_KEY:
					$sResult .= 'KEY '.$oHelper->EscapeColumn($this->GetName($sTableName));
					break;
				case CDbKey::TYPE_UNIQUE_KEY:
					$sResult .= 'UNIQUE '.$oHelper->EscapeColumn($this->GetName($sTableName));
					break;
				case CDbKey::TYPE_INDEX:
					$sResult .= 'INDEX '.$oHelper->EscapeColumn($this->GetName($sTableName));
					break;
				case CDbKey::TYPE_FULLTEXT:
					$sResult .= 'FULLTEXT '.$oHelper->EscapeColumn($this->GetName($sTableName));
					break;
			}

			$aValues = array_map(array(&$oHelper, 'EscapeColumn'), $this->aFields);
			$sResult .= ' ('.implode(', ', $aValues).')';
		}

		return trim($sResult);
	}
}

/**
 * @package Api
 * @subpackage Db
 */
class CDbFunction
{
	/**
	 * @var string
	 */
	protected $sName;

	/**
	 * @var string
	 */
	protected $sIncParams;

	/**
	 * @var string
	 */
	protected $sResult;

	/**
	 * @var string
	 */
	protected $sText;

	/**
	 * @param string $sName
	 * @param string $sText
	 */
	public function __construct($sName, $sIncParams, $sResult, $sText)
	{
		$this->sName = $sName;
		$this->sIncParams = $sIncParams;
		$this->sResult = $sResult;
		$this->sText = $sText;
	}

	/**
	 * @param CMySqlHelper $oHelper
	 * @param bool $bAddDropFunction = false
	 * @return string
	 */
	public function ToString(CMySqlHelper $oHelper, $bAddDropFunction = false)
	{
		$sResult = '';
		if ($bAddDropFunction)
		{
			$sResult .= 'DROP FUNCTION IF EXISTS '.$this->sName.';;'.CDbTable::CRLF;
		}

		$sResult .= 'CREATE FUNCTION '.$this->sName.'('.$this->sIncParams.') RETURNS '.$this->sResult;
		$sResult .= CDbTable::CRLF.$this->sText;

		return trim($sResult);
	}
}