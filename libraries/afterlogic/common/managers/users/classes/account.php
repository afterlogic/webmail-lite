<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CAccount summary
 * 
 * @property int $IdAccount Account's ID.
 * @property int $IdUser User ID.
 * @property int $IdDomain Domain's ID.
 * @property int $IdTenant Tenant's ID (Aurora only).
 * @property bool $IsInternal If **true**, the account is hosted by bundled mailserver.
 * @property bool $IsDisabled If **true**, the account is disabled by the administrator.
 * @property bool $IsDefaultAccount If **true**, it's the primary account of this user, their other accounts are linked ones.
 * @property bool $IsMailingList If **true**, the account denotes mailing list rather than regular email account.
 * @property int $StorageQuota Account storage quota.
 * @property int $StorageUsedSpace Disk space currently used by the account.
 * @property string $Email Email address of the account.
 * @property string $FriendlyName Display name of the account.
 * @property int $IncomingMailProtocol deprecated.
 * @property string $IncomingMailServer IMAP server's hostname or IP address.
 * @property int $IncomingMailPort IMAP server port.
 * @property string $IncomingMailLogin IMAP login value.
 * @property string $IncomingMailPassword Account password on IMAP.
 * @property bool $IncomingMailUseSSL If **true**, SSL access is used to access IMAP.
 * @property string $PreviousMailPassword Account password stored in the database, may differ for actual password entered by user, e.g. if password was changed on mailserver.
 * @property string $OutgoingMailServer SMTP server's hostname or IP address.
 * @property int $OutgoingMailPort SMTP server port.
 * @property string $OutgoingMailLogin SMTP login value.
 * @property string $OutgoingMailPassword Account password on SMTP.
 * @property int $OutgoingMailAuth Denotes whether SMTP authentication is used.
 * @property bool $OutgoingMailUseSSL If **true**, SSL access is used to access SMTP.
 * @property int $OutgoingSendingMethod Reserved for future use.
 * @property bool $HideInGAB If set to **true**, account will be excluded from global address book.
 * @property string $Signature Account's signature.
 * @property int $SignatureType **0** for plaintext signature, **1** for HTML one.
 * @property int $SignatureOptions **0** means signature isn't used, **1** means signature is added to new messages only, **2** - added to all messages.
 * @property int $GlobalAddressBook Defines mode of presenting the account in global address book.
 * @property bool $AllowCompose **true** if composing new messages is allowed for the account.
 * @property bool $AllowReply **true** if replying to messages is allowed for the account.
 * @property bool $AllowForward **true** if forwarding messages is allowed for the account. 
 * @property bool $DetectSpecialFoldersWithXList If set to **true**, WebMail attempts to locate system folders using XLIST extension of IMAP.
 * @property mixed $CustomFields
 * @property bool $ForceSaveOnLogin If set to **true**, account settings will always be updated on login. Default value is **false**.
 * @property bool $AllowMail
 * @property bool $IsPasswordSpecified
 * @package Users
 * @subpackage Classes
 */
class CAccount extends api_AContainer
{
	const ChangePasswordExtension = 'AllowChangePasswordExtension';
	const AutoresponderExtension = 'AllowAutoresponderExtension';
	const SpamFolderExtension = 'AllowSpamFolderExtension';
	const DisableAccountDeletion = 'DisableAccountDeletion';
	const DisableManageFolders = 'DisableManageFolders';
	const SieveFiltersExtension = 'AllowSieveFiltersExtension';
	const ForwardExtension = 'AllowForwardExtension';
	const DisableManageSubscribe = 'DisableManageSubscribe';
	const DisableFoldersManualSort = 'DisableFoldersManualSort';
	const IgnoreSubscribeStatus = 'IgnoreSubscribeStatus';

	/**
	 * @var CUser CUser object for the account.
	 */
	public $User;

	/**
	 * @var CDomain CDomain object for the account.
	 */
	public $Domain;

	/**
	 * @var array
	 */
	protected $aExtension;

	/**
	 * Creates a new instance of the object.
	 * 
	 * @param CDomain $oDomain CDomain object for the account.
	 * 
	 * @return void
	 */
	public function __construct($oDomain)
	{
		parent::__construct(get_class($this), 'IdAccount');

		$this->Domain = $oDomain;
		$this->User = new CUser($oDomain);
		$this->aExtension = array();

		$this->SetTrimer(array('Email', 'FriendlyName', 'IncomingMailServer', 'IncomingMailLogin', 'IncomingMailPassword',
			'PreviousMailPassword', 'OutgoingMailServer', 'OutgoingMailLogin'));

		$this->SetLower(array(/*'Email', */'IncomingMailServer', /*'IncomingMailLogin',*/
			'OutgoingMailServer', /*'OutgoingMailLogin'*/));

		$this->SetDefaults(array(

			'IdAccount'	=> 0,
			'IdUser'	=> 0,
			'IdDomain'	=> $oDomain->IdDomain,
			'IdTenant'	=> $oDomain->IdTenant,

			'IsDefaultAccount'	=> true,
			'IsInternal'		=> $oDomain->IsInternal,
			'IsDisabled'		=> false,
			'IsMailingList'		=> false,

			'StorageQuota'		=> $oDomain->UserQuota,
			'StorageUsedSpace'	=> 0,
			
			'Email'				=> '',
			'FriendlyName'		=> '',

			'IncomingMailProtocol'	=> $oDomain->IncomingMailProtocol,
			'IncomingMailServer'	=> $oDomain->IncomingMailServer,
			'IncomingMailPort'		=> $oDomain->IncomingMailPort,
			'IncomingMailLogin'		=> '',
			'IncomingMailPassword'	=> '',
			'IncomingMailUseSSL'	=> $oDomain->IncomingMailUseSSL,

			'PreviousMailPassword'	=> '',

			'OutgoingMailServer'	=> $oDomain->OutgoingMailServer,
			'OutgoingMailPort'		=> $oDomain->OutgoingMailPort,
			'OutgoingMailLogin'		=> '',
			'OutgoingMailPassword'	=> '',
			'OutgoingMailAuth'		=> $oDomain->OutgoingMailAuth,
			'OutgoingMailUseSSL'	=> $oDomain->OutgoingMailUseSSL,
			'OutgoingSendingMethod'	=> $oDomain->OutgoingSendingMethod,

			'HideInGAB'			=> false,

			'Signature'			=> '',
			'SignatureType'		=> EAccountSignatureType::Html,
			'SignatureOptions'	=> EAccountSignatureOptions::DontAdd,

			'GlobalAddressBook'	=> $oDomain->GlobalAddressBook,

			'AllowCompose'		=> true,
			'AllowReply'		=> true,
			'AllowForward'		=> true,
			
			'DetectSpecialFoldersWithXList' => $oDomain->DetectSpecialFoldersWithXList,

			'CustomFields'		=> '',
			'ForceSaveOnLogin'	=> false,
			'AllowMail'			=> true,
			'IsPasswordSpecified' => true
		));

		CApi::Plugin()->RunHook('api-account-construct', array(&$this));
	}

	/**
	 * Creates new empty instance of the account.
	 * 
	 * @param CDomain $oDomain Domain list entry used to populate default values.
	 * 
	 * @return CAccount
	 */
	public static function createInstance($oDomain)
	{
		return new CAccount($oDomain);
	}

	/**
	 * Obtains timezone offset value in minutes.
	 * 
	 * @return int
	 */
	public function getDefaultTimeOffset()
	{
		return api_Utils::GetTimeOffset($this->User->DefaultTimeZone, $this->User->ClientTimeZone);
	}

	/**
	 * Obtains timezone information.
	 * 
	 * @return string
	 */
	public function getDefaultStrTimeZone()
	{
		return api_Utils::GetStrTimeZone($this->User->DefaultTimeZone, $this->User->ClientTimeZone);
	}

	/**
	 * Enables particular functionality extension.
	 * 
	 * @param string $sExtensionName Name of extension to enable.
	 */
	public function enableExtension($sExtensionName)
	{
		$this->aExtension[] = $sExtensionName;
		$this->aExtension = array_unique($this->aExtension);
	}

	/**
	 * Disables particular functionality extension.
	 * 
	 * @param string $sExtensionName Name of extension to disable.
	 */
	public function disableExtension($sExtensionName)
	{
		$aNewExtension = array();
		$aExtension = $this->aExtension;
		foreach ($aExtension as $sExt)
		{
			if ($sExt !== $sExtensionName)
			{
				$aNewExtension[] = $sExt;
			}
		}
		
		$this->aExtension = array_unique($aNewExtension);
	}

	/**
	 * Checks whether particular functionality extension is enabled.
	 * 
	 * @param string $sExtensionName Name of extension for checking.
	 * 
	 * @return bool
	 */
	public function isExtensionEnabled($sExtensionName)
	{
		return in_array($sExtensionName, $this->aExtension);
	}

	/**
	 * Obtains list of extensions currently enabled for the account.
	 * 
	 * @return array
	 */
	public function getExtensionList()
	{
		return $this->aExtension;
	}

	/**
	 * Initializes Login and Email fields.
	 * 
	 * @param string $sLogin New login for account.
	 * @param string $sAtChar = '@' Symbol to join login and domain parts.
	 */
	public function initLoginAndEmail($sLogin, $sAtChar = '@')
	{
		$this->Email = '';
		$this->IncomingMailLogin = $sLogin;

		$sLoginPart = api_Utils::GetAccountNameFromEmail($sLogin);
		$sDomainPart = api_Utils::GetDomainFromEmail($sLogin);

		$sDomainName = ($this->Domain->IsDefaultDomain || $this->Domain->IsDefaultTenantDomain) ? $sDomainPart : $this->Domain->Name;
		if (!empty($sDomainName))
		{
			$this->Email = $sLoginPart.$sAtChar.$sDomainName;
			if ($this->Domain && $this->Domain->IsInternal && 0 < strlen($this->Domain->Name))
			{
				$this->IncomingMailLogin = $sLoginPart.$sAtChar.$this->Domain->Name;
			}
		}
	}

	/**
	 * Initializes account object by data from database.
	 * 
	 * @param stdClass $oRow Db row.
	 */
	public function initByDbRow($oRow)
	{
		parent::InitByDbRow($oRow);

		if (!$this->Domain->IsDefaultDomain && !$this->Domain->IsDefaultTenantDomain)
		{
			$this->IncomingMailProtocol = $this->Domain->IncomingMailProtocol;
			$this->IncomingMailServer = $this->Domain->IncomingMailServer;
			$this->IncomingMailPort = $this->Domain->IncomingMailPort;
			$this->IncomingMailUseSSL = $this->Domain->IncomingMailUseSSL;

			$this->OutgoingMailServer = $this->Domain->OutgoingMailServer;
			$this->OutgoingMailPort = $this->Domain->OutgoingMailPort;
			$this->OutgoingMailAuth = $this->Domain->OutgoingMailAuth;
			$this->OutgoingMailUseSSL = $this->Domain->OutgoingMailUseSSL;
			$this->OutgoingSendingMethod = $this->Domain->OutgoingSendingMethod;

			if (ESMTPAuthType::AuthSpecified === $this->OutgoingMailAuth)
			{
				$this->OutgoingMailLogin = $this->Domain->OutgoingMailLogin;
				$this->OutgoingMailPassword = $this->Domain->OutgoingMailPassword;
			}
		}

		if ($this->IsMailingList)
		{
			$this->IdUser = 0;
		}

		if ($this->IsInternal)
		{
			if ((int) CApi::GetConf('labs.unlim-quota-limit-size-in-kb', 104857600) <= $this->StorageQuota)
			{
				$this->StorageQuota = 0;
				$this->FlushObsolete('StorageQuota');
			}

			$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');
			if ($oApiUsersManager)
			{
				$this->StorageUsedSpace = $oApiUsersManager->getAccountUsedSpace($this->Email);
				$this->FlushObsolete('StorageUsedSpace');
			}
		}		
	}

	/**
	 * Initialize account object before it's changing. Function with the same name is used for other objects in a unified container **api_AContainer**.
	 */
	public function initBeforeChange()
	{
		parent::initBeforeChange();

		$bObsolete = null !== $this->GetObsoleteValue('StorageQuota');

		$this->StorageQuota = 0 === $this->StorageQuota ?
			(int) CApi::GetConf('labs.unlim-quota-limit-size-in-kb', 104857600) : $this->StorageQuota;

		if (!$bObsolete)
		{
			$this->FlushObsolete('StorageQuota');
		}
	}

	/**
	 * If display name is non-empty, returns email address with display name attached; otherwise, just the email.
	 * 
	 * @return string
	 */
	public function getFriendlyEmail()
	{
		return (0 < strlen($this->FriendlyName))
			? '"'.$this->FriendlyName.'" <'.$this->Email.'>' : $this->Email;
	}

	/**
	 * Performs a check whether account is hosted by GMail, by comparing IMAP hostname with known values.
	 * 
	 * @return bool
	 */
	public function isGmailAccount()
	{
		return 'imap.gmail.com' === strtolower($this->IncomingMailServer) || 'googlemail.com' === strtolower($this->IncomingMailServer);
	}

	/**
	 * Checks if the account can connect to the mail server.
	 * 
	 * @return bool
	 */
	public function isValid()
	{
		switch (true)
		{
			case !api_Validate::Port($this->IncomingMailPort):
				throw new CApiValidationException(Errs::Validation_InvalidPort, null, array(
					'{{ClassName}}' => 'CAccount', '{{ClassField}}' => 'IncomingMailPort'));

			case !api_Validate::Port($this->OutgoingMailPort):
				throw new CApiValidationException(Errs::Validation_InvalidPort, null, array(
					'{{ClassName}}' => 'CAccount', '{{ClassField}}' => 'OutgoingMailPort'));

			case api_Validate::IsEmpty($this->Email):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CAccount', '{{ClassField}}' => 'Email'));

			case api_Validate::IsEmpty($this->IncomingMailLogin):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CAccount', '{{ClassField}}' => 'IncomingMailLogin'));
		}

		return true;
	}

	/**
	 * Obtains static map of account fields. Function with the same name is used for other objects in a unified container **api_AContainer**.
	 * 
	 * @return array
	 */
	public function getMap()
	{
		return self::getStaticMap();
	}

	/**
	 * Gets actual quota information in bytes.
	 * 
	 * @return int
	 */
	public function getRealQuotaSize()
	{
		return 0 === $this->StorageQuota ? (int) CApi::GetConf('labs.unlim-quota-limit-size-in-kb', 104857600) : $this->StorageQuota;
	}

	/**
	 * Obtains static map of account fields.
	 * 
	 * @return array
	 */
	public static function getStaticMap()
	{
		return array(
			'IdAccount'	=> array('int', 'id_acct', false, false),
			'IdUser'	=> array('int', 'id_user'),
			'IdDomain'	=> array('int', 'id_domain'),
			'IdTenant'	=> array('int', 'id_tenant', true, false),

			'IsInternal'		=> array('bool'),
			'IsDisabled'		=> array('bool', 'deleted'),
			'IsDefaultAccount'	=> array('bool', 'def_acct'),
			'IsMailingList'		=> array('bool', 'mailing_list'),

			'StorageQuota'		=> array('int', 'quota'),
			'StorageUsedSpace'	=> array('int'),

			'Email'				=> array('string(255)', 'email', true, false),
			'FriendlyName'		=> array('string(255)', 'friendly_nm'),

			'IncomingMailProtocol'	=> array('int', 'mail_protocol'),
			'IncomingMailServer'	=> array('string(255)', 'mail_inc_host'),
			'IncomingMailPort'		=> array('int', 'mail_inc_port'),
			'IncomingMailLogin'		=> array('string(255)', 'mail_inc_login'),
			'IncomingMailPassword'	=> array('password', 'mail_inc_pass'),
			'IncomingMailUseSSL'	=> array('bool', 'mail_inc_ssl'),

			'PreviousMailPassword'	=> array('string'),

			'OutgoingMailServer'	=> array('string(255)', 'mail_out_host'),
			'OutgoingMailPort'		=> array('int', 'mail_out_port'),
			'OutgoingMailLogin'		=> array('string(255)', 'mail_out_login'),
			'OutgoingMailPassword'	=> array('password', 'mail_out_pass'),
			'OutgoingMailAuth'		=> array('int', 'mail_out_auth'),
			'OutgoingMailUseSSL'	=> array('bool', 'mail_out_ssl'),
			'OutgoingSendingMethod'	=> array('int'),

			'HideInGAB'			=> array('bool', 'hide_in_gab'),

			'Signature'			=> array('string', 'signature'),
			'SignatureType'		=> array('int', 'signature_type'),
			'SignatureOptions'	=> array('int', 'signature_opt'),

			'GlobalAddressBook'	=> array('int'),

			'AllowCompose'		=> array('bool'),
			'AllowReply'		=> array('bool'),
			'AllowForward'		=> array('bool'),
			'DetectSpecialFoldersWithXList' => array('bool'),

			'CustomFields'		=> array('serialize', 'custom_fields'),
			'ForceSaveOnLogin'	=> array('bool'),
			'AllowMail'			=> array('bool', 'allow_mail'),
			'IsPasswordSpecified' => array('bool', 'is_password_specified'),
		);
	}
	
	
	/**
	 * Can account login with password.
	 * 
	 * @return bool
	 */
	public function canLoginWithPassword()
	{
		$bCanLoginWithPassword = false;
		CApi::Plugin()->RunHook('api-account-can-login-with-password', array(&$bCanLoginWithPassword));
		return ($this->AllowMail || $this->IsPasswordSpecified || $bCanLoginWithPassword);
	}
}
