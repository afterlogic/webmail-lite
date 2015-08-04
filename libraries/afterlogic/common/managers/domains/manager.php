<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiDomainsManager class summary
 *
 * @package Domains
 */
class CApiDomainsManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('domains', $oManager, $sForcedStorage);

		$this->inc('classes.domain');
	}

	/**
	 * @return CDomain
	 */
	public function getDefaultDomain()
	{
		$oDomain = new CDomain();
		$oDomain->IsDefaultDomain = true;
		return $oDomain;
	}

	/**
	 * Retrieve information on domain based on its ID. 
	 * 
	 * @param string $sDomainId ID of the domain to look up.
	 *
	 * @return CDomain
	 */
	public function getDomainById($sDomainId)
	{
		$oDomain = null;
		try
		{
			$oDomain = $this->oStorage->getDomainById($sDomainId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oDomain;
	}

	/**
	 * Retrieve information on domain based on its name. 
	 * 
	 * @param string $sDomainName name of the domain to look up.
	 *
	 * @return CDomain
	 */
	public function getDomainByName($sDomainName)
	{
		$oDomain = null;
		try
		{
			$oDomain = $this->oStorage->getDomainByName($sDomainName);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oDomain;
	}

	/**
	 * Retrieve information on domain based on Web domain value.
	 * 
	 * @param string $sDomainUrl Web domain value.
	 *
	 * @return CDomain
	 */
	public function getDomainByUrl($sDomainUrl)
	{
		$oDomain = null;
		try
		{
			$oDomain = $this->oStorage->getDomainByUrl($sDomainUrl);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		$oDomain = (null === $oDomain) ? $this->getDefaultDomain() : $oDomain;
		return $oDomain;
	}

	/**
	 * Create domain.
	 *
	 * @param CDomain &$oDomain Object instance with its properties filled.
	 *
	 * @return bool
	 */
	public function createDomain(CDomain &$oDomain)
	{
		$bResult = false;
		try
		{
			if ($oDomain->validate())
			{
				if (!$this->domainExists($oDomain->Name))
				{
					$oTenant = null;
					$oTenantsApi = null;

					if (0 < $oDomain->IdTenant && CApi::GetConf('tenant', false))
					{
						/* @var $oTenantsApi CApiTenantsManager */
						$oTenantsApi = CApi::Manager('tenants');
						if ($oTenantsApi)
						{
							/* @var $oTenant CTenant */
							$oTenant = $oTenantsApi->getTenantById($oDomain->IdTenant);
							if (!$oTenant)
							{
								throw new CApiManagerException(Errs::TenantsManager_TenantDoesNotExist);
							}
							else
							{
								if (0 < $oTenant->DomainCountLimit &&
									$oTenant->DomainCountLimit <= $oTenant->getDomainCount())
								{
									throw new CApiManagerException(Errs::TenantsManager_DomainCreateUserLimitReached);
								}
							}
						}
						else
						{
							$oDomain->IdTenant = 0;
						}
					}
					else
					{
						$oDomain->IdTenant = 0;
					}

					if (!$this->oStorage->createDomain($oDomain))
					{
						throw new CApiManagerException(Errs::DomainsManager_DomainCreateFailed);
					}
				}
				else
				{
					throw new CApiManagerException(Errs::DomainsManager_DomainAlreadyExists);
				}
			}

			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * Save domain details back to the database upon modifying the object. 
	 * 
	 * @param CDomain $oDomain
	 *
	 * @return bool
	 */
	public function updateDomain(CDomain $oDomain)
	{
		$bResult = false;
		try
		{
			if ($oDomain->validate())
			{
				if ($oDomain->IsDefaultDomain)
				{
					$oSettings =& CApi::GetSettings();
					$aSettingsMap = $oDomain->GetSettingsMap();

					foreach ($aSettingsMap as $sProperty => $sSettingsName)
					{
						$oSettings->SetConf($sSettingsName, $oDomain->{$sProperty});
					}

					$bResult = $oSettings->SaveToXml();
				}
				else
				{
					if (!$this->oStorage->updateDomain($oDomain))
					{
						throw new CApiManagerException(Errs::DomainsManager_DomainUpdateFailed);
					}

					$bResult = true;
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * Determine if listed domains can be deleted. For that, they must not hold any user accounts. 
	 * 
	 * @param array $aDomainsIds List of domains to check.
	 *
	 * @return bool
	 */
	public function areDomainsEmpty($aDomainsIds)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->areDomainsEmpty($aDomainsIds);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * Enable or disable listed domains.
	 * 
	 * @param array $aDomainsIds List of domains to enable/disable. 
	 * @param bool $bEnable Default value is **true**. Mode switch, true for enabling domains, false for disabling.
	 *
	 * @return bool
	 */
	public function enableOrDisableDomains($aDomainsIds, $bEnable = true)
	{
		$bResult = false;
		if (is_array($aDomainsIds))
		{
			try
			{
				$bResult = $this->oStorage->enableOrDisableDomains($aDomainsIds, $bEnable);
			}
			catch (CApiBaseException $oException)
			{
				$this->setLastException($oException);
			}
		}

		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 * @param bool $bEnable Default value is **true**.
	 *
	 * @return bool
	 */
	public function enableOrDisableDomainsByTenantId($iTenantId, $bEnable = true)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->enableOrDisableDomainsByTenantId($iTenantId, $bEnable);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * Delete domain from the database. For this to work, the domain should not hold any user accounts. Domain ID is used for lookup. 
	 * 
	 * @param int $iDomainId
	 * @param bool $bRemoveAllAccounts Default value is **false**.
	 *
	 * @return bool
	 */
	public function deleteDomainById($iDomainId, $bRemoveAllAccounts = false)
	{
		$bResult = false;
		try
		{
			$oDomain = $this->getDomainById($iDomainId);
			if (!$oDomain)
			{
				throw new CApiManagerException(Errs::DomainsManager_DomainDoesNotExist);
			}

			if (!$bRemoveAllAccounts && !$this->areDomainsEmpty(array($iDomainId)))
			{
				throw new CApiManagerException(Errs::DomainsManager_DomainNotEmpty);
			}

			if ($bRemoveAllAccounts)
			{
				/* @var $oUsersApi CApiUsersManager */
				$oUsersApi = CApi::Manager('users');

				$aPrevIdList = null;
				while (true)
				{
					$aIdList = $oUsersApi->getDefaultAccountIdList($iDomainId, 0, 20);
					if (!$aIdList || 0 === count($aIdList) || (null !== $aPrevIdList &&
						implode(',', $aPrevIdList) === implode(',', $aIdList)))
					{
						break;
					}

					foreach ($aIdList as $iAccountId)
					{
						$oUsersApi->deleteAccountById($iAccountId);
					}

					$aPrevIdList = $aIdList;
				}
			}

			$bResult = $this->oStorage->deleteDomains(array($iDomainId));
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * Delete one or several domains from the database. they must not hold any user accounts. 
	 * 
	 * @param array $aDomainsIds
	 * @param bool $bRemoveAllAccounts Default value is **false**.
	 *
	 * @return bool
	 */
	public function deleteDomains($aDomainsIds, $bRemoveAllAccounts = false)
	{
		$bResult = true;
		if (is_array($aDomainsIds))
		{
			foreach ($aDomainsIds as $iIdDomain)
			{
				if (!$this->deleteDomainById($iIdDomain, $bRemoveAllAccounts))
				{
					$bResult = false;
				}
			}
		}

		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 * @param bool $bRemoveAllAccounts Default value is **false**.
	 *
	 * @return bool
	 */
	public function deleteDomainsByTenantId($iTenantId, $bRemoveAllAccounts = false)
	{
		$bResult = true;

		$aDomainsIds = $this->getDomainIdsByTenantId($iTenantId);

		if (is_array($aDomainsIds))
		{
			foreach ($aDomainsIds as $iIdDomain)
			{
				if (!$this->deleteDomainById($iIdDomain, $bRemoveAllAccounts))
				{
					$bResult = false;
				}
			}
		}

		return $bResult;
	}

	/**
	 * Delete domain from the database. For this to work, the domain should not hold any user accounts. Domain name is used for lookup. 
	 * 
	 * @param string $sDomainName
	 * @param bool $bRemoveAllAccounts Default value is **false**.
	 *
	 * @return bool
	 */
	public function deleteDomainByName($sDomainName, $bRemoveAllAccounts = false)
	{
		$bResult = false;
		$oDomain = $this->getDomainByName($sDomainName);
		if ($oDomain)
		{
			$bResult = $this->deleteDomainById($oDomain->IdDomain, $bRemoveAllAccounts);
		}
		else
		{
			$this->setLastException(new CApiManagerException(Errs::DomainsManager_DomainDoesNotExist));
		}

		return $bResult;
	}

	/**
	 * Get complete list of domains, without pagination. 
	 * 
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return array|false List of domains is returned like [IdDomain => [IsInternal, Name]], IsInternal is reserved for use in MailSuite Pro / Aurora.
	 */
	public function getFullDomainsList($iTenantId = 0)
	{
		return $this->getDomainsList(1, 99999, 'name', true, '', $iTenantId);
	}

	/**
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return array|false
	 */
	public function getFilterList($iTenantId = 0)
	{
		return $this->getFullDomainsList($iTenantId);
	}

	/**
	 * Get list of domains, with pagination enabled. 
	 * 
	 * @param int $iPage Number of page to retrieve. 
	 * @param int $iDomainsPerPage Number of domains per page. 
	 * @param string $sOrderBy Default value is **'name'**. Key field for sorting, name and email values are supported.
	 * @param bool $bOrderType Default value is **true**. Sort direction, true for ascending, false for descending.
	 * @param string $sSearchDesc Default value is empty string. Filtering value. If not empty, only domains matching this value are returned.
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return array|false [IdDomain => [IsInternal, Name]] List of domains is returned like [IdDomain => [IsInternal, Name]], IsInternal is reserved for use in MailSuite Pro / Aurora.
	 */
	public function getDomainsList($iPage, $iDomainsPerPage, $sOrderBy = 'name', $bOrderType = true, $sSearchDesc = '', $iTenantId = 0)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getDomainsList($iPage, $iDomainsPerPage,
				$sOrderBy, $bOrderType, $sSearchDesc, $iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return array|false
	 */
	public function getDomainIdsByTenantId($iTenantId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getDomainIdsByTenantId($iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * Retrieve information on default domain. 
	 * 
	 * @param int $iTenantId
	 *
	 * @return \CDomain|null
	 */
	public function getDefaultDomainByTenantId($iTenantId)
	{
		$oDomain = null;
		try
		{
			$oDomain = $this->oStorage->getDefaultDomainByTenantId($iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oDomain;
	}

	/**
	 * @param int $iVisibility
	 * @param int $iTenantId
	 *
	 * @return array|false
	 */
	public function setGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId)
	{
		$bResult = false;
		try
		{
			if (0 < $iTenantId)
			{
				$this->oStorage->setGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId);
				$bResult = true;
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * Check if domain exists in the database. 
	 *
	 * @param string $sDomainName Domain name to look up.
	 * @return bool
	 */
	public function domainExists($sDomainName)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->domainExists($sDomainName);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * Get number of domains, with optional filtering.
	 * 
	 * @param string $sSearchDesc Default value is empty string. If not empty, only domains matching the search value are counted.
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return int|false
	 */
	public function getDomainCount($sSearchDesc = '', $iTenantId = 0)
	{
		$iResult = false;
		try
		{
			$iResult = $this->oStorage->getDomainCount($sSearchDesc, $iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}
}
