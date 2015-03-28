<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $Id
 * @property int $IdTenant
 * @property bool $SocialAllow
 * @property string $SocialName
 * @property string $SocialId
 * @property string $SocialSecret
 * @property string $SocialApiKey
 * @property string $SocialApiKey
 * @property string $SocialScopes
 *
 * @package Tenants
 * @subpackage Classes
 */
class CTenantSocials extends api_AContainer
{
	public function __construct()
	{
		parent::__construct(get_class($this), 'Id');

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults(array(
			'Id'							=> 0,
			'IdTenant'						=> 0,
			'SocialAllow'					=> false,
			'SocialName'					=> '',
			'SocialId'						=> '',
			'SocialSecret'					=> '',
			'SocialApiKey'					=> null,
			'SocialScopes'					=> ''
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
			'Id'			=> array('int', 'id', false, false),
			'IdTenant'		=> array('int', 'id_tenant', true, false),
			'SocialAllow'	=> array('bool', 'social_allow'),
			'SocialName'	=> array('string', 'social_name'),
			'SocialId'		=> array('string', 'social_id'),
			'SocialSecret'	=> array('string', 'social_secret'),
			'SocialApiKey'	=> array('string', 'social_api_key'),
			'SocialScopes'	=> array('string', 'social_scopes'),
		);
	}
	
	/**
	 * @param array $aSocial
	 * 
	 * @return CTenantSocials
	 */
	public static function InitFromSettings($aSocial)
	{
		$oSocial = new CTenantSocials();
		
		$oSocial->IdTenant = 0;
		$oSocial->SocialAllow = ('on' === strtolower($aSocial['Allow']) || '1' === (string) $aSocial['Allow']);
		$oSocial->SocialName = $aSocial['Name'];
		$oSocial->SocialId = $aSocial['Id'];
		$oSocial->SocialSecret = $aSocial['Secret'];
		$oSocial->SocialApiKey = isset($aSocial['ApiKey']) ? $aSocial['ApiKey'] : null;
		$oSocial->SocialScopes = isset($aSocial['Scopes']) ? $aSocial['Scopes'] : '';
		
		return $oSocial;
	}
	
	/**
	 * @return array
	 */
	public function InitForSettings()
	{
		$aResult = array(
			'Allow'		=> $this->SocialAllow ? 'On' : 'Off',
			'Name'		=> $this->SocialName,
			'Id'		=> $this->SocialId,
			'Secret'	=> $this->SocialSecret,
			'Scopes'	=> $this->SocialScopes
		);
		if (isset($this->SocialApiKey))
		{
			$aResult['ApiKey'] = $this->SocialApiKey;
		}
		
		return $aResult;
	}

	public function ToArray()
	{
		return array(
			'@Object'	=> 'Object/CTenantSocials',
			'Id'		=> $this->SocialId,
			'Name'		=>	$this->SocialName,
			'Allow'		=> $this->SocialAllow,
			'Secret'	=> $this->SocialSecret,
			'ApiKey'	=> $this->SocialApiKey,
			'Scopes'	=> $this->SocialScopes
		);
	}
	
	/**
	 * @param string $sScope
	 * @return bool
	 */
	public function IssetScope($sScope)
	{
		return (false !== strpos(strtolower($this->SocialScopes), strtolower($sScope)));
	}		
	
}
