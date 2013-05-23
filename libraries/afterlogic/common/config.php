<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
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
	 * Defines whether javascript files compression and merging is used
	 * to speed up loading WebMail Pro interface, JavaScript files are compressed on server.
	 * You might need to turn this off, in some cases, particularly if you feel that
	 * GZip compression doesn't work on your server as expected.
	 */
	'js.use-js-gzip' => true,

	/**
	 * Number of subsequent login errors which invokes CAPTCHA
	 */
	'captcha.limit-count' => 3,

	/**
	 * Keys for configuring reCaptcha
	 */
	'captcha.recaptcha-private-key' => '6LefZb0SAAAAAK5E2Bh8Cg7XOTf9UkBXgStn8ZXF',
	'captcha.recaptcha-public-key' => '6LefZb0SAAAAADpYFCCIQakNCvNJTJVdBLp3gFAW',

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
	 * The file used to launch WebMail in IFrame (e.g. iframe-webmail.php)
	 * This is especially useful if you embed WebMail Pro into your own web application.
	 * If set to null, WebMail is launched in regular way (not in IFrame)
	 */
	'webmail.use-iframe' => null, // 'iframe-webmail.php'

	/**
	 * Languages considered to be RTL ones by WebMail
	 */
	'webmail.rtl-langs' => array('Hebrew', 'Arabic', 'Persian'),

	/**
	 * X-Mailer value used in outgoing mails
	 */
	'webmail.xmailer-value' => 'AfterLogic WebMail PHP',

	/**
	 * Additional character interface for filtering contacts in address book
	 */
	'webmail.allow-first-character-search' => false,

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

	/**
	 *
	 */
	'cdn.prefix' => '',

	/**
	 *
	 */
	'mailsuite' => false,

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

	/**
	 *
	 */
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
		'PortugueseBrazil' => 'Português Brasil',
		'Polish' => 'Polski',
		'Russian' => 'Русский',
		'Spanish' => 'Español',
		'Swedish' => 'Svenska',
		'Thai' => 'ภาษาไทย',
		'Turkish' => 'Türkçe',
		'Ukrainian' => 'Українська',
		'Japanese' => '日本語',
		'ChineseSimplified' => '中文(简体)',
		'ChineseTraditional' => '中文(香港)',
		'Korean' => '한국어',
		'Czech' => 'Čeština'
	),

	'realm' => false,

	'capa' => false,

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
	'labs.log.specified-by-user' => false,
	'labs.cache.settings-xml-in-php-file' => false,
	'labs.sieve.use-starttls' => false,
	'labs.webmail.gmail-fix-folders' => true,
	'labs.dav.use-browser-plugin' => false,
	'labs.dav.use-export-plugin' => false,
	'labs.dav.use-files' => false,
	'labs.dav.admin-principal' => 'principals/admin',
	'labs.webmail.disable-pop3-accounts' => false,
	'labs.webmail.csrftoken-protection' => true,
	'labs.unlim-quota-limit-size-in-kb' => 104857600,
	'labs.contacts.allow-multiple-contacts' => false,
	'labs.i18n' => 'en'
);
