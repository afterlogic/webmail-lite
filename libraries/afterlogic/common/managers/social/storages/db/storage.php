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
class CApiSocialDbStorage extends CApiSocialStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * @var CApiDomainsCommandCreator
	 */
	protected $oCommandCreator;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('db', $oManager);

		$this->oConnection =& $oManager->GetConnection();
		$this->oCommandCreator =& $oManager->GetCommandCreator(
			$this, array(
				EDbType::MySQL => 'CApiSocialCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiSocialCommandCreatorPostgreSQL'
			)
		);
	}
	
	/**
	 * @param string $sSql
	 * @return CSocial
	 */
	protected function getSocialBySql($sSql)
	{
		$oSocial = null;
		if ($this->oConnection->Execute($sSql))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oSocial = new CSocial();
				$oSocial->InitByDbRow($oRow);
			}
			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $oSocial;
	}	
	
	/**
	 * @param string $sIdSocial
	 * @param string $sType
	 * @return CSocial
	 */
	public function GetSocialById($sIdSocial, $sType)
	{
		return $this->getSocialBySql($this->oCommandCreator->GetSocialById($sIdSocial, $sType));
	}	
	
	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 * @return CSocial
	 */
	public function GetSocial($iIdAccount, $sType)
	{
		return $this->getSocialBySql($this->oCommandCreator->GetSocial((int) $iIdAccount, $sType));
	}	
	
	/**
	 * @param int $iIdAccount
	 * @return array
	 */
	public function GetSocials($iIdAccount)
	{
		$aSocials = array();
		if ($this->oConnection->Execute($this->oCommandCreator->GetSocials((int) $iIdAccount)))
		{
			$oRow = null;
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oSocial = new \CSocial();
				$oSocial->InitByDbRow($oRow);
				$aSocials[] = $oSocial;
			}
		}

		$this->throwDbExceptionIfExist();
		return $aSocials;
	}		
	
	/**
	 * @param CSocial &$oSocial
	 * @return bool
	 */
	public function CreateSocial(\CSocial &$oSocial)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->CreateSocial($oSocial)))
		{
			$oSocial->Id = $this->oConnection->GetLastInsertId('awm_social', 'id');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CSocial &$oSocial
	 * @return bool
	 */
	public function UpdateSocial(\CSocial &$oSocial)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->UpdateSocial($oSocial));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 * @return bool
	 */
	public function DeleteSocial($iIdAccount, $sType)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->DeleteSocial($iIdAccount, $sType));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param int $iIdAccount
	 * @return bool
	 */
	public function DeleteSocialByAccountId($iIdAccount)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->DeleteSocialByAccountId($iIdAccount));
		$this->throwDbExceptionIfExist();
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
		if ($this->oConnection->Execute($this->oCommandCreator->SocialExists($sType, $sIdSocial)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$bResult = 0 < (int) $oRow->social_count;
			}

			$this->oConnection->FreeResult();
		}
		$this->throwDbExceptionIfExist();
		return $bResult;
	}	

}