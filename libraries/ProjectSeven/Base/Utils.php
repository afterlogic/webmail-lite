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
//		return \json_encode($mValue, defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0);
		return \json_encode($mValue);
	}
}
