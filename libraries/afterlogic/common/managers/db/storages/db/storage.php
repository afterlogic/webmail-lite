<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Db
 */
class CApiDbDbStorage extends CApiDbStorage
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
	 * @var IDbHelper
	 */
	protected $oHelper;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('db', $oManager);

		$this->oConnection =& $oManager->GetConnection();
		$this->oCommandCreator =& $oManager->GetCommandCreator(
			$this, array(
				EDbType::MySQL => 'CApiDbCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiDbCommandCreatorPostgreSQL'
			)
		);

		$this->oHelper =& $oManager->GetSqlHelper();
	}

	/**
	 * @return bool
	 */
	public function TestConnection()
	{
		$bResult = $this->oConnection->Connect();
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function TryToCreateDatabase()
	{
		CDbCreator::ClearStatic();

		$aConnections =& CDbCreator::CreateConnector($this->oSettings);
		$oConnect = $aConnections[0];
		if ($oConnect)
		{
			$oConnect->ConnectNoSelect();
			$oConnect->Execute(
				$this->oCommandCreator->CreateDatabase($this->oSettings->GetConf('Common/DBName')));
		}
		else
		{
			throw new CApiBaseException(Errs::Db_ExceptionError);
		}

		return true;
	}

	/**
	 * @param mixed $fVerboseCallback
	 * @param bool $bDropFields = false
	 * @param bool $bDropIndex = false
	 */
	public function SyncTables($fVerboseCallback, $bDropFields = false, $bDropIndex = false)
	{
		$iResult = 0;
		$aDbTables = $this->oConnection->GetTableNames();
		if (is_array($aDbTables))
		{
			$iResult = 1;
			$aTables = CDbSchemaHelper::GetSqlTables();
			foreach ($aTables as /* @var $oTable CDbTable */ $oTable)
			{
				if (in_array($oTable->Name(), $aDbTables))
				{
					$iResult &= $this->syncTable($oTable, $fVerboseCallback, $bDropFields, $bDropIndex);
				}
				else
				{
					$sError = '';

					$aSql = explode(';', $oTable->ToString($this->oHelper));
					foreach ($aSql as $sSql)
					{
						if ('' !== trim($sSql))
						{
							$bResult = $this->oConnection->Execute($sSql);
							if (!$bResult)
							{
								$iResult = 0;
								$sError = $this->oConnection->GetError();
								break;
							}
						}
					}

					call_user_func($fVerboseCallback, ESyncVerboseType::CreateTable, $bResult, $oTable->Name(), array(), $sError);
				}
			}
		}

		$this->throwDbExceptionIfExist();
		return (bool) $iResult;
	}

	/**
	 * @param CDbTable $oTable
	 * @param mixed $fVerboseCallback
	 * @param bool $bDropFields = false
	 * @param bool $bDropIndex = false
	 */
	protected function syncTable(CDbTable $oTable, $fVerboseCallback, $bDropFields = false, $bDropIndex = false)
	{
		$iResult = 1;
		$aDbFields = $this->oConnection->GetTableFields($oTable->Name());
		if (!is_array($aDbFields))
		{
			return false;
		}

		$aSchemaFields = $oTable->GetFieldNames();

		$aFieldsToAdd = array_diff($aSchemaFields, $aDbFields);
		if (0 < count($aFieldsToAdd))
		{
			$sError = '';
			$sResult = $this->oConnection->Execute($oTable->GetAlterAddFields($this->oHelper, $aFieldsToAdd));
			if (!$sResult)
			{
				$iResult = 0;
				$sError = $this->oConnection->GetError();
			}

			call_user_func($fVerboseCallback, ESyncVerboseType::CreateField, $sResult, $oTable->Name(), $aFieldsToAdd, $sError);
		}

//		if ($bDropFields)
//		{
//			$aFieldsToDelete = array_diff($aDbFields, $aSchemaFields);
//			if (0 < count($aFieldsToDelete))
//			{
//				$sError = '';
//				$sResult = $this->oConnection->Execute($oTable->GetAlterDeleteFields($this->oHelper, $aFieldsToDelete));
//				if (!$sResult)
//				{
//					$iResult = 0;
//					$sError = $this->oConnection->GetError();
//				}
//
//				call_user_func($fVerboseCallback, ESyncVerboseType::DeleteField, $sResult, $oTable->Name(), $aFieldsToDelete, $sError);
//			}
//		}

		$aTableIndexes = $oTable->GetIndexesFieldsNames();
		$aDbIndexes = $this->oConnection->GetTableIndexes($oTable->Name());

		$aTableIndexesSimple = array();
		foreach ($aTableIndexes as $iKey => $aIndex)
		{
			sort($aIndex);
			$aTableIndexesSimple[$iKey] = implode('|', $aIndex);
		}

		$aDbIndexesSimple = array();
		foreach ($aDbIndexes as $sKey => $aIndex)
		{
			sort($aIndex);
			$aDbIndexesSimple[$sKey] = implode('|', $aIndex);
		}

		foreach ($aTableIndexesSimple as $iKey => $sIndexLine)
		{
			if (!empty($sIndexLine) && !in_array($sIndexLine, $aDbIndexesSimple) && isset($aTableIndexes[$iKey]))
			{
				$sError = '';
				$sResult = $this->oConnection->Execute($oTable->GetAlterCreateIndexes($this->oHelper, $aTableIndexes[$iKey]));
				if (!$sResult)
				{
					$iResult = 0;
					$sError = $this->oConnection->GetError();
				}

				call_user_func($fVerboseCallback, ESyncVerboseType::CreateIndex, $sResult, $oTable->Name(), explode('|', $sIndexLine), $sError);
			}
		}

//		if ($bDropIndex)
//		{
//			foreach ($aDbIndexesSimple as $sKey => $sIndex)
//			{
//				if ('PRIMARY' !== strtoupper($sKey) && '_pkey' !== strtolower(substr($sKey, -5)) && !empty($sIndex)
//					&& !in_array($sIndex, $aTableIndexesSimple) && isset($aDbIndexes[$sKey]))
//				{
//					$sError = '';
//					$sResult = $this->oConnection->Execute($oTable->GetAlterDeleteIndexes($this->oHelper, $sKey));
//					if (!$sResult)
//					{
//						$iResult = 0;
//						$sError = $this->oConnection->GetError();
//					}
//
//					call_user_func($fVerboseCallback, ESyncVerboseType::DeleteIndex, $sResult, $oTable->Name(), array($sIndex), $sError);
//				}
//			}
//		}

		$this->throwDbExceptionIfExist();
		return (bool) $iResult;
	}

	/**
	 * @return bool
	 */
	public function AUsersTableExists()
	{
		$aTables = $this->oConnection->GetTableNames();
		$aTables = is_array($aTables) ? $aTables : array();
		$bResult = in_array($this->oConnection->Prefix().'a_users', $aTables);

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function CreateTables()
	{
		$bResult = 1;
		$aTables = $this->GetSqlSchemaAsArray();
		foreach ($aTables as $sTableCreateSql)
		{
			$bResult &= $this->oConnection->Execute($sTableCreateSql);
		}
		$this->throwDbExceptionIfExist();
		return (bool) $bResult;
	}

	/**
	 * @param bool $bAddDropTable = false
	 * @return string
	 */
	public function GetSqlSchemaAsString($bAddDropTable = false)
	{
		$sFunctions = implode(';;'.API_CRLF.API_CRLF, $this->GetSqlFunctionsAsArray($bAddDropTable));
		$sFunctions = empty($sFunctions) ? ''
			: API_CRLF.API_CRLF.'DELIMITER ;;'.API_CRLF.API_CRLF.$sFunctions.';;'.API_CRLF.API_CRLF.'DELIMITER ;';

		return trim('DELIMITER ;'.API_CRLF.API_CRLF.
			implode(';'.API_CRLF.API_CRLF, $this->GetSqlSchemaAsArray($bAddDropTable)).';'.
			$sFunctions);
	}

	/**
	 * @param bool $bAddDropTable = false
	 * @return array
	 */
	public function GetSqlSchemaAsArray($bAddDropTable = false)
	{
		$aResult = array();
		$aTables = CDbSchemaHelper::GetSqlTables();

		foreach ($aTables as /* @var $oTable CDbTable */ $oTable)
		{
			$aResult[] = $oTable->ToString($this->oHelper, $bAddDropTable);
		}
		return $aResult;
	}

	/**
	 * @param bool $bAddDropFunction = false
	 * @return array
	 */
	public function GetSqlFunctionsAsArray($bAddDropFunction = false)
	{
		$aResult = array();
		$aFunctions = CDbSchemaHelper::GetSqlFunctions();

		foreach ($aFunctions as /* @var $oFunction CDbFunction */ $oFunction)
		{
			$aResult[] = $oFunction->ToString($this->oHelper, $bAddDropFunction);
		}
		return $aResult;
	}
}
