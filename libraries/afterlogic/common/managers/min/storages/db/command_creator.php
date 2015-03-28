<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Min
 */
class CApiMinCommandCreator extends api_CommandCreator
{
	/**
	 * @param string $sHash
	 *
	 * @return string
	 */
	public function GetMinByHash($sHash)
	{
		$sSql = 'SELECT hash_id, hash, data FROM %sawm_min WHERE hash = %s';
		
		return sprintf($sSql, $this->Prefix(), $this->escapeString($sHash));
	}
	
	/**
	 * @param string $sHashID
	 *
	 * @return string
	 */
	public function GetMinByID($sHashID)
	{
		$sSql = 'SELECT hash_id, hash, data FROM %sawm_min WHERE hash_id = %s';

		return sprintf($sSql, $this->Prefix(), $this->escapeString($sHashID));
	}
	
	/**
	 * @param string $sHash
	 *
	 * @return string
	 */
	public function DeleteMinByHash($sHash)
	{
		$sSql = 'DELETE FROM %sawm_min WHERE hash = %s';

		return sprintf($sSql, $this->Prefix(), $this->escapeString($sHash));
	}

	/**
	 * @param string $sHashID
	 *
	 * @return string
	 */
	public function DeleteMinByID($sHashID)
	{
		$sSql = 'DELETE FROM %sawm_min WHERE hash_id = %s';

		return sprintf($sSql, $this->Prefix(), $this->escapeString($sHashID));
	}

	/**
	 * @param string $sHash
	 * @param string $sHashID
	 * @param string $sEncodedParams
	 *
	 * @return string
	 */
	public function CreateMin($sHash, $sHashID, $sEncodedParams)
	{
		$sSql = 'INSERT INTO %sawm_min ( hash_id, hash, data ) VALUES ( %s, %s, %s )';

		return sprintf($sSql, $this->Prefix(), $this->escapeString($sHashID), $this->escapeString($sHash),
			$this->escapeString($sEncodedParams));
	}

	/**
	 * @param string $sHashID
	 * @param string $sEncodedParams
	 * @param string $sNewHashID = null
	 *
	 * @return string
	 */
	public function UpdateMinByID($sHashID, $sEncodedParams, $sNewHashID = null)
	{
		$sAdd = '';
		if (!empty($sNewHashID))
		{
			$sAdd = sprintf(', hash_id = %s', $this->escapeString($sNewHashID));
		}

		$sSql = 'UPDATE %sawm_min SET data = %s%s WHERE hash_id = %s';

		return sprintf($sSql, $this->Prefix(), $this->escapeString($sEncodedParams), $sAdd, $this->escapeString($sHashID));
	}
	
	/**
	 * @param string $sHash
	 * @param string $sEncodedParams
	 * @param string $sNewHashID = null
	 *
	 * @return string
	 */
	public function UpdateMinByHash($sHash, $sEncodedParams, $sNewHashID = null)
	{
		$sAdd = '';
		if (!empty($sNewHashID))
		{
			$sAdd = sprintf(', hash_id = %s', $this->escapeString($sNewHashID));
		}
		
		$sSql = 'UPDATE %sawm_min SET data = %s%s WHERE hash = %s';

		return sprintf($sSql, $this->Prefix(), $this->escapeString($sEncodedParams), $sAdd, $this->escapeString($sHash));
	}
}

/**
 * @package Min
 */
class CApiMinCommandCreatorMySQL extends CApiMinCommandCreator
{
	
}

/**
 * @package Min
 */
class CApiMinCommandCreatorPostgreSQL extends CApiMinCommandCreator
{

}
