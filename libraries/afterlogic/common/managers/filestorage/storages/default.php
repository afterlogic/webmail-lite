<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Filestorage
 */
class CApiFilestorageStorage extends AApiManagerStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct($sStorageName, CApiGlobalManager &$oManager)
	{
		parent::__construct('filestorage', $sStorageName, $oManager);
	}
	
	public function GetPrivateFiles($sPath)
	{
		
	}
	
	public function GetCorporateFiles($sPath)
	{
		
	}
}
