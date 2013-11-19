<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @property int $IdHelpdeskAttachment
 * @property int $IdHelpdeskPost
 * @property int $IdHelpdeskThread
 * @property int $IdTenant
 * @property int $IdOwner
 * @property int $Created
 * @property int $SizeInBytes
 * @property string $FileName
 * @property string $Hash
 *
 * @package Helpdesk
 * @subpackage Classes
 */
class CHelpdeskAttachment extends api_AContainer
{
	public function __construct()
	{
		parent::__construct(get_class($this));

		$this->SetDefaults(array(
			'IdHelpdeskAttachment'	=> 0,
			'IdHelpdeskPost'		=> 0,
			'IdHelpdeskThread'		=> 0,
			'IdTenant'				=> 0,
			'IdOwner'				=> 0,
			'Created'				=> time(),
			'SizeInBytes'			=> 0,
			'FileName'				=> '',
			'Hash'					=> ''
		));
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		switch (true)
		{
			case 0 >= $this->IdOwner:
				throw new CApiValidationException(Errs::Validation_ObjectNotComplete, null, array(
					'{{ClassName}}' => 'CHelpdeskPost', '{{ClassField}}' => 'IdOwner'));
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
			'IdHelpdeskAttachment'	=> array('int', 'id_helpdesk_attachment', false, false),
			'IdHelpdeskPost'		=> array('int', 'id_helpdesk_post', true, false),
			'IdHelpdeskThread'		=> array('int', 'id_helpdesk_thread', true, false),
			'IdTenant'				=> array('int', 'id_tenant', true, false),
			'IdOwner'				=> array('int', 'id_owner', true, false),
			'Created'				=> array('datetime', 'created', true, false),
			'SizeInBytes'			=> array('int', 'size_in_bytes'),
			'FileName'				=> array('string', 'file_name'),
			'Hash'					=> array('string', 'hash')
		);
	}
}
