<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace ProjectSeven\Storage\Drivers;

/**
 * @category ProjectSeven
 * @package Storage
 * @subpackage Drivers
 */
class Files implements \ProjectSeven\Storage\StorageInterface
{
	/**
	 * @var string
	 */
	protected $sDataPath;

	/**
	 * @param string $sDataPath
	 *
	 * @return void
	 */
	public function __construct($sDataPath)
	{
		$this->sDataPath = rtrim(trim($sDataPath), '\\/');
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return bool
	 */
	public function Put(\CAccount $oAccount, $iStorageType, $sKey, $sValue)
	{
		return false !== @file_put_contents(
			$this->generateFileName($oAccount, $iStorageType, $sKey, true), $sValue);
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
		$bResult = false;
		if ($rSource)
		{
			$rOpenOutput = @fopen($this->generateFileName($oAccount, $iStorageType, $sKey, true), 'w+b');
			if ($rOpenOutput)
			{
				$bResult = (false !== \MailSo\Base\Utils::MultipleStreamWriter($rSource, array($rOpenOutput)));
				@fclose($rOpenOutput);
			}
		}
		return $bResult;
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
		return @move_uploaded_file($sSource,
			$this->generateFileName($oAccount, $iStorageType, $sKey, true));
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
		return @file_get_contents($this->generateFileName($oAccount, $iStorageType, $sKey));
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
		$mResult = false;
		$sFileName = $this->generateFileName($oAccount, $iStorageType, $sKey);
		if (@file_exists($sFileName))
		{
			$mResult = @fopen($sFileName, 'rb');
		}
		return $mResult;
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
		return @unlink($this->generateFileName($oAccount, $iStorageType, $sKey));
	}

	/**
	 * @param \Account $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return int | bool
	 */
	public function FileSize(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return @filesize($this->generateFileName($oAccount, $iStorageType, $sKey));
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iType
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function FileExists(\CAccount $oAccount, $iStorageType, $sKey)
	{
		return @file_exists($this->generateFileName($oAccount, $iStorageType, $sKey));
	}

	/**
	 * @param \CAccount $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param bool $bMkDir = false
	 *
	 * @return string
	 */
	protected function generateFileName(\CAccount $oAccount, $iStorageType, $sKey, $bMkDir = false)
	{
		$sEmail = preg_replace('/[^a-z0-9\-\.@]/', '_', strtolower($oAccount->Email));

		$sTypePath = $sKeyPath = '';
		switch ($iStorageType)
		{
			case \ProjectSeven\Storage\Enumerations\StorageType::TEMP:
				$sTypePath = 'tmp';
				$sKeyPath = md5($sKey);
				$sKeyPath = substr($sKeyPath, 0, 2).'/'.$sKeyPath;
				break;
			case \ProjectSeven\Storage\Enumerations\StorageType::USER:
				$sTypePath = 'data';
				$sKeyPath = md5($sKey);
				$sKeyPath = substr($sKeyPath, 0, 2).'/'.$sKeyPath;
				break;
			case \ProjectSeven\Storage\Enumerations\StorageType::CONFIG:
				$sTypePath = 'cfg';
				$sKeyPath = preg_replace('/[^a-zA-Z0-9\/]/', '_', $sKey);
				break;
		}

		$sFilePath = $this->sDataPath.'/storage/'.$sTypePath.'/'.rtrim(substr($sEmail, 0, 2), '@').'/'.$sEmail.'/'.$sKeyPath;
		if ($bMkDir && !@is_dir(dirname($sFilePath)))
		{
			if (!@mkdir(dirname($sFilePath), 0777, true))
			{
				throw new \ProjectSeven\Exceptions\Exception('Can\'t make storage directory "'.$sFilePath.'"');
			}
		}

		return $sFilePath;
	}
}
