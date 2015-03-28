<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdIdentity
 * @property int $IdUser
 * @property int $IdAccount
 * @property bool $Virtual
 * @property bool $Enabled
 * @property string $Email
 * @property string $FriendlyName
 * @property string $Signature
 * @property int $SignatureType
 * @property bool $UseSignature
 *
 * @package Users
 * @subpackage Classes
 */
class CIdentity extends api_AContainer
{
	/**
	 * @param CDomain $oDomain
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(get_class($this), 'IdIdentity');

		$this->SetTrimer(array('Email', 'FriendlyName'));

		$this->SetDefaults(array(
			'IdIdentity'	=> 0,
			'IdUser'		=> 0,
			'IdAccount'		=> 0,
			'Virtual'		=> false,
			'Default'		=> false,
			'Enabled'		=> true,
			'Email'			=> '',
			'FriendlyName'	=> '',
			'Signature'		=> '',
			'SignatureType'	=> EAccountSignatureType::Html,
			'UseSignature'	=> false
		));

		CApi::Plugin()->RunHook('api-identity-construct', array(&$this));
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		switch (true)
		{
			case 0 === $this->IdUser:
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CIdentity', '{{ClassField}}' => 'IdUser'));

			case 0 === $this->IdAccount:
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CIdentity', '{{ClassField}}' => 'IdAccount'));

			case api_Validate::IsEmpty($this->Email):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CIdentity', '{{ClassField}}' => 'Email'));
		}

		return true;
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
			'IdIdentity'	=> array('int', 'id_identity', false, false),
			'IdUser'		=> array('int', 'id_user'),
			'IdAccount'		=> array('int', 'id_acct'),
			'Virtual'		=> array('bool'),
			'Default'		=> array('bool', 'def_identity'),
			'Enabled'		=> array('bool', 'enabled'),

			'Email'			=> array('string(255)', 'email'),
			'FriendlyName'	=> array('string(255)', 'friendly_nm'),
			'Signature'		=> array('string', 'signature'),
			'SignatureType'	=> array('int', 'signature_type'),
			'UseSignature'	=> array('bool', 'use_signature'),
		);
	}
}
