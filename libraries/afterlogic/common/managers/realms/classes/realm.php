<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @property int $IdTenant
 * @property int $IdChannel
 * @property bool $IsDisabled
 * @property bool $IsEnableAdminPanelLogin
 * @property string $Login
 * @property string $Email
 * @property string $PasswordHash
 * @property string $Description
 * @property int $QuotaInMB
 * @property int $UsedSpaceInMB
 * @property int $AllocatedSpaceInMB
 * @property int $UserCountLimit
 * @property int $DomainCountLimit
 *
 * @package Tenants
 * @subpackage Classes
 */
class CTenant extends api_AContainer
{
	public function __construct($sLogin = '', $Description = '')
	{
		parent::__construct(get_class($this), 'IdTenant');

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults(array(
			'IdTenant'		=> 0,
			'IdChannel'		=> 0,
			'IsDisabled'	=> false,
			'Login'			=> $sLogin,
			'Email'			=> '',
			'PasswordHash'	=> '',
			'Description'	=> $Description,
			'IsEnableAdminPanelLogin'	=> false,
			'QuotaInMB'		=> 0,
			'UsedSpaceInMB'	=> 0,
			'AllocatedSpaceInMB'	=> 0,
			'UserCountLimit'		=> 0,
			'DomainCountLimit'		=> 0
		));

		$this->SetLower(array('Login', 'Email'));
	}

	/**
	 * @param string $sPassword
	 *
	 * @return string
	 */
	public static function HashPassword($sPassword)
	{
		return empty($sPassword) ? '' : md5('Awm'.md5($sPassword.'Awm'));
	}

	/**
	 * @param string $sPassword
	 *
	 * @return bool
	 */
	public function ValidatePassword($sPassword)
	{
		return self::HashPassword($sPassword) === $this->PasswordHash;
	}

	/**
	 * @param string $sPassword
	 */
	public function SetPassword($sPassword)
	{
		$this->PasswordHash = self::HashPassword($sPassword);
	}

	public function GetUserCount()
	{
		$oUsersApi = CApi::Manager('users');
		return $oUsersApi->GetUserCountByTenantId($this->IdTenant);
	}

	public function GetDomainCount()
	{
		$oDomainsApi = CApi::Manager('domains');
		return $oDomainsApi->GetDomainCount('', $this->IdTenant);
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		switch (true)
		{
			case !api_Validate::IsValidTenantLogin($this->Login):
				throw new CApiValidationException(Errs::Validation_InvalidTenantName);
			case api_Validate::IsEmpty($this->Login):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CTenant', '{{ClassField}}' => 'Login'));
			case !api_Validate::IsEmpty($this->Email) && !preg_match('/^[^@]+@[^@]+$/', $this->Email):
				throw new CApiValidationException(Errs::Validation_InvalidEmail, null, array(
					'{{ClassName}}' => 'CTenant', '{{ClassField}}' => 'Email'));
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
			'IdTenant'	=> array('int', 'id_tenant', false),
			'IdChannel'	=> array('int', 'id_channel', true, false),
			'IsDisabled'	=> array('bool', 'disabled'),
			'IsEnableAdminPanelLogin' => array('bool', 'login_enabled'),
			'Login'			=> array('string(255)', 'login', true, false),
			'Email'			=> array('string(255)', 'email'),
			'PasswordHash'	=> array('string(100)', 'password'),
			'Description'	=> array('string(255)', 'description'),
			'UsedSpaceInMB'	=> array('int'),
			'AllocatedSpaceInMB'	=> array('int'),
			'QuotaInMB'				=> array('int', 'quota'),
			'UserCountLimit'		=> array('int', 'user_count_limit'),
			'DomainCountLimit'		=> array('int', 'domain_count_limit')
		);
	}
}
