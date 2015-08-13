<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Twofactorauth
 * @subpackage Storages
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
	public function getAccounts($iIdAccount)
	{
	
	}	
	
	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return string
	 */
	public function getAccount($iIdAccount, $iType)
	{
	
	}
	
	/**
	 * @param string $sIdAccount
	 * @param int $iType
	 * @return \CAccount
	 */
	public function getAccountById($sIdAccount, $iType)
	{

	}

    /**
     * @param CAccount $oObject
     * @param $sAuthType
     * @param $iDataType
     * @param $sDataValue
     */
	public function createAccount(CAccount &$oObject, $sAuthType, $iDataType, $sDataValue)
	{
		
	}

    /**
     * @param CAccount $oObject
     * @param $sAuthType
     * @param $iDataType
     * @param $sDataValue
     */
	public function updateAccount(CAccount &$oObject, $sAuthType, $iDataType, $sDataValue)
	{

	}
	
	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return bool
	 */
	public function deleteAccount($iIdAccount, $iType)
	{
		
	}

    /**
     * @param $iIdAccount
     */
	public function deleteAccountByAccountId($iIdAccount)
	{

	}	
	
	/**
	 * @param int $iType
	 * @param string $sIdAccount
	 * @return string
	 */
	public function isAccountExists($iType, $sIdAccount)
	{
		
	}	
}

