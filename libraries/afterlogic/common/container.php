<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Api
 */
abstract class api_AContainer
{
	const SESSION_CONTAINER_PREFIX = 'sess_object_container';

	/**
	 * @var bool
	 */
	public $__USE_TRIM_IN_STRINGS__;

	/**
	 * @var string
	 */
	protected $sParentClassName;

	/**
	 * @var string
	 */
	protected $sSessionUniqueProperty;

	/**
	 * @var array
	 */
	protected $aContainer;

	/**
	 * @var array
	 */
	protected $aObsolete;

	/**
	 * @var array
	 */
	protected $aTrimer;

	/**
	 * @var array
	 */
	protected $aLower;

	/**
	 * @var array
	 */
	protected $aUpper;

	/**
	 * @param string $sParentClassName
	 * @param string $sSessionUniqueProperty = ''
	 */
	public function __construct($sParentClassName, $sSessionUniqueProperty = '')
	{
		$this->__USE_TRIM_IN_STRINGS__ = false;
		$this->sParentClassName = $sParentClassName;
		$this->sSessionUniqueProperty = $sSessionUniqueProperty;

		$this->aContainer = array();
		$this->aObsolete = array();
		$this->aTrimer = array();
		$this->aLower = array();
		$this->aUpper = array();
	}

	/**
	 * @param array $aValues
	 * @return void
	 */
	public function SetDefaults($aValues)
	{
		$this->MassSetValues($aValues);
		$this->FlushObsolete();
	}

	/**
	 * @param array $aValues
	 * @return void
	 */
	public function SetTrimer($aValues)
	{
		$this->aTrimer = $aValues;
	}

	/**
	 * @param array $aValues
	 * @return void
	 */
	public function SetLower($aValues)
	{
		$this->aLower = $aValues;
	}

	/**
	 * @param array $aValues
	 * @return void
	 */
	public function SetUpper($aValues)
	{
		$this->aUpper = $aValues;
	}

	/**
	 * @param array $aValues
	 * @return void
	 */
	public function MassSetValues($aValues)
	{
		foreach ($aValues as $sKey => $mValue)
		{
			$this->{$sKey} = $mValue;
		}
	}

	/**
	 * @param stdClass $oRow
	 */
	public function InitByDbRow($oRow)
	{
		$aMap = $this->GetMap();
		foreach ($aMap as $sKey => $aTypes)
		{
			if (isset($aTypes[1]) && property_exists($oRow, $aTypes[1]))
			{
				if ('password' === $aTypes[0])
				{
					$this->{$sKey} = api_Utils::DecodePassword($oRow->{$aTypes[1]});
				}
				else if ('datetime' === $aTypes[0])
				{
					$iDateTime = 0;
					$aDateTime = api_Utils::DateParse($oRow->{$aTypes[1]});
					if (is_array($aDateTime))
					{
						$iDateTime = gmmktime($aDateTime['hour'], $aDateTime['minute'], $aDateTime['second'],
							$aDateTime['month'], $aDateTime['day'], $aDateTime['year']);

						if (false === $iDateTime || $iDateTime <= 0)
						{
							$iDateTime = 0;
						}
					}

					$this->{$sKey} = $iDateTime;
				}
				else if ('serialize' === $aTypes[0])
				{
					$this->{$sKey} = ('' === $oRow->{$aTypes[1]} || !is_string($oRow->{$aTypes[1]})) ?
						'' : unserialize($oRow->{$aTypes[1]});
				}
				else
				{
					$this->{$sKey} = $oRow->{$aTypes[1]};
				}

				$this->FlushObsolete($sKey);
			}
		}
	}

	/**
	 * @param string $sKey
	 * @return mixed
	 */
	public function GetObsoleteValue($sKey)
	{
		if (key_exists($sKey, $this->aObsolete))
		{
			return $this->aObsolete[$sKey];
		}

		return null;
	}

	/**
	 * @param string $sKey = null
	 * @return void
	 */
	public function FlushObsolete($nsKey = null)
	{
		if (null === $nsKey)
		{
			$this->aObsolete = array();
		}
		else
		{
			if (key_exists($nsKey, $this->aObsolete))
			{
				unset($this->aObsolete[$nsKey]);
			}
		}
	}

	/**
	 * @param string $sKey
	 * @param mixed $mDefault
	 * @return mixed
	 */
	public function GetSessionValue($sKey, $mDefault = null)
	{
		$aValues = CSession::Get($this->getSessionUniqueKey(), null);

		return (is_array($aValues) && array_key_exists($sKey, $aValues)) ? $aValues[$sKey] : $mDefault;
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 */
	public function SetSessionValue($sKey, $mValue)
	{
		$sUniqueKey = $this->getSessionUniqueKey();
		$aValues = CSession::Get($sUniqueKey, array());
		if (!is_array($aValues))
		{
			$aValues = array();
		}

		$aValues[$sKey] = $mValue;
		CSession::Set($sUniqueKey, $aValues);
	}

	/**
	 * @return string
	 */
	protected function getSessionUniqueKey()
	{
		$sUniqueKey = (0 === strlen($this->sSessionUniqueProperty)) ? '' : $this->{$this->sSessionUniqueProperty};
		return api_AContainer::SESSION_CONTAINER_PREFIX.$this->sParentClassName.$sUniqueKey;
	}

	/**
	 * @return string
	 */
	public function SessionUniqueProperty()
	{
		return $this->sSessionUniqueProperty;
	}

	/**
	 * @return string
	 */
	public function SessionUniquePropertyValue()
	{
		return (0 === strlen($this->sSessionUniqueProperty)) ? '' : $this->{$this->sSessionUniqueProperty};
	}

	/**
	 * @param string $sPropertyName
	 * @param mixed $mValue
	 * @return bool
	 */
	public function IsProperty($sPropertyName)
	{
		$aMap = $this->GetMap();
		return isset($aMap[$sPropertyName]);
	}

	/**
	 * @param string $sPropertyName
	 * @return bool
	 */
	public function __isset($sPropertyName)
	{
		return $this->IsProperty($sPropertyName);
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 * @return void
	 */
	public function __set($sKey, $mValue)
	{
		$aMap = $this->GetMap();
		if (isset($aMap[$sKey]))
		{
			$this->setType($mValue, $aMap[$sKey][0]);

			if (key_exists($sKey, $this->aContainer))
			{
				$this->aObsolete[$sKey] = $this->aContainer[$sKey];
			}

			if (($this->__USE_TRIM_IN_STRINGS__ && 0 === strpos($aMap[$sKey][0], 'string')) ||
				(in_array($sKey, $this->aTrimer) && is_string($mValue)))
			{
				$mValue = trim($mValue);
			}

			if (is_string($mValue))
			{
				if (in_array($sKey, $this->aLower))
				{
					$mValue = strtolower($mValue);
				}
				else if (in_array($sKey, $this->aUpper))
				{
					$mValue = strtoupper($mValue);
				}
			}

			$this->aContainer[$sKey] = $mValue;
		}
		else
		{
			throw new CApiBaseException(Errs::Container_UndefinedProperty, null, array('{{PropertyName}}' => $sKey));
		}
	}

	/**
	 * @param string $sKey
	 * @return mixed
	 */
//	public function &__get($sKey)
	public function __get($sKey)
	{
		$mReturn = null;
		if (array_key_exists($sKey, $this->aContainer))
		{
			$mReturn = $this->aContainer[$sKey];
//			if (is_scalar($this->aContainer[$sKey]))
//			{
//				$mReturn = $this->aContainer[$sKey];
//			}
//			else
//			{
//				$mReturn =& $this->aContainer[$sKey];
//			}
		}
		else
		{
			throw new Exception('Undefined property '.$sKey);
		}

		return $mReturn;
	}

	/**
	 * @param mixed $mValue
	 * @param string $sType
	 */
	protected function setType(&$mValue, $sType)
	{
		$sType = strtolower($sType);
		if (in_array($sType, array('string', 'int', 'bool', 'array')))
		{
			settype($mValue, $sType);
		}
		else if (in_array($sType, array('datetime')))
		{
			settype($mValue, 'int');
		}
		else if (in_array($sType, array('password')))
		{
			settype($mValue, 'string');
		}
		else if (0 === strpos($sType, 'string('))
		{
			settype($mValue, 'string');
			if (0 < strlen($mValue))
			{
				$iSize = substr($sType, 7, -1);
				if (is_numeric($iSize) && (int) $iSize < strlen($mValue))
				{
					// $mValue = substr($mValue, 0, (int) $iSize);
					$mValue = api_Utils::Utf8Truncate($mValue, (int) $iSize);
				}
			}
		}
	}

	/**
	 * @param bool $bIsInsert = false
	 * @return bool
	 */
	public function InitBeforeChange()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		return true;
	}

	/**
	 * @return array
	 */
	public function GetMap()
	{
		return array();
	}

	/**
	 * @return array
	 */
	public static function GetStaticMap()
	{
		return array();
	}

	/**
	 * @param array $aMap
	 * @return array
	 */
	public static function DbReadKeys($aMap)
	{
		$aResult = array();
		foreach ($aMap as $aTypes)
		{
			if (isset($aTypes[1]))
			{
				$aResult[] = $aTypes[1];
			}
		}
		return $aResult;
	}

	/**
	 * @param array $aMap
	 * @param bool $bInsert
	 * @return array
	 */
	public static function DbWriteKeys($aMap, $bInsert)
	{
		$aResult = array();
		foreach ($aMap as $sKey => $aTypes)
		{
			if (isset($aTypes[1]))
			{
				$bUseInInsert = $bUseInUpdate = true;

				if (isset($aTypes[2]) && !$aTypes[2])
				{
					$bUseInInsert = false;
				}

				if (isset($aTypes[3]) && !$aTypes[3])
				{
					$bUseInUpdate = false;
				}

				if (($bInsert && $bUseInInsert) || (!$bInsert && $bUseInUpdate))
				{
					$aResult[$aTypes[1]] = $sKey;
				}
			}
		}
		return $aResult;
	}

	/**
	 * @param object $oObject
	 * @param object $oHelper
	 * @param array $aExclude
	 * @return array
	 */
	public static function DbUpdateArray($oObject, $oHelper, $aExclude = array())
	{
		$aResult = array();
		$aExclude = is_array($aExclude) && 0 < count($aExclude) ? $aExclude : array();

		$sQueryParams = '';
		$bUseLogQueryParams = (bool) CApi::GetConf('labs.db.log-query-params', false);

		$oObject->InitBeforeChange();

		$aStaticMap = $oObject->GetMap();
		$aMap = api_AContainer::DbWriteKeys($aStaticMap, false);

		foreach ($aMap as $sDbKey => $sObjectKey)
		{
			if (in_array($sDbKey, $aExclude))
			{
				continue;
			}

			$mValue = $oObject->{$sObjectKey};
			if (isset($aStaticMap[$sObjectKey][0]))
			{
				if ('password' === $aStaticMap[$sObjectKey][0])
				{
					$mValue = api_Utils::EncodePassword($mValue);
				}
				else if ('datetime' === $aStaticMap[$sObjectKey][0])
				{
					$mValue = $oHelper->TimeStampToDateFormat($mValue);
				}
				else if ('serialize' === $aStaticMap[$sObjectKey][0])
				{
					$mValue = '' === $mValue ? '' : serialize($mValue);
				}
			}

			$aResult[] = $oHelper->EscapeColumn($sDbKey).' = '.
				(is_string($mValue) ? $oHelper->EscapeString($mValue) : (int) $mValue);

			if ($bUseLogQueryParams)
			{
				$sQueryParams .=
					API_CRLF.API_TAB.$sDbKey.' = '.(
						is_string($mValue) ? $oHelper->EscapeString($mValue) : (int) $mValue);
			}
		}

		if ($bUseLogQueryParams)
		{
			CApi::Log($sQueryParams);
		}

		return $aResult;
	}

	/**
	 * @param string $sWhere
	 * @param string $sTableName
	 * @param array $aStaticMap
	 * @param object $oHelper
	 *
	 * @return string
	 */
	public static function DbGetObjectSqlString($sWhere, $sTableName, $aStaticMap, $oHelper)
	{
		$aMap = api_AContainer::DbReadKeys($aStaticMap);
		$aMap = array_map(array($oHelper, 'EscapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %s WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $sTableName, $sWhere);
	}

	/**
	 * @param string $sTableName
	 * @param object $oObject
	 * @param object $oHelper
	 *
	 * @return string
	 */
	public static function DbCreateObjectSqlString($sTableName, $oObject, $oHelper)
	{
		$sSql = '';
		$aResults = self::DbInsertArrays($oObject, $oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %s ( %s ) VALUES ( %s )';
			$sSql = sprintf($sSql, $sTableName, implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return $sSql;
	}

	/**
	 * @param string $sTableName
	 * @param object $oObject
	 * @param object $oHelper
	 *
	 * @return string
	 */
	public static function DbUpdateObjectSqlString($sTableName, $oObject, $oHelper)
	{
		$aResult = self::DbUpdateArray($oObject, $oHelper);
		$mValue = $oObject->SessionUniquePropertyValue();

		$sSql = 'UPDATE %s SET %s WHERE %s = %s';
		return sprintf($sSql, $sTableName, implode(', ', $aResult),
			$oHelper->EscapeColumn($oObject->SessionUniqueProperty()),
			(is_string($mValue) ? $oHelper->EscapeString($mValue) : (int) $mValue)
		);
	}

	/**
	 * @param object $oObject
	 * @param object $oHelper
	 * @return array
	 */
	public static function DbInsertArrays($oObject, $oHelper)
	{
		$aResult = array(false, false);

		$sQueryParams = '';
		$bUseLogQueryParams = (bool) CApi::GetConf('labs.db.log-query-params', false);

		$oObject->InitBeforeChange();

		$aStaticMap = $oObject->GetMap();
		$aMap = api_AContainer::DbWriteKeys($aStaticMap, true);

		$aDbKeys = array_keys($aMap);
		$aResult[0] = array_map(array(&$oHelper, 'EscapeColumn'), $aDbKeys);

		$aDbValues = array_values($aMap);
		foreach ($aDbValues as $iIndex => $sKey)
		{
			$mValue = $oObject->{$sKey};
			if (isset($aStaticMap[$sKey][0]))
			{
				if ('password' === $aStaticMap[$sKey][0])
				{
					$mValue = api_Utils::EncodePassword($mValue);
				}
				else if ('datetime' === $aStaticMap[$sKey][0])
				{
					$mValue = $oHelper->TimeStampToDateFormat($mValue);
				}
				else if ('serialize' === $aStaticMap[$sKey][0])
				{
					$mValue = '' === $mValue ? '' : serialize($mValue);
				}
			}

			$aDbValues[$iIndex] = is_string($mValue)
				? $oHelper->EscapeString($mValue) : (int) $mValue;

			if ($bUseLogQueryParams)
			{
				$sDbKey = isset($aDbKeys[$iIndex]) ? $aDbKeys[$iIndex] : '!unknown!';
				$sQueryParams .= API_CRLF.API_TAB.$sDbKey.' = '.$aDbValues[$iIndex];
			}
		}

		$aResult[1] = $aDbValues;

		if ($bUseLogQueryParams)
		{
			CApi::Log($sQueryParams);
		}

		return $aResult;
	}
}
