<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

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
	 * @return bool
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

	/**
	 * @param int $iTimeToClearInHours = 24
	 *
	 * @return bool
	 */
	public function GC($iTimeToClearInHours = 24);
}
