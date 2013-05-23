<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	class CollectionList
	{
		/**
		 * @access private
		 * @var array
		 */
		var $_list = array();
		
		/**
		* @return array 
		*/
		function &Instance()
		{
			return $this->_list;
		}

		function Clear()
		{
			$this->_list = array();
		}
		
		/**
		 * @return Array
		 */
		function &GetKeys()
		{
			return array_keys($this->_list);
		}
		
		/**
		* @param object $item
		*/
		function Add(&$item)
		{
		  $this->_list[] = &$item;
		}
		
		/**
		* @param object $item
		*/
		function AddCopy($item)
		{
		  $this->_list[] = $item;
		}
		
		/**
		* @param object $item 
		*/
		function Remove($item)
		{
			foreach ($this->_list as $key => $value)
			{
				if ($item == $value)
				{
					array_splice($this->_list, $key, 1);
					break;
				}
			}
		}
		
		/**
		* @param object $item 
		*/
		function Contains($item)
		{
			foreach ($this->_list as $value)
			{
				if ($item == $value)
				{
					return true;
				}
			}
			return false;
		}
		
		/**
		 * @param int $index
		 */
		function RemoveAt($index)
		{
			array_splice($this->_list, $index, 1);
		}
		
		/**
		 * @param int $index
		 * @return object
		 */
		function &Get($index)
		{
			return $this->_list[$index];
		}
		
		/**
		 * @param int $index
		 * @param object $item
		 */
		function Set($index, &$item)
		{
			$this->_list[$index] = &$item;
		}
		
		/**
		* @param int $index
		* @param object $item 
		*/
		function Insert($index, &$item)
		{
			array_splice($this->_list, $index, 0, $item);
		}
		
		/**
		 * @return int
		 */
		function Count()
		{
			return count($this->_list);
		}
		
	}