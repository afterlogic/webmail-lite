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
class CApiTwofactorauthDbStorage extends CApiTwofactorauthStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * @var CApiDomainsCommandCreator
	 */
	protected $oCommandCreator;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('db', $oManager);

		$this->oConnection =& $oManager->GetConnection();
		$this->oCommandCreator =& $oManager->GetCommandCreator(
			$this, array(
				EDbType::MySQL => 'CApiTwofactorauthCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiTwofactorauthCommandCreatorPostgreSQL'
			)
		);
	}
	
	/**
	 * @param string $sSql
	 * @return CTwofactorauth
	 */
	protected function getAccountBySql($sSql)
	{
		$oAccount = null;
		if ($this->oConnection->Execute($sSql))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oAccount = new CTwofactorauth();
				$oAccount->InitByDbRow($oRow);
			}
			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $oAccount;
	}	
	
	/**
	 * @param string $sIdAccount
	 * @param string $sType
	 * @return CTwofactorauth
	 */
	public function GetAccountById($sIdAccount, $sType)
	{
		return $this->getAccountBySql($this->oCommandCreator->GetAccountById($sIdAccount, $sType));
	}
	
	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 * @return CTwofactorauth
	 */
	public function GetAccount($iIdAccount, $sType)
	{
		return $this->getAccountBySql($this->oCommandCreator->GetAccount((int) $iIdAccount, $sType));
	}	
	
	/**
	 * @param int $iIdAccount
	 * @return array
	 */
	public function GetAccounts($iIdAccount)
	{
		$aAccounts = array();
		if ($this->oConnection->Execute($this->oCommandCreator->GetAccounts((int) $iIdAccount)))
		{
			$oRow = null;
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oAccount = new \CTwofactorauth();
				$oAccount->InitByDbRow($oRow);
				$aAccounts[] = $oAccount;
			}
		}

		$this->throwDbExceptionIfExist();
		return $aAccounts;
	}

    /**
     * @param CAccount $oAccount
     * @param $sAuthType
     * @param $iDataType
     * @param $sDataValue
     * @return bool
     * @throws CApiBaseException
     */
	public function CreateAccount(\CAccount &$oAccount, $sAuthType, $iDataType, $sDataValue)
	{
		$bResult = false;

        $oObject = new CTwofactorauth();
        $oObject->AccountId = $oAccount->IdAccount;
        $oObject->AuthType = $sAuthType;
        $oObject->DataType = $iDataType;
        $oObject->DataValue = $sDataValue;

		if ($this->oConnection->Execute($this->oCommandCreator->CreateAccount($oObject)))
		{
			$oAccount->Id = $this->oConnection->GetLastInsertId('twofa_accounts', 'id');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

    /**
     * @param CAccount $oAccount
     * @param $sAuthType
     * @param $iDataType
     * @param $sDataValue
     * @return bool
     * @throws CApiBaseException
     */
	public function UpdateAccount(\CAccount &$oAccount, $sAuthType, $iDataType, $sDataValue)
	{
        $oObject = new CTwofactorauth();
        $oObject->AccountId = $oAccount->IdAccount;
        $oObject->AuthType = $sAuthType;
        $oObject->DataType = $iDataType;
        $oObject->DataValue = $sDataValue;

		$bResult = $this->oConnection->Execute($this->oCommandCreator->UpdateAccount($oObject));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 * @return bool
	 */
	public function DeleteAccount($iIdAccount, $sType)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->DeleteAccount($iIdAccount, $sType));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param int $iIdAccount
	 * @return bool
	 */
	public function DeleteAccountByAccountId($iIdAccount)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->DeleteAccountByAccountId($iIdAccount));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param string $sType
	 * @param string $sIdAccount
	 * @return string
	 */
	public function AccountExists($sType, $sIdAccount)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->AccountExists($sType, $sIdAccount)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$bResult = 0 < (int) $oRow->account_count;
			}

			$this->oConnection->FreeResult();
		}
		$this->throwDbExceptionIfExist();
		return $bResult;
	}	

}