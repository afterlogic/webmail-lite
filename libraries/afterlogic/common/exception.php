<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Api
 */
class CApiErrorCodes
{
	// users
	const UserManager_AccountAlreadyExists = 1001;
	const UserManager_AccountCreateFailed = 1002;
	const UserManager_AccountUpdateFailed = 1003;
	const UserManager_AccountAuthenticationFailed = 1004;
	const UserManager_AccountCreateUserLimitReached = 1005;
	const UserManager_AccountDoesNotExist = 1006;
	const UserManager_LicenseKeyIsOutdated = 1007;
	const UserManager_LicenseKeyInvalid = 1008;
	const UserManager_IdentityCreateFailed = 1009;
	const UserManager_IdentityUpdateFailed = 1010;
	const UserManager_AccountConnectToMailServerFailed = 1011;

	const UserManager_AccountOldPasswordNotCorrect = 1020;
	const UserManager_AccountNewPasswordUpdateError = 1021;
	const UserManager_AccountNewPasswordRejected = 1022;

	const UserManager_CalUserCreateFailed = 1030;
	const UserManager_CalUserUpdateFailed = 1031;
	const UserManager_CalUserAlreadyExists = 1032;

	// validation
	const Validation_InvalidPort = 1101;
	const Validation_FieldIsEmpty = 1102;
	const Validation_InvalidPort_OutInfo = 1103;
	const Validation_FieldIsEmpty_OutInfo = 1104;
	const Validation_InvalidParameters = 1105;
	const Validation_ObjectNotComplete = 1106;
	const Validation_InvalidEmail = 1107;
	const Validation_InvalidEmail_OutInfo = 1108;
	const Validation_InvalidRealmName = 1109;
	const Validation_InvalidChannelName = 1110;

	// domains
	const DomainsManager_DomainAlreadyExists = 1301;
	const DomainsManager_DomainCreateFailed = 1302;
	const DomainsManager_DomainUpdateFailed = 1303;
	const DomainsManager_DomainNotEmpty = 1304;
	const DomainsManager_DomainDoesNotExist = 1305;

	// mailsuite
	const MailSuiteManager_MailingListAlreadyExists = 1401;
	const MailSuiteManager_MailingListCreateFailed = 1402;
	const MailSuiteManager_MailingListUpdateFailed = 1403;
	const MailSuiteManager_MailingListInvalid = 1404;
	const MailSuiteManager_MailingListDeleteFailed = 1405;

	// webmail
	const WebMailManager_AccountDisabled = 1501;
	const WebMailManager_AccountWebmailDisabled = 1502;
	const WebMailManager_AccountCreateOnLogin = 1503;
	const WebMailManager_NewUserRegistrationDisabled = 1504;
	const WebMailManager_AccountAuthentication = 1505;
	const WebMailManager_DomainDoesNotExist = 1506;
	const WebMailManager_AccountConnectToMailServerFailed = 1507;

	// container
	const Container_UndefinedProperty = 1601;

	// realms
	const RealmsManager_RealmAlreadyExists = 1701;
	const RealmsManager_RealmCreateFailed = 1702;
	const RealmsManager_RealmUpdateFailed = 1703;
	const RealmsManager_RealmDoesNotExist = 1704;
	const RealmsManager_AccountCreateUserLimitReached = 1705;
	const RealmsManager_DomainCreateUserLimitReached = 1706;
	const RealmsManager_QuotaLimitExided = 1707;

	// channels
	const ChannelsManager_ChannelAlreadyExists = 1801;
	const ChannelsManager_ChannelCreateFailed = 1802;
	const ChannelsManager_ChannelUpdateFailed = 1803;
	const ChannelsManager_ChannelDoesNotExist = 1804;

	// main
	const Main_SettingLoadError = 2001;
	const Main_UnknownError = 2002;
	const Main_CustomError = 2003;

	// db
	const Db_ExceptionError = 3001;
	const Db_PdoExceptionError = 3002;

	// mail
	const Mail_FolderNameContainDelimiter = 4001;
	const Mail_AccountAuthentication = 4002;
	const Mail_AccountConnectToMailServerFailed = 4003;
	const Mail_AccountLoginFailed = 4004;
	const Mail_InvalidRecipients = 4005;

	// Sabre
	const Sabre_Exception = 5001;
	const Sabre_PreconditionFailed = 5002;

	/**
	 * @param int $iCode
	 * @param array $aParams = array()
	 * @return string
	 */
	public static function GetMessageByCode($iCode, $aParams = array())
	{
		static $aMessages = array(
			self::UserManager_AccountAlreadyExists => 'Such account already exists',
			self::UserManager_AccountCreateFailed => 'Failed to create',
			self::UserManager_AccountUpdateFailed => 'Failed to update',
			self::UserManager_AccountAuthenticationFailed => 'Authentication failed',
			self::UserManager_AccountCreateUserLimitReached => 'User couldn\'t be created because max number of users allowed by your license exceeded.',
			self::UserManager_AccountDoesNotExist => 'Account does not exist',
			self::UserManager_LicenseKeyIsOutdated => 'This license key is outdated, please contact us to upgrade your license key',
			self::UserManager_LicenseKeyInvalid => 'This license key is invalid',
			self::UserManager_IdentityCreateFailed => 'Failed to create',
			self::UserManager_IdentityUpdateFailed => 'Failed to update',
			self::UserManager_AccountConnectToMailServerFailed => 'Can\'t connect to mail server',

			self::UserManager_AccountOldPasswordNotCorrect => 'Current password is not correct.',
			self::UserManager_AccountNewPasswordUpdateError => 'Can\'t save new password.',
			self::UserManager_AccountNewPasswordRejected => 'Can\'t save new password. Perhaps, it\'s too simple.',

			self::UserManager_CalUserCreateFailed => 'Failed to create',
			self::UserManager_CalUserUpdateFailed => 'Failed to update',
			self::UserManager_CalUserAlreadyExists => 'Such clendar user already exists',

			self::DomainsManager_DomainAlreadyExists => 'Such domain already exists',
			self::DomainsManager_DomainCreateFailed => 'Failed to create',
			self::DomainsManager_DomainUpdateFailed => 'Failed to update',
			self::DomainsManager_DomainNotEmpty => 'Before deleting a domain, please delete all its users first',
			self::DomainsManager_DomainDoesNotExist => 'Domain does not exist',

			self::RealmsManager_RealmAlreadyExists => 'Such realm already exists',
			self::RealmsManager_RealmCreateFailed => 'Failed to create',
			self::RealmsManager_RealmUpdateFailed => 'Failed to update',
			self::RealmsManager_RealmDoesNotExist => 'Realm does not exist',
			self::RealmsManager_AccountCreateUserLimitReached => 'User couldn\'t be created because max number of users allowed per your realm is reached.',
			self::RealmsManager_DomainCreateUserLimitReached => 'Domain couldn\'t be created because max number of domains allowed per your realm is reached.',
			self::RealmsManager_QuotaLimitExided => 'The requested change of disk space quota cannot be completed as the summed quota size for all accounts would exceed the total quota allocated for the realm.',

			self::ChannelsManager_ChannelAlreadyExists => 'Such channel already exists',
			self::ChannelsManager_ChannelCreateFailed => 'Failed to create',
			self::ChannelsManager_ChannelUpdateFailed => 'Failed to update',
			self::ChannelsManager_ChannelDoesNotExist => 'Channel does not exist',

			self::MailSuiteManager_MailingListAlreadyExists => 'Such account already exists',
			self::MailSuiteManager_MailingListCreateFailed => 'Failed to create',
			self::MailSuiteManager_MailingListUpdateFailed => 'Failed to update',
			self::MailSuiteManager_MailingListInvalid => 'Mailing list not valid',

			self::WebMailManager_AccountDisabled => 'Account is inactive, please contact the system administrator on this',
			self::WebMailManager_AccountWebmailDisabled => 'Webmail is inactive',
			self::WebMailManager_AccountCreateOnLogin => 'There was an error while creating account',
			self::WebMailManager_NewUserRegistrationDisabled => 'Registering new users is not allowed',
			self::WebMailManager_AccountAuthentication => 'The username or password you entered is incorrect',
			self::WebMailManager_DomainDoesNotExist => 'Domain does not exist',
			self::WebMailManager_AccountConnectToMailServerFailed => 'Connect to mail server failed',

			self::Validation_InvalidPort => 'Valid port required ({{ClassName}}->{{ClassField}})',
			self::Validation_InvalidEmail => 'Valid email required ({{ClassName}}->{{ClassField}})',
			self::Validation_FieldIsEmpty => 'Required fields cannot be empty ({{ClassName}}->{{ClassField}})',
			self::Validation_InvalidPort_OutInfo => 'Valid port required',
			self::Validation_InvalidEmail_OutInfo => 'Valid email required',
			self::Validation_FieldIsEmpty_OutInfo => 'Required fields cannot be empty',
			self::Validation_InvalidParameters => 'Invalid parameters',
			self::Validation_InvalidRealmName => 'Valid name required',
			self::Validation_InvalidChannelName => 'Valid name required',

			self::Container_UndefinedProperty => 'Undefined property {{PropertyName}}',

			self::Main_SettingLoadError => 'Can\'t get settings',
			self::Main_UnknownError => 'Unknown error',
			self::Main_CustomError => 'Custom error',

			self::Db_ExceptionError => 'Database error',
			self::Db_PdoExceptionError => 'Database error',

			self::Mail_FolderNameContainDelimiter => 'Folder name contain delimiter',
			self::Mail_AccountAuthentication => 'The username or password you entered is incorrect',
			self::Mail_AccountConnectToMailServerFailed => 'Connect to mail server failed',

			self::Sabre_Exception => 'Sabre exception',
			self::Sabre_PreconditionFailed => 'Sabre precondition failed'
		);

		return isset($aMessages[$iCode])
			? ((0 < count($aParams)) ? strtr($aMessages[$iCode], $aParams) : $aMessages[$iCode])
			: 'Unknown error';
	}
}

/**
 * Alias
 *
 * @package Api
 */
class Errs extends CApiErrorCodes {}

/**
 * @package Api
 */
class CApiException extends Exception {}

/**
 * @package Api
 */
class CApiInvalidArgumentException extends CApiException {}

/**
 * @package Api
 */
class CApiDbException extends CApiException {}

/**
 * @package Api
 */
class CApiBaseException extends CApiException
{
	/**
	 * @var array
	 */
	protected $aObjectParams;

	/**
	 * @param int $iCode
	 * @param Exception $oPrevious = null
	 * @param array $aParams = array()
	 * @param array $aObjectParams = array()
	 */
	public function __construct($iCode, $oPrevious = null, $aParams = array(), $aObjectParams = array())
	{
		if (CApiErrorCodes::Validation_InvalidPort === $iCode)
		{
			CApi::Log('Exception error: '.CApiErrorCodes::GetMessageByCode($iCode, $aParams), ELogLevel::Error);
			$iCode = CApiErrorCodes::Validation_InvalidPort_OutInfo;
		}
		else if (CApiErrorCodes::Validation_InvalidEmail === $iCode)
		{
			CApi::Log('Exception error: '.CApiErrorCodes::GetMessageByCode($iCode, $aParams), ELogLevel::Error);
			$iCode = CApiErrorCodes::Validation_InvalidEmail_OutInfo;
		}
		else if (CApiErrorCodes::Validation_FieldIsEmpty === $iCode)
		{
			CApi::Log('Exception error: '.CApiErrorCodes::GetMessageByCode($iCode, $aParams), ELogLevel::Error);
			$iCode = CApiErrorCodes::Validation_FieldIsEmpty_OutInfo;
		}

		$this->aObjectParams = $aObjectParams;

		if ($oPrevious)
		{
			CApi::Log('Previous Exception: '.$oPrevious->getMessage(), ELogLevel::Error);
		}

		parent::__construct(CApiErrorCodes::GetMessageByCode($iCode, $aParams), $iCode);
	}

	/**
	 * @return array
	 */
	public function GetObjectParams()
	{
		return $this->aObjectParams;
	}
}
