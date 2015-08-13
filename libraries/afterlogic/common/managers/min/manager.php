<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiMinManager class summary
 *
 * @package Min
 */
class CApiMinManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('min', $oManager, $sForcedStorage);
	}

	/**
	 * @param string $sHashID
	 * @param array $aParams
	 *
	 * @return string|bool
	 */
	public function createMin($sHashID, $aParams)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->createMin($sHashID, $aParams);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param string $sHashID
	 *
	 * @return array|bool
	 */
	public function getMinByID($sHashID)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getMinByID($sHashID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param string $sHash
	 *
	 * @return array|bool
	 */
	public function getMinByHash($sHash)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getMinByHash($sHash);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param string $sHashID
	 *
	 * @return bool
	 */
	public function deleteMinByID($sHashID)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->deleteMinByID($sHashID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param string $sHash
	 *
	 * @return bool
	 */
	public function deleteMinByHash($sHash)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->deleteMinByHash($sHash);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param string $sHashID
	 * @param array $aParams
	 * @param string $sNewHashID Default value is **null**
	 *
	 * @return bool
	 */
	public function updateMinByID($sHashID, $aParams, $sNewHashID = null)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->updateMinByID($sHashID, $aParams, $sNewHashID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param string $sHash
	 * @param array $aParams
	 * @param string $sNewHashID Default value is **null**
	 *
	 * @return bool
	 */
	public function updateMinByHash($sHash, $aParams, $sNewHashID = null)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->updateMinByHash($sHash, $aParams, $sNewHashID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}
}
