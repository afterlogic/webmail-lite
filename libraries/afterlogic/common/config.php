<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

$sCurrentDate = date('Y-m-d');

return array(

	/**
	 * File used for webmail logging
	 */
	'log.log-file' => "log-$sCurrentDate.txt",

	/**
	 * File used for webmail logging
	 */
	'log.custom-full-path' => '',

	/**
	 * File used for for users activity logging
	 */
	'log.event-file' => "event-$sCurrentDate.txt",

	/**
	 * The setting defines size of the log file extract available through adminpanel (in Kbytes)
	 */
	'log.max-view-size' => 1000,

	/**
	 * Socket connection timeout limit (in seconds)
	 */
	'socket.connect-timeout' => 20,

	/**
	 * Socket stream access timeout (in seconds)
	 */
	'socket.get-timeout' => 20,

	'socket.verify-ssl' => false,

	/**
	 * Encoding used by default if not specified in a message
	 */
	'webmail.default-inc-charset' => 'iso-8859-1',

	/**
	 * Encoding used for composing mails
	 */
	'webmail.default-out-charset' => 'utf-8',

	/**
	 * Defines whether messages are prefetched to minimize response time
	 * when selecting a message, WebMail Pro fetches messages from server in background.
	 */
	'webmail.use-prefetch' => true,

	/**
	 * Languages considered to be RTL ones by WebMail
	 */
	'webmail.rtl-langs' => array('Hebrew', 'Arabic', 'Persian'),

	/**
	 * X-Mailer value used in outgoing mails
	 */
	'webmail.xmailer-value' => 'AfterLogic webmail client',

	/**
	 * IMAP4 only
	 * Allow creating system folders if those are not found on mail server
	 * WebMail attempts to locate special (system) folders like Trash, Drafts, Sent Items.
	 * If particular folder is not found, WebMail can create it, and you can disable this of course.
	 */
	'webmail.create-imap-system-folders' => true,

	/**
	 * Configuration option for creating required folders on each login.
	 */
	'webmail.system-folders-sync-on-each-login' => false,

	/**
	 * IMAP4 only
	 * Flag used for marking message as Forwarded
	 * If empty, the functionality is disabled
	 */
	'webmail.forwarded-flag-name' => '$Forwarded',

	/**
	 * Memory limit set by WebMail for resource-consuming operations (in Mbytes)
	 */
	'webmail.memory-limit' => 200,

	/**
	 * Time limit set by WebMail for resource-consuming operations (in seconds)
	 */
	'webmail.time-limit' => 3000,

	/**
	 * Max number of contacts, for autocompletion drop-down
	 */
	'webmail.suggest-contacts-limit' => 20,

	/**
	 * Enable saving drafts automatically. Saving is performed once a minute,
	 * assuming it is supported by particular IMAP server.
	 * Default value: true
	 */
	'webmail.autosave' => true,

	'mailsuite' => false,

	'files' => true,

	'tenant' => false,

	'helpdesk' => false,

	'capa' => false,

	'themes' => array('Default', 'DeepForest', 'OpenWater', 'Funny', 'BlueJeans', 'White'),

	/**
	 * Which email should be treated as primary one in contact object
	 * Supported values:
	 *     EPrimaryEmailType::Home, EPrimaryEmailType::Business, EPrimaryEmailType::Other
	 */
	'contacts.default-primary-email' => EPrimaryEmailType::Home,

	'links.importing-contacts' => '',
	
	'links.outlook-sync-plugin-32' => 'http://www.afterlogic.com/download/OutlookSyncAddIn.msi',
	'links.outlook-sync-plugin-64' => 'http://www.afterlogic.com/download/OutlookSyncAddIn64.msi',
	'links.outlook-sync-read-more' => 'http://www.afterlogic.com/wiki/Outlook_sync_(Aurora)',

	/*
	 * temp.cron-time-*
	 * The settings affect functionality of purging folder of temporary files
	 * when API method CApiWebmailManager->ClearTempFiles() is called
	 */

	/**
	 * Minimal timeframe between two runs of cron script (in seconds).
	 */
	'temp.cron-time-to-run' => 10800, // (3 hours)

	/**
	 * If file is older than this it is considered outdated
	 */
	'temp.cron-time-to-kill' => 10800, // (3 hours)

	/**
	 * This file stores information on last launch of the script
	 */
	'temp.cron-time-file' => '.clear.dat',

	'langs.names' => array(
		'Arabic' => 'العربية',
		'Bulgarian' => 'Български',
		'Danish' => 'Dansk',
		'Dutch' => 'Nederlands',
		'English' => 'English',
		'French' => 'Français',
		'German' => 'Deutsch',
		'Greek' => 'Ελληνικά',
		'Hebrew' => 'עברית',
		'Hungarian' => 'Magyar',
		'Italian' => 'Italiano',
		'Norwegian' => 'Norsk',
		'Portuguese-Portuguese' => 'Português',
		'Portuguese-Brazil' => 'Português Brasileiro',
		'Polish' => 'Polski',
		'Russian' => 'Русский',
		'Spanish' => 'Español',
		'Swedish' => 'Svenska',
		'Thai' => 'ภาษาไทย',
		'Turkish' => 'Türkçe',
		'Ukrainian' => 'Українська',
		'Japanese' => '日本語',
		'Chinese-Simplified' => '中文(简体)',
		'Chinese-Traditional' => '中文(香港)',
		'Korean' => '한국어',
		'Czech' => 'Čeština'
	),

	/**
	 * Enable plugins in WebMail
	 */
	'plugins' => true,

	/**
	 * Force enabling all the plugins.
	 */
	'plugins.config.include-all' => false,

	// labs.*
	// Experimental settings
	'labs.db.use-explain' => false,
	'labs.db.use-explain-extended' => false,
	'labs.db.log-query-params' => false,
	'labs.htmleditor-default-font-name' => '',
	'labs.htmleditor-default-font-size' => '',
	'labs.log.post-view' => false,
	'labs.allow-social-integration' => true,
	'labs.use-app-min-js' => true,
	'labs.webmail.gmail-fix-folders' => true,
	'labs.webmail.custom-login-url' => '',
	'labs.webmail.custom-logout-url' => '',
	'labs.webmail.disable-folders-manual-sort' => false,
	'labs.webmail.ios-detect-on-login' => true,
	'labs.webmail.display-server-error-information' => false,
	'labs.webmail.display-inline-css' => false,
	'labs.allow-thumbnail' => true,
	'labs.allow-post-login' => false,
	'labs.allow-save-as-pdf' => false,
	'labs.dav.use-browser-plugin' => false,
	'labs.dav.use-export-plugin' => true,
	'labs.dav.use-files' => false,
	'labs.dav.admin-principal' => 'principals/admin',
	'labs.cache.i18n' => true,
	'labs.cache.templates' => true,
	'labs.cache.static' => true,
	'labs.twilio' => false,
	'labs.voice' => false,
	'labs.open-pgp' => true,
	'labs.webmail.csrftoken-protection' => true,
	'labs.webmail-client-debug' => false,
	'labs.x-frame-options' => '',
	'labs.fetchers' => true,
	'labs.simple-saas-api-key' => '',
	'labs.message-body-size-limit' => 25000,
	'labs.unlim-quota-limit-size-in-kb' => 104857600,
	'labs.use-date-from-headers' => false,
	'labs.use-body-structures-for-has-attachments-search' => false,
	'labs.google-analytic.account' => '',
	'labs.app-cookie-path' => '/',
	'labs.prefer-starttls' => true,
	'labs.server-use-url-rewrite' => false,
	'labs.server-url-rewrite-base' => '',
	'labs.db-debug-backtrace-limit' => 0,
	'labs.allow-officeapps-viewer' => true,
	'labs.mail-expand-folders' => false,
	'labs.i18n' => 'en',
	
	/* Enable Social Auth plugin */
	'plugins.social-auth' => true,
	'plugins.social-auth.connectors' => array(
		'google',
		'facebook',
		'twitter',
		'dropbox',
		'vk'
	),
	
);
