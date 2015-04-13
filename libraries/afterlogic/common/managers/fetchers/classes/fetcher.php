<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdFetcher
 * @property int $IdAccount
 * @property int $IdUser
 * @property int $IdDomain
 * @property int $IdTenant
 * @property bool $IsEnabled
 * @property bool $IsLocked
 * @property int $CheckInterval
 * @property int $CheckLastTime
 * @property string $Name
 * @property string $Email
 * @property string $Signature
 * @property int $SignatureOptions
 * @property bool $LeaveMessagesOnServer
 * @property string $IncomingMailServer
 * @property int $IncomingMailPort
 * @property string $IncomingMailLogin
 * @property string $IncomingMailPassword
 * @property int $IncomingMailSecurity
 * @property bool $IsOutgoingEnabled
 * @property string $OutgoingMailServer
 * @property int $OutgoingMailPort
 * @property bool $OutgoingMailAuth
 * @property int $OutgoingMailSecurity
 * @property string $Folder
 *
 * @package Fetchers
 * @subpackage Classes
 */
class CFetcher extends api_AContainer
{
	/**
	 * @param CAccount $oAccount
	 */
	public function __construct(CAccount $oAccount)
	{
		parent::__construct(get_class($this));

		$this->SetTrimer(array('Name', 'Signature', 'IncomingMailServer', 'IncomingMailLogin', 'IncomingMailPassword',
			'OutgoingMailServer'));

		$this->SetLower(array('IncomingMailServer', 'OutgoingMailServer'));

		$this->SetDefaults(array(
			'IdFetcher'		=> 0,
			'IdAccount'		=> $oAccount->IdAccount,
			'IdUser'		=> $oAccount->IdUser,
			'IdDomain'		=> $oAccount->IdDomain,
			'IdTenant'		=> $oAccount->IdTenant,
			'IsEnabled'		=> true,
			'IsLocked'		=> false,
			'CheckInterval'	=> 0,
			'CheckLastTime'	=> 0,
			'Name'			=> '',
			'Email'			=> '',
			'Signature'		=> '',
			'SignatureOptions'		=> EAccountSignatureOptions::DontAdd,
			'LeaveMessagesOnServer'	=> true,
			'IncomingMailServer'	=> '',
			'IncomingMailPort'		=> 110,
			'IncomingMailLogin'		=> '',
			'IncomingMailPassword'	=> '',
			'IncomingMailSecurity'	=> \MailSo\Net\Enumerations\ConnectionSecurityType::NONE,
			'IsOutgoingEnabled'		=> false,
			'OutgoingMailServer'	=> '',
			'OutgoingMailPort'		=> 25,
			'OutgoingMailAuth'		=> true,
			'OutgoingMailSecurity'	=>  \MailSo\Net\Enumerations\ConnectionSecurityType::NONE,
			'Folder'			=> 'INBOX'
		));
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
			'IdFetcher'		=> array('int', 'id_fetcher', false, false),
			'IdAccount'		=> array('int', 'id_acct', true, false),
			'IdUser'		=> array('int', 'id_user', true, false),
			'IdDomain'		=> array('int', 'id_domain', true, false),
			'IdTenant'		=> array('int', 'id_tenant', true, false),

			'IsEnabled'		=> array('bool', 'enabled'),
			'IsLocked'		=> array('bool', 'locked', false, false),
			'CheckInterval'	=> array('int', 'mail_check_interval'),
			'CheckLastTime'	=> array('int', 'mail_check_lasttime', false, false),
			
			'LeaveMessagesOnServer' => array('bool', 'leave_messages'),
			
			'Name'			=> array('string', 'frienly_name'),
			'Email'			=> array('string', 'email'),
			'Signature'		=> array('string', 'signature'),
			'SignatureOptions'		=> array('int', 'signature_opt'),

			'IncomingMailServer'	=> array('string', 'inc_host'),
			'IncomingMailPort'		=> array('int', 'inc_port'),
			'IncomingMailLogin'		=> array('string', 'inc_login'),
			'IncomingMailPassword'	=> array('string', 'inc_password'),
			'IncomingMailSecurity'	=> array('int', 'inc_security'),

			'IsOutgoingEnabled'		=> array('bool', 'out_enabled'),

			'OutgoingMailServer'	=> array('string', 'out_host'),
			'OutgoingMailPort'		=> array('int', 'out_port'),
			'OutgoingMailAuth'		=> array('bool', 'out_auth'),
			'OutgoingMailSecurity'	=> array('int', 'out_security'),

			'Folder'				=> array('string', 'dest_folder')
		);
	}
}
