<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 * @package Api
 * @ignore
 */

define('API_PATH_TO_WEBMAIL', '/../../');

define('API_CRLF', "\r\n");
define('API_TAB', "\t");
define('API_P7', true);

define('API_SESSION_WEBMAIL_NAME', 'PHPWEBMAILSESSID');
define('API_SESSION_ADMINPANEL_NAME', 'PHPWMADMINSESSID');
define('API_SESSION_CSRF_TOKEN', 'API_CSRF_TOKEN');

define('API_INC_PROTOCOL_POP3_DEF_PORT', 110);
define('API_INC_PROTOCOL_IMAP4_DEF_PORT', 143);
define('API_INC_PROTOCOL_SMTP_DEF_PORT', 25);

define('API_DEFAULT_SKIN', 'Default');
define('API_DUMMY', '*******');

// timezone fix
$sDefaultTimeZone = function_exists('date_default_timezone_get')
	? @date_default_timezone_get() : 'US/Pacific';

define('API_SERVER_TIME_ZONE', ($sDefaultTimeZone && 0 < strlen($sDefaultTimeZone))
	? $sDefaultTimeZone : 'US/Pacific');

if (defined('API_SERVER_TIME_ZONE') && function_exists('date_default_timezone_set'))
{
	@date_default_timezone_set(API_SERVER_TIME_ZONE);
}

unset($sDefaultTimeZone);
