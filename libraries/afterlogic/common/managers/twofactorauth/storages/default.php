<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package twofactorauth
 */
class CApiTwofactorauthStorage extends AApiManagerStorage
{

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct($sAuthName, CApiGlobalManager &$oManager)
	{
		parent::__construct('twofactorauth', $sAuthName, $oManager);
	}
	
	/**
	 * @param int $iIdAccount
	 * @return array
	 */
	public function GetAccounts($iIdAccount)
	{
	
	}	
	
	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return string
	 */
	public function GetAccount($iIdAccount, $iType)
	{
	
	}
	
	/**
	 * @param string $sIdAccount
	 * @param int $iType
	 * @return \CAccount
	 */
	public function GetAccountById($sIdAccount, $iType)
	{

	}

    /**
     * @param CAccount $oObject
     * @param $sAuthType
     * @param $iDataType
     * @param $sDataValue
     */
	public function CreateAccount(CAccount &$oObject, $sAuthType, $iDataType, $sDataValue)
	{
		
	}

    /**
     * @param CAccount $oObject
     * @param $sAuthType
     * @param $iDataType
     * @param $sDataValue
     */
	public function UpdateAccount(CAccount &$oObject, $sAuthType, $iDataType, $sDataValue)
	{

	}
	
	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return bool
	 */
	public function DeleteAccount($iIdAccount, $iType)
	{
		
	}

    /**
     * @param $iIdAccount
     */
	public function DeleteAccountByAccountId($iIdAccount)
	{

	}	
	
	/**
	 * @param int $iType
	 * @param string $sIdAccount
	 * @return string
	 */
	public function AccountExists($iType, $sIdAccount)
	{
		
	}	
}

