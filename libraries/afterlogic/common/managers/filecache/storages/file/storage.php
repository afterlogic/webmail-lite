<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sValue
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function Put($oAccount, $sKey, $sValue, $sFileSuffix = '')
	{
		return false !== @file_put_contents(
			$this->generateFileName($oAccount, $sKey, true, $sFileSuffix), $sValue);
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param resource $rSource
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function PutFile($oAccount, $sKey, $rSource, $sFileSuffix = '')
	{
		$bResult = false;
		if ($rSource)
		{
			$rOpenOutput = @fopen($this->generateFileName($oAccount, $sKey, true, $sFileSuffix), 'w+b');
			if ($rOpenOutput)
			{
				$bResult = (false !== \MailSo\Base\Utils::MultipleStreamWriter($rSource, array($rOpenOutput)));
				@fclose($rOpenOutput);
			}
		}
		return $bResult;
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sSource
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function MoveUploadedFile($oAccount, $sKey, $sSource, $sFileSuffix = '')
	{
		return @move_uploaded_file($sSource,
			$this->generateFileName($oAccount, $sKey, true, $sFileSuffix));
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix = ''
	 *
	 * @return string | bool
	 */
	public function Get($oAccount, $sKey, $sFileSuffix = '')
	{
		return @file_get_contents($this->generateFileName($oAccount, $sKey, false, $sFileSuffix));
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix = ''
	 *
	 * @return resource | bool
	 */
	public function GetFile($oAccount, $sKey, $sFileSuffix = '')
	{
		$mResult = false;
		$sFileName = $this->generateFileName($oAccount, $sKey, false, $sFileSuffix);
		if (@file_exists($sFileName))
		{
			$mResult = @fopen($sFileName, 'rb');
		}
		return $mResult;
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sTempName
	 * @param string $sMode = ''
	 *
	 * @return resource | bool
	 */
	public function GetTempFile($oAccount, $sTempName, $sMode = '')
	{
		return @fopen($this->generateFileName($oAccount, $sTempName, true), $sMode);
	}	
	
	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function Clear($oAccount, $sKey, $sFileSuffix = '')
	{
		$mResult = false;
		$sFileName = $this->generateFileName($oAccount, $sKey, false, $sFileSuffix);
		if (@file_exists($sFileName))
		{
			$mResult = @unlink($sFileName);
		}
		return $mResult;
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix = ''
	 *
	 * @return int | bool
	 */
	public function FileSize($oAccount, $sKey, $sFileSuffix = '')
	{
		return @filesize($this->generateFileName($oAccount, $sKey, false, $sFileSuffix));
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function FileExists($oAccount, $sKey, $sFileSuffix = '')
	{
		return @file_exists($this->generateFileName($oAccount, $sKey, false, $sFileSuffix));
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param bool $bMkDir = false
	 * @param string $sFileSuffix = ''
	 *
	 * @return string
	 */
	protected function generateFileName($oAccount, $sKey, $bMkDir = false, $sFileSuffix = '')
	{
		$sEmailMd5 = md5(strtolower($oAccount->Email));

		$sKeyPath = md5($sKey);
		$sKeyPath = substr($sKeyPath, 0, 2).'/'.$sKeyPath;

		$sFilePath = $this->sDataPath.'/temp/.cache/'.substr($sEmailMd5, 0, 2).'/'.$sEmailMd5.'/'.$sKeyPath.$sFileSuffix;
		if ($bMkDir && !@is_dir(dirname($sFilePath)))
		{
			if (!@mkdir(dirname($sFilePath), 0777, true))
			{
				throw new \ProjectSeven\Exceptions\Exception('Can\'t make storage directory "'.$sFilePath.'"');
			}
		}

		return $sFilePath;
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix = ''
	 *
	 * @return string
	 */
	public function GenerateFullFilePath($oAccount, $sKey, $sFileSuffix = '')
	{
		return $this->generateFileName($oAccount, $sKey, true, $sFileSuffix);
	}

	/**
	 * @return bool
	 */
	public function GC()
	{
		return \MailSo\Base\Utils::RecTimeDirRemove($this->sDataPath.'/temp/.cache/', 60 * 60 * 6, time());
	}
}