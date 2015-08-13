<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @internal
 * 
 * @package Filestorage
 * @subpackage Storages
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
	
	/**
	 * @param CAccount $oAccount
	 */
	public function init($oAccount)
	{
	
	}
	
	public function isFileExists($oAccount, $iType, $sPath, $sName)
	{
		return false;
	}
	
	public function getFileInfo($oAccount, $iType, $sPath, $sName)
	{
	
	}
	
	public function getDirectoryInfo($oAccount, $iType, $sPath)
	{
		
	}
	
	public function getFile($oAccount, $iType, $sPath, $sName)
	{

	}

	public function getFiles($oAccount, $iType, $sPath, $sPattern)
	{
		
	}
	
	public function createFolder($oAccount, $iType, $sPath, $sFolderName)
	{

	}
	
	public function createFile($oAccount, $iType, $sPath, $sFileName, $sData)
	{

	}
	
	public function createLink($oAccount, $iType, $sPath, $sLink, $sName)
	{
	
		
	}	
	
	public function delete($oAccount, $iType, $sPath, $sName)
	{

	}

	public function rename($oAccount, $iType, $sPath, $sName, $sNewName)
	{

	}
	
	public function copy($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName)
	{
		
	}

	public function getQuota($oAccount, $iType)
	{

	}
	
	public function getNonExistingFileName($oAccount, $iType, $sPath, $sFileName)
	{
	
	}	
	
	public function clearPrivateFiles($oAccount)
	{
		
	}

	public function clearCorporateFiles($oAccount)
	{
		
	}
}
