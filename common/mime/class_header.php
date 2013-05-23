<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/class_convertutils.php');

	class Header
	{
		/**
		 * @var string
		 */
		var $Name;
		
		/**
		 * @var string
		 */
		var $Value;
		
		/**
		 * @var bool
		 */
		var $IsParsed;
		
		/**
		 * @param string $name
		 * @param string $value
		 * @return Header
		 */
		function Header($name, $value, $isParsed = false)
		{
			$this->Name = $name;
			$this->Value = $value;
			$this->IsParsed = $isParsed;
		}
		
		/**
		 * @return string
		 */
		function ToString()
		{
			return  $this->Name.': '.$this->GetEncodedValue();
		}
		
		/**
		 * @return string
		 */
		function GetDecodedValue()
		{
			return ($this->IsParsed) ? $this->Value : ConvertUtils::DecodeHeaderString($this->Value, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset], $this->IsWithParameters());
		}
		
		/**
		 * @return string
		 */
		function GetEncodedValue()
		{
			if ($this->IsParsed)
			{
				if ($this->IsEmailAddress())
				{
					$addressCollection = new EmailAddressCollection($this->Value);
					return $addressCollection->ToString();
				}
				if ($this->IsSubject())
				{
					return ConvertUtils::EncodeHeaderString($this->Value, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset], true);
				}
				if ($this->IsWithParameters())
				{
					$parameterCollection = new HeaderParameterCollection($this->Value);
					return $parameterCollection->ToString(true);
				}
			}
			
			if (ConvertUtils::IsLatin($this->Value))
			{
				return $this->Value;
			}
			else 
			{
				return ConvertUtils::EncodeHeaderString($this->Value, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset]);
			}
			
		}
		
		/**
		 * @return bool
		 */
		function IsEmailAddress()
		{
			$lowerName = strtolower($this->Name);
			if ($lowerName == MIMEConst_BccLower || $lowerName == MIMEConst_CcLower ||
				$lowerName == MIMEConst_FromLower || $lowerName == MIMEConst_ReplyToLower ||
				$lowerName == MIMEConst_ToLower || $lowerName == MIMEConst_ReturnPathLower)
			{
				return true;
			}
			return false;
		}
		
		/**
		 * @return bool
		 */
		function IsSubject()
		{
			return (strtolower($this->Name) == MIMEConst_SubjectLower) ? true : false;
		}	
		
		/**
		 * @access private
		 * @return bool
		 */
		function IsWithParameters()
		{
			$lowerName = strtolower($this->Name);
			return ($lowerName == MIMEConst_ContentDispositionLower || $lowerName == MIMEConst_ContentTypeLower);
		}
			
	}