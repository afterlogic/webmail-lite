<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdDomain
 * @property int $IdTenant
 * @property bool $IsDisabled
 * @property string $Name
 * @property string $Url
 * @property bool $OverrideSettings
 * @property bool $IsInternal
 * @property bool $IsDefaultDomain
 * @property string $SiteName
 * @property string $DefaultLanguage
 * @property int $DefaultTimeZone
 * @property int $DefaultTimeFormat
 * @property int $DefaultDateFormat
 * @property bool $AllowRegistration
 * @property bool $AllowPasswordReset
 * @property int $IncomingMailProtocol
 * @property string $IncomingMailServer
 * @property int $IncomingMailPort
 * @property bool $IncomingMailUseSSL
 * @property string $OutgoingMailServer
 * @property int $OutgoingMailPort
 * @property int $OutgoingMailAuth
 * @property string $OutgoingMailLogin
 * @property string $OutgoingMailPassword
 * @property bool $OutgoingMailUseSSL
 * @property int $OutgoingSendingMethod
 * @property int $UserQuota
 * @property int $AutoCheckMailInterval
 * @property string $DefaultSkin
 * @property int $MailsPerPage
 * @property bool $AllowUsersChangeInterfaceSettings
 * @property bool $AllowUsersChangeEmailSettings
 * @property bool $AllowUsersAddNewAccounts
 * @property bool $AllowNewUsersRegister
 * @property bool $AllowOpenPGP
 * @property int $Layout
 * @property int $SaveMail
 * @property int $ContactsPerPage
 * @property int $GlobalAddressBook
 * @property bool $CalendarShowWeekEnds
 * @property int $CalendarWorkdayStarts
 * @property int $CalendarWorkdayEnds
 * @property bool $CalendarShowWorkDay
 * @property int $CalendarWeekStartsOn
 * @property int $CalendarDefaultTab
 * @property bool $DetectSpecialFoldersWithXList
 * @property string $ExternalHostNameOfLocalImap
 * @property string $ExternalHostNameOfLocalSmtp
 * @property string $ExternalHostNameOfDAVServer
 * @property bool $UseThreads
 * @property bool $AllowWebMail
 * @property bool $AllowContacts
 * @property bool $AllowCalendar
 * @property bool $AllowFiles
 * @property bool $AllowHelpdesk
 * @property string $DefaultTab
 * @property string $PasswordMinLength
 * @property bool $PasswordMustBeComplex
 * @property bool $IsDefaultTenantDomain
 * 
 * @package Domains
 * @subpackage Classes
 */
class CDomain extends api_AContainer
{
	/**
	 * @var array
	 */
	protected $aFolders;

	/**
	 * @param string $sName = ''
	 * @param string $sUrl = null
	 * @param int $iTenantId = 0
	 */
	public function __construct($sName = '', $sUrl = null, $iTenantId = 0)
	{
		parent::__construct(get_class($this), 'IdDomain');

		$oSettings =& CApi::GetSettings();

		$aDefaults = array(
			'IdDomain'		=> 0,
			'IdTenant'		=> $iTenantId,
			'IsDisabled'	=> false,
			'Name'			=> trim($sName),
			'Url'			=> (null === $sUrl) ? '' : trim($sUrl),

			'IsDefaultDomain'		=> false,
			'IsDefaultTenantDomain'	=> false,
			'IsInternal'			=> false,
			'UseThreads'			=> true,
			'OverrideSettings'		=> true
		);

		$aSettingsMap = $this->GetSettingsMap();
		foreach ($aSettingsMap as $sProperty => $sSettingsName)
		{
			$aDefaults[$sProperty] = $oSettings->GetConf($sSettingsName);
		}

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults($aDefaults);

		$this->aFolders = array(
			EFolderType::Inbox => array('INBOX', 'Inbox'),
			EFolderType::Drafts => array('Drafts', 'Draft'),
			EFolderType::Sent => array('Sent', 'Sent Items', 'Sent Mail'),
			EFolderType::Spam => array('Spam', 'Junk', 'Junk Mail', 'Junk E-mail', 'Bulk Mail'),
			EFolderType::Trash => array('Trash', 'Bin', 'Deleted', 'Deleted Items'),
		);

		$this->SetLower(array('Name', 'IncomingMailServer',/* 'IncomingMailLogin',*/
			'OutgoingMailServer'/*, 'OutgoingMailLogin'*/));

		CApi::Plugin()->RunHook('api-domain-construct', array(&$this));
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		switch (true)
		{
			case !api_Validate::Port($this->IncomingMailPort):
				throw new CApiValidationException(Errs::Validation_InvalidPort, null, array(
					'{{ClassName}}' => 'CAccount', '{{ClassField}}' => 'IncomingMailPort'));

			case !api_Validate::Port($this->OutgoingMailPort):
				throw new CApiValidationException(Errs::Validation_InvalidPort, null, array(
					'{{ClassName}}' => 'CAccount', '{{ClassField}}' => 'OutgoingMailPort'));

			case (!$this->IsDefaultDomain && api_Validate::IsEmpty($this->Name)):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CDomain', '{{ClassField}}' => 'Name'));

			case api_Validate::IsEmpty($this->IncomingMailServer):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CDomain', '{{ClassField}}' => 'IncomingMailServer'));

			case api_Validate::IsEmpty($this->OutgoingMailServer):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CDomain', '{{ClassField}}' => 'OutgoingMailServer'));
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function GetMap()
	{
		return self::GetStaticMap();
	}

	/**
	 * @return array
	 */
	public static function GetStaticMap()
	{
		return array(
			'IdDomain'		=> array('int', 'id_domain', false, false),
			'IdTenant'		=> array('int', 'id_tenant'),
			'IsDisabled'	=> array('bool', 'disabled'),
			'Name'			=> array('string(255)', 'name', true, false),
			'Url'			=> array('string(255)', 'url'),

			'OverrideSettings'	=> array('bool', 'override_settings'),
			'IsInternal'		=> array('bool', 'is_internal'),
			'IsDefaultDomain'	=> array('bool'),
			'IsDefaultTenantDomain'	=> array('bool', 'is_default_for_tenant'),

			// Common
			'SiteName'				=> array('string(255)', 'site_name'),
			'DefaultLanguage'		=> array('string(255)', 'lang'),
			'DefaultTimeZone'		=> array('int', 'def_user_timezone'),
			'DefaultTimeFormat'		=> array('int', 'def_user_timeformat'),
			'DefaultDateFormat'		=> array('string(50)', 'def_user_dateformat'),

			'AllowRegistration'		=> array('bool', 'allow_registration'),
			'AllowPasswordReset'	=> array('bool', 'allow_pass_reset'),
			
//			'PasswordMinLength'		=> array('int', 'password_min_length'),
//			'PasswordMustBeComplex'	=> array('bool', 'password_must_be_complex'),

			// WebMail
			'AllowWebMail'			=> array('bool', 'allow_webmail'),
			'IncomingMailProtocol'	=> array('int', 'mail_protocol'),
			'IncomingMailServer'	=> array('string(255)', 'mail_inc_host'),
			'IncomingMailPort'		=> array('int', 'mail_inc_port'),
			'IncomingMailUseSSL'	=> array('bool', 'mail_inc_ssl'),

			'OutgoingMailServer'	=> array('string(255)', 'mail_out_host'),
			'OutgoingMailPort'		=> array('int', 'mail_out_port'),
			'OutgoingMailAuth'		=> array('int', 'mail_out_auth'),
			'OutgoingMailLogin'		=> array('string(255)', 'mail_out_login'),
			'OutgoingMailPassword'	=> array('password', 'mail_out_pass'),
			'OutgoingMailUseSSL'	=> array('bool', 'mail_out_ssl'),
			'OutgoingSendingMethod'	=> array('int', 'mail_out_method'),

			'ExternalHostNameOfLocalImap'	=> array('string(255)'),// 'ext_imap_host'),
			'ExternalHostNameOfLocalSmtp'	=> array('string(255)'),// 'ext_smtp_host'),
			'ExternalHostNameOfDAVServer'	=> array('string(255)'),// 'ext_dav_host'),

			'UserQuota'				=> array('int'), // user_quota // TODO
			'AutoCheckMailInterval'	=> array('int', 'check_interval'),

			'DefaultSkin'	=> array('string(255)', 'skin'),
			'MailsPerPage'	=> array('int', 'msgs_per_page'),

			'AllowUsersChangeInterfaceSettings'	=> array('bool', 'allow_change_interface_settings'),
			'AllowUsersChangeEmailSettings'		=> array('bool', 'allow_change_account_settings'),
			'AllowUsersAddNewAccounts'			=> array('bool', 'allow_users_add_acounts'),
			'AllowNewUsersRegister'				=> array('bool', 'allow_new_users_register'),
			'AllowOpenPGP'						=> array('bool', 'allow_open_pgp'),

			'Layout'	=> array('int', 'layout'),
			'DetectSpecialFoldersWithXList'	=> array('int', 'xlist'),
			'UseThreads'					=> array('bool', 'use_threads'),

			// Contacts
			'AllowContacts'			=> array('bool', 'allow_contacts'),
			'ContactsPerPage'		=> array('int', 'contacts_per_page'),
			'GlobalAddressBook'		=> array('int', 'global_addr_book'),

			// Calendar
			'AllowCalendar'			=> array('bool', 'allow_calendar'),
			'CalendarShowWeekEnds'	=> array('bool', 'cal_show_weekends'),
			'CalendarWorkdayStarts'	=> array('int', 'cal_workday_starts'),
			'CalendarWorkdayEnds'	=> array('int', 'cal_workday_ends'),
			'CalendarShowWorkDay'	=> array('bool', 'cal_show_workday'),
			'CalendarWeekStartsOn'	=> array('int', 'cal_week_starts_on'),
			'CalendarDefaultTab'	=> array('int', 'cal_default_tab'),
			
			'AllowFiles'			=> array('bool', 'allow_files'),
			'AllowHelpdesk'			=> array('bool', 'allow_helpdesk'),
			
			'DefaultTab'			=> array('string(100)', 'default_tab')
			
		);
	}

	/**
	 * @return bool
	 */
	public function InitBeforeChange()
	{
		parent::InitBeforeChange();

		if (0 < $this->IdTenant)
		{
			$this->OverrideSettings = true;
		}

		if (!$this->OverrideSettings && !$this->IsDefaultDomain)
		{
			/* @var $oApiDomainsManager CApiDomainsManager */
			$oApiDomainsManager = CApi::Manager('domains');

			$oDefDomain = $oApiDomainsManager->GetDefaultDomain();
			$aOverridenSettingsMap = $this->GetOverridenSettingsMap();

			foreach ($aOverridenSettingsMap as $sName)
			{
				$this->{$sName} = $oDefDomain->{$sName};
			}
		}

		return true;
	}

	/**
	 * @param stdClass $oRow
	 */
	public function InitByDbRow($oRow)
	{
		parent::InitByDbRow($oRow);

		$this->InitBeforeChange();
		$this->FlushObsolete();
	}

	/**
	 * @return array
	 */
	public function &GetFoldersMap()
	{
		return $this->aFolders;
	}

	/**
	 * @return array
	 */
	public function GetOverridenSettingsMap()
	{
		return array(
			'SiteName',
			'DefaultLanguage',
			'DefaultTimeZone',
			'DefaultTimeFormat',
			'DefaultDateFormat',

			'AllowRegistration',
			'AllowPasswordReset',

//			'PasswordMinLength',
//			'PasswordMustBeComplex',
			
			'AllowWebMail',
//			'UserQuota', // TODO
			'AutoCheckMailInterval',
			'DefaultSkin',
			'MailsPerPage',
			'AllowUsersChangeInterfaceSettings',
			'AllowUsersChangeEmailSettings',
			'AllowUsersAddNewAccounts',
			'AllowNewUsersRegister',
			'AllowOpenPGP',

			'ExternalHostNameOfLocalImap',
			'ExternalHostNameOfLocalSmtp',
			'ExternalHostNameOfDAVServer',

			'Layout',
			'DetectSpecialFoldersWithXList',

			'AllowContacts',
			'ContactsPerPage',
			'GlobalAddressBook',

			'AllowCalendar',
			'CalendarShowWeekEnds',
			'CalendarWorkdayStarts',
			'CalendarWorkdayEnds',
			'CalendarShowWorkDay',
			'CalendarWeekStartsOn',
			'CalendarDefaultTab',
			
			'AllowFiles',
			'AllowHelpdesk',
			
			'DefaultTab',
			
			'UseThreads'
		);
	}

	/**
	 * @return array
	 */
	public function GetSettingsMap()
	{
		return array(
			'SiteName'				=> 'Common/SiteName',
			'DefaultLanguage'		=> 'Common/DefaultLanguage',
			'DefaultTimeZone'		=> 'Common/DefaultTimeZone',
			'DefaultDateFormat'		=> 'Common/DefaultDateFormat',

			'DefaultTimeFormat'		=> 'Common/DefaultTimeFormat',
			'AllowRegistration'		=> 'Common/AllowRegistration',
			'AllowPasswordReset'	=> 'Common/AllowPasswordReset',
			
			'DefaultTab'			=> 'Common/DefaultTab',
			
//			'PasswordMinLength'		=> 'Common/PasswordMinLength',
//			'PasswordMustBeComplex'	=> 'Common/PasswordMustBeComplex',

			'IncomingMailProtocol'	=> 'WebMail/IncomingMailProtocol',
			'IncomingMailServer'	=> 'WebMail/IncomingMailServer',
			'IncomingMailPort'		=> 'WebMail/IncomingMailPort',
			'IncomingMailUseSSL'	=> 'WebMail/IncomingMailUseSSL',

			'OutgoingMailServer'	=> 'WebMail/OutgoingMailServer',
			'OutgoingMailPort'		=> 'WebMail/OutgoingMailPort',
			'OutgoingMailAuth'		=> 'WebMail/OutgoingMailAuth',
			'OutgoingMailLogin'		=> 'WebMail/OutgoingMailLogin',
			'OutgoingMailPassword'	=> 'WebMail/OutgoingMailPassword',
			'OutgoingMailUseSSL'	=> 'WebMail/OutgoingMailUseSSL',
			'OutgoingSendingMethod'	=> 'WebMail/OutgoingSendingMethod',
			'UserQuota'				=> 'WebMail/UserQuota',
			'AutoCheckMailInterval'	=> 'WebMail/AutoCheckMailInterval',
			'DefaultSkin'			=> 'WebMail/DefaultSkin',
			'MailsPerPage'			=> 'WebMail/MailsPerPage',
			'AllowUsersChangeInterfaceSettings'		=> 'WebMail/AllowUsersChangeInterfaceSettings',
			'AllowUsersChangeEmailSettings'			=> 'WebMail/AllowUsersChangeEmailSettings',
			'AllowUsersAddNewAccounts'				=> 'WebMail/AllowUsersAddNewAccounts',
			'AllowNewUsersRegister'					=> 'WebMail/AllowNewUsersRegister',
			'AllowOpenPGP'							=> 'WebMail/AllowOpenPGP',

			'ExternalHostNameOfDAVServer'			=> 'WebMail/ExternalHostNameOfDAVServer',
			'ExternalHostNameOfLocalImap'			=> 'WebMail/ExternalHostNameOfLocalImap',
			'ExternalHostNameOfLocalSmtp'			=> 'WebMail/ExternalHostNameOfLocalSmtp',

			'Layout'	=> 'WebMail/Layout',
			'DetectSpecialFoldersWithXList'	=> 'WebMail/DetectSpecialFoldersWithXList',

			'ContactsPerPage'		=> 'Contacts/ContactsPerPage',
			'GlobalAddressBook'		=> 'Contacts/GlobalAddressBookVisibility',

			'CalendarShowWeekEnds'	=> 'Calendar/ShowWeekEnds',
			'CalendarWorkdayStarts'	=> 'Calendar/WorkdayStarts',
			'CalendarWorkdayEnds'	=> 'Calendar/WorkdayEnds',
			'CalendarShowWorkDay'	=> 'Calendar/ShowWorkDay',
			'CalendarWeekStartsOn'	=> 'Calendar/WeekStartsOn',
			'CalendarDefaultTab'	=> 'Calendar/DefaultTab',

			'AllowWebMail'			=> 'WebMail/AllowWebMail',
			'AllowContacts'			=> 'Contacts/AllowContacts',
			'AllowCalendar'			=> 'Calendar/AllowCalendar',
			'AllowFiles'			=> 'Files/AllowFiles',
			'AllowHelpdesk'			=> 'Helpdesk/AllowHelpdesk',
			'UseThreads'			=> 'WebMail/UseThreadsIfSupported'
		);
	}
}
