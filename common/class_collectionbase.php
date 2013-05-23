<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/class_collectionlist.php');

	/**
	 * @package Collections
	 */

	class CollectionBase
	{
		/**
		 * @access private
		 * @var CollectionList
		 */
		var $List;
		
		function CollectionBase()
		{
			$this->List = new CollectionList();
		}
      
		/**
		* @return Array 
		*/
		function &Instance()
		{
			return $this->List->Instance();
		}
		
		/**
		 * @return int
		 */
		function Count()
		{
			return $this->List->Count();
		}

		/**
		 * @param int $index
		 * @return object
		 */
		function &Get($index)
		{
			return $this->List->Get($index);
		}
		
		/**
		 * @param int $index
		 * @param object $item
		 */
		function Set($index, &$item)
		{
			$this->List->Set($index, $item);
		}
		
		/**
		 * @param int $index
		 */
		function RemoveAt($index)
		{
			$this->List->RemoveAt($index);
		}
	}