<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	set_time_limit(60);
	ignore_user_abort(true);

	/**
	 * @return void
	 */
	function PrefetchFlush()
	{
//		echo str_repeat('             ', 256);
		@flush();
	}

	@header('Content-type: text/html; charset=utf-8');

	PrefetchFlush();

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><link rel="shortcut icon" href="favicon.ico" /></head><body>';

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', dirname(__FILE__).'/');
	include_once WM_ROOTPATH.'application/include.php';
	$oInput = new api_Http();

	$iAccountId = CSession::Get(APP_SESSION_ACCOUNT_ID, false);

	require_once WM_ROOTPATH.'common/inc_constants.php';
	require_once WM_ROOTPATH.'common/class_convertutils.php';
	require_once WM_ROOTPATH.'common/class_mailprocessor.php';

	include_once WM_ROOTPATH.'application/xml-builder.php';
	include_once WM_ROOTPATH.'application/xml-helper.php';


	/* @var $oAccount CAccount */
	$oAccount = AppGetAccount($iAccountId);
	if (!$oAccount)
	{
		exit();
	}

	/* exit(); */

	AppIncludeLanguage($oAccount->User->DefaultLanguage);

	$oMailProcessor = new MailProcessor($oAccount);
	$sStartIncCharset = $oAccount->User->DefaultIncomingCharset;

	$aFolders = null;
	$sData = $oInput->GetPost('data', '');
	if (!empty($sData))
	{
		$aFolders = json_decode($sData, true);
	}

	$iPreloadBodySize = CApi::GetConf('webmail.preload-body-size', 76800);
	$sErrorDesc = '';
	$aFolderArray = array();
	foreach ($aFolders as $iFolderId => $aItem)
	{
		if ($iFolderId < 1)
		{
			$sErrorDesc = WebMailException;
			break;
		}

		if (!is_array($aItem) || empty($aItem[0]) && !isset($aItem[1]) || !is_array($aItem[1]) || 0 === count($aItem[1]))
		{
			break;
		}

		$oFolder = null;
		if (isset($aFolderArray[$iFolderId]))
		{
			$oFolder =& $aFolderArray[$iFolderId];
		}
		else
		{
			$oFolder = new Folder($oAccount->IdAccount, $iFolderId, $aItem[0]);
			$oMailProcessor->GetFolderInfo($oFolder);
			$aFolderArray[$iFolderId] =& $oFolder;
		}

		if (!$oFolder || (EMailProtocol::POP3 === $oAccount->IncomingMailProtocol && (
				$oFolder->SyncType == FOLDERSYNC_AllHeadersOnly || $oFolder->SyncType == FOLDERSYNC_NewHeadersOnly)))
		{
			continue;
		}

		if (is_array($aItem[1]) && count($aItem[1]) > 0)
		{
			CSession::Stop();

			foreach ($aItem[1] as $aValues)
			{
				if (is_array($aValues) && 4 < count($aValues))
				{
					$iCharsetNum = $aValues[2];
					if ($iCharsetNum > 0)
					{
						$sCharsetName = ConvertUtils::GetCodePageName($iCharsetNum);
						if (empty($sCharsetName))
						{
							$sCharsetName = CApi::GetConf('webmail.default-inc-charset', 'iso-8859-1');
						}

						$oAccount->User->DefaultIncomingCharset = $sCharsetName;
						$oMailProcessor->_account->User->DefaultIncomingCharset = $oAccount->User->DefaultIncomingCharset;
						$GLOBALS[MailInputCharset] = $oAccount->User->DefaultIncomingCharset;
					}
					else
					{
						$oAccount->User->DefaultIncomingCharset = $sStartIncCharset;
						$oMailProcessor->_account->User->DefaultIncomingCharset = $oAccount->User->DefaultIncomingCharset;
						if (isset($GLOBALS[MailInputCharset]))
						{
							unset($GLOBALS[MailInputCharset]);
						}
					}

					$iMsgSize = $aValues[3];
					$iModeForGet = false;
					if ((int) $iMsgSize > 0 && (int) $iMsgSize < $iPreloadBodySize)
					{
						$iModeForGet = null;
						if (EMailProtocol::IMAP4 === $oAccount->IncomingMailProtocol && $oFolder->Type != FOLDERTYPE_Drafts)
						{
							$iModeForGet = 263;
						}
					}
					else if (EMailProtocol::IMAP4 === $oAccount->IncomingMailProtocol)
					{
						$iModeForGet = false;
						if ($oFolder->Type != FOLDERTYPE_Drafts)
						{
							$iModeForGet = 263;
						}
					}

					$bVoice = $aValues[4];
					if ($bVoice)
					{
						$iModeForGet = null;
					}

					echo "\r\n";
					PrefetchFlush();

					if (connection_status() !== CONNECTION_NORMAL)
					{
						break;
					}

					$oMessage = null;
					if (false !== $iModeForGet)
					{
						$oMessage =& $oMailProcessor->GetMessage($aValues[0], $aValues[1], $oFolder, $iModeForGet, (EMailProtocol::POP3 === $oAccount->IncomingMailProtocol));
					}

					if (null != $oMessage && ($oMessage->Size < $iPreloadBodySize || EMailProtocol::IMAP4 === $oAccount->IncomingMailProtocol))
					{
						$oFromObj = new EmailAddress();
						$oFromObj->Parse($oMessage->GetFromAsString(true));

						$bShowImages = CApi::GetSettings()->GetConf('WebMail/AlwaysShowImagesInMessage');
						if (USE_DB && 0 < strlen($oFromObj->Email) && false === $bShowImages)
						{
							$bShowImages = $oMailProcessor->DbStorage->SelectSenderSafetyByEmail(
								$oFromObj->Email, $oAccount->IdUser);
						}

						$iModeForGet = ($oFolder->Type == FOLDERTYPE_Drafts) ? 391 + 512 : 391;

						$oResultXml = new CXmlDocument();
						$oResultXml->CreateElement('webmail');

// TODO
//						CApi::Log('PREFETCH: build message: '.$oFolder->FullName.' / '.$oMessage->Uid);

						CAppXmlBuilder::BuildMessage($oResultXml, $oAccount, $oMailProcessor,
							$oMessage, $oFolder, $iModeForGet, $iCharsetNum, $bShowImages);

						echo '<script type="text/javascript">parent && parent.prefetchData && parent.prefetchData("'.
							ConvertUtils::ReBuildStringToJavaScript($oResultXml->ToString(), '"').'");</script>';

						unset($oResultXml);

						PrefetchFlush();
					}

					unset($oMessage);
				}
			}
		}
		unset($oFolder);
	}

	echo '</body></html>';
	PrefetchFlush();