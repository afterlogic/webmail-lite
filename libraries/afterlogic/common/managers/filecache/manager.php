<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiFilecacheManager class summary
 *
 * @package Filecache
 */
class CApiFilecacheManager extends AApiManagerWithStorage
{
	/**
	 * Creates a new instance of the object.
	 *
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('filecache', $oManager, $sForcedStorage);
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
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->put($oAccount, $sKey, $sValue, $sFileSuffix, $sFolder);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
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
		try
		{
			$bResult = $this->oStorage->putFile($oAccount, $sKey, $rSource, $sFileSuffix, $sFolder);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
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
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->moveUploadedFile($oAccount, $sKey, $sSource, $sFileSuffix, $sFolder);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
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
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->get($oAccount, $sKey, $sFileSuffix, $sFolder);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
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
		try
		{
			$mResult = $this->oStorage->getFile($oAccount, $sKey, $sFileSuffix, $sFolder);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
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
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getTempFile($oAccount, $sTempName, $sMode);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
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
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->clear($oAccount, $sKey, $sFileSuffix, $sFolder);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
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
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->fileSize($oAccount, $sKey, $sFileSuffix, $sFolder);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
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
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->isFileExists($oAccount, $sKey, $sFileSuffix, $sFolder);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sKey
	 * @param string $sFileSuffix Default value is empty string.
	 * @param string $sFolder Default value is empty string.
	 *
	 * @return bool
	 */
	public function generateFullFilePath($oAccount, $sKey, $sFileSuffix = '', $sFolder = '')
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->generateFullFilePath($oAccount, $sKey, $sFileSuffix, $sFolder);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function gc()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->gc();
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}
}
