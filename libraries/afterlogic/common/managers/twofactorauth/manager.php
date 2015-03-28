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
class CApiTwofactorauthManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedAuthentication = '')
	{
		parent::__construct('twofactorauth', $oManager, $sForcedAuthentication);
		$this->inc('classes.twofactorauth');
	}
	
	/**
	 * @param int $iAccountId
	 * @param string $sAuthType
	 * @return \CAccount
	 */
	public function GetAccount($iAccountId, $sAuthType)
	{
		$oAccount = null;
		try
		{
			$oAccount = $this->oStorage->GetAccount($iAccountId, $sAuthType);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oAccount;
	}	
	
	/**
	 * @param string $iAccountId
	 * @param string $sAuthType
	 * @return \CAccount
	 */
	public function GetAccountById($iAccountId, $sAuthType)
	{
		$oAccount = null;
		try
		{
			$oAccount = $this->oStorage->GetAccountById($iAccountId, $sAuthType);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oAccount;
	}		
	
	/**
	 * @param int $iAccountId
	 * @return array
	 */
	public function GetAccounts($iAccountId)
	{
		$aAccounts = null;
		try
		{
			$aAccounts = $this->oStorage->GetAccounts($iAccountId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aAccounts;
	}

    /**
     * @param CAccount $oAccount
     * @param string $sAuthType
     * @param int $iDataType
     * @param string $sDataValue
     * @return bool
     */
	public function CreateAccount(\CAccount &$oAccount, $sAuthType, $iDataType, $sDataValue)
	{
		$bResult = false;
		try
		{
			if ($oAccount->Validate())
			{
				$this->oStorage->CreateAccount($oAccount, $sAuthType, $iDataType, $sDataValue);
			}
			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

    /**
     * @param CAccount $oAccount
     * @param string $sAuthType
     * @param int $iDataType
     * @param string $sDataValue
     * @return bool
     */
	public function UpdateAccount(CAccount &$oAccount, $sAuthType, $iDataType, $sDataValue)
	{
		$bResult = false;
		try
		{
			if ($oAccount->Validate())
			{
				$this->oStorage->UpdateAccount($oAccount, $sAuthType, $iDataType, $sDataValue);
			}
			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}
	
	/**
	 * @param int $iAccountId
	 * @param string $sType
	 * @return bool
	 */
	public function DeleteAccount($iAccountId, $sType)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteAccount($iAccountId, $sType);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}
	
	/**
	 * @param int $iAccountId
	 * @return bool
	 */
	public function DeleteAccountByAccountId($iAccountId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteAccountByAccountId($iAccountId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

    /**
     * @param $sType
     * @param $sAccountId
     * @return bool
     */
	public function AccountExists($sType, $sAccountId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->AccountExists($sType, $sAccountId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;		
	}	
}
