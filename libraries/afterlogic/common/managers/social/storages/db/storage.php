<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Social
 * @subpackage Storages
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
	 *
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
	 *
	 * @return CSocial
	 */
	public function getSocialById($sIdSocial, $sType)
	{
		return $this->getSocialBySql($this->oCommandCreator->getSocialById($sIdSocial, $sType));
	}	
	
	/**
	 * @param int $iIdAccount
	 * @param string $sType
	 *
	 * @return CSocial
	 */
	public function getSocial($iIdAccount, $sType)
	{
		return $this->getSocialBySql($this->oCommandCreator->getSocial((int) $iIdAccount, $sType));
	}	
	
	/**
	 * @param int $iIdAccount
	 *
	 * @return array
	 */
	public function getSocials($iIdAccount)
	{
		$aSocials = array();
		if ($this->oConnection->Execute($this->oCommandCreator->getSocials((int) $iIdAccount)))
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
	 *
	 * @return bool
	 */
	public function createSocial(\CSocial &$oSocial)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createSocial($oSocial)))
		{
			$oSocial->Id = $this->oConnection->GetLastInsertId('awm_social', 'id');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CSocial &$oSocial
	 *
	 * @return bool
	 */
	public function updateSocial(\CSocial &$oSocial)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->updateSocial($oSocial));
		$this->throwDbExceptionIfExist();
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
		$bResult = $this->oConnection->Execute($this->oCommandCreator->deleteSocial($iIdAccount, $sType));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param int $iIdAccount
	 *
	 * @return bool
	 */
	public function deleteSocialByAccountId($iIdAccount)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->deleteSocialByAccountId($iIdAccount));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param string $sEmail
	 *
	 * @return bool
	 */
	public function deleteSocialsByEmail($sEmail)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->deleteSocialsByEmail($sEmail));
		$this->throwDbExceptionIfExist();
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
		if ($this->oConnection->Execute($this->oCommandCreator->isSocialExists($oSocial->IdAccount, $oSocial->TypeStr)))
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