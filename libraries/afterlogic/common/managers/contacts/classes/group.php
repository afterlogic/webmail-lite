<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @property mixed $IdGroup
 * @property string $IdGroupStr
 * @property int $IdUser
 * @property string $Name
 * @property array $ContactsIds
 * @property array $DeletedContactsIds
 * @property bool $IsOrganization
 * @property string $Email
 * @property string $Company
 * @property string $Street
 * @property string $City
 * @property string $State
 * @property string $Zip
 * @property string $Country
 * @property string $Phone
 * @property string $Fax
 * @property string $Web
 *
 * @package Contacts
 * @subpackage Classes
 */
class CGroup extends api_AContainer
{
	const STR_PREFIX = '5765624D61696C50726F';

	public function __construct()
	{
		parent::__construct(get_class($this), 'IdGroup');

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults(array(
			'IdGroup'		=> '',
			'IdGroupStr'	=> '',
			'IdUser'		=> 0,

			'Name'			=> '',
			'ContactsIds'			=> array(),
			'DeletedContactsIds'	=> array(),

			'IsOrganization'	=> false,

			'Email'		=> '',
			'Company'	=> '',
			'Street'	=> '',
			'City'		=> '',
			'State'		=> '',
			'Zip'		=> '',
			'Country'	=> '',
			'Phone'		=> '',
			'Fax'		=> '',
			'Web'		=> ''
		));

		CApi::Plugin()->RunHook('api-group-construct', array(&$this));
	}

	/**
	 * @return string
	 */
	public function GenerateStrId()
	{
		return self::STR_PREFIX.$this->IdGroup;
	}

	/**
	 * @return bool
	 */
	public function InitBeforeChange()
	{
		parent::InitBeforeChange();

		if (0 === strlen($this->IdGroupStr))
		{
			$this->IdGroupStr = $this->GenerateStrId();
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		switch (true)
		{
			case api_Validate::IsEmpty($this->Name):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CGroup', '{{ClassField}}' => 'Name'));
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
			'IdGroup'		=> array('string', 'id_group', false, false),
			'IdGroupStr'	=> array('string(100)', 'group_str_id', false),
			'IdUser'		=> array('int', 'id_user'),

			'Name'			=> array('string(255)', 'group_nm'),

			'ContactsIds'			=> array('array'),
			'DeletedContactsIds'	=> array('array'),

			'IsOrganization'	=> array('bool', 'organization'),

			'Email'		=> array('string(255)', 'email'),
			'Company'	=> array('string(200)', 'company'),
			'Street'	=> array('string(255)', 'street'),
			'City'		=> array('string(200)', 'city'),
			'State'		=> array('string(200)', 'state'),
			'Zip'		=> array('string(10)', 'zip'),
			'Country'	=> array('string(200)', 'country'),
			'Phone'		=> array('string(50)', 'phone'),
			'Fax'		=> array('string(50)', 'fax'),
			'Web'		=> array('string(255)', 'web')
		);
	}
}
