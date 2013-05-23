<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Api
 * @subpackage Db
 */
class CDbGeneralSql
{
	/**
	 * @var	resource
	 */
	protected $_rConectionHandle;

	/**
	 * @var	resource
	 */
	protected $_rResultId;

	/**
	 * @var	int
	 */
	protected $iExecuteCount;

	/**
	 * @var	int
	 */
	public $ErrorCode;

	/**
	 * @var	string
	 */
	public $ErrorDesc;

	/**
	 * @return resource
	 */
	function GetResult()
	{
		return $this->_rResultId;
	}

	/**
	 * @return bool
	 */
	function IsConnected()
	{
		return is_resource($this->_rConectionHandle);
	}

	/**
	 * @param string $sLogDesc
	 * @param string $bIsSlaveExecute = false
	 * @return void
	 */
	protected function log($sLogDesc, $bIsSlaveExecute = false)
	{
		if (CApi::$bUseDbLog)
		{
			if ($bIsSlaveExecute)
			{
				CApi::Log('DB-Slave['.$this->iExecuteCount.'] > '.trim($sLogDesc));
			}
			else
			{
				CApi::Log('DB['.$this->iExecuteCount.'] > '.trim($sLogDesc));
			}
		}
	}

	/**
	 * @param string $sErrorDesc
	 * @return void
	 */
	protected function errorLog($sErrorDesc)
	{
		CApi::Log('DB ERROR < '.trim($sErrorDesc), ELogLevel::Error);
	}
}

/**
 * @package Api
 * @subpackage Db
 */
class CDbSql extends CDbGeneralSql
{
	/**
	 * @var	string
	 */
	protected $sHost;

	/**
	 * @var	string
	 */
	protected $sUser;

	/**
	 * @var	string
	 */
	protected $sPassword;

	/**
	 * @var	string
	 */
	protected $sDbName;
}