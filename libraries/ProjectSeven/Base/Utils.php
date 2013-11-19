<?php

namespace ProjectSeven\Base;

/**
 * @category ProjectSeven
 * @package Base
 */
class Utils
{
	/**
	 * @param mixed $mValue
	 *
	 * @return string
	 */
	public static function JsonEncode($mValue)
	{
		return \json_encode($mValue, defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0);
	}

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
}
