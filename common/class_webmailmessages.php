<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	function GetStrReplacement($_str, &$_rep)
	{
		$_rep[] = stripslashes($_str);
		return '##string_replacement{'.(count($_rep) - 1).'}##';
	}

	require_once(WM_ROOTPATH.'common/mime/class_mailmessage.php');
	require_once(WM_ROOTPATH.'common/class_collectionbase.php');

	define('MESSAGEFLAGS_None', 0);
	define('MESSAGEFLAGS_Seen', 1);
	define('MESSAGEFLAGS_Answered', 2);
	define('MESSAGEFLAGS_Flagged', 4);
	define('MESSAGEFLAGS_Deleted', 8);
	define('MESSAGEFLAGS_Draft', 16);
	define('MESSAGEFLAGS_Recent', 32);

	define('MESSAGEFLAGS_Forwarded', 256);
	define('MESSAGEFLAGS_Grayed', 512);

	define('MESSAGEPRIORITY_High', 1);
	define('MESSAGEPRIORITY_Normal', 3);
	define('MESSAGEPRIORITY_Low', 5);

	class WebMailMessage extends MailMessage
	{
		/**
		 * @var int
		 */
		var $IdMsg = -1;

		/**
		 * @var int
		 */
		var $IdFolder = -1;

		/**
		 * @var string
		 */
		var $Uid = -1;

		/**
		 * @var int
		 */
		var $Size;

		/**
		 * @var int
		 */
		var $Flags = MESSAGEFLAGS_None;

		/**
		 * @var bool
		 */
		var $DbHasAttachments = null;

		/**
		 * @var short
		 */
		var $DbPriority = 0;

		/**
		 * @var bool
		 */
		var $DbXSpam = null;

		/**
		 * @var int
		 */
		var $Charset = -1;

		/**
		 * @var bool
		 */
		var $Downloaded = false;

		/**
		 * @var int
		 */
		var $Sensitivity = null;

		var $aPrepearPlainStringUrls = array();

		/**
		 * @return short
		 */
		function GetPriorityStatus()
		{
			if ($this->DbPriority > 0)
			{
				return $this->DbPriority;
			}

			$priority = MailMessage::GetPriority();

			switch (strtolower($priority))
			{
				case 'high':
				case '1 (highest)':
				case '2 (high)':
				case '1':
				case '2':
					return MESSAGEPRIORITY_High;

				case 'low':
				case '4 (low)':
				case '5 (lowest)':
				case '4':
				case '5':
					return MESSAGEPRIORITY_Low;
			}

			return MESSAGEPRIORITY_Normal;
		}

		/**
		 * @return bool
		 */
		function GetXSpamStatus()
		{
			if ($this->DbXSpam !== null)
			{
				return $this->DbXSpam;
			}

			$xSpamValue = $this->Headers->GetHeaderValueByName(MIMEConst_XSpamLower);

			return (strtolower($xSpamValue) == 'probable spam' || strtolower($xSpamValue) == 'suspicious');
		}

		/**
		 * @return string
		 */
		function GetSpamHeader()
		{
			return $this->Headers->GetHeaderValueByName(MIMEConst_XSpamHeaderLower);
		}

		/**
		 * @return string
		 */
		function GetVirusHeader()
		{
			return $this->Headers->GetHeaderValueByName(MIMEConst_XVirusHeaderLower);
		}

		/**
		 * @return bool
		 */
		function HasAttachments($bWatchInline = true)
		{
			if ($this->DbHasAttachments !== null)
			{
				return $this->DbHasAttachments;
			}

			return MailMessage::HasAttachments($bWatchInline);
		}

		function GetSensitivity()
		{
			if (null !== $this->Sensitivity)
			{
				return $this->Sensitivity;
			}

			return $this->GetSensitivityAsInt();
		}

		/**
		 * @return bool
		 */
		function IsVoiceMessage()
		{
			$bResult = false;
			CApi::Plugin()->RunHook('webmail.voice-message-detect', array($this, &$bResult));
			return $bResult;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetFromAsString($isClear = false)
		{
			$value = MailMessage::GetFromAsString();
			if ($isClear)
			{
				$value = WebMailMessage::ClearForSend($value);
			}
			return $value;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetShortFromAsString($isClear = false)
		{
			$email =& $this->GetFrom();
			$value = $email->DisplayName;
			if ($isClear)
			{
				$value = WebMailMessage::ClearForSend($value);
			}
			return trim($value);
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetToAsString($isClear = false)
		{
			$emails = &$this->GetTo();
			$out = $emails->ToDecodedString();

			return ($isClear) ? WebMailMessage::ClearForSend($out) : $out;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetToAsStringForSend($isClear = true)
		{
			$emails = &$this->GetTo();
			$out = $emails->ToFriendlyString();

			return ($isClear) ? WebMailMessage::ClearForSend($out) : $out;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetCcAsString($isClear = false)
		{
			/*$value = MailMessage::GetCcAsString();
			if ($isClear)
			{
				$value = WebMailMessage::ClearForSend($value);
			}
			return $value;*/

			$emails = &$this->GetCc();
			$out = $emails->ToDecodedString();

			return ($isClear) ? WebMailMessage::ClearForSend($out) : $out;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetCcAsStringForSend($isClear = true)
		{
			$emails = &$this->GetCc();
			$out = $emails->ToFriendlyString();

			return ($isClear) ? WebMailMessage::ClearForSend($out) : $out;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetBccAsString($isClear = true)
		{
			$emails = &$this->GetBcc();
			$out = $emails->ToDecodedString();

			return ($isClear) ? WebMailMessage::ClearForSend($out) : $out;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetBccAsStringForSend($isClear = true)
		{
			$emails = &$this->GetBcc();
			$out = $emails->ToFriendlyString();

			return ($isClear) ? WebMailMessage::ClearForSend($out) : $out;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetSubject($isClear = false)
		{
			$value = MailMessage::GetSubject();
			if ($isClear)
			{
				$value = WebMailMessage::ClearForSend($value);
			}
			return $value;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetFromAsStringForSend($isClear = true)
		{
			$email = &$this->GetFrom();
			$out = $email->ToFriendlyString();
			return ($isClear) ? WebMailMessage::ClearForSend($out) : $out;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetReplyToAsString($isClear = false)
		{
			$value = MailMessage::GetReplyToAsString();
			if ($isClear)
			{
				$value = WebMailMessage::ClearForSend($value);
			}
			return $value;
		}

		/**
		 * @param bool $isClear optional
		 * @return string
		 */
		function GetReplyToAsStringForSend($isClear = true)
		{
			$emails = &$this->GetReplyTo();
			$out = $emails->ToFriendlyString();

			return ($isClear) ? WebMailMessage::ClearForSend($out) : $out;
		}

		/**
		 * @param bool $onlyTo optional
		 * @return string
		 */
		function GetAllRecipientsEmailsAsString($onlyTo = false)
		{
			$emails = '';
			$emailCollection = &$this->GetAllRecipients($onlyTo);
			foreach (array_keys($emailCollection->Instance()) as $key)
			{
				$email = $emailCollection->Get($key);
				$emails .= ($emails === '') ? $email->Email : ', '.$email->Email;
				unset($email);
			}

			return $emails;
		}

		/**
		 * @param string $value
		 * @return string
		 */
		public static function ClearForSend($value)
		{
			if ((isset($GLOBALS[MailOutputCharset]) ? $GLOBALS[MailOutputCharset] : '') == CPAGE_UTF8)
			{
				$value = ConvertUtils::ClearUtf8($value);
			}
			return $value;
		}

		/**
		 * @return string
		 */
		function _addTrimMesage($bodytype = 1)
		{
			if (isset($GLOBALS[MIMEConst_IsBodyTrim]) && $GLOBALS[MIMEConst_IsBodyTrim] && !isset($GLOBALS['PRINTFILE']))
			{
				$mes_charset = $this->Charset;
				$href = 'message-view.php?type='.MESSAGE_VIEW_TYPE_FULL.'&msg_id='.((int) $this->IdMsg).'&msg_uid='.urlencode($this->Uid).'&folder_id='.((int) $this->IdFolder).'&folder_name='.urlencode('').'&charset='.urlencode($mes_charset).'&bodytype='.((int) $bodytype);
				return '<br /><div class="wm_safety_info"><span><span>'.ReportMessagePartDisplayed.' '.ReportViewEntireMessage.' </span><a href="'.$href.'" target="_blank">'.ReportClickHere.'</a></span></div><br />';
			}
			return '';
		}

		/**
		 * @return string
		 */
		function GetCensoredHtmlBody()
		{
			$Body = $this->TextBodies->HtmlTextBodyPart;

			$ToRemoveArray = array (
				"'<!doctype[^>]*>'si",
				"'<html[^>]*>'si",
				"'</html>'si",
				"'<iframe[^>]*>'si",
				"'</iframe>'si",
				"'<body[^>]*>'si",
				"'<link[^>]*>'si",
				"'</body>'si",
				"'<base[^>]*>'si",
				"'<title[^>]*>.*?</title>'si",
				"'<head[^>]*>.*?</head[^>]*>'si",
				"'<style[^>]*>.*?</style[^>]*>'si",
				"'<script[^>]*>.*?</script[^>]*>'si",
				"'</script[^>]*>'si",
				"'<object[^>]*>.*?</object>'si",
				"'<embed[^>]*>.*?</embed[^>]*>'si",
				"'<applet[^>]*>.*?</applet[^>]*>'si",
				"'<mocha[^>]*>.*?</mocha[^>]*>'si",
				"'<meta[^>]*>'si",
			);

			$Body = preg_replace($ToRemoveArray, '', $Body);

//			$Body = preg_replace('/<([^>]*)&{.*}([^>]*)>/', '<&{;}\\3>', $Body); #400
			$Body = preg_replace("/\x0D\x0A\t+/", "\x0D\x0A", $Body);
			$Body = preg_replace_callback('/<a[^>]+/i', 'targetAdd', $Body);

			return ConvertUtils::ClearUtf8($Body).$this->_addTrimMesage(1);
		}

		protected function prepearPlainStringUrlHelper($aMatch)
		{
			if (is_array($aMatch) && 6 < count($aMatch))
			{
				while (in_array($sChar = substr($aMatch[3], -1), array(']', ')')))
				{
					if (substr_count($aMatch[3], ']' === $sChar ? '[': '(') - substr_count($aMatch[3], $sChar) < 0)
					{
						$aMatch[3] = substr($aMatch[3], 0, -1);
						$aMatch[6] = (']' === $sChar ? ']': ')').$aMatch[6];
					}
					else
					{
						break;
					}
				}

				$sHrefPrefix = '';
				if (0 === strpos($aMatch[2].$aMatch[3], 'www.'))
				{
					$sHrefPrefix = 'http://';
				}

				$this->aPrepearPlainStringUrls[] =
					stripslashes('<a target="_blank" href="'.$sHrefPrefix.$aMatch[2].$aMatch[3].'">'.$aMatch[2].$aMatch[3].'</a>');

				return $aMatch[1].'@#@link{'.(count($this->aPrepearPlainStringUrls) - 1).'}link@#@'.$aMatch[6];
			}

			return '';
		}

		protected function prepearPlainStringShortUrlHelper($aMatch)
		{
			if (is_array($aMatch) && 2 < count($aMatch) && !empty($aMatch[1]))
			{
				if (in_array(strtolower($aMatch[1]), array('asp.net', 'mailbee.net', 'vb.net')))
				{
					return $aMatch[0];
				}

				$this->aPrepearPlainStringUrls[] = stripslashes('<a target="_blank" href="http://'.$aMatch[1].'">'.$aMatch[1].'</a>');
				return '@#@link{'.(count($this->aPrepearPlainStringUrls) - 1).'}link@#@'.$aMatch[2];
			}

			return '';
		}

		protected function prepearPlainStringMailToHelper($aMatch)
		{
			if (is_array($aMatch) && isset($aMatch[1]))
			{
				$this->aPrepearPlainStringUrls[] =
					stripslashes('<a target="_blank" href="mailto:'.$aMatch[1].'">'.$aMatch[1].'</a>');

				return '@#@link{'.(count($this->aPrepearPlainStringUrls) - 1).'}link@#@';
			}

			return '';
		}

		/**
		 * @param bool $replaceSpecialHtmlChars optional
		 * @return string
		 */
		function GetCensoredTextBody($replaceSpecialHtmlChars = false)
		{
			$sText = $this->HasPlainText()
				? $this->TextBodies->PlainTextBodyPart
				: $this->TextBodies->HtmlToPlain();

			$sText = str_replace("\r", '', $sText);

			$this->aPrepearPlainStringUrls = array();
/* $sPattern = '/(<\w+.*?>|[^=<>!:\'"\/]|^)((?:http[s]?:\/\/)|(?:svn:\/\/)|(?:git:\/\/)|(?:[s]?ftp[s]?:\/\/)|(?:www\.))((\S+?)(\\/)?)((?:&gt;)?|[^\w\=\\/;\(\)\[\]]*?)(?=<|\s|$)/im'; */
			$sPattern = '/([\W]|^)((?:https?:\/\/)|(?:svn:\/\/)|(?:git:\/\/)|(?:s?ftps?:\/\/)|(?:www\.))((\S+?)(\\/)?)((?:&gt;)?|[^\w\=\\/;\(\)\[\]]*?)(?=<|\s|$)/im';
			$sText = preg_replace_callback($sPattern, array(&$this, 'prepearPlainStringUrlHelper'), $sText);

			$sPattern = '/([\w\.!#\$%\-+.]+@[A-Za-z0-9\-]+(\.[A-Za-z0-9\-]+)+)/';
			$sText = preg_replace_callback($sPattern, array(&$this, 'prepearPlainStringMailToHelper'), $sText);

			$sPattern = '/([a-z0-9-\.]+\.(?:com|org|net|ru))([^a-z0-9-\.])/i';
			$sText = preg_replace_callback($sPattern, array(&$this, 'prepearPlainStringShortUrlHelper'), $sText);

			$sText = htmlspecialchars($sText);

			for ($i = 0, $c = count($this->aPrepearPlainStringUrls); $i < $c; $i++)
			{
				$sText = str_replace('@#@link{'.$i.'}link@#@', $this->aPrepearPlainStringUrls[$i], $sText);
			}

			$this->aPrepearPlainStringUrls = array();

			$BodyArray = explode("\n", $sText);
			foreach ($BodyArray as $key => $BodyPart)
			{
				if (preg_match('/^[^&;]{0,20}&gt;/', $BodyPart))
				{
					$BodyArray[$key] = '<font class="wm_message_body_quotation">'.$BodyPart.'</font>';
				}
			}

			$sText = join("<br />\n", $BodyArray);
			unset($BodyArray);

			$sText = str_replace("\t", '&nbsp;&nbsp;&nbsp;', $sText);
			$sText = str_replace('  ', '&nbsp;&nbsp;', $sText).$this->_addTrimMesage(0);

			if ($replaceSpecialHtmlChars)
			{
				$sText = WebMailMessage::ClearForSend($sText);
			}

			return $sText;
		}

		/**
		 * @param bool $replaceSpecialHtmlChars optional
		 * @return string
		 */
		function GetNotCensoredTextBody($replaceSpecialHtmlChars = false)
		{
			$Body = '';
			if ($this->HasPlainText())
			{
				$Body = $this->TextBodies->PlainTextBodyPart.$this->_addTrimMesage(0);
			}
			else
			{
				$Body = $this->TextBodies->HtmlToPlain().$this->_addTrimMesage(1);
			}

			if ($replaceSpecialHtmlChars)
			{
				$Body = WebMailMessage::ClearForSend($Body);
			}

			return $Body;
		}

		/**
		 * @return string
		 */
		function GetPlainLowerCaseBodyText()
		{
			$mailText = '';
			if ($this->TextBodies->PlainTextBodyPart != '')
			{
				$mailText = $this->TextBodies->PlainTextBodyPart;
			}
			elseif ($this->TextBodies->HtmlTextBodyPart != '')
			{
				$mailText = $this->TextBodies->HtmlToPlain();
			}

			$mailText = preg_replace('/[\s]+/', ' ', str_replace(array(CRLF, "\t", "\r", "\n"), ' ', $mailText));

			return api_Utils::Utf8ToLowerCase(
				ConvertUtils::ConvertEncoding($mailText, $GLOBALS[MailOutputCharset], 'utf-8'));
		}

		function GetPlainBodyTextFromPlainAndHtml()
		{
			$mailText = '';
			if ($this->TextBodies->PlainTextBodyPart != '')
			{
				$mailText = $this->TextBodies->PlainTextBodyPart;
			}
			elseif ($this->TextBodies->HtmlTextBodyPart != '')
			{
				$mailText = $this->TextBodies->HtmlToPlain();
			}

			$mailText = preg_replace('/[\s]+/', ' ', str_replace(array(CRLF, "\t", "\r", "\n"), ' ', $mailText));

			return $mailText;
		}

		/**
		 * @param bool $replaceSpecialHtmlChars optional
		 * @return string
		 */
		function GetCensoredHtmlWithImageLinks($replaceSpecialHtmlChars = false, $messageInfo = null)
		{
			$text = $this->GetCensoredHtmlBody();

			if ($this->Attachments->Count() == 0)
			{
				return $text;
			}

			for ($i = 0, $count = $this->Attachments->Count(); $i < $count; $i++)
			{
				$attach =& $this->Attachments->Get($i);
				$filename = ConvertUtils::ClearFileName(ConvertUtils::ClearUtf8($attach->GetFilenameFromMime(), $GLOBALS[MailInputCharset], CPAGE_UTF8));
				$imgUrl = 'attach.php?img&tn='.$this->IdMsg.'-'.$i.'_'.$attach->GetTempName().'&filename='.$filename;

				$contentLocation = $attach->MimePart->GetContentLocation();
				$contentId = $attach->MimePart->GetContentID();
				if (null !== $attach->MimePart->BodyStructureIndex && null !== $messageInfo)
				{
					$imgUrl .= '&'.$messageInfo->GetUrl().'&bsi='.urlencode($attach->MimePart->BodyStructureIndex);
					if (null !== $attach->MimePart->BodyStructureEncode && strlen($attach->MimePart->BodyStructureEncode) > 0)
					{
						$imgUrl .= '&bse='.urlencode(ConvertUtils::GetBodyStructureEncodeType($attach->MimePart->BodyStructureEncode));
					}
				}

				$patternArray = array('cid:'.$contentId, 'CID:'.$contentId, 'Cid:'.$contentId);
				if ($contentId !== '')
				{
					$text = str_replace($patternArray, $imgUrl, $text);
				}
				else if ($contentLocation !== '')
				{
					$text = str_replace($contentLocation, $imgUrl, $text);
				}
			}

			return $text;
		}

		/**
		 * @return int
		 */
		function GetMailSize()
		{
			return ($this->Size) ? $this->Size : strlen($this->TryToGetOriginalMailMessage());
		}

		/**
		 * @param bool $isClear = false
		 * @param int $timeOffset = 0
		 * @return string
		 */
		function GetRelpyAsHtml($isClear = false, $timeOffset = 0, $messageInfo = null)
		{
			$result = '<br /><blockquote style="border-left: solid 2px #000000; margin-left: 5px; padding-left: 5px">';
			$result .= '---- Original Message ----<br />';
			$result .= '<b>From</b>: '.ConvertUtils::WMHtmlSpecialChars($this->GetFromAsString()).'<br />';
			$result .= '<b>To</b>: '.ConvertUtils::WMHtmlSpecialChars($this->GetToAsString()).'<br />';
			$cc = ConvertUtils::WMHtmlSpecialChars($this->GetCcAsString());
			if ($cc)
			{
				$result .= '<b>Cc</b>: '.$cc.'<br />';
			}

			$date = $this->GetDate();
			$result .= '<b>Sent</b>: '.$date->GetFormattedFullDate($timeOffset).'<br />';
			$result .= '<b>Subject</b>: '.ConvertUtils::WMHtmlSpecialChars($this->GetSubject()).'<br /><br />';

			if ($this->HasHtmlText())
			{
				$result .= $this->GetCensoredHtmlWithImageLinks(true, $messageInfo).'</blockquote>';
			}
			else
			{
				$result .= nl2br(ConvertUtils::WMHtmlSpecialChars($this->TextBodies->PlainTextBodyPart)).'</blockquote>';
			}

			return ($isClear) ?  WebMailMessage::ClearForSend($result) : $result;
		}

		/**
		 * @param bool $isClear = false
		 * @param int $timeOffset = 0
		 * @return string
		 */
		function GetRelpyAsPlain($isClear = false, $timeOffset = 0)
		{
			$result = CRLF.'---- Original Message ----'.CRLF;
			$result .= 'From: '.$this->GetFromAsString().CRLF;
			$result .= 'To: '.$this->GetToAsString().CRLF;
			$cc = $this->GetCcAsString();
			if ($cc)
			{
				$result .= 'Cc: '.$cc.CRLF;
			}

			$date = $this->GetDate();
			$result .= 'Sent: '.$date->GetFormattedFullDate($timeOffset).CRLF;
			$result .= 'Subject: '.$this->GetSubject().CRLF.CRLF;

			$result .= ($this->HasPlainText())
							? $this->TextBodies->PlainTextBodyPart
							: $this->TextBodies->HtmlToPlain();

			$result = str_replace("\n", "\n>", $result);

			return ($isClear) ?  WebMailMessage::ClearForSend($result) : $result;
		}

		/**
		 * @param bool $isClear = false
		 * @param int $timeOffset = 0
		 * @return string
		 */
		function GetForwardAsHtml($isClear = false, $timeOffset = 0, $messageInfo = null)
		{
			$result = '<br />---- Original Message ----<br />';
			$result .= '<b>From</b>: '.ConvertUtils::WMHtmlSpecialChars($this->GetFromAsString()).'<br />';
			$result .= '<b>To</b>: '.ConvertUtils::WMHtmlSpecialChars($this->GetToAsString()).'<br />';
			$cc = ConvertUtils::WMHtmlSpecialChars($this->GetCcAsString());
			if ($cc)
			{
				$result .= '<b>Cc</b>: '.$cc.'<br />';
			}

			$date = $this->GetDate();
			$result .= '<b>Sent</b>: '.$date->GetFormattedFullDate($timeOffset).'<br />';
			$result .= '<b>Subject</b>: '.ConvertUtils::WMHtmlSpecialChars($this->GetSubject()).'<br /><br />';

			if ($this->HasHtmlText())
			{
				$result .= $this->GetCensoredHtmlWithImageLinks(true, $messageInfo);
			}
			else
			{
				$result .= nl2br(ConvertUtils::WMHtmlSpecialChars($this->TextBodies->PlainTextBodyPart));
			}

			return ($isClear) ?  WebMailMessage::ClearForSend($result) : $result;
		}

		/**
		 * @param bool $isClear = false
		 * @param int $timeOffset = 0
		 * @return string
		 */
		function GetForwardAsPlain($isClear = false, $timeOffset = 0)
		{
			$result = CRLF.'---- Original Message ----'.CRLF;
			$result .= 'From: '.$this->GetFromAsString().CRLF;
			$result .= 'To: '.$this->GetToAsString().CRLF;
			$cc = $this->GetCcAsString();
			if ($cc)
			{
				$result .= 'Cc: '.$cc.CRLF;
			}

			$date = $this->GetDate();
			$result .= 'Sent: '.$date->GetFormattedFullDate($timeOffset).CRLF;
			$result .= 'Subject: '.$this->GetSubject().CRLF.CRLF;

			$result .= ($this->HasPlainText())
							? $this->TextBodies->PlainTextBodyPart
							: $this->TextBodies->HtmlToPlain();

			return ($isClear) ?  WebMailMessage::ClearForSend($result) : $result;
		}
	}

	class WebMailMessageCollection extends CollectionBase
	{
		/**
		 * @param int
		 */
		public $FilteredCount = null;

		/**
		 * @param bool
		 */
		public $Error = false;


		/**
		 * @return WebMailMessageCollection
		 */
		function WebMailMessageCollection()
		{
			CollectionBase::CollectionBase();
		}

		/**
		 * @param WebMailMessage $message
		 */
		function Add(&$message)
		{
			if ($message)
			{
				$this->List->Add($message);
			}
		}

		/**
		 * @param WebMailMessage $message
		 */
		function AddCopy($message)
		{
			if ($message)
			{
				$this->List->Add($message);
			}
		}

		/**
		 * @param int $index
		 * @return WebMailMessage
		 */
		function &Get($index)
		{
			return $this->List->Get($index);
		}

		/**
		 * @param int $id
		 */
		function SetAllMessageFolderId($id)
		{
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$msg =& $this->Get($i);
				$msg->IdFolder = $id;
			}
		}
	}

	function targetAdd($array)
	{
		if (is_array($array) && count($array) > 0)
		{
			$temp = $array[0];
			$tempLower = strtolower($temp);
			$sharpStart = strpos($tempLower, '#');
			$sharpHas = false;
			$equallyHas = false;
			$urlHas = false;

			$addTarget = true;

			if ($sharpStart !== false)
			{
				$hrefStart = strpos($tempLower, 'href');
				if ($hrefStart !== false && $hrefStart > 0)
				{
					for ($i = $hrefStart + 4, $l = strlen($temp); $i < $l; $i++)
					{

						if ($equallyHas && $sharpHas && !$urlHas)
						{
							$addTarget = false;
							break;
						}

						$char = $temp{$i};
						if (!$equallyHas && $char == '=')
						{
							$equallyHas = true;
							continue;
						}

						if ($equallyHas && ($char != ' ' && $char != '"' && $char != '\'' && $char != '#'))
						{
							$urlHas = true;
							continue;
						}

						if ($equallyHas && $char == '#')
						{
							$sharpHas = true;
							continue;
						}
					}
				}
			}

			if ($addTarget)
			{
				return '<a target="_blank" '.substr($temp, 3);
			}
			else
			{
				return $temp;
			}
		}

		return '';
	}
