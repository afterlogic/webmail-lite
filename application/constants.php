<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

define('APP_SESSION_ACCOUNT_ID', 'id_account');
define('APP_SESSION_USER_ID', 'AUserId');
define('APP_SESSION_LANG', 'session_lang');

define('APP_SESSION_RESET_STEP', 'sessionresetstep');
define('APP_SESSION_RESET_ACCT_ID', 'sessionresetacctid');

define('APP_DUMMYPASSWORD', '*******');

define('APP_COOKIE_CSRF_TOKEN_KEY', 'awmcsrftoken');

define('APP_MESSAGE_LIST_FILTER_NONE', 0);
define('APP_MESSAGE_LIST_FILTER_UNSEEN', 1);
define('APP_MESSAGE_LIST_FILTER_WITH_ATTACHMENTS', 2);

defined('INI_DIR') || define('INI_DIR', CApi::DataPath());
defined('APP_DEFAULT_OUTPUT_CHARSET') || define('APP_DEFAULT_OUTPUT_CHARSET', CApi::GetConf('webmail.default-out-charset', 'utf-8'));
