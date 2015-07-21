<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Db
 * @subpackage Storages
 */
class CApiDbStorage extends AApiManagerStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct($sStorageName, CApiGlobalManager &$oManager)
	{
		parent::__construct('db', $sStorageName, $oManager);
	}

	/**
	 * @return bool
	 */
	public function testConnection()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function createDatabase()
	{
		return false;
	}

	/**
	 * @param mixed $fVerboseCallback
	 *
	 * @return bool
	 */
	public function syncTables($fVerboseCallback)
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function isAUsersTableExists()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function createTables()
	{
		return false;
	}

	/**
	 * @param bool $bAddDropTable Default value is **false**.
	 *
	 * @return string
	 */
	public function getSqlSchemaAsString($bAddDropTable = false)
	{
		return '';
	}

	/**
	 * @param bool $bAddDropTable Default value is **false**.
	 *
	 * @return array
	 */
	public function getSqlSchemaAsArray($bAddDropTable = false)
	{
		return array();
	}

	/**
	 * @param bool $bAddDropFunction Default value is **false**.
	 *
	 * @return array
	 */
	public function getSqlFunctionsAsArray($bAddDropFunction = false)
	{
		return array();
	}
}