<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdIdentity Identifier of identity.
 * @property int $IdUser Identifier of user wich contains the identity.
 * @property int $IdAccount Identifier of account wich contains the identity.
 * @property bool $Virtual If **true** the identity is not present in the database.
 * @property bool $Enabled If **true** the identity is enabled for using.
 * @property string $Email Email of identity.
 * @property string $FriendlyName Display name of identity.
 * @property string $Signature Signature of identity.
 * @property int $SignatureType Deprecated.
 * @property bool $UseSignature If **true** and this identity is used for message sending the identity signature will be attached to message body.
 *
 * @package Users
 * @subpackage Classes
 */
class CIdentity extends api_AContainer
{
	/**
	 * Creates a new instance of the object.
	 * 
	 * @param CDomain $oDomain CDomain object for the identity.
	 * 
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
	 * Checks if the identity has only valid data.
	 * 
	 * @return bool
	 */
	public function isValid()
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
	 * If display name is non-empty, returns email address with display name attached; otherwise, just the email.
	 * 
	 * @return string
	 */
	public function getFriendlyEmail()
	{
		return (0 < strlen($this->FriendlyName))
			? '"'.$this->FriendlyName.'" <'.$this->Email.'>' : $this->Email;
	}

	/**
	 * Obtains static map of identity fields. Function with the same name is used for other objects in a unified container **api_AContainer**.
	 * 
	 * @return array
	 */
	public function getMap()
	{
		return self::getStaticMap();
	}

	/**
	 * Obtains static map of identity fields.
	 * 
	 * @return array
	 */
	public static function getStaticMap()
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
