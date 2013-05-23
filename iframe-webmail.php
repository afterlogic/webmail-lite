<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
include_once WM_ROOTPATH.'application/include.php';
$oInput = new api_Http();

$iAccountId = CSession::Get(APP_SESSION_ACCOUNT_ID, false);

$oAccount = AppGetAccount($iAccountId);
/* @var $oAccount CAccount */
if ($oAccount)
{
	AppIncludeLanguage($oAccount->User->DefaultLanguage);

	$start = (int) $oInput->GetQuery('start', 0);
	$to = $oInput->GetQuery('to', '');

	$params = array();
	if ($start > 0)
	{
		$params[] = 'start='.$start;
	}
	if (strlen($to) > 0)
	{
		$params[] = 'to='.$to;
	}

	define('defaultTitle', AppGetSiteName($oAccount));

	header('Content-type: text/html; charset=utf-8');
	header('Content-script-type: text/javascript');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="favicon.ico" />
	<title><?php echo defaultTitle; ?></title>
</head>
<frameset rows="100,*" cols="*">
	<frame src="empty.html" name="topFrame" scrolling="no" noresize="noresize" />
	<frameset cols="100,*,100">
		<frame src="empty.html" name="leftFrame" scrolling="no" noresize="noresize" frameborder="0" />
		<frame src="webmail.php?iframe&<?php echo implode('&', $params); ?>" name="mainFrame" frameborder="0" />
		<frame src="empty.html" name="rightFrame" scrolling="no" noresize="noresize" frameborder="0" />
	</frameset>
</frameset>
</html><?php echo '<!-- '.WMVERSION.' -->';

}
