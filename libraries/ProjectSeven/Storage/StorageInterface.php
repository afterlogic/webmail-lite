<?php

namespace ProjectSeven\Storage;

/**
 * @category ProjectSeven
 * @package Storage
 */
interface StorageInterface
{
	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return bool
	 */
	public function Put(\CAccount $oAccount, $iStorageType, $sKey, $sValue);

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return string | bool
	 */
	public function Get(\CAccount $oAccount, $iStorageType, $sKey);

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function Clear(\CAccount $oAccount, $iStorageType, $sKey);
}