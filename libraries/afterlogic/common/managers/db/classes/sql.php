<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

CApi::Inc('common.db.table');

/**
 * @package Db
 * @subpackage Classes
 */
class CDbSchemaHelper
{
	/**
	 * @staticvar string $sPrefix
	 * @return string
	 */
	public static function Prefix()
	{
		static $sPrefix = null;
		if (null === $sPrefix)
		{
			$oSettings = null;
			$oSettings =& CApi::GetSettings();
			$sPrefix = $oSettings->GetConf('Common/DBPrefix');
		}

		return $sPrefix;
	}

	/**
	 * @param string $sName
	 * @return CDbTable
	 */
	public static function GetTable($sName)
	{
		$oTable = null;
		$aNames = explode('_', strtolower($sName));
		$sFunctionName = implode(array_map('ucfirst', $aNames));
		if (is_callable(array('CDbSchema', $sFunctionName)))
		{
			$oTable = call_user_func(array('CDbSchema', $sFunctionName));
		}
		return $oTable;
	}

	/**
	 * @staticvar array $aFunctionsCache
	 * @return array
	 */
	public static function GetSqlFunctions()
	{
		static $aFunctionsCache = null;
		if (null !== $aFunctionsCache)
		{
			return $aFunctionsCache;
		}

		$aFunctions = array(
			CDbSchema::functionDP1()
		);

		$aFunctionsCache = $aFunctions;
		return $aFunctionsCache;
	}

	/**
	 * @staticvar array $aTablesCache
	 * @return array
	 */
	public static function GetSqlTables()
	{
		static $aTablesCache = null;
		if (null !== $aTablesCache)
		{
			return $aTablesCache;
		}

		$aTables = array();
		CDbSchemaHelper::addTablesToArray($aTables, array(
			'a_users',
			'awm_accounts', 'awm_settings', 'awm_domains',
			'awm_folders', 'awm_folders_tree', 'awm_filters',
			'awm_messages', 'awm_messages_body', 'awm_reads',
			'awm_columns', 'awm_senders',
			'awm_mailaliases', 'awm_mailforwards', 'awm_mailinglists',
			'awm_addr_book', 'awm_addr_groups', 'awm_addr_groups_contacts',

			'awm_identities', 'awm_realms', 'awm_fetchers', 'awm_system_folders',
			'awm_channels',

			//calendar
			'acal_calendars', 'acal_events', 'acal_users_data',
			'acal_publications', 'acal_reminders', 'acal_appointments',
			'acal_eventrepeats', 'acal_exclusions', 'acal_sharing',
			'acal_cron_runs', 'acal_awm_fnbl_runs',

			//dav
			'adav_addressbooks', 'adav_calendars', 'adav_cache', 'adav_calendarobjects',
			'adav_cards', 'adav_delegates', 'adav_locks', 'adav_groupmembers', 'adav_principals',
			'adav_reminders'
		));

		$aTablesCache = $aTables;
		return $aTablesCache;
	}

	/**
	 * @param array &$aTables
	 * @param array $aNames
	 */
	protected static function addTablesToArray(array &$aTables, array $aNames)
	{
		foreach ($aNames as $sName)
		{
			$oTable = CDbSchemaHelper::GetTable($sName);
			if ($oTable)
			{
				$aTables[] = $oTable;
			}
		}
	}
}

/**
 * @package Db
 * @subpackage Classes
 */
class CDbSchema
{
	/**
	 * @return CDbTable
	 */
	public static function AUsers()
	{
		return new CDbTable('a_users', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_user', CDbField::AUTO_INT),
			new CDbField('deleted', CDbField::BIT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_user'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmAccounts()
	{
		return new CDbTable('awm_accounts', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_acct', CDbField::AUTO_INT),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('id_domain', CDbField::INT, 0),
			new CDbField('id_realm', CDbField::INT, 0),

			new CDbField('def_acct', CDbField::BIT, 0),
			new CDbField('deleted', CDbField::BIT, 0),

			new CDbField('quota', CDbField::INT_UNSIGNED, 0),

			new CDbField('email', CDbField::VAR_CHAR, ''),
			new CDbField('friendly_nm', CDbField::VAR_CHAR),

			new CDbField('mail_protocol', CDbField::INT_SHORT, EMailProtocol::IMAP4),
			new CDbField('mail_inc_host', CDbField::VAR_CHAR),
			new CDbField('mail_inc_port', CDbField::INT, API_INC_PROTOCOL_IMAP4_DEF_PORT),
			new CDbField('mail_inc_login', CDbField::VAR_CHAR),
			new CDbField('mail_inc_pass', CDbField::VAR_CHAR),
			new CDbField('mail_inc_ssl', CDbField::BIT, 0),

			new CDbField('mail_out_host', CDbField::VAR_CHAR),
			new CDbField('mail_out_port', CDbField::INT, API_INC_PROTOCOL_SMTP_DEF_PORT),
			new CDbField('mail_out_login', CDbField::VAR_CHAR),
			new CDbField('mail_out_pass', CDbField::VAR_CHAR),
			new CDbField('mail_out_auth', CDbField::INT_SHORT, 0),
			new CDbField('mail_out_ssl', CDbField::BIT, 0),

			new CDbField('def_order', CDbField::INT_SHORT, 0),
			new CDbField('getmail_at_login', CDbField::BIT, 0),
			new CDbField('mail_mode', CDbField::INT_SHORT, 1),
			new CDbField('mails_on_server_days', CDbField::INT_SMALL, 7),

			new CDbField('signature', CDbField::TEXT),
			new CDbField('signature_type', CDbField::INT_SHORT, 1),
			new CDbField('signature_opt', CDbField::INT_SHORT, 0),

			new CDbField('delimiter', CDbField::CHAR, '/'),
			new CDbField('mailbox_size', CDbField::INT_BIG, 0),
			new CDbField('mailing_list', CDbField::BIT, 0),
			new CDbField('namespace', CDbField::VAR_CHAR, ''),

			new CDbField('custom_fields', CDbField::TEXT)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_acct')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_user')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct', 'id_user')),
			new CDbKey(CDbKey::TYPE_INDEX, array('email'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmSettings()
	{
		return new CDbTable('awm_settings', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_setting', CDbField::AUTO_INT),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('msgs_per_page', CDbField::INT_SMALL, 20),
			new CDbField('contacts_per_page', CDbField::INT_SMALL, 20),
			new CDbField('last_login', CDbField::DATETIME),
			new CDbField('logins_count', CDbField::INT, 0),
			new CDbField('auto_checkmail_interval', CDbField::INT, 0),
			new CDbField('def_skin', CDbField::VAR_CHAR, API_DEFAULT_SKIN),
			new CDbField('def_editor', CDbField::BIT, 1),
			new CDbField('layout', CDbField::INT_SHORT, ELayout::Side),
			new CDbField('save_mail', CDbField::INT_SHORT, 0),
			new CDbField('def_timezone', CDbField::INT_SMALL, 0),
			new CDbField('def_time_fmt', CDbField::VAR_CHAR),
			new CDbField('def_lang', CDbField::VAR_CHAR),
			new CDbField('def_date_fmt', CDbField::VAR_CHAR, EDateFormat::MMDDYYYY, 100),
			new CDbField('mailbox_limit', CDbField::INT_BIG, 0),
			new CDbField('incoming_charset', CDbField::VAR_CHAR, 'iso-8859-1', 30),

			new CDbField('question_1', CDbField::VAR_CHAR),
			new CDbField('answer_1', CDbField::VAR_CHAR),
			new CDbField('question_2', CDbField::VAR_CHAR),
			new CDbField('answer_2', CDbField::VAR_CHAR),

			new CDbField('enable_fnbl_sync', CDbField::BIT, 0),

			new CDbField('capa', CDbField::VAR_CHAR),
			new CDbField('client_timeoffset', CDbField::INT, 0),
			new CDbField('custom_fields', CDbField::TEXT)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_setting')),
			new CDbKey(CDbKey::TYPE_UNIQUE_KEY, array('id_user'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmFilters()
	{
		return new CDbTable('awm_filters', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_filter', CDbField::AUTO_INT),
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('field', CDbField::INT_SHORT, 0),
			new CDbField('condition', CDbField::INT_SHORT, 0),
			new CDbField('filter', CDbField::VAR_CHAR),
			new CDbField('action', CDbField::INT_SHORT, 0),
			new CDbField('id_folder', CDbField::INT_BIG, 0),
			new CDbField('applied', CDbField::BIT, 1),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_filter')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct', 'id_folder')),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmFolders()
	{
		return new CDbTable('awm_folders', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_folder', CDbField::AUTO_INT_BIG),
			new CDbField('id_parent', CDbField::INT_BIG, 0),
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('type', CDbField::INT_SMALL, 0),
			new CDbField('name', CDbField::VAR_CHAR),
			new CDbField('full_path', CDbField::VAR_CHAR),
			new CDbField('sync_type', CDbField::INT_SHORT, 0),
			new CDbField('hide', CDbField::BIT, 0),
			new CDbField('fld_order', CDbField::INT_SMALL, 1),
			new CDbField('flags', CDbField::VAR_CHAR, '', 100),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_folder')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct', 'id_folder')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct', 'id_parent')),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmFoldersTree()
	{
		return new CDbTable('awm_folders_tree', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_folder', CDbField::INT_BIG, 0),
			new CDbField('id_parent', CDbField::INT_BIG, 0),
			new CDbField('folder_level', CDbField::INT_SHORT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_folder')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_folder', 'id_parent')),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmSystemFolders()
	{
		return new CDbTable('awm_system_folders', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('folder_full_name', CDbField::VAR_CHAR),
			new CDbField('system_type', CDbField::INT_SHORT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct')),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmMessages()
	{
		return new CDbTable('awm_messages', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_BIG),
			new CDbField('id_msg', CDbField::INT_BIG, 0),
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('id_folder_srv', CDbField::INT_BIG, 0),
			new CDbField('id_folder_db', CDbField::INT_BIG, 0),
			new CDbField('str_uid', CDbField::VAR_CHAR),
			new CDbField('int_uid', CDbField::INT_BIG, 0),
			new CDbField('from_msg', CDbField::VAR_CHAR),
			new CDbField('to_msg', CDbField::VAR_CHAR),
			new CDbField('cc_msg', CDbField::VAR_CHAR),
			new CDbField('bcc_msg', CDbField::VAR_CHAR),
			new CDbField('subject', CDbField::VAR_CHAR),
			new CDbField('msg_date', CDbField::DATETIME),
			new CDbField('attachments', CDbField::BIT, 0),
			new CDbField('size', CDbField::INT_BIG, 0),
			new CDbField('seen', CDbField::BIT, 0),
			new CDbField('flagged', CDbField::BIT, 0),
			new CDbField('priority', CDbField::INT_SHORT, 0),
			new CDbField('downloaded', CDbField::BIT, 0),
			new CDbField('x_spam', CDbField::BIT, 0),
			new CDbField('rtl', CDbField::BIT, 0),
			new CDbField('deleted', CDbField::BIT, 0),
			new CDbField('is_full', CDbField::BIT, 1),
			new CDbField('replied', CDbField::BIT),
			new CDbField('forwarded', CDbField::BIT),
			new CDbField('flags', CDbField::INT),
			new CDbField('body_text', CDbField::TEXT_LONG),
			new CDbField('grayed', CDbField::BIT, 0),
			new CDbField('charset', CDbField::INT, -1),
			new CDbField('sensitivity', CDbField::INT_SHORT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct', 'id_folder_db')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct', 'id_folder_db', 'seen')),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmMessagesBody()
	{
		return new CDbTable('awm_messages_body', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_BIG),
			new CDbField('id_msg', CDbField::INT_BIG, 0),
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('msg', CDbField::BLOB_LONG, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_UNIQUE_KEY, array('id_acct', 'id_msg')),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmColumns()
	{
		return new CDbTable('awm_columns', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_column', CDbField::INT, 0),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('column_value', CDbField::INT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_user'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmReads()
	{
		return new CDbTable('awm_reads', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_read', CDbField::AUTO_INT_BIG),
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('str_uid', CDbField::VAR_CHAR),
			new CDbField('tmp', CDbField::BIT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_read')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmSenders()
	{
		return new CDbTable('awm_senders', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('email', CDbField::VAR_CHAR),
			new CDbField('safety', CDbField::INT_SHORT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_user'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmRealms()
	{
		return new CDbTable('awm_realms', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_realm', CDbField::AUTO_INT),
			new CDbField('id_channel', CDbField::INT, 0),
			new CDbField('disabled', CDbField::BIT, 0),
			new CDbField('login_enabled', CDbField::BIT, 0),
			new CDbField('login', CDbField::VAR_CHAR),
			new CDbField('email', CDbField::VAR_CHAR),
			new CDbField('password', CDbField::VAR_CHAR),
			new CDbField('description', CDbField::VAR_CHAR),
			new CDbField('quota', CDbField::INT, 0),
			new CDbField('user_count_limit', CDbField::INT, 0),
			new CDbField('domain_count_limit', CDbField::INT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_realm'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmChannels()
	{
		return new CDbTable('awm_channels', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_channel', CDbField::AUTO_INT),
			new CDbField('login', CDbField::VAR_CHAR),
			new CDbField('password', CDbField::VAR_CHAR),
			new CDbField('description', CDbField::VAR_CHAR)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_channel'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmFetchers()
	{
		return new CDbTable('awm_fetchers', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_fetcher', CDbField::AUTO_INT),
			new CDbField('disabled', CDbField::BIT, 0),
			new CDbField('inc_protocol', CDbField::INT_SHORT, 0),
			new CDbField('inc_security', CDbField::INT_SHORT, 0),
			new CDbField('inc_host', CDbField::VAR_CHAR),
			new CDbField('inc_port', CDbField::INT),
			new CDbField('inc_login', CDbField::VAR_CHAR),
			new CDbField('inc_password', CDbField::VAR_CHAR),
			new CDbField('local_user', CDbField::VAR_CHAR),
			new CDbField('local_domain', CDbField::VAR_CHAR),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_fetcher'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmIdentities()
	{
		return new CDbTable('awm_identities', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_identity', CDbField::AUTO_INT),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('email', CDbField::VAR_CHAR, ''),
			new CDbField('friendly_nm', CDbField::VAR_CHAR, ''),
			new CDbField('signature', CDbField::TEXT),
			new CDbField('signature_type', CDbField::INT_SHORT, 1),
			new CDbField('use_signature', CDbField::BIT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_identity'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmMailaliases()
	{
		return new CDbTable('awm_mailaliases', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_acct', CDbField::INT),
			new CDbField('alias_name', CDbField::VAR_CHAR, ''),
			new CDbField('alias_domain', CDbField::VAR_CHAR, ''),
			new CDbField('alias_to', CDbField::VAR_CHAR, '')
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmMailinglists()
	{
		return new CDbTable('awm_mailinglists', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_acct', CDbField::INT),
			new CDbField('list_name', CDbField::VAR_CHAR, ''),
			new CDbField('list_to', CDbField::VAR_CHAR, '')
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmMailforwards()
	{
		return new CDbTable('awm_mailforwards', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_acct', CDbField::INT),
			new CDbField('forward_name', CDbField::VAR_CHAR, ''),
			new CDbField('forward_domain', CDbField::VAR_CHAR, ''),
			new CDbField('forward_to', CDbField::VAR_CHAR, '')
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_acct'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmDomains()
	{
		return new CDbTable('awm_domains', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_domain', CDbField::AUTO_INT),
			new CDbField('id_realm', CDbField::INT, 0),
			new CDbField('name', CDbField::VAR_CHAR),
			new CDbField('url', CDbField::VAR_CHAR),
			new CDbField('user_quota', CDbField::INT, 0),
			new CDbField('override_settings', CDbField::BIT, 0),

			new CDbField('mail_protocol', CDbField::INT_SHORT, EMailProtocol::IMAP4),
			new CDbField('mail_inc_host', CDbField::VAR_CHAR),
			new CDbField('mail_inc_port', CDbField::INT, API_INC_PROTOCOL_IMAP4_DEF_PORT),
			new CDbField('mail_inc_ssl', CDbField::BIT, 0),

			new CDbField('mail_out_host', CDbField::VAR_CHAR),
			new CDbField('mail_out_port', CDbField::INT, API_INC_PROTOCOL_SMTP_DEF_PORT),
			new CDbField('mail_out_auth', CDbField::INT_SHORT, 1),
			new CDbField('mail_out_login', CDbField::VAR_CHAR),
			new CDbField('mail_out_pass', CDbField::VAR_CHAR),
			new CDbField('mail_out_ssl', CDbField::BIT, 0),
			new CDbField('mail_out_method', CDbField::INT_SHORT, 1),

			new CDbField('allow_webmail', CDbField::BIT, 1),
			new CDbField('site_name', CDbField::VAR_CHAR),
			new CDbField('allow_change_interface_settings', CDbField::BIT, 0),
			new CDbField('allow_users_add_acounts', CDbField::BIT, 0),
			new CDbField('allow_change_account_settings', CDbField::BIT, 0),
			new CDbField('allow_new_users_register', CDbField::BIT, 1),

			new CDbField('def_user_timezone', CDbField::INT, 0),
			new CDbField('def_user_timeformat', CDbField::INT_SHORT, 0),
			new CDbField('def_user_dateformat', CDbField::VAR_CHAR, EDateFormat::MMDDYYYY, 100),
			new CDbField('msgs_per_page', CDbField::INT_SMALL, 20),
			new CDbField('skin', CDbField::VAR_CHAR),
			new CDbField('lang', CDbField::VAR_CHAR),

			new CDbField('ext_imap_host', CDbField::VAR_CHAR, ''),
			new CDbField('ext_smtp_host', CDbField::VAR_CHAR, ''),
			new CDbField('ext_dav_host', CDbField::VAR_CHAR, ''),

			new CDbField('allow_contacts', CDbField::BIT, 1),
			new CDbField('contacts_per_page', CDbField::INT_SMALL, 20),

			new CDbField('allow_calendar', CDbField::BIT, 1),
			new CDbField('cal_week_starts_on', CDbField::INT_SHORT, 0),
			new CDbField('cal_show_weekends', CDbField::BIT, 0),
			new CDbField('cal_workday_starts', CDbField::INT_SHORT, 9),
			new CDbField('cal_workday_ends', CDbField::INT_SHORT, 18),
			new CDbField('cal_show_workday', CDbField::BIT, 0),
			new CDbField('cal_default_tab', CDbField::INT_SHORT, 2),

			new CDbField('layout', CDbField::INT_SHORT, ELayout::Side),
			new CDbField('xlist', CDbField::BIT, 1),

			new CDbField('global_addr_book', CDbField::INT_SHORT, 0),
			new CDbField('check_interval', CDbField::INT, 0),
			new CDbField('allow_registration', CDbField::BIT, 0),
			new CDbField('allow_pass_reset', CDbField::BIT, 0),

			new CDbField('is_internal', CDbField::BIT, 0),
			new CDbField('disabled', CDbField::BIT, 0)

		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_domain'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmAddrBook()
	{
		return new CDbTable('awm_addr_book', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_addr', CDbField::AUTO_INT_BIG),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('str_id', CDbField::VAR_CHAR),
			new CDbField('fnbl_pim_id', CDbField::INT_BIG),
			new CDbField('deleted', CDbField::BIT, 0),

			new CDbField('date_created', CDbField::DATETIME),
			new CDbField('date_modified', CDbField::DATETIME),

			new CDbField('fullname', CDbField::VAR_CHAR),
			new CDbField('view_email', CDbField::VAR_CHAR, ''),
			new CDbField('use_friendly_nm', CDbField::BIT, 1),

			new CDbField('firstname', CDbField::VAR_CHAR, '', 100),
			new CDbField('surname', CDbField::VAR_CHAR, '', 100),
			new CDbField('nickname', CDbField::VAR_CHAR, '', 100),

			new CDbField('h_email', CDbField::VAR_CHAR),
			new CDbField('h_street', CDbField::VAR_CHAR),
			new CDbField('h_city', CDbField::VAR_CHAR, null, 200),
			new CDbField('h_state', CDbField::VAR_CHAR, null, 200),
			new CDbField('h_zip', CDbField::VAR_CHAR, null, 10),
			new CDbField('h_country', CDbField::VAR_CHAR, null, 200),
			new CDbField('h_phone', CDbField::VAR_CHAR, null, 50),
			new CDbField('h_fax', CDbField::VAR_CHAR, null, 50),
			new CDbField('h_mobile', CDbField::VAR_CHAR, null, 50),
			new CDbField('h_web', CDbField::VAR_CHAR),

			new CDbField('b_email', CDbField::VAR_CHAR),
			new CDbField('b_company', CDbField::VAR_CHAR, null, 200),
			new CDbField('b_street', CDbField::VAR_CHAR),
			new CDbField('b_city', CDbField::VAR_CHAR, null, 200),
			new CDbField('b_state', CDbField::VAR_CHAR, null, 200),
			new CDbField('b_zip', CDbField::VAR_CHAR, null, 10),
			new CDbField('b_country', CDbField::VAR_CHAR, null, 200),
			new CDbField('b_job_title', CDbField::VAR_CHAR, null, 100),
			new CDbField('b_department', CDbField::VAR_CHAR, null, 200),
			new CDbField('b_office', CDbField::VAR_CHAR, null, 200),
			new CDbField('b_phone', CDbField::VAR_CHAR, null, 50),
			new CDbField('b_fax', CDbField::VAR_CHAR, null, 50),
			new CDbField('b_web', CDbField::VAR_CHAR),

			new CDbField('other_email', CDbField::VAR_CHAR),
			new CDbField('primary_email', CDbField::INT_SHORT),

			new CDbField('birthday_day', CDbField::INT_SHORT, 0),
			new CDbField('birthday_month', CDbField::INT_SHORT, 0),
			new CDbField('birthday_year', CDbField::INT_SMALL, 0),

			new CDbField('id_addr_prev', CDbField::INT_BIG),
			new CDbField('tmp', CDbField::BIT, 0),

			new CDbField('use_frequency', CDbField::INT, 11),
			new CDbField('auto_create', CDbField::BIT, 0),

			new CDbField('notes', CDbField::VAR_CHAR),
			new CDbField('etag', CDbField::VAR_CHAR, '', 100)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_addr')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_user')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_user', 'deleted')),
			new CDbKey(CDbKey::TYPE_KEY, array('use_frequency')),
			new CDbKey(CDbKey::TYPE_KEY, array('view_email')),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmAddrGroups()
	{
		return new CDbTable('awm_addr_groups', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_group', CDbField::AUTO_INT),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('group_nm', CDbField::VAR_CHAR),
			new CDbField('group_str_id', CDbField::VAR_CHAR, null, 100),
			new CDbField('use_frequency', CDbField::INT, 0),

			new CDbField('email', CDbField::VAR_CHAR),
			new CDbField('company', CDbField::VAR_CHAR, null, 200),
			new CDbField('street', CDbField::VAR_CHAR),
			new CDbField('city', CDbField::VAR_CHAR, null, 200),
			new CDbField('state', CDbField::VAR_CHAR, null, 200),
			new CDbField('zip', CDbField::VAR_CHAR, null, 10),
			new CDbField('country', CDbField::VAR_CHAR, null, 200),
			new CDbField('phone', CDbField::VAR_CHAR, null, 50),
			new CDbField('fax', CDbField::VAR_CHAR, null, 50),
			new CDbField('web', CDbField::VAR_CHAR),
			new CDbField('organization', CDbField::BIT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_group')),
			new CDbKey(CDbKey::TYPE_KEY, array('id_user')),
			new CDbKey(CDbKey::TYPE_KEY, array('use_frequency'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmAddrGroupsContacts()
	{
		return new CDbTable('awm_addr_groups_contacts', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_addr', CDbField::INT_BIG, 0),
			new CDbField('id_group', CDbField::INT, 0),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalAppointments()
	{
		return new CDbTable('acal_appointments', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_appointment', CDbField::AUTO_INT),
			new CDbField('id_event', CDbField::INT, 0),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('email', CDbField::VAR_CHAR),
			new CDbField('access_type', CDbField::INT_SHORT, 0),
			new CDbField('status', CDbField::INT_SHORT, 0),
			new CDbField('hash', CDbField::VAR_CHAR, null, 32),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_appointment'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalAwmFnblRuns()
	{
		return new CDbTable('acal_awm_fnbl_runs', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_run', CDbField::AUTO_INT),
			new CDbField('run_date', CDbField::DATETIME)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_run'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalCalendars()
	{
		return new CDbTable('acal_calendars', CDbSchemaHelper::Prefix(), array(
			new CDbField('calendar_id', CDbField::AUTO_INT),
			new CDbField('calendar_str_id', CDbField::VAR_CHAR),
			new CDbField('user_id', CDbField::INT, 0),
			new CDbField('calendar_name', CDbField::VAR_CHAR, '', 100),
			new CDbField('calendar_description', CDbField::TEXT),
			new CDbField('calendar_color', CDbField::INT, 0),
			new CDbField('calendar_active', CDbField::BIT, 1),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('calendar_id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalCronRuns()
	{
		return new CDbTable('acal_cron_runs', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_run', CDbField::AUTO_INT_BIG),
			new CDbField('run_date', CDbField::DATETIME),
			new CDbField('latest_date', CDbField::DATETIME)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_run'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalEventrepeats()
	{
		return new CDbTable('acal_eventrepeats', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_repeat', CDbField::AUTO_INT),
			new CDbField('id_event', CDbField::INT),
			new CDbField('repeat_period', CDbField::BIT, 0),
			new CDbField('repeat_order', CDbField::BIT, 1),
			new CDbField('repeat_num', CDbField::INT, 0),
			new CDbField('repeat_until', CDbField::DATETIME),
			new CDbField('sun', CDbField::BIT, 0),
			new CDbField('mon', CDbField::BIT, 0),
			new CDbField('tue', CDbField::BIT, 0),
			new CDbField('wed', CDbField::BIT, 0),
			new CDbField('thu', CDbField::BIT, 0),
			new CDbField('fri', CDbField::BIT, 0),
			new CDbField('sat', CDbField::BIT, 0),
			new CDbField('week_number', CDbField::BIT),
			new CDbField('repeat_end', CDbField::BIT, 0),
			new CDbField('excluded', CDbField::BIT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_repeat'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalEvents()
	{
		return new CDbTable('acal_events', CDbSchemaHelper::Prefix(), array(
			new CDbField('event_id', CDbField::AUTO_INT),
			new CDbField('event_str_id', CDbField::VAR_CHAR),
			new CDbField('fnbl_pim_id', CDbField::INT_BIG),
			new CDbField('calendar_id', CDbField::INT),

			new CDbField('event_timefrom', CDbField::DATETIME),
			new CDbField('event_timetill', CDbField::DATETIME),
			new CDbField('event_allday', CDbField::BIT, 0),
			new CDbField('event_name', CDbField::VAR_CHAR, '', 100),
			new CDbField('event_text', CDbField::TEXT),
			new CDbField('event_priority', CDbField::INT_SHORT),
			new CDbField('event_repeats', CDbField::BIT, 0),
			new CDbField('event_last_modified', CDbField::DATETIME),
			new CDbField('event_owner_email', CDbField::VAR_CHAR, ''),
			new CDbField('event_appointment_access', CDbField::INT_SHORT, 0),
			new CDbField('event_deleted', CDbField::BIT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('event_id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalExclusions()
	{
		return new CDbTable('acal_exclusions', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_exclusion', CDbField::AUTO_INT),
			new CDbField('id_event', CDbField::INT),
			new CDbField('id_calendar', CDbField::INT),
			new CDbField('id_repeat', CDbField::INT),
			new CDbField('id_recurrence_date', CDbField::DATETIME),

			new CDbField('event_timefrom', CDbField::DATETIME),
			new CDbField('event_timetill', CDbField::DATETIME),
			new CDbField('event_name', CDbField::VAR_CHAR, null, 100),
			new CDbField('event_text', CDbField::TEXT),

			new CDbField('event_allday', CDbField::BIT, 0),
			new CDbField('event_last_modified', CDbField::DATETIME),
			new CDbField('is_deleted', CDbField::BIT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_exclusion'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalPublications()
	{
		return new CDbTable('acal_publications', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_publication', CDbField::AUTO_INT),
			new CDbField('id_user', CDbField::INT),
			new CDbField('id_calendar', CDbField::INT),
			new CDbField('str_md5', CDbField::VAR_CHAR, null, 32),
			new CDbField('int_access_level', CDbField::INT_SHORT, 1),
			new CDbField('access_type', CDbField::INT_SHORT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_publication'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalReminders()
	{
		return new CDbTable('acal_reminders', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_reminder', CDbField::AUTO_INT),
			new CDbField('id_event', CDbField::INT),
			new CDbField('id_user', CDbField::INT),
			new CDbField('notice_type', CDbField::INT_SHORT, 0),
			new CDbField('remind_offset', CDbField::INT, 0),
			new CDbField('sent', CDbField::INT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_reminder'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalSharing()
	{
		return new CDbTable('acal_sharing', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_share', CDbField::AUTO_INT),
			new CDbField('id_user', CDbField::INT),
			new CDbField('id_calendar', CDbField::INT),
			new CDbField('id_to_user', CDbField::INT),
			new CDbField('str_to_email', CDbField::VAR_CHAR, ''),
			new CDbField('int_access_level', CDbField::INT_SHORT, 2),
			new CDbField('calendar_active', CDbField::BIT, 1),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_share'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AcalUsersData()
	{
		return new CDbTable('acal_users_data', CDbSchemaHelper::Prefix(), array(
			new CDbField('settings_id', CDbField::AUTO_INT),
			new CDbField('user_id', CDbField::INT, 0),
//			new CDbField('timeformat', CDbField::BIT, 1),
//			new CDbField('dateformat', CDbField::BIT, 1),
			new CDbField('showweekends', CDbField::BIT, 0),
			new CDbField('workdaystarts', CDbField::INT_SHORT, ECalendarDefaultWorkDay::Starts),
			new CDbField('workdayends', CDbField::INT_SHORT, ECalendarDefaultWorkDay::Ends),
			new CDbField('showworkday', CDbField::BIT, 0),
			new CDbField('weekstartson', CDbField::INT_SHORT, ECalendarWeekStartOn::Sunday),
			new CDbField('defaulttab', CDbField::INT_SHORT, ECalendarDefaultTab::Week),
//			new CDbField('country', CDbField::VAR_CHAR, null, 3),
//			new CDbField('timezone', CDbField::INT_SMALL),
//			new CDbField('alltimezones', CDbField::BIT, 0),
//			new CDbField('reminders_web_url', CDbField::VAR_CHAR),
//			new CDbField('autoaddinvitation', CDbField::BIT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('settings_id')),
			new CDbKey(CDbKey::TYPE_INDEX, array('user_id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavAddressbooks()
	{
		return new CDbTable('adav_addressbooks', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('principaluri', CDbField::VAR_CHAR, null, 255),
			new CDbField('displayname', CDbField::VAR_CHAR, null, 255),
			new CDbField('uri', CDbField::VAR_CHAR, null, 200),
			new CDbField('description', CDbField::TEXT),
			new CDbField('ctag', CDbField::INT_UNSIGNED, 1),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavCalendars()
	{
		return new CDbTable('adav_calendars', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('principaluri', CDbField::VAR_CHAR, null, 100),
			new CDbField('displayname', CDbField::VAR_CHAR, null, 100),
			new CDbField('uri', CDbField::VAR_CHAR, null, 255),
			new CDbField('ctag', CDbField::INT_UNSIGNED, 0),
			new CDbField('description', CDbField::TEXT),
			new CDbField('calendarorder', CDbField::INT_UNSIGNED, 0),
			new CDbField('calendarcolor', CDbField::VAR_CHAR, null, 10),
			new CDbField('timezone', CDbField::TEXT),
			new CDbField('components', CDbField::VAR_CHAR, null, 20),
			new CDbField('transparent', CDbField::BIT, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavCache()
	{
		return new CDbTable('adav_cache', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('user', CDbField::VAR_CHAR, null, 255),
			new CDbField('calendaruri', CDbField::VAR_CHAR, null, 255),
			new CDbField('type', CDbField::INT_SHORT),
			new CDbField('time', CDbField::INT),
			new CDbField('starttime', CDbField::INT),
			new CDbField('eventid', CDbField::VAR_CHAR, null, 45)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavCalendarobjects()
	{
		return new CDbTable('adav_calendarobjects', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('calendardata', CDbField::TEXT),
			new CDbField('uri', CDbField::VAR_CHAR, null, 255),
			new CDbField('calendarid', CDbField::INT_UNSIGNED, null, null, true),
			new CDbField('lastmodified', CDbField::INT),
			new CDbField('etag', CDbField::VAR_CHAR, '', 32),
			new CDbField('size', CDbField::INT_UNSIGNED, 0),
			new CDbField('componenttype', CDbField::VAR_CHAR, '', 8),
			new CDbField('firstoccurence', CDbField::INT_UNSIGNED, 0),
			new CDbField('lastoccurence', CDbField::INT_UNSIGNED, 0),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavCards()
	{
		return new CDbTable('adav_cards', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('addressbookid', CDbField::INT_UNSIGNED, null, null, true),
			new CDbField('carddata', CDbField::TEXT),
			new CDbField('uri', CDbField::VAR_CHAR, null, 255),
			new CDbField('lastmodified', CDbField::INT_UNSIGNED),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavDelegates()
	{
		return new CDbTable('adav_delegates', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('calendarid', CDbField::INT_UNSIGNED, null, null, true),
			new CDbField('principalid', CDbField::INT_UNSIGNED, null, null, true),
			new CDbField('mode', CDbField::INT_SHORT_SMALL, null),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavLocks()
	{
		return new CDbTable('adav_locks', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('owner', CDbField::VAR_CHAR, null, 100),
			new CDbField('timeout', CDbField::INT_UNSIGNED, null),
			new CDbField('created', CDbField::INT),
			new CDbField('token', CDbField::VAR_CHAR, null, 100),
			new CDbField('scope', CDbField::INT_SHORT),
			new CDbField('depth', CDbField::INT_SHORT),
			new CDbField('uri', CDbField::TEXT),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavGroupmembers()
	{
		return new CDbTable('adav_groupmembers', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('principal_id', CDbField::INT_UNSIGNED, null, null, true),
			new CDbField('member_id', CDbField::INT_UNSIGNED, null, null, true)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_UNIQUE_KEY, array('principal_id', 'member_id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavPrincipals()
	{
		return new CDbTable('adav_principals', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('uri', CDbField::VAR_CHAR, null, 255, true),
			new CDbField('email', CDbField::VAR_CHAR, null, 80),
			new CDbField('vcardurl', CDbField::VAR_CHAR, null, 80),
			new CDbField('displayname', CDbField::VAR_CHAR, null, 80)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_UNIQUE_KEY, array('uri'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavReminders()
	{
		return new CDbTable('adav_reminders', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('user', CDbField::VAR_CHAR, null, 100, true),
			new CDbField('calendaruri', CDbField::VAR_CHAR, null),
			new CDbField('eventid', CDbField::VAR_CHAR, null, 45),
			new CDbField('time', CDbField::INT, null),
			new CDbField('starttime', CDbField::INT, null)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
		));
	}

	/**
	 * @return CDbFunction
	 */
	public static function functionDP1()
	{
		return new CDbFunction('DP1', 'password VARCHAR(255)', 'VARCHAR(128)',
'DETERMINISTIC
READS SQL DATA
BEGIN
	DECLARE result VARCHAR(128) DEFAULT \'\';
	DECLARE passwordLen INT;
	DECLARE decodeByte CHAR(3);
	DECLARE plainBytes VARCHAR(128);
	DECLARE startIndex INT DEFAULT 3;
	DECLARE currentByte INT DEFAULT 1;
	DECLARE hexByte CHAR(3);

	SET passwordLen = LENGTH(password);
	IF passwordLen > 0 AND passwordLen % 2 = 0 THEN
		SET decodeByte = CONV((SUBSTRING(password, 1, 2)), 16, 10);
		SET plainBytes = UNHEX(SUBSTRING(password, 1, 2));

		REPEAT
			SET hexByte = CONV((SUBSTRING(password, startIndex, 2)), 16, 10);
			SET plainBytes = CONCAT(plainBytes, UNHEX(HEX(hexByte ^ decodeByte)));

			SET startIndex = startIndex + 2;
			SET currentByte = currentByte + 1;

		UNTIL startIndex > passwordLen
		END REPEAT;

		SET result = plainBytes;
	END IF;

	RETURN result;
END');

	}
}
