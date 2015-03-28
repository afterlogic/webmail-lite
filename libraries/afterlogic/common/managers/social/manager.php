<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Social
 */
class CApiSocialManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('social', $oManager, $sForcedStorage);
		$this->inc('classes.social');
	}
	
	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 * @return \CSocial
	 */
	public function GetSocial($iIdAccount, $sType)
	{
		$oSocial = null;
		try
		{
			$oSocial = $this->oStorage->GetSocial($iIdAccount, $sType);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oSocial;
	}	
	
	/**
	 * @param string $sIdSocial
	 * @param string $sType
	 * @return \CSocial
	 */
	public function GetSocialById($sIdSocial, $sType)
	{
		$oSocial = null;
		try
		{
			$oSocial = $this->oStorage->GetSocialById($sIdSocial, $sType);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oSocial;
	}		
	
	/**
	 * @param int $iIdAccount
	 * @return array
	 */
	public function GetSocials($iIdAccount)
	{
		$aSocials = null;
		try
		{
			$aSocials = $this->oStorage->GetSocials($iIdAccount);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aSocials;
	}	
	
	/**
	 * @param CSocial &$oSocial
	 * @return bool
	 */
	public function CreateSocial(CSocial &$oSocial)
	{
		$bResult = false;
		try
		{
			if ($oSocial->Validate())
			{
				if (!$this->SocialExists($oSocial->TypeStr, $oSocial->IdSocial))
				{
					$bResult = $this->oStorage->CreateSocial($oSocial);
				}
			}
			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CSocial &$oSocial
	 * @return bool
	 */
	public function UpdateSocial(CSocial &$oSocial)
	{
		$bResult = false;
		try
		{
			if ($oSocial->Validate())
			{
				if ($this->SocialExists($oSocial->TypeStr, $oSocial->IdSocial))
				{
					$bResult = $this->oStorage->UpdateSocial($oSocial);
				}
			}
			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}
	
	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 * @return bool
	 */
	public function DeleteSocial($iIdAccount, $sType)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteSocial($iIdAccount, $sType);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}
	
	/**
	 * @param int $iIdAccount
	 * @return bool
	 */
	public function DeleteSocialByAccountId($iIdAccount)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteSocialByAccountId($iIdAccount);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}	
	
	/**
	 * @param string $sType
	 * @param string $sIdSocial
	 * @return string
	 */
	public function SocialExists($sType, $sIdSocial)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->SocialExists($sType, $sIdSocial);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;		
	}	
}
