<?php

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

require_once(WM_ROOTPATH.'common/inc_constants.php');

class CPOP3
{
	/**
	 * @var bool
	 */
	var $socket;

	/**
	 * @var bool
	 */
	var $socket_status;

	/**
	 * @var string
	 */
	var $error;

	/**
	 * @var string
	 */
	var $state;

	/**
	 * @var string
	 */
	var $apop_banner;

	/**
	 * @var bool
	 */
	var $apop_detect;

	/**
	 * @param bool $bApopDetect = false
	 * @return void
	 */
	public function CPOP3($bApopDetect = false)
	{
		$this->socket = false;
		$this->socket_status = false;
		$this->error = 'No Errors';
		$this->state = 'DISCONNECTED';
		$this->apop_banner = '';
		$this->apop_detect = $bApopDetect;
	}

	/**
	 * @result void 
	 */
	public function _cleanup()
	{
		$this->state = 'DISCONNECTED';

		if (is_array($this->socket_status))
		{
			$this->socket_status = false;
		}

		if (is_resource($this->socket))
		{
			@fclose($this->socket);
			$this->socket = false;
		}
	}

	/**
	 * @param string $sDesc
	 * @return void
	 */
	public function _log($sDesc, $iLogLevel = ELogLevel::Full)
	{
		CApi::Log($sDesc, $iLogLevel);
	}

	/**
	 * @param string $sServer
	 * @param string $iPort = 110
	 * @param string $iTimeout = 10
	 * @param string $iSockTimeout = 10
	 * @return bool
	 */
	public function connect($sServer, $iPort = 110, $iTimeout = 10, $iSockTimeout = 10)
	{
		if ($this->socket)
		{
			$this->error = 'POP3 connect() - Error: Connection also avalible!';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if (!trim($sServer))
		{
			$this->error = 'POP3 connect() - Error: Please give a server address.';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if ($iPort < 1 && $iPort > 65535 || !trim($iPort))
		{
			$this->error = 'POP3 connect() - Error: Port not set or out of range (1 - 65535)';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if (!$this->_checkstate('connect'))
		{
			return false;
		}

		$isSsl = ((strlen($sServer) > 6) && strtolower(substr($sServer, 0, 6)) == 'ssl://');
		if (function_exists('openssl_open') && ($isSsl || $iPort == 995))
		{
			if (!$isSsl)
			{
				$sServer = 'ssl://'.$sServer;
			}
		}
		else
		{
			if ($isSsl)
			{
				$sServer = substr($sServer, 6);
			}
		}

		$sErrStr = '';
		$iErrNo = 0;

		$iTimeout = CApi::GetConf('socket.connect-timeout', 5);
		$iSockTimeout = CApi::GetConf('socket.get-timeout', 5);

		CApi::Plugin()->RunHook('webmail-pop3-update-socket-timeouts', array(&$iTimeout, &$iSockTimeout));

		$this->_log('POP3 : start connect to '.$sServer.':'.$iPort);

		$this->socket = @fsockopen($sServer, $iPort, $iErrNo, $sErrStr, $iTimeout);
		if (!$this->socket)
		{
			$this->error = 'POP3 connect() - Error: Can\'t connect to Server. Error: '.$iErrNo.' -- '.$sErrStr;
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		@socket_set_timeout($this->socket, $iSockTimeout);

		$sResponse = $this->_getnextstring();
		if (substr($sResponse, 0, 1) != '+')
		{
			$this->_cleanup();
			$this->error = 'POP3 connect() - Error: '.$sResponse;
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		$this->apop_banner = $this->_parse_banner($sResponse);
		$this->state = 'AUTHORIZATION';
		return true;
	}

	/**
	 * @param string $sUser
	 * @param string $sPass
	 * @param bool $bApop = false
	 * @return bool
	 */
	public function login($sUser, $sPass, $bApop = false)
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 login() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			$this->_cleanup();
			return false;
		}

		if ($this->_checkstate('login'))
		{
			if ($this->apop_detect && $this->apop_banner != '')
			{
				$bApop = true;
			}

			if (!$bApop)
			{
				if (!$this->_putline('USER '.$sUser))
				{
					return false;
				}

				$sResponse = $this->_getnextstring();
				if (substr($sResponse, 0, 1) != '+')
				{
					$this->error = 'POP3 login() - Error: '.$sResponse;
					$this->setGlobalErrorAndWriteLog();
					$this->_cleanup();
					return false;
				}

				if (!$this->_putline('PASS '.$sPass))
				{
					return false;
				}

				$sResponse = $this->_getnextstring();
				if (substr($sResponse, 0, 1) != '+')
				{
					$this->error = 'POP3 login() - Error: '.$sResponse;
					$this->setGlobalErrorAndWriteLog();
					$this->_cleanup();
					return false;
				}
				$this->state = 'TRANSACTION';
				return true;
			}
			else
			{
				if (empty($this->apop_banner))
				{
					$this->error = 'POP3 login() (APOP) - Error: No Server Banner -- aborted and close connection';
					$this->setGlobalErrorAndWriteLog();
					$this->_cleanup();
					return false;
				}

				if (!$this->_putline('APOP '.$sUser.' '.md5($this->apop_banner.$sPass)))
				{
					return false;
				}

				$sResponse = $this->_getnextstring();
				if (substr($sResponse, 0, 1) != '+')
				{
					$this->error = 'POP3 login() (APOP) - Error: '.$sResponse;
					$this->setGlobalErrorAndWriteLog();
					$this->_cleanup();
					return false;
				}
				$this->state = 'TRANSACTION';
				return true;
			}
		}
		return false;
	}

	/**
	 * @param int $iMsgNumber
	 * @param int $iLines = 0
	 * @return string | bool
	 */
	public function get_top($iMsgNumber, $iLines = 0)
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 get_top() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if (!$this->_checkstate('get_top'))
		{
			return false;
		}

		if (!$this->_putline('TOP '.$iMsgNumber.' '.$iLines))
		{
			return false;
		}

		$sResponse = $this->_getnextstring();
		if (substr($sResponse, 0, 3) != '+OK')
		{
			$this->error = 'POP3 get_top() - Error: '.$sResponse;
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		$sOutput = '';
		$sResponse = $this->_getnextstring();
		while (substr($sResponse, 0, 3) != ".\r\n")
		{
			if (strlen($sResponse) > 1 && substr($sResponse, 0, 2) == '..')
			{
				$sResponse = substr($sResponse, 1);
			}

			$sOutput .= $sResponse;
			$sResponse = $this->_getnextstring();
			if ($sResponse === false)
			{
				break;
			}
		}

		if ($iLines > 0)
		{
			for ($iG = 0; $iG < $iLines; $iG++)
			{
				if (substr($sResponse, 0, 3) == ".\r\n")
				{
					break;
				}

				if (strlen($sResponse) > 1 && substr($sResponse, 0, 2) == '..')
				{
					$sResponse = substr($sResponse, 1);
				}

				$sOutput .= $sResponse;
				$sResponse = $this->_getnextstring(false);
				if ($sResponse === false)
				{
					break;
				}
			}
		}

		$this->_resetTimeOut(true);
		return $sOutput;
	}

	/**
	 * @param int $iMsgNumber
	 * @param bool $bQmailer = false
	 * @return string | bool
	 */
	public function get_mail($iMsgNumber, $bQmailer = false)
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 get_mail() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			return FALSE;
		}

		if (!$this->_checkstate('get_mail'))
		{
			return false;
		}

		if (!$this->_putline('RETR '.$iMsgNumber))
		{
			return false;
		}

		$sResponse = $this->_getnextstring();
		if ($bQmailer)
		{
			if (substr($sResponse, 0, 1) != '.')
			{
				$this->error = 'POP3 get_mail() - qmailer Error: '.$sResponse;
				$this->setGlobalErrorAndWriteLog();
				return false;
			}
		}
		else
		{
			if (substr($sResponse, 0, 3) != '+OK')
			{
				$this->error = 'POP3 get_mail() - Error: '.$sResponse;
				$this->setGlobalErrorAndWriteLog();
				return false;
			}
		}

		$aOutput = array();
		$sResponse = $this->_getnextstring();
		while (substr($sResponse, 0, 3) != ".\r\n")
		{
			if (substr($sResponse, 0, 2) == '..')
			{
				$sResponse = substr($sResponse, 1);
			}

			$aOutput[] = $sResponse;
			$sResponse = $this->_getnextstring();
			if ($sResponse === false)
			{
				$aOutput = array();
				break;
			}
		}
		$this->_resetTimeOut(true);
		return implode('', $aOutput);
	}

	/**
	 * @param string $sString
	 * @return bool
	 */
	public function _checkstate($sString)
	{
		if ($sString == 'delete_mail' || $sString == 'get_office_status' || $sString == 'get_mail' ||
			$sString == 'get_top' || $sString == 'noop' || $sString == 'reset' ||
			$sString == 'uidl' || $sString == 'stats')
		{
			$sState = 'TRANSACTION';
			if ($this->state != $sState)
			{
				$this->error = 'POP3 _checkstate('.$sString.') - Error: state must be in "'.$sState.'" mode! Your state: "'.$this->state.'"!';
				$this->setGlobalErrorAndWriteLog();
				return false;
			}
			return true;
		}

		if ($sString == 'connect')
		{
			$sState = 'DISCONNECTED';
			$sStateOne = 'UPDATE';
			if ($this->state == $sState or $this->state == $sStateOne)
			{
				return true;
			}
			$this->error = 'POP3 _checkstate('.$sString.') - Error: state must be in "'.$sState.'" or "'.$sStateOne.'" mode! Your state: "'.$this->state.'"!';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if ($sString == 'login')
		{
			$sState = 'AUTHORIZATION';
			if ($this->state != $sState)
			{
				$this->error = 'POP3 _checkstate('.$sString.') - Error: state must be in "'.$sState.'" mode! Your state: "'.$this->state.'"!';
				$this->setGlobalErrorAndWriteLog();
				return false;
			}
			return true;
		}

		$this->error = 'POP3 _checkstate() - Error: Not allowed string given!';
		$this->setGlobalErrorAndWriteLog();
		return false;
	}

	/**
	 * @param int $iMsgNumber = 0
	 * @return bool
	 */
	public function delete_mail($iMsgNumber = 0)
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 delete_mail() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if (!$this->_checkstate('delete_mail'))
		{
			return false;
		}

		if ($iMsgNumber == 0)
		{
			$this->error = 'POP3 delete_mail() - Error: Please give a valid Messagenumber (Number can\'t be "0").';
			$this->setGlobalErrorAndWriteLog();
			return FALSE;
		}

		if (!$this->_putline('DELE '.$iMsgNumber))
		{
			return false;
		}

		$response = $this->_getnextstring();
		if (substr($response, 0, 1) != '+')
		{
			$this->error = 'POP3 delete_mail() - Error: '.$response;
			$this->setGlobalErrorAndWriteLog();
			return false;
		}
		return true;
	}

	/**
	 * @return array
	 */
	public function get_office_status()
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 get_office_status() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			$this->_cleanup();
			return false;
		}

		if (!$this->_checkstate('get_office_status'))
		{
			$this->_cleanup();
			return false;
		}

		if (!$this->_putline('STAT'))
		{
			return false;
		}

		$sResponse = $this->_getnextstring();
		if (substr($sResponse, 0, 3) != '+OK')
		{
			$this->error = 'POP3 get_office_status() - Error: '.$sResponse;
			$this->setGlobalErrorAndWriteLog();
			$this->_cleanup();
			return false;
		}

		$sResponse = trim($sResponse);
		if (preg_match('/[0-9]/', substr($sResponse, -1)))
		{
			$sResponse = substr($sResponse, 0, strlen($sResponse) - 1);
		}

		$aArray = explode(' ', $sResponse);
		$aOutput = array();
		$aOutput['count_mails'] = $aArray[1];
		$aOutput['octets'] = $aArray[2];
		unset($aArray);

		$sResponse = '';
		if ($aOutput['count_mails'] != '0')
		{

			if (!$this->_putline('LIST'))
			{
				return false;
			}

			$sResponse = $this->_getnextstring();
			if (substr($sResponse, 0, 3) != '+OK')
			{
				$this->error = 'POP3 get_office_status() - Error: '.$sResponse;
				$this->setGlobalErrorAndWriteLog();
				$this->_cleanup();
				return false;
			}

			$sResponse = '';
			for ($iIndex = 0; $iIndex < (int) $aOutput['count_mails']; $iIndex++)
			{
				$iNr = $iIndex + 1;
				$sResponse = trim($this->_getnextstring());
				$aArray = explode(' ', $sResponse);
				$aOutput[$iNr]['size'] = $aArray[1];
				$sResponse = '';
			}

			if (trim($this->_getnextstring()) != '.')
			{
				$this->error = 'POP3 get_office_status() - Error: Server does not send "." at the end.';
				$this->setGlobalErrorAndWriteLog();
				$this->_cleanup();
				return FALSE;
			}
			if (!$this->_putline('UIDL'))
			{
				return false;
			}

			$sResponse = $this->_getnextstring();
			if (substr($sResponse, 0, 3) != '+OK')
			{
				$this->error = 'POP3 get_office_status() - Error: '.$sResponse;
				$this->setGlobalErrorAndWriteLog();
				$this->_cleanup();
				return false;
			}

			for ($iIndex = 0; $iIndex < (int) $aOutput["count_mails"]; $iIndex++)
			{
				$iNr = $iIndex + 1;
				$sResponse = trim($this->_getnextstring());
				$aArray = explode(' ', $sResponse);
				$aOutput[$iNr]['uid'] = $aArray[1];
				$sResponse = '';
			}

			if (trim($this->_getnextstring()) != '.')
			{
				$this->error = 'POP3 get_office_status() - Error: Server does not send "." at the end.';
				$this->setGlobalErrorAndWriteLog();
				$this->_cleanup();
				return false;
			}
		}
		return $aOutput;
	}

	/**
	 * @return bool
	 */
	public function noop()
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 noop() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}
		if (!$this->_checkstate('noop'))
		{
			return false;
		}

		if (!$this->_putline('NOOP'))
		{
			return false;
		}

		$sResponse = $this->_getnextstring();
		if (substr($sResponse, 0, 1) != '+')
		{
			$this->error = 'POP3 noop() - Error: '.$sResponse;
			$this->setGlobalErrorAndWriteLog();
			return false;
		}
		return true;
	}

	/**
	 * @return bool
	 */
	public function reset()
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 reset() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if (!$this->_checkstate('reset'))
		{
			return false;
		}

		if (!$this->_putline('RSET'))
		{
			return false;
		}
		$sResponse = $this->_getnextstring();
		if (substr($sResponse, 0, 1) != '+')
		{
			$this->error = 'POP3 reset() - Error: '.$sResponse;
			$this->setGlobalErrorAndWriteLog();
			return false;
		}
		return true;
	}

	/**
	 * @return array | bool
	 */
	public function _stats()
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 _stats() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if (!$this->_checkstate('stats'))
		{
			return false;
		}
		if (!$this->_putline('STAT'))
		{
			return false;
		}

		$sResponse = $this->_getnextstring();
		if (substr($sResponse, 0, 1) != '+')
		{
			$this->error = 'POP3 _stats() - Error: '.$sResponse;
			$this->setGlobalErrorAndWriteLog();
			return FALSE;
		}

		$sResponse = trim($sResponse);

		$aArray = explode(' ', $sResponse);

		$aOutput = array();
		$aOutput['count_mails'] = $aArray[1];
		$aOutput['octets'] = $aArray[2];

		return $aOutput;
	}

	/**
	 * @param int $iMsgNumber = 0
	 * @return array | bool
	 */
	public function uidl($iMsgNumber = 0)
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 uidl() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if (!$this->_checkstate('uidl'))
		{
			return false;
		}

		if ($iMsgNumber == 0)
		{
			$aMails = $this->_stats();
			if (!$aMails)
			{
				return false;
			}

			if (!$this->_putline('UIDL'))
			{
				return false;
			}

			$sResponse = $this->_getnextstring();
			if (substr($sResponse, 0, 1) != '+')
			{
				$this->error = 'POP3 uidl() - Error: '.$sResponse;
				$this->setGlobalErrorAndWriteLog();
				return false;
			}

			$aOutput = array();
			for ($iIndex = 1, $c = (int) $aMails['count_mails']; $iIndex <= $c; $iIndex++)
			{
				$sResponse = $this->_getnextstring();
				$sResponse = trim($sResponse);
				$aArray = explode(' ', $sResponse);
				if (count($aArray) > 1)
				{
					$aOutput[(int) $aArray[0]] = $aArray[1];
				}
			}
			$this->_getnextstring();
			$this->_resetTimeOut(true);
			return $aOutput;
		}
		else
		{
			if (!$this->_putline('UIDL '.$iMsgNumber))
			{
				return false;
			}

			$sResponse = $this->_getnextstring();
			if (substr($sResponse, 0, 1) != '+')
			{
				$this->error = 'POP3 uidl() - Error: '.$sResponse;
				$this->setGlobalErrorAndWriteLog();
				return false;
			}

			$sResponse = trim($sResponse);
			$aArray = explode(' ', $sResponse);
			if (count($aArray) > 2)
			{
				$aOutput[(int) $aArray[1]] = $aArray[2];
			}

			return $aOutput;
		}
	}

	/**
	 * @param int $iMsgNumber = 0
	 * @return array | bool
	 */
	public function msglist($iMsgNumber = 0)
	{
		if (!$this->socket)
		{
			$this->error = 'POP3 uidl() - Error: No connection avalible.';
			$this->setGlobalErrorAndWriteLog();
			return false;
		}

		if (!$this->_checkstate('uidl'))
		{
			return false;
		}

		if ($iMsgNumber == 0)
		{
			$aMails = $this->_stats();
			if (!$aMails)
			{
				return false;
			}
			if (!$this->_putline('LIST'))
			{
				return false;
			}

			$sResponse = $this->_getnextstring();
			if (substr($sResponse, 0, 1) != '+')
			{
				$this->error = 'POP3 uidl() - Error: '.$sResponse;
				$this->setGlobalErrorAndWriteLog();
				return false;
			}

			$aOutput = array();
			for ($iIndex = 1, $iLen = (int) $aMails['count_mails']; $iIndex <= $iLen; $iIndex++)
			{
				$sResponse = $this->_getnextstring();
				$sResponse = trim($sResponse);
				$aArray = explode(' ', $sResponse);
				$aOutput[$iIndex] = $aArray[1];
				if (count($aArray) > 1)
				{
					$aOutput[(int) $aArray[0]] = $aArray[1];
				}
			}

			$this->_getnextstring();
			$this->_resetTimeOut(true);
			return $aOutput;
		}
		else
		{
			if (!$this->_putline('LIST '.$iMsgNumber))
			{
				return false;
			}

			$sResponse = $this->_getnextstring();
			if (substr($sResponse, 0, 1) != '+')
			{
				$this->error = 'POP3 uidl() - Error: '.$sResponse;
				$this->setGlobalErrorAndWriteLog();
				return FALSE;
			}

			$sResponse = trim($sResponse);
			$aArray = explode(' ', $sResponse);
			if (count($aArray) > 2)
			{
				$aOutput[(int) $aArray[1]] = $aArray[2];
			}

			return $aOutput;
		}
	}

	/**
	 * @return bool
	 */
	public function close()
	{
		if (!$this->_putline('QUIT'))
		{
			return false;
		}

		if ($this->state == 'AUTHORIZATION')
		{
			$this->state = 'DISCONNECTED';
		}
		elseif ($this->state == 'TRANSACTION')
		{
			$this->state = 'UPDATE';
		}

		$sResponse = $this->_getnextstring();
		if (substr($sResponse, 0, 1) != '+')
		{
			$this->error = 'POP3 close() - Error: '.$sResponse;
			$this->setGlobalErrorAndWriteLog();
			return false;
		}
		$this->socket = false;
		$this->_cleanup();
		return true;
	}

	/**
	 * @param bool $isLog = true
	 * @return string
	 */
	public function _getnextstring($isLog = true)
	{
		$this->_resetTimeOut();
		$sBuffer = @fgets($this->socket, 2048);
		if ($isLog)
		{
			$this->_log('POP3 < '.ConvertUtils::ShowCRLF($sBuffer));
		}

		if (false === $sBuffer)
		{
			$this->socket_status = @socket_get_status($this->socket);
			if (isset($this->socket_status['timed_out']) && $this->socket_status['timed_out'])
			{
				$this->_cleanup();
				$this->error = "Socket timeout reached during POP3 connection.";
				$this->setGlobalErrorAndWriteLog();
			}
		}
		$this->socket_status = false;
		return $sBuffer;
	}

	/**
	 * @param string $sString
	 * @param bool $bIsLog = true
	 * @return bool
	 */
	public function _putline($sString, $bIsLog = true)
	{
		if ($bIsLog)
		{
			$this->_log('POP3 > '.ConvertUtils::ShowCRLF($sString));
		}

		$line = $sString."\r\n";

		$this->_resetTimeOut();
		if (!@fwrite($this->socket, $line, strlen($line)))
		{
			$this->error = 'POP3 _putline() - Error while send "'.$sString.'". -- Connection closed.';
			$this->setGlobalErrorAndWriteLog();
			$this->_cleanup();
			return false;
		}
		return true;
	}

	/**
	 * @param string &$sServerText
	 * @return string
	 */
	public function _parse_banner(&$sServerText)
	{
		$bOutside = true;
		$sBanner = '';
		$iLength = strlen($sServerText);
		for ($iCount = 0; $iCount < $iLength; $iCount++)
		{
			$sDigit = substr($sServerText, $iCount, 1);
			if ($sDigit != '')
			{
				if (!$bOutside && $sDigit != '<' && $sDigit != '>')
				{
					$sBanner .= $sDigit;
					continue;
				}
				if ($sDigit == '<')
				{
					$bOutside = false;
				}
				elseif ($sDigit == '>')
				{
					$bOutside = true;
				}
			}
		}

		$sBanner = trim($sBanner);
		if (strlen($sBanner) != 0)
		{
			return '<'.$sBanner.'>';
		}
		return '';
	}

	/**
	 * @return void 
	 */
	public function setGlobalErrorAndWriteLog()
	{
		if (strlen($this->error) > 0)
		{
			setGlobalError($this->error);
			$this->_log('POP3 Error: '.$this->error, ELogLevel::Error);
		}
	}

	/**
	 * @param bool $bForce
	 * @return void
	 */
	public function _resetTimeOut($bForce = false)
	{
		static $iStaticTime = null;

		$Time = time();
		if ($bForce || $iStaticTime < $Time - RESET_TIME_LIMIT_RUN)
		{
			@set_time_limit(RESET_TIME_LIMIT);
			$iStaticTime = $Time;
		}
	}
}
