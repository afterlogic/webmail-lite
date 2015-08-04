<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

CApi::Inc('common.db.sql');

/**
 * @package Api
 * @subpackage Db
 */
class CDbPdoPostgres extends CDbSql
{
	/**
	 * @var bool
	 */
	protected $bUseExplain;

	/**
	 * @var bool
	 */
	protected $bUseExplainExtended;

	/**
	 * @var PDO database object
	 */
	protected $oPDO;

	/**
	 * @var PDO resource
	 */
	protected $rResultId;

	/**
	 * @param string $sHost
	 * @param string $sUser
	 * @param string $sPassword
	 * @param string $sDbName
	 * @param string $sDbTablePrefix = ''
	 */
	public function __construct($sHost, $sUser, $sPassword, $sDbName, $sDbTablePrefix = '')
	{
		$this->sHost = trim($sHost);
		$this->sUser = trim($sUser);
		$this->sPassword = trim($sPassword);
		$this->sDbName = trim($sDbName);
		$this->sDbTablePrefix = trim($sDbTablePrefix);

		$this->oPDO = null;
		$this->rResultId = null;

		$this->iExecuteCount = 0;
		$this->bUseExplain = CApi::GetConf('labs.db.use-explain', false);
		$this->bUseExplainExtended = CApi::GetConf('labs.db.use-explain-extended', false);
	}

	/**
	 * @param string $sHost
	 * @param string $sUser
	 * @param string $sPassword
	 * @param string $sDbName
	 */
	public function ReInitIfNotConnected($sHost, $sUser, $sPassword, $sDbName)
	{
		if (!$this->IsConnected())
		{
			$this->sHost = trim($sHost);
			$this->sUser = trim($sUser);
			$this->sPassword = trim($sPassword);
			$this->sDbName = trim($sDbName);
		}
	}

	/**
	 * @param bool $bWithSelect = true
	 * @return bool
	 */
	public function Connect($bWithSelect = true, $bNewLink = false)
	{
		if (!class_exists('PDO'))
		{
			throw new CApiDbException('Can\'t load PDO extension.', 0);
		}

		$mPdoDrivers = PDO::getAvailableDrivers();
		if (!is_array($mPdoDrivers) || !in_array('pgsql', $mPdoDrivers))
		{
			throw new CApiDbException('Can\'t load PDO postgresql driver.', 0);
		}

		if (strlen($this->sHost) == 0 || strlen($this->sUser) == 0 || strlen($this->sDbName) == 0)
		{
			throw new CApiDbException('Not enough details required to establish connection.', 0);
		}

		if (CApi::$bUseDbLog)
		{
			CApi::Log('DB(PDO/postgresql) : start connect to '.$this->sUser.'@'.$this->sHost);
		}

		$aPDOAttr = array(PDO::ATTR_TIMEOUT => 5, PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		if (defined('PDO::MYSQL_ATTR_MAX_BUFFER_SIZE' ))
		{
			$aPDOAttr[PDO::MYSQL_ATTR_MAX_BUFFER_SIZE] = 1024*1024*50;
		}

		$sDbPort = '';
		$sUnixSocket = '';

		$sDbHost = $this->sHost;
		$sDbName = $this->sDbName;
		$sDbLogin = $this->sUser;
		$sDbPassword = $this->sPassword;

		$iPos = strpos($sDbHost, ':');
		if (false !== $iPos && 0 < $iPos)
		{
			$sAfter = substr($sDbHost, $iPos + 1);
			$sDbHost = substr($sDbHost, 0, $iPos);

			if (is_numeric($sAfter))
			{
				$sDbPort = $sAfter;
			}
			else
			{
				$sUnixSocket = $sAfter;
			}
		}

		$this->oPDO = false;
		if (class_exists('PDO'))
		{
			try
			{
				$aParts = array();
				if ($bWithSelect && 0 < strlen($sDbName))
				{
					$aParts[] = 'dbname='.$sDbName;
				}
				if (0 < strlen($sDbHost))
				{
					$aParts[] = 'host='.$sDbHost;
				}
				if (0 < strlen($sDbPort))
				{
					$aParts[] = 'port='.$sDbPort;
				}
				if (0 < strlen($sUnixSocket))
				{
					$aParts[] = 'unix_socket='.$sUnixSocket;
				}

				$sPdoString = 'pgsql:'.implode(';', $aParts);
				if (CApi::$bUseDbLog)
				{
					CApi::Log('DB : PDO('.$sPdoString.')');
				}
				
				$this->oPDO = @new PDO($sPdoString, $sDbLogin, $sDbPassword, $aPDOAttr);
				if (CApi::$bUseDbLog)
				{
					CApi::Log('DB : connected to '.$this->sUser.'@'.$this->sHost);
				}

				if ($this->oPDO)
				{
					@register_shutdown_function(array(&$this, 'Disconnect'));
				}
			}
			catch (Exception $oException)
			{
				self::Log($oException->getMessage(), ELogLevel::Error);
				self::Log($oException->getTraceAsString(), ELogLevel::Error);
				$this->oPDO = false;
			}
		}
		else
		{
			self::Log('Class PDO dosn\'t exist', ELogLevel::Error);
		}

		return !!$this->oPDO;
	}

	/**
	 * @return bool
	 */
	public function ConnectNoSelect()
	{
		return $this->Connect(false);
	}

	/**
	 * @return bool
	 */
	public function Select()
	{
		return $this->oPDO != null;
	}

	/**
	 * @return bool
	 */
	public function Disconnect()
	{
		$result = false;
		if ($this->oPDO != null)
		{
			if (is_resource($this->rResultId))
			{
				$this->rResultId->closeCursor();
			}

			$this->rResultId = null;

			if (CApi::$bUseDbLog)
			{
				CApi::Log('DB : disconnect from '.$this->sUser.'@'.$this->sHost);
			}

			unset($this->oPDO);
			$this->oPDO = null;
			$result = true;
		}

		return $result;
	}

	/**
	 * @return bool
	 */
	function IsConnected()
	{
		return !!$this->oPDO;
	}

	/** @param $sQuery
	    @return false or PDOStatement
	 */
	private function SilentQuery($sQuery)
	{
		$res = false;
		if (!$this->oPDO)
		{
			return $res;
		}
		
		try
		{
			$res = $this->oPDO->query($sQuery);
		}
		catch (Exception $e)
		{
			$res = false;
		}

		return $res;
	}

	/**
	 * @param string $sQuery
	 * @param string $bIsSlaveExecute = false
	 * @return bool
	 */
	public function Execute($sQuery, $bIsSlaveExecute = false)
	{
		$sExplainLog = '';
		$sQuery = trim($sQuery);
		if (($this->bUseExplain || $this->bUseExplainExtended) && 0 === strpos($sQuery, 'SELECT'))
		{
			$sExplainQuery = 'EXPLAIN ';
			$sExplainQuery .= ($this->bUseExplainExtended) ? 'extended '.$sQuery : $sQuery;

			$rExplainResult = $this->SilentQuery($sExplainQuery);
			if ($rExplainResult != false)
			{
				while (false != ($mResult = $rExplainResult->fetch(PDO::FETCH_ASSOC)))
				{
					$sExplainLog .= API_CRLF.print_r($mResult, true);
				}
				
				$rExplainResult->closeCursor();
			}

			if ($this->bUseExplainExtended)
			{
				$rExplainResult = $this->SilentQuery('SHOW warnings');
				if ($rExplainResult != false)
				{
					while (false != ($mResult = $rExplainResult->fetch(PDO::FETCH_ASSOC)))
					{
						$sExplainLog .= API_CRLF.print_r($mResult, true);
					}
					
					$rExplainResult->closeCursor();
				}
			}
		}

		$this->iExecuteCount++;
		$this->log($sQuery, $bIsSlaveExecute);
		if (!empty($sExplainLog))
		{
			$this->log('EXPLAIN:'.API_CRLF.trim($sExplainLog), $bIsSlaveExecute);
		}

		$this->rResultId = $this->SilentQuery($sQuery);
		if ($this->rResultId === false)
		{
			$this->_setSqlError();
		}

		return $this->rResultId !== false;
	}

	/**
	 * @param bool $bAutoFree = true
	 * @return &object
	 */
	public function &GetNextRecord($bAutoFree = true)
	{
		if ($this->rResultId)
		{
			$mResult = $this->rResultId->fetch(PDO::FETCH_OBJ);
			if (!$mResult && $bAutoFree)
			{
				$this->FreeResult();
			}
			
			return $mResult;
		}
		else
		{
			$nNull = false;
			$this->_setSqlError();
			return $nNull;
		}
	}

	/**
	 * @param bool $bAutoFree = true
	 * @return &array
	 */
	public function &GetNextArrayRecord($bAutoFree = true)
	{
		if ($this->rResultId)
		{
			$mResult = $this->rResultId->fetch(PDO::FETCH_ASSOC);
			if (!$mResult && $bAutoFree)
			{
				$this->FreeResult();
			}
			return $mResult;
		}
		else
		{
			$nNull = false;
			$this->_setSqlError();
			return $nNull;
		}
	}

	/**
	 * @param string $sTableName = null
	 * @param string $sFieldName = null
	 * @return int
	 */
	public function GetLastInsertId($sTableName = null, $sFieldName = null)
	{
		try
		{
			$sName = null;
			if (null !== $sTableName && null !== $sFieldName)
			{
				$sName = $this->sDbTablePrefix.$sTableName.'_'.$sFieldName.'_seq';
			}

			return (int) ($sName ? $this->oPDO->lastInsertId($sName) : $this->oPDO->lastInsertId());
		}
		catch( Exception $e)
		{
			CApi::LogException($e);
		}
		
		return 0;
	}

	/**
	 * @return array
	 */
	public function GetTableNames()
	{
		if (!$this->Execute('SELECT table_name FROM information_schema.tables WHERE table_schema = \'public\''))
		{
			return false;
		}

		$aResult = array();
		while (false !== ($aValue = $this->GetNextArrayRecord()))
		{
			foreach ($aValue as $sValue)
			{
				$aResult[] = $sValue;
				break;
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
		if (!$this->Execute('SELECT column_name FROM information_schema.columns WHERE table_name =\''.$sTableName.'\''))
		{
			return false;
		}

		$aResult = array();
		while (false !== ($oValue = $this->GetNextRecord()))
		{
			if ($oValue && isset($oValue->column_name) && 0 < strlen($oValue->column_name))
			{
				$aResult[] = $oValue->column_name;
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
		if (!$this->Execute('SELECT * FROM pg_indexes WHERE tablename = \''.$sTableName.'\''))
		{
			return false;
		}

		$aResult = array();
		while (false !== ($oValue = $this->GetNextRecord()))
		{
			if ($oValue && !empty($oValue->indexname) && !empty($oValue->indexdef))
			{
				if (!isset($aResult[$oValue->indexname]))
				{
					$aMatch = array();
					if (preg_match('/\(([a-z0-9 _,]+)\)/i', $oValue->indexdef, $aMatch) && !empty($aMatch[1]))
					{
						$aCols = explode(',', $aMatch[1]);
						$aCols = array_map('trim', $aCols);

						if (0 < count($aCols))
						{
							$aResult[$oValue->indexname] = $aCols;
						}
					}
				}
			}
		}

		return $aResult;
	}

	/**
	 * @return bool
	 */
	public function FreeResult()
	{
		if ($this->rResultId)
		{
			if (!$this->rResultId->closeCursor())
			{
				$this->_setSqlError();
				return false;
			}
			else
			{
				$this->rResultId = null;
			}
		}
		return true;
	}

	/**
	 * @return int
	 */
	public function ResultCount()
	{
		if ($this->rResultId)
		{
			return $this->rResultId->rowCount(); // Only works for MySQL
//			return $this->SilentQuery("SELECT FOUND_ROWS()")->fetchColumn();
		}
		
		return 0;
	}

	/**
	 * @return void
	 */
	private function _setSqlError()
	{
		if ($this->IsConnected())
		{
			$aEr = $this->oPDO->errorInfo();
			$this->ErrorDesc = (string) implode("\r\n", is_array($aEr) ? $aEr : array()).' ['.$this->oPDO->errorCode().']';
			$this->ErrorCode = 0;
		}
		else
		{
			$this->ErrorDesc = 'No connection';
			$this->ErrorCode = -23456789;
		}

		if (0 < strlen($this->ErrorDesc))
		{
			$this->errorLog($this->ErrorDesc);
			throw new CApiDbException($this->ErrorDesc, $this->ErrorCode);
		}
	}
}
