<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/mime/inc_constants.php');
	require_once(WM_ROOTPATH.'common/mime/class_mimepart.php');
	require_once(WM_ROOTPATH.'common/mime/class_emailaddress.php');
	require_once(WM_ROOTPATH.'common/mime/class_emailaddresscollection.php');
	require_once(WM_ROOTPATH.'common/mime/class_attachmentcollection.php');
	require_once(WM_ROOTPATH.'common/mime/class_textbodycollection.php');
	require_once(WM_ROOTPATH.'common/class_datetime.php');

	class MailMessage extends MimePart
	{
		/**
		 * @var AttachmentCollection
		 */
		var $Attachments = null;

		/**
		 * @var TextBodyCollection
		 */
		var $TextBodies = null;

		/**
		 * @var string
		 */
		var $OriginalMailMessage;

		/**
		 * @var bool
		 */
		var $HasCharset = false;

		/**
		 * @return EmailAddress
		 */
		function &GetFrom()
		{
			$emailAddress = new EmailAddress();
			$emailAddress->Parse($this->Headers->GetHeaderDecodedValueByName(MIMEConst_FromLower));
			return $emailAddress;
		}

		/**
		 * @return string
		 */
		function GetTextCharset()
		{
			return (null !== $this->TextBodies) ? $this->TextBodies->GetTextCharset() : null;
		}

		/**
		 * @return string
		 */
		function GetFromAsString()
		{
			return $this->Headers->GetHeaderDecodedValueByName(MIMEConst_FromLower);
		}

		/**
		 * @param EmailAddressCollection $value
		 */
		function SetFrom($value)
		{
			$this->Headers->SetHeaderByName(MIMEConst_From, $value->ToDecodedString());
		}

		/**
		 * @param string $value
		 */
		function SetFromAsString($value)
		{
			$this->Headers->SetHeader(new Header(MIMEConst_From, $value, true));
		}


		/**
		 * @return EmailAddressCollection
		 */
		function &GetTo()
		{
			$emails = new EmailAddressCollection($this->Headers->GetHeaderDecodedValueByName(MIMEConst_ToLower));
			return $emails;
		}

		/**
		 * @return string
		 */
		function GetToAsString()
		{
			return $this->Headers->GetHeaderDecodedValueByName(MIMEConst_ToLower);
		}

		/**
		 * @param EmailAddressCollection $value
		 */
		function SetTo($value)
		{
			$this->Headers->SetHeaderByName(MIMEConst_To, $value->ToDecodedString());
		}

		/**
		 * @param string $value
		 */
		function SetToAsString($value)
		{
			$this->Headers->SetHeader(new Header(MIMEConst_To, $value, true));
		}

		/**
		 * @return EmailAddressCollection
		 */
		function &GetCc()
		{
			$emails = new EmailAddressCollection($this->Headers->GetHeaderDecodedValueByName(MIMEConst_CcLower));
			return $emails;
		}

		/**
		 * @return string
		 */
		function GetCcAsString()
		{
			return $this->Headers->GetHeaderDecodedValueByName(MIMEConst_CcLower);
		}

		/**
		 * @param EmailAddressCollection $value
		 */
		function SetCc($value)
		{
			$this->Headers->SetHeaderByName(MIMEConst_Cc, $value->ToDecodedString());
		}

		/**
		 * @param string $value
		 */
		function SetCcAsString($value)
		{
			$this->Headers->SetHeader(new Header(MIMEConst_Cc, trim($value), true));
		}

		/**
		 * @return EmailAddressCollection
		 */
		function &GetBcc()
		{
			$emails = new EmailAddressCollection($this->Headers->GetHeaderDecodedValueByName(MIMEConst_BccLower));;
			return $emails;
		}

		/**
		 * @return string
		 */
		function GetBccAsString()
		{
			return $this->Headers->GetHeaderDecodedValueByName(MIMEConst_BccLower);
		}

		/**
		 * @param EmailAddressCollection $value
		 */
		function SetBcc($value)
		{
			$this->Headers->SetHeaderByName(MIMEConst_Bcc, $value->ToDecodedString());
		}

		/**
		 * @param string $value
		 */
		function SetBccAsString($value)
		{
			$this->Headers->SetHeader(new Header(MIMEConst_Bcc, $value, true));
		}

		/**
		 * @param string $value
		 */
		function SetReadMailConfirmationAsString($value)
		{
			$this->Headers->SetHeader(new Header(MIMEConst_DispositionNotificationTo, $value, true));
			$this->Headers->SetHeader(new Header(MIMEConst_XConfirmReadingTo, $value, true));
		}

		/**
		 * @return	string
		 */
		function GetReadMailConfirmationAsString()
		{
			$notification = $this->Headers->GetHeaderDecodedValueByName(MIMEConst_DispositionNotificationTo);
			if (strlen($notification) === 0)
			{
				$notification = $this->Headers->GetHeaderDecodedValueByName(MIMEConst_XConfirmReadingTo);
			}

			return $notification;
		}

		/**
		 * @return EmailAddressCollection
		 */
		function &GetReplyTo()
		{
			$emails = new EmailAddressCollection($this->Headers->GetHeaderDecodedValueByName(MIMEConst_ReplyToLower));
			return $emails;
		}

		/**
		 * @return string
		 */
		function GetReplyToAsString()
		{
			return $this->Headers->GetHeaderDecodedValueByName(MIMEConst_ReplyToLower);
		}

		/**
		 * @param EmailAddressCollection $value
		 */
		function SetReplyTo($value)
		{
			$this->Headers->SetHeaderByName(MIMEConst_Bcc, $value->ToDecodedString());
		}

		/**
		 * @param string $value
		 */
		function SetReplyToAsString($value)
		{
			$this->Headers->SetHeader(new Header(MIMEConst_ReplyToLower, $value, true));
		}

		/**
		 * @return string
		 */
		function GetSubject()
		{
			return str_replace(array("\n","\r","\t"), '', $this->Headers->GetHeaderDecodedValueByName(MIMEConst_SubjectLower));
		}

		/**
		 * @param string $value
		 */
		function SetSubject($value)
		{
			$this->Headers->SetHeader(new Header(MIMEConst_Subject, $value, true));
		}

		/**
		 * @return CDateTime
		 */
		function GetDate()
		{
			$dt = $this->GetDateReceived();
			if ($dt === '')
			{
				$dt = $this->Headers->GetHeaderValueByName(MIMEConst_DateLower);
			}

			return new CDateTime(ConvertUtils::ParseRFC2822DateString(trim($dt)));
		}

		/**
		 * @param CDateTime $date
		 */
		function SetDate($date)
		{
			$this->Headers->SetHeader(new Header(MIMEConst_Date, $date->GetAsStrNew(), true));
		}

		/**
		 * @return string
		 */
		function GetDateReceived()
		{
			$date = '';
			$receiv = $this->Headers->GetHeadersValuesByName(MIMEConst_Received);
			foreach ($receiv as $value)
			{
				$value = str_replace(array("\r", "\n"), array('', ' '), $value);
				$value = preg_replace('/[\s]+/', ' ', $value);

				$receivedArr = explode(';', $value);
				if (is_array($receivedArr) && count($receivedArr) > 0)
				{
					foreach ($receivedArr as $rValue)
					{
						$rValue = trim($rValue);
						if (preg_match('/[\d]{4} [\d]{2}:[\d]{2}:[\d]{2} /', $rValue))
						{
							$date = $rValue;
							break 2;
						}
					}
				}
			}

			return str_replace('  ', ' ', str_replace(array("\r", "\n", "\t"), array('', ' ', ' '), $date));
		}

		/**
		 * @return string
		 */
		function GetPriority()
		{
			$header = &$this->Headers->GetHeaderByName(MIMEConst_XMSMailPriorityLower);
			if ($header != null)
			{
				return $header->Value;
			}

			$header = &$this->Headers->GetHeaderByName(MIMEConst_ImportanceLower);
			if ($header != null)
			{
				return $header->Value;
			}

			$header = &$this->Headers->GetHeaderByName(MIMEConst_XPriorityLower);
			if ($header != null)
			{
				return $header->Value;
			}

			return '';
		}

		/**
		 * @param int $value
		 */
		function SetPriority($value)
		{
			switch ($value)
			{
				case 1:
					$value .= ' (Highest)';
					break;
				case 2:
					$value .= ' (High)';
					break;
				case 3:
					$value .= ' (Normal)';
					break;
				case 4:
					$value .= ' (Low)';
					break;
				case 5:
					$value .= ' (Lowest)';
					break;
			}

			$this->Headers->SetHeaderByName(MIMEConst_XPriority, $value);
		}

		/**
		 * @param int $value
		 */
		function SetSensivity($value)
		{
			$headValue = '';
			switch ($value)
			{
				case MIME_SENSIVITY_CONFIDENTIAL:
					$headValue = 'Company-Confidential';
					break;
				case MIME_SENSIVITY_PRIVATE:
					$headValue = 'Private';
					break;
				case MIME_SENSIVITY_PERSONAL:
					$headValue = 'Personal';
					break;
			}

			if (strlen($headValue) > 0)
			{
				$this->Headers->SetHeaderByName(MIMEConst_Sensitivity, $headValue);
			}
		}

		/**
		 * @param string $rawData
		 * @return MailMessage
		 */
		function MailMessage($rawData = null, $holdOriginalBody = false)
		{
			ConvertUtils::SetLimits();

			$GLOBALS[MailInputCharset] = (isset($GLOBALS[MailInputCharset])) ? $GLOBALS[MailInputCharset] : '';
			MimePart::MimePart($rawData);
			$null = null;
			$this->Attachments = new AttachmentCollection($null);
			$this->TextBodies = new TextBodyCollection($null);

			$this->OriginalMailMessage = '';
			if ($rawData)
			{
				if ($holdOriginalBody)
				{
					$this->OriginalMailMessage =& $rawData;
				}
				$this->_setAllParams();
			}
		}

		/**
		 * @return EmailAddressCollection
		 */
		function &GetAllRecipients($onlyTo = false, $addReply = false)
		{
			$emails = array();
			$allRecipients = new EmailAddressCollection();
			$toAddr =& $this->GetTo();
			foreach (array_keys($toAddr->Instance()) as $key)
			{
				$temp = $toAddr->Get($key);
				if ($temp && !in_array($temp->Email, $emails))
				{
					$emails[] = $temp->Email;
					$allRecipients->AddEmailAddress($temp);
				}
				unset($temp);
			}

			if ($allRecipients->Count() > 0 && $onlyTo)
			{
				return $allRecipients;
			}

			$toCc = &$this->GetCc();
			foreach (array_keys($toCc->Instance()) as $key)
			{
				$temp = $toCc->Get($key);
				if ($temp && !in_array($temp->Email, $emails))
				{
					$emails[] = $temp->Email;
					$allRecipients->AddEmailAddress($temp);
				}
				unset($temp);
			}

			$toBcc = &$this->GetBcc();
			foreach (array_keys($toBcc->Instance()) as $key)
			{
				$temp = $toBcc->Get($key);
				if ($temp && !in_array($temp->Email, $emails))
				{
					$emails[] = $temp->Email;
					$allRecipients->AddEmailAddress($temp);
				}
				unset($temp);
			}

			if ($addReply)
			{
				$toReply = &$this->GetReplyTo();
				if ($toReply->Count() > 0)
				{
					foreach (array_keys($toReply->Instance()) as $key)
					{
						$temp = $toReply->Get($key);
						if ($temp && !in_array($temp->Email, $emails))
						{
							$emails[] = $temp->Email;
							$allRecipients->AddEmailAddress($temp);
						}
						unset($temp);
					}
				}
				else
				{
					$toFrom =& $this->GetFrom();
					if ($toFrom && !in_array($toFrom->Email, $emails))
					{
						$emails[] = $toFrom->Email;
						$allRecipients->AddEmailAddress($toFrom);
					}
				}
			}

			return $allRecipients;
		}

		/**
		 * @return EmailAddressCollection
		 */
		function &GetAllRecipientsForReplyAll($accountEmailLower)
		{
			$emails = array();
			$allRecipients = new EmailAddressCollection();

			$toAddr =& $this->GetTo();
			$toAddrKeys = array_keys($toAddr->Instance());
			foreach ($toAddrKeys as $key)
			{
				$temp = $toAddr->Get($key);
				if ($temp && !in_array($temp->Email, $emails) && strtolower($temp->Email) != $accountEmailLower)
				{
					$emails[] = $temp->Email;
					$allRecipients->AddEmailAddress($temp);
				}
				unset($temp);
			}

			$ccAddr =& $this->GetCc();
			$ccAddrKeys = array_keys($ccAddr->Instance());
			foreach ($ccAddrKeys as $key)
			{
				$temp = $ccAddr->Get($key);
				if ($temp && !in_array($temp->Email, $emails))
				{
					$emails[] = $temp->Email;
					$allRecipients->AddEmailAddress($temp);
				}
				unset($temp);
			}

			return $allRecipients;
		}

		/**
		 * @return MimePartCollection
		 */
		function GetSubParts()
		{
			return $this->_subParts;
		}

		/**
		 * Loads the message from the specified file.
		 * @param string $filename
		 * @param bool $holdOriginalBody optional
		 * @return bool
		 */
		function LoadMessageFromEmlFile($filename, $holdOriginalBody = false)
		{
			$handle = @fopen($filename, 'rb');
			$data = @fread($handle, 3);
			if ($data)
			{
				if ($data === "\xEF\xBB\xBF")
				{
					$data = '';
					$GLOBALS[MailDefaultOriginalCharset] = $GLOBALS[MailDefaultCharset];
					$GLOBALS[MailDefaultCharset] = CPAGE_UTF8;
					$GLOBALS[MailInputCharset] = CPAGE_UTF8;
					$this->HasCharset = true;
				}
				elseif (isset($GLOBALS[MailDefaultOriginalCharset]))
				{
					$GLOBALS[MailDefaultCharset] = $GLOBALS[MailDefaultOriginalCharset];
				}

				$data .= @fread($handle, filesize($filename)-3);
				$this->OriginalMailMessage = '';
				if ($holdOriginalBody)
				{
					$this->OriginalMailMessage = &$data;
				}
				$this->Parse($data);
				unset($data);
				@fclose($handle);
				$this->_setAllParams();
				return true;
			}
			return false;
		}

		/**
		 * Loads the message from the specified string.
		 * @param string $messageRawBody
		 * @param bool $holdOriginalBody optional
		 */
		function LoadMessageFromRawBody(&$messageRawBody, $holdOriginalBody = false)
		{
			if (substr($messageRawBody, 0, 3) === "\xEF\xBB\xBF")
			{
				$GLOBALS[MailDefaultOriginalCharset] = $GLOBALS[MailDefaultCharset];
				$GLOBALS[MailDefaultCharset] = CPAGE_UTF8;
				$messageRawBody = substr($messageRawBody, 3);
				$GLOBALS[MailInputCharset] = CPAGE_UTF8;
				$this->HasCharset = true;
			}
			elseif (isset($GLOBALS[MailDefaultOriginalCharset]))
			{
				$GLOBALS[MailDefaultCharset] = $GLOBALS[MailDefaultOriginalCharset];
			}

			$this->OriginalMailMessage = '';
			if ($holdOriginalBody)
			{
				$this->OriginalMailMessage = &$messageRawBody;
			}

			$this->Parse($messageRawBody);
			unset($messageRawBody);
			$this->_setAllParams();
		}

		/**
		 * @param CBodyStructureObject $bodyStructureObject
		 */
		function FillByBodyStructure(&$bodyStructureObject, $defaulAccountEncode = null)
		{
			$gCharset = null;
			$oHeaders = $bodyStructureObject->GetFullHeaders();
			if (!empty($oHeaders))
			{
				$this->OriginalHeaders = $oHeaders;
				$this->Headers->Parse($oHeaders);
			}

			$this->TextBodies->BodyStructureType = $bodyStructureObject->ClassType();
			$this->Size = $bodyStructureObject->GetSize();

			$bodyPartsArray = array();
			$bodyPartsArray =& $bodyStructureObject->GetBodyPartsAsArray();
			if (count($bodyPartsArray) > 0)
			{
				foreach ($bodyPartsArray as $key => $variable)
				{
					$encode = '';
					$charset = (null !== $defaulAccountEncode) ? $defaulAccountEncode : CPAGE_UTF8;
					$part = $bodyStructureObject->GetPartByKey($key);
					if ($part)
					{
						$part_charset = CBodyStructureParser::GetCharsetFromPart($part);
						$part_encode = CBodyStructureParser::GetEncodeFromPart($part);

						if (strlen($part_charset) > 0)
						{
							$charset = $part_charset;
							$gCharset = (null === $gCharset)  ? $part_charset : $gCharset;
							$this->TextBodies->SetTextCharset($charset);
							$this->HasCharset = true;
						}

						if (strlen($part_encode) > 0)
						{
							$encode = $part_encode;
						}

						if (strlen($encode) > 0)
						{
							$variable = ConvertUtils::DecodeBodyByType($variable, $encode);
						}

						$variable = ConvertUtils::ConvertEncoding($variable, $charset, $GLOBALS[MailOutputCharset]);

						$len = MIMEConst_TrimBodyLen_Bytes;

						if (!isset($GLOBALS[MIMEConst_DoNotUseMTrim]) && $len > 0 && strlen($variable) > $len)
						{
							$variable = substr($variable, 0, $len);
							$GLOBALS[MIMEConst_IsBodyTrim] = true;
						}

						$type = CBodyStructureParser::GetBodyStructurePartType($part);
						switch ($type)
						{
							case BODYSTRUCTURE_TYPE_TEXT_PLAIN:
								$this->TextBodies->PlainTextBodyPart .=
									str_replace("\n", CRLF,
									str_replace("\r", '', ConvertUtils::WMBackHtmlNewCode($variable)));
								break;
							case BODYSTRUCTURE_TYPE_TEXT_HTML:
								$this->TextBodies->HtmlTextBodyPart .=
									str_replace("\n", CRLF,
									str_replace("\r", '', ConvertUtils::WMBackHtmlNewCode($variable)));
								break;
						}
					}
				}
			}

			$attachmentsIndexs = array();
			$attachmentsIndexs = $bodyStructureObject->GetAttachmentIndexs();
			if (count($attachmentsIndexs) > 0)
			{
				foreach ($attachmentsIndexs as $idx)
				{
					$part = $bodyStructureObject->GetPartByKey($idx);
					if ($part)
					{
						$part_name = CBodyStructureParser::GetNameFromPart($part);
						if (empty($part_name))
						{
							$part_name = CBodyStructureParser::GetFileNameFromPart($part);
						}

						if (empty($part_name))
						{
							$part_name = CBodyStructureParser::GetNullNameByType($part);
						}

						if ($gCharset !== null && !ConvertUtils::IsLatin($part_name))
						{
							$part_name = ConvertUtils::ConvertEncoding($part_name, $gCharset, CPAGE_UTF8);
						}

						$size = 0;
						if (!empty($part_name))
						{
							$size = CBodyStructureParser::GetSizeFromPart($part);
						}

						$duration = CBodyStructureParser::GetDurationFromPart($part);

						$part_encode = CBodyStructureParser::GetEncodeFromPart($part);
						$part_contentId = CBodyStructureParser::GetContentIdFromPart($part);
						if (!$part_contentId)
						{
							$part_contentId = null;
						}

						$part_name = ConvertUtils::DecodeHeaderString($part_name, CPAGE_UTF8, CPAGE_UTF8);

						$bIsInline = CBodyStructureParser::IsInlinePart($part);

						$this->Attachments->AddFromBodyStructure(
							$part_name, $idx, $size, $part_encode, $duration, $part_contentId, $bIsInline);
					}
				}
			}
		}

		/**
		 * @access private
		 */
		function _setAllParams()
		{
			$contentTypeCharset = $this->GetContentTypeCharset();
			$contentTypeCharset = (strtolower($contentTypeCharset) == 'us-ascii') ? '' : $contentTypeCharset;
			if ($contentTypeCharset && strlen($contentTypeCharset) > 0)
			{
				$this->HasCharset = true;
			}
			$GLOBALS[MailInputCharset] = (isset($GLOBALS[MailInputCharset]) && $GLOBALS[MailInputCharset] != '') ? $GLOBALS[MailInputCharset] : $contentTypeCharset;
			if ($GLOBALS[MailInputCharset])
			{
				$this->HasCharset = true;
			}
			$GLOBALS[MailInputCharset] = ($GLOBALS[MailInputCharset]) ? $GLOBALS[MailInputCharset] : $GLOBALS[MailDefaultCharset];
			if ($this->IsMimeMail())
			{
				$bIsAlternative = false !== strpos(strtolower($this->GetContentType()), 'multipart/alternative');

				$this->ReparseAllHeader($GLOBALS[MailInputCharset]);
				$this->TextBodies = new TextBodyCollection($this, $bIsAlternative);
				$this->Attachments = new AttachmentCollection($this, $bIsAlternative);
			}
			else
			{
				$this->ReparseAllHeader($GLOBALS[MailInputCharset]);

				$preg = array();
				preg_match('/\nbegin [\d]* ([.]*)/i', $this->_body, $preg);
				if (count($preg) > 0)
				{
					$firstBegin = strpos($this->_body, 'begin');
					$this->TextBodies->PlainTextBodyPart = ConvertUtils::ConvertEncoding(substr($this->_body, 0, $firstBegin-1), $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset]);
					$parts = explode("\n".'end', substr($this->_body, $firstBegin));
					$this->_body = '';

					for ($i = 0, $c = count($parts); $i < $c; $i++)
					{
						$parts[$i] = trim($parts[$i]);
						if (strlen($parts[$i]) == 0)
						{
							continue;
						}
						$startBody = strpos($parts[$i], CRLF);
						$firstLine = substr($parts[$i], 0, $startBody);
						$filename = preg_replace('/begin [\d]* ([.]*)/i', '\\1',$firstLine);

						$newMimePart = new MimePart();
						$newMimePart->Headers->SetHeaderByName(MIMEConst_ContentType, ConvertUtils::GetContentTypeFromFileName($filename).'; '.CRLF."\t".MIMEConst_NameLower.'="'.$filename.'"');
						$newMimePart->Headers->SetHeaderByName(MIMEConst_ContentTransferEncoding, 'x-uue');
						$newMimePart->Headers->SetHeaderByName(MIMEConst_ContentDisposition, MIMEConst_AttachmentLower.';'.CRLF."\t".MIMEConst_FilenameLower.'="'.$filename.'"');
						$newMimePart->_body = $parts[$i].CRLF.'end'.CRLF;

						$this->Attachments->List->Add(new Attachment($newMimePart));
						unset($newMimePart, $parts[$i]);
					}
				}
				else
				{
					$_strLen = strlen($this->_body);
					if (!isset($GLOBALS[MIMEConst_DoNotUseMTrim]) && $_strLen > MIMEConst_TrimBodyLen_Bytes)
					{
						$this->_body = substr($this->_body, 0, MIMEConst_TrimBodyLen_Bytes);
						$GLOBALS[MIMEConst_IsBodyTrim] = true;
					}

					$this->TextBodies->PlainTextBodyPart = ConvertUtils::ConvertEncoding($this->_body, $GLOBALS[MailInputCharset], $GLOBALS[MailOutputCharset]);
				}
			}
		}

		/**
		 * @return bool
		 */
		function IsMimeMail()
		{
			if ($this->GetContentType() === '' && $this->Headers->GetHeaderValueByName(MIMEConst_MimeVersionLower) === '')
			{
				return false;
			}
			return true;
		}

		/**
		 * @param string  $nputCharset
		 */
		function ReparseAllHeader($inputCharset)
		{
			$header = null;
			for ($i =0, $c = $this->Headers->Count(); $i < $c; $i++)
			{
				$header = &$this->Headers->Get($i);
				if (!ConvertUtils::IsLatin($header->Value))
				{
					$header->Value = ConvertUtils::ConvertEncoding($header->Value, $inputCharset, $GLOBALS[MailOutputCharset]);
					$header->IsParsed = true;
				}
			}
		}

		/**
		 * @return bool
		 */
		function HasAttachments($bWatchInline = true)
		{
			if ($this->Attachments->Count() > 0 && $bWatchInline)
			{
				return true;
			}
			else if ($this->Attachments->Count() > 0 && !$bWatchInline)
			{
				return $this->Attachments->HasInlineAttachment();
			}
			else
			{
				if (strlen($this->OriginalMailMessage) > 0)
				{
					return false;
				}

				$content = strtolower($this->GetContentType());
				if (strpos($content, MIMEConst_BoundaryLower) !== false)
				{
					if (strpos($content, MIMETypeConst_MultipartMixed) !== false)
					{
						return true;
					}
					if (strpos($content, MIMETypeConst_MessageReport) !== false)
					{
						return true;
					}
					if ($bWatchInline && strpos($content, MIMETypeConst_MultipartRelated) !== false)
					{
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * @return bool
		 */
		function HasHtmlText()
		{
			return $this->TextBodies->HtmlTextBodyPart != '';
		}

		/**
		 * @return bool
		 */
		function HasPlainText()
		{
			return $this->TextBodies->PlainTextBodyPart != '';
		}

		/**
		 * Saves a message into the specified file.
		 * @param string $filename
		 * @return bool
		 */
		function SaveMessage($filename)
		{
			$handle = @fopen($filename, 'wb');
			if ($handle)
			{
				$result = @fwrite($handle, $this->TryToGetOriginalMailMessage()) !== false;
				$result = @fclose($handle);
				return $result;
			}
			return false;
		}

		/**
		 * @return MimePart
		 */
		function CreateNewMessageAlternativeBody()
		{
			$oPart = new MimePart();
			if ($this->TextBodies->HtmlTextBodyPart != '' && $this->TextBodies->PlainTextBodyPart != '')
			{
				$oPart->_subParts = new MimePartCollection($oPart);
				$oPart->_sourceCharset = $GLOBALS[MailOutputCharset];

				$sBoundary = '--=_NextPart_'.md5(rand(100000, 999999));
				$oPart->Headers->SetHeaderByName(MIMEConst_ContentType,
					MIMETypeConst_MultipartAlternative.';'.CRLF."\t".MIMEConst_BoundaryLower.'="'.$sBoundary.'"');

				$oPart->_subParts->Add($this->TextBodies->ToPlainMime());
				$oPart->_subParts->Add($this->TextBodies->ToHtmlMime());
			}
			else
			{
				$oPart->_sourceCharset = $GLOBALS[MailOutputCharset];
				if ($this->HasPlainText())
				{
					if (!empty($this->TextBodies->CustomContentType))
					{
						$oPart->Headers->SetHeaderByName(MIMEConst_ContentType,
							$this->TextBodies->CustomContentType);
					}
					else
					{
						$oPart->Headers->SetHeaderByName(MIMEConst_ContentType,
							MIMETypeConst_TextPlain.'; '.MIMEConst_CharsetLower.'="'.$GLOBALS[MailOutputCharset].'"');
					}

					$oPart->SetEncodedBodyFromText($this->TextBodies->PlainTextBodyPart,
						empty($this->TextBodies->CustomContentTransferEncoding) ? null : $this->TextBodies->CustomContentTransferEncoding);
				}
				else if ($this->HasHtmlText())
				{
					if (!empty($this->TextBodies->CustomContentType))
					{
						$oPart->Headers->SetHeaderByName(MIMEConst_ContentType,
							$this->TextBodies->CustomContentType);
					}
					else
					{
						$oPart->Headers->SetHeaderByName(MIMEConst_ContentType,
							MIMETypeConst_TextHtml.'; '.MIMEConst_CharsetLower.'="'.$GLOBALS[MailOutputCharset].'"');
					}

					$oPart->SetEncodedBodyFromText($this->TextBodies->HtmlTextBodyPart,
						empty($this->TextBodies->CustomContentTransferEncoding) ? null : $this->TextBodies->CustomContentTransferEncoding);
				}
			}

			return $oPart;
		}

		/**
		 * @param MimePart $oPart
		 * @return MimePart
		 */
		function CreateNewMessageRelatedBody($oPart)
		{
			if ($this->Attachments && $this->Attachments->HasInlineAttachment())
			{
				$oNewMimePart = new MimePart();
				$oNewMimePart->_subParts = new MimePartCollection($oNewMimePart);
				$oNewMimePart->_sourceCharset = $GLOBALS[MailOutputCharset];

				$sBoundary = '--=_NextPart_'.md5(rand(100000, 999999));
				$oNewMimePart->Headers->SetHeaderByName(MIMEConst_ContentType,
					MIMETypeConst_MultipartRelated.';'.CRLF."\t".MIMEConst_BoundaryLower.'="'.$sBoundary.'"');

				$oNewMimePart->_subParts->Add($oPart);

				$attachs =& $this->Attachments;
				foreach ($attachs->Instance() as $att)
				{
					if ($att && $att->IsInline)
					{
						$oNewMimePart->_subParts->Add($att->MimePart);
					}
					unset($att);
				}

				return $oNewMimePart;
			}

			return $oPart;
		}

		/**
		 * @param MimePart $oPart
		 * @return MimePart
		 */
		function CreateNewMessageMixedBody($oPart)
		{
			if ($this->Attachments && $this->Attachments->HasNotInlineAttachment())
			{
				$oNewMimePart = new MimePart();
				$oNewMimePart->_subParts = new MimePartCollection($oNewMimePart);
				$oNewMimePart->_sourceCharset = $GLOBALS[MailOutputCharset];

				$sBoundary = '--=_NextPart_'.md5(rand(100000, 999999));
				$oNewMimePart->Headers->SetHeaderByName(MIMEConst_ContentType,
					MIMETypeConst_MultipartMixed.';'.CRLF."\t".MIMEConst_BoundaryLower.'="'.$sBoundary.'"');

				$oNewMimePart->_subParts->Add($oPart);

				$attachs =& $this->Attachments;
				foreach ($attachs->Instance() as $att)
				{
					if ($att && !$att->IsInline)
					{
						$oNewMimePart->_subParts->Add($att->MimePart);
					}
					unset($att);
				}

				return $oNewMimePart;
			}

			return $oPart;
		}

		/**
		 * @param MimePart $oPart
		 */
		function InitNewMessageHeaders(&$oPart)
		{
			if ($oPart)
			{
				$aNewHeaders =& $oPart->Headers->Instance();
				foreach ($aNewHeaders as $oHeader)
				{
					$this->Headers->SetHeader($oHeader);
					unset($oHeader);
				}

				$oPart->Headers = $this->Headers;
			}
		}

		/**
		 * @return string
		 */
		function ToMailString($bWithoutBcc = false)
		{
			$oMimePart = $this->CreateNewMessageAlternativeBody();
			$oMimePart = $this->CreateNewMessageRelatedBody($oMimePart);
			$oMimePart = $this->CreateNewMessageMixedBody($oMimePart);

			$this->InitNewMessageHeaders($oMimePart);
			return $oMimePart->ToString($bWithoutBcc);
		}

		/**
		 * @return bool
		 */
		function NeedToUpdateHeader()
		{
			if ($this->GetContentTypeCharset())
			{
				return false;
			}

			if ($GLOBALS[MailInputCharset] == $GLOBALS[MailDefaultCharset])
			{
				return false;
			}

			return true;
		}

		/**
		 * @return string
		 */
		function TryToGetOriginalMailMessage()
		{
			if (strlen($this->OriginalMailMessage) > 0)
			{
				return $this->OriginalMailMessage;
			}
			else
			{
				return $this->ToString();
			}
		}
	}
