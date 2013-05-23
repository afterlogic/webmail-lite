<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
	include_once WM_ROOTPATH.'application/include.php';
	$oInput = new api_Http();

	require_once(WM_ROOTPATH.'common/inc_constants.php');
	require_once(WM_ROOTPATH.'common/message_preview.php');

	@header('Content-Type: text/html; charset=utf-8');

	$mViewType = $mViewType = $oInput->GetQuery('type', false);
	if (false === $mViewType)
	{
		exit();
	}

	$oAccount = AppGetAccount(CSession::Get(APP_SESSION_ACCOUNT_ID, false));
	if (!$oAccount)
	{
		if ($mViewType == MESSAGE_VIEW_TYPE_FULL)
		{
			exit();
		}
		else
		{
			exit('<script>parent.changeLocation("'.LOGINFILE.'?error=2");</script>');
		}
	}

	AppIncludeLanguage($oAccount->User->DefaultLanguage);

	require_once WM_ROOTPATH.'common/class_getmessagebase.php';

	$message = false;
	$isNull = true;

	$_rtl = in_array($oAccount->User->DefaultLanguage, CApi::GetConf('webmail.rtl-langs', array()));
	
	$fromString = $toString = $ccString = $dateString =
		$subjectString = $attachString = $fullBodyText = '';

	$mes_id = CGet::Get('msg_id', '');
	$mes_uid = CGet::Get('msg_uid', '');
	$folder_id = CGet::Get('folder_id', '');
	$folder_name = CGet::Get('folder_fname', '');
	$mes_charset = CGet::Get('charset', -1);
	$bodytype = (int) CGet::Get('bodytype', 1);
	$tempNameFromGet = CGet::Get('tn', '');

	switch ($mViewType)
	{
		case MESSAGE_VIEW_TYPE_PRINT:

			$GLOBALS['PRINTFILE'] = true;
			if ($mes_uid || $mes_id)
			{
				$message = new GetMessageBase($oAccount, $mes_id, $mes_uid, $folder_id, $folder_name, $mes_charset);
				if ($message && $message->msg)
				{
					$isNull = false;
				}
			}

			if ($isNull)
			{
				exit(PROC_MSG_HAS_DELETED);
			}

			$fromString = $message->PrintFrom(true);
			$toString = $message->PrintTo(true);
			$ccString = $message->PrintCc(true);
			$dateString = $message->PrintDate();
			$subjectString = $message->PrintSubject(true);
			
			$attachString = '';
			if ($message->msg->Attachments && $message->msg->Attachments->Count() > 0)
			{
				$AttachNames = array();
				foreach (array_keys($message->msg->Attachments->Instance()) as $key)
				{
					$attachment =& $message->msg->Attachments->Get($key);
					$fileName = ConvertUtils::ClearFileName(ConvertUtils::ClearUtf8($attachment->GetFilenameFromMime(), $GLOBALS[MailInputCharset], CPAGE_UTF8));
					$AttachNames[] = $fileName;
					unset($attachment);
				}

				$attachString = implode(', ', $AttachNames);
			}
			$attachString = trim($attachString, ', ');

			$textCharset = $message->msg->GetTextCharset();
			$fullBodyText = ($message->msg->HasHtmlText())
				? ConvertUtils::ReplaceJSMethod($message->PrintHtmlBody(true))
				: $message->PrintPlainBody();


			break;

		case MESSAGE_VIEW_TYPE_FULL:

			$GLOBALS[MIMEConst_DoNotUseMTrim] = true;
			if ($mes_uid || $mes_id)
			{
				$message = new GetMessageBase($oAccount, $mes_id, $mes_uid, $folder_id, $folder_name, $mes_charset);
				if ($message && $message->msg)
				{
					$isNull = false;
				}
			}

			if ($isNull)
			{
				exit(PROC_MSG_HAS_DELETED);
			}

			$fromString = $message->PrintFrom(true);
			$toString = $message->PrintTo(true);
			$ccString = $message->PrintCc(true);
			$dateString = $message->PrintDate();
			$subjectString = $message->PrintSubject(true);
			$attachString = null;

			$textCharset = $message->msg->GetTextCharset();
			$fullBodyText = ($bodytype === 1)
				? ConvertUtils::ReplaceJSMethod($message->PrintHtmlBody(true))
				: $message->PrintPlainBody();

			break;
		
		case MESSAGE_VIEW_TYPE_ATTACH;

			if ($tempNameFromGet)
			{
				$tempFiles =& CTempFiles::CreateInstance($oAccount);
				
				$GLOBALS[MailDefaultCharset] = $oAccount->User->DefaultIncomingCharset;
				$GLOBALS[MailOutputCharset] = CPAGE_UTF8;

				$message = $messageBody = null;
				if ($tempFiles->IsFileExist($tempNameFromGet))
				{
					$messageBody = $tempFiles->LoadFile($tempNameFromGet);
				}
				else if (isset($_GET['bsi'])) // bodystructure_index
				{
					$processor = new MailProcessor($oAccount);
					$folder = new Folder($oAccount->IdAccount, $folder_id, $folder_name);
					
					$messageBody = $processor->GetBodyPartByIndex($_GET['bsi'], $mes_uid, $folder);
					if (isset($_GET['bse']) && strlen($messageBody) > 0)
					{
						$encode = ConvertUtils::GetBodyStructureEncodeString($_GET['bse']);
						$messageBody = ConvertUtils::DecodeBodyByType($messageBody, $encode);
					}
					
					$tempFiles->SaveFile($tempNameFromGet, $messageBody);
				}
				
				if ($messageBody)
				{
					$message = new WebMailMessage();
					$message->LoadMessageFromRawBody($messageBody, true);
				}
				
				if ($message)
				{
					$isNull = false;
				}
			}

			if ($isNull)
			{
				exit(PROC_MSG_HAS_DELETED);
			}

			$fromString = $message->GetFromAsString(true);
			$toString = $message->GetToAsString(true);
			$ccString = $message->GetCcAsString(true);
			$subjectString = $message->GetSubject(true);

			$date = $message->GetDate();
			$date->FormatString = $oAccount->User->DefaultDateFormat;
			$date->TimeFormat = $oAccount->User->DefaultTimeFormat;
			$dateString = $date->GetFormattedDate($oAccount->GetDefaultTimeOffset());
	
			$attachString = '';
			if ($message->Attachments && $message->Attachments->Count() > 0)
			{
				$attachmentsKeys = array_keys($message->Attachments->Instance());
				foreach ($attachmentsKeys as $key)
				{
					$attachment =& $message->Attachments->Get($key);
					$tempName = $key.'_'.ConvertUtils::ClearFileName($attachment->GetTempName());
					$fileName = ConvertUtils::ClearFileName(ConvertUtils::ClearUtf8($attachment->GetFilenameFromMime(), $GLOBALS[MailInputCharset], CPAGE_UTF8));

					$view = $download = null;
					$size = $tempFiles->SaveFile($tempName, $attachment->GetBinaryBody());
					if ($size > -1)
					{
						$download = 'attach.php?tn='.urlencode($tempName).'&filename='.urlencode($fileName);

						$lowerAttachFileName = strtolower($fileName);
						$contentType = ConvertUtils::GetContentTypeFromFileName($fileName);
						if (substr($lowerAttachFileName, -4) == '.eml')
						{
							$view = 'message-view.php?type='.MESSAGE_VIEW_TYPE_ATTACH.'&tn='.urlencode($tempName);
						}
						else if (false !== strpos($contentType, 'image'))
						{
							$view = 'view-image.php?img&tn='.urlencode($tempName).'&filename='.urlencode($fileName);
						}
					}

					$attachString .= ' <a href="'.ConvertUtils::AttributeQuote($download).'">'.$fileName.'</a>';
					$attachString .= (null !== $view) ? ' (<a href="'.ConvertUtils::AttributeQuote($view).'">'.JS_LANG_View.'</a>)' : '';
					$attachString .= ',';

					unset($attachment);
				}
			}

			$attachString = trim($attachString, ', ');

			$textCharset = $message->GetTextCharset();
			$fullBodyText = ($message->HasHtmlText())
				? ConvertUtils::ReplaceJSMethod(PrintHtmlBodyForViewMsgScreen($message, $oAccount, true))
				: nl2br($message->GetCensoredTextBody(true));
				
			break;
	}

	PrintMessagePreview($oAccount->User->DefaultSkin, $_rtl, $fullBodyText, $textCharset,
		$fromString, $toString, $dateString, $subjectString,
		$attachString, $ccString, $mViewType == MESSAGE_VIEW_TYPE_PRINT);
