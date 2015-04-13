<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package WebMail
 */
$aJsFilesMap = array(

	'jquery' => array(
		'js/libs/jquery-1.6.2.min.js',
		'js/libs/jquery-ui-1.8.14.custom.min.js',
		'js/libs/json2.min.js'
	),

	'login' => array(
		'js/login/login-screen.js'
	),

	'reg' => array(
		'js/login/reg-screen.js'
	),

	'reset' => array(
		'js/login/password-reset-screen.js'
	),

	'common' => array(
		'js/common/common-helpers.js',
		'js/common/popups.js'
	),

	'def' => array(
		'js/common/defines.js',
		'js/common/common-helpers.js',
		'js/common/loaders.js',
		'js/common/functions.js',
		'js/common/popups.js'
	),

	'wm' => array(
		'js/common/calendar-screen.js',
		'js/common/common-handlers.js',
		'js/common/data-source.js',
		'js/common/page-switcher.js',
		'js/common/toolbar.js',
		'js/common/variable-table.js',
		'js/common/webmail.js',

		'js/mail/autocomplete-recipients.js',
		'js/mail/context-menu.js',
		'js/mail/digdos.js',
		'js/mail/folders-pane.js',
		'js/mail/html-editor.js',
		'js/mail/mail-data.js',
		'js/mail/message-headers.js',
		'js/mail/message-info.js',
		'js/mail/message-line.js',
		'js/mail/message-list-prototype.js',
		'js/mail/message-list-central-pane.js',
		'js/mail/message-list-central-screen.js', // need to load after message-list-prototype.js
		'js/mail/message-list-display.js',
		'js/mail/message-list-top-screen.js', // need to load after message-list-prototype.js
		'js/mail/new-message-screen.js',
		'js/mail/message-reply-pane.js', // need to load after new-message-screen.js
		'js/mail/message-view-pane.js',
		'js/mail/resizers.js',
//		'js/mail/server-based-data.js',
		'js/mail/fileuploader.js',
		'js/contacts/contacts-data.js',
		'js/mail/remoting.js'
	),

	'wmp' => array(
		'js/mail/mail-handlers.js'
	),

	'mini' => array(
		'js/mail/mini-webmail-window.js'
	),

	'cont' => array(
		'js/contacts/contact-line.js',
		'js/contacts/contacts-handlers.js',
		'js/contacts/contacts-screen.js',
		'js/contacts/edit-contact.js',
		'js/contacts/edit-group.js',
		'js/contacts/import.js',
		'js/contacts/view-contact.js',
		'js/settings/account-list.js',
		'js/settings/account-properties.js',
		'js/settings/autoresponder.js',
		'js/settings/calendar.js',
		'js/settings/common.js',
		'js/settings/filters.js',
		'js/settings/folders.js',
		'js/settings/forward.js',
		'js/settings/identities.js',
		'js/settings/mobile-sync.js',
		'js/settings/outlook-sync.js',
		'js/settings/settings-data.js',
		'js/settings/signature.js',
//		'js/settings/custom.js',
		'js/settings/user-settings-screen.js'
	),

	'cal_def' => array(
		'js/common/common-helpers.js',
		'js/common/popups.js',
		'js/common/defines.js',
		'js/common/data-source.js',
		'js/common/functions.js',
		'js/common/loaders.js',
		'js/common/webmail.js',
		'js/mail/html-editor.js'
	),

	'cal' => array(
		'calendar/js/cal.lib.js',
		'calendar/js/cal.userforms.js',
		'calendar/js/cal.class.calendars.js',
		'calendar/js/cal.class.eventform.js',
		'calendar/js/cal.class.sharingform.js',
		'calendar/js/cal.class.chooseform.js',
		'calendar/js/cal.class.reminder.js',
		'calendar/js/cal.class.appointment.js',
		'calendar/js/cal.class.selection.js'
	),

	'cal_f' => array(
		'calendar/js/cal.functions.js',
		'calendar/js/cal.class.grid.js',
		'calendar/js/cal.class.calendartable.js'
	),

	'cal_p' => array(
		'calendar/js/pub.lib.js',
		'calendar/js/pub.userforms.js'
	)
);