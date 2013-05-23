<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	define('MailDefaultCharset', 'MailDefaultCharset');
	define('MailDefaultOriginalCharset', 'MailDefaultOriginalCharset');
	define('MailOutputCharset', 'MailOutputCharset');
	define('MailInputCharset', 'MailInputCharset');

	defined('CRLF') || define('CRLF', "\r\n");

	define('MIMETypeConst_TextPlain', 'text/plain');
	define('MIMETypeConst_TextHtml', 'text/html');
	define('MIMETypeConst_MultipartAlternative', 'multipart/alternative');
	define('MIMETypeConst_MultipartRelated', 'multipart/related');
	define('MIMETypeConst_MultipartMixed', 'multipart/mixed');
	define('MIMETypeConst_MultipartSigned', 'multipart/signed');
	define('MIMETypeConst_MessagePartial', 'message/partial');
	define('MIMETypeConst_MessageRfc822', 'message/rfc822');
	define('MIMETypeConst_MessageReport', 'multipart/report');

	define('MIMETypeConst_ApplicationMsTnef', 'application/ms-tnef');
	define('MIMETypeConst_ApplicationPkcs7Mime', 'application/pkcs7-mime');
	define('MIMETypeConst_ApplicationPkcs7Signature', 'application/pkcs7-signature');

	define('MIMEConst_Bcc', 'Bcc');
	define('MIMEConst_BccLower', 'bcc');

	define('MIMEConst_Cc', 'Cc');
	define('MIMEConst_CcLower', 'cc');

	define('MIMEConst_ContentDescription', 'Content-Description');
	define('MIMEConst_ContentDescriptionLower', 'content-description');

	define('MIMEConst_ContentDisposition', 'Content-Disposition');
	define('MIMEConst_ContentDispositionLower', 'content-disposition');

	define('MIMEConst_ContentID', 'Content-ID');
	define('MIMEConst_ContentIDLower', 'content-id');

	define('MIMEConst_ContentLocation', 'Content-Location');
	define('MIMEConst_ContentLocationLower', 'content-location');

	define('MIMEConst_ContentTransferEncoding', 'Content-Transfer-Encoding');
	define('MIMEConst_ContentTransferEncodingLower', 'content-transfer-encoding');

	define('MIMEConst_ContentType', 'Content-Type');
	define('MIMEConst_ContentTypeLower', 'content-type');

	define('MIMEConst_Date', 'Date');
	define('MIMEConst_DateLower', 'date');

	define('MIMEConst_Description', 'Description');
	define('MIMEConst_DescriptionLower', 'description');

	define('MIMEConst_DispositionNotificationTo', 'Disposition-Notification-To');
	define('MIMEConst_DispositionNotificationToLower', 'disposition-notification-to');

	define('MIMEConst_XConfirmReadingTo', 'X-Confirm-Reading-To');

	define('MIMEConst_From', 'From');
	define('MIMEConst_FromLower', 'from');

	define('MIMEConst_Importance', 'Importance');
	define('MIMEConst_ImportanceLower', 'importance');

	define('MIMEConst_MessageID', 'Message-ID');
	define('MIMEConst_MessageIDLower', 'message-id');

	define('MIMEConst_MimeVersion', 'MIME-Version');
	define('MIMEConst_MimeVersionLower', 'mime-version');

	define('MIMEConst_Organization', 'Organization');
	define('MIMEConst_OrganizationLower', 'organization');

	define('MIMEConst_XMSMailPriority', 'X-MSMail-Priority');
	define('MIMEConst_XMSMailPriorityLower', 'x-msmail-priority');

	define('MIMEConst_Received', 'Received');
	define('MIMEConst_ReceivedLower', 'received');

	define('MIMEConst_References', 'References');
	define('MIMEConst_ReferencesLower', 'references');

	define('MIMEConst_ReplyTo', 'Reply-To');
	define('MIMEConst_ReplyToLower', 'reply-to');

	define('MIMEConst_ReturnPath', 'Return-Path');
	define('MIMEConst_ReturnPathLower', 'return-path');

	define('MIMEConst_ReturnReceiptTo', 'Return-Receipt-To');
	define('MIMEConst_ReturnReceiptToLower', 'return-receipt-to');

	define('MIMEConst_Sensitivity', 'Sensitivity');
	define('MIMEConst_SensitivityLower', 'sensitivity');

	define('MIMEConst_Subject', 'Subject');
	define('MIMEConst_SubjectLower', 'subject');

	define('MIMEConst_To', 'To');
	define('MIMEConst_ToLower', 'to');

	define('MIMEConst_XMailer', 'X-Mailer');
	define('MIMEConst_XMailerLower', 'x-mailer');

	define('MIMEConst_XPriority', 'X-Priority');
	define('MIMEConst_XPriorityLower', 'x-priority');

	define('MIMEConst_XSpam', 'X-Spam');
	define('MIMEConst_XSpamLower', 'x-spam');

	define('MIMEConst_XBogosityLower', 'x-bogosity');
	define('MIMEConst_XSpamFlagLower', 'x-spam-flag');
	define('MIMEConst_XSpamHeaderLower', 'x-spam-header');

	define('MIMEConst_XVirusHeaderLower', 'x-virus-header');

	define('MIMEConst_AttachmentLower', 'attachment');
	define('MIMEConst_BoundaryLower', 'boundary');
	define('MIMEConst_CharsetLower', 'charset');
	define('MIMEConst_FilenameLower', 'filename');
	define('MIMEConst_InlineLower', 'inline');
	define('MIMEConst_MessageLower', 'message');
	define('MIMEConst_NameLower', 'name');
	define('MIMEConst_SmimeP7sLower', 'smime.p7s');
	define('MIMEConst_SmimeP7mLower', 'smime.p7m');

	define('MIMEConst_QuotedPrintable', 'Quoted-Printable');
	define('MIMEConst_QuotedPrintableLower', 'quoted-printable');
	define('MIMEConst_QuotedPrintableShort', 'Q');

	define('MIMEConst_Base64', 'Base64');
	define('MIMEConst_Base64Lower', 'base64');
	define('MIMEConst_Base64Short', 'B');

	define('MIMEConst_7bit', '7bit');
	define('MIMEConst_8bit', '8bit');

	define('MIMEConst_DefaultQB', MIMEConst_QuotedPrintableShort);
	//define('MIMEConst_DefaultQB', MIMEConst_Base64Short);

	define('MIME_SENSIVITY_NOTHING', 0);
	define('MIME_SENSIVITY_CONFIDENTIAL', 1);
	define('MIME_SENSIVITY_PRIVATE', 2);
	define('MIME_SENSIVITY_PERSONAL', 3);

	define('MIMEConst_LineLengthLimit', 72);
	define('MIMEConst_XOriginatingIp', 'X-Originating-IP');

	define('MIMEConst_DoNotUseMTrim', 'WmDoNotUseMTrim');
	define('MIMEConst_TrimBodyLen_Bytes', 150000);
	define('MIMEConst_IsBodyTrim', 'WMmimeIsBodyTrim');
