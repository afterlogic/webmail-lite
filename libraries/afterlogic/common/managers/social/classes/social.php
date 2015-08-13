<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $Id
 * @property int $IdAccount
 * @property string $IdSocial
 * @property int $Type
 * @property string $TypeStr
 * @property string $Name
 * @property string $Email
 * @property string $AccessToken
 * @property string $RefreshToken
 * @property string $Scopes
 * @property bool $Disabled
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
			'TypeStr'		=> '',
			'Name'			=> '',
			'Email'			=> '',
			'AccessToken'	=> '',
			'RefreshToken'	=> '',
			'Scopes'		=> '',
			'Disabled'		=> false
		));
	}

	/**
	 * @throws CApiValidationException
	 *
	 * @return bool
	 */
	public function validate()
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
	public function getMap()
	{
		return self::getStaticMap();
	}

	/**
	 * @return array
	 */
	public static function getStaticMap()
	{
		return array(
			'Id'			=> array('int', 'id', false, false),
			'IdAccount'		=> array('int', 'id_acct', true, false),
			'IdSocial'		=> array('string(255)', 'id_social', true, false),
			'Type'			=> array('int', 'type', true, false),
			'TypeStr'		=> array('string(255)', 'type_str', true, false),
			'Name'			=> array('string(255)', 'name'),
			'Email'			=> array('string(255)', 'email'),
			'AccessToken'	=> array('text', 'access_token'),
			'RefreshToken'	=> array('string(255)', 'refresh_token'),
			'Scopes'		=> array('string', 'scopes'),
			'Disabled'		=> array('bool', 'disabled'),
			
		);
	}
	
	/**
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'@Object'	=> 'Object/CSocial',
			'Id'		=> $this->Id,
			'IdAccount'	=> $this->IdAccount,
			'IdSocial'	=> $this->IdSocial,
			'Type'		=> $this->TypeStr,
			//'TypeStr'	=> $this->TypeStr,
			'Name'		=> $this->Name,
			'Email'		=> $this->Email,
			'Scopes'	=> $this->getScopesAsArray(),
			'Disabled'	=> $this->Disabled,
			'Connected'	=> !empty($this->IdSocial)
		);
	}
	
	public function getScopesAsArray()
	{
		$aResult = array();
		if (!$this->Disabled)
		{
			$aResult = array_map(function($sValue) {
					return strtolower($sValue);
				}, explode(' ', $this->Scopes)	
			);	
		}
		
		return $aResult;
	}
	
	/**
	 * @param string $sScope
	 *
	 * @return bool
	 */
	public function issetScope($sScope)
	{
		return /*'' === $this->Scopes || */false !== strpos(strtolower($this->Scopes), strtolower($sScope));
	}	
	
	/**
	 * @param string $sScope
	 */
	public function setScope($sScope)
	{
		$aScopes = $this->getScopesAsArray();
		if (!array_search($sScope, array_unique($aScopes)))
		{
			$aScopes[] = $sScope;
			$this->Scopes = implode(' ', array_unique($aScopes));
		}
	}	
	
	/**
	 * @param array $aScopes
	 */
	public function setScopes($aScopes)
	{
		$this->Scopes = implode(' ', array_unique(array_merge($aScopes, $this->getScopesAsArray())));
	}	

	/**
	 * @param string $sScope
	 */
	public function unsetScope($sScope)
	{
		$aScopes = array_map(function($sValue) {
				return strtolower($sValue);
			}, explode(' ', $this->Scopes)	
		);
		$mResult = array_search($sScope, $aScopes);
		if ($mResult !== false)
		{
			unset($aScopes[$mResult]);
			$this->Scopes = implode(' ', $aScopes);
		}
	}		
}
