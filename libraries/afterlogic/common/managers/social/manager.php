<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
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
	 * @param int $iType
	 * @return \CSocial
	 */
	public function GetSocial($iIdAccount, $iType)
	{
		$oSocial = null;
		try
		{
			$oSocial = $this->oStorage->GetSocial($iIdAccount, $iType);
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
				if (!$this->SocialExists($oSocial->Type, $oSocial->IdSocial))
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
				if ($this->SocialExists($oSocial->Type, $oSocial->IdSocial))
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
	 * @param int $iType
	 * @return bool
	 */
	public function DeleteSocial($iIdAccount, $iType)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteSocial($iIdAccount, $iType);
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
	 * @param int $iType
	 * @param string $sIdSocial
	 * @return string
	 */
	public function SocialExists($iType, $sIdSocial)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->SocialExists($iType, $sIdSocial);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;		
	}	
}
