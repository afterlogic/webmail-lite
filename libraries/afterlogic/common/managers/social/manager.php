<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiSocialManager class summary
 *
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
	 *
	 * @return \CSocial
	 */
	public function getSocial($iIdAccount, $sType)
	{
		$oSocial = null;
		try
		{
			$oSocial = $this->oStorage->getSocial($iIdAccount, $sType);
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
	 *
	 * @return \CSocial
	 */
	public function getSocialById($sIdSocial, $sType)
	{
		$oSocial = null;
		try
		{
			$oSocial = $this->oStorage->getSocialById($sIdSocial, $sType);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oSocial;
	}		
	
	/**
	 * @param int $iIdAccount
	 *
	 * @return array
	 */
	public function getSocials($iIdAccount)
	{
		$aSocials = null;
		try
		{
			$aSocials = $this->oStorage->getSocials($iIdAccount);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aSocials;
	}	
	
	/**
	 * @param CSocial &$oSocial
	 *
	 * @return bool
	 */
	public function createSocial(CSocial &$oSocial)
	{
		$bResult = false;
		try
		{
			if ($oSocial->validate())
			{
				if (!$this->isSocialExists($oSocial))
				{
					$bResult = $this->oStorage->createSocial($oSocial);
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
	 *
	 * @return bool
	 */
	public function updateSocial(CSocial &$oSocial)
	{
		$bResult = false;
		try
		{
			if ($oSocial->validate())
			{
				if ($this->isSocialExists($oSocial))
				{
					$bResult = $this->oStorage->updateSocial($oSocial);
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
	 *
	 * @return bool
	 */
	public function deleteSocial($iIdAccount, $sType)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteSocial($iIdAccount, $sType);
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
	 *
	 * @return bool
	 */
	public function deleteSocialByAccountId($iIdAccount)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteSocialByAccountId($iIdAccount);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}	
	
	/**
	 * @param string $sEmail
	 *
	 * @return bool
	 */
	public function deleteSocialsByEmail($sEmail)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteSocialsByEmail($sEmail);
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
	 *
	 * @return bool
	 */
	public function isSocialExists(CSocial $oSocial)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->isSocialExists($oSocial);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;		
	}	
}
