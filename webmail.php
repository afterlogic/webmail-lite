<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
	require_once WM_ROOTPATH.'application/include.php';
	$oInput = new api_Http();

	/* @var $oApiWebmailManager CApiWebmailManager */
	$oApiWebmailManager = CApi::Manager('webmail');

	require_once WM_ROOTPATH.'common/inc_constants.php';
	require_once WM_ROOTPATH.'common/class_convertutils.php';

	$iAccountId = CSession::Get(APP_SESSION_ACCOUNT_ID, false);

	$bCheck = (bool) $oInput->GetQuery('check', false);

	$start = $oInput->GetQuery('start', 0);
	$start = $oInput->GetPost('start', $start);

	$to = $oInput->GetQuery('to', '');
	$to = preg_replace('/[^a-zA-Z0-9\.%,\-@ ]/', '', $to);

	$aParams = array();
	if ($start > 0)
	{
		$aParams[] = 'start='.$start;
	}
	if (0 < strlen($to))
	{
		$aParams[] = 'to='.$to;
	}

	$sParamsLine = implode('&', $aParams);
	$sParamsLine = strlen($sParamsLine) > 0 ? '?'.$sParamsLine : '';

	if (false === $iAccountId)
	{
		CApi::Location('index.php');
		exit();
	}

	/* @var $oAccount CAccount */
	$oAccount = AppGetAccount($iAccountId);
	if (!$oAccount)
	{
		CApi::Location('index.php?error=2');
		exit();
	}

	$aSkins = $oApiWebmailManager->GetSkinList();
	foreach ($aSkins as $sSkinName)
	{
		if ($sSkinName === $oAccount->User->DefaultSkin)
		{
			define('defaultSkin', $sSkinName);
			break;
		}
	}

	defined('defaultSkin') || define('defaultSkin', (count($aSkins) > 0) ? $aSkins[0] : 'AfterLogic');

	if ($bCheck)
	{
		define('G_WEBMAILURL', 'webmail.php'.$sParamsLine);
		require_once WM_ROOTPATH.'check-mail-at-login.php';
	}
	else
	{
		$sIframe = CApi::GetConf('webmail.use-iframe', null);
		if (false !== $oInput->GetQuery('iframe', false) && $sIframe && 0 < strlen($sIframe))
		{
			CApi::Location($sIframe.$sParamsLine);
			exit();
		}

		AppIncludeLanguage($oAccount->User->DefaultLanguage);

		define('defaultTitle', AppGetSiteName($oAccount));

		$_rtl = in_array($oAccount->User->DefaultLanguage, CApi::GetConf('webmail.rtl-langs', array()));
		$_style = ($_rtl) ? '<link rel="stylesheet" href="skins/'.ConvertUtils::AttributeQuote(defaultSkin).'/styles-rtl.css" type="text/css" id="skin-rtl">' : '';

		define('JS_VERS', ConvertUtils::GetJsVersion());

		header('Content-type: text/html; charset=utf-8');
		header('Content-script-type: text/javascript');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html id="html">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<link type="image/x-icon" rel="shortcut icon" href="favicon.ico" />
	<title><?php echo defaultTitle; ?></title>
	<link rel="stylesheet" href="skins/<?php echo ConvertUtils::AttributeQuote(defaultSkin); ?>/styles.css" type="text/css" id="skin" />
	<link href="skins/jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
	<?php echo $_style; ?>
	<script type="text/javascript">
		var JSLoadedCount = 1;
		var TotalJSFilesCount = 49;
		function JSFileLoaded()
		{
			JSLoadedCount++;
			var percent = Math.ceil((JSLoadedCount)*100/(TotalJSFilesCount + 1));
			if (percent >= 0) {
				var jsProgressLoaded = document.getElementById('jsProgressLoaded');
				if (jsProgressLoaded) {
					percent = (percent > 100) ? 100 : percent;
					jsProgressLoaded.style.width = percent + 'px';
				}
			}
		}

		function BodyLoaded()
		{
			if (JSLoadedCount >= TotalJSFilesCount) {
				Init();
			}
		}
	</script>
</head>

<body onload="BodyLoaded();">
	<table class="wm_hide" style="right: auto; width: auto; top: 0px; left: 604px;" id="info_cont">
		<tr style="position:relative;z-index:20">
			<td class="wm_shadow" style="width:2px;font-size:1px;"></td>
			<td>
				<div class="wm_info_message" id="info_message">
					<span><?php echo JS_LANG_InfoWebMailLoading;?></span>
					<div class="wm_progressbar">
						<div id="jsProgressLoaded" class="wm_progressbar_used" style="width: 95px;"></div>
					</div>
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
	<div id="content" class="wm_hide">
		<div class="wm_logo" id="logo" tabindex="-1" onfocus="this.blur();"></div>
	</div>
	<div class="wm_hide" id="copyright">
		<?php require('inc.footer.php'); ?>
	</div>
</body>
<script type="text/javascript">
	var ActionUrl = 'processing.php';
	var CalendarUrl = 'calendar.php';
	var CalendarProcessingUrl = 'calendar/processing.php';
	var CheckMailUrl = 'check-mail.php';
	var EditAreaUrl = 'edit-area.php';
	var EmptyHtmlUrl = 'empty.html';
	var ExportContactsUrl = 'export.php?contacts';
	var FileUploaderUrl = 'fileuploader.php';
	var HistoryStorageUrl = 'history-storage.php';
	var ImageUploaderUrl = 'fileuploader.php';
	var ImportUrl = 'import.php';
	var LanguageUrl = 'langs.js.php';
	var LoginUrl = 'index.php';
	var MiniWebMailUrl = 'mini-webmail.php';
	var SessionSaverUrl = 'session-saver.php';
	var WebMailUrl = 'webmail.php<?php echo (false !== $oInput->GetQuery('iframe', false)) ? '?iframe' : ''; ?>';

	var Browser;
	var HistoryStorage;
	var WebMail;

	var CSType = <?php echo (@file_exists('CS')) ? 'true' : 'false'; ?>;
	var RTL = <?php echo ($_rtl) ? 'true' : 'false'; ?>;
	var Separated = <?php echo (CSession::Get(SEPARATED, false)) ? 'true' : 'false'; ?>;
	var SkinName = "<?php echo ConvertUtils::ClearJavaScriptString(defaultSkin, '"'); ?>";
	var Start = <?php echo (int) $start; ?>;
	var Title = "<?php echo ConvertUtils::ClearJavaScriptString(defaultTitle, '"'); ?>";
	var ToAddr = "<?php echo ConvertUtils::ClearJavaScriptString($to, '"'); ?>";
	var UseDb = <?php echo (USE_DB) ? 'true' : 'false'; ?>;
	var UseLdapSettings = <?php echo (USE_LDAP_SETTINGS_STORAGE) ? 'true' : 'false'; ?>;
	var WmVersion = "<?php echo JS_VERS; ?>";
	var XType = "0";

	function GetWidth() {
		var w = 1024;
		if (document.documentElement && document.documentElement.clientWidth) {
			w = document.documentElement.clientWidth;
		}
		else if (document.body.clientWidth) {
			w = document.body.clientWidth;
		}
		else if (self && self.innerWidth) {
			w = self.innerWidth;
		}
		return w;
	}
	var infoCont = document.getElementById('info_cont');
	if (infoCont) {
		infoCont.className = 'wm_information wm_loading_information';
		infoCont.style.right = 'auto';
		infoCont.style.left = Math.round((GetWidth() - infoCont.offsetWidth)/2) + 'px';
	}
</script>
<script type="text/javascript" src="langs.js.php?v=<?php echo JS_VERS; ?>&lang=<?php echo ConvertUtils::AttributeQuote($oAccount->User->DefaultLanguage); ?>"></script>
<?php

	$aLoadScripts = $oApiWebmailManager->GetJsFilesList(array('jquery', 'def', 'wm', 'wmp', 'cont'));
	if (is_array($aLoadScripts) && 0 < count($aLoadScripts))
	{
		foreach ($aLoadScripts as $sScriptName)
		{
			echo '<script type="text/javascript" src="'.$sScriptName.'"></script>';
		}
	}

	echo '<script type="text/javascript">';

	if (false !== $oInput->GetQuery('logging_on', false))
	{
		CSession::Set('awm_logging_on', true);
	}
	else if (false !== $oInput->GetQuery('logging_off', false))
	{
		CSession::Clear('awm_logging_on');
	}

	if (false !== $oInput->GetQuery('prefetch_on', false))
	{
		echo "\t\tUsePrefetch = true;\r\n";
	}
	else if (false !== $oInput->GetQuery('prefetch_off', false))
	{
		echo "\t\tUsePrefetch = false;\r\n";
	}
	else
	{
		echo "\t\tUsePrefetch = ".((CApi::GetConf('webmail.use-prefetch', false)) ? 'true' : 'false').";\r\n";
	}

	echo '</script></html><!-- '.WMVERSION.' -->';

}
