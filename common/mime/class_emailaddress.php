<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/class_convertutils.php');

	class EmailAddress
	{
		/**
		 * @var string
		 */
		var $DisplayName = '';

		/**
		 * @var string
		 */
		var $Email = '';

		/**
		 * @var string
		 */
		var $Remarks = '';

		/**
		 * Initializes a new instance of the EmailAddress object.
		 * @param Header $header optional
		 * @param string $email optional
		 * @param string $displayName optional
		 * @param string $remarks optional
		 * @return EmailAddress
		 */
		function EmailAddress($email = '', $displayName = '', $remarks = '')
		{
			$this->Email = $email;
			$this->DisplayName = $displayName;
			$this->Remarks = $remarks;
		}

		/**
		 * Gets the account name of the e-mail address.
		 * @return string
		 */
		function GetAccountName()
		{
			return EmailAddress::GetAccountNameFromEmail($this->Email);
		}

		/**
		 * Gets the domain name of the e-mail address.
		 * @return string
		 */
		function GetDomain()
		{
			return EmailAddress::GetDomainFromEmail($this->Email);
		}

		/**
		 * Gets the account name of the specified e-mail address as a string.
		 * @param string $email
		 * @return string
		 */
		public static function GetAccountNameFromEmail($email)
		{
			if ($email == null)
			{
				return '';
			}
			else
			{
				$parts = explode('@', $email, 2);
				return trim($parts[0]);
			}
		}


		/**
		 * Gets the domain name of the specified e-mail address as a string.
		 * @param string $email
		 * @return string
		 */
		public static function GetDomainFromEmail($email)
		{
			if ($email == null)
			{
				return '';
			}
			else
			{
				$parts = explode('@', $email, 2);
				if (count($parts) == 1)
				{
					return '';
				}
				else
				{
					return $parts[1];
				}
			}
		}

		/**
		 * Sets the e-mail address details as a string.
		 * @param string $value
		 */
		function SetAsString($value)
		{
			$this->Parse($value);
		}

		/**
		 * Parse email address.
		 * @param string $addressString
		 */
		function Parse($addressString)
		{
			if ($addressString == null)
			{
				return;
			}
			$addressString = trim($addressString);

			$name = '';
			$email = '';
			$comment = '';

			$inName = false;
			$inAddress = false;
			$inComment = false;

			$startIndex = 0;
			$endIndex = 0;
			$currentIndex = 0;

			while ($currentIndex < strlen($addressString))
			{
				switch ($addressString{$currentIndex})
				{
					case '"':
						if ((!$inName) && (!$inAddress) && (!$inComment))
						{
							$inName = true;
							$startIndex = $currentIndex;
						}
						elseif ((!$inAddress) && (!$inComment))
						{
							$endIndex = $currentIndex;
							$name = substr($addressString, $startIndex + 1, $endIndex - $startIndex - 1);
							$addressString = substr_replace($addressString, '', $startIndex, $endIndex - $startIndex + 1);
							$endIndex = 0;
							$currentIndex = 0;
							$startIndex = 0;
							$inName = false;
						}
						break;
					case '<':
						if ((!$inName) && (!$inAddress) && (!$inComment))
						{
							if ($currentIndex > 0 && strlen($name) == 0)
							{
//								$name = substr($addressString, 0, $currentIndex - 1); // 'Microsoft Outloo
								$name = substr($addressString, 0, $currentIndex);
							}

							$inAddress = true;
							$startIndex = $currentIndex;
						}
						break;
					case '>':
						if ($inAddress)
						{
							$endIndex = $currentIndex;
							$email = substr($addressString, $startIndex + 1, $endIndex - $startIndex - 1);
							$addressString = substr_replace($addressString, '', $startIndex, $endIndex - $startIndex + 1);
							$endIndex = 0;
							$currentIndex = 0;
							$startIndex = 0;
							$inAddress = false;
						}
						break;
					case '(':
						if ((!$inName) && (!$inAddress) && (!$inComment))
						{
							$inComment = true;
							$startIndex = $currentIndex;
						}
						break;
					case ')':
						if ($inComment)
						{
							$endIndex = $currentIndex;
							$comment = substr($addressString, $startIndex + 1, $endIndex - $startIndex - 1);
							$addressString = substr_replace($addressString, '', $startIndex, $endIndex - $startIndex + 1);
							$endIndex = 0;
							$currentIndex = 0;
							$startIndex = 0;
							$inComment = false;
						}
						break;
					case '\\':
						$currentIndex++;
						break;
				}

				$currentIndex++;
			}

			if (strlen($email) == 0)
			{
				$regs = array('');
				if (preg_match('/[^@\s]+@\S+/i', $addressString, $regs))
				{
					$email = $regs[0];
				}
				else
				{
					$name = $addressString;
				}
			}

			if ((strlen($email) > 0) && (strlen($name) == 0) && (strlen($comment) == 0))
			{
				$name = str_replace($email, '', $addressString);
			}


			$this->Email = trim(trim($email), '<>');
			$this->DisplayName = trim(trim($name),'"');
			$this->Remarks = trim(trim($comment),'()');
		}

		/**
		 * Returns the e-mail address as a string.
		 * @return string
		 */
		function ToString($changeCharset = true)
		{
			$result = '';

			if ($this->Email != '')
			{
				$NewDisplayName = (substr($this->DisplayName, 0, 2) == '=?')  ? $this->DisplayName : ConvertUtils::EncodeHeaderString($this->DisplayName, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset], $changeCharset);
				$NewRemarks = (substr($this->Remarks, 0, 2) == '=?')  ? $this->Remarks : ConvertUtils::EncodeHeaderString($this->Remarks, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset], $changeCharset);

				if ($this->DisplayName != '' && $this->Remarks != '')
				{
					$result = '"'.$NewDisplayName.'" ';
					if (strlen($NewDisplayName.$this->Email) > MIMEConst_LineLengthLimit) $result .= CRLF."\t";
					$result .= '<'.$this->Email.'> ';
					if (strlen($NewDisplayName.$this->Email) > MIMEConst_LineLengthLimit)
					{
						if (strlen($this->Email.$NewRemarks) > MIMEConst_LineLengthLimit) $result .= CRLF."\t";
					}
					else
					{
						if (strlen($NewDisplayName.$this->Email.$NewRemarks) > MIMEConst_LineLengthLimit) $result .= CRLF."\t";
					}
					$result .= '('.$NewRemarks.')';
				}
				elseif ($this->DisplayName != '')
				{
					$result = '"'.$NewDisplayName.'" ';
					if (strlen($NewDisplayName.$this->Email) > MIMEConst_LineLengthLimit) $result .= CRLF."\t";
					$result .= '<'.$this->Email.'>';
				}
				elseif ($this->Remarks != '')
				{
					$result = '<'.$this->Email.'>';
					if (strlen($this->Email.$NewRemarks) > MIMEConst_LineLengthLimit) $result .= CRLF."\t";
					$result .= '('.$NewRemarks.')';
				}
				else
				{
					$result = $this->Email;
				}
			}
			else
			{
				if ($this->DisplayName !== '' && $this->Remarks === '')
				{
					$result = (substr($this->DisplayName, 0, 2) == '=?')  ? '"'.$this->DisplayName.'"' : '"'.ConvertUtils::EncodeHeaderString($this->DisplayName, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset], $changeCharset).'"';
				}
				else if ($this->DisplayName === '' && $this->Remarks !== '')
				{
					$result = (substr($this->Remarks, 0, 2) == '=?')  ? '('.$this->Remarks.')' : '('.ConvertUtils::EncodeHeaderString($this->Remarks, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset], $changeCharset).')';
				}
			}

			return $result;
		}

		/**
		 * Gets the e-mail address details as a string.
		 * @return string
		 */
		function ToDecodedString()
		{
			$result = '';

			if ($this->Email != '')
			{
				$NewDisplayName = (substr($this->DisplayName, 0, 2) != '=?')  ? $this->DisplayName : ConvertUtils::DecodeHeaderString($this->DisplayName, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset]);
				$NewRemarks = (substr($this->Remarks, 0, 2) != '=?')  ? $this->Remarks : ConvertUtils::DecodeHeaderString($this->Remarks, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset]);

				if ($this->DisplayName != '' && $this->Remarks != '')
				{
					$result = sprintf('"%s" <%s> (%s)', $NewDisplayName, $this->Email, $NewRemarks);
				}
				elseif ($this->DisplayName != '')
				{
					$result = sprintf('"%s" <%s>', $NewDisplayName, $this->Email);
				}
				elseif ($this->Remarks != '')
				{
					$result = sprintf('%s (%s)', $this->Email, $NewRemarks);
				}
				else
				{
					$result = $this->Email;
				}
			}

			return $result;
		}

		/**
		 * @return string
		 */
		function ToFriendlyString()
		{
			$out = '';
			if ($this->DisplayName != '')
			{
				$out = $this->DisplayName;
			}
			else if ($this->Email != '')
			{
				$out = $this->Email;
			}

			return $out;
		}
	}