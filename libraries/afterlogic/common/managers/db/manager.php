<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Db
 */
class CApiDbManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('db', $oManager, $sForcedStorage);

		$this->inc('classes.enum');
		$this->inc('classes.sql');
	}

	/**
	 * @return bool
	 */
	public function TestConnection()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->TestConnection();
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
	public function TryToCreateDatabase(&$sError)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->TryToCreateDatabase();
		}
		catch (CApiDbException $oException)
		{
			$sError = $oException->getMessage();
		}
		catch (CApiBaseException $oException)
		{
			$sError = $oException->getMessage();
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param mixed $fVerboseCallback = null
	 * @return bool
	 */
	public function SyncTables($fVerboseCallback = null)
	{
		$fVerboseCallback = (null === $fVerboseCallback) ? 'fNullCallback' : $fVerboseCallback;

		$bResult = false;
		try
		{
			$bResult = $this->oStorage->SyncTables($fVerboseCallback);
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
	public function AUsersTableExists()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->AUsersTableExists();
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
	public function CreateTables()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->CreateTables();
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param bool $bAddDropTable = false
	 * @return string
	 */
	public function GetSqlSchemaAsString($bAddDropTable = false)
	{
		$sResult = '';
		try
		{
			$sResult = $this->oStorage->GetSqlSchemaAsString($bAddDropTable);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $sResult;
	}

	/**
	 * @param bool $bAddDropTable = false
	 * @return array
	 */
	public function GetSqlSchemaAsArray($bAddDropTable = false)
	{
		$aResult = array();
		try
		{
			$aResult = $this->oStorage->GetSqlSchemaAsArray($bAddDropTable);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param bool $bAddDropFunction = false
	 * @return array
	 */
	public function GetSqlFunctionsAsArray($bAddDropFunction = false)
	{
		$aResult = array();
		try
		{
			$aResult = $this->oStorage->GetSqlFunctionsAsArray($bAddDropFunction);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}
}