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
	public function CreateMin($sHashID, $aParams)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->CreateMin($sHashID, $aParams);
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
	public function GetMinByID($sHashID)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetMinByID($sHashID);
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
	public function GetMinByHash($sHash)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetMinByHash($sHash);
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
	public function DeleteMinByID($sHashID)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->DeleteMinByID($sHashID);
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
	public function DeleteMinByHash($sHash)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->DeleteMinByHash($sHash);
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
	 * @param string $sNewHashID = null
	 *
	 * @return bool
	 */
	public function UpdateMinByID($sHashID, $aParams, $sNewHashID = null)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->UpdateMinByID($sHashID, $aParams, $sNewHashID);
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
	 * @param string $sNewHashID = null
	 *
	 * @return bool
	 */
	public function UpdateMinByHash($sHash, $aParams, $sNewHashID = null)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->UpdateMinByHash($sHash, $aParams, $sNewHashID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}
}
