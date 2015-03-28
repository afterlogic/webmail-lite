<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property mixed $IdGroup
 * @property string $IdGroupStr
 * @property int $IdUser
 * @property string $Name
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
 * @property array $Events
 * 
 * @package Contactsmain
 * @subpackage Classes
 */
class CGroup extends api_AContainer
{
	const STR_PREFIX = '5765624D61696C50726F';

	public $Events = array();
	
	public function __construct()
	{
		parent::__construct(get_class($this), 'IdGroup');

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults(array(
			'IdGroup'		=> '',
			'IdGroupStr'	=> '',
			'IdUser'		=> 0,

			'Name'			=> '',
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
			'Web'		=> '',
			'Events'	=> array()
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
