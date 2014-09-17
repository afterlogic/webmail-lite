<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
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
				EDbType::PostgreSQL => 'CApiSocailCommandCreatorPostgreSQL'
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
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return CSocial
	 */
	public function GetSocial($iIdAccount, $iType)
	{
		return $this->getSocialBySql($this->oCommandCreator->GetSocial((int) $iIdAccount, (int) $iType));
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
	public function CreateSocial(CSocial &$oSocial)
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
	public function UpdateSocial(CSocial &$oSocial)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->UpdateSocial($oSocial));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return bool
	 */
	public function DeleteSocial($iIdAccount, $iType)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->DeleteSocial($iIdAccount, $iType));
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
	 * @param int $iType
	 * @param string $sIdSocial
	 * @return string
	 */
	public function SocialExists($iType, $sIdSocial)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->SocialExists($iType, $sIdSocial)))
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