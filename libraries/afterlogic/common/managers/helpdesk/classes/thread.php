<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @property int $IdHelpdeskThread
 * @property string $StrHelpdeskThreadHash
 * @property int $IdTenant
 * @property int $IdOwner
 * @property bool $ItsMe
 * @property bool $IsArchived
 * @property int $Type
 * @property string $Subject
 * @property int $Created
 * @property int $Updated
 * @property int $PostCount
 * @property int $LastPostId
 * @property bool $HasAttachments
 * @property bool $IsRead
 * @property array $Owner
 *
 * @package Helpdesk
 * @subpackage Classes
 */
class CHelpdeskThread extends api_AContainer
{
	/**
	 * @var array
	 */
	public $Owner;

	public function __construct()
	{
		parent::__construct(get_class($this));

		$this->SetTrimer(array('Subject'));

		$this->Owner = null;

		$this->SetDefaults(array(
			'IdHelpdeskThread'		=> 0,
			'StrHelpdeskThreadHash'	=> trim(base_convert(md5(microtime(true).rand(1000, 9999)), 16, 32), '0'),
			'IdTenant'				=> 0,
			'IdOwner'				=> 0,
			'ItsMe'					=> false,
			'IsArchived'			=> false,
			'Type'					=> EHelpdeskThreadType::None,
			'Subject'				=> '',
			'Created'				=> time(),
			'Updated'				=> time(),
			'PostCount'				=> 0,
			'LastPostId'			=> 0,
			'HasAttachments'		=> false,
			'IsRead'				=> false
		));
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		switch (true)
		{
			case 0 < $this->IdOwner:
				throw new CApiValidationException(Errs::Validation_ObjectNotComplete, null, array(
					'{{ClassName}}' => 'CHelpdeskPost', '{{ClassField}}' => 'IdOwner'));
		}

		return true;
	}
	
	/**
	 * @return string
	 */
	public function ThreadLink()
	{
		$sPath = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?helpdesk';
		
		if (0 < $this->IdTenant)
		{
			$sHash = substr(md5($this->IdTenant.CApi::$sSalt), 0, 8);
			$sPath .= '='.$sHash;
		}

		$sPath .= '&thread='.$this->StrHelpdeskThreadHash;
		
		return $sPath;
	}

	/**
	 * @return string
	 */
	public function LoginLink()
	{
		$sPath = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?helpdesk';

		if (0 < $this->IdTenant)
		{
			$sHash = substr(md5($this->IdTenant.CApi::$sSalt), 0, 8);
			$sPath .= '='.$sHash;
		}

		return $sPath;
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
			'IdHelpdeskThread'	=> array('int', 'id_helpdesk_thread', false, false),
			'StrHelpdeskThreadHash'	=> array('string', 'str_helpdesk_hash', true, false),
			'IdTenant'			=> array('int', 'id_tenant', true, false),
			'IdOwner'			=> array('int', 'id_owner', true, false),
			'ItsMe'				=> array('bool'),
			'IsArchived'		=> array('bool', 'archived'),
			'Type'				=> array('int', 'type'),
			'Subject'			=> array('string', 'subject'),
			'Created'			=> array('datetime', 'created', true, false),
			'Updated'			=> array('datetime', 'updated'),
			'PostCount'			=> array('int', 'post_count'),
			'LastPostId'		=> array('int', 'last_post_id'),
			'HasAttachments'	=> array('bool', 'has_attachments'),
			'IsRead'			=> array('bool')
		);
	}
}
