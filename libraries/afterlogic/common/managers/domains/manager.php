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
	public function GetDefaultDomain()
	{
		$oDomain = new CDomain();
		$oDomain->IsDefaultDomain = true;
		return $oDomain;
	}

	/**
	 * @param string $sDomainId
	 * @return CDomain
	 */
	public function GetDomainById($sDomainId)
	{
		$oDomain = null;
		try
		{
			$oDomain = $this->oStorage->GetDomainById($sDomainId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oDomain;
	}

	/**
	 * @param string $sDomainName
	 * @return CDomain
	 */
	public function GetDomainByName($sDomainName)
	{
		$oDomain = null;
		try
		{
			$oDomain = $this->oStorage->GetDomainByName($sDomainName);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $oDomain;
	}

	/**
	 * @param string $sDomainUrl
	 * @return CDomain
	 */
	public function GetDomainByUrl($sDomainUrl)
	{
		$oDomain = null;
		try
		{
			$oDomain = $this->oStorage->GetDomainByUrl($sDomainUrl);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		$oDomain = (null === $oDomain) ? $this->GetDefaultDomain() : $oDomain;
		return $oDomain;
	}

	/**
	 * @param CDomain &$oDomain
	 * @return bool
	 */
	public function CreateDomain(CDomain &$oDomain)
	{
		$bResult = false;
		try
		{
			if ($oDomain->Validate())
			{
				if (!$this->DomainExists($oDomain->Name))
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
							$oTenant = $oTenantsApi->GetTenantById($oDomain->IdTenant);
							if (!$oTenant)
							{
								throw new CApiManagerException(Errs::TenantsManager_TenantDoesNotExist);
							}
							else
							{
								if (0 < $oTenant->DomainCountLimit &&
									$oTenant->DomainCountLimit <= $oTenant->GetDomainCount())
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

					if (!$this->oStorage->CreateDomain($oDomain))
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
	 * @param CDomain $oDomain
	 * @return bool
	 */
	public function UpdateDomain(CDomain $oDomain)
	{
		$bResult = false;
		try
		{
			if ($oDomain->Validate())
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
					if (!$this->oStorage->UpdateDomain($oDomain))
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
	 * @param array $aDomainsIds
	 * @return bool
	 */
	public function AreDomainsEmpty($aDomainsIds)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->AreDomainsEmpty($aDomainsIds);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param array $aDomainsIds
	 * @param bool $bEnable = true
	 * @return bool
	 */
	public function EnableOrDisableDomains($aDomainsIds, $bEnable = true)
	{
		$bResult = false;
		if (is_array($aDomainsIds))
		{
			try
			{
				$bResult = $this->oStorage->EnableOrDisableDomains($aDomainsIds, $bEnable);
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
	 * @param bool $bEnable = true
	 * @return bool
	 */
	public function EnableOrDisableDomainsByTenantId($iTenantId, $bEnable = true)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->EnableOrDisableDomainsByTenantId($iTenantId, $bEnable);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param int $iDomainId
	 * @param bool $bRemoveAllAccounts = false
	 * @return bool
	 */
	public function DeleteDomainById($iDomainId, $bRemoveAllAccounts = false)
	{
		$bResult = false;
		try
		{
			$oDomain = $this->GetDomainById($iDomainId);
			if (!$oDomain)
			{
				throw new CApiManagerException(Errs::DomainsManager_DomainDoesNotExist);
			}

			if (!$bRemoveAllAccounts && !$this->AreDomainsEmpty(array($iDomainId)))
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
					$aIdList = $oUsersApi->GetUserListIdWithOutOrder($iDomainId, 0, 20);
					if (!$aIdList || 0 === count($aIdList) || (null !== $aPrevIdList &&
						implode(',', $aPrevIdList) === implode(',', $aIdList)))
					{
						break;
					}

					foreach ($aIdList as $iAccountId)
					{
						$oUsersApi->DeleteAccountById($iAccountId);
					}

					$aPrevIdList = $aIdList;
				}
			}

			$bResult = $this->oStorage->DeleteDomains(array($iDomainId));
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param array $aDomainsIds
	 * @param bool $bRemoveAllAccounts = false
	 * @return bool
	 */
	public function DeleteDomains($aDomainsIds, $bRemoveAllAccounts = false)
	{
		$bResult = true;
		if (is_array($aDomainsIds))
		{
			foreach ($aDomainsIds as $iIdDomain)
			{
				if (!$this->DeleteDomainById($iIdDomain, $bRemoveAllAccounts))
				{
					$bResult = false;
				}
			}
		}

		return $bResult;
	}

	/**
	 * @param int $iTenantId
	 * @param bool $bRemoveAllAccounts = false
	 * @return bool
	 */
	public function DeleteDomainsByTenantId($iTenantId, $bRemoveAllAccounts = false)
	{
		$bResult = true;

		$aDomainsIds = $this->GetDomainIdsByTenantId($iTenantId);

		if (is_array($aDomainsIds))
		{
			foreach ($aDomainsIds as $iIdDomain)
			{
				if (!$this->DeleteDomainById($iIdDomain, $bRemoveAllAccounts))
				{
					$bResult = false;
				}
			}
		}

		return $bResult;
	}

	/**
	 * @param string $sDomainName
	 * @param bool $bRemoveAllAccounts = false
	 * @return bool
	 */
	public function DeleteDomainByName($sDomainName, $bRemoveAllAccounts = false)
	{
		$bResult = false;
		$oDomain = $this->GetDomainByName($sDomainName);
		if ($oDomain)
		{
			$bResult = $this->DeleteDomainById($oDomain->IdDomain, $bRemoveAllAccounts);
		}
		else
		{
			$this->setLastException(new CApiManagerException(Errs::DomainsManager_DomainDoesNotExist));
		}

		return $bResult;
	}

	/**
	 * @param int $iTenantId = 0
	 *
	 * @return array | false
	 */
	public function GetFullDomainsList($iTenantId = 0)
	{
		return $this->GetDomainsList(1, 99999, 'name', true, '', $iTenantId);
	}

	/**
	 * @param int $iTenantId = 0
	 *
	 * @return array | false
	 */
	public function GetFilterList($iTenantId = 0)
	{
		return $this->GetFullDomainsList($iTenantId);
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
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetDomainsList($iPage, $iDomainsPerPage,
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
	 * @return array | false
	 */
	public function GetDomainIdsByTenantId($iTenantId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetDomainIdsByTenantId($iTenantId);
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
	 * @return \CDomain
	 */
	public function GetDefaultDomainByTenantId($iTenantId)
	{
		$oDomain = null;
		try
		{
			$oDomain = $this->oStorage->GetDefaultDomainByTenantId($iTenantId);
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
	 * @return array | false
	 */
	public function SetGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId)
	{
		$bResult = false;
		try
		{
			if (0 < $iTenantId)
			{
				$this->oStorage->SetGlobalAddressBookVisibilityByTenantId($iVisibility, $iTenantId);
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
	 * @param string $sDomainName
	 * @return bool
	 */
	public function DomainExists($sDomainName)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DomainExists($sDomainName);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
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
		$iResult = false;
		try
		{
			$iResult = $this->oStorage->GetDomainCount($sSearchDesc, $iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}
}
