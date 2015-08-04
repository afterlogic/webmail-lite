<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

	// constants
	define('AP_CRLF', "\r\n");
	define('AP_HTML_BR', '<br />');
	define('AP_TAB', "\t");

	define('AP_LOG_FILE', 'admin.log');

	define('AP_USE_DB', true);

	define('AP_MEMORYLIMIT', '50M');
	define('AP_TIMELIMIT', '60');

	define('AP_DUMMYPASSWORD', '**********');

	define('AP_DB_MSSQLSERVER', 1);
	define('AP_DB_MYSQL', 3);

	define('AP_START_TIME', 'apstarttime');
	define('AP_DB_COUNT', 'apdbcount');

	define('AP_SESS_AUTH', 'apsessauth');
	define('AP_SESS_AUTH_TYPE', 'apsessauthtype');
	define('AP_SESS_AUTH_DOMAINS', 'apsessauthdomains');
	define('AP_SESS_AUTH_TENANT_ID', 'apsessauthtenantid');

	define('AP_SESS_AUTH_TYPE_NONE', -1);
	define('AP_SESS_AUTH_TYPE_SUPER_ADMIN', 0);
	define('AP_SESS_AUTH_TYPE_SUPER_ADMIN_ONLYREAD', 2);
	define('AP_SESS_AUTH_TYPE_TENANT', 3);

	define('AP_SESS_TAB', 'apsesstab');
	define('AP_SESS_MODE', 'apsessmode');
	define('AP_SESS_PAGE', 'apsesspage');
	define('AP_SESS_FILTER', 'apsessfilter');
	define('AP_SESS_SEARCHDESC', 'apsesssearchdesc');
	define('AP_SESS_STANDARD_FILTER', 'apsessstandardfilter');

	define('AP_SESS_DOMAIN_NEXT_EDIT_ID', 'apsessdomainnexteditid');

	define('AP_TAB_DOMAINS', 'domains');
	define('AP_TAB_USERS', 'users');
	define('AP_TAB_TENANTS', 'tenants');
	define('AP_TAB_CHANNELS', 'channels');
	define('AP_TAB_SYSTEM', 'system');
	define('AP_TAB_COMMON', 'common');

	define('AP_TAB_DEFAULT', AP_TAB_SYSTEM);
	define('AP_TAB_TENANT_DEFAULT', AP_TAB_COMMON);

	define('AP_ORDER_ASC', 0);
	define('AP_ORDER_DESC', 1);
	define('AP_ORDER_DEFAULT', AP_ORDER_ASC);

	define('AP_LINES_PER_PAGE', 20);

	define('AP_SESS_MESSAGE', 'apsessmessage');
	define('AP_SESS_ERROR', 'apsesserror');

	// lang
	define('AP_LANG_SAVING', CApi::I18N('ADMIN_PANEL/MSG_SAVING'));
	define('AP_LANG_RESULTEMPTY', CApi::I18N('ADMIN_PANEL/MSG_RESULTEMPTY'));

	define('AP_LANG_SAVESUCCESSFUL', CApi::I18N('ADMIN_PANEL/MSG_SAVESUCCESSFUL'));
	define('AP_LANG_SAVEUNSUCCESSFUL', CApi::I18N('ADMIN_PANEL/MSG_SAVEUNSUCCESSFUL'));
	define('AP_LANG_CONNECTSUCCESSFUL', CApi::I18N('ADMIN_PANEL/MSG_CONNECTSUCCESSFUL'));
	define('AP_LANG_CONNECTUNSUCCESSFUL', CApi::I18N('ADMIN_PANEL/MSG_CONNECTUNSUCCESSFUL'));
	define('AP_LANG_ERROR', CApi::I18N('ADMIN_PANEL/MSG_ERROR'));

	define('AP_LANG_DELETE_SUCCESSFUL', CApi::I18N('ADMIN_PANEL/MSG_DELETE_SUCCESSFUL'));
	define('AP_LANG_DELETE_UNSUCCESSFUL', CApi::I18N('ADMIN_PANEL/MSG_DELETE_UNSUCCESSFUL'));

	define('AP_LANG_ADMIN_ONLY_READ', CApi::I18N('ADMIN_PANEL/MSG_ADMIN_ONLY_READ'));
	define('AP_REQ_FIELDS_CANNOT_BE_EMPTY', CApi::I18N('ADMIN_PANEL/MSG_FIELDS_CANNOT_BE_EMPTY'));

	define('AP_REQ_VALID_PORT', CApi::I18N('ADMIN_PANEL/MSG_INVALID_PORT'));

	define('AP_LANG_LOGIN_AUTH_ERROR', CApi::I18N('ADMIN_PANEL/MSG_LOGIN_AUTH_ERROR'));
	define('AP_LANG_LOGIN_SESS_ERROR', CApi::I18N('ADMIN_PANEL/MSG_LOGIN_SESS_ERROR'));
	define('AP_LANG_LOGIN_ACCESS_ERROR', CApi::I18N('ADMIN_PANEL/MSG_LOGIN_ACCESS_ERROR'));

	define('AP_WITHOUT_DOMAIN_NAME', CApi::I18N('ADMIN_PANEL/INFO_NOT_IN_DOMAIN'));
