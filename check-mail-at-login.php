<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('G_WEBMAILURL') || die();
	defined('defaultSkin') || die();
	
	@header('Content-type: text/html; charset=utf-8');

	if (!isset($oAccount))
	{
		CApi::Location('index.php?error=2');
		exit();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<link rel="shortcut icon" href="favicon.ico" />
	<title></title>
	<link rel="stylesheet" href="skins/<?php echo ConvertUtils::AttributeQuote(defaultSkin); ?>/styles.css" type="text/css" id="skin" />
	<script type="text/javascript" src="langs.js.php?v=<?php echo ConvertUtils::GetJsVersion(); ?>&lang=<?php echo ConvertUtils::AttributeQuote($oAccount->User->DefaultLanguage); ?>"></script>
<?php 

/* @var $oApiWebmailManager CApiWebmailManager */
$oApiWebmailManager = CApi::Manager('webmail');
		
$aLoadScripts = $oApiWebmailManager->GetJsFilesList(array('jquery', 'def'));
if (is_array($aLoadScripts) && 0 < count($aLoadScripts))
{
	foreach ($aLoadScripts as $sScriptName)
	{
		echo '<script type="text/javascript" src="'.$sScriptName.'"></script>';
	}
}

?>	
	<script type="text/javascript">
		var checkMail;
		var WebMailUrl = '<?php echo G_WEBMAILURL; ?>';
		var LoginUrl = 'index.php';
		var CheckMailUrl = 'check-mail.php';
		var EmptyHtmlUrl = 'empty.html';
		var Browser = new CBrowser();

		function Init()
		{
			checkMail = new CCheckMail(1);
			checkMail.start();
		}
		
		function SetCheckingAccountHandler(accountName)
		{
			checkMail.SetAccount(accountName);
		}
		
		function SetStateTextHandler(text) {
			checkMail.SetText(text);
		}
		
		function SetCheckingFolderHandler(folder, count) {
			checkMail.SetFolder(folder, count);
		}
		
		function SetRetrievingMessageHandler(number) {
			checkMail.SetMsgNumber(number);
		}
		
		function SetDeletingMessageHandler(number) {
			checkMail.DeleteMsg(number);
		}
		
		function EndCheckMailHandler(error) {
			if (error == 'session_error') {
				document.location = LoginUrl + '?error=1';
			} else {
				document.location = WebMailUrl;
			}
		}
		
		function CheckEndCheckMailHandler() {
			if (checkMail.started) {
				document.location = WebMailUrl;
			}
		}
	</script>
</head>
<body onload="Init();">
<div id="content" class="wm_content">
	<div class="wm_logo" id="logo" tabindex="-1" onfocus="this.blur();"></div>
</div>
<div class="wm_copyright" id="copyright">
	<?php require('inc.footer.php'); ?>
</div>
</body>
</html>