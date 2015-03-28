<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

CApi::Inc('common.net.abstract');

/**
 * @package Api
 * @subpackage Net
 */
class CApiSieveProtocol extends CApiNetAbstract
{
	/**
	 * @var array
	 */
	protected $aData;


	public function __construct($sHost, $iPort, $bUseSsl = false, $iConnectTimeOut = null, $iSocketTimeOut = null)
	{
		parent::__construct($sHost, $iPort, $bUseSsl, $iConnectTimeOut, $iSocketTimeOut);

		$this->aData = array();
	}

	/**
	 * @return bool
	 */
	public function Connect()
	{
		$sLine = '';
		$bResult = false;
		if (parent::Connect())
		{
			$sLine = $this->GetNextLine();
			$aTokens = $this->parseLine($sLine);
			if ($aTokens && isset($aTokens[0], $aTokens[1]) && 'IMPLEMENTATION' === $aTokens[0])
			{
				while (true)
				{
					if (false === $sLine || !isset($aTokens[0]) ||
						in_array(substr($sLine, 0, 2), array('OK', 'NO')))
					{
						break;
					}

					$sLine = trim($sLine);
					if (in_array($aTokens[0], array('IMPLEMENTATION', 'VERSION')))
					{
						$this->aData[$aTokens[0]] = $aTokens[1];
					}
					else if ('STARTTLS' === $aTokens[0])
					{
						$this->aData['STARTTLS'] = true;
					}
					else if (isset($aTokens[1]) && in_array($aTokens[0], array('SIEVE', 'SASL')))
					{
						$this->aData['TYPE'] = 'SASL' === $aTokens[0] ? 'AUTH' : 'MODULES';
						$this->aData[$this->aData['TYPE']] = explode(' ', $aTokens[1]);
					}
					else
					{
						$this->aData['UNDEFINED'] = isset($this->aData['UNDEFINED'])
							? $this->aData['UNDEFINED'] : array();

						$this->aData['UNDEFINED'][] = $sLine;
					}

					$sLine = $this->GetNextLine();
					$aTokens = $this->parseLine($sLine);
				}
			}
		}

		if ('OK' === substr($sLine, 0, 2))
		{
			$bResult = true;
		}

		if (CApi::GetConf('labs.sieve.use-starttls', false) && $bResult && isset($this->aData['STARTTLS']) && $this->aData['STARTTLS'])
		{
			$rConnect = $this->GetConnectResource();
			if (is_resource($rConnect) && function_exists('stream_socket_enable_crypto'))
			{
				if ($this->SendLine('STARTTLS') && $this->CheckResponse($this->GetResponse()))
				{
					@stream_socket_enable_crypto($rConnect, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
					$this->CheckResponse($this->GetResponse());
				}
			}
		}

		return $bResult;
	}


	/**
	 * @param string $sLogin
	 * @param string $sPassword
	 * @param string $sLoginAuthKey = ''
	 * @return bool
	 */
	public function Login($sLogin, $sPassword, $sLoginAuthKey = '')
	{
		$bResult = false;
		if (isset($this->aData['AUTH']))
		{
			if (in_array('PLAIN', $this->aData['AUTH']))
			{
				$sAuth = base64_encode($sLoginAuthKey."\0".$sLogin."\0".$sPassword);

				$this->SendLine('AUTHENTICATE "PLAIN" {'.strlen($sAuth).'+}');
				$this->SendLine($sAuth);

				$bResult = $this->CheckResponse($this->GetResponse());
			}
			else if (in_array('LOGIN', $this->aData['AUTH']))
			{
				$sLogin = base64_decode($sLogin);
				$sPassword = base64_decode($sPassword);

				$this->SendLine('AUTHENTICATE "LOGIN"');
				$this->SendLine('{'.strlen($sLogin).'+}');
				$this->SendLine($sLogin);
				$this->SendLine('{'.strlen($sPassword).'+}');
				$this->SendLine($sPassword);

				$bResult = $this->CheckResponse($this->GetResponse());
			}
		}

		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function IsConnected()
	{
		return parent::IsConnected();
	}
	/**
	 * @param string $sLogin
	 * @param string $sPassword
	 * @return bool
	 */
	public function ConnectAndLogin($sLogin, $sPassword)
	{
		return $this->Connect() && $this->Login($sLogin, $sPassword);
	}

	/**
	 * @return bool
	 */
	public function Disconnect()
	{
		return parent::Disconnect();
	}

	/**
	 * @return bool
	 */
	public function Logout()
	{
		$this->SendLine('LOGOUT');
		return $this->CheckResponse($this->GetResponse());
	}

	/**
	 * @return bool
	 */
	public function LogoutAndDisconnect()
	{
		return $this->Logout() && $this->Disconnect();
	}

	/**
	 * @return bool
	 */
	public function Capability()
	{
		$this->SendLine('CAPABILITY');
		$aResponse = $this->GetResponse();
		if ($this->CheckResponse($aResponse))
		{
			array_pop($aResponse);

			$aCapa = array();
			foreach ($aResponse as $sLine)
			{
				$aLine = $this->parseLine($sLine);
				if (isset($aLine[0]))
				{
					$aCapa[$aLine[0]] = isset($aLine[1]) ? $aLine[1] : true;
				}
			}

			return $aCapa;
		}
		return false;
	}

	/**
	 * @return array | bool
	 */
	public function ListScripts()
	{
		$this->SendLine('LISTSCRIPTS');
		$aResponse = $this->GetResponse();
		if ($this->CheckResponse($aResponse))
		{
			array_pop($aResponse);

			$aList = array();
			foreach ($aResponse as $sLine)
			{
				$aParsed = $this->parseLine($sLine);
				if (!empty($aParsed[0]))
				{
					$aList[$aParsed[0]] = ('ACTIVE' === substr($sLine, -6));
				}
			}

			return $aList;
		}

		return false;
	}

	/**
	 * @param string $sScriptName
	 * @return string | bool
	 */
	public function GetScript($sScriptName)
	{
		$this->SendLine('GETSCRIPT "'.$sScriptName.'"');
		$aResponse = $this->GetResponse();
		if ($this->CheckResponse($aResponse))
		{
			if ('{' === $aResponse[0]{0})
			{
				array_shift($aResponse);
			}
			array_pop($aResponse);

			return implode("\n", $aResponse);
		}

		return false;
	}

	/**
	 * @param string $sScriptName
	 * @return string | bool
	 */
	public function GetScriptIfActive($sScriptName)
	{
		return ($this->IsActiveScript($sScriptName)) ? $this->GetScript($sScriptName) : false;
	}

	/**
	 * @param string $sScriptName
	 * @return bool
	 */
	public function IsActiveScript($sScriptName)
	{
		$aList = $this->ListScripts();
		if (is_array($aList) && 0 < $aList)
		{
			foreach ($aList as $sName => $bIsActive)
			{
				if ($bIsActive && $sScriptName === $sName)
				{
					return true;
				}
				else if ($bIsActive)
				{
					break;
				}
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function Noop()
	{
		$this->SendLine('NOOP');
		return $this->CheckResponse($this->GetResponse());
	}

	/**
	 * @param string $sScriptName
	 * @param string $sScriptSource
	 * @return bool
	 */
	public function SendScript($sScriptName, $sScriptSource)
	{
		$sScriptSource = stripslashes($sScriptSource);

		$this->SendLine('PUTSCRIPT "'.$sScriptName.'" {'.strlen($sScriptSource).'+}');
		$this->SendLine($sScriptSource);

		return $this->CheckResponse($this->GetResponse());
	}

	/**
	 * @param string $sScriptSource
	 * @return bool
	 */
	public function CheckScript($sScriptSource)
	{
		$sScriptSource = stripslashes($sScriptSource);

		$this->SendLine('CHECKSCRIPT {'.strlen($sScriptSource).'+}');
		$this->SendLine($sScriptSource);

		return $this->CheckResponse($this->GetResponse());
	}

	/**
	 * @return bool
	 */
	public function SetActiveScript($sScriptName)
	{
		$this->SendLine('SETACTIVE "'.$sScriptName.'"');
		return $this->CheckResponse($this->GetResponse());
	}

	/**
	 * @return bool
	 */
	public function DeleteScript($sScriptName)
	{
		$this->SendLine('DELETESCRIPT "'.$sScriptName.'"');
		return $this->CheckResponse($this->GetResponse());
	}

	/**
	 * @param string $sCmd
	 * @return bool
	 */
	public function SendLine($sCmd)
	{
		return $this->WriteLine($sCmd);
	}

	/**
	 * @return string
	 */
	public function GetNextLine()
	{
		$sLine =  $this->ReadLine();
		return $sLine;
	}

	/**
	 * @return bool
	 */
	public function GetResponse()
	{
		$aLines = array();

		do
		{
			$sLine = $this->GetNextLine();
			if (false === $sLine)
			{
				break;
			}
			else if (in_array(substr($sLine, 0, 2), array('OK', 'NO')))
			{
				$aLines[] = trim($sLine);
				break;
			}
			else
			{
				$aLines[] = trim($sLine);
			}
		}
		while (true);

		return $this->CheckResponse($aLines) ? $aLines : false;
	}

	/**
	 * @return bool
	 */
	public function CheckResponse($aResponse)
	{
		return is_array($aResponse) && 0 < count($aResponse) &&
			'OK' === substr($aResponse[count($aResponse) - 1], 0, 2);
	}

	/**
	 * @param string $sLine
	 * @return array | false
	 */
	protected function parseLine($sLine)
	{
		if (false === $sLine || null === $sLine)
		{
			return false;
		}

		$iStart = -1;
		$iIndex = 0;
		$aResult = false;

		for ($iPos = 0; $iPos < strlen($sLine); $iPos++)
		{
			if ('"' === $sLine[$iPos] && '\\' !== $sLine[$iPos])
			{
				if (-1 === $iStart)
				{
					$iStart = $iPos;
				}
				else
				{
					$aResult = is_array($aResult) ? $aResult : array();
					$aResult[$iIndex++] = substr($sLine, $iStart + 1, $iPos - $iStart - 1);
					$iStart = -1;
				}
			}
		}

		return $aResult;
	}
}
