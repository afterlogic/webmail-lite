<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Licensing
 */
class CApiLicensingManager extends AApiManager
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('licensing', $oManager);

		$this->inc('classes.enc');
		$this->inc('classes.inc', false);
	}

	/**
	 * @return string
	 */
	public function GetLicenseKey()
	{
		return $this->oSettings->GetConf('Common/LicenseKey');
	}

	/**
	 * @param string $sKey
	 * @return bool
	 */
	public function UpdateLicenseKey($sKey)
	{
		$this->oSettings->SetConf('Common/LicenseKey', $sKey);
		return $this->oSettings->SaveToXml();
	}

	/**
	 * @return int
	 */
	public function GetCurrentNumberOfUsers()
	{
		static $iCache = null;
		if (null === $iCache)
		{
			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');
			$iCache = $oApiUsersManager->GetCurrentNumberOfUsers();
		}
		return $iCache;
	}

	/**
	 * @return int
	 */
	public function GetLicenseType()
	{
		$aInfo = $this->getInfo()->ObjValues();
		return isset($aInfo[1]) ? (int) $aInfo[1] : null;
	}

	/**
	 * @return bool
	 */
	public function IsValidKey()
	{
		$aInfo = $this->getInfo();
		return $aInfo->IsValid();
	}

	/**
	 * @return int
	 */
	public function GetVersion()
	{
		$aInfo = $this->getInfo();
		$oValues = $aInfo->ObjValues();
		return $oValues[5];
	}

	/**
	 * @param bool $bCheckOnCreate = false
	 * @return bool
	 */
	public function IsValidLimit($bCheckOnCreate = false)
	{
		$aInfo = $this->getInfo();
		$iCurrentNumberOfUsers = $this->GetCurrentNumberOfUsers();
		$iCurrentNumberOfUsers += $bCheckOnCreate ? 1 : 0;
		return $aInfo->IsValidLimit($iCurrentNumberOfUsers);
	}

	/**
	 * @param int $iExpiredSeconds
	 * @return bool
	 */
	public function IsAboutToExpire(&$iExpiredSeconds)
	{
		return $this->getInfo()->IsAboutToExpire($iExpiredSeconds);
	}

	/**
	 * @return int
	 */
	public function GetUserNumberLimit()
	{
		$aInfo = $this->getInfo()->ObjValues();
		return isset($aInfo[2]) ? $aInfo[2] : null;
	}

	/**
	 * @return int
	 */
	public function GetUserNumberLimitAsString()
	{
		$aInfo = $this->getInfo()->ObjValues();
		$sResult = empty($aInfo[0]) ? 'Empty' : 'Invalid';
		if (isset($aInfo[1], $aInfo[2], $aInfo[5]))
		{
			switch ($aInfo[1])
			{
				case 0:
					$sResult = 'Unlim';
					break;
				case 1:
					$sResult = $aInfo[2].' users, Permanent';
					break;
				case 2:
					$sResult = $aInfo[2].' domains';
					break;
				case 10:
					$sResult = 'Trial';
					if (isset($aInfo[4]))
					{
						$sResult .= ', expires in '.ceil($aInfo[4] / 60 / 60 / 24).' day(s).';
					}
					break;
				case 11:
					$sResult = 'Trial expired.
This license is outdated, please contact AfterLogic to upgrade your license key.';
					break;
				case 3:
					$sResult =  $aInfo[2].' users, Annual';
					if (isset($aInfo[4]))
					{
						$sResult .= ', expires in '.ceil($aInfo[4] / 60 / 60 / 24).' day(s).';
					}
					break;
				case 13:
					$sResult = $aInfo[2].' users, Annual, Expired.
This license is outdated, please contact AfterLogic to upgrade your license key.';
					break;
				case 14:
					$sResult = 'This license is outdated, please contact AfterLogic to upgrade your license key.';
					break;
			}
		}

		return $sResult;
	}

	/**
	 * @return int
	 */
	public function GetT()
	{
		return $this->getInfo()->Generate();
	}

	/**
	 * @return bool
	 */
	public function IsAU()
	{
		return $this->getInfo()->IsAU();
	}

	/**
	 * @return ALInfo
	 */
	protected function getInfo()
	{
		$oK = new ALInfo($this->oSettings->GetConf('Common/LicenseKey'), defined('AL_AU') && AL_AU);
		return $oK;
	}

}
