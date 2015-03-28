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
class CPost
{
	/**
	 * @var bool
	 */
	static $bIsMagicQuotesOn = false;

	private function __construct() {}

	/**
	 * @param string $sKey
	 * @return bool
	 */
	public static function Has($sKey)
	{
		return (isset($_POST[$sKey]));
	}

	/**
	 * @param string $sKey
	 * @param mixed $nmDefault = null
	 * @return mixed
	 */
	public static function Get($sKey, $nmDefault = null)
	{
		return (isset($_POST[$sKey])) ? self::_stripSlashesValue($_POST[$sKey]) : $nmDefault;
	}

	/**
	 * @param string $sKey
	 * @return bool
	 */
	public static function GetCheckBox($sKey)
	{
		return ('1' === (string) self::Get($sKey, '0'));
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 */
	public static function Set($sKey, $mValue)
	{
		$_POST[$sKey] = $mValue;
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

CPost::$bIsMagicQuotesOn = (bool) ini_get('magic_quotes_gpc');
