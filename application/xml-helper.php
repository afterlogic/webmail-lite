<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

require_once WM_ROOTPATH.'common/class_filters.php';
require_once WM_ROOTPATH.'common/class_webmailmessages.php';

class CAppXmlHelper
{
	/**
	 * @param CXmlDomNode $oAccountNode
	 * @param CAccount $oAccount
	 * @param CDomain $oDomain
	 * @param bool $bIsUpdate = false
	 */
	public static function PopulateAccount($oAccountNode, &$oAccount, $oDomain, $bIsUpdate = false)
	{
		CApi::Plugin()->RunHook('webmail-populate-account-pre-call', array(&$oAccountNode, &$oAccount, &$oDomain, $bIsUpdate));

		if ($bIsUpdate && !$oDomain->AllowUsersChangeEmailSettings)
		{
			$oAccount->FriendlyName = $oAccountNode->GetChildValueByTagName('friendly_nm');
		}
		else
		{
			$oAccount->FriendlyName = $oAccountNode->GetChildValueByTagName('friendly_nm');

			if (!$oAccount->IsInternal)
			{
				if (isset($oAccountNode->Attributes['mail_protocol'],
					$oAccountNode->Attributes['mail_inc_port'], $oAccountNode->Attributes['mail_out_port'],
					$oAccountNode->Attributes['mail_out_auth'],	$oAccountNode->Attributes['mails_on_server_days'],
					$oAccountNode->Attributes['mail_mode'],	$oAccountNode->Attributes['getmail_at_login']))
				{
					$oAccount->IncomingMailPort = $oAccountNode->Attributes['mail_inc_port'];
					$oAccount->OutgoingMailPort = $oAccountNode->Attributes['mail_out_port'];
					$oAccount->OutgoingMailAuth = ((bool) $oAccountNode->Attributes['mail_out_auth'])
						? ESMTPAuthType::AuthCurrentUser : ESMTPAuthType::NoAuth;
					$oAccount->MailsOnServerDays = $oAccountNode->Attributes['mails_on_server_days'];
					$oAccount->MailMode = $oAccountNode->Attributes['mail_mode'];
					$oAccount->GetMailAtLogin = (bool) $oAccountNode->Attributes['getmail_at_login'];

					$oAccount->IncomingMailServer = $oAccountNode->GetChildValueByTagName('mail_inc_host');
					$oAccount->IncomingMailLogin = $oAccountNode->GetChildValueByTagName('mail_inc_login');

					$oAccount->OutgoingMailServer = $oAccountNode->GetChildValueByTagName('mail_out_host');

					if (!$bIsUpdate)
					{
						$oAccount->IncomingMailProtocol = $oAccountNode->Attributes['mail_protocol'];

						$sIncomingMailPassword = (string) $oAccountNode->GetChildValueByTagName('mail_inc_pass');
						if (APP_DUMMYPASSWORD !== $sIncomingMailPassword)
						{
							$oAccount->IncomingMailPassword = $sIncomingMailPassword;
						}

						$oAccount->OutgoingMailLogin = $oAccountNode->GetChildValueByTagName('mail_out_login');
						$sOutgoingMailPassword = $oAccountNode->GetChildValueByTagName('mail_out_pass');
						if (APP_DUMMYPASSWORD !== $sOutgoingMailPassword)
						{
							$oAccount->OutgoingMailPassword = $sOutgoingMailPassword;
						}
					}
				}
			}
		}

		if ($bIsUpdate || ($bIsUpdate && $oAccount->IsEnabledExtension(CAccount::ChangePasswordExtension)))
		{
			$sIncomingMailPassword = (string) $oAccountNode->GetChildValueByTagName('mail_inc_pass');
			if (!empty($sIncomingMailPassword))
			{
				$oAccount->PreviousMailPassword = $oAccount->IncomingMailPassword;
				$oAccount->IncomingMailPassword = $sIncomingMailPassword;
			}
		}

		if (!$bIsUpdate)
		{
			$oAccount->Email = $oAccountNode->GetChildValueByTagName('email');
		}

		CApi::Plugin()->RunHook('webmail-populate-account-post-call', array(&$oAccountNode, &$oAccount, &$oDomain, $bIsUpdate));
	}

		/**
	 * @param CXmlDocument $oResultXml
	 * @param CAccount $oAccount
	 * @return CContact
	 */
	public static function GetContact($oRequestXml, $oAccount)
	{
		$oContact = new CContact();

		$oContactNode =& $oRequestXml->XmlRoot->GetChildNodeByTagName('contact');

		$oContact->IdUser = $oAccount->IdUser;
		if (isset($oContactNode->Attributes['id']))
		{
			$oContact->IdContact = $oContactNode->Attributes['id'];
		}

		if (isset($oContactNode->Attributes['etag']))
		{
			$oContact->ETag = $oContactNode->Attributes['etag'];
		}

		$oContact->PrimaryEmail = $oContactNode->GetAttribute('primary_email', $oContact->PrimaryEmail);
		$oContact->UseFriendlyName = (bool) $oContactNode->GetAttribute('use_friendly_nm', $oContact->UseFriendlyName);

		$oContact->Title = $oContactNode->GetChildValueByTagName('title', true);
		$oContact->FullName = $oContactNode->GetChildValueByTagName('fullname', true);
		$oContact->FirstName = $oContactNode->GetChildValueByTagName('firstname', true);
		$oContact->LastName = $oContactNode->GetChildValueByTagName('lastname', true);
		$oContact->NickName = $oContactNode->GetChildValueByTagName('nickname', true);

		$oPersonalNode =& $oContactNode->GetChildNodeByTagName('personal');
		$oContact->HomeEmail = $oPersonalNode->GetChildValueByTagName('email', true);
		$oContact->HomeStreet = $oPersonalNode->GetChildValueByTagName('street', true);
		$oContact->HomeCity = $oPersonalNode->GetChildValueByTagName('city', true);
		$oContact->HomeState = $oPersonalNode->GetChildValueByTagName('state', true);
		$oContact->HomeZip = $oPersonalNode->GetChildValueByTagName('zip', true);
		$oContact->HomeCountry = $oPersonalNode->GetChildValueByTagName('country', true);
		$oContact->HomeFax = $oPersonalNode->GetChildValueByTagName('fax', true);
		$oContact->HomePhone = $oPersonalNode->GetChildValueByTagName('phone', true);
		$oContact->HomeMobile = $oPersonalNode->GetChildValueByTagName('mobile', true);
		$oContact->HomeWeb = $oPersonalNode->GetChildValueByTagName('web', true);

		$oBusinessNode =& $oContactNode->GetChildNodeByTagName('business', true);

		$oContact->BusinessEmail = $oBusinessNode->GetChildValueByTagName('email', true);
		$oContact->BusinessCompany = $oBusinessNode->GetChildValueByTagName('company', true);
		$oContact->BusinessJobTitle = $oBusinessNode->GetChildValueByTagName('job_title', true);
		$oContact->BusinessDepartment = $oBusinessNode->GetChildValueByTagName('department', true);
		$oContact->BusinessOffice = $oBusinessNode->GetChildValueByTagName('office', true);
		$oContact->BusinessStreet = $oBusinessNode->GetChildValueByTagName('street', true);
		$oContact->BusinessCity = $oBusinessNode->GetChildValueByTagName('city', true);
		$oContact->BusinessState = $oBusinessNode->GetChildValueByTagName('state', true);
		$oContact->BusinessZip = $oBusinessNode->GetChildValueByTagName('zip', true);
		$oContact->BusinessCountry = $oBusinessNode->GetChildValueByTagName('country', true);
		$oContact->BusinessFax = $oBusinessNode->GetChildValueByTagName('fax', true);
		$oContact->BusinessPhone = $oBusinessNode->GetChildValueByTagName('phone', true);
		$oContact->BusinessMobile = $oBusinessNode->GetChildValueByTagName('modile', true);
		$oContact->BusinessWeb = $oBusinessNode->GetChildValueByTagName('web', true);

		$oOtherNode =& $oContactNode->GetChildNodeByTagName('other', true);
		$oContact->OtherEmail = $oOtherNode->GetChildValueByTagName('email', true);
		$oContact->Notes = $oOtherNode->GetChildValueByTagName('notes', true);

		$oBirthdayNode =& $oContactNode->GetChildNodeByTagName('birthday');
		if (isset($oBirthdayNode->Attributes['day'], $oBirthdayNode->Attributes['month'], $oBirthdayNode->Attributes['year']))
		{
			$oContact->BirthdayDay = $oBirthdayNode->Attributes['day'];
			$oContact->BirthdayMonth = $oBirthdayNode->Attributes['month'];
			$oContact->BirthdayYear = $oBirthdayNode->Attributes['year'];
		}

		$oGroupsNode =& $oContactNode->GetChildNodeByTagName('groups');

		$mKey = null;
		$aGroupsIds = array();
		$aGroupsKeys = array_keys($oGroupsNode->Children);
		foreach ($aGroupsKeys as $mKey)
		{
			$oGroupNode =& $oGroupsNode->Children[$mKey];
			if (isset($oGroupNode->Attributes['id']))
			{
				$aGroupsIds[] = $oGroupNode->Attributes['id'];
			}
			unset($oGroupNode);
		}
		$oContact->GroupsIds = $aGroupsIds;

		return $oContact;
	}

	/**
	 * @param CXmlDomNode $oMessageNode
	 * @param CAccount $oAccount
	 * @param CAccount $oFromAccount
	 * @param CIdentity $oFromIdentity = null
	 * @return WebMailMessage
	 */
	public static function GetMessage($oMessageNode, $oAccount, $oFromAccount, $oFromIdentity = null)
	{
		$sHeadersNode =& $oMessageNode->GetChildNodeByTagName('headers');

		$oMessage = new WebMailMessage();
		$GLOBALS[MailDefaultCharset] = CPAGE_UTF8;
		$GLOBALS[MailInputCharset] = CPAGE_UTF8;
		$GLOBALS[MailOutputCharset] = APP_DEFAULT_OUTPUT_CHARSET;

		$oMessage->Headers->SetHeaderByName(MIMEConst_MimeVersion, '1.0');
		$oMessage->Headers->SetHeaderByName(MIMEConst_XMailer, CApi::GetConf('webmail.xmailer-value', 'PHP'));

		$sIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
		if (null !== $sIp)
		{
			$oMessage->Headers->SetHeaderByName(MIMEConst_XOriginatingIp, $sIp);
		}

		$oMessage->IdMsg = $oMessageNode->GetAttribute('id', -1);
		$oMessage->SetPriority($oMessageNode->GetAttribute('priority', 3));
		$oMessage->SetSensivity($oMessageNode->GetAttribute('sensivity', MIME_SENSIVITY_NOTHING));

		$oMessage->Uid = $oMessageNode->GetChildValueByTagName('uid');

		$sServerAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['SERVER_NAME'] : 'cantgetservername';
		$oMessage->Headers->SetHeaderByName(MIMEConst_MessageID,
			'<'.substr(md5(rand(1000, 9999)), 0, 7).'.'.md5(time()).'@'. $sServerAddr .'>');

		$sFromEmail = ($oFromIdentity) ? $oFromIdentity->GetFriendlyEmail() : $oFromAccount->GetFriendlyEmail();

		/* custom class */
//		wm_Custom::StaticUseMethod('ChangeAccountEmailToFake', array(&$sFromEmail));
		$oMessage->SetFromAsString(ConvertUtils::WMBackHtmlSpecialChars($sFromEmail));

		$mTemp = $sHeadersNode->GetChildValueByTagName('to');
		if ($mTemp)
		{
			$oMessage->SetToAsString(ConvertUtils::WMBackHtmlSpecialChars($mTemp));
		}
		$mTemp = $sHeadersNode->GetChildValueByTagName('cc');
		if ($mTemp)
		{
			$oMessage->SetCcAsString(ConvertUtils::WMBackHtmlSpecialChars($mTemp));
		}
		$mTemp = $sHeadersNode->GetChildValueByTagName('bcc');
		if ($mTemp)
		{
			$oMessage->SetBccAsString(ConvertUtils::WMBackHtmlSpecialChars($mTemp));
		}
		$mTemp = $sHeadersNode->GetChildValueByTagName('mailconfirmation');
		if ($mTemp)
		{
			/* custom class */
//			wm_Custom::StaticUseMethod('ChangeAccountEmailToFake', array(&$mTemp));
			$oMessage->SetReadMailConfirmationAsString(ConvertUtils::WMBackHtmlSpecialChars($mTemp));
		}

		$oMessage->SetSubject(ConvertUtils::WMBackHtmlSpecialChars($sHeadersNode->GetChildValueByTagName('subject')));

		$oMessage->SetDate(new CDateTime(time()));

		$oBodyNode = null;
		$oBodyNode =& $oMessageNode->GetChildNodeByTagName('body');
		$bIsPlainMessage = !(isset($oBodyNode->Attributes['is_html']) && $oBodyNode->Attributes['is_html']);

		if (!$bIsPlainMessage)
		{
			$oMessage->TextBodies->HtmlTextBodyPart =
				ConvertUtils::AddHtmlTagToHtmlBody(
					str_replace("\n", CRLF,
					str_replace("\r", '',
						ConvertUtils::BackImagesToHtmlBody(
							ConvertUtils::WMBackHtmlNewCode($oBodyNode->Value)))));

			$oMessage->TextBodies->PlainTextBodyPart =
				str_replace("\n", CRLF,
				str_replace("\r", '',
					$oMessage->TextBodies->HtmlToPlain()));
		}
		else
		{
			$oMessage->TextBodies->PlainTextBodyPart =
				str_replace("\n", CRLF,
				str_replace("\r", '',
					ConvertUtils::WMBackHtmlNewCode($oBodyNode->Value)));
		}

		$oAttachmentsNode =& $oMessageNode->GetChildNodeByTagName('attachments');

		if ($oAttachmentsNode != null)
		{
			$sRequestUri = api_Utils::RequestUri();
			$sWebUrl =
				(isset($_SERVER['HTTPS']) && 'on' === strtolower($_SERVER['HTTPS']) ? 'https' : 'http')
				.'://'.$_SERVER['HTTP_HOST']
				.strtolower(substr($sRequestUri, 0, strrpos($sRequestUri, '/')))
				.'/';

			$oTempFiles =& CTempFiles::CreateInstance($oAccount);

			$sKey = null;
			$aAttachmentsKeys = array_keys($oAttachmentsNode->Children);
			foreach ($aAttachmentsKeys as $sKey)
			{
				$oAttachmentNode =& $oAttachmentsNode->Children[$sKey];

				$bIsInline = (bool) $oAttachmentNode->GetAttribute('inline', false);

				if ($bIsPlainMessage && $bIsInline)
				{
					CApi::Log('Skip inline attachment for plain message');
					continue;
				}

				$sTempName = $oAttachmentNode->GetChildValueByTagName('temp_name');
				$sFileName = $oAttachmentNode->GetChildValueByTagName('name');

				$sAttachmentHtmlUrl = 'attach.php?img&amp;tn='.urlencode($sTempName).'&amp;filename='.urlencode($sFileName);
				$sReplaceCid = md5(time().$sFileName);

				$sMimeType = $oAttachmentNode->GetChildValueByTagName('mime_type');
				if (empty($sMimeType))
				{
					$sMimeType = ConvertUtils::GetContentTypeFromFileName($sFileName);
				}

				if (!$oMessage->Attachments->AddFromBinaryBody($oTempFiles->LoadFile(
					$oAttachmentNode->GetChildValueByTagName('temp_name')),
					$sFileName, $sMimeType, $bIsInline))
				{
					CApi::Log('Error Add tempfile in message: '.getGlobalError(), ELogLevel::Error);
				}

				if (isset($oBodyNode->Attributes['is_html']) && $oBodyNode->Attributes['is_html'])
				{
					if (strpos($oMessage->TextBodies->HtmlTextBodyPart, $sAttachmentHtmlUrl) !== false)
					{
						$oAttachment =& $oMessage->Attachments->GetLast();
						if ($oAttachment)
						{
							$oAttachment->MimePart->Headers->SetHeaderByName(MIMEConst_ContentID, '<'.$sReplaceCid.'>');
							$oMessage->TextBodies->HtmlTextBodyPart = str_replace($sWebUrl.$sAttachmentHtmlUrl, 'cid:'.$sReplaceCid, $oMessage->TextBodies->HtmlTextBodyPart);
							$oMessage->TextBodies->HtmlTextBodyPart = str_replace($sAttachmentHtmlUrl, 'cid:'.$sReplaceCid, $oMessage->TextBodies->HtmlTextBodyPart);

							$sAttachmentName = ConvertUtils::EncodeHeaderString($oAttachmentNode->GetChildValueByTagName('name'), CPAGE_UTF8, $GLOBALS[MailOutputCharset]);
							$oAttachment->MimePart->Headers->SetHeaderByName(MIMEConst_ContentDisposition, MIMEConst_InlineLower.';'.CRLF."\t".MIMEConst_FilenameLower.'="'.$sAttachmentName.'"', false);
						}
						unset($oAttachment);
					}
					else if ($bIsInline)
					{
						$oMessage->Attachments->DeleteLast();
					}
				}
				unset($oAttachmentNode);
			}
		}

		return $oMessage;
	}

	/**
	 * @param CXmlDomNode $oMessageNode
	 * @param MailProcessor $oMailProcessor
	 */
	public static function ReplySetFlag($oMessageNode, $oMailProcessor)
	{
		$oReplyNode =& $oMessageNode->GetChildNodeByTagName('reply_message');
		if ($oReplyNode && isset($oReplyNode->Attributes['action']))
		{
			$iReplyFlag = null;
			switch ($oReplyNode->Attributes['action'])
			{
				case 'reply':
					$iReplyFlag = MESSAGEFLAGS_Answered;
					break;
				case 'forward':
					$iReplyFlag = MESSAGEFLAGS_Forwarded;
					break;
			}

			if (null !== $iReplyFlag && isset($oReplyNode->Attributes['id']))
			{
				$iReplyMsgId = (int) $oReplyNode->Attributes['id'];
				$mReplyMsgUid = $oReplyNode->GetChildValueByTagName('uid', true);
				$oReplyMsgFolderNode =& $oReplyNode->GetChildNodeByTagName('folder');
				if ($oReplyMsgFolderNode && isset($oReplyMsgFolderNode->Attributes['id']))
				{
					$iReplyFolderId = (int) $oReplyMsgFolderNode->Attributes['id'];
					$sReplyFolderFullName = $oReplyMsgFolderNode->GetChildValueByTagName('full_name', true);

					$oMailProcessor->SetFlagFromReply($iReplyMsgId, $mReplyMsgUid, $iReplyFolderId, $sReplyFolderFullName, $iReplyFlag);
				}
			}
		}
	}

	/**
	 * @param CXmlDocument $oRequestXml
	 * @param CXmlDocument &$oResultXml
	 * @param CAccount $oAccount
	 * @return Filter
	 */
	public static function GetConfirmationMessage($oRequestXml, &$oResultXml, $oAccount)
	{
		$oMessage = null;
		$sConfirmation = $oRequestXml->XmlRoot->GetChildValueByTagName('confirmation');
		if ($sConfirmation && 0 < strlen($sConfirmation) && $oAccount)
		{
			$oMessage = new WebMailMessage();
			$GLOBALS[MailDefaultCharset] = CPAGE_UTF8;
			$GLOBALS[MailInputCharset] = CPAGE_UTF8;
			$GLOBALS[MailOutputCharset] = APP_DEFAULT_OUTPUT_CHARSET;

			$oMessage->Headers->SetHeaderByName(MIMEConst_MimeVersion, '1.0');
			$oMessage->Headers->SetHeaderByName(MIMEConst_XMailer, CApi::GetConf('webmail.xmailer-value', 'PHP'));

			$sIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
			if (null !== $sIp)
			{
				$oMessage->Headers->SetHeaderByName(MIMEConst_XOriginatingIp, $sIp);
			}

			$sServerAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['SERVER_NAME'] : 'cantgetservername';
			$oMessage->Headers->SetHeaderByName(MIMEConst_MessageID,
				'<'.substr(md5(rand(1000, 9999)), 0, 7).'.'.md5(time()).'@'. $sServerAddr .'>');

			$emailAccount = $oAccount->Email;

			/* custom class */
//			wm_Custom::StaticUseMethod('ChangeAccountEmailToFake', array(&$emailAccount));

			$oMessage->SetFromAsString(ConvertUtils::WMBackHtmlSpecialChars($emailAccount));
			$oMessage->SetToAsString(ConvertUtils::WMBackHtmlSpecialChars($sConfirmation));
			$oMessage->SetSubject(ConvertUtils::WMBackHtmlSpecialChars(ReturnReceiptSubject));
			$oMessage->SetDate(new CDateTime(time()));

			$sConfSubject = $oRequestXml->XmlRoot->GetChildValueByTagName('subject');

			$bodyText = ReturnReceiptMailText1.' '.$oAccount->Email.' '.ReturnReceiptMailText3.' "'.$sConfSubject."\".\r\n\r\n".ReturnReceiptMailText2;

			$oMessage->TextBodies->PlainTextBodyPart =
					str_replace("\n", CRLF, str_replace("\r", '', ConvertUtils::WMBackHtmlNewCode($bodyText)));
		}

		return $oMessage;
	}

	/**
	 * @param CXmlDomNode $oFilterNode
	 * @return Filter
	 */
	public static function GetFilter($oFilterNode)
	{
		$oFilter = null;
		if ($oFilterNode && isset($oFilterNode->Attributes['field'],
			$oFilterNode->Attributes['condition'], $oFilterNode->Attributes['action'],
			$oFilterNode->Attributes['id_folder']))
		{
			$oFilter = new Filter();
			$oFilter->Id = (isset($oFilterNode->Attributes['id'])) ? $oFilterNode->Attributes['id'] : $oFilter->Id;
			$oFilter->Field = $oFilterNode->Attributes['field'];
			$oFilter->Condition = $oFilterNode->Attributes['condition'];
			$oFilter->Action = $oFilterNode->Attributes['action'];
			$oFilter->IdFolder = $oFilterNode->Attributes['id_folder'];
			$oFilter->Applied = (isset($oFilterNode->Attributes['applied']) && 1 === (int) $oFilterNode->Attributes['applied']);
			$oFilter->Filter = api_Utils::DecodeSpecialXmlChars($oFilterNode->Value);
		}

		return $oFilter;
	}

	/**
	 * @param CXmlDomNode $oIdentityNode
	 * @return CIdentity
	 */
	public static function GetIdentity($oIdentityNode, $bIsNew = false)
	{
		$oIdentity = null;

		if ($oIdentityNode && isset($oIdentityNode->Attributes['id'], $oIdentityNode->Attributes['id_acct'],
			$oIdentityNode->Attributes['html_signature'], $oIdentityNode->Attributes['use_signature']))
		{
			$oIdentityEmailNode =& $oIdentityNode->GetChildNodeByTagName('email');
			$oIdentityNameNode =& $oIdentityNode->GetChildNodeByTagName('name');
			$oIdentitySignatureNode =& $oIdentityNode->GetChildNodeByTagName('signature');

			if ($oIdentityEmailNode && $oIdentityNameNode && $oIdentitySignatureNode)
			{
				$oIdentity = new CIdentity();
				$oIdentity->IdIdentity = (int) $oIdentityNode->Attributes['id'];
				$oIdentity->IdAccount = (int) $oIdentityNode->Attributes['id_acct'];
				$oIdentity->Virtual = (bool) ($bIsNew) ? false : -1 === $oIdentity->IdIdentity;
				$oIdentity->Email = api_Utils::DecodeSpecialXmlChars($oIdentityEmailNode->Value);
				$oIdentity->FriendlyName = api_Utils::DecodeSpecialXmlChars($oIdentityNameNode->Value);
				$oIdentity->UseSignature = ('1' === ((string) $oIdentityNode->Attributes['use_signature']));
				$oIdentity->Signature = api_Utils::DecodeSpecialXmlChars($oIdentitySignatureNode->Value);
				$oIdentity->SignatureType = ('1' === ((string) $oIdentityNode->Attributes['html_signature']))
					? EAccountSignatureType::Html : EAccountSignatureType::Plain;
			}
		}

		return $oIdentity;
	}
}