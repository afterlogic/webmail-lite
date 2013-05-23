<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/class_collectionbase.php');
	require_once(WM_ROOTPATH.'common/mime/class_headerparameter.php');
	
	/**
	 * @package Mime
	 */
	
	class HeaderParameterCollection extends CollectionBase
	{
		/**
		 * @return HeaderParameterCollection
		 */
		function HeaderParameterCollection($params = null)
		{
			CollectionBase::CollectionBase();
			if ($params != null)
			{
				$this->Parse($params);
			}
		}

		/**
		 * @param int $index
		 * @return HeaderParameter
		 */
		function &Get($index)
		{
			return $this->List->Get($index);
		}

		/**
		 * @param int $index
		 * @param HeaderParameter $value
		 */
		function Set($index, &$value)
		{
			$this->List->Set($index, $value);
		}
		
		/**
		 * @param string $name
		 * @return HeaderParameter
		 */
		function GetByName($name)
		{
			foreach ($this->List->Instance() as $param)
			{
				if (strtolower($param->Attribute) == strtolower($name))
				{
					return $param;
				}
			}
			return null;
		}
		
		/**
		 * @param HeaderParameter $value
		 */
		function SetByName(&$value)
		{
			foreach ($this->List->Instance() as $index => $param)
			{
				if (strtolower($param->Attribute) == strtolower($value->Attribute))
				{
					$this->List->Set($index, $value);
					break;
				}
			}
		}
		
		/**
		 * @param HeaderParameter $valueParam
		 */
		function Add(&$valueParam)
		{
			$this->List->Add($valueParam);
		}
		
		/**
		 * @param int $index
		 * @param HeaderParameter $valueParam
		 */
		function Insert($index, &$valueParam)
		{
			$this->List->Insert($index, $valueParam);
		}
		
		/**
		 * @param HeaderParameter $valueParam
		 */
		function Remove(&$valueParam)
		{
			$this->List->Remove($valueParam);
		}

		function Clear()
		{
			$this->List->Clear();
		}
		
		/**
		 * @param HeaderParameter $valueParam
		 * @return bool
		 */
		function Contains(&$valueParam)
		{
			// If the value is not of Header type, this will return false.
			return $this->List->Contains($valueParam);
		}
		
		/**
		 * @param string $dataToParse
		 */
		function Parse($dataToParse)
		{
			$values = explode(';', $dataToParse);
			
			foreach ($values as $param)
			{
				$hparam = new HeaderParameter();
				$hparam->Parse(trim($param));
				$this->List->Add($hparam);
				unset($hparam);
			}
		}
		
		/**
		 * @return string
		 */
		function ToString($doEncode = false)
		{
			$result = '';
			$newLine = ';'.CRLF."\t";
			if ($this->List->Count() > 0)
			{
				foreach ($this->List->Instance() as $param)
				{
					$result .= $param->ToString($doEncode) . $newLine;
				}
				
				$result = substr($result, 0, strlen($result) - strlen($newLine));
			}
			return $result;			
		}
	}
