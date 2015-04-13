<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Api
 */
class CSession
{
	/**
	 * @var string
	 */
	static $sSessionName = '';

	/**
	 * @var bool
	 */
	static $bIsMagicQuotesOn = false;

	/**
	 * @var bool
	 */
	static $bFirstStarted = false;

	/**
	 * @var bool
	 */
	static $bStarted = false;

	private function __construct() {}

	/**
	 * @param string $sKey
	 * @return bool
	 */
	public static function Has($sKey)
	{
		if (!CSession::$bFirstStarted)
		{
			CSession::Start();
		}
		return (isset($_SESSION[$sKey]));
	}

	/**
	 * @param string $sKey
	 * @return void
	 */
	public static function Clear($sKey)
	{
		CSession::Start();
		unset($_SESSION[$sKey]);
	}

	/**
	 * @return void
	 */
	public static function ClearAll()
	{
		CSession::Start();
		$_SESSION = array();
	}

	/**
	 * @return void
	 */
	public static function Destroy()
	{
		CSession::Start();
		CSession::$bStarted = false;
		@session_destroy();
	}

	/**
	 * @param string $sKey
	 * @param mixed $nmDefault = null
	 * @return mixed
	 */
	public static function Get($sKey, $nmDefault = null)
	{
		if (!CSession::$bFirstStarted)
		{
			CSession::Start();
		}

		return (isset($_SESSION[$sKey])) ? CSession::stripSlashesValue($_SESSION[$sKey]) : $nmDefault;
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 */
	public static function Set($sKey, $mValue)
	{
		CSession::Start();
		$_SESSION[$sKey] = $mValue;
	}

	/**
	 * @return string
	 */
	public static function Id()
	{
		CSession::Start();
		return @session_id();
	}

	/**
	 * @param string $sId
	 *
	 * @return string
	 */
	public static function SetId($sId)
	{
		CSession::Stop();
		@session_id($sId);
		CSession::Start();
		return @session_id();
	}

	/**
	 * @return string
	 */
	public static function DestroySessionById($sId)
	{
		CSession::Stop();
		@session_id($sId);
		CSession::Start();
		CSession::Destroy();
	}

	/**
	 * @return bool
	 */
	public static function Start()
	{
		if (@session_name() !== CSession::$sSessionName || !CSession::$bStarted || !CSession::$bFirstStarted)
		{
			if (@session_name())
			{
				@session_write_close();
				if (isset($GLOBALS['PROD_NAME']) && false !== strpos($GLOBALS['PROD_NAME'], 'Plesk')) // Plesk
				{
					@session_module_name('files');
				}
			}

			@session_set_cookie_params(0);
			if (!empty(CSession::$sSessionName))
			{
				@session_name(CSession::$sSessionName);
			}

			CSession::$bFirstStarted = true;
			CSession::$bStarted = true;

			return @session_start();
		}

		return true;
	}

	/**
	 * @return void
	 */
	public static function Stop()
	{
		if (CSession::$bStarted)
		{
			CSession::$bStarted = false;
			@session_write_close();
		}
	}

	/**
	 * @param mixed $mValue
	 * @return mixed
	 */
	private static function stripSlashesValue($mValue)
	{
		if (!CSession::$bIsMagicQuotesOn)
		{
			return $mValue;
		}

		$sType = gettype($mValue);
		if ($sType === 'string')
		{
			return stripslashes($mValue);
		}
		else if ($sType === 'array')
		{
			$aReturnValue = array();
			$mValueKeys = array_keys($mValue);
			foreach($mValueKeys as $sKey)
			{
				$aReturnValue[$sKey] = CSession::stripSlashesValue($mValue[$sKey]);
			}
			return $aReturnValue;
		}
		else
		{
			return $mValue;
		}
	}
}

CSession::$bIsMagicQuotesOn = (bool) ini_get('magic_quotes_gpc');
CSession::$sSessionName = API_SESSION_WEBMAIL_NAME;
