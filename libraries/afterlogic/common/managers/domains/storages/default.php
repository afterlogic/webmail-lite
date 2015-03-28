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
class CApiDomainsStorage extends AApiManagerStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct($sStorageName, CApiGlobalManager &$oManager)
	{
		parent::__construct('domains', $sStorageName, $oManager);
	}

	/**
	 * @param string $sDomainId
	 * @return CDomain
	 */
	public function GetDomainById($sDomainId)
	{
		return null;
	}

	/**
	 * @param string $sDomainName
	 * @return CDomain
	 */
	public function GetDomainByName($sDomainName)
	{
		return null;
	}

	/**
	 * @param string $sDomainUrl
	 * @return CDomain
	 */
	public function GetDomainByUrl($sDomainUrl)
	{
		return null;
	}

	/**
	 * @param CDomain &$oDomain
	 * @return bool
	 */
	public function CreateDomain(CDomain &$oDomain)
	{
		return false;
	}

	/**
	 * @param CDomain $oDomain
	 * @return bool
	 */
	public function UpdateDomain(CDomain $oDomain)
	{
		return false;
	}
	
	/**
	 * @param int $iVisibility
	 * @param int $iTenantId
	 * @return bool
	 */
	public function SetGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId)
	{
		return false;
	}

	/**
	 * @param array $aDomainsIds
	 * @return bool
	 */
	public function AreDomainsEmpty($aDomainsIds)
	{
		return true;
	}

	/**
	 * @param array $aDomainsIds
	 * @param bool $bEnable
	 * @return bool
	 */
	public function EnableOrDisableDomains($aDomainsIds, $bEnable)
	{
		return false;
	}

	/**
	 * @param int $iTenantId
	 * @param bool $bEnable
	 * @return bool
	 */
	public function EnableOrDisableDomainsByTenantId($iTenantId, $bEnable)
	{
		return false;
	}

	/**
	 * @param array $aDomainsIds
	 * @return bool
	 */
	public function DeleteDomains($aDomainsIds)
	{
		return false;
	}

	/**
	 * @param int $iDomainId
	 * @return bool
	 */
	public function DeleteDomain($iDomainId)
	{
		return false;
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
		return false;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return array
	 */
	public function GetDomainIdsByTenantId($iTenantId)
	{
		return false;
	}
	
	/**
	 * @param int $iTenantId
	 *
	 * @return \CDomain
	 */
	public function GetDefaultDomainByTenantId($iTenantId)
	{
		return null;
	}
	
	/**
	 * @param string $sDomainName
	 * @return bool
	 */
	public function DomainExists($sDomainName)
	{
		return false;
	}

	/**
	 * @param string $sSearchDesc = ''
	 * @param int $iTenantId = 0
	 *
	 * @return int | false
	 */
	public function GetDomainCount($sSearchDesc = '', $iTenantId = 0)
	{
		return 0;
	}
}
