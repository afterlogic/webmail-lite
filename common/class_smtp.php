<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/inc_constants.php');
	require_once(WM_ROOTPATH.'common/class_webmailmessages.php');

	define('USE_STARTTLS', true);

	/**
	 * @static
	 */
	class CSmtp
	{
		/**
		 * @param CAccount $account
		 * @param WebMailMessage $message
		 * @param string $from
		 * @param string $to
		 * @return bool
		 */
		public static function SendMail($account, $message, $from, $to)
		{
			$bIsDemo = false;
			CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$account, &$bIsDemo));

			if ($bIsDemo)
			{
				$allRcpt = $message->GetAllRecipients();
				if ($allRcpt && 0 < $allRcpt->Count())
				{
					$aAccountEmailParts = explode('@', $account->Email, 2);
					$sAccessDomain = $aAccountEmailParts && !empty($aAccountEmailParts[1])
						? strtolower($aAccountEmailParts[1]) : '';

					$bSuccessRec = true;
					foreach (array_keys($allRcpt->Instance()) as $key)
					{
						$oEmail = $allRcpt->Get($key);
						if ($bSuccessRec && $oEmail)
						{
							$aEmailParts = explode('@', $oEmail->Email, 2);
							if ($aEmailParts && isset($aEmailParts[1]) && $sAccessDomain === strtolower($aEmailParts[1]))
							{
								continue;
							}
						}

						$bSuccessRec = false;
					}

					if (!$bSuccessRec)
					{
						setGlobalError(WarningSendEmailToDemoOnly);
						return false;
					}
				}
				else
				{
					setGlobalError(WebMailException);
					return false;
				}
			}

			if ($from === null)
			{
				$fromAddr = $message->GetFrom();
				$from = $fromAddr->Email;
			}

			if ($to === null)
			{
				$to = $message->GetAllRecipientsEmailsAsString();
			}

			$link = null;
			$result = CSmtp::Connect($link, $account);
			if ($result)
			{
				$result = CSmtp::Send($link, $account, $message, $from, $to);
				if ($result)
				{
					$result = CSmtp::Disconnect($link);
				}
			}
			else
			{
				setGlobalError(ErrorSMTPConnect);
			}

			return $result;
		}


		/**
		 * @access private
		 * @param resource $link
		 * @param CAccount $account
		 * @return bool
		 */
		public static function Connect(&$link, &$account)
		{
			$outHost = $account->OutgoingMailServer;

			$errno = null;
			$errstr = null;
			$out = '';

			if ($account->OutgoingMailUseSSL)
			{
				$outHost = 'ssl://'.$outHost;
			}

			$sConnectTimeout = CApi::GetConf('socket.connect-timeout', 5);
			$sFgetTimeout = CApi::GetConf('socket.get-timeout', 5);

			CApi::Plugin()->RunHook('webmail-smtp-update-socket-timeouts',
				array(&$sConnectTimeout, &$sFgetTimeout));

			CApi::Log('[SMTP] Connecting to server '. $outHost.' on port '.$account->OutgoingMailPort);
			$link = @fsockopen($outHost, $account->OutgoingMailPort, $errno, $errstr, $sConnectTimeout);
			if(!$link)
			{
				setGlobalError('[SMTP] Error: '.$errstr);
				CApi::Log(getGlobalError(), ELogLevel::Error);
				return false;
			}
			else
			{
				@socket_set_timeout($link, $sFgetTimeout);
				return CSmtp::IsSuccess($link, $out);
			}
		}

		/**
		 * @access private
		 * @param resource $link
		 * @return bool
		 */
		public static function Disconnect(&$link)
		{
			$out = '';
			return CSmtp::ExecuteCommand($link, 'QUIT', $out);
		}

		/**
		 * @access private
		 * @param resource $link
		 * @return bool
		 */
		public static function StartTLS(&$link)
		{
			$out = '';
			return CSmtp::ExecuteCommand($link, 'STARTTLS', $out);
		}

		/**
		 * @access private
		 * @param resource $link
		 * @param CAccount $account
		 * @param WebMailMessage $message
		 * @param string $from
		 * @param string $to
		 * @return bool
		 */
		public static function Send(&$link, &$account, &$message, $from, $to)
		{
			$ehloMsg = trim(EmailAddress::GetDomainFromEmail($account->Email));
			$ehloMsg = strlen($ehloMsg) > 0 ? $ehloMsg : trim(EmailAddress::GetDomainFromEmail($account->IncomingMailLogin));

			$out = '';
			$result = CSmtp::ExecuteCommand($link, 'EHLO '.$ehloMsg, $out);
			if (!$result)
			{
				$result = CSmtp::ExecuteCommand($link, 'HELO '.$ehloMsg, $out);
			}

			if (587 === $account->OutgoingMailPort)
			{
				$capa = CSmtp::ParseEhlo($out);
				if ($result && in_array('STARTTLS', $capa) && USE_STARTTLS && function_exists('stream_socket_enable_crypto') && CSmtp::StartTLS($link))
				{
					CApi::Log('[SMTP] : stream_socket_enable_crypto: '.
						(@stream_socket_enable_crypto($link, true, STREAM_CRYPTO_METHOD_TLS_CLIENT) ? 'true' : 'false'));

					$result = CSmtp::ExecuteCommand($link, 'EHLO '.$ehloMsg, $out);
					if (!$result)
					{
						$result = CSmtp::ExecuteCommand($link, 'HELO '.$ehloMsg, $out);
					}
				}
			}

			if ($result && ESMTPAuthType::NoAuth !== $account->OutgoingMailAuth)
			{
				$result = CSmtp::ExecuteCommand($link, 'AUTH LOGIN', $out);

				$sOutgoingMailLogin = $account->OutgoingMailLogin;
				$mailOutLogin = !empty($sOutgoingMailLogin) ? $sOutgoingMailLogin : $account->IncomingMailLogin;

				$sOutgoingMailPassword = $account->OutgoingMailPassword;
				$mailOutPassword = !empty($sOutgoingMailPassword) ?	$sOutgoingMailPassword : $account->IncomingMailPassword;

				CApi::Plugin()->RunHook('webmail-smtp-change-auth-login',
					array(&$mailOutLogin, &$mailOutPassword));

				if ($result)
				{
					CApi::Log('[SMTP] Sending encoded login');
					$result = CSmtp::ExecuteCommand($link, base64_encode($mailOutLogin), $out);
				}

				if ($result)
				{
					CApi::Log('[SMTP] Sending encoded password');
					$result = CSmtp::ExecuteCommand($link, base64_encode($mailOutPassword), $out);
				}
			}

			if ($result)
			{
				$result = CSmtp::ExecuteCommand($link, 'MAIL FROM:<'.$from.'>', $out);
			}
			else
			{
				setGlobalError(ErrorSMTPAuth);
			}

			if ($result)
			{
				$toArray = explode(',', $to);
				foreach ($toArray as $recipient)
				{
					$recipient = trim($recipient);
					$result = CSmtp::ExecuteCommand($link, 'RCPT TO:<'.$recipient.'>', $out);
					if (!$result)
					{
						break;
					}
				}
			}

			if ($result)
			{
				$result = CSmtp::ExecuteCommand($link, 'DATA', $out);
			}

			if ($result)
			{
				$result = CSmtp::ExecuteCommand($link, str_replace(CRLF.'.', CRLF.'..', $message->TryToGetOriginalMailMessage()).CRLF.'.', $out);
			}

			if ($result)
			{
				CApi::LogEvent('User Send message', $account);
			}

			CSmtp::resetTimeOut(true);
			return $result;
		}


		public static function ParseEhlo($str)
		{
			$return = array();
			$arrayOut = explode("\n", $str);
			array_shift($arrayOut);
			if (is_array($arrayOut))
			{
				foreach ($arrayOut as $line)
				{
					$parts = explode('-', trim($line), 2);
					if (count($parts) == 2 && $parts[0] == '250')
					{
						$return[] = strtoupper(trim($parts[1]));
					}
				}
			}
			return $return;
		}

		/**
		 * @access private
		 * @param resource $link
		 * @param string $command
		 * @return bool
		 */
		public static function ExecuteCommand(&$link, $command, &$out, $isLog = true)
		{
			$command = str_replace("\n", "\r\n", str_replace("\r", '', $command));
			if ($isLog)
			{
				CApi::Log('[SMTP] >>: '.$command);
			}

			CSmtp::resetTimeOut();
			@fputs($link, $command.CRLF);
			return CSmtp::IsSuccess($link, $out);
		}

		/**
		 * @access private
		 * @param resource $link
		 * @return bool
		 */
		public static function IsSuccess(&$link, &$out, $isLog = true)
		{
			$out = '';
			$line = '';
			$result = true;
			do
			{
				$line = @fgets($link, 1024);
				if ($isLog)
				{
					CApi::Log('[SMTP] <<: '.trim($line));
				}
				if ($line === false)
				{
					$result = false;
					setGlobalError('[SMTP] Error: IsSuccess fgets error');
					break;
				}
				else
				{
					$out .= $line;
					$line = str_replace("\r", '', str_replace("\n", '', str_replace(CRLF, '', $line)));
					if (substr($line, 0, 1) != '2' && substr($line, 0, 1) != '3')
					{
						$result = false;
						$error = '[SMTP] Error <<: ' . $line;
						setGlobalError($error);
						//setGlobalError(substr($line, 3));
						break;
					}
				}

			} while (substr($line, 3, 1) == '-');

			if (!$result && $isLog)
			{
				CApi::Log(getGlobalError(), ELogLevel::Error);
			}

			return $result;
		}

		/**
		 * @param bool $_force
		 */
		public static function resetTimeOut($_force = false)
		{
			static $_staticTime = null;

			$_time = time();
			if ($_staticTime < $_time - RESET_TIME_LIMIT_RUN || $_force)
			{
				@set_time_limit(RESET_TIME_LIMIT);
				$_staticTime = $_time;
			}
		}
	}
