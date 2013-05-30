<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Filestorage
 */
class CApiFilestorageSabredavStorage extends CApiFilestorageStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('sabredav', $oManager);
	}
	
	public function GetPrivateFiles($sPath)
	{
		
	}
	
	public function GetCorporateFiles($sPath)
	{
		
	}
}

