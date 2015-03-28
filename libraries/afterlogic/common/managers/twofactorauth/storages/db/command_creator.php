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
class CApiTwofactorauthCommandCreatorMySQL extends api_CommandCreator
{
	/**
	 * @param CTwofactorauth $oObject
	 * @return string
	 */
	public function CreateAccount(\CTwofactorauth $oObject)
	{
		$aResults = api_AContainer::DbInsertArrays($oObject, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %stwofa_accounts ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CTwofactorauth $oObject
	 * @return string
	 */
	public function UpdateAccount(\CTwofactorauth $oObject)
	{
		$aResult = api_AContainer::DbUpdateArray($oObject, $this->oHelper);

		$sSql = 'UPDATE %stwofa_accounts SET %s WHERE auth_type = %s AND account_id = %s';

		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult), $this->escapeString(strtolower($oObject->AuthType)), $oObject->AccountId);
	}

	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 * @return bool
	 */
	public function DeleteAccount($iIdAccount, $sType)
	{
		$sSql = 'DELETE FROM %stwofa_accounts WHERE account_id = %d AND auth_type = %s ';

		return sprintf($sSql, $this->Prefix(), $iIdAccount, $this->escapeString(strtolower($sType)));
	}

	/**
	 * @param int $iIdAccount
	 * @return bool
	 */
	public function DeleteAccountByAccountId($iIdAccount)
	{
		$sSql = 'DELETE FROM %stwofa_accounts WHERE account_id = %d';

		return sprintf($sSql, $this->Prefix(), $iIdAccount);
	}
	/**
	 * @param string $sType
	 * @param string $sIdAccount
	 * @return string
	 */
	public function AccountExists($sType, $sIdAccount)
	{
		$sSql = 'SELECT COUNT(id) as account_count FROM %stwofa_accounts WHERE %s = %s AND %s = %s';

		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('auth_type'), $this->escapeString(strtolower($sType)),
			$this->escapeColumn('account_id'), $this->escapeString(strtolower($sIdAccount))
		);
	}

	/**
	 * @param int $iIdAccount
	 * @param string $sAuthType
	 * @return string
	 */
	public function GetAccount($iIdAccount, $sAuthType)
	{
		return $this->getAccountByWhere(sprintf('%s = %d AND %s = %s', 
				$this->escapeColumn('account_id'), $iIdAccount,
				$this->escapeColumn('auth_type'), $this->escapeString(strtolower($sAuthType))
		));
	}
	
	/**
	 * @param string $iIdAccount
	 * @param string $sAuthType
	 * @return string
	 */
	public function GetAccountById($iIdAccount, $sAuthType)
	{
		return $this->getAccountByWhere(sprintf('%s = %s AND %s = %s', 
				$this->escapeColumn('account_id'), $iIdAccount,
				$this->escapeColumn('auth_type'), $this->escapeString(strtolower($sAuthType))
		));
	}	
	
	/**
	 * @param int $iIdAccount
	 * @return string
	 */
	public function GetAccounts($iIdAccount)
	{
		return $this->getAccountByWhere(sprintf('%s = %d', 
				$this->escapeColumn('account_id'), $iIdAccount
		));
	}

	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getAccountByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CTwofactorauth::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %stwofa_accounts WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}	
}
