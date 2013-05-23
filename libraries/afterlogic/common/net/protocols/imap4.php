<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

CApi::Inc('common.net.abstract');

/**
 * @package Api
 * @subpackage Net
 */
class CApiImap4MailProtocol extends CApiNetAbstract
{
	/**
	 * @var array
	 */
	protected $aCapa;

	public function __construct($sHost, $iPort, $bUseSsl = false, $iConnectTimeOut = null, $iSocketTimeOut = null)
	{
		parent::__construct($sHost, $iPort, $bUseSsl, $iConnectTimeOut, $iSocketTimeOut);

		$this->aCapa = null;
	}

	/**
	 * @return bool
	 */
	public function Connect()
	{
		$bResult = false;
		if (parent::Connect())
		{
			$bResult = $this->CheckResponse('*', $this->GetResponse('*'));
		}
		return $bResult;
	}

	/**
	 * @param string $sIncCapa
	 * @param bool $bForce = false
	 * @return bool
	 */
	public function IsSupported($sIncCapa, $bForce = false)
	{
		if (null === $this->aCapa || $bForce)
		{
			$sTag = $this->getNextTag();
			if ($this->WriteLine($sTag.' CAPABILITY'))
			{
				$sResponse = $this->GetResponse($sTag);
				if ($this->CheckResponse($sTag, $sResponse))
				{
					$this->aCapa = array();
					$aCapasLineArray = explode("\n", $sResponse);
					foreach ($aCapasLineArray as $sCapasLine)
					{
						$sCapa = strtoupper(trim($sCapasLine));
						if (substr($sCapa, 0, 12) === '* CAPABILITY')
						{
							$sCapa = substr($sCapa, 12);
							$aArray = explode(' ', $sCapa);

							foreach ($aArray as $sSubLine)
							{
								if (strlen($sSubLine) > 0)
								{
									$this->aCapa[] = $sSubLine;
								}
							}
						}
					}
				}
			}
		}

		return is_array($this->aCapa) && in_array($sIncCapa, $this->aCapa);
	}

	/**
	 * @param string $sLogin
	 * @param string $sPassword
	 * @param string $sLoginAuthKey = ''
	 * @return bool
	 */
	public function Login($sLogin, $sPassword, $sLoginAuthKey = '')
	{
		$bReturn = false;

		$bPlain = ((bool) CApi::GetConf('login.enable-plain-auth', false)) && $this->IsSupported('AUTH=PLAIN');
		if ($bPlain)
		{
			$sAuth = base64_encode($sLoginAuthKey."\0".$sLogin."\0".$sPassword);

			$sTag = $this->getNextTag();
			$this->WriteLine($sTag.' AUTHENTICATE PLAIN');
			if (strtok(trim($this->ReadLine()), ' ') == '+')
			{
				$this->WriteLine($sAuth);
				$bReturn = $this->CheckResponse($sTag, $this->GetResponse($sTag));
			}
		}
		else
		{
			$bReturn = $this->SendCommand('LOGIN '.
				$this->escapeString($sLogin, true).' '.$this->escapeString($sPassword, true),
				array($this->escapeString($sPassword)));
		}

		return $bReturn;
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
		return $this->SendCommand('LOGOUT');
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
	public function GetNamespace()
	{
		$sNamespace = '';
		$sTag = $this->getNextTag();
		if ($this->WriteLine($sTag.' NAMESPACE'))
		{
			$sResponse = $this->GetResponse($sTag);
			if ($this->CheckResponse($sTag, $sResponse))
			{
				$a = array();
				if (false !== preg_match_all('/NAMESPACE \(\(".*?"\)\)/', $sResponse, $a)
					&& isset($a[0][0]) && is_string($a[0][0]))
				{
					$b = array();
					if (false !== preg_match('/\(\("([^"]*)" "/', $a[0][0], $b) && isset($b[1]))
					{
						$sNamespace = trim($b[1]);
					}
				}
			}
		}
		return $sNamespace;
	}


	/**
	 * @param string $sCmd
	 * @return bool
	 */
	public function SendLine($sCmd)
	{
		$sTag = $this->getNextTag();
		return $this->WriteLine($sTag.' '.$sCmd);
	}

	/**
	 * @param string $sCmd
	 * @param array $aHideValues = array()
	 * @return bool
	 */
	public function SendCommand($sCmd, $aHideValues = array())
	{
		$sTag = $this->getNextTag();
		if ($this->WriteLine($sTag.' '.$sCmd, $aHideValues))
		{
			return $this->CheckResponse($sTag, $this->GetResponse($sTag));
		}

		return false;
	}

	/**
	 * @param string $sTag
	 * @return string
	 */
	public function GetResponse($sTag)
	{
		$aResponse = array();
		$iLen = strlen($sTag);
		while(true)
		{
			$sLine = $this->ReadLine();
			if ($sLine == false)
			{
				break;
			}

			if (substr($sLine, 0, $iLen) === $sTag)
			{
				$aResponse[] = $sLine;
				break;
			}

			$aResponse[] = $sLine;
		}

		return trim(implode('', $aResponse));
	}

	/**
	 * @param string $sTag
	 * @param string $sResponse
	 * @return bool
	 */
	public function CheckResponse($sTag, $sResponse)
	{
		return ('OK' === substr($sResponse, strpos($sResponse, $sTag.' ') + strlen($sTag) + 1, 2));
	}

	/**
	 * @staticvar int $sTag
	 * @return string
	 */
	protected function getNextTag()
	{
		static $sTag = 1;
		return 'TAG'.($sTag++);
	}

	/**
	 * @param string $sLineForEscape
	 * @param bool $bAddQuot = false
	 * @return string
	 */
	protected function escapeString($sLineForEscape, $bAddQuot = false)
	{
		$sReturn = strtr($sLineForEscape, array('"' => '\\"'));
		return ($bAddQuot) ? '"'.$sReturn.'"' : $sReturn;
	}
}

