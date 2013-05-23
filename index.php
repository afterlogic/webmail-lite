<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */
	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));

	require_once WM_ROOTPATH.'application/include.php';
	require_once WM_ROOTPATH.'common/inc_constants.php';
	require_once WM_ROOTPATH.'common/class_convertutils.php';

	if (!CApi::IsValid())
	{
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<link rel="shortcut icon" href="favicon.ico" />
	<title>WebMail is not configured properly</title>
	<link rel="stylesheet" href="skins/AfterLogic/styles.css" type="text/css" />
</head>
<body>
<div id="content" class="wm_content">
	<div class="wm_logo" id="logo" tabindex="-1" onfocus="this.blur();"></div>
<?php
	echo buildInfoCont('wm_information wm_error_information', 'WebMail is not configured properly.');
?><div class="wm_copyright" id="copyright"><?php
		require('inc.footer.php');
		exit();
?></div></div></body></html><?php

	}

	$oInput = new api_Http();

	$errorClass = 'wm_hide';
	$errorDesc = '';
	$error = isset($_GET['error']) ? (int) $_GET['error'] : 0;

	CSession::Clear(SESSION_LANG);
	CSession::Clear(SESSION_RESET_STEP);
	CSession::Clear(SESSION_RESET_ACCT_ID);

	/* @var $oSettings api_Settings */
	$oSettings =& CApi::GetSettings();

	/* @var $oApiWebmailManager CApiWebmailManager */
	$oApiWebmailManager = CApi::Manager('webmail');

	/* @var $oApiDomainsManager CApiDomainsManager */
	$oApiDomainsManager = CApi::Manager('domains');

	/* @var $oApiDavManager CApiDavManager */
	$oApiDavManager = CApi::Manager('dav');

	/* @var $oDomain CDomain */
	$oDomain = AppGetDomain();

	CApi::Plugin()->RunHook('webmail-index', array($oDomain, $oInput));

	/* @var $oApiUsersManager CApiUsersManager */
	$oApiUsersManager = CApi::Manager('users');

	$iAccountId = CSession::Get(APP_SESSION_ACCOUNT_ID, null);

	$aLangs = $oApiWebmailManager->GetLanguageList();

	if (in_array($oInput->GetQuery('lang'), $aLangs))
	{
		define('defaultLanguage', $oInput->GetQuery('lang'));
		@setcookie('awm_defLang', defaultLanguage, time() + 31104000);
	}
	else if ($oSettings->GetConf('WebMail/AllowLanguageOnLogin') && in_array($oInput->GetCookie('awm_defLang'), $aLangs))
	{
		define('defaultLanguage', $oInput->GetCookie('awm_defLang'));
	}

	defined('defaultLanguage') || define('defaultLanguage', $oDomain->DefaultLanguage);

	AppIncludeLanguage(defaultLanguage);

	$_rtl = in_array(defaultLanguage, CApi::GetConf('webmail.rtl-langs', array()));
	$_style = ($_rtl) ? '<link rel="stylesheet" href="skins/'.$oDomain->DefaultSkin.'/styles-rtl.css" type="text/css">' : '';

	$mode = isset($_GET['mode']) ? $_GET['mode'] : 'standard';
	if ('logout' === $mode)
	{
		if (null !== $iAccountId && 0 < $iAccountId)
		{
			$oAccount = $oApiUsersManager->GetAccountById($iAccountId);
			if ($oAccount)
			{
				CApi::Plugin()->RunHook('statistics.logout', array(&$oAccount));

				CApi::LogEvent('User logout', $oAccount);
			}
		}

		if (false !== $oInput->GetCookie('awm_autologin_data', false))
		{
			unset($_COOKIE['awm_autologin_data']);
			@setcookie('awm_autologin_data', null);
		}

		if (false !== $oInput->GetCookie('awm_autologin_subid', false))
		{
			unset($_COOKIE['awm_autologin_subid']);
			@setcookie('awm_autologin_subid', null);
		}

		if (false !== $oInput->GetCookie('awm_autologin_id', false))
		{
			unset($_COOKIE['awm_autologin_id']);
			@setcookie('awm_autologin_id', null);
		}

		CSession::ClearAll();
	}

	CApi::Plugin()->RunHook('webmail-index-p2', array($oDomain, $oInput, $iAccountId));

	define('JS_VERS', ConvertUtils::GetJsVersion());

	$sCookieEmail = '';
	if (6 === $error) // lang error
	{
		$errorDesc = 'Can\'t find required language file.';
		$errorClass = 'wm_information wm_error_information';
	}
	else if (1 === $error) // session error
	{
		$errorDesc = PROC_SESSION_ERROR;
		$errorClass = 'wm_information wm_error_information';
	}
	else if (2 === $error) // account error
	{
		$errorDesc = PROC_CANT_LOAD_ACCT;
		$errorClass = 'wm_information wm_error_information';
	}
	else if (3 === $error) // settings error
	{
		$errorDesc = PROC_CANT_GET_SETTINGS;
		$errorClass = 'wm_information wm_error_information';
	}
	else if (5 === $error) // connection error
	{
		$errorDesc = PROC_CANT_LOAD_DB;
		$errorClass = 'wm_information wm_error_information';
	}

	if ('logout' !== $mode && (0 === $error || 1 === $error))
	{
		RestoreAccountSessionFromAutoload($oInput);
	}

	@header('Content-type: text/html; charset=utf-8');

	define('defaultTitle', AppGetSiteName());

	$aSkins = $oApiWebmailManager->GetSkinList();
	foreach ($aSkins as $sSkinName)
	{
		if ($sSkinName == $oDomain->DefaultSkin)
		{
			define('defaultSkin', $oDomain->DefaultSkin);
			break;
		}
	}
	defined('defaultSkin') || define('defaultSkin', (count($aSkins) > 0) ? $aSkins[0] : 'AfterLogic');

	$sLangDiv = '';
	if ($oSettings->GetConf('WebMail/AllowLanguageOnLogin'))
	{
		if (count($aLangs) > 0)
		{
			$sLangDiv .= '<span class="wm_language_place">
<a id="langs_selected" href="#" class="wm_reg" onclick="return false;" style="padding-right: 0px;"><span>'.$oApiWebmailManager->GetLanguageName(defaultLanguage).'</span><font>&nbsp;</font><span class="wm_login_lang_switcher">&nbsp;</span></a>
<input type="hidden" value="'.(isset($_GET['lang']) ? defaultLanguage : '').'" id="language" name="language">
<br /><div id="langs_collection">';

			foreach ($aLangs as $sLangName)
			{
				$sLangDiv .= '<a href="#" name="lng_'.ConvertUtils::AttributeQuote($sLangName).'" onclick="ChangeLang(this); return false;">'.$oApiWebmailManager->GetLanguageName($sLangName).'</a>';
			}

			$sLangDiv .= '</div></span>';
		}
	}

	define('defaultIncServer', $oDomain->IncomingMailServer);
	define('defaultIncPort', $oDomain->IncomingMailPort);
	define('defaultOutServer', $oDomain->OutgoingMailServer);
	define('defaultOutPort', $oDomain->OutgoingMailPort);
	define('defaultUseSmtpAuth', ESMTPAuthType::AuthCurrentUser === $oDomain->OutgoingMailAuth);

	$pop3Selected = ' selected="selected"';
	$imap4Selected = '';

	if (EMailProtocol::IMAP4 === $oDomain->IncomingMailProtocol)
	{
		$imap4Selected = ' selected="selected"';
		$pop3Selected = '';
	}

	$smtpAuthChecked = (defaultUseSmtpAuth) ? ' checked="checked"' : '';
	$bAdvancedLogin = false;

	function hiddenDomainValue($sDomainName)
	{
		return '@'.$sDomainName.'<input type="hidden" name="sDomainValue" id="sDomainValue" value="'.
			str_replace(array('"', "\r", "\n", "\t"), array('&quot;', '', '', ''), $sDomainName).
			'" />';
	}

	$emailWidth = '224px';
	$sAtDomainValue = '<input type="hidden" name="sDomainValue" id="sDomainValue" value="" />';
	$iLoginFormType = $oSettings->GetConf('WebMail/LoginFormType');
	switch ($iLoginFormType)
	{
		case ELoginFormType::Email:
		case ELoginFormType::Login:
			break;

		case ELoginFormType::LoginAtDomain:
			$sAtDomainValue = $oSettings->GetConf('WebMail/LoginAtDomainValue');
			if (empty($sAtDomainValue))
			{
				$iLoginFormType = ELoginFormType::Email;
			}
			else
			{
				$sAtDomainValue = hiddenDomainValue($sAtDomainValue);
				$emailWidth = '120px';
			}
			break;

		case ELoginFormType::LoginAtDomainDropdown:
			$aDomains = array_values($oApiDomainsManager->GetFullDomainsList());
			if (is_array($aDomains) && 0 < count($aDomains))
			{
				if (1 === count($aDomains))
				{
					$sAtDomainValue = hiddenDomainValue($aDomains[0][1]);
					$iLoginFormType = ELoginFormType::LoginAtDomain;
				}
				else
				{
					$sAtDomainValue = '@<select name="sDomainValue" id="sDomainValue" style="width: 80px">';
					foreach ($aDomains as $aDomainItem)
					{
						$sAtDomainValue .= '<option value="'.$aDomainItem[1].'">'.$aDomainItem[1].'</option>';
					}
					$sAtDomainValue .= '</select>';
				}
				$emailWidth = '120px';
			}
			else
			{
				$iLoginFormType = ELoginFormType::Email;
			}
			break;
	}

	$bUseAdvancedLogin = $oSettings->GetConf('WebMail/UseAdvancedLogin');

	$sLoginFormEmail = CApi::GetConf('demo.webmail.login', '');
	$sLoginFormPassword = CApi::GetConf('demo.webmail.password', '');

	if (!empty($sCookieEmail))
	{
		$sLoginFormEmail = $sCookieEmail;
		$sLoginFormPassword = '';
	}

	$sGetEmail = $oInput->GetQuery('email');
	if (!empty($sGetEmail) && preg_match('/^[a-zA-Z0-9\-\.]+@[[a-zA-Z0-9\-\.]+$/', $sGetEmail))
	{
		$sLoginFormEmail = $sGetEmail;
		$sLoginFormPassword = '';
	}

	$sSpecLog = strtolower($oInput->GetQuery('speclog', ''));
	if ('on' === $sSpecLog)
	{
		@setcookie('spec-log', '1');
	}
	else if (isset($_COOKIE['spec-log']) && 'off' === $sSpecLog)
	{
		@setcookie('spec-log', null);
	}

	$sUserLog = strtolower($oInput->GetQuery('userlog', ''));
	if (!empty($sUserLog) && 'off' !== $sUserLog)
	{
		$sUserLog = substr($sUserLog, 0, 20);
		@setcookie('user-log', preg_replace('/[^a-z0-9]/', '', $sUserLog));
	}
	else if (isset($_COOKIE['user-log']) && 'off' === $sUserLog)
	{
		@setcookie('user-log', null);
	}

	$sDemoHeadPart = '';
	$sDemoFootPart = '';
	CApi::Plugin()->RunHook('webmail-index-demo-hook', array(&$sDemoHeadPart, &$sDemoFootPart));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<link rel="shortcut icon" href="favicon.ico" />
	<title><?php echo defaultTitle; ?></title>
	<link rel="stylesheet" href="skins/<?php echo defaultSkin; ?>/styles.css?v=<?php echo JS_VERS; ?>" type="text/css" id="skin" />
<?php if ($oSettings->GetConf('WebMail/AllowLanguageOnLogin') && $oSettings->GetConf('WebMail/FlagsLangSelect')): ?>
	<link rel="stylesheet" href="skins/<?php echo defaultSkin; ?>/login.css?v=<?php echo JS_VERS; ?>" type="text/css" id="skin" />
<?php endif; ?>
	<?php echo $_style; ?>

	<script type="text/javascript">
		var ActionUrl = "processing.php";
		var EmptyHtmlUrl = "empty.html";
		var LanguageUrl = "langs.js.php";
		var LoginUrl = "index.php";
		var WebMailUrl = "webmail.php";

		var DefLang = "<?php echo ConvertUtils::ClearJavaScriptString(defaultLanguage, '"'); ?>";
		var LoginFormType = <?php echo (int) $iLoginFormType; ?>;
		var DisableIos = <?php echo (($oApiDavManager && $oApiDavManager->IsMobileSyncEnabled()) ? 'false' : 'true'); ?>;
		var IsLite = <?php echo (null === CApi::Manager('licensing') ? 'true' : 'false'); ?>;
		var NeedToSubmit = false;
		var RTL = <?php echo ($_rtl) ? 'true' : 'false'; ?>;
		var UseDb = <?php echo (USE_DB) ? 'true' : 'false'; ?>;
		var WmVersion = "<?php echo JS_VERS; ?>";
	</script>
	<script type="text/javascript" src="langs.js.php?v=<?php echo JS_VERS; ?>&lang=<?php echo ConvertUtils::AttributeQuote(defaultLanguage); ?>"></script>
<?php

	$aLoadScripts = $oApiWebmailManager->GetJsFilesList(array('jquery', 'def', 'login'));
	if (is_array($aLoadScripts) && 0 < count($aLoadScripts))
	{
		foreach ($aLoadScripts as $sScriptName)
		{
			echo '<script type="text/javascript" src="'.$sScriptName.'"></script>';
		}
	}

	$aLoadScripts = $oApiWebmailManager->GetJsFilesList(array('wm', 'cont'));
	if (is_array($aLoadScripts) && 0 < count($aLoadScripts)):
?>
	<script type="text/javascript">
		WebMailScripts = [<?php

		foreach ($aLoadScripts as $iIndex => $sScriptName)
		{
			$aLoadScripts[$iIndex] = '\''.$sScriptName.'\'';
		}

		echo implode(', ', $aLoadScripts);
?>
];
	</script>
<?php endif; ?>
	<script type="text/javascript">
		function ChangeLang(object) {
			if (object && object.name && object.name.length > 4 && object.name.substr(0, 4) == 'lng_') {
				document.location = LoginUrl + '?lang=' + object.name.substr(4);
			}
		}
<?php if ($oSettings->GetConf('WebMail/AllowLanguageOnLogin') && $oSettings->GetConf('WebMail/FlagsLangSelect')): ?>
		function DemoLangInit()
		{
            if (LoginDemoLangClass) LoginDemoLangClass.checkLang('lng_' + DefLang);
		}
<?php endif; ?>
	</script>
<?php echo $sDemoHeadPart; ?>
</head>
<body onload="Init();" id="mbody">
<div class="wm_content">
	<div id="content">
		<div class="wm_logo" id="logo" tabindex="-1" onfocus="this.blur();"></div>
	</div>
	<?php echo buildInfoCont($errorClass, $errorDesc); ?>
	<div id="login_screen">
		<form action="index.php?mode=submit" method="post" id="login_form" name="login_form" onsubmit="NeedToSubmit = true; return false;" autocomplete="on">
			<input type="hidden" name="advanced_login" value="<?php echo $bAdvancedLogin; ?>" />
			<div class="wm_login" >
				<div class="a top"></div>
				<div class="b top"></div>
				<div class="login_table">
					<div id="lang_LoginInfo" class="wm_login_header"><?php echo LANG_LoginInfo?></div>
					<div class="wm_login_content">
						<table id="login_table" class="login_table_block" border="0" cellspacing="0" cellpadding="10">
							<tr id="email_cont">
								<td class="wm_title" style="font-size:12px; width: 70px;" id="lang_Email"><?php echo LANG_Email?>:</td>
								<td colspan="4">
									<nobr>
										<input style="width: <?php echo $emailWidth;?>; font-size:16px;"
											   class="wm_input" type="text" value="<?php
echo ConvertUtils::AttributeQuote($sLoginFormEmail, true); ?>"
											   id="email" name="email" maxlength="255"
											   onfocus="this.className = 'wm_input_focus';"
											   onblur="this.className = 'wm_input';" tabindex="1" />
										<span id="domain_cont"><?php echo $sAtDomainValue; ?></span>
									</nobr>
								</td>
							</tr>
							<tr id="login_cont" class="wm_hide">
								<td class="wm_title" style="font-size:12px; width: 70px;" id="lang_Login"><?php echo LANG_Login; ?>:</td>
								<td colspan="4">
									<input tabindex="3" style="width:224px; font-size:16px;" class="wm_input" type="text" value="" id="inc_login" name="inc_login" maxlength="255"
										onfocus="this.className = 'wm_input_focus';" onblur="this.className = 'wm_input';" />
								</td>
							</tr>
							<tr>
								<td class="wm_title" style="font-size:12px; width: 70px;" id="lang_Password"><?php echo LANG_Password; ?>:</td>
								<td colspan="4">
									<input tabindex="3" style="width:224px; font-size:16px;" class="wm_input wm_password_input" type="password" value="<?php
echo ConvertUtils::AttributeQuote($sLoginFormPassword, true); ?>" id="password" name="password" maxlength="255"
										onfocus="this.className = 'wm_input_focus wm_password_input';" onblur="this.className = 'wm_input wm_password_input';" />
								</td>
							</tr>
<?php if ($oDomain->AllowPasswordReset): ?>
							<tr>
								<td></td>
								<td colspan="4">
									<a tabindex="4" class="wm_recover_link" href="password-reset.php" id="reset_link_id"><?php echo IndexResetLink;?></a>
								</td>
							</tr>
<?php
	endif;

	if ($oSettings->GetConf('WebMail/UseReCaptcha'))
	{
		require_once(WM_ROOTPATH.'libraries/recaptcha/recaptchalib.php');
?>
							<tr valign="top">
								<td colspan="6">
<?php echo recaptcha_get_html(CApi::GetConf('captcha.recaptcha-public-key', ''), null, $oInput->IsSecure()); ?>
								</td>
							</tr>
<?php
	}
	else if ($oSettings->GetConf('WebMail/UseCaptcha'))
	{
		$iCaptchaLimit = CApi::GetConf('captcha.limit-count', 3);
		$sCapthcaClass = (0 === $iCaptchaLimit || CSession::Get('captcha_count', 0) >= $iCaptchaLimit)
			? '' : 'wm_hide';
?>
							<tr valign="top" id="captcha_content" class="<?php echo $sCapthcaClass; ?>">
								<td class="wm_title" style="font-size:12px; width: 70px; padding-top:9px;" id="lang_CaptchaTitle"><?php echo CaptchaTitle; ?>:</td>
								<td align="center">
									<input tabindex="5" style="width:95px; font-size:16px;" class="wm_input" type="text" value="" id="captcha" name="captcha" maxlength="6"
										onfocus="this.className = 'wm_input_focus';" onblur="this.className = 'wm_input';" />
									<span class="wm_message_right"><a href="#" class="wm_reg" id="lang_CaptchaReloadLink"><?php echo CaptchaReloadLink; ?></a></span>
								</td>
								<td colspan="3">
									<img src="captcha.php?<?php echo 'PHPWEBMAILSESSID='.CSession::Id().'&c='.rand(100, 999); ?>"
										 id="captcha_img" width="120" height="46" class="wm_chaptcha" alt="Captcha" />
								</td>
							</tr>
<?php } ?>
						</table>

<?php if ($bUseAdvancedLogin): ?>
						<div id="advanced_fields" style="margin:0px; height:95px; display:none; overflow:hidden; padding:0px;">
						<table cellspacing="0" cellpadding="6">
							<tr id="incoming">
								<td class="wm_title" id="lang_IncServer"><?php echo LANG_IncServer?>:</td>
								<td>
									<input tabindex="6" class="wm_advanced_input" type="text" value="<?php echo defaultIncServer?>" id="inc_server" name="inc_server" maxlength="255"
										onfocus="this.className = 'wm_advanced_input_focus';" onblur="this.className = 'wm_advanced_input';" />
								</td>
								<td>
									<select tabindex="7" class="wm_advanced_input" id="inc_protocol" name="inc_protocol"
										onfocus="this.className = 'wm_advanced_input_focus';" onblur="this.className = 'wm_advanced_input';">
										<option value="<?php echo EMailProtocol::POP3 ?>" <?php echo $pop3Selected?>><?php echo LANG_PopProtocol?></option>
										<option value="<?php echo EMailProtocol::IMAP4 ?>" <?php echo $imap4Selected?>><?php echo LANG_ImapProtocol?></option>
									</select>
								</td>
								<td class="wm_title" id="lang_IncPort"><?php echo LANG_IncPort?>:</td>
								<td>
									<input tabindex="8" class="wm_advanced_input" type="text" value="<?php echo defaultIncPort?>" id="inc_port" name="inc_port" maxlength="5"
										onfocus="this.className = 'wm_advanced_input_focus';" onblur="this.className = 'wm_advanced_input';" />
								</td>
							</tr>
							<tr id="outgoing">
								<td class="wm_title" id="lang_OutServer"><?php echo LANG_OutServer?>:</td>
								<td colspan="2">
									<input tabindex="9" class="wm_advanced_input" type="text" value="<?php echo defaultOutServer?>" id="out_server" name="out_server" maxlength="255"
										onfocus="this.className = 'wm_advanced_input_focus';" onblur="this.className = 'wm_advanced_input';" />
								</td>
								<td class="wm_title" id="lang_OutPort"><?php echo LANG_OutPort?>:</td>
								<td align="right">
									<input tabindex="10" class="wm_advanced_input" type="text" value="<?php echo defaultOutPort?>" id="out_port" name="out_port" maxlength="5"
										onfocus="this.className = 'wm_advanced_input_focus';" onblur="this.className = 'wm_advanced_input';" />
								</td>
							</tr>
							<tr id="authentication">
								<td colspan="5">
									<input tabindex="11" class="wm_checkbox" type="checkbox" value="1" id="smtp_auth" name="smtp_auth"<?php echo $smtpAuthChecked?> />
									<label for="smtp_auth" id="lang_UseSmtpAuth" style="font-size: 12px;"><?php echo LANG_UseSmtpAuth?></label>
								</td>
							</tr>
						</table>
						</div>
<?php endif; ?>

						<div class="<?php echo (USE_DB) ? 'login_table_block': 'wm_hide'; ?>">
							<input tabindex="12" class="wm_checkbox" type="checkbox" value="1" id="sign_me" name="sign_me" />
							<label for="sign_me" id="lang_SignMe" style="font-size: 12px;"><?php echo LANG_SignMe?></label>
						</div>
						<div class="login_table_block wm_login_button">
<?php if ($bUseAdvancedLogin): ?>
							<br />
							<a tabindex="13" class="wm_reg" style="float: left;" href="#" id="login_mode_switcher" onclick="return false;"><?php echo JS_LANG_AdvancedLogin?></a>
<?php endif; ?>
							<input tabindex="14" class="wm_button" type="submit" id="submit" name="submit" value="<?php echo LANG_Enter?>" />
							<?php echo $sLangDiv; ?>
						</div>
					</div>
				</div>
				<div class="b"></div>
				<div class="a"></div>
			</div>
		</form>
	</div>
	<div class="info" id="demo_info" dir="ltr">
<?php
		echo $sDemoFootPart;

		if ($oSettings->GetConf('WebMail/AllowLanguageOnLogin') && $oSettings->GetConf('WebMail/FlagsLangSelect')):
?>
		<div class="top">
			<div style="clear: both; margin: 0; padding: 0;height: 0; overflow: hidden;"></div>
			<div class="r2"></div>
			<div class="r1"></div>
		</div>
		<div class="middle">
            <div class="title">WebMail in your language</div>
			<div style="width: 40%; float: left; margin-left: 30px;" id="langDemoTop">
				<a name="lng_English"  href="#" class="sprite lang_eng" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;"><span class="sprite lang_usa"></span>English</a><br />
				<a name="lng_Arabic" href="#" class="sprite lang_arb" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">العربية</a><br />
				<a name="lng_Chinese-Simplified" href="#" class="sprite lang_ch_simple" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">中文(简体)</a><br />
				<a name="lng_Chinese-Traditional" href="#" class="sprite lang_ch_tr" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">中文(香港)</a><br />
				<a name="lng_Danish" href="#" class="sprite lang_dan" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Dansk</a><br />
				<a name="lng_Dutch" href="#" class="sprite lang_dut" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Nederlands</a><br />
				<a name="lng_French" href="#" class="sprite lang_frn" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Français</a><br />

				<a name="lng_German" href="#" class="sprite lang_gmn" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Deutsch</a><br />
				<a name="lng_Greek" href="#" class="sprite lang_grk" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Ελληνικά</a><br />
				<a name="lng_Hebrew" href="#" class="sprite lang_hbw" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">עברית</a><br />
				<a name="lng_Hungarian" href="#" class="sprite lang_hng" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Magyar</a>

			</div>

			<div style="width: 45%; float: left;" id="langDemoBottom">
				<a name="lng_Italian" href="#" class="sprite lang_itl" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Italiano</a><br />
				<a name="lng_Japanese" href="#" class="sprite lang_jap" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">日本語</a><br />
				<a name="lng_Norwegian" href="#" class="sprite lang_nrw" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Norsk</a><br />
				<a name="lng_Polish" href="#" class="sprite lang_pls" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Polski</a><br />
				<a name="lng_Portuguese-Brazil" href="#" class="sprite lang_prt" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Portuguese-Brazil</a><br />
				<a name="lng_Russian" href="#" class="sprite lang_rss" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Русский</a><br />
				<a name="lng_Spanish" href="#" class="sprite lang_spn" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Español</a><br />

				<a name="lng_Swedish" href="#" class="sprite lang_swd" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Svenska</a><br />
				<a name="lng_Thai" href="#" class="sprite lang_tha" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">ภาษาไทย</a><br />
				<a name="lng_Turkish" href="#" class="sprite lang_trk" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Türkçe</a><br />
				<a name="lng_Ukrainian" href="#" class="sprite lang_ukr" onclick="LoginDemoLangClass.checkLang(this.name); if (LoginScreen) LoginScreen.changeLang(this); return false;">Українська</a><br />
			</div>
			<div class="clear"></div>
		</div>
		<div class="bottom">
			<div class="r1"></div>
			<div class="r2"></div>
			<div style="clear: both; margin: 0; padding: 0; height: 0; overflow: hidden;"></div>
		</div>
		<?php endif; ?>
	</div>
<?php if ($oDomain->AllowRegistration): ?>
	<div class="wm_reg_link">
		<a href="reg.php" id="reg_link_id"><?php echo IndexRegLink; ?></a>
	</div>
<?php endif; ?>

	<div id="dummy"></div>
</div>
<div class="wm_copyright" id="copyright">
<?php require('inc.footer.php'); ?>
</div>
</body>
</html>
<?php
	echo '<!-- '.WMVERSION.' -->';
