<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
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
	define('AP_SESS_AUTH_REALM_ID', 'apsessauthrealmid');

	define('AP_SESS_AUTH_TYPE_NONE', -1);
	define('AP_SESS_AUTH_TYPE_SUPER_ADMIN', 0);
	define('AP_SESS_AUTH_TYPE_SUPER_ADMIN_ONLYREAD', 2);
	define('AP_SESS_AUTH_TYPE_REALM', 3);

	define('AP_SESS_TAB', 'apsesstab');
	define('AP_SESS_MODE', 'apsessmode');
	define('AP_SESS_PAGE', 'apsesspage');
	define('AP_SESS_FILTER', 'apsessfilter');
	define('AP_SESS_SEARCHDESC', 'apsesssearchdesc');
	define('AP_SESS_STANDARD_FILTER', 'apsessstandardfilter');

	define('AP_SESS_DOMAIN_NEXT_EDIT_ID', 'apsessdomainnexteditid');

	define('AP_TAB_SERVICES', 'services');
	define('AP_TAB_DOMAINS', 'domains');
	define('AP_TAB_USERS', 'users');
	define('AP_TAB_REALMS', 'realms');
	define('AP_TAB_CHANNELS', 'channels');
	define('AP_TAB_SYSTEM', 'system');

	define('AP_TAB_DEFAULT', AP_TAB_SYSTEM);
	define('AP_TAB_REALM_DEFAULT', AP_TAB_DOMAINS);

	define('AP_ORDER_ASC', 0);
	define('AP_ORDER_DESC', 1);
	define('AP_ORDER_DEFAULT', AP_ORDER_ASC);

	define('AP_LINES_PER_PAGE', 20);

	define('AP_SESS_MESSAGE', 'apsessmessage');
	define('AP_SESS_ERROR', 'apsesserror');

	// lang
	define('AP_LANG_SAVING', 'Saving ...');
	define('AP_LANG_RESULTEMPTY', 'The result is empty');

	define('AP_LANG_SAVESUCCESSFUL', 'Saved successfully.');
	define('AP_LANG_SAVEUNSUCCESSFUL', 'Failed to save.');
	define('AP_LANG_CONNECTSUCCESSFUL', 'Connected successfully.');
	define('AP_LANG_CONNECTUNSUCCESSFUL', 'Failed to connect.');
	define('AP_LANG_ERROR', 'Error');

	define('AP_LANG_DELETE_SUCCESSFUL', 'Deleted successfully.');
	define('AP_LANG_DELETE_UNSUCCESSFUL', 'Failed to delete.');

	define('AP_LANG_ADMIN_ONLY_READ', 'This is just an AdminPanel demo, saving changes or viewing private data is disabled.');
	define('AP_REQ_FIELDS_CANNOT_BE_EMPTY', 'Required fields cannot be empty.');
	define('AP_REQ_VALID_PORT', 'Required valid port.');

	define('AP_LANG_LOGIN_AUTH_ERROR', 'Wrong login and/or password. Authentication failed.');
	define('AP_LANG_LOGIN_SESS_ERROR', 'Previous session was terminated due to a timeout.');
	define('AP_LANG_LOGIN_ACCESS_ERROR', 'An attempt of unauthorized access.');

	define('AP_WITHOUT_DOMAIN_NAME', '[Users not in domain]');
