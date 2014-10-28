<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

	define('BU_MODE_SMTP', 'smtp');
	define('BU_MODE_POP3IMAP', 'pop3imap');
	define('BU_MODE_LOGGING', 'logging');
	define('BU_MODE_SPAM_VIRUS', 'spamvirus');
	define('BU_MODE_LDAP', 'ldap');

	define('BU_SWITCHER_MODE_EDIT_DOMAIN_GENERAL', 'editgeneral');
	define('BU_SWITCHER_MODE_EDIT_USER_GENERAL', 'editgeneral');
	define('BU_SWITCHER_MODE_EDIT_USER_ALIASES', 'editaliases');
	define('BU_SWITCHER_MODE_EDIT_USER_FORWARDS', 'editforwards');
	define('BU_SWITCHER_MODE_EDIT_LIST_GENERAL', 'editlist');
	define('BU_SWITCHER_MODE_NEW_MAIL_LIST', 'newmaillist');

	/* langs */

	define('BU_MODE_SMTP_NAME', 'SMTP');
	define('BU_MODE_POP3IMAP_NAME', 'POP3 / IMAP');
	define('BU_MODE_LOGGING_NAME', 'Logging');
	define('BU_MODE_SPAM_VIRUS_NAME', 'Spam & Anti-Virus');
	define('BU_MODE_LDAP_NAME', 'LDAP');

	define('BU_SWITCHER_MODE_EDIT_DOMAIN_GENERAL_NAME', CApi::I18N('ADMIN_PANEL/EDIT_DOMAIN_GENERAL_NAME'));
	define('BU_SWITCHER_MODE_EDIT_USER_GENERAL_NAME', CApi::I18N('ADMIN_PANEL/EDIT_USERS_GENERAL_NAME'));
	define('BU_SWITCHER_MODE_EDIT_USER_ALIASES_NAME', CApi::I18N('ADMIN_PANEL/EDIT_USERS_ALIASES_NAME'));
	define('BU_SWITCHER_MODE_EDIT_USER_FORWARDS_NAME', CApi::I18N('ADMIN_PANEL/EDIT_USERS_FORWARD_NAME'));
	define('BU_SWITCHER_MODE_EDIT_LIST_GENERAL_NAME', CApi::I18N('ADMIN_PANEL/EDIT_USERS_GENERAL_NAME'));
	define('BU_SWITCHER_MODE_NEW_MAIL_LIST_NAME', CApi::I18N('ADMIN_PANEL/TOOLBAR_BUTTON_NEW_LIST'));