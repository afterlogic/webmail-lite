<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
	include_once WM_ROOTPATH.'application/include.php';
	
	$oInput = new api_Http();

	require_once WM_ROOTPATH.'common/inc_constants.php';
	require_once WM_ROOTPATH.'common/class_convertutils.php';
	require_once WM_ROOTPATH.'common/class_mailprocessor.php';
	require_once WM_ROOTPATH.'common/class_folders.php';
	require_once WM_ROOTPATH.'common/class_webmailmessages.php';
	require_once WM_ROOTPATH.'common/class_tempfiles.php';
	
	$oAccount = AppGetAccount(CSession::Get(APP_SESSION_ACCOUNT_ID, false));
	/* @var $oAccount CAccount */
	if (!$oAccount)
	{
		exit();
	}
	
	$data = '';
	if (isset($_GET['msg_id'], $_GET['msg_uid'], $_GET['folder_id'], $_GET['folder_fname']))
	{
		$oFolder = new Folder($oAccount->IdAccount, $_GET['folder_id'], $_GET['folder_fname']);
		
		$dbStorage =& DbStorageCreator::CreateDatabaseStorage($oAccount);
		if (USE_DB && $dbStorage->Connect())
		{
			$dbStorage->GetFolderInfo($oFolder);
		}
		else if (!USE_DB)
		{
			$oFolder->SyncType = FOLDERSYNC_DirectMode;
		}
		
		$oMailProcessor = new MailProcessor($oAccount);

		CSession::Stop();
		
		if (isset($_GET['bsi'], $_GET['tn']))
		{
			if ($oInput->ServerNotModifiedCache(31536000))
			{
				exit();
			}
	
			$tempName = ConvertUtils::ClearFileName($_GET['tn']);
			$tempFiles =& CTempFiles::CreateInstance($oAccount);

			if (isset($_GET['play']) && $tempFiles->IsFileExist(ConvertFileName($tempName)))
			{
				$data = $tempFiles->LoadFile(ConvertFileName($tempName));
			}
			else if (!isset($_GET['play']) && $tempFiles->IsFileExist($tempName))
			{
				$data = $tempFiles->LoadFile($tempName);
			}
			else
			{
				$data = $oMailProcessor->GetBodyPartByIndex($_GET['bsi'], $_GET['msg_uid'], $oFolder);
				$encode = 'none';
				if (isset($_GET['bse']) && strlen($data) > 0)
				{
					$encode = ConvertUtils::GetBodyStructureEncodeString($_GET['bse']);
					$data = ConvertUtils::DecodeBodyByType($data, $encode);
				}
				
				$tempFiles->SaveFile($tempName, $data);

				if (isset($_GET['play']))
				{
					$tempFiles->ConvertTempFile($tempName, ConvertFileName($tempName));
					$data = $tempFiles->LoadFile(ConvertFileName($tempName));
				}
			}
			
			AddAttachmentHeaders(CPAGE_UTF8, $tempName);
		}
		else
		{
			$message =& $oMailProcessor->GetMessage($_GET['msg_id'], $_GET['msg_uid'], $oFolder);
			if (!$message)
			{
				exit();
			}

			$data = $message->TryToGetOriginalMailMessage();
			$fileNameToSave = trim(ConvertUtils::ClearFileName($message->GetSubject()));
			if (empty($fileNameToSave))
			{
				$fileNameToSave = 'message';
			}

			if (ConvertUtils::IsIE())
			{
				$fileNameToSave = rawurlencode($fileNameToSave);
			}
			
			AddFileNameHeaders(CPAGE_UTF8, $fileNameToSave.'.eml');
		}
	}
	else if (CSession::Has(APP_SESSION_ACCOUNT_ID) && isset($_GET['tn']))
	{
		if ($oInput->ServerNotModifiedCache(31536000))
		{
			exit();
		}
		
		$tempName = ConvertUtils::ClearFileName($_GET['tn']);
		$tempFiles =& CTempFiles::CreateInstance($oAccount);

		if (isset($_GET['play']))
		{
			$sConvertedFileName = ConvertFileName($tempName);
			if ($tempFiles->IsFileExist($sConvertedFileName))
			{
				$data = $tempFiles->LoadFile($sConvertedFileName);
			}
			else if ($tempFiles->IsFileExist($tempName))
			{
				if ($tempName !== $sConvertedFileName)
				{
					$tempFiles->ConvertTempFile($tempName, $sConvertedFileName);
				}
				
				$data = $tempFiles->LoadFile($sConvertedFileName);
			}
		}
		else
		{
			$data = $tempFiles->LoadFile($tempName);
		}
		
		AddAttachmentHeaders(CPAGE_UTF8, $tempName);
	}
	else
	{
		exit();
	}
	
	echo $data;
	
	function AddAttachmentHeaders($userCharset, $tempName)
	{
		if (isset($_GET['filename']))
		{
			$filename = trim(ConvertUtils::ClearFileName(urldecode($_GET['filename'])));
			$filename = (strlen($filename)) > 0 ? $filename : 'attachmentname';
			if (ConvertUtils::IsIE())
			{
				$filename = rawurlencode($filename);
			}

			if (isset($_GET['play']))
			{
				header('Content-Disposition: attachment; filename="message.wav"');
				header('Content-Type: audio/x-wav');
			}
			else if (isset($_GET['img']))
			{
				header('Content-Disposition: inline; filename="'.$filename.'"; charset='.$userCharset);
				header('Content-Type: '.ConvertUtils::GetContentTypeFromFileName($tempName));
			}
			else
			{
				AddFileNameHeaders($userCharset, $filename);
			}
		}
		else
		{
			header('Content-Disposition: inline; filename="'.$tempName.'"; charset='.$userCharset);
			header('Content-Type: '.ConvertUtils::GetContentTypeFromFileName($tempName));
		}
	}

	function AddFileNameHeaders($userCharset, $filename)
	{
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		header('Content-Type: application/octet-stream');
		header('Content-Type: application/octet-stream');
		header('Content-Type: application/download');

		header('Accept-Ranges: bytes');
		header('Content-Disposition: attachment; filename="'.$filename.'"; charset='.$userCharset);
		header('Content-Transfer-Encoding: binary');
	}

	/**
	 * @param string $sFileName
	 * @return string
	 */
	function ConvertFileName($sFileName)
	{
		return ('.wav' === strtolower(substr($sFileName, -4))) ? $sFileName : $sFileName.'.wav';
	}