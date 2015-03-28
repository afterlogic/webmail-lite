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
class CApiFilecacheManager extends AApiManagerWithStorage
{
	/**
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
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function Put($oAccount, $sKey, $sValue, $sFileSuffix = '')
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->Put($oAccount, $sKey, $sValue, $sFileSuffix);
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
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function PutFile($oAccount, $sKey, $rSource, $sFileSuffix = '')
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->PutFile($oAccount, $sKey, $rSource, $sFileSuffix);
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
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function MoveUploadedFile($oAccount, $sKey, $sSource, $sFileSuffix = '')
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->MoveUploadedFile($oAccount, $sKey, $sSource, $sFileSuffix);
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
	 * @param string $sFileSuffix = ''
	 *
	 * @return string | bool
	 */
	public function Get($oAccount, $sKey, $sFileSuffix = '')
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->Get($oAccount, $sKey, $sFileSuffix);
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
	 * @param string $sFileSuffix = ''
	 *
	 * @return resource | bool
	 */
	public function GetFile($oAccount, $sKey, $sFileSuffix = '')
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetFile($oAccount, $sKey, $sFileSuffix);
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
	 * @param string $sMode = ''
	 *
	 * @return resource | bool
	 */
	public function GetTempFile($oAccount, $sTempName, $sMode = '')
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetTempFile($oAccount, $sTempName, $sMode);
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
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function Clear($oAccount, $sKey, $sFileSuffix = '')
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->Clear($oAccount, $sKey, $sFileSuffix);
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
	 * @param string $sFileSuffix = ''
	 *
	 * @return int | bool
	 */
	public function FileSize($oAccount, $sKey, $sFileSuffix = '')
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->FileSize($oAccount, $sKey, $sFileSuffix);
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
	 *
	 * @return bool
	 */
	public function FileExists($oAccount, $sKey, $sFileSuffix = '')
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->FileExists($oAccount, $sKey, $sFileSuffix);
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
	 * @param string $sFileSuffix = ''
	 *
	 * @return bool
	 */
	public function GenerateFullFilePath($oAccount, $sKey, $sFileSuffix = '')
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->GenerateFullFilePath($oAccount, $sKey, $sFileSuffix);
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
	public function GC()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->GC();
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}
}
