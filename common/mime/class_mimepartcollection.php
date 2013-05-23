<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/mime/class_mimepart.php');
  	require_once(WM_ROOTPATH.'common/class_collectionbase.php');

	class MimePartCollection extends CollectionBase
	{
		/**
		 * @access private
		 * @var MimePart
		 */
		var $_parent;
		
		function MimePartCollection(&$parent)
		{
			CollectionBase::CollectionBase();
			$this->_parent =& $parent;
		}
		
		/**
		 * @access internal
		 * @param MimePart $valueParam
		 */
		function Add($valueParam) 
		{
			$this->List->Add($valueParam);
		}
		
		/**
		 * @param int $index
		 * @return MimePart
		 */
		function &Get($index)
		{
			return $this->List->Get($index);
		}
		
		/**
		 * @return string
		 */
		function ToString()
		{
			$retval = '';	
			$lineStr = '--';		
			if ($this->List->Count() > 0)
			{
				$bound = $this->_parent->GetBoundary();
				foreach ($this->List->Instance() as $mimePart)
				{
					if ($mimePart)
					{
						$retval .= $lineStr.$bound.CRLF;
						$retval .= $mimePart->ToString().CRLF;
					}
				}
				$retval .= $lineStr.$this->_parent->GetBoundary().$lineStr.CRLF;
			}

			return $retval;
		}
	}