<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

//	api
	include_once WM_ROOTPATH.'libraries/afterlogic/api.php';

//	base
	include_once WM_ROOTPATH.'application/constants.php';
	include_once WM_ROOTPATH.'application/functions.php';

	CSession::$sSessionName = 'PHPWEBMAILSESSID';

	// CSRF protection
	if (CApi::GetConf('labs.webmail.csrftoken-protection', false) && empty($_COOKIE[APP_COOKIE_CSRF_TOKEN_KEY]))
	{
		@setcookie(APP_COOKIE_CSRF_TOKEN_KEY, md5(microtime(true).md5(rand(10000, 99999))), time() + 60 * 60 * 24 * 356 * 2, '/');
	}
