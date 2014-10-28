<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $Id
 * @property int $IdAccount
 * @property string $IdSocial
 * @property int $Type
 * @property string $Name
 * @property string $AccessToken
 * @property string $RefreshToken
 *
 * @package Social
 * @subpackage Classes
 */
class CSocial extends api_AContainer
{
	public function __construct()
	{
		parent::__construct(get_class($this), 'Id');

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults(array(
			'Id'			=> 0,
			'IdAccount'		=> 0,
			'IdSocial'		=> '',
			'Type'			=> \ESocialType::Unknown,
			'Name'			=> '',
			'AccessToken'	=> '',
			'RefreshToken'	=> ''
		));
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		switch (true)
		{
			case api_Validate::IsEmpty($this->IdSocial):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CSocial', '{{ClassField}}' => 'IdSocial'));
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
			'Id'			=> array('int', 'id', false, false),
			'IdAccount'		=> array('int', 'id_acct', true, false),
			'IdSocial'		=> array('string(255)', 'id_social', true, false),
			'Type'			=> array('int', 'type', true, false),
			'Name'			=> array('string(255)', 'name'),
			'AccessToken'	=> array('text', 'access_token'),
			'RefreshToken'	=> array('string(255)', 'refresh_token')
		);
	}
	
	/**
	 * @return array
	 */
	public function ToArray()
	{
		return array(
			'@Object'	=> 'Object/CSocial',
			'Id'		=> $this->Id,
			'IdAccount'	=> $this->IdAccount,
			'IdSocial'	=> $this->IdSocial,
			'Type'		=> $this->Type,
			'Name'		=> $this->Name
		);
	}
	
}
