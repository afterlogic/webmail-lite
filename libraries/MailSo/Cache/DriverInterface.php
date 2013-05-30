<?php

namespace MailSo\Cache;

/**
 * @category MailSo
 * @package Cache
 */
interface DriverInterface
{
	/**
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return void
	 */
	public function Set($sKey, $sValue);

	/**
	 * @param string $sKey
	 *
	 * @return string
	 */
	public function Get($sKey);

	/**
	 * @param string $sKey
	 *
	 * @return void
	 */
	public function Delete($sKey);
}
