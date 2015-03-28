<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
			'awm_addr_groups_events',

			'awm_identities', 'awm_tenants', 'awm_fetchers', 'awm_system_folders',
			'awm_channels', 'awm_folders_order', 'awm_min', 'awm_subscriptions',
			'awm_folders_order_names', 'awm_social', 'awm_tenant_socials',

			// quotas
			'awm_account_quotas', 'awm_domain_quotas', 'awm_tenant_quotas',

			// calendar
			'acal_calendars', 'acal_events', 'acal_users_data',
			'acal_publications', 'acal_reminders', 'acal_appointments',
			'acal_eventrepeats', 'acal_exclusions', 'acal_sharing',
			'acal_cron_runs', 'acal_awm_fnbl_runs',

			// helpdesk
			'ahd_users', 'ahd_threads', 'ahd_posts', 'ahd_reads', 'ahd_attachments', 'ahd_online', 'ahd_fetcher',

			// dav
			'adav_addressbooks', 'adav_calendars', 'adav_cache', 'adav_calendarobjects',
			'adav_cards', 'adav_locks', 'adav_groupmembers', 'adav_principals',
			'adav_reminders', 'adav_calendarshares',

            'twofa_accounts'
		));

		CApi::Plugin()->RunHook('api-db-tables', array(&$aTables));

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
			new CDbField('id_tenant', CDbField::INT, 0),

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

			new CDbField('signature', CDbField::TEXT_MEDIUM),
			new CDbField('signature_type', CDbField::INT_SHORT, 1),
			new CDbField('signature_opt', CDbField::INT_SHORT, 0),

			new CDbField('mailbox_size', CDbField::INT_BIG, 0),
			new CDbField('mailing_list', CDbField::BIT, 0),

			new CDbField('hide_in_gab', CDbField::BIT, 0),

			new CDbField('custom_fields', CDbField::TEXT),
			new CDbField('social_email', CDbField::VAR_CHAR),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_acct')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_user')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct', 'id_user')),
			new CDbKey(CDbKey::TYPE_INDEX, array('mail_inc_login')),
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
			new CDbField('id_subscription', CDbField::INT, 0),
			new CDbField('id_helpdesk_user', CDbField::INT, 0),
			new CDbField('msgs_per_page', CDbField::INT_SMALL, 20),
			new CDbField('contacts_per_page', CDbField::INT_SMALL, 20),
			new CDbField('created_time', CDbField::DATETIME),
			new CDbField('last_login', CDbField::DATETIME),
			new CDbField('last_login_now', CDbField::DATETIME),
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

			new CDbField('sip_enable', CDbField::BIT, 1),
			new CDbField('sip_impi', CDbField::VAR_CHAR, ''),
			new CDbField('sip_password', CDbField::VAR_CHAR, ''),
			new CDbField('twilio_number', CDbField::VAR_CHAR, ''),
			new CDbField('twilio_enable', CDbField::BIT, 1),
			new CDbField('twilio_default_number', CDbField::BIT, 0),

			new CDbField('files_enable', CDbField::BIT, 1),

			new CDbField('use_threads', CDbField::BIT, 1),
			new CDbField('save_replied_messages_to_current_folder', CDbField::BIT, 0),
			new CDbField('desktop_notifications', CDbField::BIT, 0),
			new CDbField('allow_change_input_direction', CDbField::BIT, 0),
			new CDbField('allow_helpdesk_notifications', CDbField::BIT, 0),

			new CDbField('enable_open_pgp', CDbField::BIT, 0),
			new CDbField('allow_autosave_in_drafts', CDbField::BIT, 1),
			new CDbField('autosign_outgoing_emails', CDbField::BIT, 0),

			new CDbField('capa', CDbField::VAR_CHAR),
			new CDbField('client_timezone', CDbField::VAR_CHAR, '', 100),
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct', 'id_folder')),
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
			new CDbField('flags', CDbField::VAR_CHAR, '', 255),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_folder')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct', 'id_folder')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct', 'id_parent')),
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_folder')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_folder', 'id_parent')),
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct')),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmFoldersOrder()
	{
		return new CDbTable('awm_folders_order', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('folders_order', CDbField::TEXT),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct')),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmFoldersOrderNames()
	{
		return new CDbTable('awm_folders_order_names', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('real_name', CDbField::VAR_CHAR, ''),
			new CDbField('order_name', CDbField::VAR_CHAR, '')
		), array(
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct')),
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct', 'id_folder_db')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct', 'id_folder_db', 'seen')),
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_user'))
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct'))
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_user'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmTenants()
	{
		return new CDbTable('awm_tenants', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_tenant', CDbField::AUTO_INT),
			new CDbField('id_channel', CDbField::INT, 0),
			new CDbField('disabled', CDbField::BIT, 0),
			new CDbField('login_enabled', CDbField::BIT, 0),
			new CDbField('login', CDbField::VAR_CHAR),
			new CDbField('email', CDbField::VAR_CHAR),
			new CDbField('password', CDbField::VAR_CHAR),
			new CDbField('description', CDbField::VAR_CHAR),

			new CDbField('quota', CDbField::INT, 0),
			new CDbField('files_usage_bytes', CDbField::INT_BIG_UNSIGNED, 0),

			new CDbField('user_count_limit', CDbField::INT, 0),
			new CDbField('domain_count_limit', CDbField::INT, 0),
			new CDbField('capa', CDbField::VAR_CHAR, ''),

			new CDbField('allow_change_email', CDbField::BIT, 1),
			new CDbField('allow_change_password', CDbField::BIT, 1),

			new CDbField('expared_timestamp', CDbField::INT_UNSIGNED, 0),
			new CDbField('pay_url', CDbField::VAR_CHAR, ''),
			new CDbField('is_trial', CDbField::BIT, 0),

			new CDbField('hd_admin_email_account', CDbField::VAR_CHAR, ''),
			new CDbField('hd_client_iframe_url', CDbField::VAR_CHAR, ''),
			new CDbField('hd_agent_iframe_url', CDbField::VAR_CHAR, ''),
			new CDbField('hd_site_name', CDbField::VAR_CHAR, ''),
			new CDbField('hd_style_allow', CDbField::BIT, 0),
			new CDbField('hd_style_image', CDbField::VAR_CHAR, ''),
			new CDbField('hd_style_text', CDbField::TEXT),

			new CDbField('login_style_image', CDbField::VAR_CHAR, ''),
			new CDbField('app_style_image', CDbField::VAR_CHAR, ''),
			
			new CDbField('hd_facebook_allow', CDbField::BIT, 0),
			new CDbField('hd_facebook_id', CDbField::VAR_CHAR, ''),
			new CDbField('hd_facebook_secret', CDbField::VAR_CHAR, ''),
			new CDbField('hd_google_allow', CDbField::BIT, 0),
			new CDbField('hd_google_id', CDbField::VAR_CHAR, ''),
			new CDbField('hd_google_secret', CDbField::VAR_CHAR, ''),
			new CDbField('hd_twitter_allow', CDbField::BIT, 0),
			new CDbField('hd_twitter_id', CDbField::VAR_CHAR, ''),
			new CDbField('hd_twitter_secret', CDbField::VAR_CHAR, ''),
			new CDbField('hd_allow_fetcher', CDbField::BIT, 0),
			new CDbField('hd_fetcher_type', CDbField::INT, 0),
			new CDbField('hd_fetcher_timer', CDbField::INT, 0),

			new CDbField('social_facebook_allow', CDbField::BIT, 0),
			new CDbField('social_facebook_id', CDbField::VAR_CHAR, ''),
			new CDbField('social_facebook_secret', CDbField::VAR_CHAR, ''),
			new CDbField('social_google_allow', CDbField::BIT, 0),
			new CDbField('social_google_id', CDbField::VAR_CHAR, ''),
			new CDbField('social_google_secret', CDbField::VAR_CHAR, ''),
			new CDbField('social_google_api_key', CDbField::VAR_CHAR, ''),
			new CDbField('social_twitter_allow', CDbField::BIT, 0),
			new CDbField('social_twitter_id', CDbField::VAR_CHAR, ''),
			new CDbField('social_twitter_secret', CDbField::VAR_CHAR, ''),
			new CDbField('social_dropbox_allow', CDbField::BIT, 0),
			new CDbField('social_dropbox_secret', CDbField::VAR_CHAR, ''),
			new CDbField('social_dropbox_key', CDbField::VAR_CHAR, ''),

			new CDbField('sip_allow', CDbField::BIT, 0),
			new CDbField('sip_allow_configuration', CDbField::BIT, 0),
			new CDbField('sip_realm', CDbField::VAR_CHAR, ''),
			new CDbField('sip_websocket_proxy_url', CDbField::VAR_CHAR, ''),
			new CDbField('sip_outbound_proxy_url', CDbField::VAR_CHAR, ''),
			new CDbField('sip_caller_id', CDbField::VAR_CHAR, ''),
			
			new CDbField('twilio_allow', CDbField::BIT, 0),
			new CDbField('twilio_allow_configuration', CDbField::BIT, 0),
			new CDbField('twilio_phone_number', CDbField::VAR_CHAR, ''),
			new CDbField('twilio_account_sid', CDbField::VAR_CHAR, ''),
			new CDbField('twilio_auth_token', CDbField::VAR_CHAR, ''),
			new CDbField('twilio_app_sid', CDbField::VAR_CHAR, '')
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_tenant'))
		));
	}
	
	/**
	 * @return CDbTable
	 */
	public static function AwmTenantSocials()
	{
		return new CDbTable('awm_tenant_socials', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_tenant', CDbField::INT),
			new CDbField('social_allow', CDbField::BIT, 0),
			new CDbField('social_name', CDbField::VAR_CHAR),
			new CDbField('social_id', CDbField::VAR_CHAR),
			new CDbField('social_secret', CDbField::VAR_CHAR),
			new CDbField('social_api_key', CDbField::VAR_CHAR),
			new CDbField('social_scopes', CDbField::VAR_CHAR)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
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
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('id_user', CDbField::INT, 0),
			new CDbField('id_domain', CDbField::INT, 0),
			new CDbField('id_tenant', CDbField::INT, 0),
			new CDbField('enabled', CDbField::BIT, 1),
			new CDbField('locked', CDbField::BIT, 0),
			new CDbField('mail_check_interval', CDbField::INT, 0),
			new CDbField('mail_check_lasttime', CDbField::INT, 0),
			new CDbField('leave_messages', CDbField::BIT, 1),
			new CDbField('frienly_name', CDbField::VAR_CHAR, ''),
			new CDbField('email', CDbField::VAR_CHAR, ''),
			new CDbField('signature', CDbField::TEXT, ''),
			new CDbField('signature_opt', CDbField::INT_SHORT, 0),
			new CDbField('inc_host', CDbField::VAR_CHAR, ''),
			new CDbField('inc_port', CDbField::INT, 110),
			new CDbField('inc_login', CDbField::VAR_CHAR, ''),
			new CDbField('inc_password', CDbField::VAR_CHAR, ''),
			new CDbField('inc_security', CDbField::INT_SHORT, 0),
			new CDbField('out_enabled', CDbField::BIT, 1),
			new CDbField('out_host', CDbField::VAR_CHAR, ''),
			new CDbField('out_port', CDbField::INT, 110),
			new CDbField('out_auth', CDbField::BIT, 1),
			new CDbField('out_security', CDbField::INT_SHORT, 0),
			new CDbField('dest_folder', CDbField::VAR_CHAR, '')
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_fetcher'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmSubscriptions()
	{
		return new CDbTable('awm_subscriptions', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_subscription', CDbField::AUTO_INT),
			new CDbField('id_tenant', CDbField::INT, 0),
			new CDbField('name', CDbField::VAR_CHAR, ''),
			new CDbField('description', CDbField::VAR_CHAR, ''),
			new CDbField('capa', CDbField::VAR_CHAR, ''),
			new CDbField('limit', CDbField::INT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_subscription'))
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
			new CDbField('def_identity', CDbField::BIT, 0),
			new CDbField('enabled', CDbField::BIT, 1),
			new CDbField('email', CDbField::VAR_CHAR, ''),
			new CDbField('friendly_nm', CDbField::VAR_CHAR, ''),
			new CDbField('signature', CDbField::TEXT_MEDIUM),
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmMin()
	{
		return new CDbTable('awm_min', CDbSchemaHelper::Prefix(), array(
			new CDbField('hash_id', CDbField::VAR_CHAR, '', 32),
			new CDbField('hash', CDbField::VAR_CHAR, '', 20),
			new CDbField('data', CDbField::TEXT),
		), array(
			new CDbKey(CDbKey::TYPE_INDEX, array('hash'))
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct'))
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmAccountQuotas()
	{
		return new CDbTable('awm_account_quotas', CDbSchemaHelper::Prefix(), array(
			new CDbField('name', CDbField::VAR_CHAR, '', 100),
			new CDbField('quota_usage_messages', CDbField::INT_BIG_UNSIGNED, 0),
			new CDbField('quota_usage_bytes', CDbField::INT_BIG_UNSIGNED, 0)
		), array(
			new CDbKey(CDbKey::TYPE_INDEX, array('name'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmDomainQuotas()
	{
		return new CDbTable('awm_domain_quotas', CDbSchemaHelper::Prefix(), array(
			new CDbField('name', CDbField::VAR_CHAR, '', 100),
			new CDbField('quota_usage_messages', CDbField::INT_BIG_UNSIGNED, 0),
			new CDbField('quota_usage_bytes', CDbField::INT_BIG_UNSIGNED, 0)
		), array(
			new CDbKey(CDbKey::TYPE_INDEX, array('name'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmTenantQuotas()
	{
		return new CDbTable('awm_tenant_quotas', CDbSchemaHelper::Prefix(), array(
			new CDbField('name', CDbField::VAR_CHAR, '', 100),
			new CDbField('quota_usage_messages', CDbField::INT_BIG_UNSIGNED, 0),
			new CDbField('quota_usage_bytes', CDbField::INT_BIG_UNSIGNED, 0)
		), array(
			new CDbKey(CDbKey::TYPE_INDEX, array('name'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AwmDomains()
	{
		return new CDbTable('awm_domains', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_domain', CDbField::AUTO_INT),
			new CDbField('id_tenant', CDbField::INT, 0),
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

			new CDbField('quota_mbytes_limit', CDbField::INT_UNSIGNED, 0),
			new CDbField('quota_usage_bytes', CDbField::INT_BIG_UNSIGNED, 0),
			new CDbField('quota_usage_messages', CDbField::INT_BIG_UNSIGNED, 0),

			new CDbField('allow_webmail', CDbField::BIT, 1),
			new CDbField('site_name', CDbField::VAR_CHAR),
			new CDbField('allow_change_interface_settings', CDbField::BIT, 0),
			new CDbField('allow_users_add_acounts', CDbField::BIT, 0),
			new CDbField('allow_change_account_settings', CDbField::BIT, 0),
			new CDbField('allow_new_users_register', CDbField::BIT, 1),
			new CDbField('allow_open_pgp', CDbField::BIT, 0),

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

			new CDbField('allow_files', CDbField::BIT, 1),
			new CDbField('allow_helpdesk', CDbField::BIT, 1),

			new CDbField('use_threads', CDbField::BIT, 1),
			new CDbField('is_internal', CDbField::BIT, 0),
			new CDbField('disabled', CDbField::BIT, 0),
			
			new CDbField('default_tab', CDbField::VAR_CHAR, 'mailbox'),
			
			new CDbField('is_default_for_tenant', CDbField::BIT, 0)

//			new CDbField('password_min_length', CDbField::INT_SHORT, 0),
//			new CDbField('password_must_be_complex', CDbField::BIT, 0)
			
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
			new CDbField('id_domain', CDbField::INT, 0),
			new CDbField('id_tenant', CDbField::INT, 0),
			new CDbField('str_id', CDbField::VAR_CHAR),
			new CDbField('type', CDbField::INT_SHORT, 0),
			new CDbField('type_id', CDbField::VAR_CHAR, '', 100),
			new CDbField('deleted', CDbField::BIT, 0),

			new CDbField('date_created', CDbField::DATETIME),
			new CDbField('date_modified', CDbField::DATETIME),

			new CDbField('fullname', CDbField::VAR_CHAR),
			new CDbField('view_email', CDbField::VAR_CHAR, ''),
			new CDbField('use_friendly_nm', CDbField::BIT, 1),

			new CDbField('firstname', CDbField::VAR_CHAR, '', 100),
			new CDbField('surname', CDbField::VAR_CHAR, '', 100),
			new CDbField('nickname', CDbField::VAR_CHAR, '', 100),
			new CDbField('skype', CDbField::VAR_CHAR, '', 100),
			new CDbField('facebook', CDbField::VAR_CHAR, ''),

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
			new CDbField('etag', CDbField::VAR_CHAR, '', 100),
			new CDbField('shared_to_all', CDbField::BIT, 0),
			new CDbField('hide_in_gab', CDbField::BIT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_addr')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_user')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_user', 'deleted')),
			new CDbKey(CDbKey::TYPE_INDEX, array('use_frequency')),
			new CDbKey(CDbKey::TYPE_INDEX, array('view_email')),
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
			new CDbKey(CDbKey::TYPE_INDEX, array('id_user')),
			new CDbKey(CDbKey::TYPE_INDEX, array('use_frequency'))
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
	public static function AwmAddrGroupsEvents()
	{
		return new CDbTable('awm_addr_groups_events', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::INT_BIG, 0),
			new CDbField('id_group', CDbField::INT, 0),
			new CDbField('id_calendar', CDbField::VAR_CHAR, null, 250),
			new CDbField('id_event', CDbField::VAR_CHAR, null, 250),
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
			new CDbField('principaluri', CDbField::VAR_CHAR, null, 255),
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
			new CDbField('calendardata', CDbField::TEXT_MEDIUM),
			new CDbField('uri', CDbField::VAR_CHAR, null, 255),
			new CDbField('calendarid', CDbField::INT_UNSIGNED, null, null, true),
			new CDbField('lastmodified', CDbField::INT),
			new CDbField('etag', CDbField::VAR_CHAR, '', 32),
			new CDbField('size', CDbField::INT_UNSIGNED, 0),
			new CDbField('componenttype', CDbField::VAR_CHAR, '', 8),
			new CDbField('firstoccurence', CDbField::INT_UNSIGNED),
			new CDbField('lastoccurence', CDbField::INT_UNSIGNED),
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
			new CDbField('carddata', CDbField::TEXT_MEDIUM),
			new CDbField('uri', CDbField::VAR_CHAR, null, 255),
			new CDbField('lastmodified', CDbField::INT_UNSIGNED),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_INDEX, array('addressbookid'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AdavCalendarshares()
	{
		return new CDbTable('adav_calendarshares', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('calendarid', CDbField::INT_UNSIGNED),
			new CDbField('member', CDbField::INT_UNSIGNED),
			new CDbField('status', CDbField::INT_SHORT_SMALL),
			new CDbField('readonly', CDbField::BIT, 0),
			new CDbField('summary', CDbField::VAR_CHAR, null, 150),
			new CDbField('displayname', CDbField::VAR_CHAR, null, 100),
			new CDbField('color', CDbField::VAR_CHAR, null, 10),
			new CDbField('principaluri', CDbField::VAR_CHAR, null, 255),
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
			new CDbField('eventid', CDbField::VAR_CHAR, null, 255),
			new CDbField('time', CDbField::INT, null),
			new CDbField('starttime', CDbField::INT, null),
			new CDbField('allday', CDbField::BIT, 0)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AhdUsers()
	{
		return new CDbTable('ahd_users', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_helpdesk_user', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('id_system_user', CDbField::INT, 0),
			new CDbField('id_tenant', CDbField::INT, 0),
			new CDbField('is_agent', CDbField::BIT, 0),
			new CDbField('activated', CDbField::BIT, 0),
			new CDbField('activate_hash', CDbField::VAR_CHAR, ''),
			new CDbField('blocked', CDbField::BIT, 0),
			new CDbField('email', CDbField::VAR_CHAR, ''),
			new CDbField('notification_email', CDbField::VAR_CHAR, ''),
			new CDbField('name', CDbField::VAR_CHAR, ''),
			new CDbField('social_id', CDbField::VAR_CHAR, ''),
			new CDbField('social_type', CDbField::VAR_CHAR, ''),
			new CDbField('language', CDbField::VAR_CHAR, 'English', 100),
			new CDbField('date_format', CDbField::VAR_CHAR, '', 50),
			new CDbField('time_format', CDbField::INT_SMALL, 0),
			new CDbField('password_hash', CDbField::VAR_CHAR, ''),
			new CDbField('password_salt', CDbField::VAR_CHAR, ''),
			new CDbField('mail_notifications', CDbField::BIT, 0),
			new CDbField('created', CDbField::DATETIME),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_helpdesk_user'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AhdFetcher()
	{
		return new CDbTable('ahd_fetcher', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_tenant', CDbField::INT, 0),
			new CDbField('email', CDbField::VAR_CHAR, ''),
			new CDbField('last_uid', CDbField::INT, 0),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AhdThreads()
	{
		return new CDbTable('ahd_threads', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_helpdesk_thread', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('str_helpdesk_hash', CDbField::VAR_CHAR, '', 50),
			new CDbField('id_tenant', CDbField::INT, 0),
			new CDbField('id_owner', CDbField::INT, 0),
			new CDbField('post_count', CDbField::INT, 0),
			new CDbField('last_post_id', CDbField::INT, 0),
			new CDbField('last_post_owner_id', CDbField::INT, 0),
			new CDbField('type', CDbField::INT_SMALL, 0),
			new CDbField('has_attachments', CDbField::BIT, 0),
			new CDbField('archived', CDbField::BIT, 0),
			new CDbField('notificated', CDbField::BIT, 0),
			new CDbField('subject', CDbField::VAR_CHAR, ''),
			new CDbField('created', CDbField::DATETIME),
			new CDbField('updated', CDbField::DATETIME),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_helpdesk_thread'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AhdAttachments()
	{
		return new CDbTable('ahd_attachments', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_helpdesk_attachment', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('id_helpdesk_post', CDbField::INT),
			new CDbField('id_helpdesk_thread', CDbField::INT),
			new CDbField('id_tenant', CDbField::INT),
			new CDbField('id_owner', CDbField::INT),
			new CDbField('created', CDbField::DATETIME),
			new CDbField('size_in_bytes', CDbField::INT_UNSIGNED),
			new CDbField('file_name', CDbField::VAR_CHAR),
			new CDbField('hash', CDbField::TEXT),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_helpdesk_attachment'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AhdPosts()
	{
		return new CDbTable('ahd_posts', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_helpdesk_post', CDbField::AUTO_INT_UNSIGNED),
			new CDbField('id_helpdesk_thread', CDbField::INT),
			new CDbField('id_tenant', CDbField::INT),
			new CDbField('id_owner', CDbField::INT),
			new CDbField('type', CDbField::INT_SMALL, 0),
			new CDbField('system_type', CDbField::INT_SMALL, 0),
			new CDbField('text', CDbField::TEXT),
			new CDbField('deleted', CDbField::BIT, 0),
			new CDbField('created', CDbField::DATETIME),
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id_helpdesk_post'))
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AhdReads()
	{
		return new CDbTable('ahd_reads', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_tenant', CDbField::INT, 0),
			new CDbField('id_owner', CDbField::INT),
			new CDbField('id_helpdesk_thread', CDbField::INT),
			new CDbField('last_post_id', CDbField::INT),
		));
	}

	/**
	 * @return CDbTable
	 */
	public static function AhdOnline()
	{
		return new CDbTable('ahd_online', CDbSchemaHelper::Prefix(), array(
			new CDbField('id_helpdesk_thread', CDbField::INT, 0),
			new CDbField('id_helpdesk_user', CDbField::INT, 0),
			new CDbField('id_tenant', CDbField::INT, 0),
			new CDbField('name', CDbField::VAR_CHAR, ''),
			new CDbField('email', CDbField::VAR_CHAR, ''),
			new CDbField('ping_time', CDbField::INT, 0)
		));
	}
	
	/**
	 * @return CDbTable
	 */
	public static function AwmSocial()
	{
		return new CDbTable('awm_social', CDbSchemaHelper::Prefix(), array(
			new CDbField('id', CDbField::AUTO_INT),
			new CDbField('id_acct', CDbField::INT, 0),
			new CDbField('id_social', CDbField::VAR_CHAR),
			new CDbField('type', CDbField::INT, 0),
			new CDbField('type_str', CDbField::VAR_CHAR),
			new CDbField('name', CDbField::VAR_CHAR),
			new CDbField('access_token', CDbField::TEXT),
			new CDbField('refresh_token', CDbField::VAR_CHAR),
			new CDbField('scopes', CDbField::VAR_CHAR)
		), array(
			new CDbKey(CDbKey::TYPE_PRIMARY_KEY, array('id')),
			new CDbKey(CDbKey::TYPE_INDEX, array('id_acct'))
		));
	}

    /**
     * @return CDbTable
     */
    public static function TwofaAccounts()
    {
        return new CDbTable('twofa_accounts', CDbSchemaHelper::Prefix(), array(
            new CDbField('id', CDbField::AUTO_INT),
            new CDbField('account_id', CDbField::INT),
            new CDbField('auth_type', CDbField::VAR_CHAR, ETwofaType::AUTH_TYPE_AUTHY),
            new CDbField('data_type', CDbField::INT, ETwofaType::DATA_TYPE_AUTHY_ID),
            new CDbField('data_value', CDbField::VAR_CHAR)
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
