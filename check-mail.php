<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	@header('Content-type: text/html; charset=utf-8');

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
	include_once WM_ROOTPATH.'application/include.php';

	$oInput = new api_Http();
	$iAccountId = CSession::Get(APP_SESSION_ACCOUNT_ID, false);

	require_once WM_ROOTPATH.'common/inc_constants.php';
	require_once WM_ROOTPATH.'common/class_convertutils.php';
	require_once WM_ROOTPATH.'common/class_mailprocessor.php';

	/* @var $oApiUsersManager CApiUsersManager */
	$oApiUsersManager = CApi::Manager('users');

	/* @var $oAccount CAccount */
	$oAccount = AppGetAccount($iAccountId);
	if (!$oAccount)
	{
		CApi::Log('check-mail: $oAccount = null');

		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><meta http-equiv="Content-Script-Type" content="text/javascript" /><link rel="shortcut icon" href="favicon.ico" /></head>
<body onload="parent.CheckEndCheckMailHandler();"><script>parent.EndCheckMailHandler("session_error");</script></body></html>';

		exit();
	}

	AppIncludeLanguage($oAccount->User->DefaultLanguage);

	@ob_start();
	@ob_end_flush();

	$sErrorDesc = '';
	$aGlobalForders4Update = array();

	/**
	 * @global $aGlobalForders4Update
	 * @param int $id
	 * @param string $fullName
	 */
	function AddFolder4Update($id, $fullName)
	{
		global $aGlobalForders4Update;
		$aGlobalForders4Update[$id] = $fullName;
	}

	/**
	 * @global $aGlobalForders4Update
	 * @return string
	 */
	function Folders4UpdateToJsArray()
	{
		global $aGlobalForders4Update;

		$sResult = array();
		if ($aGlobalForders4Update && count($aGlobalForders4Update) > 0)
		{
			foreach ($aGlobalForders4Update as $id => $name)
			{
				$sResult[] = '{id: '.((int) $id).', fullName: \''.ConvertUtils::ClearJavaScriptString($name, '\'').'\'}';
			}
		}

		return '['.implode(',', $sResult).']';
	}

	/**
	 * @param bool $bAdd = false
	 * @return void
	 */
	function myFlush($bAdd = false)
	{
		if ($bAdd)
		{
			echo str_repeat('             ', 256);
		}

		@ob_flush();
		@flush();
	}

	/**
	 * @param string $sFolderName
	 * @param int $iMessageCount
	 */
	function ShowDownloadedMessageNumber($sFolderName = '', $iMessageCount = -1)
	{
		static $msgNumber = 0;
		static $msgTime = 0;

		if ($sFolderName != '' && $iMessageCount != -1)
		{
			$msgNumber = 0;
			$msgTime = 0;
			echo '<script>parent.SetCheckingFolderHandler("'.$sFolderName.'", '.$iMessageCount.');</script>'.CRLF;
			if ($iMessageCount == 0)
			{
				echo '<script>parent.SetStateTextHandler(parent.Lang.GettingMsgsNum);</script>'.CRLF;
			}
			myFlush(true);
		}
		else
		{
			$msgNumber++;
			if (time() - $msgTime > 0)
			{
				echo '<script>parent.SetRetrievingMessageHandler('.$msgNumber.');</script>'.CRLF;
				$msgTime = time();
				myFlush(true);
			}
		}
	}

	function ShowLoggingToServer()
	{
		echo '<script>parent.SetStateTextHandler("'.ConvertUtils::ClearJavaScriptString(JS_LANG_LoggingToServer, '"').'");</script>'.CRLF;
		myFlush(true);
	}

	function ShowLoggingOffFromServer()
	{
		echo '<script>parent.SetStateTextHandler("'.ConvertUtils::ClearJavaScriptString(LoggingOffFromServer, '"').'");</script>'.CRLF;
		myFlush(true);
	}

	function ShowDeletingMessageNumber($resetCount = false)
	{
		static $msgNumber = 0;
		static $msgTime = 0;

		if ($resetCount)
		{
			$msgNumber = 0;
			$msgTime = 0;
		}
		else
		{
			$msgNumber++;
			if (time() - $msgTime > 0)
			{
				echo '<script>parent.SetDeletingMessageHandler('.$msgNumber.');</script>'.CRLF;
				$msgTime = time();
				myFlush(true);
			}
		}
	}

	/**
	 * @param string $sText
	 */
	function SetError($sText)
	{
		CSession::Set(INFORMATION, $sText);
		CSession::Set(ISINFOERROR, true);
	}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<body onload="parent.CheckEndCheckMailHandler();">
<?php

	ConvertUtils::SetLimits();

	$GLOBALS['useFilters'] = true;
	$type = (int) $oInput->GetPost('Type', 0);

	CApi::Log('CM: Check mail type = '.$type);

	if (1 === $type)
	{
		$aAccounts = $oApiUsersManager->GetUserIdList($oAccount->IdUser);
		if (is_array($aAccounts) && 0 < count($aAccounts))
		{
			foreach ($aAccounts as $iAccountItemId)
			{
				/* @var $oListAccount CAccount */
				$oListAccount = ($oAccount->IdAccount !== $iAccountItemId)
					? AppGetAccount($iAccountItemId) : $oAccount;

				$oProcessor = new MailProcessor($oListAccount);
				if (!$oProcessor->SynchronizeFolders())
				{
					$sErrorDesc = getGlobalError();
					$oProcessor->MailStorage->Disconnect();
					break;
				}

				if ($oListAccount->GetMailAtLogin)
				{
					echo '<script>parent.SetCheckingAccountHandler("'.$oListAccount->Email.'");</script>'.CRLF;

					myFlush(true);

					ShowLoggingToServer();

					$oFolders = null;
					$oProcessor->MailStorage->DownloadedMessagesHandler = 'ShowDownloadedMessageNumber';

					$oFolders =& $oProcessor->GetFolders();
					if (!$oProcessor->Synchronize($oFolders))
					{
						$sErrorDesc = getGlobalError();
						$oProcessor->MailStorage->Disconnect();
						break;
					}

					ShowLoggingOffFromServer();

					$oProcessor->MailStorage->Disconnect();

					unset($oFolders, $oProcessor);
				}
				
				unset($oListAccount);
			}
		}

		$sErrorDesc = trim($sErrorDesc);
		if (strlen($sErrorDesc) > 0)
		{
			SetError($sErrorDesc);
		}

		echo '<script>parent.EndCheckMailHandler(\'\');</script>'.CRLF;
	}
	else if (2 === $type)
	{
		$oProcessor = new MailProcessor($oAccount);

		$oFolders =& $oProcessor->GetFolders();

		$oProcessor->MailStorage->DownloadedMessagesHandler = null;
		$oProcessor->MailStorage->UpdateFolderHandler = 'AddFolder4Update';

		$oInboxFolder = $oFolders->GetFolderByType(FOLDERTYPE_Inbox);

		if ($oInboxFolder)
		{
			$oInboxFolder->SubFolders = null;
			$foldersForInboxSynchronize = new FolderCollection();
			$foldersForInboxSynchronize->Add($oInboxFolder);

			if (!$oProcessor->Synchronize($foldersForInboxSynchronize))
			{
				$sErrorDesc = getGlobalError();
			}

			$oProcessor->MailStorage->Disconnect();
		}
		else
		{
			$sErrorDesc = '';
		}

		$sErrorDesc = trim($sErrorDesc);
		echo '<script>
parent.SetUpdatedFolders('.Folders4UpdateToJsArray().', false);
parent.EndCheckMailHandler("'.ConvertUtils::ClearJavaScriptString($sErrorDesc, '"').'");
</script>'.CRLF;
	}
	else
	{
		ShowLoggingToServer();

		$oProcessor = new MailProcessor($oAccount);

		$oFolders =& $oProcessor->GetFolders();

		$oProcessor->MailStorage->DownloadedMessagesHandler = 'ShowDownloadedMessageNumber';
		$oProcessor->MailStorage->UpdateFolderHandler = 'AddFolder4Update';

		if (!$oProcessor->Synchronize($oFolders))
		{
			$sErrorDesc = getGlobalError();
		}

		ShowLoggingOffFromServer();

		$oProcessor->MailStorage->Disconnect();

		$sErrorDesc = trim($sErrorDesc);
		echo '<script>
parent.SetUpdatedFolders('.Folders4UpdateToJsArray().');
parent.EndCheckMailHandler("'.ConvertUtils::ClearJavaScriptString($sErrorDesc, '"').'");
</script>'.CRLF;
	}

	myFlush(true);
?>
</body>
</html>
