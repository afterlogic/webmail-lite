<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Tenants
 */
class CApiTenantsManager extends AApiManagerWithStorage
{
	/**
	 * @var array
	 */
	static $aTenantNameCache = array();

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('tenants', $oManager, $sForcedStorage);

		$this->inc('classes.tenant');
	}

	/**
	 * @param int $iPage
	 * @param int $iTenantsPerPage
	 * @param string $sOrderBy = 'Login'
	 * @param bool $bOrderType = true
	 * @param string $sSearchDesc = ''
	 *
	 * @return array | false [Id => [Login, Description]]
	 */
	public function GetTenantList($iPage, $iTenantsPerPage, $sOrderBy = 'Login', $bOrderType = true, $sSearchDesc = '')
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetTenantList($iPage, $iTenantsPerPage, $sOrderBy, $bOrderType, $sSearchDesc);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param string $sSearchDesc = ''
	 *
	 * @return int | false
	 */
	public function GetTenantCount($sSearchDesc = '')
	{
		$iResult = false;
		try
		{
			$iResult = $this->oStorage->GetTenantCount($sSearchDesc);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return CTenant
	 */
	public function GetTenantAllocatedSize($iTenantId)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetTenantAllocatedSize($iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $iResult;
	}

	/**
	 * @param int $iTenantId
	 *
	 * @return CTenant
	 */
	public function GetTenantById($iTenantId)
	{
		$oTenant = null;
		try
		{
			$oTenant = $this->oStorage->GetTenantById($iTenantId);
			if ($oTenant)
			{
				$oTenant->AllocatedSpaceInMB = $this->GetTenantAllocatedSize($iTenantId);
				$oTenant->FlushObsolete('AllocatedSpaceInMB');
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

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
		try
		{
			if (!empty($sTenantLogin))
			{
				$iTenantId = $this->oStorage->GetTenantIdByLogin($sTenantLogin, $sTenantPassword);
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $iTenantId;
	}

	/**
	 * @param int $iIdTenant
	 * @param bool $bUseCache = false
	 *
	 * @return string
	 */
	public function GetTenantLoginById($iIdTenant, $bUseCache = false)
	{
		$sResult = '';
		try
		{
			if (0 < $iIdTenant)
			{
				if ($bUseCache && !empty(self::$aTenantNameCache[$iIdTenant]))
				{
					return self::$aTenantNameCache[$iIdTenant];
				}

				$sResult = $this->oStorage->GetTenantLoginById($iIdTenant);
				if ($bUseCache && !empty($sResult))
				{
					self::$aTenantNameCache[$iIdTenant] = $sResult;
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $sResult;
	}

	/**
	 * @param CTenant $oTenant
	 *
	 * @return bool
	 */
	public function TenantExists(CTenant $oTenant)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->TenantExists($oTenant);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
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
		try
		{
			$mResult = $this->oStorage->GetTenantDomains($iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CTenant $oTenant
	 *
	 * @return bool
	 */
	public function CreateTenant(CTenant &$oTenant)
	{
		$bResult = false;
		try
		{
			if ($oTenant->Validate())
			{
				if (!$this->TenantExists($oTenant))
				{
					if (0 < $oTenant->IdChannel && CApi::GetConf('tenant', false))
					{
						/* @var $oChannelsApi CApiChannelsManager */
						$oChannelsApi = CApi::Manager('channels');
						if ($oChannelsApi)
						{
							/* @var $oChannel CChannel */
							$oChannel = $oChannelsApi->GetChannelById($oTenant->IdChannel);
							if (!$oChannel)
							{
								throw new CApiManagerException(Errs::ChannelsManager_ChannelDoesNotExist);
							}
						}
						else
						{
							$oTenant->IdChannel = 0;
						}
					}
					else
					{
						$oTenant->IdChannel = 0;
					}

					if (!$this->oStorage->CreateTenant($oTenant))
					{
						throw new CApiManagerException(Errs::TenantsManager_TenantCreateFailed);
					}
				}
				else
				{
					throw new CApiManagerException(Errs::TenantsManager_TenantAlreadyExists);
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
	 * @param CTenant $oTenant
	 */
	public function UpdateTenant(CTenant $oTenant)
	{
		$bResult = false;
		try
		{
			if ($oTenant->Validate())
			{
				if (null !== $oTenant->GetObsoleteValue('QuotaInMB'))
				{
					$iQuota = $oTenant->QuotaInMB;
					if (0 < $iQuota)
					{
						$iSize = $this->GetTenantAllocatedSize($oTenant->IdTenant);
						if ($iSize > $iQuota)
						{
							throw new CApiManagerException(Errs::TenantsManager_QuotaLimitExided);
						}
					}
				}

				if (!$this->oStorage->UpdateTenant($oTenant))
				{
					throw new CApiManagerException(Errs::TenantsManager_TenantUpdateFailed);
				}

				if (null !== $oTenant->GetObsoleteValue('IsDisabled'))
				{
					/* @var $oDomainsApi CApiDomainsManager */
					$oDomainsApi = CApi::Manager('domains');
					if (!$oDomainsApi->EnableOrDisableDomainsByTenantId($oTenant->IdTenant, !$oTenant->IsDisabled))
					{
						$oException = $oDomainsApi->GetLastException();
						if ($oException)
						{
							throw $oException;
						}
					}
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
	 * @param int $iChannelId
	 * @return array
	 */
	public function GetTenantsIdsByChannelId($iChannelId)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetTenantsIdsByChannelId($iChannelId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iChannelId
	 * @return bool
	 */
	public function DeleteTenantsByChannelId($iChannelId)
	{
		$iResult = 1;

		$aTenantsIds = $this->GetTenantsIdsByChannelId($iChannelId);

		if (is_array($aTenantsIds))
		{
			foreach ($aTenantsIds as $iTenantId)
			{
				$oTenant = $this->GetTenantById($iTenantId);
				if ($oTenant)
				{
					$iResult &= $this->DeleteTenant($oTenant);
				}
			}
		}

		return (bool) $iResult;
	}

	/**
	 * @param CTenant $oTenant
	 *
	 * @return bool
	 */
	public function DeleteTenant(CTenant $oTenant)
	{
		$bResult = false;
		try
		{
			if ($oTenant)
			{
				/* @var $oDomainsApi CApiDomainsManager */
				$oDomainsApi = CApi::Manager('domains');
				if (!$oDomainsApi->DeleteDomainsByTenantId($oTenant->IdTenant, true))
				{
					$oException = $oDomainsApi->GetLastException();
					if ($oException)
					{
						throw $oException;
					}
				}

				$bResult = $this->oStorage->DeleteTenant($oTenant->IdTenant);
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $bResult;
	}
}
