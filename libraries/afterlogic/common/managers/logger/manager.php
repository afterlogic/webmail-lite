<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Logger
 */
class CApiLoggerManager extends AApiManager
{
	/**
	 * @var string
	 */
	protected $sLogFileName;

	/**
	 * @var string
	 */
	protected $sCurrentUserLogFileName;

	/**
	 * @var string
	 */
	protected $sLogFile;

	/**
	 * @var string
	 */
	protected $sCurrentUserLogFile;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('logger', $oManager);

		$sS = CApi::GetConf('log.custom-full-path', '');
		$sPrePath = empty($sS) ? CApi::DataPath().'/logs/' : rtrim(trim($sS), '\\/').'/';

		$this->sLogFileName = CApi::GetConf('log.log-file', 'log.txt');
		$this->sLogFile = $sPrePath.$this->sLogFileName;

		$this->sCurrentUserLogFileName = CApi::GetConf('log.event-file', 'event.txt');
		$this->sCurrentUserLogFile = $sPrePath.$this->sCurrentUserLogFileName;
	}

	/**
	 * @return string
	 */
	public function getLogName()
	{
		return $this->sLogFileName;
	}

	/**
	 * @return string
	 */
	public function getCurrentUserActivityLogName()
	{
		return $this->sCurrentUserLogFileName;
	}

	/**
	 * @return int|bool
	 */
	public function getCurrentLogSize()
	{
		return @filesize($this->sLogFile);
	}

	/**
	 * @return int|bool
	 */
	public function getCurrentUserActivityLogSize()
	{
		return @filesize($this->sCurrentUserLogFile);
	}

	/**
	 * @return bool
	 */
	public function deleteCurrentLog()
	{
		return $this->_deleteSomeFile($this->sLogFile);
	}

	/**
	 * @return bool
	 */
	public function deleteCurrentUserActivityLog()
	{
		return $this->_deleteSomeFile($this->sCurrentUserLogFile);
	}

	/**
	 * @param int &$iSize = 0
	 * @return bool|resource
	 */
	public function getCurrentLogStream(&$iSize = 0)
	{
		return $this->_getSomeFileStream($this->sLogFile, $iSize);
	}

	/**
	 * @param int &$iSize = 0
	 * 
	 * @return bool|resource
	 */
	public function getCurrentUserActivityLogStream(&$iSize = 0)
	{
		return $this->_getSomeFileStream($this->sCurrentUserLogFile, $iSize);
	}

	/**
	 * @param string $sFileFullPath
	 * @param int &$iSize
	 * 
	 * @return bool|resource
	 */
	protected function _getSomeFileStream($sFileFullPath, &$iSize)
	{
		$rResult = false;
		if (@file_exists($sFileFullPath))
		{
			$iSize = filesize($sFileFullPath);
			$rResult = fopen($sFileFullPath, 'rw+');
		}
		else
		{
			$iSize = false;
		}

		return $rResult;
	}

	/**
	 * @param string $sFileFullPath
	 * 
	 * @return bool
	 */
	protected function _deleteSomeFile($sFileFullPath)
	{
		return (@file_exists($sFileFullPath)) ? @unlink($sFileFullPath) : true;
	}
}
