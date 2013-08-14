<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Filestorage
 */
class CApiFilestorageManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('filestorage', $oManager, $sForcedStorage);
	}
	
	public function GetPrivateFiles($sPath)
	{
		
	}
	
	public function GetCorporateFiles($sPath)
	{
		
	}
}
