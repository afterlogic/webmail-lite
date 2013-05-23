<?php

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../../'));

require_once(WM_ROOTPATH.'common/inc_constants.php');

/* Constant FLAGS */
define('HKC_ALL_MSG', 'MESSAGES');			/* USED TO RETRIVE NUMBER OF ALL MESSAGES IN MAILBOX */
define('HKC_RECENT_MSG', 'RECENT');			/* USED TO RETRIVE NUMBER OF RECENT MESSAGES IN MAILBOX */
define('HKC_UNSEEN_MSG', 'UNSEEN');			/* USED TO RETRIVE NUMBER OF UNSEEN MESSAGES IN MAILBOX */
define('HKC_UID_NEXT', 'UIDNEXT');			/* USED TO RETRIVE UIDNEXT NUMBER */
define('HKC_UID_VALIDITY', 'UIDVALIDITY');	/* USED TO RETRIVE UIDVALIDITY NUMBER */

define('USE_LSUB', false);

/**
 * @author Harish Chauhan
 */
class IMAPMAIL
{
	var $host;			/* host like 127.0.0.1 or mail.yoursite.com */
	var $port;			/* port default is 110 or 143 */
	var $user;			/* user for logon */
	var $proxyuser;		/* user for PROXYAUTH */
	var $password;		/* user paswword */
	var $state;			/* variable define diffrent state of connection */
	var $lastSelect;
	var $connection;	/* handle to a open connection */
	var $error;			/* error string */
	var $must_update;
	var $tag;
	var $mail_box;
	var $response_text;

	var $_oLog = null;
	var $_bLogEnable = true;
	var $_bCurrentFolderSupportForwardFlag = false;

	var $_capas = null;

	function IMAPMAIL()
	{
		$this->host = null;
		$this->port = 143;
		$this->user = '';
		$this->password = '';
		$this->state = 'DISCONNECTED';
		$this->lastSelect = '';
		$this->proxyuser = null;

		$this->error = '';
		$this->must_update = false;
		$this->UpdateTag();
	}

	/**
	 * @param string $sDesc
	 */
	function _log($sDesc, $iLogLevel = ELogLevel::Full)
	{
		CApi::Log($sDesc, $iLogLevel);
	}

	/**
	 * This functiuon set the host
	 * @example popmail::set_host("mail.yoursite.com")
	 *
	 * @param string $host
	 */
	function set_host($host)
	{
		$this->host = $host;
	}

	/**
	 * This functiuon set the port
	 * @example popmail::set_port(110)
	 *
	 * @param int $port
	 */
	function set_port($port)
	{
		$this->port = $port;
	}

	/**
	 * This functiuon is to retrive the error of last operation
	 * @example popmail::get_error()
	 *
	 * @return string
	 */
	function get_error()
	{
		return $this->error;
	}

	/**
	 * This functiuon is to retrive the state of connaction
	 *
	 * @return string
	 */
	function get_state()
	{
		return $this->state;
	}

	/**
	 * Function is used to open connection
	 *
	 * @param	string	$host
	 * @param	int		$port
	 * @return	bool
	 */
	function open($host = '', $port = '', $persistent = false)
	{
		if (!empty($host))
		{
			$this->host = $host;
		}
		if (!empty($port))
		{
			$this->port = $port;
		}

		return $this->open_connection($persistent);
	}

	/**
	 * close the active connection
	 *
	 * @return bool
	 */
	function close()
	{
		if ($this->must_update)
		{
			$this->close_mailbox();
		}
		$this->logout();
		@fclose($this->connection);
		$this->connection = null;
		$this->state = 'DISCONNECTED';
		return true;
	}

	/*
	 * The Functions is written bellow is the subordinate functions used in
	 * communication with SERVER.
	 */

	/* This function is used to get response line from server */
	function get_line($isLog = true)
	{
		$return = @fgets($this->connection, 2048);
		if ($this->_bLogEnable && $isLog)
		{
		    $this->_log('IMAP4 < '.ConvertUtils::ShowCRLF($return));
		}

		if (false === $return)
		{
		    $_socket_status = @socket_get_status($this->connection);
		    if (isset($_socket_status['timed_out']) && $_socket_status['timed_out'])
		    {
			  $this->error = 'Error : Socket timeout reached during IMAP4 connection.';
		    }
		}

		$this->_resetTimeOut();
		return $return;
	}

	/* This functiuon is to retrive the full response message from server */
	function get_server_responce($isLog = true, $asArray = false)
	{
		$response = array();
		$l = strlen($this->tag);
		while(1)
		{
			$new = $this->get_line($isLog);
			if ($new == false)
			{
				break;
			}

			if (substr($new, 0, $l) == $this->tag)
			{
				$response[] = $new;
				break;
			}

			$response[] = $new;
		}
		$this->_resetTimeOut(true);
		$response = ($asArray) ? $response : trim(implode('', $response));
		return $response;
	}

	/**
	 * this functiuon is to send the command to server
	 *
	 * @param string $msg
	 * @return bool
	 */
	function put_line($msg = '', $isLog = true)
	{
		if ($this->_bLogEnable && $isLog)
		{
			$this->_log('IMAP4 > '.$msg);
		}
		$this->_resetTimeOut();
		if (!@fputs($this->connection, $msg."\r\n"))
		{
			$this->error = 'Error : Could not send user request.';
			return false;
		}
		return true;
	}

	/**
	 * @param string $msg
	 * @return bool
	 */
	function put_line_with_continuations($msg = '', $isLog = true)
	{
		$aList = explode("\r\n", $msg);
		$aResult = array();
		$sCmd = $sLine = '';

		foreach ($aList as $sLine)
		{
			$sLine .= "\r\n";
			if (preg_match('/{[\d]+}\r\n$/', $sLine))
			{
				$sCmd .= $sLine;
				$aResult[] = $sCmd;
				$sCmd = '';
			}
			else
			{
				$sCmd .= $sLine;
			}
		}

		if (!empty($sCmd))
		{
			$aResult[] = $sCmd;
		}

		$mResult = false;
		$iResultCount = count($aResult);
		if (1 < $iResultCount)
		{
			foreach ($aResult as $iIndex => $sLine)
			{
				$mResult = $this->put_line(rtrim($sLine, "\r\n"), $isLog);
				if (false === $mResult)
				{
					break;
				}
				else if ($iIndex === $iResultCount - 1)
				{
					$mResult = $this->get_server_responce();
					break;
				}
				else
				{
					$sResultLine = $this->get_line($isLog);
					if (false !== $sResultLine && '+ ' === substr($sResultLine, 0, 2))
					{
					}
					else
					{
						$mResult = false;
						break;
					}
				}
			}
		}
		else
		{
			if ($this->put_line(rtrim($aResult[0], "\r\n"), $isLog))
			{
				$mResult = $this->get_server_responce();
			}
		}

		return $mResult;
	}

	/**
	 * @return bool
	 */
	function IsGmail()
	{
		return '.gmail.com' === substr(strtolower($this->host), -10);
	}

	/*
	 * The Functions is written bellow is the subordinate functions used in
	 * communication with SERVER.
	 */

	/**
	 * This functiuon is to open the mailbox
	 * Arguments 1: mailbox name 2: open as read only or read write mode.
	 *
	 * @param string $mailbox_name = 'INBOX'
	 * @param bool $read_only = false
	 * @return bool
	 */
	function open_mailbox($mailbox_name = 'INBOX', $read_only = false, $force = false)
	{
		$result = ($read_only)
			? $this->examine_mailbox($mailbox_name, $force)
			: $this->select_mailbox($mailbox_name, $force);

		if ($result)
		{
			$this->mail_box = $mailbox_name;
		}
		else
		{
			$_error = 'IMAP4: Can\'t '.(($read_only) ? 'examine' : 'select' ).' folder ('.$mailbox_name.').';
			$this->_log($_error, ELogLevel::Error);
			setGlobalError($_error);
		}

		return $result;
	}

	/**
	 * @param string $mailboxName
	 * @return array
	 */
	function get_all_and_unnread_msg_count($mailboxName)
	{
		$response = $this->get_status($mailboxName, HKC_ALL_MSG.' '.HKC_UNSEEN_MSG);
		if (!$response)
		{
			return null;
		}

		$count_array = array(
			HKC_ALL_MSG => 0,
			HKC_UNSEEN_MSG => 0
		);

		$matches = array();
		preg_match('/'.HKC_ALL_MSG.'\s(\d+)/', $response, $matches);
		if (isset($matches[1]) && is_numeric($matches[1]))
		{
			$count_array[HKC_ALL_MSG] = (int) $matches[1];
		}

		$matches = array();
		preg_match('/'.HKC_UNSEEN_MSG.'\s(\d+)/', $response, $matches);
		if (isset($matches[1]) && is_numeric($matches[1]))
		{
			$count_array[HKC_UNSEEN_MSG] = (int) $matches[1];
		}

		return $count_array;
	}

	/**
	 * @return array
	 */
	function &get_uidlist()
	{
		$null = null;
		$resultArray = array();
		if (!$this->_checkState_0('SELECTED')) return $null;

		if ($this->get_mailbox_count($this->response_text) < 1)
		{
			return $resultArray;
		}

		$this->UpdateTag('FTC');
		if ($this->put_line($this->tag.' FETCH 1:* (UID)'))
		{
			$response = $this->get_server_responce();
			if (!$this->_checkResponse($response))
			{
				return $null;
			}

			$temp_arr = explode("\r\n", $response);
			array_shift($temp_arr);
			array_pop($temp_arr);

			for ($i = 0, $c = count($temp_arr); $i < $c; $i++)
			{
				$str = substr($temp_arr[$i], 2, strlen($temp_arr[$i]) - 3);
				$parts = explode(' ', $str, 2);
				if (count($parts) == 2)
				{
					$pts = explode(' ', $parts[1]);
					if (count($pts) >= 3)
					{
						$resultArray[$parts[0]] = substr($pts[0].' '.$pts[1].' '.$pts[2], 11);
					}
				}
			}
			return $resultArray;
		}

		return $null;
	}

	/**
	 * @return Array
	 */
	function &get_flaglist()
	{
		$null = null;
		$resultArray = array();
		if (!$this->_checkState_0('SELECTED')) return $null;

		if ($this->get_mailbox_count($this->response_text) < 1)
		{
			return $resultArray;
		}

		$this->UpdateTag('FTC');
		if ($this->put_line($this->tag.' FETCH 1:* (FLAGS)'))
		{
			$response = $this->get_server_responce();
			if (!$this->_checkResponse($response))
			{
				return $null;
			}

			$temp_arr = explode("\r\n", $response);
			array_shift($temp_arr);
			array_pop($temp_arr);

			for ($i = 0, $c = count($temp_arr); $i < $c; $i++)
			{
				$str = substr($temp_arr[$i], 2, strlen($temp_arr[$i])-3);
				$parts = explode(' ', $str, 2);
				if (count($parts) == 2)
				{
					$resultArray[$parts[0]] = trim(substr($parts[1], 14), ')');
				}
			}
			return $resultArray;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	function isMailBoxEmpty()
	{
		return ($this->get_mailbox_count($this->response_text) < 1);
	}

	function FillBodyStructureByMode($messageUid, $mode, &$bodyStructureObject)
	{
		if (!$this->_checkState_0('SELECTED')) return false;
		$this->UpdateTag('FTC');

		$requestAdd = $bodyStructureObject->GetRequestByMode($mode);
		if (strlen($requestAdd) > 0)
		{
			$request = $this->tag.' UID FETCH '.$messageUid.' ('.$requestAdd.')';
			if ($this->put_line($request))
			{
				$response = $this->get_server_responce(true, true);
				if ($this->_checkResponseAsArray($response))
				{
					array_pop($response);
					$this->_parseResponseForBodyStructure($bodyStructureObject, $mode, implode('', $response));
				}
			}
		}
	}

	function _parseResponseForBodyStructure(&$bodyStructureObject, $mode, $response)
	{
		if (false !== strpos($response, 'BODY[HEADER] {'))
		{
			$headerSize = -1;
			$aHeaderSize = array();
			preg_match('/BODY\[HEADER\] \{([\d]+)\}/', $response, $aHeaderSize);
			if (isset($aHeaderSize[0], $aHeaderSize[1]))
			{
				$headerStr = $this->_searchAndGetLiteralText($response, $aHeaderSize[0], $aHeaderSize[1]);
				if (strlen($headerStr) > 0)
				{
					$bodyStructureObject->SetFullHeaders($headerStr);
				}
			}
		}

		if (($mode & 2) == 2 || ($mode & 4) == 4 || ($mode & 8) == 8 || ($mode & 16) == 16 || ($mode & 32) == 32 || ($mode & 64) == 64 || ($mode & 256) == 256)
		{
			$aBodyPart = array();
			preg_match_all('/BODY\[([\d\.]+)\] \{([\d]+)\}/', $response, $aBodyPart);
			if (isset($aBodyPart[0]) && is_array($aBodyPart[0]) && count($aBodyPart[0]) > 0)
			{
				for ($i = 0, $c = count($aBodyPart[0]); $i < $c; $i++)
				{
					if (isset($aBodyPart[0][$i], $aBodyPart[1][$i], $aBodyPart[2][$i]))
					{
						$key = $aBodyPart[1][$i];
						$text = $this->_searchAndGetLiteralText($response, $aBodyPart[0][$i], $aBodyPart[2][$i]);
						if (strlen($text) > 0 && strlen($key) > 0)
						{
							$bodyStructureObject->SetBodyPart($key, $text);
						}
					}
				}
			}
		}
	}

	function _searchAndGetLiteralText($response, $literalPrefix, $size)
	{
		$outStr = '';
		$size = (int) $size;
		if ($size > 0)
		{
			$start = strpos($response, $literalPrefix);
			if (false !== $start)
			{
				$start += strlen($literalPrefix) + 2;
				$outStr = substr($response, $start, $size);
			}
		}
		return $outStr;
	}

	function getBodyPartByIndex($bsIndex, $messageUid)
	{
		if (!$this->_checkState_0('SELECTED')) return false;
		$this->UpdateTag('FTC');
		if ($this->put_line($this->tag.' UID FETCH '.$messageUid.' BODY.PEEK['.$bsIndex.']'))
		{
			$response = $this->get_server_responce(true);
			if ($this->_checkResponse($response))
			{
				$msgSizeTemp = array();
				preg_match('/BODY\[[\d\.]+\] \{([\d]+)\}/', $response, $msgSizeTemp);
				if (isset($msgSizeTemp[0], $msgSizeTemp[1]))
				{
					$pos = strpos($response, $msgSizeTemp[0]);
					$size = (int) $msgSizeTemp[1];
					if (false !== $pos && $size > 0)
					{
						$pos = $pos + strlen($msgSizeTemp[0]) + 2;
						return substr($response, $pos, $size);
					}
				}
			}
		}
		return '';
	}

	/**
	 * @param string $_index = null
	 * @param bool $_idexAsUid = true
	 * @return CBodyStructureObject
	 */
	function getMessageBodyStructure($_index, $_idexAsUid = true)
	{
		if (!$this->_checkState_0('SELECTED')) return false;
		$this->UpdateTag('FTC');
		$request = ($_idexAsUid)
			? $this->tag.' UID FETCH '.$_index.' (FLAGS UID RFC822.SIZE BODYSTRUCTURE)'
			: $this->tag.' FETCH '.$_index.' (FLAGS UID RFC822.SIZE BODYSTRUCTURE)';

		if ($this->put_line($request))
		{
			$response = $this->get_server_responce(true, true);
			if ($this->_checkResponseAsArray($response))
			{
				array_pop($response);
				return new CBodyStructureObject(implode('', $response));
			}
		}
		return false;
	}

	/**
	 * @param string $_index = null
	 * @param bool $_idexAsUid = true
	 * @return array/bool
	 */
	function getParamsMessages($_index = null, $_idexAsUid = true)
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		if ($this->isMailBoxEmpty())
		{
			return array();
		}

		$this->UpdateTag('FTC');
		$_line = (null !== $_index)
					? (
						($_idexAsUid)
							? $this->tag.' UID FETCH '.$_index.' (FLAGS UID RFC822.SIZE)'
							: $this->tag.' FETCH '.$_index.' (FLAGS UID RFC822.SIZE)'
					) : $this->tag.' FETCH 1:* (FLAGS UID RFC822.SIZE)';

		if ($this->put_line($_line))
		{
			$response = $this->get_server_responce();
			if ($this->_checkResponse($response))
			{
				return $this->_parseParams($response);
			}
		}

		return false;
	}

	/**
	 * @param	string	$response
	 * @return	array | false
	 */
	function _parseParams($response)
	{
		$temp_arr = explode("\r\n", trim($response));
		array_pop($temp_arr);

		$resultArray = $temp1 = $temp2 = $temp3 = $temp4 = array();
		for ($i = 0, $c = count($temp_arr); $i < $c; $i++)
		{
			$_trim_temp_array =  trim($temp_arr[$i]);
			preg_match('/\* ([\d]+) FETCH/', $_trim_temp_array, $temp1);
			preg_match('/FLAGS \(([^\)]*)\)/', $_trim_temp_array, $temp2);
			preg_match('/UID ([\d]+)/', $_trim_temp_array, $temp3);
			preg_match('/RFC822\.SIZE ([\d]+)/', $_trim_temp_array, $temp4);

			$id = isset($temp1[1]) ? (int) $temp1[1] : -1;
			if ($id > 0)
			{
				$flag =  isset($temp2[1]) ? trim(trim($temp2[1]), '()') : '';
				$uid = isset($temp3[1]) ? (int) $temp3[1] : -1;
				$size = isset($temp4[1]) ? (int) trim(trim($temp4[1]), ')') : 0;

				$resultArray[$id] = array('uid' => $uid, 'flag' => $flag, 'size' => $size);
			}
		}

		return $resultArray;
	}

	/**
	 * @return array/bool
	 */
	function getFastUids()
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		if ($this->isMailBoxEmpty())
		{
			return array();
		}

		$this->UpdateTag('SRCH');

		if ($this->put_line($this->tag.' UID SEARCH ALL'))
		{
			$response = $this->get_server_responce();
			if ($this->_checkResponse($response))
			{
				return $this->_parseFastUids($response);
			}
		}

		return false;
	}

	/**
	 * @param	string	$response
	 * @return	array | false
	 */
	function _parseFastUids($response)
	{
		$temp_arr = explode("\r\n", trim($response));
		array_pop($temp_arr);

		$resultArray = array();
		for ($i = 0, $c = count($temp_arr); $i < $c; $i++)
		{
			$_trim_temp_line = trim($temp_arr[$i]);
			$_trim_temp_line = str_replace('* SEARCH ', '', $_trim_temp_line);

			$_trim_temp_line_array = explode(' ', trim($_trim_temp_line));

			if (count($_trim_temp_line_array) > 0)
			{
				foreach($_trim_temp_line_array as $uid)
				{
					$resultArray[(int) $uid] = '';
				}
			}
		}

		$resultArray = count($resultArray) > 0 ? array_keys($resultArray) : $resultArray;
		return $resultArray;
	}

	/* function retrives the full message from server. */
	function get_message_header($msgno, $uidFetch = false)
	{
		$rString = 'BODY.PEEK[HEADER]';
		//$rString = 'BODY.PEEK[HEADER.FIELDS (RETURN-PATH RECEIVED MIME-VERSION FROM TO CC DATE SUBJECT X-MSMAIL-PRIORITY IMPORTANCE X-PRIORITY CONTENT-TYPE)]';
		$response = ($uidFetch)
			? $this->uid_fetch_mail($msgno, $rString)
			: $this->fetch_mail($msgno, $rString);

		$temp_arr = explode("\n", $response);
		array_shift($temp_arr);
		array_shift($temp_arr);
		array_pop($temp_arr);
		array_pop($temp_arr);
		return implode("\n", $temp_arr);
	}

	function getResponseAsArray($request)
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		$this->UpdateTag();
		$request = $this->tag.' '.$request;
		if ($this->put_line($request))
		{
			$response = $this->get_server_responce(true, true);
			if ($this->_checkResponseAsArray($response))
			{
				array_pop($response);
				return $response;
			}
		}

		return false;
	}

	/* Function retrives the full message from server. */
	function get_message($msgno, $uidFetch = false)
	{
		$response = ($uidFetch)
		    ? $this->uid_fetch_mail($msgno, 'BODY.PEEK[]')
		    : $this->fetch_mail($msgno, 'BODY.PEEK[]');

		$response = explode("\n", $response, 2);
		$respHead = explode('BODY[]', $response[0], 2);
		if (count($respHead) == 2)
		{
			$msgSize = (int) trim(trim($respHead[1]), '{}');
			return substr($response[1], 0, $msgSize);
		}

		return '';
	}

	/* Function retrives the full message from server. */
	function getMessageWithFlag($msgUid)
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		$this->UpdateTag('FTC');
		if ($this->put_line($this->tag.' UID FETCH '.trim($msgUid).' (FLAGS BODY.PEEK[])'))
		{
			$response = $this->get_server_responce();
			if ($this->_checkResponse($response))
			{
				$return = array('', '');

				$index = 0;
				$trim = -1;
				$responseArray = explode("\n", $response);
				unset($response);
				$cnt = count($responseArray);
				do
				{
					if (preg_match('/\* [\d]+ FETCH/', $responseArray[$index++]))
					{
						$trim = --$index;
						break;
					}
				}
				while($cnt > $index);

				$responseOut = array();
				if ($trim > -1)
				{
					$responseArray = array_slice($responseArray, $trim);
					if (count($responseArray) > 1)
					{
						$responseOut[] = $responseArray[0];
						$responseOut[1] = implode("\n", array_slice($responseArray, 1));
					}
				}
				unset($responseArray);

				if (count($responseOut) > 1)
				{
					$fetchTemp = $flagsTemp = $msgSizeTemp = array();
					preg_match('/\* ([\d]+) FETCH/', $responseOut[0], $fetchTemp);
					preg_match('/FLAGS \(([^\)]*)\)/', $responseOut[0], $flagsTemp);
					preg_match('/BODY\[\] \{([\d]+)\}/', $responseOut[0], $msgSizeTemp);

					$flag = '';
					$size = 0;
					$id = isset($fetchTemp[1]) ? (int) $fetchTemp[1] : -1;
					if ($id > 0)
					{
						$flag =  isset($flagsTemp[1]) ? trim(trim($flagsTemp[1]), '()') : '';
						$size = isset($msgSizeTemp[1]) ? (int) trim($msgSizeTemp[1]) : 0;
					}

					if ($size > 0)
					{
						$return[0] = substr($responseOut[1], 0, $size);
						$return[1] = $flag;
						return $return;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Function put a delete flag the message and when you close the mail box then
	 * message flagged as deleted
	 *
	 * @param string $msgno
	 * @return bool
	 */
	function delete_message($msgno)
	{
		$this->must_update = true;
		return $this->store_mail_flag($msgno, '+Flags', '\Deleted');
	}

	/* function put a delete flag the message */
	function rollback_delete($msgno)
	{
		$this->must_update = true;
		return $this->store_mail_flag($msgno, '-Flags', '\Deleted');
	}

	/*
	 * The Functions is written bellow is the main commands defined in IMAP
	 * protocol.
	 */

	/* This functiuon is to open the connection to the server */
	function open_connection()
	{
		if (!$this->_checkState_0('DISCONNECTED')) return false;

		if (empty($this->host) || empty($this->port))
		{
			$this->error = 'Error : Either HOST or PORT is undifined!';
			return false;
		}

		$host = $this->host;
		$isSsl = ((strlen($host) > 6) && strtolower(substr($host, 0, 6)) == 'ssl://');
		if (function_exists('openssl_open') && ($isSsl || $this->port == 993))
		{
			if (!$isSsl)
			{
				$host = 'ssl://'.$host;
			}
		}
		else
		{
			if ($isSsl)
			{
				$host = substr($host, 6);
			}
		}

		$errstr = '';
		$errno = 0;

		$sConnectTimeout = CApi::GetConf('socket.connect-timeout', 5);
		$sFgetTimeout = CApi::GetConf('socket.get-timeout', 5);

		CApi::Plugin()->RunHook('webmail-imap-update-socket-timeouts',
			array(&$sConnectTimeout, &$sFgetTimeout));

		$this->_log('IMAP4 : start connect to '.$host.':'.$this->port);
		$this->connection = @fsockopen($host, $this->port, $errno, $errstr, $sConnectTimeout);
		if (!$this->connection)
		{
			$this->error = 'Could not make a connection to server , Error : '.$errstr.' ('.$errno.')';
			return false;
		}

		/* set socket timeout
         * it is valid for all other functions! */
		@socket_set_timeout($this->connection, $sFgetTimeout);
        /* socket_set_blocking($this->connection, true); */

		$this->get_line();
		$this->state = 'AUTHORIZATION';
		// $this->InitCapa();
		return true;
	}

	function incSokectTimeout($sFgetTimeout = 60)
	{
		if (is_resource($this->connection))
		{
			@socket_set_timeout($this->connection, $sFgetTimeout);
		}
	}

	/**
	 * The get_capability function returns a listing of capabilities that the
	 * server supports.
	 *
	 * @return string|false
	 */
	function get_capability()
	{
		$this->UpdateTag('CAP');
		if ($this->put_line($this->tag.' CAPABILITY'))
		{
			$response = $this->get_server_responce();
			if ($this->_checkResponse($response))
			{
				return $response;
			}
		}

		return false;
	}

	/**
	 * noop function can be used as a periodic poll for new messages or
	 * message status updates during a period of inactivity
	 *
	 * @return bool
	 */
	function noop()
	{
		$this->UpdateTag('NP');
		if ($this->put_line($this->tag.' NOOP'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * The logout function informs the server that the client is done with the connection.
	 *
	 * @return bool
	 */
	function logout()
	{
		$this->UpdateTag('LGT');
		if ($this->put_line($this->tag.' LOGOUT'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * This function is used to authenticate the user
	 * arguments $auth_str is a authorization String example LOGIN
	 * $ans_str1 and $ans_str2 is a base 64 encoded answer string to server
	 * Example if it authentication type is login then user your userid and password
	 * as ans_str1 and ans_str2
	 *
	 * @param string $auth_str
	 * @param string $ans_str1 = ''
	 * @param string $ans_str2 = ''
	 * @return string|false
	 */
	function authenticate($auth_str, $ans_str1 = '', $ans_str2 = '')
	{
		if (!$this->_checkState_1('DISCONNECTED')) return false;
		if (!$this->_checkState_1('AUTHENTICATED')) return false;

		$this->UpdateTag('AUTH');
		if ($this->put_line($this->tag.' AUTHENTICATE '.$auth_str))
		{
			if (!empty($ans_str1))
			{
				$response = $this->get_line();
				if (strtok($response, ' ') == '+')
				{
					$ans_str1 = base64_encode($ans_str1);
					$this->put_line($ans_str1);
				}
				else
				{
					$this->error = 'Error : response'.$response;
					return false;
				}
			}

			if (!empty($ans_str2))
			{
				$response = $this->get_line();
				if (strtok($response,' ') == '+')
				{
					$ans_str2 = base64_encode($ans_str2);
					$this->put_line($ans_str2);
				}
				else
				{
					$this->error = 'Error : response'.$response;
					return false;
				}
			}

			$response = $this->get_line();
			if ($this->_checkResponse($response))
			{
				$this->state = 'AUTHENTICATED';
				return $response;
			}
		}

		return false;
	}

	/**
	 * This function is used to login into server
	 * $user is a valid username and $pwd is a valid password.
	 *
	 * @param string $user
	 * @param string $pwd
	 * @return bool
	 */
	function login($user, $pwd, $proxyuser = null)
	{
		if (!$this->_checkState_1('DISCONNECTED')) return false;
		if (!$this->_checkState_1('AUTHENTICATED')) return false;

		$this->UpdateTag('LGN');
		if ($this->put_line($this->tag.' LOGIN "'.$this->quote($user).'" "'.$this->quote($pwd).'"'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				if ($proxyuser && strlen($proxyuser) > 0)
				{
					if ($this->put_line($this->tag.' PROXYAUTH "'.$this->quote($proxyuser).'"')
							&& $this->_checkResponse($this->get_server_responce()))
					{
						$this->state = 'AUTHENTICATED';
						return true;
					}
					else
					{
						setGlobalError($this->error);
						return false;
					}
				}

				$this->state = 'AUTHENTICATED';
				return true;
			}
		}

		setGlobalError($this->error);
		return false;
	}

	/**
	 * @param string $_text
	 * @return int
	 */
	function get_mailbox_count($_text)
	{
		$_arr = array();
		preg_match_all('/\* (\d+) EXISTS/i', $_text, $_arr);

		return isset($_arr[1][0]) ? (int) $_arr[1][0] : 0;
	}

	/**
	 * @param string $str
	 * @return string
	 */
	function quote($str)
	{
		return strtr($str, array('\\' => '\\\\', '"' => '\\"'));
	}

	/**
	 * @param string $str
	 * @return string
	 */
	function dequote($str)
	{
		return strtr($str, array('\\"' => '"', '\\\\' => '\\'));
	}

	/**
	 *
	 * @param string $mailbox_name
	 * @return bool
	 */
	function _in_mailbox($mailbox_name, $_onlyRead = false, $force = false)
	{
		if (!$this->_checkState_1('AUTHORIZATION')) return false;

		$_line = ($_onlyRead)
			? 'EXAMINE "'.$this->quote($mailbox_name).'"'
			: 'SELECT "'.$this->quote($mailbox_name).'"';

		if (!$force && $this->state == 'SELECTED' && $this->lastSelect == $_line)
		{
			$this->_log('IMAP4 > CmdRepeat: '.$_line);
			return true;
		}

		$this->UpdateTag('MBX');
		$_cline = $this->tag.' '.$_line;

		if ($this->put_line($_cline))
		{
			$response = $this->get_server_responce();
			if ($this->_checkResponse($response))
			{
				$this->lastSelect = $_line;
				$this->response_text = $response;

				$this->_bCurrentFolderSupportForwardFlag = false;

				$a = array();
				@preg_match('/\[PERMANENTFLAGS \(([^)]*)\)]/is', $this->response_text, $a);
				if (isset($a[1]))
				{
					$sLower = strtolower($a[1]);
					$sLowerForwarded = strtolower(CApi::GetConf('webmail.forwarded-flag-name', ''));

					if (false !== strpos($sLower, '\*') || (!empty($sLowerForwarded) && false !== strpos($sLower, $sLowerForwarded)))
					{
						$this->_bCurrentFolderSupportForwardFlag = true;
					}
				}

				$this->state = 'SELECTED';
				return true;
			}
		}

		return false;
	}

	/**
	 * The select_mailbox command selects a mailbox so that messages in the
	 * mailbox can be accessed.
	 *
	 * @param string $mailbox_name
	 * @return bool
	 */
	function select_mailbox($mailbox_name, $force = false)
	{
		return $this->_in_mailbox($mailbox_name, false, $force);
	}

	/**
	 * The examine_mailbox command is identical to SELECT and returns the same
	 * output; however, the selected mailbox is identified as read-only.
	 *
	 * @param string $mailbox_name
	 * @return bool
	 */
	function examine_mailbox($mailbox_name, $force = false)
	{
		return $this->_in_mailbox($mailbox_name, true, $force);
	}

	/* This function create a mail box*/
	function create_mailbox($mailbox_name)
	{
		if (!$this->_checkState_1('AUTHORIZATION')) return false;

		$this->UpdateTag('CRT');
		if ($this->put_line($this->tag.' CREATE "'.$this->quote($mailbox_name).'"'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		setGlobalError($this->error);
		return false;
	}

	/* This function delete exists mail box*/
	function delete_mailbox($mailbox_name)
	{
		if (!$this->_checkState_1('AUTHORIZATION')) return false;

		$this->UpdateTag('DLT');
		if ($this->put_line($this->tag.' DELETE "'.$this->quote($mailbox_name).'"'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		return false;
	}

	/* This function rename exists mail box*/
	function rename_mailbox($old_mailbox_name, $new_mailbox_name)
	{
		if (!$this->_checkState_1('AUTHORIZATION')) return false;

		$this->UpdateTag('RNM');
		if ($this->put_line($this->tag.' RENAME "'.$this->quote($old_mailbox_name).'" "'.$this->quote($new_mailbox_name).'"'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * The subscribe_mailbox command adds the specified mailbox name to the
	 * server's set of "active" or "subscribed" mailboxes
	 *
	 * @param string $mailbox_name
	 * @return bool
	 */
	function subscribe_mailbox($mailbox_name)
	{
		if (!$this->_checkState_1('AUTHORIZATION')) return false;

		$this->UpdateTag('SUBS');
		if ($this->put_line($this->tag.' SUBSCRIBE "'.$this->quote($mailbox_name).'"'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * The subscribe_mailbox command removes the specified mailbox name to the
	 * server's set of "active" or "subscribed" mailboxes
	 *
	 * @param unknown_type $mailbox_name
	 * @return unknown
	 */
	function unsubscribe_mailbox($mailbox_name)
	{
		if (!$this->_checkState_1('AUTHORIZATION')) return false;

		$this->UpdateTag('UNS');
		if ($this->put_line($this->tag.' UNSUBSCRIBE "'.$this->quote($mailbox_name).'"'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $delimiter
	 * @param array $flags
	 * @param string $ref_mail_box = ''
	 * @param string $wild_card = '*'
	 * @param string $_isSub = false
	 * @return array
	 */
	function _in_list_mailbox(&$delimiter, &$flags, $ref_mail_box = '', $wild_card = '*',
		$_isSub = false, $bUseXList = true)
	{
		$return_arr = null;
		$firstDelimiter = $delimiter;

		if (!$this->_checkState_1('AUTHORIZATION')) return false;

		if (trim($ref_mail_box) == '')
		{
			$ref_mail_box = '""';
		}

		$response = '';
		$_line = ($_isSub) ? 'LSUB' :
			(($bUseXList && $this->IsXListSupport()) ? 'XLIST' : 'LIST');

		$this->UpdateTag('LST');
		if ($this->put_line($this->tag.' '.$_line.' '.$ref_mail_box.' '.$wild_card))
		{
			$response = $this->get_server_responce();
			if (!$this->_checkResponse($response))
			{
				return $return_arr;
			}
		}
		else
		{
			return $return_arr;
		}

		$temp_arr = explode("\r\n", $response);

		$lit = null;
		$litString = null;
		$return_arr = array();
		$iLenPost = strlen('* '.$_line);
		for ($i = 0, $c = count($temp_arr) - 1; $i < $c; $i++)
		{
			$sFlags = '';
			$line = $temp_arr[$i];
			if (substr($line, 0, $iLenPost) == '* '.$_line)
			{
				$foldersParts = explode(')', $line, 2);

				$parts = explode(' ', $foldersParts[1], 3);
				if (trim($parts[1]) != 'NIL')
				{
					$delimiter = trim($parts[1], '"');
				}

				$delimiter = (strlen($delimiter) > 0) ? $delimiter{strlen($delimiter) - 1} : $firstDelimiter;

				$name = $parts[2];

				$aFlags = explode('(', $foldersParts[0], 2);
				$sFlags = is_array($aFlags) && 2 === count($aFlags) ? strtolower($aFlags[1]) : '';

				if ($name{strlen($name) - 1} == '}' && strpos($name, '{') !== false)
				{
					$start = strpos($name, '"');
					$startIndex =  strpos($name, '{');
					$endIndex =  strpos($name, '}');

					if (($start === false || $start > $startIndex) && $startIndex < $endIndex)
					{
						$lit = substr($name, $startIndex + 1, $endIndex - $startIndex - 1);
						if (is_numeric($lit))
						{
							$lit = (int) $lit;
						}
						else
						{
							$lit = null;
						}
					}
				}

				if ($lit === null)
				{
					$name = trim($name, '"'.$delimiter);

					if (false !== strpos($sFlags, '\inbox'))
					{
						$name = 'INBOX';
					}

					$name = $this->dequote($name);
					$flags[$name] = $sFlags;
					array_push($return_arr, $name);
				}
				else
				{
					$litString = $name;
				}
			}
			else if ($lit > 0)
			{
				$litline = substr($line, 0, $lit);
				$litString = str_replace('{'.$lit.'}', $litline, $litString);

				$litString = trim($litString, '"'.$delimiter);

				if (false !== strpos($sFlags, '\inbox'))
				{
					$litString = 'INBOX';
				}

				$litString = $this->dequote($litString);
				$flags[$litString] = $sFlags;
				array_push($return_arr, $litString);

				$litString = null;
				$lit = null;
			}
		}

		if (is_array($return_arr))
		{
			$return_arr = array_unique($return_arr);
		}

		return $return_arr;
	}

	/**
	 *	The list_mailbox command gets the specified list of mailbox
	 *
	 *	$ref_mail_box	$wild_card   	Interpretation
	 *	Reference    	Mailbox Name  	Interpretation
	 *	------------  	------------  	--------------
	 *	~smith/Mail/  	foo.*         	~smith/Mail/foo.*
	 *	archive/      	%             	archive/%
	 *	#news.        	comp.mail.*   	#news.comp.mail.*
	 *	~smith/Mail/  	/usr/doc/foo  	/usr/doc/foo
	 *	archive/      	~fred/Mail/*  	~fred/Mail/*
	 *
	 * @param string $delimiter
	 * @param string $ref_mail_box = ''
	 * @param string $wild_card = '*'
	 * @return array
	 */
	function &list_mailbox(&$delimiter, $ref_mail_box = '', $wild_card = '*', &$flags = array(), $bUseXList = true)
	{
		$_sub = $this->_in_list_mailbox($delimiter, $flags, $ref_mail_box, $wild_card, USE_LSUB, $bUseXList);
		return $_sub;
	}

	/**
	 * function is same as list_mailbox rather than it returns active mail box list
	 *
	 * @param string $delimiter
	 * @param string $ref_mail_box = ''
	 * @param string $wild_card = '*'
	 * @return array
	 */
	function &list_subscribed_mailbox(&$delimiter, $ref_mail_box = '', $wild_card = '*', &$flags = array())
	{
		$_sub = $this->_in_list_mailbox($delimiter, $flags, $ref_mail_box, $wild_card, true);
		return $_sub;
	}

	/**
	 * function is same as list_mailbox rather than it returns active mail box list
	 *
	 * @param string $mail_box
	 * @param string $status_cmd
	 * @return string|bool
	 */
	function get_status($mail_box, $status_cmd)
	{
		if (!$this->_checkState_1('AUTHORIZATION')) return false;

		$this->UpdateTag('STS');
		if ($this->put_line($this->tag.' STATUS "'.$this->quote($mail_box).'" ('.$status_cmd.')'))
		{
			$response=$this->get_server_responce();
			if ($this->_checkResponse($response))
			{
				return $response;
			}
		}

		return false;
	}

	/**
	 * The CHECK command requests a checkpoint of the currently selected
	 * mailbox.  A checkpoint refers to any implementation-dependent
	 * housekeeping associated with the mailbox
	 *
	 * @return string|bool
	 */
	function check_mailbox()
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		$this->UpdateTag('CHK');
		if ($this->put_line($this->tag.' CHECK'))
		{
			$response = $this->get_server_responce();
			if ($this->_checkResponse($response))
			{
				return $response;
			}
		}

		return false;
	}

	/**
	 * The close_mailbox command permanently removes from the currently selected
	 * mailbox all messages that have the \Deleted flag set, and returns
	 * to authenticated state from selected state.  No untagged EXPUNGE
	 * responses are sent.
	 *
	 * @return strin|bool
	 */
	function close_mailbox()
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		$this->UpdateTag('CLS');
		if ($this->put_line($this->tag.' CLOSE'))
		{
			$response = $this->get_server_responce();
			if ($this->_checkResponse($response))
			{
				return $response;
			}
		}

		return false;
	}

	/**
	 * The expunge_mailbox command permanently removes from the currently selected
	 * mailbox all messages that have the \Deleted flag set, and returns
	 * to authenticated state from selected state.  tagged EXPUNGE responses are sent.
	 *
	 * @return bool
	 */
	function expunge_mailbox()
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		$this->UpdateTag('EXP');
		if ($this->put_line($this->tag.' EXPUNGE'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		return false;
	}


	/**
	 * @param int $strUids
	 * @return unknown
	 */
	function expunge_uid_mailbox($intUids)
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		if ($this->IsUidPlusSupport())
		{
			$this->UpdateTag('EXP');
			if ($this->put_line($this->tag.' UID EXPUNGE '.$intUids))
			{
				if ($this->_checkResponse($this->get_server_responce()))
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param int $strUids
	 * @return unknown
	 */
	function expunge_uid_or_not_mailbox($intUids)
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		if ($this->IsUidPlusSupport())
		{
			$this->UpdateTag('EXP');
			if ($this->put_line($this->tag.' UID EXPUNGE '.$intUids))
			{
				if ($this->_checkResponse($this->get_server_responce()))
				{
					return true;
				}
			}
		}

		return $this->expunge_mailbox();
	}

	function get_sorted_by_internaldate_indexs()
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		$aResult = false;

		$response = $this->getResponseAsArray('FETCH 1:* (UID INTERNALDATE)');
		if ($response)
		{
			$aPreResult = array();
			foreach ($response as $key => $text)
			{
				$index = $uid = $internaldate = null;
				$match = $match1 = array();

				if (preg_match('/\* ([\d]+) FETCH \(([^\)]+)\)/', $text, $match))
				{
					if (isset($match[1], $match[2]) && is_numeric($match[1]))
					{
						$index = $match[1];
						if (preg_match('/UID (\d+)/', $match[2], $match1) && isset($match1[1]))
						{
							$uid = $match1[1];
						}

						if (preg_match('/INTERNALDATE "([^"]+)"/', $match[2], $match1) && isset($match1[1]))
						{
							$internaldate = $match1[1];
						}

						$aPreResult[$index] = array($uid, $internaldate);
					}
				}
			}

			if (is_array($aPreResult) && 0 < count($aPreResult))
			{
				$aNewResult = array();
				foreach ($aPreResult as $iIndex => $aPart)
				{
					if (isset($aPart[1]))
					{
						$aNewResult[$iIndex] = ConvertUtils::ParseRFC2822DateString($aPart[1]);
					}
				}

				asort($aNewResult, SORT_NUMERIC);

				foreach ($aNewResult as $iIndex => $iTime)
				{
					$aNewResult[$iIndex] = $aPreResult[$iIndex][0];
				}

				$aResult = $aNewResult;
			}
		}

		return $aResult;
	}

	/*

The search_mailbox command  searches the mailbox for messages that match
the given searching criteria.  Searching criteria consist of one
or more search keys.
The defined search keys are as follows.  Refer to the Formal
Syntax section for the precise syntactic definitions of the
arguments.

<message set>  Messages with message sequence numbers
corresponding to the specified message sequence
number set

ALL            All messages in the mailbox; the default initial
key for ANDing.

ANSWERED       Messages with the \Answered flag set.

BCC <string>   Messages that contain the specified string in the
envelope structure's BCC field.

BEFORE <date>  Messages whose internal date is earlier than the
specified date.

BODY <string>  Messages that contain the specified string in the
body of the message.

CC <string>    Messages that contain the specified string in the
envelope structure's CC field.

DELETED        Messages with the \Deleted flag set.

DRAFT          Messages with the \Draft flag set.

FLAGGED        Messages with the \Flagged flag set.

FROM <string>  Messages that contain the specified string in the
envelope structure's FROM field.

HEADER <field-name> <string>
Messages that have a header with the specified
field-name (as defined in [RFC-822]) and that
contains the specified string in the [RFC-822]
field-body.

KEYWORD <flag> Messages with the specified keyword set.

LARGER <n>     Messages with an [RFC-822] size larger than the
specified number of octets.

NEW            Messages that have the \Recent flag set but not the
\Seen flag.  This is functionally equivalent to
"(RECENT UNSEEN)".

NOT <search-key>
Messages that do not match the specified search
key.

OLD            Messages that do not have the \Recent flag set.
This is functionally equivalent to "NOT RECENT" (as
opposed to "NOT NEW").

ON <date>      Messages whose internal date is within the
specified date.

OR <search-key1> <search-key2>
Messages that match either search key.

RECENT         Messages that have the \Recent flag set.

SEEN           Messages that have the \Seen flag set.

SENTBEFORE <date>
Messages whose [RFC-822] Date: header is earlier
than the specified date.

SENTON <date>  Messages whose [RFC-822] Date: header is within the
specified date.

SENTSINCE <date>
Messages whose [RFC-822] Date: header is within or
later than the specified date.

SINCE <date>   Messages whose internal date is within or later
than the specified date.

SMALLER <n>    Messages with an [RFC-822] size smaller than the
specified number of octets.

SUBJECT <string>
Messages that contain the specified string in the
envelope structure's SUBJECT field.

TEXT <string>  Messages that contain the specified string in the
header or body of the message.

TO <string>    Messages that contain the specified string in the
envelope structure's TO field.

UID <message set>
Messages with unique identifiers corresponding to
the specified unique identifier set.

UNANSWERED     Messages that do not have the \Answered flag set.

UNDELETED      Messages that do not have the \Deleted flag set.

UNDRAFT        Messages that do not have the \Draft flag set.

UNFLAGGED      Messages that do not have the \Flagged flag set.

UNKEYWORD <flag>
Messages that do not have the specified keyword
set.

UNSEEN         Messages that do not have the \Seen flag set.

Example:    search_mailbox("FLAGGED SINCE 1-Feb-1994 NOT FROM \"Smith\"")

	*/

	/**
	 * @param string $search_cri
	 * @param string $charset = ''
	 * @param bool $_isUidSearch = false
	 * @param string $order_by = null
	 * @param bool $bContinueOnRead = false
	 *
	 * @return array|false
	 */
	function _in_search_mailbox($search_cri, $charset = '', $_isUidSearch = false, $order_by = null, $bContinueOnRead = false)
	{
		$cmd = $cmd_wo_charset = '';
		$doSort = (null != $order_by);
		if (!$bContinueOnRead)
		{
			if (!$this->_checkState_0('SELECTED')) return false;

			$oSettings =& CApi::GetSettings();

			if ($doSort && !$oSettings->GetConf('WebMail/UseSortImapForDateMode') &&
				in_array($order_by, array('ARRIVAL', 'REVERSE ARRIVAL')))
			{
				$doSort = false;
			}

			if (trim($charset) != '')
			{
				if ($doSort)
				{
					$charset = trim($charset).' ';
				}
				else
				{
					$charset = 'CHARSET '.trim($charset).' ';
				}
			}

			if ($search_cri == null || trim($search_cri) == '')
			{
				$search_cri = 'ALL';
			}
			else
			{
				$search_cri = trim($search_cri);
			}

			$_temp = $_isUidSearch ? 'UID ' : '';

			if ($doSort)
			{
				$this->UpdateTag('SRT');
			}
			else
			{
				$this->UpdateTag('SRCH');
			}

			if ($doSort)
			{
				$cmd = $this->tag.' '.$_temp.'SORT ('.$order_by.') '.$charset.$search_cri;
				$cmd_wo_charset = $this->tag.' '.$_temp.'SORT ('.$order_by.') US-ASCII '.$search_cri;
			}
			else
			{
				if (!empty($charset))
				{
					if (ConvertUtils::IsLatin($search_cri))
					{
						$charset = '';
					}
				}

				$cmd = $this->tag.' '.$_temp.'SEARCH '.$charset.$search_cri;
				$cmd_wo_charset = $this->tag.' '.$_temp.'SEARCH '.$search_cri;
			}
		}

		$response = '';
		if ($bContinueOnRead || $this->put_line($cmd))
		{
			$iTime = time();
			$response = $this->get_server_responce();
			if (!$this->_checkResponse($response))
			{
				if ($cmd === $cmd_wo_charset)
				{
					return false;
				}
				// try without charset
				else if (30 > time() - $iTime && !empty($cmd_wo_charset) && $this->put_line($cmd_wo_charset))
				{
					$response = $this->get_server_responce();
					if (!$this->_checkResponse($response))
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}

		$return = array();
		$temp_arr = explode("\r\n", $response);
		foreach ($temp_arr as $line)
		{
			if ($doSort)
			{
				if (substr($line, 0, 7) == '* SORT ')
				{
					$return = array_merge($return, explode(' ', substr($line, 7)));
				}
			}
			else
			{
				if (substr($line, 0, 9) == '* SEARCH ')
				{
					$return = array_merge($return, explode(' ', substr($line, 9)));
				}
			}
		}

		$aResult = array();
		foreach ($return as $sItem)
		{
			$sItem = trim($sItem);
			if (!empty($sItem))
			{
				$aResult[] = (int) $sItem;
			}
		}

		return $aResult;
	}

	/**
	 * @param string $search_cri
	 * @param string $charset
	 * @param string $order_by
	 * @param bool $bContinueOnRead = false
	 *
	 * @return array|false
	 */
	function search_mailbox($search_cri, $charset = '', $order_by = null, $bContinueOnRead = false)
	{
		return $this->_in_search_mailbox($search_cri, $charset, false, $order_by, $bContinueOnRead);
	}

	/**
	 * The uid_search_mailbox as same as above but diffrence is that
	 * it takes uid number as $msg_set;
	 *
	 * @param string $search_cri
	 * @param string $charset
	 * @param string $order_by
	 * @param bool $bContinueOnRead = false
	 *
	 * @return array|false
	 */
	function uid_search_mailbox($search_cri, $charset = '', $order_by = null, $bContinueOnRead = false)
	{
		return $this->_in_search_mailbox($search_cri, $charset, true, $order_by);
	}

	/*
The fetch_mail function retrieves data associated with a message in the
mailbox.  The data items to be fetched can be either a single atom
or a parenthesized list.

ALL            Macro equivalent to: (FLAGS INTERNALDATE RFC822.SIZE ENVELOPE)

BODY           Non-extensible form of BODYSTRUCTURE.

BODY[<section>]<<partial>>

BODY.PEEK[<section>]<<partial>>
An alternate form of BODY[<section>] that does not
implicitly set the \Seen flag.

BODYSTRUCTURE  The [MIME-IMB] body structure of the message.  This
is computed by the server by parsing the [MIME-IMB]
header fields in the [RFC-822] header and
[MIME-IMB] headers.

ENVELOPE       The envelope structure of the message.  This is
computed by the server by parsing the [RFC-822]
header into the component parts, defaulting various
fields as necessary.

FAST         Macro equivalent to: (FLAGS INTERNALDATE RFC822.SIZE)

FLAGS       The flags that are set for this message.

FULL        Macro equivalent to: (FLAGS INTERNALDATE RFC822.SIZE ENVELOPE BODY)

INTERNALDATE   The internal date of the message.

RFC822      Functionally equivalent to BODY[], differing in the
syntax of the resulting untagged FETCH data (RFC822
is returned).

RFC822.HEADER  Functionally equivalent to BODY.PEEK[HEADER],
differing in the syntax of the resulting untagged
FETCH data (RFC822.HEADER is returned).

RFC822.SIZE The [RFC-822] size of the message.

RFC822.TEXT  Functionally equivalent to BODY[TEXT], differing in
the syntax of the resulting untagged FETCH data
(RFC822.TEXT is returned).

UID            The unique identifier for the message.

Example : fetch_mail( 2:4 (FLAGS BODY[HEADER.FIELDS (DATE FROM)])

	*/

	/**
	 * @param string $msg_set
	 * @param string $msg_data_name
	 * @param string $_isUidFetch = false
	 * @return string
	 */
	function _in_fetch_mail($msg_set, $msg_data_name, $_isUidFetch = false)
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		$msg_set = trim($msg_set);
		$msg_data_name = trim($msg_data_name);
		$_temp = ($_isUidFetch) ? 'UID ' : '';

		$this->UpdateTag('FTC');
		if ($this->put_line($this->tag.' '.$_temp.'FETCH '.$msg_set.' ('.$msg_data_name.')'))
		{
			$response = $this->get_server_responce();
			if ($this->_checkResponse($response))
			{
				return $response;
			}
		}

		return false;
	}

	/**
	 * @param string $msg_set
	 * @param string $msg_data_name
	 * @return string
	 */
	function fetch_mail($msg_set, $msg_data_name)
	{
		return $this->_in_fetch_mail($msg_set, $msg_data_name);
	}

	/**
	 * The uid_fetch_mail as same as above but difference is that
	 * it takes uid number as $msg_set
	 *
	 * @param string $msg_set
	 * @param string $msg_data_name
	 * @return string
	 */
	function uid_fetch_mail($msg_set, $msg_data_name)
	{
		return $this->_in_fetch_mail($msg_set, $msg_data_name, true);
	}

	/*
The store_mail_flag function alters data associated with a message in the
mailbox.  Normally, store_mail_flag will return the updated value of the
data with an untagged FETCH response.  A suffix of ".SILENT" in
the data item name prevents the untagged FETCH, and the server
SHOULD assume that the client has determined the updated value
itself or does not care about the updated value.
The currently defined data items that can be stored are:

FLAGS <flag list>	Replace the flags for the message with the
argument.  The new value of the flags are returned
as if a FETCH of those flags was done.

FLAGS.SILENT <flag list>
Equivalent to FLAGS, but without returning a new value.

+FLAGS <flag list>
Add the argument to the flags for the message.  The
new value of the flags are returned as if a FETCH
of those flags was done.

+FLAGS.SILENT <flag list>
Equivalent to +FLAGS, but without returning a new
value.

-FLAGS <flag list>
Remove the argument from the flags for the message.
The new value of the flags are returned as if a
FETCH of those flags was done.

-FLAGS.SILENT <flag list>
Equivalent to -FLAGS, but without returning a new
value.

Example :store_mail_flag("3","+FLAGS","Seen");
	*/

	/**
	 * @param string $msg_set
	 * @param string $msg_data_name
	 * @param string $value
	 * @param bool $_isUidStore = false
	 * @return bool
	 */
	function _in_store_mail_flag($msg_set, $msg_data_name, $value, $_isUidStore = false)
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		$msg_set = trim($msg_set);
		$msg_data_name = trim($msg_data_name);
		$value = trim($value);

		$_temp = ($_isUidStore) ? 'UID ' : '';

		$this->UpdateTag('STR');
		if ($this->put_line($this->tag.' '.$_temp.'STORE '.$msg_set.' '.$msg_data_name.' ('.$value.')'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		setGlobalError($this->error);
		return false;
	}

	/**
	 * @param string $msg_set
	 * @param string $msg_data_name
	 * @param string $value
	 * @return bool
	 */
	function store_mail_flag($msg_set, $msg_data_name, $value)
	{
		return $this->_in_store_mail_flag($msg_set, $msg_data_name, $value);
	}

	/**
	 * The uid_store_mail_flag as same as above but diffrence is that
	 * it takes uid number as $msg_set
	 *
	 * @param unknown_type $msg_set
	 * @param unknown_type $msg_data_name
	 * @param unknown_type $value
	 * @return unknown
	 */
	function uid_store_mail_flag($msg_set, $msg_data_name, $value)
	{
		return $this->_in_store_mail_flag($msg_set, $msg_data_name, $value, true);
	}


	/**
	 * @param string $msg_set
	 * @param string $mailbox
	 * @param bool $_isUidCopy = false
	 * @return bool
	 */
	function _in_copy_mail($msg_set, $mailbox, $_isUidCopy = false)
	{
		if (!$this->_checkState_0('SELECTED')) return false;

		$msg_set = trim($msg_set);
		$_temp = ($_isUidCopy) ? 'UID ' : '';

		$this->UpdateTag('CP');
		if ($this->put_line($this->tag.' '.$_temp.'COPY '.$msg_set.' "'.$this->quote($mailbox).'"'))
		{
			if ($this->_checkResponse($this->get_server_responce()))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * The copy_mail command copies the specified message(s) to the end of the
	 * specified destination mailbox
	 * @example: copy_mail("2:4","TEST")
	 *
	 * @param string $msg_set
	 * @param string $mailbox
	 * @return bool
	 */
	function copy_mail($msg_set, $mailbox)
	{
		return $this->_in_copy_mail($msg_set, $mailbox);
	}

	/**
	 * The uid_copy_mail as same as above but diffrence is that
	 * it takes uid number as $msg_set;
	 *
	 * @param string $msg_set
	 * @param string $mailbox
	 * @return bool
	 */
	function uid_copy_mail($msg_set, $mailbox)
	{
		return $this->_in_copy_mail($msg_set, $mailbox, true);
	}

	/**
	 * @param string $sFolderName
	 * @param string $sMessageId
	 * @return string | bool
	 */
	function getUidByMessageId($sFolderName, $sMessageId)
	{
		$this->select_mailbox($sFolderName);
		$aResult = $this->uid_search_mailbox('HEADER MESSAGE-ID "'.$this->quote($sMessageId).'"');

		return (is_array($aResult) && 1 === count($aResult)) ? $aResult[0] : false;

		if (is_array($aResult) && 0 < count($aResult))
		{
			$aResult = array_map('intval', $aResult);
			sort($aResult, SORT_NUMERIC);
			return array_pop($aResult);
		}

		return false;
	}

	/**
	 * @param string $mailbox
	 * @param string $flags
	 * @param string $messageText
	 * @return bool
	 */
	function append_mail($mailbox, $flags, $messageText, &$sNewUid)
	{
		$sNewUid = null;
		$this->UpdateTag('APP');
		$messageText = str_replace("\n", "\r\n", str_replace("\r", '', str_replace("\r\n", "\n", $messageText)));

		if ($this->put_line($this->tag.' APPEND "'.$this->quote($mailbox).'" ('.trim($flags).') {'.strlen($messageText).'}'))
		{
			$response = $this->get_line();
			if ($response{0} == '+')
			{
				$this->put_line($messageText);
				$response = $this->get_server_responce();

				$bResult = substr($response, strpos($response, $this->tag.' ') + strlen($this->tag) + 1, 2) == 'OK';
				$aMatch = array();
				if ($bResult && preg_match('/\[APPENDUID [\d]+ ([\d]+)\]/i', $response, $aMatch) &&
					isset($aMatch[1]) && is_numeric($aMatch[1]))
				{
					$sNewUid = $aMatch[1];
				}

				return $bResult;
			}
		}

		return false;
	}

	function InitCapa()
	{
		if (null === $this->_capas)
		{
			$resp = $this->get_capability();
			if ($resp)
			{
				$this->_parseCapability($resp);
			}
		}
	}

	/**
	 * @param string $_str
	 * @return bool
	 */
	function _isSupport($_str)
	{
		return $this->_capas ? in_array($_str, $this->_capas) : false;
	}

	/**
	 * @return bool
	 */
	function IsIdSupport()
	{
		if (null === $this->_capas)
		{
			$this->InitCapa();
		}

		return $this->_isSupport('ID');
	}

	/**
	 * @return bool
	 */
	function IsUidPlusSupport()
	{
		if (null === $this->_capas)
		{
			$this->InitCapa();
		}

		return $this->_isSupport('UIDPLUS');
	}

	/**
	 * @return bool
	 */
	function IsXListSupport()
	{
		if (null === $this->_capas)
		{
			$this->InitCapa();
		}

		return $this->_isSupport('XLIST');
	}

	/**
	 * @return bool
	 */
	function IsQuotaSupport()
	{
		if (null === $this->_capas)
		{
			$this->InitCapa();
		}

		return $this->_isSupport('QUOTA');
	}

	/**
	 * @return bool
	 */
	function IsLiteralPlusSupport()
	{
		if (null === $this->_capas)
		{
			$this->InitCapa();
		}

		return $this->_isSupport('LITERAL+');
	}

	/**
	 * @return bool
	 */
	function IsSortSupport()
	{
		if (null === $this->_capas)
		{
			$this->InitCapa();
		}

		return $this->_isSupport('SORT');
	}

	/**
	 * @return bool
	 */
	function IsPlainLoginSupport()
	{
		if (null === $this->_capas)
		{
			$this->InitCapa();
		}

		return $this->_isSupport('AUTH=PLAIN');
	}

	/**
	 * @return bool
	 */
	function IsLastSelectedFolderSupportForwardedFlag()
	{
		return $this->_bCurrentFolderSupportForwardFlag;
	}

	/**
	 * @return bool
	 */
	function IsNameSpaceSupport()
	{
		if (null === $this->_capas)
		{
			$this->InitCapa();
		}

		return $this->_isSupport('NAMESPACE');
	}

	/**
	 * @return	string
	 */
	function GetNameSpacePrefix()
	{
		if (!$this->_checkState_1('AUTHORIZATION')) return '';

		$sNameSpacePrefix = '';
		if ($this->IsNameSpaceSupport())
		{
			$this->UpdateTag('NS');
			if ($this->put_line($this->tag.' NAMESPACE'))
			{
				$response = $this->get_server_responce();
				if ($this->_checkResponse($response))
				{
					$a = array();
					if (false !== preg_match_all('/NAMESPACE \(\(".*?"\)\)/', $response, $a)
						&& isset($a[0][0]) && is_string($a[0][0]))
					{
						$b = array();
						if (false !== preg_match('/\(\("([^"]*)" "/', $a[0][0], $b) && isset($b[1]))
						{
							$sNameSpacePrefix = trim($b[1]);
						}
					}
				}
			}
		}
		return $sNameSpacePrefix;
	}

    /**
	 * @param string $str
	 */
	function _parseCapability($str)
	{
		$this->_capas = array();
		$capasLineArray = explode("\n", $str);
		foreach ($capasLineArray as $capasLine)
		{
			$capa = strtoupper(trim($capasLine));
			if (substr($capa, 0, 12) == '* CAPABILITY')
			{
				$capa = substr($capa, 12);
				$cArray = explode(' ', $capa);

				foreach ($cArray as $c)
				{
					if (strlen($c) > 0)
					{
						$this->_capas[] = $c;
					}
				}
			}
		}
	}

	/**
	 * @return int | false
	 */
	function get_quota()
	{
		$return = $this->_pre_quota();
		if (isset($return[2]))
		{
			return (int) $return[2];
		}

		return false;
	}

	/**
	 * @return int | false
	 */
	function get_used_quota()
	{
		$return = $this->_pre_quota();
		if (isset($return[1]))
		{
			return (int) $return[1];
		}

		return false;
	}

	/**
	 * @return array | false
	 */
	function _pre_quota()
	{
		static $matchResult = null;
		if (null !== $matchResult)
		{
			return $matchResult;
		}

		if ($this->IsQuotaSupport())
		{
			$this->UpdateTag();
			if ($this->put_line($this->tag.' GETQUOTAROOT "INBOX"'))
			{
				$response = $this->get_server_responce();
				if (!$this->_checkResponse($response))
				{
					$matchResult = false;
				}
				else
				{
					$match = array();
					if (preg_match('/STORAGE (\d+) (\d+)/i', $response, $match) && count($match) > 2)
					{
						$matchResult = $match;
					}
				}
			}
		}
		else
		{
			$matchResult = false;
		}

		return $matchResult;
	}

	/**
	 * @param string $response
	 * @return string
	 */
	function _checkResponse($response)
	{
		if (false === $response)
		{
			$this->error = 'Error : False response';

			return false;
		}
		else if (substr($response, strpos($response, $this->tag.' ') + strlen($this->tag) + 1, 2) != 'OK')
		{
			if (trim($response) == '')
			{
				$this->error = 'Error : Null response';
			}
			else
			{
				$this->error = 'Error response: '.$response;
			}

			return false;
		}

		return true;
	}

	/**
	 * @param string $response
	 * @return string
	 */
	function _checkResponseAsArray($response)
	{
		return (is_array($response) && count($response) > 0 && (false !== strpos($response[count($response) - 1], $this->tag.' OK')));
	}

	/**
	 * @param string $_state
	 * @return bool
	 */
	function _checkState_0($_state)
	{
		if ($this->state != $_state)
		{
			$this->error = $this->_getState_0_Error($_state);
			return false;
		}

		return true;
	}

	/**
	 * @param string $_state
	 * @return bool
	 */
	function _checkState_1($_state)
	{
		if ($this->state == $_state)
		{
			$this->error = $this->_getState_1_Error($_state);
			return false;
		}

		return true;
	}

	/**
	 * @param string $_state
	 * @return string
	 */
	function _getState_0_Error($_state)
	{
		switch ($_state)
		{
			default:				return 'Error : Unknown state.';
			case 'SELECTED':		return 'Error : No mail box is selected.';
			case 'DISCONNECTED':	return 'Error : Already Connected!';
			case 'AUTHORIZATION':	return 'Error : No Connection Found!';
		}
	}

	/**
	 * @param string $_state
	 * @return string
	 */
	function _getState_1_Error($_state)
	{
		switch ($_state)
		{
			default:				return 'Error : Unknown state.';
			case 'DISCONNECTED':	return 'Error : No Connection Found!';
			case 'AUTHENTICATED':	return 'Error : Already Authenticated!';
			case 'AUTHORIZATION':	return 'Error : User is not authorised or logged in!';
		}
	}

	/**
	 * @param bool $_force
	 */
	function _resetTimeOut($_force = false)
	{
		static $_staticTime = null;

		$_time = time();
		if ($_staticTime < $_time - RESET_TIME_LIMIT_RUN || $_force)
		{
			@set_time_limit(RESET_TIME_LIMIT);
			$_staticTime = $_time;
		}
	}

	/**
	 * @param string $_prefix = null
	 * @return string
	 */
	function GetTag($_prefix = null)
	{
		if (null === $_prefix)
		{
			$_prefix = 'WM';
		}

		return $_prefix.rand(1000, 9999);
	}

	/**
	 * @param string $_prefix = null
	 */
	function UpdateTag($_prefix = null)
	{
		$this->tag = $this->GetTag($_prefix);
	}
}
