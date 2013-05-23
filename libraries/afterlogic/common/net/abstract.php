<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Api
 * @subpackage Net
 */
abstract class CApiNetAbstract
{
	/**
	 * @var resource
	 */
	protected $rConnect;

	/**
	 * @var string
	 */
	protected $sHost;

	/**
	 * @var int
	 */
	protected $iPort;

	/**
	 * @var bool
	 */
	protected $bUseSsl;

	/**
	 * @var int
	 */
	protected $iConnectTimeOut;

	/**
	 * @var int
	 */
	protected $iSocketTimeOut;

	/**
	 * @param string $sHost
	 * @param int $iPort
	 * @param bool $bUseSsl = false
	 * @param int $iConnectTimeOut = null
	 * @param int $iSocketTimeOut = null
	 */
	public function __construct($sHost, $iPort, $bUseSsl = false, $iConnectTimeOut = null, $iSocketTimeOut = null)
	{
		$iConnectTimeOut = (null === $iConnectTimeOut) ? CApi::GetConf('socket.connect-timeout', 5) : $iConnectTimeOut;
		$iSocketTimeOut = (null === $iSocketTimeOut) ? CApi::GetConf('socket.get-timeout', 5) : $iSocketTimeOut;

		$this->sHost = $sHost;
		$this->iPort = $iPort;
		$this->bUseSsl = $bUseSsl;
		$this->iConnectTimeOut = $iConnectTimeOut;
		$this->iSocketTimeOut = $iSocketTimeOut;
	}

	/**
	 * @return bool
	 */
	public function Connect()
	{
		$sHost = ($this->bUseSsl) ? 'ssl://'.$this->sHost : $this->sHost;

		if ($this->IsConnected())
		{
			CApi::Log('already connected['.$sHost.':'.$this->iPort.']: result = false', ELogLevel::Error);

			$this->Disconnect();
			return false;
		}

		$sErrorStr = '';
		$iErrorNo = 0;

		CApi::Log('start connect to '.$sHost.':'.$this->iPort);
		$this->rConnect = @fsockopen($sHost, $this->iPort, $iErrorNo, $sErrorStr, $this->iConnectTimeOut);

		if (!$this->IsConnected())
		{
			CApi::Log('connection error['.$sHost.':'.$this->iPort.']: fsockopen = false ('.$iErrorNo.': '.$sErrorStr.')', ELogLevel::Error);
			return false;
		}
		else
		{
			CApi::Log('connected');
		}

		@stream_set_timeout($this->rConnect, $this->iSocketTimeOut);
		@stream_set_blocking($this->rConnect, true);
		return true;
	}

	/**
	 * @return bool
	 */
	public function Disconnect()
	{
		if ($this->IsConnected())
		{
			CApi::Log('disconnect from '.$this->sHost.':'.$this->iPort);
			@fclose($this->rConnect);
		}
		$this->rConnect = null;
		return true;
	}

	/**
	 * @return resource
	 */
	public function GetConnectResource()
	{
		return $this->rConnect;
	}

	/**
	 * @return bool
	 */
	public function IsConnected()
	{
		return is_resource($this->rConnect);
	}

	/**
	 * @return string | bool
	 */
	public function ReadLine()
	{
		$sLine = @fgets($this->rConnect, 4096);
		CApi::Log('NET < '.api_Utils::ShowCRLF($sLine));

		if (false === $sLine)
		{
		    $aSocketStatus = @socket_get_status($this->rConnect);
		    if (isset($aSocketStatus['timed_out']) && $aSocketStatus['timed_out'])
		    {
				CApi::Log('NET[Error] < Socket timeout reached during connection.', ELogLevel::Error);
		    }
			else
			{
				CApi::Log('NET[Error] < fgets = false', ELogLevel::Error);
			}
		}

		return $sLine;
	}

	/**
	 * @param string $sLine
	 * @return bool
	 */
	public function WriteLine($sLine, $aHideValues = array())
	{
		$sLine = $sLine."\r\n";
		$sLogLine = (0 < count($aHideValues))
			? str_replace($aHideValues, '*******', $sLine) : $sLine;

		CApi::Log('NET > '.api_Utils::ShowCRLF($sLogLine));

		if (!@fputs($this->rConnect, $sLine))
		{
			CApi::Log('NET[Error] < Could not send user request', ELogLevel::Error);
			return false;
		}

		return true;
	}
}