<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Tenants
 */
class CApiTenantsDbStorage extends CApiTenantsStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * @var CApiTenantsCommandCreatorMySQL
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
				EDbType::MySQL => 'CApiTenantsCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiTenantsCommandCreatorPostgreSQL'
			)
		);
	}

	/**
	 * @param int $iPage
	 * @param int $iTenantsPerPage
	 * @param string $sOrderBy = 'login'
	 * @param bool $bOrderType = true
	 * @param string $sSearchDesc = ''
	 *
	 * @return array | false [Id => [Login, Description]]
	 */
	public function GetTenantList($iPage, $iTenantsPerPage, $sOrderBy = 'Login', $bOrderType = true, $sSearchDesc = '')
	{
		$aTenants = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetTenantList($iPage, $iTenantsPerPage,
				$this->dbOrderBy($sOrderBy), $bOrderType, $sSearchDesc)))
		{
			$oRow = null;
			$aTenants = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aTenants[$oRow->id_tenant] = array($oRow->login, $oRow->description);
			}
		}

		$this->throwDbExceptionIfExist();
		return $aTenants;
	}

	/**
	 * @param string $sSearchDesc = ''
	 *
	 * @return int | false
	 */
	public function GetTenantCount($sSearchDesc = '')
	{
		$iResultCount = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetTenantCount($sSearchDesc)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResultCount = (int) $oRow->tenants_count;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iResultCount;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return int
	 */
	public function GetTenantAllocatedSize($iTenantId)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->GetTenantAllocatedSize($iTenantId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) ($oRow->allocated_size / 1024);
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iResult;
	}

	/**
	 * @param mixed $mTenantId
	 * @param bool $bIdIsMd5 = false
	 *
	 * @return CTenant
	 */
	public function GetTenantById($mTenantId, $bIdIsMd5 = false)
	{
		$oTenant = null;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetTenantById($mTenantId, $bIdIsMd5)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oTenant = new CTenant();
				$oTenant->InitByDbRow($oRow);
			}

			$this->oConnection->FreeResult();
			
			if ($oTenant !== null)
			{
				$oTenant->Socials = $this->GetSocials($oTenant->IdTenant);
			}
		}

		$this->throwDbExceptionIfExist();
		return $oTenant;
	}

	/**
	 * @param string $sTenantLogin
	 * @param string $sTenantPassword = null
	 *
	 * @return int
	 */
	public function GetTenantIdByLogin($sTenantLogin, $sTenantPassword = null)
	{
		$iTenantId = 0;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetTenantIdByLogin($sTenantLogin, $sTenantPassword)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iTenantId = (int) $oRow->id_tenant;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iTenantId;
	}

	/**
	 * @param int $iDomainId
	 *
	 * @return int
	 */
	public function GetTenantIdByDomainId($iDomainId)
	{
		$iTenantId = 0;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetTenantIdByDomainId($iDomainId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iTenantId = (int) $oRow->id_tenant;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iTenantId;
	}

	/**
	 * @param int $iIdTenant
	 *
	 * @return string
	 */
	public function GetTenantLoginById($iIdTenant)
	{
		$sResult = '';
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetTenantLoginById($iIdTenant)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$sResult = (string) $oRow->login;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $sResult;
	}

	/**
	 * @param int $iChannelId
	 *
	 * @return array
	 */
	public function GetTenantsIdsByChannelId($iChannelId)
	{
		$aTenantsIds = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetTenantsIdsByChannelId($iChannelId)))
		{
			$oRow = null;
			$aTenantsIds = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aTenantsIds[] = $oRow->id_tenant;
			}
		}

		$this->throwDbExceptionIfExist();
		return $aTenantsIds;
	}

	/**
	 * @param int $iTenantId
	 * @param int|null $iExceptUserId = null
	 *
	 * @return array
	 */
	public function GetSubscriptionUserUsage($iTenantId, $iExceptUserId = null)
	{
		$aLimits = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetSubscriptionUserUsage($iTenantId, $iExceptUserId)))
		{
			$oRow = null;
			$aLimits = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aLimits[$oRow->id_subscription] = $oRow->cnt;
			}
		}

		$this->throwDbExceptionIfExist();
		return $aLimits;
	}

	/**
	 * @param CTenant $oTenant
	 *
	 * @return bool
	 */
	public function TenantExists(CTenant $oTenant)
	{
		$bResult = false;
		$niExceptTenantId = (0 < $oTenant->IdTenant) ? $oTenant->IdTenant : null;

		if ($this->oConnection->Execute(
			$this->oCommandCreator->TenantExists($oTenant->Login, $niExceptTenantId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow && 0 < (int) $oRow->tenants_count)
			{
				$bResult = true;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return array
	 */
	public function GetTenantDomains($iTenantId)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetTenantDomains($iTenantId)))
		{
			$oRow = null;
			$mResult = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$mResult[$oRow->id_domain] = $oRow->name;
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param CTenant $oTenant
	 */
	public function CreateTenant(CTenant &$oTenant)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->CreateTenant($oTenant)))
		{
			$bResult = true;
			$oTenant->IdTenant = $this->oConnection->GetLastInsertId('awm_tenants', 'id_tenant');
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CTenant $oTenant
	 */
	public function UpdateTenant(CTenant $oTenant)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->UpdateTenant($oTenant));
		if ($bResult)
		{
			$this->DeleteSocialsByTenantId($oTenant->IdTenant);
			foreach ($oTenant->Socials as $sKey => $oSocial)
			{
				$oSocial->IdTenant = $oTenant->IdTenant;
				$this->CreateSocial($oSocial);
			}
		}
		
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CTenant $oTenant
	 * @param int $iAllocatedSizeInBytes
	 *
	 * @return bool
	 */
	public function AllocateFileUsage($oTenant, $iAllocatedSizeInBytes)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->AllocateFileUsage($oTenant, $iAllocatedSizeInBytes));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return bool
	 */
	public function DeleteTenant($iTenantId)
	{
		return $this->DeleteTenants(array($iTenantId));
	}

	/**
	 * @param array $aTenantIds
	 *
	 * @return bool
	 */
	public function DeleteTenants(array $aTenantIds)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->DeleteTenants($aTenantIds));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return bool
	 */
	public function DeleteTenantSubscriptions($iTenantId)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->DeleteTenantSubscriptions($iTenantId));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return bool
	 */
	public function UpdateTenantMainCapa($iTenantId, $sCapa)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->UpdateTenantMainCapa($iTenantId, $sCapa));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param string $sOrderBy
	 * @return string
	 */
	protected function dbOrderBy($sOrderBy)
	{
		$sResult = $sOrderBy;
		switch ($sOrderBy)
		{
			case 'Description':
				$sResult = 'description';
				break;
			case 'Login':
				$sResult = 'login';
				break;
		}
		return $sResult;
	}
	
	/**
	 * @param int $iIdTenant
	 *
	 * @return array | false
	 */
	public function GetSocials($iIdTenant)
	{
		$aSocials = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetSocials($iIdTenant)))
		{
			$oRow = null;
			$aSocials = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oSocial = new CTenantSocials();
				$oSocial->InitByDbRow($oRow);

				$aSocials[strtolower($oSocial->SocialName)] = $oSocial;
			}
		}
		$this->oConnection->FreeResult();

		$this->throwDbExceptionIfExist();
		return $aSocials;
	}		
	
	/**
	 * @param int $iIdSocial
	 *
	 * @return array | false
	 */
	public function GetSocialById($iIdSocial)
	{
		$oSocial = null;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetSocialById($iIdSocial)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oSocial = new CTenantSocials();
				$oSocial->InitByDbRow($oRow);
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $oSocial;
	}		
	
	/**
	 * @param int $iIdTenant
	 * @param string $sSocialName
	 *
	 * @return array | false
	 */
	public function GetSocialByName($iIdTenant, $sSocialName)
	{
		$oSocial = null;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetSocialByName($iIdTenant, $sSocialName)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oSocial = new CTenantSocials();
				$oSocial->InitByDbRow($oRow);
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $oSocial;
	}			
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function SocialExists(CTenantSocials $oSocial)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->SocialExists($oSocial);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}	
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function CreateSocial(CTenantSocials &$oSocial)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->CreateSocial($oSocial)))
		{
			$bResult = true;
			$oSocial->Id = $this->oConnection->GetLastInsertId('awm_tenant_socials', 'id');
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param int $iIdSocial
	 *
	 * @return bool
	 */
	public function DeleteSocial($iIdSocial)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->DeleteSocial($iIdSocial));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param int $iTenanatId
	 *
	 * @return bool
	 */
	public function DeleteSocialsByTenantId($iTenanatId)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->DeleteSocialsByTenantId($iTenanatId));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function UpdateSocial(CTenantSocials $oSocial)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->UpdateSocial($oSocial));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}	

}