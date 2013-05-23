<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 */

/**
 * @property int $IdAccount
 * @property int $IdUser
 * @property int $IdDomain
 * @property int $IdRealm
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
 * @property int $DefaultOrder
 * @property bool $GetMailAtLogin
 * @property int $MailMode
 * @property int $MailsOnServerDays
 * @property string $Signature
 * @property int $SignatureType
 * @property int $SignatureOptions
 * @property int $GlobalAddressBook
 * @property string $Delimiter
 * @property int $MailboxSize
 * @property string $Namespace
 * @property bool $AllowCompose
 * @property bool $AllowReply
 * @property bool $AllowForward
 * @property bool $DetectSpecialFoldersWithXList
 * @property bool $UserSpecifiedSystemFolders
 * @property mixed $CustomFields
 *
 * @package Users
 * @subpackage Classes
 */
class CAccount extends api_AContainer
{
	const ChangePasswordExtension = 'AllowChangePasswordExtension';
	const AutoresponderExtension = 'AllowAutoresponderExtension';
	const SpamFolderExtension = 'AllowSpamFolderExtension';
	const SpamLearningExtension = 'AllowSpamLearningExtension';
	const DisableAccountDeletion = 'DisableAccountDeletion';
	const DisableManageFolders = 'DisableManageFolders';
	const SieveFiltersExtension = 'AllowSieveFiltersExtension';
	const ForwardExtension = 'AllowForwardExtension';
	const DisableManageSubscribe = 'DisableManageSubscribe';
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
			'PreviousMailPassword', 'OutgoingMailServer', 'OutgoingMailLogin', 'Delimiter', 'Namespace'));

		$this->SetLower(array(/*'Email', */'IncomingMailServer', /*'IncomingMailLogin',*/
			'OutgoingMailServer', /*'OutgoingMailLogin'*/));

		$this->SetDefaults(array(

			'IdAccount'	=> 0,
			'IdUser'	=> 0,
			'IdDomain'	=> $oDomain->IdDomain,
			'IdRealm'	=> $oDomain->IdRealm,

			'IsDefaultAccount'	=> true,
			'IsInternal'		=> $oDomain->IsInternal,
			'IsDisabled'		=> false,
			'IsMailingList'		=> false,

			'StorageQuota'		=> 0,
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

			'DefaultOrder'		=> EAccountDefaultOrder::DescDate,
			'GetMailAtLogin'	=> true,

			'MailMode'			=> EAccountMailMode::DeleteMessageWhenItsRemovedFromTrash,
			'MailsOnServerDays'	=> 7, // TODO Magic

			'Signature'			=> '',
			'SignatureType'		=> EAccountSignatureType::Html,
			'SignatureOptions'	=> EAccountSignatureOptions::DontAdd,

			'GlobalAddressBook'	=> $oDomain->GlobalAddressBook,

			'Delimiter'			=> '/',
			'MailboxSize'		=> 0,
			'Namespace'			=> '',

			'AllowCompose'		=> true,
			'AllowReply'		=> true,
			'AllowForward'		=> true,
			'DetectSpecialFoldersWithXList' => $oDomain->DetectSpecialFoldersWithXList,
			'UserSpecifiedSystemFolders' => false,

			'CustomFields'		=> ''
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
		return api_Utils::GetTimeOffset($this->User->DefaultTimeZone, $this->User->ClientTimeOffset);
	}

	/**
	 * @return int
	 */
	public function GetDefaultStrTimeZone()
	{
		return api_Utils::GetStrTimeZone($this->User->DefaultTimeZone, $this->User->ClientTimeOffset);
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
	 * @param bool $bSetAdminLogin = false
	 * @param string $sSetCustomSessionId = ''
	 *
	 * @return void
	 */
	public function FillSession($bSetAdminLogin = false, $sSetCustomSessionId = '')
	{
		CSession::$sSessionName = API_SESSION_WEBMAIL_NAME;

		if (!empty($sSetCustomSessionId))
		{
			CSession::SetId($sSetCustomSessionId);
		}

		CSession::Set(EAccountSessKey::IdAccount, $this->IdAccount);
		CSession::Set(EAccountSessKey::IdUser, $this->IdUser);
		CSession::Set(EAccountSessKey::Lang, $this->User->DefaultLanguage);
		CSession::Set(EAccountSessKey::LastLogin, $this->User->LastLogin);

		if ($bSetAdminLogin)
		{
			CSession::Set(EAccountSessKey::AdminLogin, true);
		}
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

		$sDomainName = (!$this->Domain->IsDefaultDomain) ? $this->Domain->Name : $sDomainPart;
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
			$this->Namespace = 'INBOX.';
			$this->Delimiter = '.';

			if ((int) CApi::GetConf('labs.unlim-quota-limit-size-in-kb', 104857600) <= $this->StorageQuota)
			{
				$this->StorageQuota = 0;
				$this->FlushObsolete('StorageQuota');
			}
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

	public function InitStorageUsedSpace()
	{
		if ($this->IsInternal)
		{
			$sScript = '/usr/mailsuite/scripts/du.user';
			if (@file_exists($sScript))
			{
				$sCmd = $sScript.' '.
					api_Utils::GetDomainFromEmail($this->IncomingMailLogin).' '.
					api_Utils::GetAccountNameFromEmail($this->IncomingMailLogin);

				CApi::Log('Run[du.user] cmd: '.$sCmd);
				$sResult = @trim(shell_exec($sCmd));
				if (!is_numeric($sResult))
				{
					CApi::Log('Error: "'.$sScript.'" result = '.$sResult, ELogLevel::Error);
				}
				else
				{
					CApi::Log('du.user = '.$sResult);

					$this->StorageUsedSpace = (int) $sResult;
					$this->FlushObsolete('StorageUsedSpace');
				}
			}
		}
	}

	/**
	 * @return int
	 */
	public function RealQuotaSize()
	{
		return 0 === $this->StorageQuota ? (int) CApi::GetConf('labs.unlim-quota-limit-size-in-kb', 104857600) : $this->StorageQuota;
	}

	/**
	 * @param bool $bCreate = false
	 *
	 * @return void
	 */
	public function UpdateInternalStorageQuota($bCreate = false)
	{
		if ($this->IsInternal)
		{
			$sStorageQuota = $this->GetObsoleteValue('StorageQuota');
			if (null !== $sStorageQuota || $bCreate)
			{
				$sScript = '/usr/mailsuite/scripts/';
				$sScript .= $bCreate ? 'maildirmake.sh' : 'maildirquota.sh';

				if (@file_exists($sScript))
				{
					$iStorageQuota = $this->StorageQuota;
					$iStorageQuota = 0 === $iStorageQuota ? (int) CApi::GetConf('labs.unlim-quota-limit-size-in-kb', 104857600) : $iStorageQuota;

					$sCmd = $sScript.' '.
						api_Utils::GetDomainFromEmail($this->IncomingMailLogin).' '.
						api_Utils::GetAccountNameFromEmail($this->IncomingMailLogin).' '.
						$iStorageQuota;

					CApi::Log('Run cmd: '.$sCmd);
					$sResult = @trim(shell_exec($sCmd));
					if ('1' !== $sResult)
					{
						CApi::Log('Error: "'.$sScript.'" result = '.$sResult, ELogLevel::Error);
					}
				}
				else
				{
					CApi::Log('Error: "'.$sScript.'" file doesn\'t exist', ELogLevel::Error);
				}
			}
		}
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
			'IdRealm'	=> array('int', 'id_realm', true, false),

			'IsInternal'		=> array('bool'),
			'IsDisabled'		=> array('bool', 'deleted'),
			'IsDefaultAccount'	=> array('bool', 'def_acct'),
			'IsMailingList'		=> array('bool', 'mailing_list'),

			'StorageQuota'		=> array('int', 'quota', false, false),
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

			'DefaultOrder'		=> array('int', 'def_order'),
			'GetMailAtLogin'	=> array('bool', 'getmail_at_login'),

			'MailMode'			=> array('int', 'mail_mode'),
			'MailsOnServerDays'	=> array('int', 'mails_on_server_days'),

			'Signature'			=> array('string', 'signature'),
			'SignatureType'		=> array('int', 'signature_type'),
			'SignatureOptions'	=> array('int', 'signature_opt'),

			'GlobalAddressBook'	=> array('int'),

			'Delimiter'			=> array('string(2)', 'delimiter'),
			'MailboxSize'		=> array('int', 'mailbox_size'),
			'Namespace'			=> array('string(255)', 'namespace'),

			'AllowCompose'		=> array('bool'),
			'AllowReply'		=> array('bool'),
			'AllowForward'		=> array('bool'),
			'DetectSpecialFoldersWithXList' => array('bool'),
			'UserSpecifiedSystemFolders' => array('bool'),

			'CustomFields'		=> array('serialize', 'custom_fields')
		);
	}
}
