<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));
require_once WM_ROOTPATH.'common/class_filesystem.php';
require_once WM_ROOTPATH.'common/class_folders.php';

class CTempFiles
{
	/**
	 * @access private
	 * @var CFileSystemTempFilesDriver
	 */
	var $_driver;
	
	/**
	 * @var string $tempName
	 * @return string|false
	 */	
	function LoadFile($tempName)
	{
		return $this->_driver->LoadFile($tempName);
	}
	
	/**
	 * @var string $tempName
	 * @var string $body
	 * @return int (file size or -1)
	 */	
	function SaveFile($tempName, $body)
	{
		return $this->_driver->SaveFile($tempName, $body);
	}
	
	/**
	 * @var string $tempName
	 * @return bool
	 */
	function IsFileExist($tempName)
	{
		return $this->_driver->IsFileExist($tempName);
	}
	
	/**
	 * @var string $tempName
	 * @return string | false
	 */
	function GetFullFilePath($tempName)
	{
		return $this->_driver->GetFullFilePath($tempName);
	}
	
	/**
	 * @var string $tempName
	 * @return int | false
	 */
	function FileSize($tempName)
	{
		return $this->_driver->FileSize($tempName);
	}
	
	function MoveUploadedFile($serverTempFile, $fileTempName)
	{
		return $this->_driver->MoveUploadedFile($serverTempFile, $fileTempName);
	}
	
	function ClearAccountCompletely()
	{
		return $this->_driver->ClearAccountCompletely();
	}

	function GetNextTempName($sFileName)
	{
		return $this->_driver->GetNextTempName($sFileName);
	}
	
	function SaveFileFromStream($sTempName, $rFileStream)
	{
		return $this->_driver->SaveFileFromStream($sTempName, $rFileStream);
	}
	
	function ClearAccount()
	{
		return $this->_driver->ClearAccount();
	}

	function ConvertTempFile($sInFileName, $sOutFileName)
	{
		return $this->_driver->ConvertTempFile($sInFileName, $sOutFileName);
	}
	
	/**
	 * @return CTempFiles
	 */
	public static function &CreateInstance($account)
	{
		static $instance = null;
    	if (null === $instance)
    	{
			$instance = new CTempFiles($account);
    	}

    	return $instance;
	}
	
	/**
	* @access private
	*/
	function CTempFiles($account)
	{
		$this->_driver = new CFileSystemTempFilesDriver($account);
	}
}

class CFileSystemTempFilesDriver
{
	/**
	 * @access private
	 * @var Folder
	 */
	var $_folder;
	
	/**
	 * @access private
	 * @var FileSystem
	 */
	var $_fs;
	
	/**
	 * @param	Account	$accout
	 * @return	CFileSystemTempFilesDriver
	 */
	function CFileSystemTempFilesDriver($account)
	{
		$this->_fs = new FileSystem(INI_DIR.'/temp', strtolower($account->Email), $account->IdAccount);
	    $this->_folder = new Folder($account->IdAccount, -1, GetSessionAttachDir());
	}
	
	/**
	 * @var string $tempName
	 * @return string|false
	 */	
	function LoadFile($tempName)
	{
		return @file_get_contents($this->_fs->GetFolderFullPath($this->_folder).'/'.$tempName);
	}
	
	/**
	 * @var string $tempName
	 * @var string $body
	 * @return int (save file size or -1)
	 */	
	function SaveFile($tempName, $body)
	{
		$this->_fs->CreateFolder($this->_folder);
		
		$fileName = $this->_fs->GetFolderFullPath($this->_folder).'/'.$tempName;
		$returnBool = true;
		$size = @file_put_contents($fileName, $body);
		if (false === $size)
		{
			setGlobalError('can\'t open file(wb): '.$fileName);
			$returnBool = false;
		}
		
		if ($returnBool && null !== $body)
		{
			return strlen($body);
		}
		
		return -1;
	}

	/**
	 * @var string $tempName
	 * @var string $body
	 * @return int (save file size or -1)
	 */
	function SaveFileFromStream($sTempName, $rFileStream)
	{
		$this->_fs->CreateFolder($this->_folder);
		$fileName = $this->_fs->GetFolderFullPath($this->_folder).'/'.$sTempName;

		$target = @fopen($fileName, 'wb');
		if ($target)
		{
			@fseek($rFileStream, 0, SEEK_SET);
			$size = @stream_copy_to_stream($rFileStream, $target);
			if (false !== $size)
			{
				return $size;
			}
			else
			{
				setGlobalError('can\'t write file(wb): '.$fileName);
			}
			@fclose($target);
		}
		else
		{
			setGlobalError('can\'t open file(wb): '.$fileName);
		}
        
		return false;
	}
	
	/**
	 * @var string $tempName
	 * @return bool
	 */
	function IsFileExist($tempName)
	{
		return $this->_fs->IsTempFileExist($this->_folder, $tempName);
	}

	/**
	 * @var string $tempName
	 * @return bool
	 */
	function GetFullFilePath($tempName)
	{
		return $this->_fs->GetFolderFullPath($this->_folder).'/'.$tempName;
	}

	/**
	 * @var string $sTempName
	 * @return string
	 */
	function GetNextTempName($sTempName)
	{
		$iIdx = '';
		while ($this->IsFileExist($iIdx.$sTempName))
		{
			$iIdx = ($iIdx === '') ? 1 : ((int) $iIdx) + 1;
		}

		return $iIdx.$sTempName;
	}
	
	/**
	 * @var string $tempName
	 * @return int | false
	 */
	function FileSize($tempName)
	{
		return @filesize($this->_fs->GetFolderFullPath($this->_folder).'/'.$tempName);
	}
	
	function MoveUploadedFile($serverTempFile, $fileTempName)
	{
		$this->_fs->CreateFolder($this->_folder);
		return @move_uploaded_file($serverTempFile, $this->_fs->GetFolderFullPath($this->_folder).'/'.$fileTempName);
	}
	
	function ClearAccountCompletely()
	{
		$this->_fs->DeleteAccountDirs();
		return true;
	}
	
	function ClearAccount()
	{
		$this->_fs->DeleteDir($this->_folder);
		return true;
	}

	function ConvertTempFile($sInFileName, $sOutFileName)
	{
		$sFolder = $this->_fs->GetFolderFullPath($this->_folder);
		$aOutput = array();
		$sCmd = CAPi::DataPath().'/convert/amr2wav_convert.sh "'.$sFolder.'/'.$sInFileName.'" "'.$sFolder.'/'.$sOutFileName.'"';
		CApi::Log('Convert => '.$sCmd);
		@exec($sCmd, $aOutput);
		return true;
	}
}
