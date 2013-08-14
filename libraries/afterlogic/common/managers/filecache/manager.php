<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
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
	 * @param CAccount $oAccount
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return bool
	 */
	public function Put(CAccount $oAccount, $sKey, $sValue)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->Put($oAccount, $sKey, $sValue);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
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
		try
		{
			$bResult = $this->oStorage->PutFile($oAccount, $sKey, $rSource);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
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
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->MoveUploadedFile($oAccount, $sKey, $sSource);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return string | bool
	 */
	public function Get(CAccount $oAccount, $sKey)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->Get($oAccount, $sKey);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
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
		try
		{
			$mResult = $this->oStorage->GetFile($oAccount, $sKey);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
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
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->Clear($oAccount, $sKey);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return int | bool
	 */
	public function FileSize(CAccount $oAccount, $sKey)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->FileSize($oAccount, $sKey);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function FileExists(CAccount $oAccount, $sKey)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->FileExists($oAccount, $sKey);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function GenerateFullFilePath(CAccount $oAccount, $sKey)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->GenerateFullFilePath($oAccount, $sKey);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}
}
