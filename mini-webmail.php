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
	require_once WM_ROOTPATH.'common/class_actionfilters.php';
	require_once(WM_ROOTPATH.'common/class_filesystem.php');

	$iAccountId = CSession::Get(APP_SESSION_ACCOUNT_ID, null);

	$openMode = $oInput->GetQuery('open_mode', 'new');
	$bHasError = $openMode === 'login_error' || $openMode === 'message_error';

	if (null === $iAccountId && !$bHasError)
	{
		header('Location: index.php?error=1');
		exit();
	}

	$replyType = $oInput->GetQuery('reply_type', 4);
	$replyText = $oInput->GetQuery('reply_text', '');

	/* @var $oApiWebmailManager CApiWebmailManager */
	$oApiWebmailManager = CApi::Manager('webmail');

	/* @var $oApiUsersManager CApiUsersManager */
	$oApiUsersManager = CApi::Manager('users');

	$iNewAccountId = (int) $oInput->GetQuery('nacct', -1);
	if (0 < $iNewAccountId && USE_DB)
	{
		$aUsersIds = $oApiUsersManager->GetUserIdList(CSession::Get(APP_SESSION_USER_ID, -1));
		if (in_array($iNewAccountId, $aUsersIds))
		{
			CSession::Set(APP_SESSION_ACCOUNT_ID, $iNewAccountId);
			if (null !== $oInput->GetCookie('awm_autologin_id', null))
			{
				@setcookie('awm_autologin_subid', $iNewAccountId, time() + 360010);
			}
		}
		else
		{
			header('Location: index.php?error=2');
			exit();
		}
	}

	$iAccountId = CSession::Get(APP_SESSION_ACCOUNT_ID, null);
	$oAccount = AppGetAccount($iAccountId);
	if (!$oAccount)
	{
		header('Location: index.php?error=2');
		exit();
	}

	AppIncludeLanguage($oAccount->User->DefaultLanguage);

	$oSettings =& CApi::GetSettings();

	define('defaultTitle', AppGetSiteName($oAccount));
	$title = defaultTitle;

	define('defaultSkin', $oAccount->User->DefaultSkin);

	$_rtl = in_array($oAccount->User->DefaultLanguage, CApi::GetConf('webmail.rtl-langs', array()));
	$_style = ($_rtl) ? '<link rel="stylesheet" href="skins/'.defaultSkin.'/styles-rtl.css" type="text/css" id="skin-rtl">' : '';
	$_js_rtl = ($_rtl) ? 'var RTL = true;' : '';
	$bVoice = false;

	define('JS_VERS', ConvertUtils::GetJsVersion());

	header('Content-type: text/html; charset=utf-8');
	header('Content-script-type: text/javascript');
	header('Pragma: cache');
	header('Cache-control: public');

	$message = null;

	if ($openMode == 'view' || $openMode == 'reply')
	{
		require_once(WM_ROOTPATH.'common/class_mailprocessor.php');

		$mes_id = isset($_GET['msg_id']) ? (int) $_GET['msg_id'] : -1;
		$mes_uid = isset($_GET['msg_uid']) ? $_GET['msg_uid'] : '';
		$folder_id = isset($_GET['folder_id']) ? (int) $_GET['folder_id'] : -1;
		$folder_name = isset($_GET['folder_full_name']) ? $_GET['folder_full_name'] : '';
		$mes_charset = isset($_GET['charset']) ? (int) $_GET['charset'] : -1;
		$msgSize = isset($_GET['size']) ? (int) $_GET['size'] : 0;
		$mode = isset($_GET['mode']) ? (int) $_GET['mode'] : 0;

		if ($mes_charset > 0)
		{
			$GLOBALS[MailInputCharset] = ConvertUtils::GetCodePageName($mes_charset);
		}

		$processor = new MailProcessor($oAccount);

		$folder = null;
		if (!empty($folder_id) && !empty($folder_name))
		{
			$folder = new Folder($oAccount->IdAccount, $folder_id, $folder_name);
			$processor->GetFolderInfo($folder);

			if (!$folder || $folder->IdDb < 1)
			{
				///!!!! TODO
			}
		}
		else
		{
			///!!!! TODO
		}

		$msgIdUid = array($mes_id => $mes_uid);

		$_messageInfo = new CMessageInfo();
		$_messageInfo->SetInfo($mes_id, $mes_uid, $folder->IdDb, $folder->FullName);

		$modeForGet = $mode;
		if (empty($msgSize) || (int) $msgSize < CApi::GetConf('webmail.bodystructure-message-size-limit', 20000) ||	// size
				($folder && FOLDERTYPE_Drafts == $folder->Type) ||				// draft
				(($mode & 8) == 8 || ($mode & 16) == 16 ||						// forward
					($mode & 32) == 32 || ($mode & 64) == 64))
		{
			$modeForGet = null;
		}


		$message =& $processor->GetMessage($mes_id, $mes_uid, $folder, $modeForGet);

		if (null != $message)
		{
			$bVoice = $message->IsVoiceMessage();

			if (($message->Flags & MESSAGEFLAGS_Seen) != MESSAGEFLAGS_Seen)
			{
				$processor->SetFlag($msgIdUid, $folder, MESSAGEFLAGS_Seen, ACTION_Set);
			}

			$_isFromSave = false;
			if (USE_DB && ($modeForGet === null || (($modeForGet & 1) == 1)))
			{
				$_fromObj = new EmailAddress();
				$_fromObj->Parse($message->GetFromAsString(true));

				if ($_fromObj->Email)
				{
					$_isFromSave = $processor->DbStorage->SelectSenderSafetyByEmail($_fromObj->Email, $oAccount->IdUser);
				}

				if ($folder->SyncType != FOLDERSYNC_DirectMode && $processor->DbStorage->Connect())
				{
					$processor->DbStorage->UpdateMessageCharset($mes_id, $mes_charset, $message);
				}
			}

			$textCharset = $message->GetTextCharset();
			$isRTL = false;
			if (null !== $textCharset)
			{
				switch (ConvertUtils::GetCodePageNumber($textCharset))
				{
					case 1255:
					case 1256:
					case 28596:
					case 28598:
						$isRTL = true;
						break;
				}
			}

			$accountOffset = $oAccount->GetDefaultTimeOffset();

			$date = $message->GetDate();
			$date->FormatString = $oAccount->User->DefaultDateFormat;
			$date->TimeFormat = $oAccount->User->DefaultTimeFormat;

			$from4search =& $message->GetFrom();

			$safety = true;
			$bHasCharset = false;
			$HtmlBody = '';
			$PlainBody = '';

			$_messageClassType = $message->TextBodies->ClassType();

			if (($mode & 2) == 2 && ($_messageClassType & 2) == 2)
			{
				$HtmlBody = ConvertUtils::ReplaceJSMethod(
					$message->GetCensoredHtmlWithImageLinks(true, $_messageInfo));

				if (!CApi::GetSettings()->GetConf('WebMail/AlwaysShowImagesInMessage') && !$_isFromSave)
				{
					$HtmlBody = ConvertUtils::HtmlBodyWithoutImages($HtmlBody);
					if (isset($GLOBALS[GL_WITHIMG]) && $GLOBALS[GL_WITHIMG])
					{
						$GLOBALS[GL_WITHIMG] = false;
						$safety = false;
					}
				}

				$HtmlBody = ConvertUtils::AddToLinkMailToCheck($HtmlBody);
			}

			if (($mode & 4) == 4 || ($mode & 2) == 2 && ($_messageClassType & 2) != 2)
			{
				$PlainBody = $message->GetCensoredTextBody(true);
				$PlainBody = ConvertUtils::AddToLinkMailToCheck($PlainBody);
			}

			$addAttachArray = array();

			$_msqAttachLine = 'msg_id='.$mes_id.'&msg_uid='.urlencode($mes_uid).
				'&folder_id='.$folder->IdDb.'&folder_fname='.urlencode($folder->FullName);

			$bHtmlIsShort = 10 > strlen(trim($HtmlBody));

			if (($mode & 256) == 256 || ($mode & 8) == 8 || ($mode & 16) == 16 || ($mode & 32) == 32 || ($mode & 64) == 64)
			{
				$_attachments =& $message->Attachments;
				if ($_attachments && $_attachments->Count() > 0)
				{
					$tempFiles =& CTempFiles::CreateInstance($oAccount);
					$_attachmentsKeys = array_keys($_attachments->Instance());
					foreach ($_attachmentsKeys as $_key)
					{
						$attachArray = array();

						$_attachment =& $_attachments->Get($_key);
						$_tempname = $message->IdMsg.'-'.$_key.'_'.ConvertUtils::ClearFileName($_attachment->GetTempName());
						$_filename = ConvertUtils::ClearFileName(ConvertUtils::ClearUtf8($_attachment->GetFilenameFromMime(), $GLOBALS[MailInputCharset], CPAGE_UTF8));
						$_size = 0;
						$_isBodyStructureAttachment = false;
						if ($_attachment->MimePart && $_attachment->MimePart->BodyStructureIndex !== null && $_attachment->MimePart->BodyStructureSize !== null)
						{
							$_isBodyStructureAttachment = true;
							$_size = $_attachment->MimePart->BodyStructureSize;
						}
						else
						{
							$_size = $tempFiles->SaveFile($_tempname, $_attachment->GetBinaryBody());
							$_size = ($_size < 0) ? 0 : $_size;
						}

						$attachArray['name'] = $_filename;
						$attachArray['tempname'] = $_tempname;
						$attachArray['size'] = (int) $_size;

						$_bodyStructureUrlAdd = '';
						if ($_isBodyStructureAttachment)
						{
							$_bodyStructureUrlAdd = 'bsi='.urlencode($_attachment->MimePart->BodyStructureIndex);
							if ($_attachment->MimePart->BodyStructureEncode !== null && strlen($_attachment->MimePart->BodyStructureEncode) > 0)
							{
								$_bodyStructureUrlAdd .= '&bse='.urlencode(ConvertUtils::GetBodyStructureEncodeType($_attachment->MimePart->BodyStructureEncode));
							}
						}

						$attachArray['inline'] = (bool) $_attachment->IsInline && !$bHtmlIsShort;
						$attachArray['filename'] = $_filename;
						$attachArray['duration'] = $_attachment->GetDuration();
						$attachArray['transcription'] = '';

						$viewUrl = (substr(strtolower($_filename), -4) == '.eml')
							? 'message-view.php?type='.MESSAGE_VIEW_TYPE_ATTACH.'&tn='.urlencode($_tempname)
							: 'view-image.php?img&tn='.urlencode($_tempname).'&filename='.urlencode($_filename);

						if ($_isBodyStructureAttachment)
						{
							$viewUrl .= '&'.$_bodyStructureUrlAdd.'&'.$_msqAttachLine;
						}

						$attachArray['view'] = $viewUrl;

						$linkUrl = 'attach.php?tn='.urlencode($_tempname);
						if ($_isBodyStructureAttachment)
						{
							$linkUrl .= '&'.$_bodyStructureUrlAdd.'&'.$_msqAttachLine;
						}

						$downloadUrl = $linkUrl.'&filename='.urlencode($_filename);

						$attachArray['download'] = $downloadUrl;
						$attachArray['link'] = $linkUrl;

						$mime_type = ConvertUtils::GetContentTypeFromFileName($_filename);
						$attachArray['mime_type'] = $mime_type;
						$attachArray['download'] = $downloadUrl;

						$attachArray['voice'] = false;
						if ($bVoice)
						{
							$bAttachmentVoice = false;
							CApi::Plugin()->RunHook('webmail.voice-attachment-detect',
								array($_attachment, $_filename, &$bAttachmentVoice));

							if ($bAttachmentVoice)
							{
								$attachArray['voice'] = $downloadUrl.'&play';
							}
						}

						$addAttachArray[] = $attachArray;
						unset($_attachment, $attachArray);
					}
				}
			}
			$title = ConvertUtils::ReBuildStringToJavaScript($message->GetSubject(true), '\'').' - '.defaultTitle;
			$bHasCharset = empty($HtmlBody) && empty($PlainBody)
				? true : $message->HasCharset;

			CApi::Plugin()->RunHook('webmail-change-html-text-from-attachment',
				array(&$message, &$HtmlBody, &$PlainBody, &$addAttachArray));
		}
	}

	$iSaveMail = $oSettings->GetConf('WebMail/SaveMail');
	$iSaveMail = ESaveMail::Always !== $iSaveMail ? $oAccount->User->SaveMail : ESaveMail::Always;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html id="html">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Pragma" content="cache" />
	<meta http-equiv="Cache-Control" content="public" />
	<link rel="shortcut icon" href="favicon.ico" />
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="skins/<?php echo ConvertUtils::AttributeQuote(defaultSkin); ?>/styles.css" type="text/css" id="skin" />
	<?php echo $_style; ?>
	<link href="skins/jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
		var OpenMode = "<?php echo ConvertUtils::ClearJavaScriptString($openMode, '"'); ?>";
		var ReplyType = "<?php echo ConvertUtils::ClearJavaScriptString($replyType, '"'); ?>";
		var ReplyText = "<?php echo ConvertUtils::ReBuildStringToJavaScript($replyText, '"'); ?>";
		var ActionUrl = 'processing.php';
		var LoginUrl = 'index.php';
		var EmptyHtmlUrl = 'empty.html';
		var Browser;
		var PreviewPane, NewMessageScreen;
		var UseDb = true;
		var EditAreaUrl = 'edit-area.php';
		var ImageUploaderUrl = 'fileuploader.php';
		var FileUploaderUrl = 'fileuploader.php';
		var WebMail = {
			_title: '<?php echo defaultTitle; ?>',
			Settings: {
				allowComposeMessage: <?php echo $oAccount->AllowCompose ? 'true' : 'false' ?>,
				allowContacts: false,
				allowForwardMessage: <?php echo $oAccount->AllowForward ? 'true' : 'false' ?>,
				allowInsertImage: <?php echo $oSettings->GetConf('WebMail/AllowInsertImage') ? 'true' : 'false' ?>,
				allowReplyMessage: <?php echo $oAccount->AllowReply ? 'true' : 'false' ?>,
				iSaveMail: <?php echo $iSaveMail ?>,
				richDefEditor: <?php echo ($oAccount->User->DefaultEditor == EUserHtmlEditor::Html) ? 'true' : 'false' ?>
			}
		}
		var CurrentAccount = {
			id: <?php echo $oAccount->IdAccount ?>,
			mailProtocol: <?php echo $oAccount->IncomingMailProtocol ?>,
			iSaveMail: <?php echo $oAccount->User->SaveMail ?>,
			email: '<?php echo ConvertUtils::ReBuildStringToJavaScript($oAccount->Email, '\'') ?>'
		};

<?php

	$aIdentities = array();
	$aAccounts = AppGetAccounts($oAccount, CSession::Get(APP_SESSION_USER_ID, null));
	$iIdentityType = $oSettings->GetConf('WebMail/AllowIdentities')
		? EIdentityType::Normal : EIdentityType::Virtual;

	if (is_array($aAccounts) && 0 < count($aAccounts))
	{
		foreach ($aAccounts as $oAccoutnOfList)
		{
			$aAddIdentities = $oApiUsersManager->GetIdentities($oAccoutnOfList, $iIdentityType);
			if (is_array($aAddIdentities) && 0 < count($aAddIdentities))
			{
				$aIdentities = array_merge($aIdentities, $aAddIdentities);
			}
		}
	}

	$aStrIdentities = array();
	if (is_array($aIdentities) && 0 < count($aIdentities))
	{
		$oIdentity = null;
		foreach ($aIdentities as /* @var $oIdentity CIdentity */ $oIdentity)
		{

			$aStrIdentities[] = '{
	bHtmlSignature: '.((EAccountSignatureType::Html === $oIdentity->SignatureType) ? 'true' : 'false').',
	bUseSignature: '.(($oIdentity->UseSignature) ? 'true' : 'false').',
	iAcctId: '.((int) $oIdentity->IdAccount).',
	iId: '.((int) $oIdentity->IdIdentity).',
	sEmail: \''.(ConvertUtils::ReBuildStringToJavaScript($oIdentity->Email, '\'')).'\',
	sName: \''.(ConvertUtils::ReBuildStringToJavaScript($oIdentity->FriendlyName, '\'')).'\',
	sSignature: \''.(ConvertUtils::ReBuildStringToJavaScript($oIdentity->Signature, '\'')).'\'
}';
		}
	}
?>

var Identities = [<?php echo implode(',', $aStrIdentities); ?>];

	</script>
</head>

<body onload="BodyLoaded();">
	<table class="wm_information wm_loading_information" cellpadding="0" cellspacing="0" style="right: auto; width: auto; top: 0px; left: 272px;" id="info_cont">
		<tr style="position:relative;z-index:20">
			<td class="wm_shadow" style="width:2px;font-size:1px;"></td>
			<td>
				<div class="wm_info_message" id="info_message">
					<span><?php echo JS_LANG_Loading;?></span>
				</div>
				<div class="a">&nbsp;</div>
				<div class="b">&nbsp;</div>
			</td>
			<td class="wm_shadow" style="width:2px;font-size:1px;"></td>
		</tr>
		<tr>
			<td colspan="3" class="wm_shadow" style="height:2px;background:none;">
				<div class="a">&nbsp;</div>
				<div class="b">&nbsp;</div>
			</td>
		</tr>
		<tr style="position:relative;z-index:19">
			<td colspan="3" style="height:2px;">
				<div class="a wm_shadow" style="margin:0px 2px;height:2px; top:-4px; position:relative; border:0px;background:#555;">&nbsp;</div>
			</td>
		</tr>
	</table>
</body>
<script type="text/javascript" src="langs.js.php?v=<?php echo JS_VERS; ?>&lang=<?php echo ConvertUtils::AttributeQuote($oAccount->User->DefaultLanguage); ?>"></script>
<?php

	$aLoadScripts = $oApiWebmailManager->GetJsFilesList(array('jquery', 'def', 'wm', 'wmp', 'mini'));
	if (is_array($aLoadScripts) && 0 < count($aLoadScripts))
	{
		foreach ($aLoadScripts as $sScriptName)
		{
			echo '<script type="text/javascript" src="'.$sScriptName.'"></script>';
		}
	}
?>
<script type="text/javascript">
<?php
	if ($message && ($openMode == 'view' || $openMode == 'reply') && (
			($mode & 256) == 256 ||
			($mode & 8) == 8 ||
			($mode & 16) == 16 ||
			($mode & 32) == 32 ||
			($mode & 64) == 64))
	{

		$maf =& MessageActionFilters::CreateInstance();
		$mafNoReply = $maf->GetNoReplyEmails();
		$mafNoReplyAll = $maf->GetNoReplyAllEmails();
		$mafNoForward = $maf->GetNoForwardEmails();
		$fromEmail = $message->GetFrom();
		$fromEmail = $fromEmail->Email;
		$noReply = (count($mafNoReply) > 0 && in_array($fromEmail, $mafNoReply));
		$noReplyAll = (count($mafNoReplyAll) > 0 && in_array($fromEmail, $mafNoReplyAll));
		$noForward = (count($mafNoForward) > 0 && in_array($fromEmail, $mafNoForward));
		echo "	var ViewMessage = new CMessage();
	ViewMessage.idFolder = ".$folder->IdDb.";
	ViewMessage.folderFullName = '".ConvertUtils::ReBuildStringToJavaScript($folder->FullName, '\'')."';
	ViewMessage.folderType = ".$folder->Type.";
	ViewMessage.size = ".((int) $message->Size).";
	ViewMessage.id = ".$message->IdMsg.";
	ViewMessage.uid = '".ConvertUtils::ReBuildStringToJavaScript($message->Uid, '\'')."';
	ViewMessage.hasHtml = ".(empty($HtmlBody) ? 'false' : 'true').";
	ViewMessage.hasPlain = ".(empty($PlainBody) ? 'false' : 'true').";
	ViewMessage.importance = ".$message->GetPriorityStatus().";
	ViewMessage.sensivity = ".$message->GetSensitivity().";
	ViewMessage.charset = ".$mes_charset.";
	ViewMessage.hasCharset = ".($bHasCharset ? 'true' : 'false').";
	ViewMessage.isRtl = ".($isRTL ? 'true' : 'false').";
	ViewMessage.noReply = ".($noReply ? 'true' : 'false').";
	ViewMessage.noReplyAll = ".($noReplyAll ? 'true' : 'false').";
	ViewMessage.noForward = ".($noForward ? 'true' : 'false').";
	ViewMessage.safety = ".((int) $safety).";
	ViewMessage.mailConfirmationValue = '".$message->GetReadMailConfirmationAsString()."';
	ViewMessage.downloaded = ".($message->Downloaded ? 'true' : 'false').";
	ViewMessage.fromAddr = '".ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::WMHtmlSpecialChars($from4search->ToDecodedString()), '\'')."';
	ViewMessage.fromDisplayName = '".ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::WMHtmlSpecialChars(WebMailMessage::ClearForSend(trim($from4search->DisplayName))), '\'')."';
	ViewMessage.toAddr = '".ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::WMHtmlSpecialChars($message->GetToAsString(true)), '\'')."';
	ViewMessage.shortToAddr = '".ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::WMHtmlSpecialChars($message->GetToAsString(true)), '\'')."';
	ViewMessage.ccAddr = '".ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::WMHtmlSpecialChars($message->GetCcAsString(true)), '\'')."';
	ViewMessage.bccAddr = '".ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::WMHtmlSpecialChars($message->GetBccAsString(true)), '\'')."';
	ViewMessage.replyToAddr = '".ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::WMHtmlSpecialChars($message->GetReplyToAsString(true)), '\'')."';
	ViewMessage.subject = '".ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::WMHtmlSpecialChars($message->GetSubject(true)), '\'')."';
	ViewMessage.date = '".ConvertUtils::ReBuildStringToJavaScript($date->GetFormattedShortDate($accountOffset), '\'')."';
	ViewMessage.fullDate = '".ConvertUtils::ReBuildStringToJavaScript($date->GetFormattedFullDate($accountOffset), '\'')."';
	ViewMessage.time = '".ConvertUtils::ReBuildStringToJavaScript($date->GetFormattedTime($accountOffset), '\'')."';
	ViewMessage.fullHeaders = '".ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::ConvertEncoding($message->OriginalHeaders, $GLOBALS[MailInputCharset], CPAGE_UTF8), '\'')."';
	ViewMessage.htmlBody = '".ConvertUtils::ReBuildStringToJavaScript($HtmlBody, '\'')."';
	ViewMessage.plainBody = '".ConvertUtils::ReBuildStringToJavaScript($PlainBody, '\'')."';
	ViewMessage.clearPlainBody = '".ConvertUtils::ReBuildStringToJavaScript($PlainBody, '\'')."';
	ViewMessage.saveLink = '".ConvertUtils::ReBuildStringToJavaScript('attach.php?'.$_msqAttachLine, '\'')."';
	ViewMessage.printLink = '".ConvertUtils::ReBuildStringToJavaScript('message-view.php?type='.MESSAGE_VIEW_TYPE_PRINT.'&'.$_msqAttachLine.'&charset='.$mes_charset, '\'')."';
	ViewMessage.attachments = [];";

		foreach ($addAttachArray as $attachItem)
		{
			echo '
	ViewMessage.attachments.push({
		id: -1, inline: '.(($attachItem['inline']) ? 'true' : 'false').', size: '.$attachItem['size'].',
		mimeType: "'.ConvertUtils::ReBuildStringToJavaScript($attachItem['mime_type'], '"').'",
		fileName: "'.ConvertUtils::ReBuildStringToJavaScript($attachItem['filename'], '"').'",
		download: "'.ConvertUtils::ReBuildStringToJavaScript($attachItem['download'], '"').'",
		view: "'.ConvertUtils::ReBuildStringToJavaScript($attachItem['view'], '"').'",
		tempName: "'.ConvertUtils::ReBuildStringToJavaScript($attachItem['tempname'], '"').'",
		sVoice: "'.ConvertUtils::ReBuildStringToJavaScript($attachItem['voice'], '"').'",
		iDuration: "'.ConvertUtils::ReBuildStringToJavaScript((int) $attachItem['duration'], '"').'"
	});'."\r\n";
		}

		$replyAsHtml = $message->GetRelpyAsHtml(true, $accountOffset, $_messageInfo);
		if (!$_isFromSave)
		{
			echo '
	ViewMessage.replyHtml = \''.
				ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::AddToLinkMailToCheck(
					ConvertUtils::HtmlBodyWithoutImages(
						ConvertUtils::ReplaceJSMethod($replyAsHtml))), '\'').'\';';
		}
		else
		{
			echo '
	ViewMessage.replyHtml = \''.
				ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::AddToLinkMailToCheck(
					ConvertUtils::ReplaceJSMethod($replyAsHtml)), '\'').'\';';
		}

		echo '
	ViewMessage.replyPlain = \''.
			ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::AddToLinkMailToCheck(
				$message->GetRelpyAsPlain(true, $accountOffset)), '\'').'\';';

		$forwardAsHtml = $message->GetForwardAsHtml(true, $accountOffset, $_messageInfo);
		if (!$_isFromSave)
		{
			echo '
	ViewMessage.forwardHtml = \''.
				ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::AddToLinkMailToCheck(
					ConvertUtils::HtmlBodyWithoutImages(
						ConvertUtils::ReplaceJSMethod($forwardAsHtml))), '\'').'\';';
		}
		else
		{
			echo '
	ViewMessage.forwardHtml = \''.
				ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::AddToLinkMailToCheck(
					ConvertUtils::ReplaceJSMethod($forwardAsHtml)), '\'').'\';';
		}

		echo '
	ViewMessage.forwardPlain = \''.
			ConvertUtils::ReBuildStringToJavaScript(ConvertUtils::AddToLinkMailToCheck(
				$message->GetForwardAsPlain(true, $accountOffset)), '\'').'\';';
	echo '
	ViewMessage.isReplyHtml = true;
	ViewMessage.isReplyPlain = true;
	ViewMessage.isForwardHtml = true;
	ViewMessage.isForwardPlain = true;
	ViewMessage.isVoice = '.($bVoice ? 'true' : 'false').';
';

	}
?>
</script>

</html><?php

		echo '<!-- '.WMVERSION.' -->';
