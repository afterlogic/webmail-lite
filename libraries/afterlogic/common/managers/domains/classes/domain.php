<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdDomain ID of the domain in the database. 
 * @property int $IdTenant
 * @property bool $IsDisabled Set to true if the domain was disabled.
 * @property string $Name Domain name.
 * @property string $Url URL pattern to be matched against actual URL for detecting domain used. 
 * @property bool $OverrideSettings Set to true if domain properties should override default domain settings. 
 * @property bool $IsInternal Set to true for domain hosted by mailserver bundle. 
 * @property bool $IsDefaultDomain Set to true if object instance is created for the default domain. 
 * @property string $SiteName Text to be displayed in web browser tab/window title. 
 * @property string $DefaultLanguage Language used by default, name should match the name of language file under i18n dir with extension omitted. 
 * @property int $DefaultTimeZone Default timezone offset value 
 * @property int $DefaultTimeFormat Set to ETimeFormat::F12 or ETimeFormat::F24 to use 12-hour or 24-hour time format by default, respectively. 
 * @property int $DefaultDateFormat
 *		Accepted values:
 *		EDateFormat::DD_MONTH_YYYY - DD Month YYYY;
 *		EDateFormat::MMDDYYYY - MM/DD/YYYY;
 *		EDateFormat::DDMMYYYY - DD/MM/YYYY;
 *		EDateFormat::MMDDYY - MM/DD/YY;
 *		EDateFormat::DDMMYY - DD/MM/YY. 
 * @property bool $AllowRegistration
 * @property bool $AllowPasswordReset
 * @property int $IncomingMailProtocol Legacy value, only IMAP is currently supported (value is EMailProtocol::IMAP4). 
 * @property string $IncomingMailServer IP address or hostname of incoming mail server. 
 * @property int $IncomingMailPort Port number of incoming mail server, typical values are 143 (non-SSL) or 993 (SSL). 
 * @property bool $IncomingMailUseSSL Set to true if connection to incoming mail server must be made via dedicated SSL port. 
 * @property string $OutgoingMailServer IP address or hostname of outgoing mail server. 
 * @property int $OutgoingMailPort Port number of outgoing mail server, typical values are 25/587 (non-SSL) or 465 (SSL). 
 * @property int $OutgoingMailAuth 
 *		Accepted values:
 *		ESMTPAuthType::NoAuth for no authentication;
 *		ESMTPAuthType::AuthSpecified for using fixed login/password for SMTP authentication;
 *		ESMTPAuthType::AuthCurrentUser for using incoming mail credentials for that. 
 * @property string $OutgoingMailLogin
 *		Username for SMTP authentication. Applied if OutgoingMailAuth is set to ESMTPAuthType::AuthSpecified value.
 * @property string $OutgoingMailPassword
 *		Password for SMTP authentication. Applied if OutgoingMailAuth is set to ESMTPAuthType::AuthSpecified value. 
 * @property bool $OutgoingMailUseSSL Set to true if connection to outgoing mail server must be made via dedicated SSL port. 
 * @property int $OutgoingSendingMethod
 * @property int $UserQuota
 * @property int $AutoCheckMailInterval Interval (in minutes) for invoking automated checkmail, setting its value to 0 disables the feature. 
 * @property string $DefaultSkin Skin used by default.
 * @property int $MailsPerPage Number of messages to be displayed per page in message list. 
 * @property bool $AllowUsersChangeInterfaceSettings Set to true if users can change interface options. 
 * @property bool $AllowUsersChangeEmailSettings Set to true if users can change mailserver access configuration details. Most of those settings, however, are only available under default domain. 
 * @property bool $AllowUsersAddNewAccounts Set to true if users are allowed to add new accounts to their primary ones. 
 * @property bool $AllowNewUsersRegister
 * @property bool $AllowOpenPGP
 * @property int $Layout Legacy value. 
 * @property int $SaveMail
 * @property int $ContactsPerPage Number of address book entries displayed per page. 
 * @property int $GlobalAddressBook
 * @property bool $CalendarShowWeekEnds Accepted values: true or false. 
 * @property int $CalendarWorkdayStarts Define work day start time, respectively. 
 * @property int $CalendarWorkdayEnds Define work day time, respectively. 
 * @property bool $CalendarShowWorkDay Accepted values: true or false. 
 * @property int $CalendarWeekStartsOn Accepted values: ECalendarWeekStartOn::Saturday, ECalendarWeekStartOn::Sunday, ECalendarWeekStartOn::Monday. 
 * @property int $CalendarDefaultTab Accepted values: ECalendarDefaultTab::Day, ECalendarDefaultTab::Week, ECalendarDefaultTab::Month. 
 * @property bool $DetectSpecialFoldersWithXList By default, WebMail detects special folders (also known as system ones) based on their name in IMAP folder tree. In case if IMAP server supports XLIST extension, setting this to true allows for detecting special folders even if they're called in non-standard (e.g. localized) manner. 
 * @property string $ExternalHostNameOfLocalImap Mobile sync configuration option. Only applies to default domain configuration of WebMail Pro. 
 * @property string $ExternalHostNameOfLocalSmtp Mobile sync configuration option. Only applies to default domain configuration of WebMail Pro. 
 * @property string $ExternalHostNameOfDAVServer Mobile sync configuration option. Only applies to default domain configuration of WebMail Pro. 
 * @property bool $UseThreads Set to true if message threading needs to be enabled. Mail server has to support this feature, of course. 
 * @property bool $AllowWebMail Set to true to allow accessing webmail interface. 
 * @property bool $AllowContacts Set to true to enable address book functionality. 
 * @property bool $AllowCalendar Set to true to enable calendar functionality (WebMail Pro only). 
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
	public function validate()
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
	public function getMap()
	{
		return self::getStaticMap();
	}

	/**
	 * @return array
	 */
	public static function getStaticMap()
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
	public function initBeforeChange()
	{
		parent::initBeforeChange();

		if (0 < $this->IdTenant)
		{
			$this->OverrideSettings = true;
		}

		if (!$this->OverrideSettings && !$this->IsDefaultDomain)
		{
			/* @var $oApiDomainsManager CApiDomainsManager */
			$oApiDomainsManager = CApi::Manager('domains');

			$oDefDomain = $oApiDomainsManager->getDefaultDomain();
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

		$this->initBeforeChange();
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
