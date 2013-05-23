<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/class_collectionbase.php');

	define('FILTERFIELD_From', 0);
	define('FILTERFIELD_To', 1);
	define('FILTERFIELD_Subject', 2);
	
	define('FILTERFIELD_XSpam', 3);
	define('FILTERFIELD_XVirus', 4);
	
	define('FILTERFIELD_CustomHeader', 5);
	
	define('FILTERCONDITION_ContainSubstring', 0);
	define('FILTERCONDITION_ContainExactPhrase', 1);
	define('FILTERCONDITION_NotContainSubstring', 2);
	define('FILTERCONDITION_StartFrom', 3);
	
	define('FILTERACTION_DoNothing', 0);
	define('FILTERACTION_DeleteFromServerImmediately', 1);
	define('FILTERACTION_MarkGrey', 2);
	define('FILTERACTION_MoveToFolder', 3);
	define('FILTERACTION_MoveToSpamFolder', 4);
	
	define('FILTERACTION_SpamDetect', 5);
	define('FILTERACTION_VirusDetect', 6);
	
	class Filter
	{
		/**
		 * @var int
		 */
		var $Id;
		
		/**
		 * @var int
		 */
		var $IdAcct;
		
		/**
		 * @var short
		 */
		var $Field;

		/**
		 * @var short
		 */
		var $Condition;
		
		/**
		 * @var string
		 */
		var $Filter;

		/**
		 * @var short
		 */
		var $Action;

		/**
		 * @var string
		 */
		var $CustomHeaderValue;
		
		/**
		 * @var int
		 */
		var $IdFolder;

		/**
		 * @var bool
		 */
		var $Applied = true;
		
		/**
		 * @var bool
		 */
		var $IsSystem = false;
		
		/**
		 * @param WebMailMessage $message
		 * @return short
		 */
		function GetActionToApply(&$message)
		{
			if ($this->Applied)
			{
				$field = null;
				switch ($this->Field)
				{
					case FILTERFIELD_From:
						$field = $message->GetFromAsString();
						break;
					case FILTERFIELD_To:
						$field = $message->GetAllRecipientsEmailsAsString();
						break;
					case FILTERFIELD_Subject:
						$field = $message->GetSubject();
						break;
					case FILTERFIELD_XSpam:
						$field = $message->GetSpamHeader();
						break;
					case FILTERFIELD_XVirus:
						$field = $message->GetVirusHeader();
						break;
					case FILTERFIELD_CustomHeader:
						$field = $message->Headers->GetHeaderValueByName($this->CustomHeaderValue);
						break;
					default:
						$field = null;
				}

				if ($field != null)
				{
					return $this->_processMessage(trim($field));
				}
			}
			return -1;
		}
		
		/**
		 * @access private
		 * @param string $field
		 * @return short
		 */
		function _processMessage($field)
		{
			$needToProcess = false;
			$field = strtolower($field);
			$filter = strtolower($this->Filter);
			
			switch ($this->Condition)
			{
				case FILTERCONDITION_ContainSubstring:
					if (strpos($field, $filter) !== false)
					{
						$needToProcess = true;
					}
					break;
				case FILTERCONDITION_ContainExactPhrase:
					if ($field == $filter)
					{
						$needToProcess = true;
					}
					break;
				case FILTERCONDITION_NotContainSubstring:
					if (strpos($field, $filter) === false)
					{
						$needToProcess = true;
					}
					break;
				case FILTERCONDITION_StartFrom:
					if (strtolower(substr($field, 0, strlen($filter))) == strtolower($filter))
					{
						$needToProcess = true;
					}
					break;
			}
			
			if ($needToProcess)
			{
				return $this->Action;
			}
			
			return -1;
		}
	}
	
	class FilterCollection extends CollectionBase
	{
		function FilterCollection()
		{
			CollectionBase::CollectionBase();
			
			$filter = new Filter();
			$filter->Action = FILTERACTION_VirusDetect;
			$filter->Field = FILTERFIELD_XVirus;
			$filter->Condition = FILTERCONDITION_StartFrom;
			$filter->Filter = 'infected';
			$filter->Id = $filter->IdAcct = $filter->IdFolder = -1;
			$filter->IsSystem = true;
			$this->Add($filter);
			unset($filter);
			
			$filter = new Filter();
			$filter->Action = FILTERACTION_SpamDetect;
			$filter->Field = FILTERFIELD_XSpam;
			$filter->Condition = FILTERCONDITION_StartFrom;
			$filter->Filter = 'spam';
			$filter->Id = $filter->IdAcct = $filter->IdFolder = -1;
			$filter->IsSystem = true;
			$this->Add($filter);
			unset($filter);

			CApi::Plugin()->RunHook('api-filter-collection-construct', array(&$this));
		}
		
		/**
		 * @param Filter $filter
		 */
		function Add(&$filter)
		{
			$this->List->Add($filter);
		}
		
		/**
		 * @param int $index
		 * @return Filter
		 */
		function &Get($index)
		{
			return $this->List->Get($index);
		}
	}
