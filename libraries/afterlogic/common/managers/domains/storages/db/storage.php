<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @internal
 * 
 * @package Domains
 * @subpackage Storages
 */
class CApiDomainsDbStorage extends CApiDomainsStorage
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
				EDbType::MySQL => 'CApiDomainsCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiDomainsCommandCreatorPostgreSQL'
			)
		);
	}

	/**
	 * @param string $sOrderBy
	 *
	 * @return string
	 */
	protected function _dbOrderBy($sOrderBy)
	{
		$sResult = $sOrderBy;
		switch (strtolower($sOrderBy))
		{
			default:
			case 'name':
				$sResult = 'name';
				break;
			case 'email':
				$sResult = 'email';
				break;
		}
		return $sResult;
	}

	/**
	 * @param string $sDomainId
	 *
	 * @return CDomain|null
	 */
	public function getDomainById($sDomainId)
	{
		return $this->getDomainBySql($this->oCommandCreator->getDomainById((int) $sDomainId));
	}

	/**
	 * @param string $sDomainName
	 *
	 * @return CDomain|null
	 */
	public function getDomainByName($sDomainName)
	{
		return $this->getDomainBySql($this->oCommandCreator->getDomainByName($sDomainName));
	}

	/**
	 * @param string $sDomainUrl
	 *
	 * @return CDomain|null
	 */
	public function getDomainByUrl($sDomainUrl)
	{
		return $this->getDomainBySql($this->oCommandCreator->getDomainByUrl($sDomainUrl));
	}

	/**
	 * @param string $sSql
	 *
	 * @return CDomain|null
	 */
	protected function getDomainBySql($sSql)
	{
		$oDomain = null;
		if ($this->oConnection->Execute($sSql))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oDomain = new CDomain();
				$oDomain->InitByDbRow($oRow);
			}
			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $oDomain;
	}

	/**
	 * @param CDomain &$oDomain
	 *
	 * @return bool
	 */
	public function createDomain(CDomain &$oDomain)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createDomain($oDomain)))
		{
			$oDomain->IdDomain = $this->oConnection->GetLastInsertId('awm_domains', 'id_domain');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CDomain $oDomain
	 *
	 * @return bool
	 */
	public function updateDomain(CDomain $oDomain)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->updateDomain($oDomain));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iVisibility
	 * @param int $iTenantId
	 *
	 * @return bool
	 */
	public function setGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->setGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param array $aDomainsIds
	 *
	 * @return bool
	 */
	public function areDomainsEmpty($aDomainsIds)
	{
		$bIsEmpty = true;
		if ($this->oConnection->Execute($this->oCommandCreator->areDomainsEmpty($aDomainsIds)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$bIsEmpty = !(0 < (int) $oRow->users_count);
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $bIsEmpty;
	}

	/**
	 * @param array $aDomainsIds
	 * @param bool $bEnable
	 *
	 * @return bool
	 */
	public function enableOrDisableDomains($aDomainsIds, $bEnable)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->enableOrDisableDomains($aDomainsIds, $bEnable));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 * @param bool $bEnable
	 *
	 * @return bool
	 */
	public function enableOrDisableDomainsByTenantId($iTenantId, $bEnable)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->enableOrDisableDomainsByTenantId($iTenantId, $bEnable));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param array $aDomainsIds
	 *
	 * @return bool
	 */
	public function deleteDomains($aDomainsIds)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->deleteDomains($aDomainsIds));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iDomainId
	 *
	 * @return bool
	 */
	public function deleteDomain($iDomainId)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->deleteDomain($iDomainId));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iPage
	 * @param int $iDomainsPerPage
	 * @param string $sOrderBy Default value is **'name'**
	 * @param bool $bOrderType Default value is **true**.
	 * @param string $sSearchDesc Default value is empty string.
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return array|false [IdDomain => [IsInternal, Name]]
	 */
	public function getDomainsList($iPage, $iDomainsPerPage, $sOrderBy = 'name', $bOrderType = true, $sSearchDesc = '', $iTenantId = 0)
	{
		$aDomains = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getDomainsList($iPage, $iDomainsPerPage,
				$this->_dbOrderBy($sOrderBy), $bOrderType, $sSearchDesc, $iTenantId)))
		{
			$oRow = null;
			$aDomains = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aDomains[$oRow->id_domain] = array((bool) $oRow->is_internal, strtolower($oRow->name));
			}
		}
		$this->throwDbExceptionIfExist();
		return $aDomains;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return array
	 */
	public function getDomainIdsByTenantId($iTenantId)
	{
		$aDomainIds = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->getDomainIdsByTenantId($iTenantId)))
		{
			$oRow = null;
			$aDomainIds = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aDomainIds[] = $oRow->id_domain;
			}
		}

		$this->throwDbExceptionIfExist();
		return $aDomainIds;
	}
	
	/**
	 * @param int $iTenantId
	 *
	 * @return \CDomain
	 */
	public function getDefaultDomainByTenantId($iTenantId)
	{
		return $this->getDomainBySql($this->oCommandCreator->getDefaultDomainByTenantId((int) $iTenantId));
	}

	/**
	 * @param string $sDomainName
	 *
	 * @return bool
	 */
	public function domainExists($sDomainName)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->domainExists($sDomainName)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$bResult = 0 < (int) $oRow->domains_count;
			}

			$this->oConnection->FreeResult();
		}
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param string $sSearchDesc Default value is empty string.
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return int|false
	 */
	public function getDomainCount($sSearchDesc = '', $iTenantId = 0)
	{
		$iResultCount = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getDomainCount($sSearchDesc, $iTenantId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResultCount = (int) $oRow->domains_count;
			}

			$this->oConnection->FreeResult();
		}
		$this->throwDbExceptionIfExist();
		return $iResultCount;
	}
}
