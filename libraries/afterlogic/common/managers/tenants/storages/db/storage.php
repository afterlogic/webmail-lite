<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
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
}