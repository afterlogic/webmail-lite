<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Filecache
 */
class CApiFilecacheFileStorage extends CApiFilecacheStorage
{
	/**
	 * @var string
	 */
	protected $sDataPath;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('file', $oManager);

		$this->sDataPath = rtrim(trim(CApi::DataPath()), '\\/');
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return bool
	 */
	public function Put(CAccount $oAccount, $sKey, $sValue)
	{
		return false !== @file_put_contents(
			$this->generateFileName($oAccount, $sKey, true), $sValue);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 * @param resource $rSource
	 *
	 * @return bool
	 */
	public function PutFile(CAccount $oAccount, $sKey, $rSource)
	{
		$bResult = false;
		if ($rSource)
		{
			$rOpenOutput = @fopen($this->generateFileName($oAccount, $sKey, true), 'w+b');
			if ($rOpenOutput)
			{
				$bResult = (false !== \MailSo\Base\Utils::MultipleStreamWriter($rSource, array($rOpenOutput)));
				@fclose($rOpenOutput);
			}
		}
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 * @param string $sSource
	 *
	 * @return bool
	 */
	public function MoveUploadedFile(CAccount $oAccount, $sKey, $sSource)
	{
		return @move_uploaded_file($sSource,
			$this->generateFileName($oAccount, $sKey, true));
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return string | bool
	 */
	public function Get(CAccount $oAccount, $sKey)
	{
		return @file_get_contents($this->generateFileName($oAccount, $sKey));
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return resource | bool
	 */
	public function GetFile(CAccount $oAccount, $sKey)
	{
		$mResult = false;
		$sFileName = $this->generateFileName($oAccount, $sKey);
		if (@file_exists($sFileName))
		{
			$mResult = @fopen($sFileName, 'rb');
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function Clear(CAccount $oAccount, $sKey)
	{
		return @unlink($this->generateFileName($oAccount, $sKey));
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return int | bool
	 */
	public function FileSize(CAccount $oAccount, $sKey)
	{
		return @filesize($this->generateFileName($oAccount, $sKey));
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function FileExists(CAccount $oAccount, $sKey)
	{
		return @file_exists($this->generateFileName($oAccount, $sKey));
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 * @param bool $bMkDir = false
	 *
	 * @return string
	 */
	protected function generateFileName(CAccount $oAccount, $sKey, $bMkDir = false)
	{
		$sEmailMd5 = md5(strtolower($oAccount->Email));

		$sKeyPath = md5($sKey);
		$sKeyPath = substr($sKeyPath, 0, 2).'/'.$sKeyPath;

		$sFilePath = $this->sDataPath.'/temp/.cache/'.substr($sEmailMd5, 0, 2).'/'.$sEmailMd5.'/'.$sKeyPath;
		if ($bMkDir && !@is_dir(dirname($sFilePath)))
		{
			if (!@mkdir(dirname($sFilePath), 0777, true))
			{
				throw new \RainLoop\Exceptions\Exception('Can\'t make storage directory "'.$sFilePath.'"');
			}
		}

		return $sFilePath;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return string
	 */
	public function GenerateFullFilePath(CAccount $oAccount, $sKey)
	{
		return $this->generateFileName($oAccount, $sKey);
	}
}