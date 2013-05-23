<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @static
 * @package Api
 * @subpackage Db
 */
class CDbCreator
{
	/**
	 * @var DbMySql;
	 */
	static $oDbConnector;

	/**
	 * @var DbMySql;
	 */
	static $oSlaveDbConnector;

	/**
	 * @var object;
	 */
	static $oCommandCreatorHelper;

	private function __construct() {}

	/**
	 * @return void
	 */
	public static function ClearStatic()
	{
		self::$oDbConnector = null;
		self::$oSlaveDbConnector = null;
	}

	/**
	 * @param array $aData
	 * @return CDbMySql
	 */
	public static function ConnectorFabric($aData)
	{
		$oConnector = null;
		if (isset($aData['Type']))
		{
			$iDbType = $aData['Type'];

			switch ($iDbType)
			{
				default:
				case EDbType::MySQL:
					if (isset($aData['DBHost'], $aData['DBLogin'], $aData['DBPassword'], $aData['DBName']))
					{
						CApi::Inc('common.db.mysql.mysql');

						$oConnector = new CDbMySql($aData['DBHost'], $aData['DBLogin'],
							$aData['DBPassword'], $aData['DBName']);
					}
					break;
			}
		}

		return $oConnector;
	}

	/**
	 * @param int $iDbType = EDbType::MySQL
	 * @return CMySqlHelper
	 */
	public static function CommandCreatorHelperFabric($iDbType = EDbType::MySQL)
	{
		$oHelper = null;
		switch ($iDbType)
		{
			default:
			case EDbType::MySQL:
				CApi::Inc('common.db.mysql.helper');
				$oHelper = new CMySqlHelper();
				break;
		}

		return $oHelper;
	}

	/**
	 * @param api_Settings $oSettings
	 * @return &CDbMySql
	 */
	public static function &CreateConnector(api_Settings $oSettings)
	{
		$aResult = array();
		if (!is_object(self::$oDbConnector))
		{
			CDbCreator::$oDbConnector = CDbCreator::ConnectorFabric(array(
				'Type' => $oSettings->GetConf('Common/DBType'),
				'DBHost' => $oSettings->GetConf('Common/DBHost'),
				'DBLogin' => $oSettings->GetConf('Common/DBLogin'),
				'DBPassword' => $oSettings->GetConf('Common/DBPassword'),
				'DBName' => $oSettings->GetConf('Common/DBName')
			));

			if ($oSettings->GetConf('Common/UseSlaveDBConnection'))
			{
				CDbCreator::$oSlaveDbConnector = CDbCreator::ConnectorFabric(array(
					'Type' => $oSettings->GetConf('Common/DBType'),
					'DBHost' => $oSettings->GetConf('Common/DBSlaveHost'),
					'DBLogin' => $oSettings->GetConf('Common/DBSlaveLogin'),
					'DBPassword' => $oSettings->GetConf('Common/DBSlavePassword'),
					'DBName' => $oSettings->GetConf('Common/DBSlaveName')
				));
			}
		}

		$aResult = array(&CDbCreator::$oDbConnector, &CDbCreator::$oSlaveDbConnector);
		return $aResult;
	}

	/**
	 * @param api_Settings $oSettings
	 * @return &CMySqlHelper
	 */
	public static function &CreateCommandCreatorHelper(api_Settings $oSettings)
	{
		if (is_object(CDbCreator::$oCommandCreatorHelper))
		{
			return CDbCreator::$oCommandCreatorHelper;
		}

		CDbCreator::$oCommandCreatorHelper = CDbCreator::CommandCreatorHelperFabric(
			$oSettings->GetConf('Common/DBType'));

		return CDbCreator::$oCommandCreatorHelper;
	}
}

/**
 * @package Api
 * @subpackage Db
 */
class CDbStorage
{
	/**
	 * @var string
	 */
	protected $sPrefix;

	/**
	 * @var CDbMySql
	 */
	protected $oConnector;

	/**
	 * @var CDbMySql
	 */
	protected $oSlaveConnector;

	/**
	 * @var CApiDbException
	 */
	protected $oLastException;

	/**
	 * @var api_Settings
	 */
	protected $oSettings;

	/**
	 * @param api_Settings $oSettings
	 */
	public function __construct(api_Settings &$oSettings)
	{
		$aConnections =& CDbCreator::CreateConnector($oSettings);

		$this->oSettings = $oSettings;
		$this->sPrefix = $this->oSettings->GetConf('Common/DBPrefix');
		$this->oConnector = null;
		$this->oSlaveConnector = null;
		$this->oLastException = null;

		if (is_array($aConnections) && 2 === count($aConnections))
		{
			$this->oConnector =& $aConnections[0];
			if (null !== $aConnections[1])
			{
				$this->oSlaveConnector =& $aConnections[1];
			}
		}
	}

	/**
	 * @return &CDbMySql
	 */
	public function &GetConnector()
	{
		return $this->oConnector;
	}

	/**
	 * @return &CDbMySql
	 */
	public function &GetSlaveConnector()
	{
		return $this->oSlaveConnector;
	}

	/**
	 * @return bool
	 */
	public function IsConnected()
	{
		try
		{
			return $this->oConnector->IsConnected();
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function Connect()
	{
		try
		{
			if ($this->oConnector->IsConnected())
			{
				return true;
			}

			$this->oConnector->ReInitIfNotConnected(
				$this->oSettings->GetConf('Common/DBHost'),
				$this->oSettings->GetConf('Common/DBLogin'),
				$this->oSettings->GetConf('Common/DBPassword'),
				$this->oSettings->GetConf('Common/DBName')
			);

			return $this->oConnector->Connect();
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function ConnectSlave()
	{
		try
		{
			if ($this->oSlaveConnector->IsConnected())
			{
				return true;
			}

			$this->oSlaveConnector->ReInitIfNotConnected(
				$this->oSettings->GetConf('Common/DBHost'),
				$this->oSettings->GetConf('Common/DBLogin'),
				$this->oSettings->GetConf('Common/DBPassword'),
				$this->oSettings->GetConf('Common/DBName')
			);

			return $this->oSlaveConnector->Connect(true, true);
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function ConnectNoSelect()
	{
		try
		{
			if ($this->oConnector->IsConnected())
			{
				return true;
			}
			return $this->oConnector->ConnectNoSelect();
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function Disconnect()
	{
		try
		{
			$this->oConnector->Disconnect();
			if ($this->oSlaveConnector)
			{
				$this->oSlaveConnector->Disconnect();
			}
			return true;
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function Select()
	{
		try
		{
			return $this->oConnector->Select();
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function Execute($sSql)
	{
		$bResult = false;
		try
		{
			if (!empty($sSql))
			{
				if ($this->oSlaveConnector && $this->isSlaveSql($sSql))
				{
					if ($this->ConnectSlave())
					{
						$bResult = $this->oSlaveConnector->Execute($sSql, true);
					}
				}
				else
				{
					if ($this->Connect())
					{
						$bResult = $this->oConnector->Execute($sSql);
					}
				}
			}
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}

		return $bResult;
	}

	/**
	 * @param bool $bAutoFree = true
	 * @return bool
	 */
	public function GetNextArrayRecord($bAutoFree = true)
	{
		try
		{
			if ($this->oSlaveConnector)
			{
				return $this->oSlaveConnector->GetNextArrayRecord($bAutoFree);
			}
			return $this->oConnector->GetNextArrayRecord($bAutoFree);
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}
		return false;
	}

	/**
	 * @param bool $bAutoFree = true
	 * @return bool
	 */
	public function GetNextRecord($bAutoFree = true)
	{
		try
		{
			if ($this->oSlaveConnector)
			{
				return $this->oSlaveConnector->GetNextRecord($bAutoFree);
			}
			return $this->oConnector->GetNextRecord($bAutoFree);
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}
		return false;
	}

	/**
	 * @return array | bool [object]
	 */
	public function GetResultAsObjects()
	{
		$aResult = array();
		while (false !== ($oRow = $this->GetNextRecord()))
		{
			$aResult[] = $oRow;
		}
		return $aResult;
	}

	/**
	 * @return array | bool [array]
	 */
	public function GetResultAsAssocArrays()
	{
		$aResult = array();
		while (false !== ($aRow = $this->GetNextArrayRecord()))
		{
			$aResult[] = $aRow;
		}
		return $aResult;
	}

	/**
	 * @return int
	 */
	public function GetLastInsertId()
	{
		try
		{
			return $this->oConnector->GetLastInsertId();
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}
		return false;
	}

	/**
	 * @return int
	 */
	public function ResultCount()
	{
		try
		{
			if ($this->oSlaveConnector)
			{
				return $this->oSlaveConnector->ResultCount();
			}
			return $this->oConnector->ResultCount();
		}
		catch (CApiDbException $oException)
		{
			$this->SetException($oException);
		}
		return false;
	}

	/**
	 * @return array
	 */
	public function GetTableNames()
	{
		$aResult = false;
		if ($this->Connect())
		{
			try
			{
				$aResult = $this->oConnector->GetTableNames();
			}
			catch (CApiDbException $oException)
			{
				$this->SetException($oException);
			}
		}
		return $aResult;
	}

	/**
	 * @param string $sTableName
	 * @return array
	 */
	public function GetTableFields($sTableName)
	{
		$aResult = false;
		if ($this->Connect())
		{
			try
			{
				$aResult = $this->oConnector->GetTableFields($sTableName);
			}
			catch (CApiDbException $oException)
			{
				$this->SetException($oException);
			}
		}
		return $aResult;
	}

	/**
	 * @param string $sTableName
	 * @return array
	 */
	public function GetTableIndexes($sTableName)
	{
		$aResult = false;
		if ($this->Connect())
		{
			try
			{
				$aResult = $this->oConnector->GetTableIndexes($sTableName);
			}
			catch (CApiDbException $oException)
			{
				$this->SetException($oException);
			}
		}
		return $aResult;
	}

	/**
	 * @return string
	 */
	public function Prefix()
	{
		return $this->sPrefix;
	}

	/**
	 * @return string
	 */
	public function GetError()
	{
		return '#'.$this->oConnector->ErrorCode.': '.$this->oConnector->ErrorDesc;
	}

	/**
	 * @return CApiDbException
	 */
	public function GetException()
	{
		return $this->oLastException;
	}

	/**
	 * @param CApiDbException $oException
	 */
	public function SetException($oException)
	{
		$this->oLastException = $oException;
	}

	/**
	 * @param string $sSql
	 * @return bool
	 */
	protected function isSlaveSql($sSql)
	{
		return in_array(strtoupper(substr(trim($sSql), 0, 6)), array('SELECT'));
	}
}