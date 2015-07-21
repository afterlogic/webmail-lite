<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiDbManager class summary
 *
 * @package Db
 */
class CApiDbManager extends AApiManagerWithStorage
{
	/**
	 * Creates a new instance of the object.
	 *
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
	public function testConnection()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->testConnection();
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
	public function createDatabase(&$sError)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->createDatabase();
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
	 * @param mixed $fVerboseCallback Default value is **null**.
	 *
	 * @return bool
	 */
	public function syncTables($fVerboseCallback = null)
	{
		$fVerboseCallback = (null === $fVerboseCallback) ? 'fNullCallback' : $fVerboseCallback;

		$bResult = false;
		try
		{
			$bResult = $this->oStorage->syncTables($fVerboseCallback);
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
	public function isAUsersTableExists()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->isAUsersTableExists();
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
	public function createTables()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->createTables();
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param bool $bAddDropTable Default value is **false**.
	 *
	 * @return string
	 */
	public function getSqlSchemaAsString($bAddDropTable = false)
	{
		$sResult = '';
		try
		{
			$sResult = $this->oStorage->getSqlSchemaAsString($bAddDropTable);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $sResult;
	}

	/**
	 * @param bool $bAddDropTable Default value is **false**.
	 *
	 * @return array
	 */
	public function getSqlSchemaAsArray($bAddDropTable = false)
	{
		$aResult = array();
		try
		{
			$aResult = $this->oStorage->getSqlSchemaAsArray($bAddDropTable);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param bool $bAddDropFunction Default value is **false**.
	 *
	 * @return array
	 */
	public function getSqlFunctionsAsArray($bAddDropFunction = false)
	{
		$aResult = array();
		try
		{
			$aResult = $this->oStorage->getSqlFunctionsAsArray($bAddDropFunction);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}
}