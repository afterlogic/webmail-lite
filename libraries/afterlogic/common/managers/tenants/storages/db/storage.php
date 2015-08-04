<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Tenants
 * @subpackage Storages
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
	 * @param string $sOrderBy
	 * @return string
	 */
	protected function _dbOrderBy($sOrderBy)
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
	 * @param int $iPage
	 * @param int $iTenantsPerPage
	 * @param string $sOrderBy Default value is **'login'**.
	 * @param bool $bOrderType Default value is **true**.
	 * @param string $sSearchDesc Default value is empty string.
	 *
	 * @return array|false [Id => [Login, Description]]
	 */
	public function getTenantList($iPage, $iTenantsPerPage, $sOrderBy = 'Login', $bOrderType = true, $sSearchDesc = '')
	{
		$aTenants = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getTenantList($iPage, $iTenantsPerPage,
				$this->_dbOrderBy($sOrderBy), $bOrderType, $sSearchDesc)))
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
	 * @param string $sSearchDesc Default value is empty string.
	 *
	 * @return int|false
	 */
	public function getTenantCount($sSearchDesc = '')
	{
		$iResultCount = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getTenantCount($sSearchDesc)))
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
	public function getTenantAllocatedSize($iTenantId)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->getTenantAllocatedSize($iTenantId)))
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
	 * @param bool $bIdIsMd5 Default value is **false**.
	 *
	 * @return CTenant
	 */
	public function getTenantById($mTenantId, $bIdIsMd5 = false)
	{
		$oTenant = null;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getTenantById($mTenantId, $bIdIsMd5)))
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
				$oTenant->Socials = $this->getSocials($oTenant->IdTenant);
			}
		}

		$this->throwDbExceptionIfExist();
		return $oTenant;
	}

	/**
	 * @param string $sTenantLogin
	 * @param string $sTenantPassword Default value is **null**.
	 *
	 * @return int
	 */
	public function getTenantIdByLogin($sTenantLogin, $sTenantPassword = null)
	{
		$iTenantId = 0;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getTenantIdByLogin($sTenantLogin, $sTenantPassword)))
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
	public function getTenantIdByDomainId($iDomainId)
	{
		$iTenantId = 0;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getTenantIdByDomainId($iDomainId)))
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
	public function getTenantLoginById($iIdTenant)
	{
		$sResult = '';
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getTenantLoginById($iIdTenant)))
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
	public function getTenantsIdsByChannelId($iChannelId)
	{
		$aTenantsIds = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getTenantsIdsByChannelId($iChannelId)))
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
	 * @param int|null $iExceptUserId Default value is **null**.
	 *
	 * @return array
	 */
	public function getSubscriptionUserUsage($iTenantId, $iExceptUserId = null)
	{
		$aLimits = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getSubscriptionUserUsage($iTenantId, $iExceptUserId)))
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
	public function isTenantExists(CTenant $oTenant)
	{
		$bResult = false;
		$niExceptTenantId = (0 < $oTenant->IdTenant) ? $oTenant->IdTenant : null;

		if ($this->oConnection->Execute(
			$this->oCommandCreator->isTenantExists($oTenant->Login, $niExceptTenantId)))
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
	public function getTenantDomains($iTenantId)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getTenantDomains($iTenantId)))
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
	 *
	 * @return bool
	 */
	public function createTenant(CTenant &$oTenant)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createTenant($oTenant)))
		{
			$bResult = true;
			$oTenant->IdTenant = $this->oConnection->GetLastInsertId('awm_tenants', 'id_tenant');
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CTenant $oTenant
	 *
	 * @return bool
	 */
	public function updateTenant(CTenant $oTenant)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->updateTenant($oTenant));
		if ($bResult)
		{
			$this->deleteSocialsByTenantId($oTenant->IdTenant);
			foreach ($oTenant->Socials as $sKey => $oSocial)
			{
				$oSocial->IdTenant = $oTenant->IdTenant;
				$this->createSocial($oSocial);
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
	public function allocateFileUsage($oTenant, $iAllocatedSizeInBytes)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->allocateFileUsage($oTenant, $iAllocatedSizeInBytes));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return bool
	 */
	public function deleteTenant($iTenantId)
	{
		return $this->deleteTenants(array($iTenantId));
	}

	/**
	 * @param array $aTenantIds
	 *
	 * @return bool
	 */
	public function deleteTenants(array $aTenantIds)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->deleteTenants($aTenantIds));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return bool
	 */
	public function deleteTenantSubscriptions($iTenantId)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->deleteTenantSubscriptions($iTenantId));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return bool
	 */
	public function updateTenantMainCapa($iTenantId, $sCapa)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->updateTenantMainCapa($iTenantId, $sCapa));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iIdTenant
	 *
	 * @return array|false
	 */
	public function getSocials($iIdTenant)
	{
		$aSocials = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getSocials($iIdTenant)))
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
	 * @return array|false
	 */
	public function getSocialById($iIdSocial)
	{
		$oSocial = null;
		if ($this->oConnection->Execute($this->oCommandCreator->getSocialById($iIdSocial)))
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
	 * @return array|false
	 */
	public function getSocialByName($iIdTenant, $sSocialName)
	{
		$oSocial = null;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getSocialByName($iIdTenant, $sSocialName)))
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
	public function isSocialExists(CTenantSocials $oSocial)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->isSocialExists($oSocial);
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
	public function createSocial(CTenantSocials &$oSocial)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createSocial($oSocial)))
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
	public function deleteSocial($iIdSocial)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->deleteSocial($iIdSocial));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param int $iTenanatId
	 *
	 * @return bool
	 */
	public function deleteSocialsByTenantId($iTenanatId)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->deleteSocialsByTenantId($iTenanatId));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function updateSocial(CTenantSocials $oSocial)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->updateSocial($oSocial));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}	

}