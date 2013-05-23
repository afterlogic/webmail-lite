<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Api
 */
class CGet
{
	/**
	 * @var bool
	 */
	public static $bIsMagicQuotesOn = false;

	private function __construct() {}

	/**
	 * @param string $sKey
	 * @return bool
	 */
	public static function Has($sKey)
	{
		return (isset($_GET[$sKey]));
	}

	/**
	 * @param string $sKey
	 * @param mixed $nmDefault = null
	 * @return mixed
	 */
	public static function Get($sKey, $nmDefault = null)
	{
		return (isset($_GET[$sKey])) ? self::_stripSlashesValue($_GET[$sKey]) : $nmDefault;
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 */
	public static function Set($sKey, $mValue)
	{
		$_GET[$sKey] = $mValue;
	}

	/**
	 * @param mixed $mValue
	 * @return mixed
	 */
	private static function _stripSlashesValue($mValue)
	{
		if (!self::$bIsMagicQuotesOn)
		{
			return $mValue;
		}

		$sType = gettype($mValue);
		if ($sType === 'string')
		{
			return stripslashes($mValue);
		}
		else if ($sType === 'array')
		{
			$aReturnValue = array();
			$mValueKeys = array_keys($mValue);
			foreach($mValueKeys as $sKey)
			{
				$aReturnValue[$sKey] = self::_stripSlashesValue($mValue[$sKey]);
			}
			return $aReturnValue;
		}
		else
		{
			return $mValue;
		}
	}
}

CGet::$bIsMagicQuotesOn = (bool) ini_get('magic_quotes_gpc');
