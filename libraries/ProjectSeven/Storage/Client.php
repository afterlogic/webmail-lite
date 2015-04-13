<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace ProjectSeven\Storage;

/**
 * @category ProjectSeven
 * @package Storage
 */
class Client
{
	/**
	 * @var \ProjectSeven\Storage\Drivers\Files
	 */
	protected $oDriver;

	/**
	 * @return void
	 */
	public function __construct()
	{
		// TODO hc
		$this->oDriver = new \ProjectSeven\Storage\Drivers\Files(\CApi::DataPath());
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $iStorageType
	 * @param string $sValue
	 *
	 * @return bool
	 */
	public function Put(\CAccount $oAccount, $iStorageType, $sKey, $sValue)
	{
		return $this->oDriver->Put($oAccount, $iStorageType, $sKey, $sValue);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param resource $rSource
	 *
	 * @return bool
	 */
	public function PutFile(\CAccount $oAccount, $iStorageType, $sKey, $rSource)
	{
		return $this->oDriver->PutFile($oAccount, $iStorageType, $sKey, $rSource);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param string $sSource
	 *
	 * @return bool
	 */
	public function MoveUploadedFile(\CAccount $oAccount, $iStorageType, $sKey, $sSource)
	{
		return $this->oDriver->MoveUploadedFile($oAccount, $iStorageType, $sKey, $sSource);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return string | bool
	 */
	public function Get(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->Get($oAccount, $iStorageType, $sKey);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return resource | bool
	 */
	public function GetFile(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->GetFile($oAccount, $iStorageType, $sKey);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function Clear(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->Clear($oAccount, $iStorageType, $sKey);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return int | bool
	 */
	public function FileSize(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->FileSize($oAccount, $iStorageType, $sKey);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function FileExists(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return $this->oDriver->FileExists($oAccount, $iStorageType, $sKey);
	}
}
