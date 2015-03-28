<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Subscriptions
 */
class CApiSubscriptionsManager extends AApiManagerWithStorage
{
	/**
	 * @var CApiTenantsManager
	 */
	private $oTenants;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('subscriptions', $oManager, $sForcedStorage);

		$this->inc('classes.subscription');

		$this->oTenants  = null;
	}

	/**
	 * @return CSubscription|null
	 */
	public function TenantManager()
	{
		if (null === $this->oTenants)
		{
			$this->oTenants = CApi::Manager('tenants');
		}

		return $this->oTenants;
	}

	/**
	 * @param CSubscription $oSubscription
	 *
	 * @return bool
	 */
	public function CreateSubscription(&$oSubscription)
	{
		$bResult = false;
		try
		{
			if ($oSubscription->Validate())
			{
				$bResult = $this->oStorage->CreateSubscription($oSubscription);
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

//		if ($bResult)
//		{
//			$oTenant = $this->TenantManager();
//			if ($oTenant)
//			{
//				$oTenant->UpdateTenantMainCapa($oSubscription->IdTenant);
//			}
//		}

		return $bResult;
	}

	/**
	 * @param CSubscription $oSubscription
	 *
	 * @return bool
	 */
	public function UpdateSubscription($oSubscription)
	{
		$bResult = false;
		try
		{
			if ($oSubscription->Validate())
			{
				$bResult = $this->oStorage->CreateSubscription($oSubscription);
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

//		if ($bResult)
//		{
//			$oTenant = $this->TenantManager();
//			if ($oTenant)
//			{
//				$oTenant->UpdateTenantMainCapa($oSubscription->IdTenant);
//			}
//		}

		return $bResult;
	}
	
	/**
	 * @param int $iTenantID
	 * @param int $iSubscriptionID
	 *
	 * @return bool
	 */
	public function DeleteSubscription($iTenantID, $iSubscriptionID)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteSubscription($iTenantID, $iSubscriptionID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

//		if ($bResult)
//		{
//			$oTenant = $this->TenantManager();
//			if ($oTenant)
//			{
//				$oTenant->UpdateTenantMainCapa($iTenantID);
//			}
//		}

		return $bResult;
	}

	/**
	 * @param int $iTenantID
	 *
	 * @return array|bool
	 */
	public function GetSubscriptions($iTenantID)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->GetSubscriptions($iTenantID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iTenantID
	 *
	 * @return CSubscription|bool|null
	 */
	public function GetSubscriptionById($iSubscriptionID)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetSubscriptionById($iSubscriptionID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}
}
