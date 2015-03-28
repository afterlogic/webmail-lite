<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace ProjectSeven\Base;

/**
 * @category ProjectSeven
 * @package Base
 */
class Utils
{
	/**
	 * @param string $sValue
	 * @return array
	 */
	public static function ExplodeIntUids($sValue)
	{
		$aValue = explode(',', (string) $sValue);
		$aValue = array_map('trim', $aValue);
		$aValue = array_map('intval', $aValue);

		$aValue = array_filter($aValue, function ($iValue) {
			return 0 < $iValue;
		});

		return $aValue;
	}
	
	/**
	 * @return int
	 */
	public static function iframedTimestamp()
	{
		return time() - 60 * 2;
	}
}
