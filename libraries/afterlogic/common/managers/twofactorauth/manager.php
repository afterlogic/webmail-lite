<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Twofactorauth
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
	public function getAccount($iAccountId, $sAuthType)
	{
		$oAccount = null;
		try
		{
			$oAccount = $this->oStorage->getAccount($iAccountId, $sAuthType);
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
	public function getAccountById($iAccountId, $sAuthType)
	{
		$oAccount = null;
		try
		{
			$oAccount = $this->oStorage->getAccountById($iAccountId, $sAuthType);
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
	public function getAccounts($iAccountId)
	{
		$aAccounts = null;
		try
		{
			$aAccounts = $this->oStorage->getAccounts($iAccountId);
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
	public function createAccount(\CAccount &$oAccount, $sAuthType, $iDataType, $sDataValue)
	{
		$bResult = false;
		try
		{
			if ($oAccount->isValid())
			{
				$this->oStorage->createAccount($oAccount, $sAuthType, $iDataType, $sDataValue);
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
	public function updateAccount(CAccount &$oAccount, $sAuthType, $iDataType, $sDataValue)
	{
		$bResult = false;
		try
		{
			if ($oAccount->isValid())
			{
				$this->oStorage->updateAccount($oAccount, $sAuthType, $iDataType, $sDataValue);
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
	public function deleteAccount($iAccountId, $sType)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteAccount($iAccountId, $sType);
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
	public function deleteAccountByAccountId($iAccountId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteAccountByAccountId($iAccountId);
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
	public function isAccountExists($sType, $sAccountId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->isAccountExists($sType, $sAccountId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;		
	}	
}
