<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
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
			$this, array(EDbType::MySQL => 'CApiDomainsCommandCreatorMySQL')
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
			$oDomain->IdDomain = $this->oConnection->GetLastInsertId();
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
	 * @param int $iRealmId
	 * @return bool
	 */
	public function SetGlobalAddressBookVisibilityByRealmId($iVisibility, $iRealmId)
	{
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->SetGlobalAddressBookVisibilityByRealmId($iVisibility, $iRealmId));

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
	 * @param int $iRealmId
	 * @param bool $bEnable
	 * @return bool
	 */
	public function EnableOrDisableDomainsByRealmId($iRealmId, $bEnable)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->EnableOrDisableDomainsByRealmId($iRealmId, $bEnable));
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
	 * @param int $iRealmId = 0
	 *
	 * @return array | false [IdDomain => [IsInternal, Name]]
	 */
	public function GetDomainsList($iPage, $iDomainsPerPage, $sOrderBy = 'name', $bOrderType = true, $sSearchDesc = '', $iRealmId = 0)
	{
		$aDomains = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetDomainsList($iPage, $iDomainsPerPage,
				$this->dbOrderBy($sOrderBy), $bOrderType, $sSearchDesc, $iRealmId)))
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
	 * @param int $iRealmId
	 *
	 * @return array
	 */
	public function GetDomainIdsByRealmId($iRealmId)
	{
		$aDomainIds = false;
		if ($this->oConnection->Execute(
			$this->oCommandCreator->GetDomainIdsByRealmId($iRealmId)))
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
		}
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param string $sSearchDesc = ''
	 * @param int $iRealmId = 0
	 *
	 * @return int | false
	 */
	public function GetDomainCount($sSearchDesc = '', $iRealmId = 0)
	{
		$iResultCount = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetDomainCount($sSearchDesc, $iRealmId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResultCount = (int) $oRow->domains_count;
			}
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
