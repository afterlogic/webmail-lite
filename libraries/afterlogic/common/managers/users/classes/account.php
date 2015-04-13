<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdAccount
 * @property int $IdUser
 * @property int $IdDomain
 * @property int $IdTenant
 * @property bool $IsInternal
 * @property bool $IsDisabled
 * @property bool $IsDefaultAccount
 * @property bool $IsMailingList
 * @property int $StorageQuota
 * @property int $StorageUsedSpace
 * @property string $Email
 * @property string $FriendlyName
 * @property int $IncomingMailProtocol
 * @property string $IncomingMailServer
 * @property int $IncomingMailPort
 * @property string $IncomingMailLogin
 * @property string $IncomingMailPassword
 * @property bool $IncomingMailUseSSL
 * @property string $PreviousMailPassword
 * @property string $OutgoingMailServer
 * @property int $OutgoingMailPort
 * @property string $OutgoingMailLogin
 * @property string $OutgoingMailPassword
 * @property int $OutgoingMailAuth
 * @property bool $OutgoingMailUseSSL
 * @property int $OutgoingSendingMethod
 * @property bool $HideInGAB
 * @property string $Signature
 * @property int $SignatureType
 * @property int $SignatureOptions
 * @property int $GlobalAddressBook
 * @property bool $AllowCompose
 * @property bool $AllowReply
 * @property bool $AllowForward
 * @property bool $DetectSpecialFoldersWithXList
 * @property mixed $CustomFields
 * @property bool $ForceSaveOnLogin
 * @property bool $SocialEmail
 *
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
	 * @var CUser
	 */
	public $User;

	/**
	 * @var CDomain
	 */
	public $Domain;

	/**
	 * @var array
	 */
	protected $aExtension;

	/**
	 * @param CDomain $oDomain
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
			'SocialEmail'		=> ''
		));

		CApi::Plugin()->RunHook('api-account-construct', array(&$this));
	}

	/**
	 * @param CDomain $oDomain
	 * @return CAccount
	 */
	public static function NewInstance($oDomain)
	{
		return new CAccount($oDomain);
	}

	/**
	 * @return int
	 */
	public function GetDefaultTimeOffset()
	{
		return api_Utils::GetTimeOffset($this->User->DefaultTimeZone, $this->User->ClientTimeZone);
	}

	/**
	 * @return string
	 */
	public function GetDefaultStrTimeZone()
	{
		return api_Utils::GetStrTimeZone($this->User->DefaultTimeZone, $this->User->ClientTimeZone);
	}

	/**
	 * @param string $sExtensionName
	 */
	public function EnableExtension($sExtensionName)
	{
		$this->aExtension[] = $sExtensionName;
		$this->aExtension = array_unique($this->aExtension);
	}

	/**
	 * @param string $sExtensionName
	 */
	public function DisableExtension($sExtensionName)
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
	 * @return bool
	 */
	public function IsEnabledExtension($sExtensionName)
	{
		return in_array($sExtensionName, $this->aExtension);
	}

	/**
	 * @return array
	 */
	public function GetExtensions()
	{
		return $this->aExtension;
	}

	/**
	 * @param string $sLogin
	 * @param string $sAtChar = '@'
	 */
	public function InitLoginAndEmail($sLogin, $sAtChar = '@')
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
	 * @param stdClass $oRow
	 */
	public function InitByDbRow($oRow)
	{
		parent::InitByDbRow($oRow);

		if (!$this->Domain->IsDefaultDomain)
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
				$this->StorageUsedSpace = $oApiUsersManager->GetAccountUsedSpaceInKBytesByEmail($this->Email);
				$this->FlushObsolete('StorageUsedSpace');
			}
		}		
	}

	public function InitBeforeChange()
	{
		parent::InitBeforeChange();

		$bObsolete = null !== $this->GetObsoleteValue('StorageQuota');

		$this->StorageQuota = 0 === $this->StorageQuota ?
			(int) CApi::GetConf('labs.unlim-quota-limit-size-in-kb', 104857600) : $this->StorageQuota;

		if (!$bObsolete)
		{
			$this->FlushObsolete('StorageQuota');
		}
	}

	/**
	 * @return string
	 */
	public function GetFriendlyEmail()
	{
		return (0 < strlen($this->FriendlyName))
			? '"'.$this->FriendlyName.'" <'.$this->Email.'>' : $this->Email;
	}

	/**
	 * @return bool
	 */
	public function IsGmailAccount()
	{
		return 'imap.gmail.com' === strtolower($this->IncomingMailServer) || 'googlemail.com' === strtolower($this->IncomingMailServer);
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
	 * @return array
	 */
	public function GetMap()
	{
		return self::GetStaticMap();
	}

	/**
	 * @return int
	 */
	public function RealQuotaSize()
	{
		return 0 === $this->StorageQuota ? (int) CApi::GetConf('labs.unlim-quota-limit-size-in-kb', 104857600) : $this->StorageQuota;
	}

	/**
	 * @return array
	 */
	public static function GetStaticMap()
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
			'SocialEmail'		=> array('string', 'social_email'),
		);
	}
}
