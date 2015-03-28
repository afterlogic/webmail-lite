<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Domains
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
	 * @param string $iDomainId
	 * @return CDomain
	 */
	public function GetDomainById($sDomainId)
	{
		return $this->getDomainBySql($this->oCommandCreator->GetDomainById((int) $sDomainId));
	}

	/**
	 * @param string $sDomainName
	 * @return CDomain
	 */
	public function GetDomainByName($sDomainName)
	{
		return $this->getDomainBySql($this->oCommandCreator->GetDomainByName($sDomainName));
	}

	/**
	 * @param string $sDomainUrl
	 * @return CDomain
	 */
	public function GetDomainByUrl($sDomainUrl)
	{
		return $this->getDomainBySql($this->oCommandCreator->GetDomainByUrl($sDomainUrl));
	}

	/**
	 * @param string $sSql
	 * @return CDomain
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
	 * @return bool
	 */
	public function CreateDomain(CDomain &$oDomain)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->CreateDomain($oDomain)))
		{
			$oDomain->IdDomain = $this->oConnection->GetLastInsertId('awm_domains', 'id_domain');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CDomain $oDomain
	 * @return bool
	 */
	public function UpdateDomain(CDomain $oDomain)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->UpdateDomain($oDomain));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iVisibility
	 * @param int $iTenantId
	 * @return bool
	 */
	public function SetGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->SetGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param array $aDomainsIds
	 * @return bool
	 */
	public function AreDomainsEmpty($aDomainsIds)
	{
		$bIsEmpty = true;
		if ($this->oConnection->Execute($this->oCommandCreator->AreDomainsEmpty($aDomainsIds)))
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
	 * @return bool
	 */
	public function EnableOrDisableDomains($aDomainsIds, $bEnable)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->EnableOrDisableDomains($aDomainsIds, $bEnable));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 * @param bool $bEnable
	 * @return bool
	 */
	public function EnableOrDisableDomainsByTenantId($iTenantId, $bEnable)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->EnableOrDisableDomainsByTenantId($iTenantId, $bEnable));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param array $aDomainsIds
	 * @return bool
	 */
	public function DeleteDomains($aDomainsIds)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->DeleteDomains($aDomainsIds));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iDomainId
	 * @return bool
	 */
	public function DeleteDomain($iDomainId)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->DeleteDomain($iDomainId));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iPage
	 * @param int $iDomainsPerPage
	 * @param string $sOrderBy = 'name'
	 * @param bool $bOrderType = true
	 * @param string $sSearchDesc = ''
	 * @param int $iTenantId = 0
	 *
	 * @return array | false [IdDomain => [IsInternal, Name]]
	 */
	public function GetDomainsList($iPage, $iDomainsPerPage, $sOrderBy = 'name', $bOrderType = true, $sSearchDesc = '', $iTenantId = 0)
	{
		$aDomains = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetDomainsList($iPage, $iDomainsPerPage,
				$this->dbOrderBy($sOrderBy), $bOrderType, $sSearchDesc, $iTenantId)))
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
	public function GetDomainIdsByTenantId($iTenantId)
	{
		$aDomainIds = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetDomainIdsByTenantId($iTenantId)))
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
	public function GetDefaultDomainByTenantId($iTenantId)
	{
		return $this->getDomainBySql($this->oCommandCreator->GetDefaultDomainByTenantId((int) $iTenantId));
	}

	/**
	 * @param string $sDomainName
	 * @return bool
	 */
	public function DomainExists($sDomainName)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->DomainExists($sDomainName)))
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
	 * @param string $sSearchDesc = ''
	 * @param int $iTenantId = 0
	 *
	 * @return int | false
	 */
	public function GetDomainCount($sSearchDesc = '', $iTenantId = 0)
	{
		$iResultCount = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetDomainCount($sSearchDesc, $iTenantId)))
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

	/**
	 * @param string $sOrderBy
	 * @return string
	 */
	protected function dbOrderBy($sOrderBy)
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
}
