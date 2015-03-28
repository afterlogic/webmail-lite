<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
		$this->inc('classes.socials');
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
	 * @return int|false
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
	 * @return int
	 */
	public function GetTenantAllocatedSize($iTenantId)
	{
		$iResult = 0;
		if (0 < $iTenantId)
		{
			try
			{
				$iResult = $this->oStorage->GetTenantAllocatedSize($iTenantId);
			}
			catch (CApiBaseException $oException)
			{
				$this->setLastException($oException);
			}
		}

		return $iResult;
	}

	/**
	 * @return CTenant
	 */
	public function GetDefaultGlobalTenant()
	{
		$oTenant = new CTenant();
		$oTenant->IsDefault = true;
		$oTenant->SipAllowConfiguration = true;
		$oTenant->TwilioAllowConfiguration = true;

		$oTenant->HelpdeskAdminEmailAccount = $this->oSettings->GetConf('Helpdesk/AdminEmailAccount');
		$oTenant->HelpdeskClientIframeUrl = $this->oSettings->GetConf('Helpdesk/ClientIframeUrl');
		$oTenant->HelpdeskAgentIframeUrl = $this->oSettings->GetConf('Helpdesk/AgentIframeUrl');
		$oTenant->HelpdeskSiteName = $this->oSettings->GetConf('Helpdesk/SiteName');
		$oTenant->HelpdeskStyleAllow = $this->oSettings->GetConf('Helpdesk/StyleAllow');
		$oTenant->HelpdeskStyleImage = $this->oSettings->GetConf('Helpdesk/StyleImage');
		$oTenant->HelpdeskStyleText = $this->oSettings->GetConf('Helpdesk/StyleText');
		
		$oTenant->HelpdeskFetcherType = $this->oSettings->GetConf('Helpdesk/FetcherType');

		$oTenant->LoginStyleImage = $this->oSettings->GetConf('Common/LoginStyleImage');
		$oTenant->AppStyleImage = $this->oSettings->GetConf('Common/AppStyleImage');

		return $oTenant;
	}

	/**
	 * @param mixed $mTenantId
	 * @param bool $bIdIsHash = false
	 *
	 * @return CTenant
	 */
	public function GetTenantById($mTenantId, $bIdIsHash = false)
	{
		$oTenant = null;
		try
		{
			$oTenant = $this->oStorage->GetTenantById($mTenantId, $bIdIsHash);
			if ($oTenant)
			{
				/* @var $oTenant CTenant */
				
				$mTenantId = $oTenant->IdTenant;

				$iFilesUsageInMB = 0;
				if (0 < strlen($oTenant->FilesUsageInBytes))
				{
					$iFilesUsageInMB = (int) ($oTenant->FilesUsageInBytes / (1024 * 1024));
				}

				$oTenant->AllocatedSpaceInMB = $this->GetTenantAllocatedSize($mTenantId) + $iFilesUsageInMB;
				$oTenant->FlushObsolete('AllocatedSpaceInMB');

				$oTenant->FilesUsageInMB = $iFilesUsageInMB;
				$oTenant->FlushObsolete('FilesUsageInMB');

				if (0 < $oTenant->QuotaInMB)
				{
					$oTenant->FilesUsageDynamicQuotaInMB = $oTenant->QuotaInMB - $oTenant->AllocatedSpaceInMB + $oTenant->FilesUsageInMB;
					$oTenant->FilesUsageDynamicQuotaInMB = 0 < $oTenant->FilesUsageDynamicQuotaInMB ?
						$oTenant->FilesUsageDynamicQuotaInMB : 0;
					$oTenant->FlushObsolete('FilesUsageDynamicQuotaInMB');
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $oTenant;
	}

	/**
	 * @param string $sTenantHash
	 *
	 * @return CTenant
	 */
	public function GetTenantByHash($sTenantHash)
	{
		return $this->GetTenantById($sTenantHash, true);
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
	 * @param string $iDomainId
	 *
	 * @return int
	 */
	public function GetTenantIdByDomainId($iDomainId)
	{
		$iTenantId = 0;
		try
		{
			if (0 < $iDomainId)
			{
				$iTenantId = $this->oStorage->GetTenantIdByDomainId($iDomainId);
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
		$bResult = $oTenant->IsDefault;
		if (!$bResult)
		{
			try
			{
				$bResult = $this->oStorage->TenantExists($oTenant);
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
	 *
	 * @return array|bool
	 */
	public function GetTenantDomains($iTenantId)
	{
		$mResult = false;
		if (0 < $iTenantId)
		{
			try
			{
				$mResult = $this->oStorage->GetTenantDomains($iTenantId);
			}
			catch (CApiBaseException $oException)
			{
				$this->setLastException($oException);
			}
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
			if ($oTenant->Validate() && !$oTenant->IsDefault)
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
				if ($oTenant->IsDefault && 0 === $oTenant->IdTenant)
				{
					$this->oSettings->SetConf('Helpdesk/AdminEmailAccount', $oTenant->HelpdeskAdminEmailAccount);
					$this->oSettings->SetConf('Helpdesk/ClientIframeUrl', $oTenant->HelpdeskClientIframeUrl);
					$this->oSettings->SetConf('Helpdesk/AgentIframeUrl', $oTenant->HelpdeskAgentIframeUrl);
					$this->oSettings->SetConf('Helpdesk/SiteName', $oTenant->HelpdeskSiteName);
					$this->oSettings->SetConf('Helpdesk/StyleAllow', $oTenant->HelpdeskStyleAllow);
					$this->oSettings->SetConf('Helpdesk/StyleImage', $oTenant->HelpdeskStyleImage);
					$this->oSettings->SetConf('Helpdesk/StyleText', $oTenant->HelpdeskStyleText);

					$this->oSettings->SetConf('Helpdesk/FetcherType', $oTenant->HelpdeskFetcherType);

					$this->oSettings->SetConf('Common/LoginStyleImage', $oTenant->LoginStyleImage);
					$this->oSettings->SetConf('Common/AppStyleImage', $oTenant->AppStyleImage);

					$this->oSettings->SetConf('Helpdesk/FacebookAllow', $oTenant->HelpdeskFacebookAllow);
					$this->oSettings->SetConf('Helpdesk/FacebookId', $oTenant->HelpdeskFacebookId);
					$this->oSettings->SetConf('Helpdesk/FacebookSecret', $oTenant->HelpdeskFacebookSecret);
					$this->oSettings->SetConf('Helpdesk/GoogleAllow', $oTenant->HelpdeskGoogleAllow);
					$this->oSettings->SetConf('Helpdesk/GoogleId', $oTenant->HelpdeskGoogleId);
					$this->oSettings->SetConf('Helpdesk/GoogleSecret', $oTenant->HelpdeskGoogleSecret);
					$this->oSettings->SetConf('Helpdesk/TwitterAllow', $oTenant->HelpdeskTwitterAllow);
					$this->oSettings->SetConf('Helpdesk/TwitterId', $oTenant->HelpdeskTwitterId);
					$this->oSettings->SetConf('Helpdesk/TwitterSecret', $oTenant->HelpdeskTwitterSecret);

					$this->oSettings->SetConf('Sip/AllowSip', $oTenant->SipAllow);
					$this->oSettings->SetConf('Sip/Realm', $oTenant->SipRealm);
					$this->oSettings->SetConf('Sip/WebsocketProxyUrl', $oTenant->SipWebsocketProxyUrl);
					$this->oSettings->SetConf('Sip/OutboundProxyUrl', $oTenant->SipOutboundProxyUrl);
					$this->oSettings->SetConf('Sip/CallerID', $oTenant->SipCallerID);

					$this->oSettings->SetConf('Twilio/AllowTwilio', $oTenant->TwilioAllow);
					$this->oSettings->SetConf('Twilio/PhoneNumber', $oTenant->TwilioPhoneNumber);
					$this->oSettings->SetConf('Twilio/AccountSID', $oTenant->TwilioAccountSID);
					$this->oSettings->SetConf('Twilio/AuthToken', $oTenant->TwilioAuthToken);
					$this->oSettings->SetConf('Twilio/AppSID', $oTenant->TwilioAppSID);
					$this->oSettings->SetConf('Socials', $oTenant->Socials);

					$bResult = $this->oSettings->SaveToXml();
				}
				else
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
			}

			$bResult = true;

//			$this->UpdateTenantMainCapa($oTenant->IdTenant);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @todo
	 * @param int $iTenantID
	 */
	public function UpdateTenantMainCapa($iTenantID)
	{
		$bResult = false;
		if (0 < $iTenantID)
		{
			// TODO subscriptions
//			$aCapa = array();
//			$oSubscriptionsApi = /* @var $oSubscriptionsApi CApiSubscriptionsManager */ CApi::Manager('subscriptions');
//			$aSubs = $oSubscriptionsApi ? $oSubscriptionsApi->GetSubscriptions($iTenantID) : null;
//			if (is_array($aSubs) && 0 < count($aSubs))
//			{
//				foreach ($aSubs as /* @var $oSub CSubscription */ $oSub)
//				{
//					if ($oSub)
//					{
//						$sCapa = $oSub->Capa;
//						if ('' === $sCapa)
//						{
//							$aCapa = array();
//							break;
//						}
//						else
//						{
//							if (0 < strlen($oSub->Capa))
//							{
//								$aCapa = array_merge($aCapa, explode(' ', $oSub->Capa));
//							}
//						}
//					}
//				}
//			}
//
//			try
//			{
//				$aCapa = array_unique($aCapa);
//				$bResult = $this->oStorage->UpdateTenantMainCapa($iTenantID, implode(' ', $aCapa));
//			}
//			catch (CApiBaseException $oException)
//			{
//				$this->setLastException($oException);
//			}
		}
		
		return $bResult;
	}

	/**
	 * @param CTenant $oTenant
	 * @param int $iNewAllocatedSizeInBytes
	 *
	 * @return bool
	 */
	public function TryToAllocateFileUsage($oTenant, $iNewAllocatedSizeInBytes)
	{
		try
		{
			if ($oTenant && 0 < $oTenant->IdTenant)
			{
				$iNewUsedInMB = (int) round($iNewAllocatedSizeInBytes / (1024 * 1024));

				if (0 < $oTenant->QuotaInMB && $oTenant->FilesUsageDynamicQuotaInMB < $iNewUsedInMB)
				{
					return false;
				}
				else
				{
					return $this->oStorage->AllocateFileUsage($oTenant, $iNewAllocatedSizeInBytes);
				}

				return true;
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return false;
	}

	/**
	 * @param int $iTenantID
	 * @param int|null $iExceptUserId = null
	 *
	 * @return array|bool
	 */
	public function GetSubscriptionUserUsage($iTenantID, $iExceptUserId = null)
	{
		$mResult = false;
		if (0 < $iTenantID)
		{
			$mResult = $this->oStorage->GetSubscriptionUserUsage($iTenantID, $iExceptUserId);
		}

		return $mResult;
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
				if (0 < $iTenantId)
				{
					$oTenant = $this->GetTenantById($iTenantId);
					if ($oTenant)
					{
						$iResult &= $this->DeleteTenant($oTenant);
					}
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
			if ($oTenant && !$oTenant->IsDefault)
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
				// TODO subscriptions
//				if ($bResult)
//				{
//					$this->oStorage->DeleteTenantSubscriptions($oTenant->IdTenant);
//				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $bResult;
	}
	
	/**
	 * @param int $iIdTenant
	 *
	 * @return array | false
	 */
	public function GetSocials($iIdTenant)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetSocials($iIdTenant);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}	
	
	/**
	 * @param int $iIdSocial
	 *
	 * @return CTenantSocials
	 */
	public function GetSocialById($iIdSocial)
	{
		$oSocial = null;
		try
		{
			$oSocial = $this->oStorage->GetSocialById($iIdSocial);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $oSocial;
	}		
	
	/**
	 * @param int $iIdTenant
	 * @param string $sSocialName
	 *
	 * @return CTenantSocials
	 */
	public function GetSocialByName($iIdTenant, $sSocialName)
	{
		$oSocial = null;
		try
		{
			$oSocial = $this->oStorage->GetSocialByName($iIdTenant, $sSocialName);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $oSocial;
	}
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function SocialExists(CTenantSocials $oSocial)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->SocialExists($oSocial);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $bResult;
	}	
	
	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function CreateSocial(CTenantSocials $oSocial)
	{
		$bResult = false;
		try
		{
			if (!$this->SocialExists($oSocial))
			{
				if (!$this->oStorage->CreateTenant($oSocial))
				{
					throw new CApiManagerException(Errs::TenantsManager_TenantCreateFailed);
				}
			}
			else
			{
				throw new CApiManagerException(Errs::TenantsManager_TenantAlreadyExists);
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
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function DeleteSocial(CTenantSocials $oSocial)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteSocial($oSocial->Id);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}
	
	/**
	 * @param int $iTenanatId
	 *
	 * @return bool
	 */
	public function DeleteSocialsByTenantId($iTenanatId)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteSocialsByTenantId($iTenanatId);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CTenantSocials $oSocial
	 *
	 * @return bool
	 */
	public function UpdateSocial(CTenantSocials $oSocial)
	{
		$bResult = false;
		try
		{
			if (!$this->oStorage->UpdateSocial($oSocial))
			{
				throw new CApiManagerException(Errs::TenantsManager_TenantUpdateFailed);
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
	
}
