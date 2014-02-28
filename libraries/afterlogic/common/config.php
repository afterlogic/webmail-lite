<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

$sCurrentDate = date('Y-m-d');

return array(

	/**
	 * File used for webmail logging
	 */
	'log.log-file' => "log-$sCurrentDate.txt",

	/**
	 * File used for for users activity logging
	 */
	'log.event-file' => "event-$sCurrentDate.txt",

	/**
	 * The setting defines size of the log file extract available through adminpanel (in Kbytes)
	 */
	'log.max-view-size' => 100,

	/**
	 * Socket connection timeout limit (in seconds)
	 */
	'socket.connect-timeout' => 20,

	/**
	 * Socket stream access timeout (in seconds)
	 */
	'socket.get-timeout' => 20,

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
	 * Number of prefetched message lists aside from prefetching messages themselves,
	 * WebMail Pro would also prefetch first page of message list for every folder.
	 * This configuration option limits number of folders scanned.
	 */
	'webmail.folder-base-limit' => 5,

	/**
	 * Max. size of message allowed to be prefetched
	 */
	'webmail.preload-body-size'	=> 76800,

	/**
	 * Max number of contacts, for autocompletion drop-down
	 */
	'webmail.suggest-contacts-limit' => 20,

	/**
	 * Enable saving drafts automatically. Saving is performed once a minute,
	 * assuming it is supported by particular IMAP server.
	 * Works for POP3 accounts as well.
	 * Default value: true
	 */
	'webmail.autosave' => true,

	/**
	 * IMAP4 only
	 * Defines upper limit for message size allowed for loading completely
	 * If message size is less then that, it will be loaded in full,
	 * partially otherwise (using bodystructure request)
	 */
	'webmail.bodystructure-message-size-limit' => 20000,

	'mailsuite' => false,

	'files' => false,

	'helpdesk' => false,

	'capa' => false,

	'themes' => array('Default', 'White'),

	/**
	 * Which email should be treated as primary one in contact object
	 * Supported values:
	 *     EPrimaryEmailType::Home, EPrimaryEmailType::Business, EPrimaryEmailType::Other
	 */
	'contacts.default-primary-email' => EPrimaryEmailType::Home,

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
		'Portuguese-Brazil' => 'Português Brasil',
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
	'labs.log.post-view' => false,
	'labs.allow-social-integration' => false,
	'labs.use-app-min-js' => true,
	'labs.webmail.gmail-fix-folders' => true,
	'labs.webmail.custom-login-url' => '',
	'labs.webmail.custom-logout-url' => '',
	'labs.webmail.disable-folders-manual-sort' => false,
	'labs.webmail.ios-detect-on-login' => true,
	'labs.allow-thumbnail' => true,
	'labs.allow-post-login' => false,
	'labs.dav.use-browser-plugin' => false,
	'labs.dav.use-export-plugin' => true,
	'labs.dav.use-files' => false,
	'labs.dav.admin-principal' => 'principals/admin',
	'labs.cache.i18n' => true,
	'labs.cache.templates' => true,
	'labs.cache.static' => true,
	'labs.twilio' => false,
	'labs.voice' => false,
	'labs.webmail.csrftoken-protection' => true,
	'labs.webmail-client-debug' => false,
	'labs.fetchers' => true,
	'labs.simple-saas-api-key' => '',
	'labs.unlim-quota-limit-size-in-kb' => 104857600,
	'labs.use-date-from-headers' => false,
	'labs.google-analytic.account' => '',
	'labs.app-cookie-path' => '/',
	'labs.force-depricated-mysql' => false,
	'labs.server-use-url-rewrite' => false,
	'labs.server-url-rewrite-base' => '',
	'labs.i18n' => 'en'
);
