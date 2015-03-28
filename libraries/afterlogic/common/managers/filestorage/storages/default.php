<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
	
	/**
	 * @param CAccount $oAccount
	 */
	public function Init($oAccount)
	{
	
	}
	
	public function FileExists($oAccount, $iType, $sPath, $sName)
	{
		return false;
	}
	
	public function GetFileInfo($oAccount, $iType, $sPath, $sName)
	{
	
	}
	
	public function GetDirectoryInfo($oAccount, $iType, $sPath)
	{
		
	}
	
	public function GetFile($oAccount, $iType, $sPath, $sName)
	{

	}

	public function GetFiles($oAccount, $iType, $sPath, $sPattern)
	{
		
	}
	
	public function CreateFolder($oAccount, $iType, $sPath, $sFolderName)
	{

	}
	
	public function CreateFile($oAccount, $iType, $sPath, $sFileName, $sData)
	{

	}
	
	public function CreateLink($oAccount, $iType, $sPath, $sLink, $sName)
	{
	
		
	}	
	
	public function Delete($oAccount, $iType, $sPath, $sName)
	{

	}

	public function Rename($oAccount, $iType, $sPath, $sName, $sNewName)
	{

	}
	
	public function Copy($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName)
	{
		
	}

	public function GetQuota($oAccount, $iType)
	{

	}
	
	public function GetNonExistingFileName($oAccount, $iType, $sPath, $sFileName)
	{
	
	}	
	
	public function ClearPrivateFiles($oAccount)
	{
		
	}

	public function ClearCorporateFiles($oAccount)
	{
		
	}
}
