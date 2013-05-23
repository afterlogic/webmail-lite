<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/mime/class_emailaddress.php');
	require_once(WM_ROOTPATH.'common/class_collectionbase.php');
	
	/**
	 * @package Mime
	 */

 	class EmailAddressCollection extends CollectionBase
	{

		/**
		 * Initializes a new instance of the EmailAddressCollection object with the list of e-mail addresses.
		 * @param string $emails optional
		 * @return EmailAddressCollection
		 */
		function EmailAddressCollection($emails = null)
		{
			CollectionBase::CollectionBase();
			if ($emails != null)
			{
				$this->Parse($emails);
			}
		}
		
		/**
		 * Sets the list of the e-mail addresses as a string.
		 * @param string $value
		 */
		function SetAsString($value)
		{
			$this->List->Clear();
			$this->Parse($value);
		}
		
		/**
		 * @param int $index
		 * @return EmailAddress
		 */
		function &Get($index)
		{
			return $this->List->Get($index);
		}
		
		/**
		 * @param int $index
		 * @param EmailAddress $item
		 */
		function Set($index, &$item)
		{
			$this->List->Set($index, $item);
		}
		
		/**
		 * @param EmailAddress $address
		 */
		function AddEmailAddress($address)
		{
			$this->List->Add($address);
		}
		
		/**
		 * @param EmailAddressCollection $recipients
		 */
		function AddEmailAddressCollection($recipients)
		{
			for ($i = 0; $i < $recipients->Count(); $i++)
			{
				$this->AddEmailAddress($recipients->Get($i));
			}
		}
		
		/**
		 * Adds the e-mail address (specified as actual e-mail address, display name and remarks parts) to the collection.
		 * @param string $email
		 * @param string $name optional
		 * @param string $remarks optional
		 */
		function Add($email, $name = '', $remarks = '')
		{
			$this->List->Add(new EmailAddress($email, $name, $remarks));
		}
		
		/**
		 * Adds the specified full e-mail address (including display name if any) to the collection.
		 * @param string $address
		 */
		function AddFromString($address)
		{
			$this->AddEmailAddress(EmailAddress::Parse($address, $this->_header));
		}
		
		/**
		 * Removes the specified e-mail address from the collection.
		 * @param string $email
		 */
		function Remove($email)
		{
			for ($i = $this->List->Count() - 1; $i >= 0; $i--)
			{
				$addr = $this->List->Get($i);
				if ($addr->GetEmail() == $email)
				{
					$this->List->RemoveAt($i);
				}
			}
		}

		/**
		 * Clears the entire collection of the e-mail addresses.
		 */
		function Clear()
		{
			$this->List->Clear();
		}

		/**
		 * Parse specified string-based list of the e-mail addresses.
		 * @param string $recipients
		 */
		function Parse($recipients)
		{
			if ($recipients == null)  return;

			$sWorkingRecipients = $recipients;
			$sWorkingRecipients = trim($sWorkingRecipients);

			$emailStartPos = 0;
			$emailEndPos = 0;

			$isInQuotes = false;
			$chQuote = '"';
			$isInAngleBrackets = false;
			$isInBrackets = false;

			$currentPos = 0;
			
			$sWorkingRecipientsLen = strlen($sWorkingRecipients);
			
			while ($currentPos < $sWorkingRecipientsLen)
			{
				switch ($sWorkingRecipients{$currentPos})
				{
					case '\'':
					case '"':
						if (!$isInQuotes)
						{
							$chQuote = $sWorkingRecipients{$currentPos};
							$isInQuotes = true;
						}
						elseif ($chQuote == $sWorkingRecipients{$currentPos})
						{
							$isInQuotes = false;
						}
						break;
						
					case '<':
						if (!$isInAngleBrackets)
						{
							$isInAngleBrackets = true;
						}
						break;

					case '>':
						if ($isInAngleBrackets)
						{
							$isInAngleBrackets = false;
						}
						break;
						
					case '(':
						if (!$isInBrackets)
						{
							$isInBrackets = true;
						}
						break;
						
					case ')':
						if ($isInBrackets)
						{
							$isInBrackets = false;
						}
						break;
						
					case ',':
					case ';':											
						if (!$isInAngleBrackets && !$isInBrackets && !$isInQuotes)
						{
							$emailEndPos = $currentPos;
							$addr = new EmailAddress();
							$addr->SetAsString(substr($sWorkingRecipients, $emailStartPos, $emailEndPos - $emailStartPos));
							$this->AddEmailAddress($addr);
							$emailStartPos = $currentPos + 1;
						}
						break;
				}
				$currentPos++;
			}
			
			if ($emailStartPos < $currentPos)
			{
				$addr = new EmailAddress();
				$addr->SetAsString(substr($sWorkingRecipients, $emailStartPos, $currentPos - $emailStartPos));
				$this->AddEmailAddress($addr);
			}
		}
		
		/**
		 * Returns the string containing all the e-mail addresses in the collection as comma-separated list.
		 * @return string
		 */
		function ToString($changeCharset = true)
		{
			$result = '';
			if ($this->List->Count() > 0)
			{
				foreach ($this->List->Instance() as $address)
				{
					$result .= ($result !== '') ? "\t" : '';
					$result .= $address->ToString($changeCharset) . ','.CRLF;
				}
				
				$result = substr($result, 0, strlen($result) - 1 - strlen(CRLF));
			}

			return $result;	
		}
		
		/**
		 * Gets the list of the e-mail addresses as a string.
		 * @return string
		 */
		function ToDecodedString()
		{
			$result = '';
			if ($this->List->Count() > 0)
			{
				foreach ($this->List->Instance() as $address)
				{
					$result .= $address->ToDecodedString(). ', ';
				}

				$result = substr($result, 0, strlen($result)  - 2);
			}

			return $result;
		}
		
		/**
		 * @return string
		 */
		function ToFriendlyString()
		{
			$result = '';
			if ($this->List->Count() > 0)
			{
				foreach ($this->List->Instance() as $address)
				{
					$result .= $address->ToFriendlyString() . ', ';
				}
				$result = trim(trim($result), ',');
			}

			return $result;			
		}
	}
