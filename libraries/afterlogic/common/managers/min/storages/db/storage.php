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
class CApiMinDbStorage extends CApiMinStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * @var CApiMinCommandCreatorMySQL
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
				EDbType::MySQL => 'CApiMinCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiMinCommandCreatorPostgreSQL'
			)
		);
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
		$sNewMin = '';

		if (is_string($sHashID) && 0 < strlen($sHashID) && false !== $this->GetMinByID($sHashID))
		{
			return false;
		}

		while (true)
		{
			$sNewMin = api_Utils::GenerateShortHashString(10);
			if (false === $this->GetMinByHash($sNewMin))
			{
				break;
			}
		}

		if (0 < strlen($sNewMin))
		{
			$aParams['__hash_id__'] = $sHashID;
			$aParams['__hash__'] = $sNewMin;
			$aParams['__time__'] = time();
			$aParams['__time_update__'] = time();

			if ($this->oConnection->Execute($this->oCommandCreator->CreateMin($sNewMin, md5($sHashID), @\json_encode($aParams))))
			{
				$mResult = $sNewMin;
			}
		}

		$this->throwDbExceptionIfExist();
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
		if (is_string($sHashID) && 0 < strlen($sHashID))
		{
			$aPrevParams = $this->GetMinByID($sHashID);
			if (isset($aPrevParams['__hash__']))
			{
				$aParams['__hash__'] = $aPrevParams['__hash__'];
			}
			if (!empty($sNewHashID))
			{
				$aParams['__hash_id__'] = $sNewHashID;
			}
			if (isset($aPrevParams['__time__']))
			{
				$aParams['__time__'] = $aPrevParams['__time__'];
			}

			$aParams['__time_update__'] = time();
			$mResult = $this->oConnection->Execute($this->oCommandCreator->UpdateMinByID(md5($sHashID), @\json_encode($aParams),
				!empty($sNewHashID) ? md5($sNewHashID) : null));
		}

		$this->throwDbExceptionIfExist();
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
		if (is_string($sHash) && 0 < strlen($sHash) && is_array($aParams))
		{
			$aPrevParams = $this->GetMinByHash($sHash);
			if (isset($aPrevParams['__hash_id__']))
			{
				$aParams['__hash_id__'] = $aPrevParams['__hash_id__'];
			}
			if (!empty($sNewHashID))
			{
				$aParams['__hash_id__'] = $sNewHashID;
			}
			if (isset($aPrevParams['__time__']))
			{
				$aParams['__time__'] = $aPrevParams['__time__'];
			}

			$aParams['__time_update__'] = time();
			$mResult = $this->oConnection->Execute($this->oCommandCreator->UpdateMinByHash($sHash, @\json_encode($aParams),
				!empty($sNewHashID) ? md5($sNewHashID) : null));
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @return array|bool
	 */
	private function parseGetMinDbResult()
	{
		$mResult = false;
		$oRow = $this->oConnection->GetNextRecord();
		if ($oRow && !empty($oRow->data))
		{
			$aData = @\json_decode($oRow->data, true);
			if (is_array($aData) && 0 < count($aData))
			{
				$mResult = $aData;
			}
		}
		$this->oConnection->FreeResult();

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

		if (is_string($sHashID) && 0 < strlen($sHashID) && $this->oConnection->Execute($this->oCommandCreator->GetMinByID(md5($sHashID))))
		{
			$mResult = $this->parseGetMinDbResult();
		}

		$this->throwDbExceptionIfExist();
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

		if (is_string($sHash) && 0 < strlen($sHash) && $this->oConnection->Execute($this->oCommandCreator->GetMinByHash($sHash)))
		{
			$mResult = $this->parseGetMinDbResult();
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}
	
	/**
	 * @param string $sHashID
	 *
	 * @return array|bool
	 */
	public function DeleteMinByID($sHashID)
	{
		$mResult = false;
		if (is_string($sHashID) && 0 < strlen($sHashID))
		{
			$mResult = $this->oConnection->Execute($this->oCommandCreator->DeleteMinByID(md5($sHashID)));
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}
	
	/**
	 * @param string $sHash
	 *
	 * @return array|bool
	 */
	public function DeleteMinByHash($sHash)
	{
		$mResult = false;
		if (is_string($sHash) && 0 < strlen($sHash))
		{
			$mResult = $this->oConnection->Execute($this->oCommandCreator->DeleteMinByHash($sHash));
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}
}
