<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
	
	const UserManager_SocialAccountAlreadyExists = 1033;
	
	// validation
	const Validation_InvalidPort = 1101;
	const Validation_FieldIsEmpty = 1102;
	const Validation_InvalidPort_OutInfo = 1103;
	const Validation_FieldIsEmpty_OutInfo = 1104;
	const Validation_InvalidParameters = 1105;
	const Validation_ObjectNotComplete = 1106;
	const Validation_InvalidEmail = 1107;
	const Validation_InvalidEmail_OutInfo = 1108;
	const Validation_InvalidTenantName = 1109;
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

	// tenants
	const TenantsManager_TenantAlreadyExists = 1701;
	const TenantsManager_TenantCreateFailed = 1702;
	const TenantsManager_TenantUpdateFailed = 1703;
	const TenantsManager_TenantDoesNotExist = 1704;
	const TenantsManager_AccountCreateUserLimitReached = 1705;
	const TenantsManager_DomainCreateUserLimitReached = 1706;
	const TenantsManager_QuotaLimitExided = 1707;
	const TenantsManager_AccountUpdateUserLimitReached = 1705;

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
	const Mail_CannotRenameNonExistenFolder = 4006;
	const Mail_CannotSendMessage = 4007;
	const Mail_CannotSaveMessageInSentItems = 4008;
	const Mail_MailboxUnavailable = 4009;

	// fetcher
	const Fetcher_ConnectToMailServerFailed = 4101;
	const Fetcher_AuthError = 4102;

	// Sabre
	const Sabre_Exception = 5001;
	const Sabre_PreconditionFailed = 5002;

	// Helpdesk
	const HelpdeskManager_UserAlreadyExists = 6001;
	const HelpdeskManager_UserCreateFailed = 6002;
	const HelpdeskManager_UserUpdateFailed = 6003;
	const HelpdeskManager_AccountAuthentication = 6004;
	const HelpdeskManager_ThreadCreateFailed = 6005;
	const HelpdeskManager_ThreadUpdateFailed = 6006;
	const HelpdeskManager_PostCreateFailed = 6007;
	const HelpdeskManager_AccountSystemAuthentication = 6008;
	const HelpdeskManager_AccountCannotBeDeleted = 6009;
	const HelpdeskManager_UnactivatedUser = 6010;

	/*// Rest
	const Rest_InvalidParameters = 7001;
	const Rest_InvalidCredentials = 7002;
	const Rest_InvalidToken = 7003;
	const Rest_TokenExpired = 7004;
	const Rest_AccountCreateFailed = 7010;
	const Rest_AccountUpdateFailed = 7011;
	const Rest_AccountDeleteFailed = 7012;
	const Rest_AccountFindFailed = 7013;
	const Rest_AccountEnableFailed = 7014;
	const Rest_AccountDisableFailed = 7015;
	const Rest_AccountPasswordChangeFailed = 7016;
	const Rest_AccountListGetFailed = 7017;
	const Rest_DomainCreateFailed = 7020;
	const Rest_DomainUpdateFailed = 7021;
	const Rest_DomainFindFailed = 7022;
	const Rest_DomainListGetFailed = 7023;
	const Rest_TenantFindFailed = 7030;*/

	/**
	 * @param int $iCode
	 * @param array $aParams = array()
	 * @return string
	 */
	public static function GetMessageByCode($iCode, $aParams = array())
	{
		static $aMessages = null;
		if (null === $aMessages)
		{
			$aMessages = array(
				self::UserManager_AccountAlreadyExists => CApi::I18N('API/USERMANAGER_ACCOUNT_ALREADY_EXISTS'),
				self::UserManager_AccountCreateFailed => CApi::I18N('API/USERMANAGER_ACCOUNT_CREATE_FAILED'),
				self::UserManager_AccountUpdateFailed => CApi::I18N('API/USERMANAGER_ACCOUNT_UPDATE_FAILED'),
				self::UserManager_AccountAuthenticationFailed => CApi::I18N('API/USERMANAGER_ACCOUNT_AUTHENTICATION_FAILED'),
				self::UserManager_AccountCreateUserLimitReached => CApi::I18N('API/USERMANAGER_ACCOUNT_CREATE_USER_LIMIT_REACHED'),
				self::UserManager_AccountDoesNotExist => CApi::I18N('API/USERMANAGER_ACCOUNT_DOES_NOT_EXIST'),
				self::UserManager_LicenseKeyIsOutdated => CApi::I18N('API/USERMANAGER_LICENSE_KEY_IS_OUTDATED'),
				self::UserManager_LicenseKeyInvalid => CApi::I18N('API/USERMANAGER_LICENSE_KEY_INVALID'),
				self::UserManager_IdentityCreateFailed => CApi::I18N('API/USERMANAGER_IDENTIFY_CREATE_FAILED'),
				self::UserManager_IdentityUpdateFailed => CApi::I18N('API/USERMANAGER_IDENTITI_UPDATE_FAILED'),
				self::UserManager_AccountConnectToMailServerFailed => CApi::I18N('API/USERMANAGER_ACCOUNT_CONNECT_TO_MAIL_SERVER_FAILED'),

				self::UserManager_AccountOldPasswordNotCorrect => CApi::I18N('API/USERMANAGER_ACCOUNT_OLD_PASSWORD_NOT_CORRECT'),
				self::UserManager_AccountNewPasswordUpdateError => CApi::I18N('API/USERMANAGER_ACCOUNT_NEW_PASSWORD_UPDATE_ERROR'),
				self::UserManager_AccountNewPasswordRejected => CApi::I18N('API/USERMANAGER_ACCOUNT_NEW_PASSWORD_REJECTED'),

				self::UserManager_CalUserCreateFailed => CApi::I18N('API/USERMANAGER_CALUSER_CREATE_FAILED'),
				self::UserManager_CalUserUpdateFailed => CApi::I18N('API/USERMANAGER_CALUSER_UPDATE_FAILED'),
				self::UserManager_CalUserAlreadyExists => CApi::I18N('API/USERMANAGER_CALUSER_ALREADY_EXISTS'),

				self::UserManager_SocialAccountAlreadyExists => CApi::I18N('API/USERMANAGER_SOCIAL_ACCOUNT_ALREADY_EXISTS'),

				self::DomainsManager_DomainAlreadyExists => CApi::I18N('API/DOMAINSMANAGER_DOMAIN_ALREADY_EXISTS'),
				self::DomainsManager_DomainCreateFailed => CApi::I18N('API/DOMAINSMANAGER_DOMAIN_CREATE_FAILED'),
				self::DomainsManager_DomainUpdateFailed => CApi::I18N('API/DOMAINSMANAGER_DOMAIN_UPDATE_FAILED'),
				self::DomainsManager_DomainNotEmpty => CApi::I18N('API/DOMAINSMANAGER_DOMAIN_NOT_EMPTY'),
				self::DomainsManager_DomainDoesNotExist => CApi::I18N('API/DOMAINSMANAGER_DOMAIN_DOES_NOT_EXIST'),

				self::TenantsManager_TenantAlreadyExists => CApi::I18N('API/TENANTSMANAGER_TENANT_ALREADY_EXISTS'),
				self::TenantsManager_TenantCreateFailed => CApi::I18N('API/TENANTSMANAGER_TENANT_CREATE_FAILED'),
				self::TenantsManager_TenantUpdateFailed => CApi::I18N('API/TENANTSMANAGER_TENANT_UPDATE_FAILED'),
				self::TenantsManager_TenantDoesNotExist => CApi::I18N('API/TENANTSMANAGER_TENANT_DOES_NOT_EXIST'),
				self::TenantsManager_AccountCreateUserLimitReached => CApi::I18N('API/TENANTSMANAGER_ACCOUNT_CREATE_USER_LIMIT_REACHED'),
				self::TenantsManager_AccountUpdateUserLimitReached => CApi::I18N('API/TENANTSMANAGER_ACCOUNT_UPDATE_USER_LIMIT_REACHED'),
				self::TenantsManager_DomainCreateUserLimitReached => CApi::I18N('API/TENANTSMANAGER_DOMAIN_CREATE_USER_LIMIT_REACHED'),
				self::TenantsManager_QuotaLimitExided => CApi::I18N('API/TENANTS_MANAGER_QUOTA_LIMIT_EXCEEDED'),

				self::ChannelsManager_ChannelAlreadyExists => CApi::I18N('API/CHANNELSMANAGER_CHANNEL_ALREADY_EXISTS'),
				self::ChannelsManager_ChannelCreateFailed => CApi::I18N('API/CHANNELSMANAGER_CHANNEL_CREATE_FAILED'),
				self::ChannelsManager_ChannelUpdateFailed => CApi::I18N('API/CHANNELSMANAGER_CHANNEL_UPDATE_FAILED'),
				self::ChannelsManager_ChannelDoesNotExist => CApi::I18N('API/CHANNELSMANAGER_CHANNEL_DOES_NOT_EXIST'),

				self::MailSuiteManager_MailingListAlreadyExists => CApi::I18N('API/MAILSUITEMANAGER_MAILING_LIST_ALREADY_EXISTS'),
				self::MailSuiteManager_MailingListCreateFailed => CApi::I18N('API/MAILSUITEMANAGER_MAILING_LIST_CREATE_FAILED'),
				self::MailSuiteManager_MailingListUpdateFailed => CApi::I18N('API/MAILSUITEMANAGER_MAILING_LIST_UPDATE_FAILED'),
				self::MailSuiteManager_MailingListInvalid => CApi::I18N('API/MAILSUITEMANAGER_MAILING_LIST_INVALID'),

				self::WebMailManager_AccountDisabled => CApi::I18N('API/WEBMAILMANAGER_ACCOUNT_DISABLED'),
				self::WebMailManager_AccountWebmailDisabled => CApi::I18N('API/WEBMAILMANAGER_ACCOUNT_WEBMAIL_DISABLED'),
				self::WebMailManager_AccountCreateOnLogin => CApi::I18N('API/WEBMAILMANAGER_CREATE_ON_LOGIN'),
				self::WebMailManager_AccountAuthentication => CApi::I18N('API/WEBMAILMANAGER_ACCOUNT_AUTHENTICATION'),
				self::WebMailManager_DomainDoesNotExist => CApi::I18N('API/WEBMAILMANAGER_DOMAIN_DOES_NOT_EXIST'),
				self::WebMailManager_AccountConnectToMailServerFailed => CApi::I18N('API/WEBMAILMANAGER_ACCOUNT_CONNECT_TO_MAIL_SERVER_FAILED'),

				self::Validation_InvalidPort => CApi::I18N('API/VALIDATION_INVALID_PORT'),
				self::Validation_InvalidEmail => CApi::I18N('API/VALIDATION_INVALID_EMAIL'),
				self::Validation_FieldIsEmpty => CApi::I18N('API/VALIDATION_FIELD_IS_EMPTY'),
				self::Validation_InvalidPort_OutInfo => CApi::I18N('API/VALIDATION_INVALID_PORT_OUTINFO'),
				self::Validation_InvalidEmail_OutInfo => CApi::I18N('API/VALIDATION_INVALID_EMAIL_OUTINFO'),
				self::Validation_FieldIsEmpty_OutInfo => CApi::I18N('API/VALIDATION_FIELD_IS_EMPTY_OUTINFO'),
				self::Validation_InvalidParameters => CApi::I18N('API/VALIDATION_INVALID_PARAMETERS'),
				self::Validation_InvalidTenantName => CApi::I18N('API/VALIDATION_INVALID_TENANT_NAME'),
				self::Validation_InvalidChannelName => CApi::I18N('API/VALIDATION_INVALID_CHANNEL_NAME'),

				self::Container_UndefinedProperty => CApi::I18N('API/CONTAINER_UNDEFINED_PROPERTY'),

				self::Main_SettingLoadError => CApi::I18N('API/MAIN_SETTINGS_LOAD_ERROR'),
				self::Main_UnknownError => CApi::I18N('API/MAIN_UNKNOWN_ERROR'),
				self::Main_CustomError => CApi::I18N('API/MAIN_CUSTOM_ERROR'),

				self::Db_ExceptionError => CApi::I18N('API/DB_EXCEPTION_ERROR'),
				self::Db_PdoExceptionError => CApi::I18N('API/DB_PDO_EXCEPTION_ERROR'),

				self::Mail_FolderNameContainDelimiter => CApi::I18N('API/MAIL_FOLDER_NAME_CONTAIN_DELIMITER'),
				self::Mail_AccountAuthentication => CApi::I18N('API/MAIL_ACCOUNT_AUTHENTICATION'),
				self::Mail_AccountConnectToMailServerFailed => CApi::I18N('API/MAIL_ACCOUNT_CONNECT_TO_MAIL_SERVER_FAILED'),
				self::Mail_CannotRenameNonExistenFolder => CApi::I18N('API/MAIL_CANNOT_RENAME_NON_EXITEN_FOLDER'),
				self::Mail_CannotSendMessage => CApi::I18N('API/MAIL_CANNOT_SEND_MESSAGE'),
				self::Mail_MailboxUnavailable => CApi::I18N('API/MAIL_MAILBOX_UNAVAILABLE'),

				self::Fetcher_ConnectToMailServerFailed => CApi::I18N('API/FETCHER_CONNECT_TO_MAIL_SERVER_FAILED'),
				self::Fetcher_AuthError => CApi::I18N('API/FETCHER_AUTH_ERROR'),

				self::Sabre_Exception => CApi::I18N('API/SABRE_EXCEPTION'),
				self::Sabre_PreconditionFailed => CApi::I18N('API/SABRE_PRECONDITION_FAILED')

				/*self::Rest_InvalidParameters => CApi::I18N('API/REST_INVALID_PARAMETERS'),
				self::Rest_InvalidCredentials => CApi::I18N('API/REST_INVALID_CREDENTIALS'),
				self::Rest_InvalidToken => CApi::I18N('API/REST_INVALID_TOKEN'),
				self::Rest_TokenExpired => CApi::I18N('API/REST_TOKEN_EXPIRED'),
				self::Rest_AccountCreateFailed => CApi::I18N('API/REST_ACCOUNT_CREATE_FAILED'),
				self::Rest_AccountUpdateFailed => CApi::I18N('API/REST_ACCOUNT_UPDATE_FAILED'),
				self::Rest_AccountDeleteFailed => CApi::I18N('API/REST_ACCOUNT_DELETE_FAILED'),
				self::Rest_AccountFindFailed => CApi::I18N('API/REST_ACCOUNT_FIND_FAILED'),
				self::Rest_AccountEnableFailed => CApi::I18N('API/REST_ACCOUNT_ENABLE_FAILED'),
				self::Rest_AccountDisableFailed => CApi::I18N('API/REST_ACCOUNT_DISABLE_FAILED'),
				self::Rest_AccountPasswordChangeFailed => CApi::I18N('API/REST_ACCOUNT_PASSWORD_CHANGE_FAILED'),
				self::Rest_AccountListGetFailed => CApi::I18N('API/REST_ACCOUNT_LIST_GET_FAILED'),
				self::Rest_DomainCreateFailed => CApi::I18N('API/REST_DOMAIN_CREATE_FAILED'),
				self::Rest_DomainUpdateFailed => CApi::I18N('API/REST_DOMAIN_UPDATE_FAILED'),
				self::Rest_DomainFindFailed => CApi::I18N('API/REST_DOMAIN_FIND_FAILED'),
				self::Rest_DomainListGetFailed => CApi::I18N('API/REST_DOMAIN_LIST_GET_FAILED'),
				self::Rest_TenantFindFailed => CApi::I18N('API/REST_TENANT_FIND_FAILED'),*/
			);
		}

		return isset($aMessages[$iCode])
			? ((0 < count($aParams)) ? strtr($aMessages[$iCode], $aParams) : $aMessages[$iCode])
			: CApi::I18N('API/UNKNOWN_ERROR');
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
	 * @var Exception
	 */
	protected $oPrevious;

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
		$this->oPrevious = $oPrevious ? $oPrevious : null;

		if ($this->oPrevious)
		{
			CApi::Log('Previous Exception: '.$this->oPrevious->getMessage(), ELogLevel::Error);
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

	/**
	 * @return string
	 */
	public function GetPreviousMessage()
	{
		$sMessage = '';
		if ($this->oPrevious instanceof \MailSo\Imap\Exceptions\NegativeResponseException)
		{
			$oResponse = /* @var $oResponse \MailSo\Imap\Response */ $this->oPrevious->GetLastResponse();
			
			$sMessage = $oResponse instanceof \MailSo\Imap\Response ?
				$oResponse->Tag.' '.$oResponse->StatusOrIndex.' '.$oResponse->HumanReadable : '';
		}
		else if ($this->oPrevious instanceof \MailSo\Smtp\Exceptions\NegativeResponseException)
		{
			$sMessage = $this->oPrevious->getMessage();
//			$oSub = $this->oPrevious->getPrevious();
//			$oSub = $oSub instanceof \MailSo\Smtp\Exceptions\NegativeResponseException ? $oSub : null;
//
//			$sMessage = $oSub ? $oSub->getMessage() : $this->oPrevious->getMessage();
		}
		else if ($this->oPrevious instanceof \Exception)
		{
			$sMessage = $this->oPrevious->getMessage();
		}

		return $sMessage;
	}

	/**
	 * @return string
	 */
	public function GetPreviousException()
	{
		return $this->oPrevious;
	}
}
