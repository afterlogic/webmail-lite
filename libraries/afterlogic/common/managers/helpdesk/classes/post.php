<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdHelpdeskPost
 * @property int $IdHelpdeskThread
 * @property int $IdTenant
 * @property int $IdOwner
 * @property array $Owner
 * @property array $Attachments
 * @property int $Type
 * @property int $SystemType
 * @property int $Created
 * @property bool $IsThreadOwner
 * @property bool $ItsMe
 * @property string $Text
 *
 * @package Helpdesk
 * @subpackage Classes
 */
class CHelpdeskPost extends api_AContainer
{
	/**
	 * @var array
	 */
	public $Owner;
	
	/**
	 * @var array
	 */
	public $Attachments;

	public function __construct()
	{
		parent::__construct(get_class($this));

		$this->SetTrimer(array('Text'));

		$this->Owner = null;
		$this->Attachments = null;

		$this->SetDefaults(array(
			'IdHelpdeskPost'		=> 0,
			'IdHelpdeskThread'		=> 0,
			'IdTenant'				=> 0,
			'IdOwner'				=> 0,
			'Type'					=> EHelpdeskPostType::Normal,
			'SystemType'			=> EHelpdeskPostSystemType::None,
			'Created'				=> time(),
			'IsThreadOwner'			=> true,
			'ItsMe'					=> false,
			'Text'					=> ''
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
			'IdHelpdeskPost'	=> array('int', 'id_helpdesk_post', false, false),
			'IdHelpdeskThread'	=> array('int', 'id_helpdesk_thread', true, false),
			'IdTenant'			=> array('int', 'id_tenant', true, false),
			'IdOwner'			=> array('int', 'id_owner', true, false),
			'Type'				=> array('int', 'type'),
			'SystemType'		=> array('int', 'system_type'),
			'IsThreadOwner'		=> array('bool'),
			'ItsMe'				=> array('bool'),
			'Text'				=> array('string', 'text'),
			'Created'			=> array('datetime', 'created', true, false)
		);
	}
}
