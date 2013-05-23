<?php

/**
 * AfterLogic Api by AfterLogic Corp. <support@afterlogic.com>
 *
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
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
					$oRealm = null;
					$oRealmsApi = null;

					if (0 < $oDomain->IdRealm && CApi::GetConf('realm', false))
					{
						/* @var $oRealmsApi CApiRealmsManager */
						$oRealmsApi = CApi::Manager('realms');
						if ($oRealmsApi)
						{
							/* @var $oRealm CRealm */
							$oRealm = $oRealmsApi->GetRealmById($oDomain->IdRealm);
							if (!$oRealm)
							{
								throw new CApiManagerException(Errs::RealmsManager_RealmDoesNotExist);
							}
							else
							{
								if (0 < $oRealm->DomainCountLimit &&
									$oRealm->DomainCountLimit <= $oRealm->GetDomainCount())
								{
									throw new CApiManagerException(Errs::RealmsManager_DomainCreateUserLimitReached);
								}
							}
						}
						else
						{
							$oDomain->IdRealm = 0;
						}
					}
					else
					{
						$oDomain->IdRealm = 0;
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
	 * @param int $iRealmId
	 * @param bool $bEnable = true
	 * @return bool
	 */
	public function EnableOrDisableDomainsByRealmId($iRealmId, $bEnable = true)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->EnableOrDisableDomainsByRealmId($iRealmId, $bEnable);
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
	 * @param int $iRealmId
	 * @param bool $bRemoveAllAccounts = false
	 * @return bool
	 */
	public function DeleteDomainsByRealmId($iRealmId, $bRemoveAllAccounts = false)
	{
		$bResult = true;

		$aDomainsIds = $this->GetDomainIdsByRealmId($iRealmId);

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
	 * @param int $iRealmId = 0
	 *
	 * @return array | false
	 */
	public function GetFullDomainsList($iRealmId = 0)
	{
		return $this->GetDomainsList(1, 99999, 'name', true, '', $iRealmId);
	}

	/**
	 * @param int $iRealmId = 0
	 *
	 * @return array | false
	 */
	public function GetFilterList($iRealmId = 0)
	{
		return $this->GetFullDomainsList($iRealmId);
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
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetDomainsList($iPage, $iDomainsPerPage,
				$sOrderBy, $bOrderType, $sSearchDesc, $iRealmId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iRealmId
	 *
	 * @return array | false
	 */
	public function GetDomainIdsByRealmId($iRealmId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetDomainIdsByRealmId($iRealmId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iVisibility
	 * @param int $iRealmId
	 *
	 * @return array | false
	 */
	public function SetGlobalAddressBookVisibilityByRealmId($iVisibility, $iRealmId)
	{
		$bResult = false;
		try
		{
			if (0 < $iRealmId)
			{
				$this->oStorage->SetGlobalAddressBookVisibilityByRealmId($iVisibility, $iRealmId);
				$bResult = true;
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
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
	 * @param int $iRealmId = 0
	 *
	 * @return int | false
	 */
	public function GetDomainCount($sSearchDesc = '', $iRealmId = 0)
	{
		$iResult = false;
		try
		{
			$iResult = $this->oStorage->GetDomainCount($sSearchDesc, $iRealmId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}
}
