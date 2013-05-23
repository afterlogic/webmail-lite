<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/mime/inc_constants.php');
	
	define('ACCOUNT_HIERARCHY_DEPTH', 1);
	
	class FileSystem
	{
		/**
		 * @var string
		 */
		var $RootFolder;
		
		/**
		 * @var string
		 */
		var $AccountName;
		
		/**
		 * @access private
		 * @var int
		 */
		var $_accountId;
		
		/**
		 * @param string $accountName
		 * @param string $rootFolder
		 * @return FileSystem
		 */
		function FileSystem($rootFolder, $accountName, $accountId)
		{
			$this->_accountId = $accountId;
			$this->AccountName = strtolower($accountName.'.'.$accountId);
			$this->RootFolder = $rootFolder;
		}
		
		/**
		 * @param long $idMsg
		 * @param Folder $folder
		 * @return WebMailMessage
		 */
		function &LoadMessage($idMsg, &$folder)
		{
			$path = $this->_createFolderFullPath($folder->FullName);
			
			$msg = new WebMailMessage();
			if ($msg->LoadMessageFromEmlFile($path.'/'.$idMsg.'.eml', true))
			{
				CApi::Log('FS: Load file '.$path.'/'.$idMsg.'.eml');
				$msg->IdMsg = $idMsg;
				return $msg;
			}
			else
			{
				CApi::Log('FS: Load file '.$path.'/'.$idMsg.'.eml', ELogLevel::Error);
			}
			
			$msg = null;
			return $msg;
		}
		
		function &LoadMessageFromTemp($tempName, &$folder)
		{
			$path = $this->_createFolderFullPath($folder->FullName);

			$msg = new WebMailMessage();
			if ($msg->LoadMessageFromEmlFile($path.'/'.$tempName, true))
			{
				CApi::Log('FS: Load file '.$path.'/'.$tempName);
			}
			else
			{
				$msg = null;
				CApi::Log('FS: Can\'t load file '.$path.'/'.$tempName, ELogLevel::Error);
			}
			
			return $msg;
		}
		
		/**
		 * @param WebMailMessage $msg
		 * @param Folder $folder
		 * @return bool
		 */
		function SaveMessage(&$msg, &$folder)
		{
			$path = $this->_createFolderFullPath($folder->FullName);
			
			CApi::Log('FS: Save file '.$path.'/'.$msg->IdMsg.'.eml');
			return $this->CreateFolder($folder) && $msg->SaveMessage($path.'/'.$msg->IdMsg.'.eml');
		}
		
		/**
		 * @param WebMailMessage $msg
		 * @param Folder $folder
		 * @return bool
		 */
		function UpdateMessage(&$msg, &$folder)
		{
			$path = $this->_createFolderFullPath($folder->FullName);
			
			CApi::Log('FS: Update file '.$path.'/'.$msg->IdMsg.'.eml');
			return $this->CreateFolder($folder) && $msg->SaveMessage($path.'/'.$msg->IdMsg.'.eml');
		}
				
		/**
		 * @param Array $messageIdSet
		 * @param Folder $fromFolder
		 * @param Folder $toFolder
		 * @return bool
		 */
		function MoveMessages(&$messageIdSet, &$fromFolder, &$toFolder)
		{
			$fromPath = $this->_createFolderFullPath($fromFolder->FullName);
			$toPath = $this->_createFolderFullPath($toFolder->FullName);
			
			$result = $this->CreateFolder($toFolder);
			
			foreach ($messageIdSet as $idMsg)
			{
				if (@rename($fromPath.'/'.$idMsg.'.eml', $toPath.'/'.$idMsg.'.eml'))
				{
					CApi::Log('FS: Rename file '.$fromPath.'/'.$idMsg.'.eml => '.$toPath.'/'.$idMsg.'.eml');
					$result &= true;	
				}
				else 
				{
					CApi::Log('FS: Can\'t rename file '.$fromPath.'/'.$idMsg.'.eml => '.$toPath.'/'.$idMsg.'.eml', ELogLevel::Error);
					$result = false;
				}
			}
			
			return $result;
		}
		
		/**
		 * @param Array $messageIdSet
		 * @param Folder $folder
		 * @param bool $notLogFileExist = false
		 * @return bool
		 */
		function DeleteMessages(&$messageIdSet, &$folder, $notLogFileExist = false)
		{
			$result = true;
			$path = $this->_createFolderFullPath($folder->FullName);
			foreach ($messageIdSet as $idMsg)
			{
				if (@file_exists($path.'/'.$idMsg.'.eml'))
				{
					if (@unlink($path.'/'.$idMsg.'.eml'))
					{
						CApi::Log('FS: Delete file '.$path.'/'.$idMsg.'.eml');
						$result &= true;
					}
					else 
					{
						CApi::Log('FS: Can\'t delete file '.$path.'/'.$idMsg.'.eml', ELogLevel::Error);
						$result = false;
					}
				}
				else 
				{
					if (!$notLogFileExist)
					{
						CApi::Log('FS: Can\'t delete file (not exist) '.$path.'/'.$idMsg.'.eml', ELogLevel::Error);
					}
				}
			}
			
			return $result;
		}

		/**
		 * @param Folder $folder
		 * @return bool
		 */
		function CreateFolder(&$folder)
		{
			$path = rtrim($this->_createFolderFullPath($folder->FullName), '/');
			if (@is_dir($path))
			{
				return true;
			}
			
			return $this->_createRecursiveFolderPath($path);
		}
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function GetFolderFullPath(&$folder)
		{
			return rtrim($this->_createFolderFullPath($folder->FullName), '/');
		}
		
		/**
		 * @param string $string
		 * @return bool
		 */
		function CreateFolderFromString($string)
		{
			$path = rtrim($this->_createFolderFullPath($string), '/');
			if (@is_dir($path))
			{
				return true;
			}
			
			return $this->_createRecursiveFolderPath($path);
		}

		/**
		 * @access private
		 * @param string $path
		 * @return bool
		 */
		function _createRecursiveFolderPath($path)
		{
			$result = true;
			$rootFolder = substr($path, 0, strrpos($path, '/'));
			if (!@is_dir($rootFolder))
			{
				$result &= $this->_createRecursiveFolderPath($rootFolder);
			}
			if (@mkdir($path))
			{
				CApi::Log('FS: Create folder '.$path);

				$result &= true;	
			}
			else 
			{
				CApi::Log('FS: Can\'t create folder '.$path, ELogLevel::Error);
				$result = false;
			}
			
			return $result;
		}
		
		/**
		 * @access private
		 * @param string $folder
		 * @return string
		 */
		function _createFolderFullPath($folder)
		{
			$returnPath = $this->RootFolder.'/';

			for ($i = 0; $i <= ACCOUNT_HIERARCHY_DEPTH - 1; $i++)
			{
				$returnPath .= $this->AccountName{$i}.'/';
			}

			$returnPath .= $this->AccountName.'/'.$folder;
			return rtrim($returnPath, '/\\');
		}
		
		/**
		 * @param Attachment $attach
		 * @param Folder $folder
		 * @param string $tempname
		 * @return bool
		 */
		function SaveAttach(&$attach, &$folder, $tempname)
		{
			$path = $this->_createFolderFullPath($folder->FullName);
			if ($this->CreateFolder($folder))
			{
				return $attach->SaveToFile($path.'/'.$tempname);
			}
			return -1;
		}

		/**
		 * @param string $data
		 * @param Folder $folder
		 * @param string $tempname
		 * @return bool
		 */
		function SaveBinaryAttach(&$data, &$folder, $tempname)
		{
			if (!$this->CreateFolder($folder))
			{
				return false;
			}
			
			$filename = $this->_createFolderFullPath($folder->FullName).'/'.$tempname;
			$fh = @fopen($filename, 'wb');
			if ($fh)
			{
				if (@fwrite($fh, $data))
				{
					@fclose($fh);
					return true;
				}
				setGlobalError('can\'t write file: '.$filename);
			}
			else
			{
				setGlobalError('can\'t open file(wb): '.$filename);
			}

			return false;
		}
		
		/**
		 * @param Folder $folder
		 * @param string $tempname
		 * @return string
		 */
		function LoadBinaryAttach(&$folder, $tempname)
		{
			$data = '';
			$filename = $this->_createFolderFullPath($folder->FullName).'/'.$tempname;
			$handle = @fopen($filename, 'rb');
			if ($handle)
			{
				while (!feof($handle))
				{
					$temp = fread($handle, 8192);
					if (!$temp) break;
					$data .= $temp;
				}
				fclose($handle);
				return $data;
			}
			return '';
		}

		/**
		 * @param Folder $folder
		 * @param string $tempname
		 * @return bool
		 */
		function IsTempFileExist(&$folder, $tempname)
		{
			return @file_exists($this->_createFolderFullPath($folder->FullName).'/'.$tempname);
		}
		
		/**
		 * @param Folder $folder
		 */
		function ClearDir(&$folder)
		{
			$path = $this->_createFolderFullPath($folder->FullName);

			if (@is_dir($path))
			{
				$dh = @opendir($path);
				if ($dh)
				{
					while (($file = @readdir($dh)) !== false)
					{
						if ($file != '.' && $file != '..')
						{ 
							@unlink($path.'/'.$file);
						} 
					}
					@closedir($dh);
				}
			}
		}
		
		/**
		 * @param Folder $folder
		 */
		function DeleteDir(&$folder)
		{
			$path = $this->_createFolderFullPath($folder->FullName);
			$count = 0;
			if (@is_dir($path))
			{
				$dh = @opendir($path);
				if ($dh)
				{
					while (($file = @readdir($dh)) !== false)
					{
						if ($file != '.' && $file != '..')
						{
							$count++;
						} 
					}
					@closedir($dh);
				}
				if ($count) $this->ClearDir($folder);
				@rmdir($path);
			}
		}

		/**
		 * @param string $newAccountName
		 * @return bool
		 */
		function MoveFolders($newAccountName)
		{
			$oldFolderPath = rtrim($this->_createFolderFullPath(''), '/');
			
			if (!@is_dir($oldFolderPath))
			{
				return true;
			}
			
			$fs = new FileSystem($this->RootFolder, $newAccountName, $this->_accountId);
			
			$newFolderPath = rtrim($fs->_createFolderFullPath(''), '/');
			
			$rootFolder = substr($newFolderPath, 0, strrpos($newFolderPath, '/'));
			
			if (!@is_dir($rootFolder))
			{
				$this->_createRecursiveFolderPath($rootFolder);
			}
			
			if (!@rename($oldFolderPath, $newFolderPath))
			{
				CApi::Log('FS: Error move folder: '.$oldFolderPath.' => '.$newFolderPath, ELogLevel::Error);
				return false;
			}
			
			return true;
		}
		
		/**
		 * @param string $folderPath
		 * @return bool
		 */
		function IsFolderExist($folderPath)
		{
			$accountPath = rtrim($this->_createFolderFullPath(''), '/');
			
			return @is_dir($accountPath.'/'.$folderPath);
		}

		/**
		 * @param string $folderPath
		 * @return bool
		 */
		function MoveSubFolders($oldFolderPath, $newFolderPath)
		{
			$oldFullPath = rtrim($this->_createFolderFullPath($oldFolderPath), '/');
			$newFullPath = rtrim($this->_createFolderFullPath($newFolderPath), '/');
			
			return rename($oldFullPath, $newFullPath);
		}

		/**
		 * @param string[optional] $subfolder
		 * @return bool
		 */
		function DeleteAccountDirs($subfolder = '')
		{
			$path = $this->_createFolderFullPath($subfolder);
			
			if (@is_dir($path))
			{
				$dh = @opendir($path);
				if ($dh)
				{
					while (($file = @readdir($dh)) !== false)
					{
						if ($file != '.' && $file != '..')
						{ 
							if (@is_dir($path.'/'.$file))
							{
								$this->DeleteAccountDirs($file);
							}
							else 
							{
								@unlink($path.'/'.$file);
							}
						} 
					}
					@closedir($dh);
				}
				@rmdir($path);
			}
			
			return true;
		}

		/**
		 * @param array $filesArray
		 * @param string[optional] $subfolder
		 * @return bool
		 */
		function DeleteTempFilesByArray($filesArray, $subfolder = '')
		{
			if (!is_array($filesArray) || count($filesArray) < 1)
			{
				return true;
			}
			
			$path = $this->_createFolderFullPath($subfolder);

			if (@is_dir($path))
			{
				$dh = @opendir($path);
				if ($dh)
				{
					while (($file = @readdir($dh)) !== false)
					{
						if ($file != '.' && $file != '..')
						{ 
							if (@is_dir($path.'/'.$file))
							{
								$this->DeleteTempFilesByArray($filesArray, $file);
							}
							else 
							{
								if (in_array($file, $filesArray))
								{
									CApi::Log('FS: Delete temp - '.$path.'/'.$file);
									@unlink($path.'/'.$file);
								}
							}
						} 
					}
					@closedir($dh);
				}
			}
		
			return true;
		}

		/**
		 * @param string $filename
		 * @param string $tempFolderName
		 * @return string
		 */
		function CacheFileExist($filename, $tempFolderName)
		{
			return @file_exists($this->_createFolderFullPath($tempFolderName).'/'.$filename);
		}

		/**
		 * @param string $filename
		 * @param string $tempFolderName
		 * @return string
		 */
		function CacheFileLoad($filename, $tempFolderName)
		{
			$data = '';
			$filename = $this->_createFolderFullPath($tempFolderName).'/'.$filename;
			$handle = @fopen($filename, 'rb');
			if ($handle)
			{
				while (!feof($handle))
				{
					$temp = @fread($handle, 8192);
					if (!$temp) break;
					$data .= $temp;
				}
				@fclose($handle);
				return $data;
			}
			return '';
		}

		/**
		 * @param string $body
		 * @param string $filename
		 * @param string $tempFolderName
		 * @return string
		 */
		function CacheFileSave($body, $filename, $tempFolderName)
		{
			$filename = $this->_createFolderFullPath($tempFolderName).'/'.$filename;

			$fh = @fopen($filename, 'wb');
			if ($fh)
			{
				if (!@fwrite($fh, $body))
				{
					setGlobalError('can\'t write file: '.$filename);
					$returnBool = false;
				}
				@fclose($fh);
			}
			else
			{
				setGlobalError('can\'t open file(wb): '.$filename);
				$returnBool = false;
			}

			return $returnBool;
		}
	}
