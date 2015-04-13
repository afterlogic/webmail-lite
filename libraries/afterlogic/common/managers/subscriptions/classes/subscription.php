<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdSubscription
 * @property int $IdTenant
 * @property string $Name
 * @property string $Description
 * @property string $Capa
 * @property int $Limit
 *
 * @package Subscription
 * @subpackage Classes
 */
class CSubscription extends api_AContainer
{
	public function __construct()
	{
		parent::__construct(get_class($this), 'IdSubscription');

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults(array(
			'IdSubscription'	=> 0,
			'IdTenant'			=> 0,
			'Name'				=> '',
			'Description'		=> '',
			'Capa'				=> '',
			'Limit'				=> 0
		));
	}

	/**
	 * @param CTenant $oTenant
	 * @return CSubscription
	 */
	public static function NewInstance($oTenant)
	{
		return new self($oTenant);
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
			'IdSubscription'	=> array('int', 'id_subscription', false, false),
			'IdTenant'			=> array('int', 'id_tenant', true, false),
			'Name'				=> array('string', 'name'),
			'Description'		=> array('string', 'description'),
			'Capa'				=> array('string', 'capa'),
			'Limit'				=> array('int', 'limit')
		);
	}
}
