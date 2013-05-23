<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/class_convertutils.php');

	class HeaderParameter
	{
		/**
		 * @var string
		 */
		var $Attribute = '';
		
		/**
		 * @var string
		 */
		var $Value = '';
		
		/**
		 * @param string $paramAttribute optional
		 * @param string $paramValue optional
		 * @return HeaderParameter
		 */
		function HeaderParameter($paramAttribute = '', $paramValue = '')
		{
			if ($paramAttribute != '' || $paramValue != '')
			{
				$this->Attribute = $paramAttribute;
				$this->Value = $paramValue;
			}
		}
		
		/**
		 * @param string $paramStr
		 * @param string $paramSeparator optional
		 */
		function Parse($paramStr, $separator = '=')
		{
			$parts = explode($separator, $paramStr, 2);

			$this->Attribute = trim(trim($parts[0]), '"\'');
			if (count($parts) == 2)
			{
				$this->Value = trim(trim($parts[1]), '"\'');
			}
			
		}
		
		/**
		 * @return string
		 */
		function ToString($doEncode = false)
		{
			$value = $this->Value;
			
			if ($doEncode)
			{
				if (!ConvertUtils::IsLatin($value))
				{
					$value = ConvertUtils::EncodeHeaderString($value, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset], true);	
				}
			}
			
			if ($this->Attribute != '' && $this->Value != '')
			{
				return sprintf('%s="%s"', $this->Attribute, $value);
			}
			elseif ($this->Attribute != '')
			{
				return $this->Attribute;
			}
			elseif ($this->Value != '')
			{
				return $value;
			}
			return '';
		}
	
	}
