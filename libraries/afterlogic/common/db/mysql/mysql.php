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
class CDbMySql extends CDbSql
{
	/*
	 * @var	resource
	 */
	protected $_rConectionHandle;

	/**
	 * @var	resource
	 */
	protected $_rResultId;

	/**
	 * @var bool
	 */
	protected $bUseExplain;

	/**
	 * @var bool
	 */
	protected $bUseExplainExtended;

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

		$this->_rConectionHandle = null;
		$this->_rResultId = null;

		$this->iExecuteCount = 0;
		$this->bUseExplain = CApi::GetConf('labs.db.use-explain', false);
		$this->bUseExplainExtended = CApi::GetConf('labs.db.use-explain-extended', false);
	}

	/**
	 * @return bool
	 */
	function IsConnected()
	{
		return is_resource($this->_rConectionHandle);
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
		if (!function_exists('mysql_connect'))
		{
			throw new CApiDbException('Can\'t load MySQL extension.', 0);
		}

		if (strlen($this->sHost) == 0 || strlen($this->sUser) == 0 || strlen($this->sDbName) == 0)
		{
			throw new CApiDbException('Not enough details required to establish connection.', 0);
		}

		@ini_set('mysql.connect_timeout', 5);

		if (CApi::$bUseDbLog)
		{
			CApi::Log('DB(mysql) : start connect to '.$this->sUser.'@'.$this->sHost);
		}
		
		$this->_rConectionHandle = @mysql_connect($this->sHost, $this->sUser, $this->sPassword, (bool) $bNewLink);
		if ($this->_rConectionHandle)
		{
			if (CApi::$bUseDbLog)
			{
				CApi::Log('DB : connected to '.$this->sUser.'@'.$this->sHost);
			}
			
			@register_shutdown_function(array(&$this, 'Disconnect'));
			return ($bWithSelect) ? $this->Select() : true;
		}
		else
		{
			CApi::Log('DB : connect to '.$this->sUser.'@'.$this->sHost.' failed', ELogLevel::Error);
			$this->_setSqlError();
			return false;
		}
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
		if (0 < strlen($this->sDbName))
		{
			$rDbSelect = @mysql_select_db($this->sDbName, $this->_rConectionHandle);
			if(!$rDbSelect)
			{
				$this->_setSqlError();
				if ($this->_rConectionHandle)
				{
					@mysql_close($this->_rConectionHandle);
				}
				$this->_rConectionHandle = null;
				return false;
			}

			if ($this->_rConectionHandle)
			{
				$bSet = false;
				if (function_exists('mysql_set_charset'))
				{
					$bSet = true;
					mysql_set_charset('utf8', $this->_rConectionHandle);
				}

				if (!$bSet)
				{
					mysql_query('SET NAMES utf8', $this->_rConectionHandle);
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function Disconnect()
	{
		$result = true;
		if ($this->_rConectionHandle)
		{
			if (is_resource($this->_rResultId))
			{
				mysql_free_result($this->_rResultId);
			}
			$this->_resultId = null;

			if (CApi::$bUseDbLog)
			{
				CApi::Log('DB : disconnect from '.$this->sUser.'@'.$this->sHost);
			}

			$result = @mysql_close($this->_rConectionHandle);
			$this->_rConectionHandle = null;
			return $result;
		}
		else
		{
			return false;
		}
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

			$rExplainResult = @mysql_query($sExplainQuery, $this->_rConectionHandle);
			while (false != ($mResult = mysql_fetch_assoc($rExplainResult)))
			{
				$sExplainLog .= API_CRLF.print_r($mResult, true);
			}

			if ($this->bUseExplainExtended)
			{
				$rExplainResult = @mysql_query('SHOW warnings', $this->_rConectionHandle);
				while (false != ($mResult = mysql_fetch_assoc($rExplainResult)))
				{
					$sExplainLog .= API_CRLF.print_r($mResult, true);
				}
			}
		}

		$this->iExecuteCount++;
		$this->log($sQuery, $bIsSlaveExecute);
		if (!empty($sExplainLog))
		{
			$this->log('EXPLAIN:'.API_CRLF.trim($sExplainLog), $bIsSlaveExecute);
		}

		$this->_rResultId = @mysql_query($sQuery, $this->_rConectionHandle);
		if ($this->_rResultId === false)
		{
			$this->_setSqlError();
		}

		return ($this->_rResultId !== false);
	}

	/**
	 * @param bool $bAutoFree = true
	 * @return &object
	 */
	public function &GetNextRecord($bAutoFree = true)
	{
		if ($this->_rResultId)
		{
			$mResult = @mysql_fetch_object($this->_rResultId);
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
		if ($this->_rResultId)
		{
			$mResult = mysql_fetch_assoc($this->_rResultId);
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
		return (int) @mysql_insert_id($this->_rConectionHandle);
	}

	/**
	 * @return array
	 */
	public function GetTableNames()
	{
		if (!$this->Execute('SHOW TABLES'))
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
		if (!$this->Execute('SHOW COLUMNS FROM `'.$sTableName.'`'))
		{
			return false;
		}

		$aResult = array();
		while (false !== ($oValue = $this->GetNextRecord()))
		{
			if ($oValue && isset($oValue->Field) && 0 < strlen($oValue->Field))
			{
				$aResult[] = $oValue->Field;
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
		if (!$this->Execute('SHOW INDEX FROM `'.$sTableName.'`'))
		{
			return false;
		}

		$aResult = array();
		while (false !== ($oValue = $this->GetNextRecord()))
		{
			if ($oValue && isset($oValue->Key_name, $oValue->Column_name))
			{
				if (!isset($aResult[$oValue->Key_name]))
				{
					$aResult[$oValue->Key_name] = array();
				}
				$aResult[$oValue->Key_name][] = $oValue->Column_name;
			}
		}

		return $aResult;
	}

	/**
	 * @return bool
	 */
	public function FreeResult()
	{
		if ($this->_rResultId)
		{
			if (!@mysql_free_result($this->_rResultId))
			{
				$this->_setSqlError();
				return false;
			}
			else
			{
				$this->_rResultId = null;
			}
		}
		return true;
	}

	/**
	 * @return int
	 */
	public function ResultCount()
	{
		return @mysql_num_rows($this->_rResultId);
	}

	/**
	 * @return void
	 */
	private function _setSqlError()
	{
		if ($this->IsConnected())
		{
			$this->ErrorDesc = @mysql_error($this->_rConectionHandle);
			$this->ErrorCode = @mysql_errno($this->_rConectionHandle);
		}
		else
		{
			$this->ErrorDesc = @mysql_error();
			$this->ErrorCode = @mysql_errno();
		}

		if (0 < strlen($this->ErrorDesc))
		{
			$this->errorLog($this->ErrorDesc);
			throw new CApiDbException($this->ErrorDesc, $this->ErrorCode);
		}
	}
}