<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	if (!defined('WM_ROOTPATH')) define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/inc_constants.php');
	require_once(WM_ROOTPATH.'common/class_webmailmessages.php');
	require_once(WM_ROOTPATH.'common/class_mailprocessor.php');
	require_once(WM_ROOTPATH.'common/class_filters.php');
	require_once(WM_ROOTPATH.'common/class_filesystem.php');
	require_once(WM_ROOTPATH.'common/class_convertutils.php');
	require_once(WM_ROOTPATH.'common/class_smtp.php');
	require_once(WM_ROOTPATH.'common/class_tempfiles.php');

class GetMessageBase
{
	/**
	 * @var int
	 */
	var $accountId;

	/**
	 * @var int
	 */
	var $messId;

	/**
	 * @var string
	 */
	var $messUid;

	/**
	 * @var int
	 */
	var $folderId;

	/**
	 * @var string
	 */
	var $folderFullName;

	/**
	 * @var CAccount
	 */
	var $account;

	/**
	 * @var MailProcessor
	 */
	var $processor;

	/**
	 * @var Folder
	 */
	var $folder;

	/**
	 * @var WebMailMessage
	 */
	var $msg;

	/**
	 * @var string
	 */
	var $charset;

	var $prevData = null;
	var $nextData = null;

	/**
	 * @var bool
	 */
	var $isSafety = true;

	/**
	 * @param CAccount $account;
	 * @param int $messId
	 * @param string $messUid
	 * @param int $folderId
	 * @param string $folderFullName
	 * @return GetMessageBase
	 */
	function GetMessageBase($account, $messId, $messUid, $folderId, $folderFullName, $charset)
	{
		$this->messId = $messId;
		$this->messUid = $messUid;
		$this->folderId = $folderId;
		$this->folderFullName = $folderFullName;
		$this->charset = $charset;

		$this->accountId = $account->IdAccount;

		$this->account = $account;

		$this->processor = new MailProcessor($this->account);

		$this->folder = new Folder($this->accountId, $folderId, $folderFullName);

		$this->processor->GetFolderInfo($this->folder);

		$msgIdUid = array();
		if (!empty($messId) && !empty($messUid))
		{
			$msgIdUid[$messId] = $messUid;
		}

		$charsetNum = -1;
		if ($charset != -1)
		{
			$GLOBALS[MailInputCharset] = $charset;
			$charsetNum = ConvertUtils::GetCodePageNumber($charset);
		}

		$this->msg =& $this->processor->GetMessage($messId, $messUid, $this->folder);
		if ($this->msg)
		{
			if (!(($this->msg->Flags & MESSAGEFLAGS_Seen) == MESSAGEFLAGS_Seen))
			{
				$this->processor->SetFlag($msgIdUid, $this->folder, MESSAGEFLAGS_Seen, ACTION_Set);
			}

			if ($this->folder->SyncType != FOLDERSYNC_DirectMode && $this->processor->DbStorage->Connect())
			{
				$this->processor->DbStorage->UpdateMessageCharset($messId, $charsetNum, $this->msg);
			}
		}
	}

	/**
	 * @return int
	 */
	function GetTypeOfMessage()
	{
		return $this->msg->TextBodies->ClassType();
	}

	/**
	 * @return bool
	 */
	function HasAttachments()
	{
		return ($this->msg->Attachments != null && $this->msg->Attachments->Count() > 0);
	}

	/**
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintFrom($isEncode = true)
	{
		return $this->msg->GetFromAsString($isEncode);
	}

	/**
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintFriendlyFrom($isEncode = true)
	{
		return $this->msg->GetFromAsStringForSend($isEncode);
	}

	/**
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintTo($isEncode = true)
	{
		return $this->msg->GetToAsString($isEncode);
	}

	/**
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintCc($isEncode = true)
	{
		return $this->msg->GetCcAsString($isEncode);
	}

	/**
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintBcc($isEncode = true)
	{
		return $this->msg->GetBccAsString($isEncode);
	}

	/**
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintReplyTo($isEncode = true)
	{
		return $this->msg->GetReplyToAsString($isEncode);
	}

	/**
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintSubject($isEncode = true)
	{
		return $this->msg->GetSubject($isEncode);
	}

	/**
	 * @return string
	 */
	function PrintDate()
	{
		$date = $this->msg->GetDate();
		$date->FormatString = $this->account->User->DefaultDateFormat;
		$date->TimeFormat = $this->account->User->DefaultTimeFormat;

		return $date->GetFormattedFullDate($this->account->GetDefaultTimeOffset());
	}

	/**
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintPlainBody($isEncode = true)
	{
		return $this->msg->GetCensoredTextBody($isEncode);
	}

	/**
	 * @param bool $isEncode
	 * @return string
	 */
	function PrintHtmlBody($isEncode = true, $isFromSave = false)
	{
		if (!$isFromSave)
		{
			$newtext = ConvertUtils::HtmlBodyWithoutImages($this->msg->GetCensoredHtmlWithImageLinks($isEncode));
			if (isset($GLOBALS[GL_WITHIMG]) && $GLOBALS[GL_WITHIMG])
			{
				$GLOBALS[GL_WITHIMG] = false;
				$this->isSafety = false;
			}
		}
		else
		{
			$newtext = $this->msg->GetCensoredHtmlWithImageLinks($isEncode);
		}

		return $newtext;
	}

}
