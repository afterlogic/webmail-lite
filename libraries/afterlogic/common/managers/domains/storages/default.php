<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
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
	 * @param int $iRealmId
	 * @return bool
	 */
	public function SetGlobalAddressBookVisibilityByRealmId($iVisibility, $iRealmId)
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
	 * @param int $iRealmId
	 * @param bool $bEnable
	 * @return bool
	 */
	public function EnableOrDisableDomainsByRealmId($iRealmId, $bEnable)
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
	 * @param int $iRealmId = 0
	 *
	 * @return array | false [IdDomain => [IsInternal, Name]]
	 */
	public function GetDomainsList($iPage, $iDomainsPerPage, $sOrderBy = 'name', $bOrderType = true, $sSearchDesc = '', $iRealmId = 0)
	{
		return false;
	}

	/**
	 * @param int $iRealmId
	 *
	 * @return array
	 */
	public function GetDomainIdsByRealmId($iRealmId)
	{
		return false;
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
	 * @param int $iRealmId = 0
	 *
	 * @return int | false
	 */
	public function GetDomainCount($sSearchDesc = '', $iRealmId = 0)
	{
		return 0;
	}
}
