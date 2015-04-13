<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $Id
 * @property int $AccountId
 * @property string $DataValue
 * @property int $DataType
 * @property string $AuthType
 *
 * @package twofactorauth
 * @subpackage Classes
 */
class CTwofactorauth extends api_AContainer
{
    public function __construct()
	{
		parent::__construct(get_class($this), 'Id');

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults(array(
			'Id'			=> 0,
			'AccountId'		=> 0,
			'DataValue'		=> ETwofaType::AUTH_TYPE_AUTHY,
			'DataType'		=> ETwofaType::DATA_TYPE_AUTHY_ID
		));
	}

    /**
     * @return bool
     * @throws CApiValidationException
     */
	public function Validate()
	{
		switch (true)
		{
			case api_Validate::IsEmpty($this->AccountId):
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'C2fa', '{{ClassField}}' => 'IdAccount'));
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
			'AccountId'		=> array('int', 'account_id', true, false),
			'DataValue'		=> array('string(255)', 'data_value'),
			'DataType'		=> array('int', 'data_type', true, false),
			'AuthType'		=> array('string(255)', 'auth_type', true, false),
		);
	}
	
	/**
	 * @return array
	 */
	public function ToArray()
	{
		return array(
			'@Object'	=> 'Object/C2fa',
			'Id'		=> $this->Id,
			'AccountId'	=> $this->AccountId,
			'DataValue'	=> $this->DataValue,
			'DataType'	=> $this->DataType,
            'AuthType'	=> $this->AuthType,
		);
	}

}
