<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Fetchers
 */
class CApiFetchersCommandCreator extends api_CommandCreator
{
	/**
	 * @param CAccount $oAccount
	 * @return string
	 */
	public function GetFetchers($oAccount)
	{
		$aMap = api_AContainer::DbReadKeys(CFetcher::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_fetchers WHERE %s = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(),
			$this->escapeColumn('id_acct'), $oAccount->IdAccount);
	}

	/**
	 * @param CAccount $oAccount
	 * @param int $iFetcherID
	 * @return string
	 */
	public function DeleteFetcher($oAccount, $iFetcherID)
	{
		$sSql = 'DELETE FROM %sawm_fetchers WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_acct'), $oAccount->IdAccount,
			$this->escapeColumn('id_fetcher'), $iFetcherID);
	}

	/**
	 * @param CAccount $oAccount
	 * @param CFetcher $oFetcher
	 * @return string
	 */
	public function CreateFetcher($oAccount, $oFetcher)
	{
		$aResults = api_AContainer::DbInsertArrays($oFetcher, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_fetchers ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		
		return '';
	}

	/**
	 * @param CAccount $oAccount
	 * @param CFetcher $oFetcher
	 * @return string
	 */
	public function UpdateFetcher($oAccount, $oFetcher)
	{
		$aResult = api_AContainer::DbUpdateArray($oFetcher, $this->oHelper);

		$sSql = 'UPDATE %sawm_fetchers SET %s WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult),
			$this->escapeColumn('id_acct'), $oAccount->IdAccount,
			$this->escapeColumn('id_fetcher'), $oFetcher->IdFetcher);
	}
}

/**
 * @package Fetchers
 */
class CApiFetchersCommandCreatorMySQL extends CApiFetchersCommandCreator
{
	
}

/**
 * @package Fetchers
 */
class CApiFetchersCommandCreatorPostgreSQL extends CApiFetchersCommandCreator
{

}
