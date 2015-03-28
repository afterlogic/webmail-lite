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
class CApiPop3MailProtocol extends CApiNetAbstract
{
	/**
	 * @return bool
	 */
	public function Connect()
	{
		$bResult = false;
		if (parent::Connect())
		{
			$bResult = $this->CheckResponse($this->GetNextLine());
		}
		return $bResult;
	}

	/**
	 * @param string $sLogin
	 * @param string $sPassword
	 * @param string $sLoginAuthKey = ''
	 * @param string $sProxyAuthUser = ''
	 * @return bool
	 */
	public function Login($sLogin, $sPassword, $sLoginAuthKey = '', $sProxyAuthUser = '')
	{
		return $this->SendCommand('USER '.$sLogin) && $this->SendCommand('PASS '.$sPassword, array($sPassword));
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
		return $this->SendCommand('QUIT');
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
		return '';
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
	 * @param string $sCmd
	 * @param array $aHideValues = array()
	 * @return bool
	 */
	public function SendCommand($sCmd, $aHideValues = array())
	{
		if ($this->WriteLine($sCmd, $aHideValues))
		{
			return $this->CheckResponse($this->GetNextLine());
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function GetNextLine()
	{
		return $this->ReadLine();
	}

	/**
	 * @param string $sResponse
	 * @return bool
	 */
	public function CheckResponse($sResponse)
	{
		return ('+OK' === substr($sResponse, 0, 3));
	}
}
