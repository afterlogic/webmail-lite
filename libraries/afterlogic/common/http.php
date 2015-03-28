<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package base
 */
class api_Http
{
	/**
	 * @var bool
	 */
	protected $bIsMagicQuotesOn;

	public function __construct()
	{
		$this->bIsMagicQuotesOn = (bool) ini_get('magic_quotes_gpc');
	}
	
	/**
	 * @param string $sKey
	 * @param mixed $nmDefault = null
	 * @return mixed
	 */
	public function GetQuery($sKey, $nmDefault = null)
	{
		return (isset($_GET[$sKey])) ? $this->_stripSlashesValue($_GET[$sKey]) : $nmDefault;
	}

	/**
	 * @param string $sKey
	 * @param mixed $nmDefault = null
	 * @return mixed
	 */
	public function GetPost($sKey, $nmDefault = null)
	{
		return (isset($_POST[$sKey])) ? $this->_stripSlashesValue($_POST[$sKey]) : $nmDefault;
	}

	/**
	 * @param string $sKey
	 * @param mixed $nmDefault = null
	 * @return mixed
	 */
	public function GetRequest($sKey, $nmDefault = null)
	{
		return (isset($_REQUEST[$sKey])) ? $this->_stripSlashesValue($_REQUEST[$sKey]) : $nmDefault;
	}

	/**
	 * @param string $sKey
	 * @param mixed $nmDefault = null
	 * @return mixed
	 */
	public function GetCookie($sKey, $nmDefault = null)
	{
		return (isset($_COOKIE[$sKey])) ? $this->_stripSlashesValue($_COOKIE[$sKey]) : $nmDefault;
	}

	/**
	 * @param string $sKey
	 * @param mixed $nmDefault = null
	 * @return mixed
	 */
	public function GetServer($sKey, $nmDefault = null)
	{
		return (isset($_SERVER[$sKey])) ? $_SERVER[$sKey] : $nmDefault;
	}

	/**
	 * @param string $sKey
	 * @param mixed $nmDefault = null
	 * @return mixed
	 */
	public function GetEnv($sKey, $nmDefault = null)
	{
		return (isset($_ENV[$sKey])) ? $_ENV[$sKey] : $nmDefault;
	}

	/**
	 * @return string
	 */
	public function ServerProtocol()
	{
		return $this->GetServer('SERVER_PROTOCOL', 'HTTP/1.0');
	}

	/**
	 * @return string
	 */
	public function GetMethod()
	{
		return $this->GetServer('REQUEST_METHOD', '');
	}

	/**
	 * @return bool
	 */
	public function IsPost()
	{
		return ('POST' === $this->GetMethod());
	}

	/**
	 * @return bool
	 */
	public function IsGet()
	{
		return ('GET' === $this->GetMethod());
	}

	/**
	 * @return string
	 */
	public function GetRawBody()
	{
		static $sRawBody = null;
		if (null === $sRawBody)
		{
			$sBody = @file_get_contents('php://input');
			$sRawBody = (false !== $sBody) ? $sBody : '';
		}
		return $sRawBody;
	}

	/**
	 * @param string $sHeader
	 * @return string
	 */
	public function GetHeader($sHeader)
	{
		$sResultHeader = '';
		$sServerKey = 'HTTP_'.strtoupper(str_replace('-', '_', $sHeader));
		$sResultHeader = $this->GetServer($sServerKey, '');

		if (empty($sResultHeader) && function_exists('apache_request_headers'))
		{
			$sHeaders = @apache_request_headers();
			if (isset($sHeaders[$sHeader]))
			{
				$sResultHeader = $sHeaders[$sHeader];
			}
		}

		return $sResultHeader;
	}

	/**
	 * @return string
	 */
	public function GetScheme()
	{
		return ('on' === strtolower($this->GetServer('HTTPS'))) ? 'https' : 'http';
	}

	/**
	 * @return bool
	 */
	public function IsSecure()
	{
		return ('https' === $this->GetScheme());
	}

	/**
	 * @return string
	 */
	public function GetHost()
	{
		$sHost = $this->GetServer('HTTP_HOST', '');
		if (!empty($sHost))
		{
			return $sHost;
		}

		$sScheme = $this->GetScheme();
		$sName = $this->GetServer('SERVER_NAME');
		$iPort = (int) $this->GetServer('SERVER_PORT');

		return (('http' === $sScheme && 80 === $iPort) || ('https' === $sScheme && 443 === $iPort))
			? $sName : $sName.':'.$iPort;
	}

	/**
	 * @param bool $bCheckProxy = true
	 * @return string
	 */
	public function GetClientIp($bCheckProxy = true)
	{
		$sIp = '';
		if ($bCheckProxy && null !== $this->GetServer('HTTP_CLIENT_IP', null))
		{
			$sIp = $this->GetServer('HTTP_CLIENT_IP', '');
		}
		else if ($bCheckProxy && null !== $this->GetServer('HTTP_X_FORWARDED_FOR', null))
		{
			$sIp = $this->GetServer('HTTP_X_FORWARDED_FOR', '');
		}
		else
		{
			$sIp = $this->GetServer('REMOTE_ADDR', '');
		}

		return $sIp;
	}

	/**
	 * @param int $iStatus
	 * @return void
	 */
	public function StatusHeader($iStatus)
	{
		switch ($iStatus)
		{
			default:
				header('Status: '.$iStatus, true, $iStatus);
				break;
			case 304:
				header($this->ServerProtocol().' 304 Not Modified', true, $iStatus);
				break;
			case 200:
				header($this->ServerProtocol().' 200 OK', true, $iStatus);
				break;
			case 404:
				header($this->ServerProtocol().' 404 Not Found', true, $iStatus);
				break;
		}
	}
	
	/**
	 * @param int $iExpireTime
	 * @return bool
	 */
	public function ServerNotModifiedCache($iExpireTime, $bSetCacheHeader = true, $sEtag = '')
	{
		$bResult = false;
		if (0 < $iExpireTime)
		{
			$iUtcTimeStamp = (int) date('U');
			$sIfModifiedSince = $this->GetHeader('If-Modified-Since', '');
			if (empty($sIfModifiedSince))
			{
				if ($bSetCacheHeader)
				{
					header('Cache-Control: public', true);
					header('Pragma: public', true);
					header('Last-Modified: '.gmdate('D, d M Y H:i:s', $iUtcTimeStamp - $iExpireTime).' UTC', true);
					header('Expires: '.date('D, j M Y H:i:s', $iUtcTimeStamp + $iExpireTime).' UTC', true);
					if (!empty($sEtag))
					{
						header('Etag: '.$sEtag, true);
					}
				}
			}
			else
			{
				header("HTTP/1.1 304 Not Modified");
				$bResult = true;
			}
		}
		
		return $bResult;
	}

	/**
	 * @param mixed $mValue
	 * @return mixed
	 */
	private function _stripSlashesValue($mValue)
	{
		if (!$this->bIsMagicQuotesOn)
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
				$aReturnValue[$sKey] = $this->_stripSlashesValue($mValue[$sKey]);
			}
			return $aReturnValue;
		}
		else
		{
			return $mValue;
		}
	}
}
