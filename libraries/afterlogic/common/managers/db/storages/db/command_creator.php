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
class CApiDbCommandCreator extends api_CommandCreator
{
}

/**
 * @package Db
 * @subpackage Storages
 */
class CApiDbCommandCreatorMySQL extends CApiDbCommandCreator
{
	/**
	 * @param string $sName
	 *
	 * @return string
	 */
	public function createDatabase($sName)
	{
		$oSql = 'CREATE DATABASE %s';
		return sprintf($oSql, $this->escapeColumn($sName));
	}
}

/**
 * @package Db
 * @subpackage Storages
 */
class CApiDbCommandCreatorPostgreSQL extends CApiDbCommandCreator
{
	/**
	 * @param string $sName
	 *
	 * @return string
	 */
	public function createDatabase($sName)
	{
		$oSql = 'CREATE DATABASE %s';
		return sprintf($oSql, $this->escapeColumn($sName));
	}
}
