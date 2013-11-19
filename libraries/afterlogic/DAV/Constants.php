<?php

namespace afterlogic\DAV;

class Constants
{
	const T_ACCOUNTS = 'awm_accounts';
	const T_PRINCIPALS = 'adav_principals';
	const T_GROUPMEMBERS = 'adav_groupmembers';
	const T_DELEGATES = 'adav_delegates';
	const T_CALENDARS = 'adav_calendars';
	const T_CALENDAROBJECTS = 'adav_calendarobjects';
	const T_ADDRESSBOOKS = 'adav_addressbooks';
	const T_CARDS = 'adav_cards';
	const T_LOCKS = 'adav_locks';
	const T_CACHE = 'adav_cache';
	
	const GLOBAL_CONTACTS = 'Global Contacts';
	const CALENDAR_DEFAULT_NAME = 'Default';
	const CALENDAR_DEFAULT_COLOR = '#F09650';

	const ADDRESSBOOK_DEFAULT_NAME = 'Default';
	const ADDRESSBOOK_DEFAULT_DISPLAY_NAME = 'Address Book';
	const ADDRESSBOOK_DEFAULT_DISPLAY_NAME_OLD = 'General';
	const ADDRESSBOOK_COLLECTED_NAME = 'Collected';
	const ADDRESSBOOK_COLLECTED_DISPLAY_NAME = 'Collected Addresses';
	
	const DAV_PUBLIC_PRINCIPAL = 'caldav_public_user@localhost';
	
	const DAV_USER_AGENT = 'AfterlogicDAVClient';
	const DAV_SERVER_NAME = 'AfterlogicDAVServer';
	const DAV_DIGEST_AUTH = true;
	
	const FILESTORAGE_PRIVATE_QUOTA = 104857600;
	const FILESTORAGE_CORPORATE_QUOTA = 1048576000;
	
	const FILESTORAGE_PATH_ROOT = '/files';
	const FILESTORAGE_PATH_PRIVATE = '/private';
	const FILESTORAGE_PATH_CORPORATE = '/corporate';
	
	const LOG_BODY = false;

	// Langs Constants
	const CalendarDefaultName = 'My Calendar';
}