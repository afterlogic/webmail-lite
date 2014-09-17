<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @property int $IdTenant
 * @property int $IdChannel
 * @property bool $IsDisabled
 * @property bool $IsEnableAdminPanelLogin
 * @property bool $IsDefault
 * @property string $Login
 * @property string $Email
 * @property string $PasswordHash
 * @property string $Description
 * @property int $QuotaInMB
 * @property int $AllocatedSpaceInMB
 * @property string $FilesUsageInBytes
 * @property int $FilesUsageInMB
 * @property int $FilesUsageDynamicQuotaInMB
 * @property int $UserCountLimit
 * @property int $DomainCountLimit
 * @property string $Capa
 * @property int $Expared
 * @property string $PayUrl
 * @property bool $IsTrial
 * @property bool $AllowChangeAdminEmail
 * @property bool $AllowChangeAdminPassword
 * @property string $HelpdeskAdminEmailAccount
 * @property string $HelpdeskClientIframeUrl
 * @property string $HelpdeskAgentIframeUrl
 * @property string $HelpdeskSiteName
 * @property string $HelpdeskStyleAllow
 * @property string $HelpdeskStyleImage
 * @property bool $HelpdeskFetcherType
 * @property bool $HelpdeskAllowFetcher
 * @property int $HelpdeskFetcherTimer
 * @property string $LoginStyleImage
 * @property string $AppStyleImage
 * @property bool $SipAllow
 * @property bool $SipAllowConfiguration
 * @property string $SipRealm
 * @property string $SipWebsocketProxyUrl
 * @property string $SipOutboundProxyUrl
 * @property string $SipCallerID
 * @property bool $TwilioAllow
 * @property bool $TwilioAllowConfiguration
 * @property string $TwilioAccountSID
 * @property string $TwilioAuthToken
 * @property string $TwilioAppSID
 * @property bool $SocialDropboxAllow
 * @property string $SocialDropboxKey
 * @property string $SocialDropboxSecret
 *
 * @package Tenants
 * @subpackage Classes
 */
class CTenant extends api_AContainer
{
	public function __construct($sLogin = '', $Description = '')
	{
		parent::__construct(get_class($this), 'IdTenant');

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$oSettings =& CApi::GetSettings();

		$this->SetDefaults(array(
			'IdTenant'						=> 0,
			'IdChannel'						=> 0,
			'IsDisabled'					=> false,
			'IsDefault'						=> false,
			'Login'							=> $sLogin,
			'Email'							=> '',
			'PasswordHash'					=> '',
			'Description'					=> $Description,
			'IsEnableAdminPanelLogin'		=> false,
			'QuotaInMB'						=> 0,
			'AllocatedSpaceInMB'			=> 0,
			'FilesUsageInBytes'				=> '0',
			'FilesUsageInMB'				=> 0,
			'FilesUsageDynamicQuotaInMB'	=> 0,
			'UserCountLimit'				=> 0,
			'DomainCountLimit'				=> 0,
			'Capa'							=> (string) $oSettings->GetConf('Common/TenantGlobalCapa'),
			
			'AllowChangeAdminEmail'			=> true,
			'AllowChangeAdminPassword'		=> true,

			'Expared'						=> 0,
			'PayUrl'						=> '',
			'IsTrial'						=> false,

			'HelpdeskAdminEmailAccount'		=> '',
			'HelpdeskClientIframeUrl'		=> '',
			'HelpdeskAgentIframeUrl'		=> '',
			'HelpdeskSiteName'				=> '',
			'HelpdeskStyleAllow'			=> false,
			'HelpdeskStyleImage'			=> '',
			'HelpdeskStyleText'				=> '',

			'LoginStyleImage'				=> '',
			'AppStyleImage'					=> '',

			'HelpdeskFacebookAllow'			=> !!$oSettings->GetConf('Helpdesk/FacebookAllow'),
			'HelpdeskFacebookId'			=> (string) $oSettings->GetConf('Helpdesk/FacebookId'),
			'HelpdeskFacebookSecret'		=> (string) $oSettings->GetConf('Helpdesk/FacebookSecret'),
			'HelpdeskGoogleAllow'			=> !!$oSettings->GetConf('Helpdesk/GoogleAllow'),
			'HelpdeskGoogleId'				=> (string) $oSettings->GetConf('Helpdesk/GoogleId'),
			'HelpdeskGoogleSecret'			=> (string) $oSettings->GetConf('Helpdesk/GoogleSecret'),
			'HelpdeskTwitterAllow'			=> !!$oSettings->GetConf('Helpdesk/TwitterAllow'),
			'HelpdeskTwitterId'				=> (string) $oSettings->GetConf('Helpdesk/TwitterId'),
			'HelpdeskTwitterSecret'			=> (string) $oSettings->GetConf('Helpdesk/TwitterSecret'),

			'HelpdeskFetcherType'			=> EHelpdeskFetcherType::NONE,
			'HelpdeskAllowFetcher'			=> false,
			'HelpdeskFetcherTimer'			=> 0,

			'SocialFacebookAllow'			=> !!$oSettings->GetConf('Social/FacebookAllow'),
			'SocialFacebookId'				=> (string) $oSettings->GetConf('Social/FacebookId'),
			'SocialFacebookSecret'			=> (string) $oSettings->GetConf('Social/FacebookSecret'),
			'SocialGoogleAllow'				=> !!$oSettings->GetConf('Social/GoogleAllow'),
			'SocialGoogleId'				=> (string) $oSettings->GetConf('Social/GoogleId'),
			'SocialGoogleSecret'			=> (string) $oSettings->GetConf('Social/GoogleSecret'),
			'SocialGoogleApiKey'			=> (string) $oSettings->GetConf('Social/GoogleApiKey'),
			'SocialTwitterAllow'			=> !!$oSettings->GetConf('Social/TwitterAllow'),
			'SocialTwitterId'				=> (string) $oSettings->GetConf('Social/TwitterId'),
			'SocialTwitterSecret'			=> (string) $oSettings->GetConf('Social/TwitterSecret'),
			'SocialDropboxAllow'			=> !!$oSettings->GetConf('Social/DropboxAllow'),
			'SocialDropboxKey'				=> (string) $oSettings->GetConf('Social/DropboxKey'),
			'SocialDropboxSecret'			=> (string) $oSettings->GetConf('Social/DropboxSecret'),

			'SipAllow'						=> !!$oSettings->GetConf('Sip/AllowSip'),
			'SipAllowConfiguration'			=> false,
			'SipRealm'						=> (string) $oSettings->GetConf('Sip/Realm'),
			'SipWebsocketProxyUrl'			=> (string) $oSettings->GetConf('Sip/WebsocketProxyUrl'),
			'SipOutboundProxyUrl'			=> (string) $oSettings->GetConf('Sip/OutboundProxyUrl'),
			'SipCallerID'					=> (string) $oSettings->GetConf('Sip/CallerID'),
			
			'TwilioAllow'					=> !!$oSettings->GetConf('Twilio/AllowTwilio'),
			'TwilioAllowConfiguration'		=> false,
			'TwilioPhoneNumber'				=> (string) $oSettings->GetConf('Twilio/PhoneNumber'),
			'TwilioAccountSID'				=> (string) $oSettings->GetConf('Twilio/AccountSID'),
			'TwilioAuthToken'				=> (string) $oSettings->GetConf('Twilio/AuthToken'),
			'TwilioAppSID'					=> (string) $oSettings->GetConf('Twilio/AppSID')
		));

		$this->SetLower(array('Login', 'Email', 'HelpdeskAdminEmailAccount'));
		$this->SetUpper(array('Capa'));
	}

	/**
	 * @param string $sPassword
	 *
	 * @return string
	 */
	public static function HashPassword($sPassword)
	{
		return empty($sPassword) ? '' : md5('Awm'.md5($sPassword.'Awm'));
	}

	/**
	 * @param string $sPassword
	 *
	 * @return bool
	 */
	public function ValidatePassword($sPassword)
	{
		return self::HashPassword($sPassword) === $this->PasswordHash;
	}

	/**
	 * @param string $sPassword
	 */
	public function SetPassword($sPassword)
	{
		$this->PasswordHash = self::HashPassword($sPassword);
	}

	public function GetUserCount()
	{
		$oUsersApi = CApi::Manager('users');
		return $oUsersApi->GetUserCountByTenantId($this->IdTenant);
	}

	public function GetDomainCount()
	{
		$oDomainsApi = CApi::Manager('domains');
		return $oDomainsApi->GetDomainCount('', $this->IdTenant);
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		if (!$this->IsDefault)
		{
			switch (true)
			{
				case !api_Validate::IsValidTenantLogin($this->Login):
					throw new CApiValidationException(Errs::Validation_InvalidTenantName);
				case api_Validate::IsEmpty($this->Login):
					throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
						'{{ClassName}}' => 'CTenant', '{{ClassField}}' => 'Login'));
				case !api_Validate::IsEmpty($this->Email) && !preg_match('/^[^@]+@[^@]+$/', $this->Email):
					throw new CApiValidationException(Errs::Validation_InvalidEmail, null, array(
						'{{ClassName}}' => 'CTenant', '{{ClassField}}' => 'Email'));
			}
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
			'IdTenant'					=> array('int', 'id_tenant', false, false),
			'IdChannel'					=> array('int', 'id_channel', true, false),
			'IsDisabled'				=> array('bool', 'disabled'),
			'IsDefault'					=> array('bool'),
			'IsEnableAdminPanelLogin'	=> array('bool', 'login_enabled'),
			'Login'						=> array('string(255)', 'login', true, false),
			'Email'						=> array('string(255)', 'email'),
			'PasswordHash'				=> array('string(100)', 'password'),
			'Description'				=> array('string(255)', 'description'),
			'AllocatedSpaceInMB'		=> array('int'),
			'FilesUsageInMB'			=> array('int'),
			'FilesUsageDynamicQuotaInMB'=> array('int'),
			'FilesUsageInBytes'			=> array('string', 'files_usage_bytes'),
			'QuotaInMB'					=> array('int', 'quota'),
			'UserCountLimit'			=> array('int', 'user_count_limit'),
			'DomainCountLimit'			=> array('int', 'domain_count_limit'),
			'Capa'						=> array('string', 'capa'),
			
			'AllowChangeAdminEmail'		=> array('bool', 'allow_change_email'),
			'AllowChangeAdminPassword'	=> array('bool', 'allow_change_password'),

			'Expared'					=> array('int', 'expared_timestamp'),
			'PayUrl'					=> array('string', 'pay_url'),
			'IsTrial'					=> array('bool', 'is_trial'),

			'HelpdeskAdminEmailAccount'	=> array('string', 'hd_admin_email_account'),
			'HelpdeskClientIframeUrl'	=> array('string', 'hd_client_iframe_url'),
			'HelpdeskAgentIframeUrl'	=> array('string', 'hd_agent_iframe_url'),
			'HelpdeskSiteName'			=> array('string', 'hd_site_name'),
			'HelpdeskStyleAllow'		=> array('bool', 'hd_style_allow'),
			'HelpdeskStyleImage'		=> array('string', 'hd_style_image'),
			'HelpdeskStyleText'			=> array('string', 'hd_style_text'),

			'LoginStyleImage'			=> array('string', 'login_style_image'),
			'AppStyleImage'				=> array('string', 'app_style_image'),

			'HelpdeskFacebookAllow'		=> array('bool', 'hd_facebook_allow'),
			'HelpdeskFacebookId'		=> array('string', 'hd_facebook_id'),
			'HelpdeskFacebookSecret'	=> array('string', 'hd_facebook_secret'),
			'HelpdeskGoogleAllow'		=> array('bool', 'hd_google_allow'),
			'HelpdeskGoogleId'			=> array('string', 'hd_google_id'),
			'HelpdeskGoogleSecret'		=> array('string', 'hd_google_secret'),
			'HelpdeskTwitterAllow'		=> array('bool', 'hd_twitter_allow'),
			'HelpdeskTwitterId'			=> array('string', 'hd_twitter_id'),
			'HelpdeskTwitterSecret'		=> array('string', 'hd_twitter_secret'),
			'HelpdeskAllowFetcher'		=> array('bool', 'hd_allow_fetcher'),
			'HelpdeskFetcherType'		=> array('int', 'hd_fetcher_type'),
			'HelpdeskFetcherTimer'		=> array('int', 'hd_fetcher_timer'),

			'SocialFacebookAllow'		=> array('bool', 'social_facebook_allow'),
			'SocialFacebookId'			=> array('string', 'social_facebook_id'),
			'SocialFacebookSecret'		=> array('string', 'social_facebook_secret'),
			'SocialGoogleAllow'			=> array('bool', 'social_google_allow'),
			'SocialGoogleId'			=> array('string', 'social_google_id'),
			'SocialGoogleSecret'		=> array('string', 'social_google_secret'),
			'SocialGoogleApiKey'		=> array('string', 'social_google_api_key'),
			'SocialTwitterAllow'		=> array('bool', 'social_twitter_allow'),
			'SocialTwitterId'			=> array('string', 'social_twitter_id'),
			'SocialTwitterSecret'		=> array('string', 'social_twitter_secret'),
			'SocialDropboxAllow'		=> array('bool', 'social_dropbox_allow'),
			'SocialDropboxKey'			=> array('string', 'social_dropbox_key'),
			'SocialDropboxSecret'		=> array('string', 'social_dropbox_secret'),

			'SipAllow'					=> array('bool', 'sip_allow'),
			'SipAllowConfiguration'		=> array('bool', 'sip_allow_configuration'),
			'SipRealm'					=> array('string', 'sip_realm'),
			'SipWebsocketProxyUrl'		=> array('string', 'sip_websocket_proxy_url'),
			'SipOutboundProxyUrl'		=> array('string', 'sip_outbound_proxy_url'),
			'SipCallerID'				=> array('string', 'sip_caller_id'),
			
			'TwilioAllow'				=> array('bool', 'twilio_allow'),
			'TwilioAllowConfiguration'	=> array('bool', 'twilio_allow_configuration'),
			'TwilioPhoneNumber'			=> array('string', 'twilio_phone_number'),
			'TwilioAccountSID'			=> array('string', 'twilio_account_sid'),
			'TwilioAuthToken'			=> array('string', 'twilio_auth_token'),
			'TwilioAppSID'				=> array('string', 'twilio_app_sid')
		);
	}

	/**
	 * @return string
	 */
	public function GetHelpdeskStyleText()
	{
		return '' !== $this->HelpdeskStyleText ? base64_decode($this->HelpdeskStyleText) : '';
	}

	/**
	 * @param string $sStyle
	 */
	public function SetHelpdeskStyleText($sStyle)
	{
		$sStyle = trim($sStyle);
		$this->HelpdeskStyleText = ('' !== $sStyle) ? base64_encode($sStyle) : '';
	}
	
	/**
	 * @return bool
	 */
	public function IsFilesSupported()
	{
		if (!CApi::GetConf('capa', false))
		{
			return true;
		}

		return '' === $this->Capa || false !== strpos($this->Capa, ETenantCapa::FILES);
	}

	/**
	 * @return bool
	 */
	public function IsHelpdeskSupported()
	{
		if (!CApi::GetConf('capa', false))
		{
			return true;
		}

		return '' === $this->Capa || false !== strpos($this->Capa, ETenantCapa::HELPDESK);
	}

	/**
	 * @return bool
	 */
	public function IsSipSupported()
	{
		if (!CApi::GetConf('capa', false))
		{
			return true;
		}

		return '' === $this->Capa || false !== strpos($this->Capa, ETenantCapa::SIP);
	}

	/**
	 * @return bool
	 */
	public function IsTwilioSupported()
	{
		if (!CApi::GetConf('capa', false))
		{
			return true;
		}

		return '' === $this->Capa || false !== strpos($this->Capa, ETenantCapa::TWILIO);
	}
}
