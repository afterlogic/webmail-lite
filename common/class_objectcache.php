<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));
	
	class CObjectCache
	{
		var $_cache = array();
		
		/**
		 * @param mixed $_index
		 * @return mixed
		 */
		function &Get($_index)
		{
			$_return = null;
			if (isset($this->_cache[$_index]))
			{
				$_return =& $this->_cache[$_index];
			}
			return $_return;
		}
		
		/**
		 * @param mixed $_index
		 * @param mixed $_obj
		 */
		function Set($_index, &$_obj)
		{
			$this->_cache[$_index] =& $_obj;
		}
		
		/**
		 * @param mixed $_index
		 * @return bool
		 */		
		function Has($_index)
		{
			return isset($this->_cache[$_index]);
		}
		
		/**
		 * @param mixed $_index
		 */			
		function Erase($_index)
		{
			if (isset($this->_cache[$_index]))
			{
				unset($this->_cache[$_index]);
			}
		}
		
		function Clear()
		{
			$this->_cache = array();
		}
		
		/**
		 * @return CObjectCache
		 */
		public static function &CreateInstance()
		{
			static $instance;
    		if (!is_object($instance))
    		{
				$instance = new CObjectCache();
    		}
    		return $instance;
		}
	}