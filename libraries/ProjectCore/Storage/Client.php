<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace ProjectCore\Storage;

/**
 * @category ProjectCore
 * @package Storage
 */
class Client
{
	/**
	 * @var \ProjectCore\Storage\Drivers\Files
	 */
	protected $oDriver;

	/**
	 * @return void
	 */
	public function __construct()
	{
		// TODO hc
		$this->oDriver = new \ProjectCore\Storage\Drivers\Files(\CApi::DataPath());
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $iStorageType
	 * @param string $sValue
	 *
	 * @return bool
	 */
	public function put(\CAccount $oAccount, $iStorageType, $sKey, $sValue)
	{
		return $this->oDriver->put($oAccount, $iStorageType, $sKey, $sValue);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param resource $rSource
	 *
	 * @return bool
	 */
	public function putFile(\CAccount $oAccount, $iStorageType, $sKey, $rSource)
	{
		return $this->oDriver->putFile($oAccount, $iStorageType, $sKey, $rSource);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param string $sSource
	 *
	 * @return bool
	 */
	public function moveUploadedFile(\CAccount $oAccount, $iStorageType, $sKey, $sSource)
	{
		return $this->oDriver->moveUploadedFile($oAccount, $iStorageType, $sKey, $sSource);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return string | bool
	 */
	public function get(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->get($oAccount, $iStorageType, $sKey);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return resource | bool
	 */
	public function getFile(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->getFile($oAccount, $iStorageType, $sKey);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function clear(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->clear($oAccount, $iStorageType, $sKey);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return int | bool
	 */
	public function fileSize(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->fileSize($oAccount, $iStorageType, $sKey);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function isFileExists(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->isFileExists($oAccount, $iStorageType, $sKey);
	}
}
