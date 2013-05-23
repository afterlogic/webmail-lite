<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Db
 */
class CApiDbCommandCreator extends api_CommandCreator
{
}

/**
 * @package Db
 */
class CApiDbCommandCreatorMySQL extends CApiDbCommandCreator
{
	/**
	 * @param string $sName
	 * @return string
	 */
	public function CreateDatabase($sName)
	{
		$oSql = 'CREATE DATABASE %s';
		return sprintf($oSql, $this->escapeColumn($sName));
	}
}
