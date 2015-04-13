<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Api
 */
abstract class AApiTwoFactorAuthPlugin extends AApiPlugin
{
	/**
	 * @var \CApiTwofactorauthManager
	 */
	protected $oApiTwofactorauth;
	
	/**
	 * @param string $sVersion
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct($sVersion, CApiPluginManager $oPluginManager)
	{
		parent::__construct($sVersion, $oPluginManager);
		$this->oApiTwofactorauth = null;

        $this->addHook('api-integrator-set-account-as-logged-in', 'SetAccountIsLoggedIn');
	}
	
	protected function GetTwofactorauthManager()
	{
		if (null === $this->oApiTwofactorauth)
		{
			$this->oApiTwofactorauth = \CApi::Manager('twofactorauth');
		}
		
		return $this->oApiTwofactorauth;
	}
	
    /**
     * Create new secret.
     * 16 characters, randomly chosen from the allowed base32 characters.
     *
     * @param CAccount $oAccount
     * @param int $iDataType
     * @param $sDataValue
     * @param bool $bAllowUpdate
     * @return string
     */
    public function CreateDataValue($oAccount = null, $iDataType = null, $sDataValue, $bAllowUpdate = true)
    {
		return '';
    }

    /**
     * Remove secret
     *
     * @param CAccount $oAccount
     * @return bool
     */
    public function RemoveDataValue($oAccount = null)
    {
        return false;
    }
	
    /**
     * Calculate the code, with given secret and point in time
     *
     * @param CAccount $oAccount
     * @return string
     */
    public function GetCode($oAccount)
    {
		return '';
	}	
	
    /**
     * Get QR-Code URL for image, from google charts
     *
     * @param string $sName
     * @param string $sDataValue
     * @return string
     */
    public function GetQRCode($sName, $sDataValue)
    {
		return '';
	}	
	
    /**
     * Check if the code is correct.
     *
     * @param string $sDataValue
     * @param string $sCode
     * @return bool
     */
	public function VerifyCode($sDataValue, $sCode)
	{
        return false;
	}

    /**
     * @param ref $bResult
     */
    public function SetAccountIsLoggedIn(&$bResult)
    {
        $bResult = false;
    }
}
