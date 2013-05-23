<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));
		
	require_once WM_ROOTPATH.'common/inc_constants.php';
	require_once WM_ROOTPATH.'common/class_convertutils.php';
	
	class SystemFolders
	{
		/**
		 * @var string
		 */
		const FILE_NAME = 'system_folders.xml';

		/**
		 * @var array
		 */
		private $_main;

		/**
		 * @return SystemFolders
		 */
		public static function &CreateInstance()
		{
			static $instance = null;
    		if (null === $instance)
    		{
				$instance = new SystemFolders();
    		}

    		return $instance;
		}

		private function SystemFolders()
		{
			$this->_main = array();
			$this->LoadXML();
		}

		/**
		 * @return array
		 */
		public function GetSystemFoldersNames()
		{
			return $this->_main;
		}

		public static function StaticGetSystemFoldersNames()
		{
			$sf =& self::CreateInstance();
			return $sf->GetSystemFoldersNames();
		}

		/**
		 * @return	bool
		 */
		protected function LoadXML()
		{
			if (@file_exists(INI_DIR.'/settings/'.self::FILE_NAME))
			{
				$xmlDocument = new CXmlDocument();
				if ($xmlDocument->LoadFromFile(INI_DIR.'/settings/'.self::FILE_NAME))
				{
					return $this->initFromXmlRoot($xmlDocument->XmlRoot);
				}
				return false;
			}
			
			return true;
		}

		/**
		 * @param	XmlDomNode	$xmlTree
		 * @param	array		$mainArray
		 */
		protected function initFromXmlRoot(&$xmlTree)
		{
			if ($xmlTree && $xmlTree->Children && count($xmlTree->Children) > 0)
			{
				if ('Folders' === $xmlTree->TagName)
				{
					foreach ($xmlTree->Children as $node)
					{
						if ('Folder' == $node->TagName)
						{
							$this->_main[] = ConvertUtils::WMBackHtmlSpecialChars($node->Value);
						}
					}
				}
				else
				{
					foreach ($xmlTree->Children as $node)
					{
						$this->initFromXmlRoot($node);
					}
				}
			}
		}
	}
	