<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 * 
 */

/**
 * @package Db
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
	public function TestConnection()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function TryToCreateDatabase()
	{
		return false;
	}

	/**
	 * @param mixed $fVerboseCallback
	 * @return bool
	 */
	public function SyncTables($fVerboseCallback)
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function AUsersTableExists()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function CreateTables()
	{
		return false;
	}

	/**
	 * @param bool $bAddDropTable = false
	 * @return string
	 */
	public function GetSqlSchemaAsString($bAddDropTable = false)
	{
		return '';
	}

	/**
	 * @param bool $bAddDropTable = false
	 * @return array
	 */
	public function GetSqlSchemaAsArray($bAddDropTable = false)
	{
		return array();
	}

	/**
	 * @param bool $bAddDropFunction = false
	 * @return array
	 */
	public function GetSqlFunctionsAsArray($bAddDropFunction = false)
	{
		return array();
	}
}