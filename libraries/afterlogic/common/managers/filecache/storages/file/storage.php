<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Filecache
 * @subpackage Storages
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
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return bool
	 */
	public function put($oAccount, $sKey, $sValue, $sFileSuffix = '', $sFolder = '')
	{
		return false !== @file_put_contents(
			$this->generateFileName($oAccount, $sKey, true, $sFileSuffix, $sFolder), $sValue);
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param resource $rSource
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return bool
	 */
	public function putFile($oAccount, $sKey, $rSource, $sFileSuffix = '', $sFolder = '')
	{
		$bResult = false;
		if ($rSource)
		{
			$rOpenOutput = @fopen($this->generateFileName($oAccount, $sKey, true, $sFileSuffix, $sFolder), 'w+b');
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
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return bool
	 */
	public function moveUploadedFile($oAccount, $sKey, $sSource, $sFileSuffix = '', $sFolder = '')
	{
		return @move_uploaded_file($sSource,
			$this->generateFileName($oAccount, $sKey, true, $sFileSuffix, $sFolder));
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return string|bool
	 */
	public function get($oAccount, $sKey, $sFileSuffix = '', $sFolder = '')
	{
		return @file_get_contents($this->generateFileName($oAccount, $sKey, false, $sFileSuffix, $sFolder));
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return resource|bool
	 */
	public function getFile($oAccount, $sKey, $sFileSuffix = '', $sFolder = '')
	{
		$mResult = false;
		$sFileName = $this->generateFileName($oAccount, $sKey, false, $sFileSuffix, $sFolder);
		if (@file_exists($sFileName))
		{
			$mResult = @fopen($sFileName, 'rb');
		}
		return $mResult;
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sTempName
	 * @param string $sMode Default value is empty string.
	 *
	 * @return resource|bool
	 */
	public function getTempFile($oAccount, $sTempName, $sMode = '')
	{
		return @fopen($this->generateFileName($oAccount, $sTempName, true), $sMode);
	}	
	
	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return bool
	 */
	public function clear($oAccount, $sKey, $sFileSuffix = '', $sFolder = '')
	{
		$mResult = false;
		$sFileName = $this->generateFileName($oAccount, $sKey, false, $sFileSuffix, $sFolder);
		if (@file_exists($sFileName))
		{
			$mResult = @unlink($sFileName);
		}
		return $mResult;
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return int|bool
	 */
	public function fileSize($oAccount, $sKey, $sFileSuffix = '', $sFolder = '')
	{
		return @filesize($this->generateFileName($oAccount, $sKey, false, $sFileSuffix));
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return bool
	 */
	public function isFileExists($oAccount, $sKey, $sFileSuffix = '', $sFolder = '')
	{
		return @file_exists($this->generateFileName($oAccount, $sKey, false, $sFileSuffix, $sFolder));
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param bool $bMkDir Default value is **false**.
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @throws \ProjectCore\Exceptions\Exception
	 *
	 * @return string
	 */
	protected function generateFileName($oAccount, $sKey, $bMkDir = false, $sFileSuffix = '', $sFolder = '')
	{
		$sEmailMd5 = md5(strtolower($oAccount->Email));

		$sKeyPath = md5($sKey);
		$sKeyPath = substr($sKeyPath, 0, 2).'/'.$sKeyPath;
		if (!empty($sFolder))
		{
			$sKeyPath = $sFolder . '/' . $sKeyPath;
		}
		$sFilePath = $this->sDataPath.'/temp/.cache/'.substr($sEmailMd5, 0, 2).'/'.$sEmailMd5.'/'.$sKeyPath.$sFileSuffix;
		if ($bMkDir && !@is_dir(dirname($sFilePath)))
		{
			if (!@mkdir(dirname($sFilePath), 0777, true))
			{
				throw new \ProjectCore\Exceptions\Exception('Can\'t make storage directory "'.$sFilePath.'"');
			}
		}

		return $sFilePath;
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return string
	 */
	public function generateFullFilePath($oAccount, $sKey, $sFileSuffix = '', $sFolder = '')
	{
		return $this->generateFileName($oAccount, $sKey, true, $sFileSuffix, $sFolder);
	}

	/**
	 * @return bool
	 */
	public function gc()
	{
		return \MailSo\Base\Utils::RecTimeDirRemove($this->sDataPath.'/temp/.cache/', 60 * 60 * 6, time());
	}
}