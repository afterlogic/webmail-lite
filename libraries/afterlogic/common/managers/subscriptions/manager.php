<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiSubscriptionsManager class summary
 * 
 * @package Subscriptions
 */
class CApiSubscriptionsManager extends AApiManagerWithStorage
{
	/**
	 * @var $oApiTenantsManager CApiTenantsManager
	 */
	private $oApiTenantsManager;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('subscriptions', $oManager, $sForcedStorage);

		$this->inc('classes.subscription');

		$this->oApiTenantsManager  = null;
	}

	/**
	 * @return CApiTenantsManager|null
	 */
	private function _getApiTenantsManager()
	{
		if (null === $this->oApiTenantsManager)
		{
			$this->oApiTenantsManager = CApi::Manager('tenants');
		}

		return $this->oApiTenantsManager;
	}

	/**
	 * @param CSubscription $oSubscription
	 *
	 * @return bool
	 */
	public function createSubscription(&$oSubscription)
	{
		$bResult = false;
		try
		{
			if ($oSubscription->validate())
			{
				$bResult = $this->oStorage->createSubscription($oSubscription);
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CSubscription $oSubscription 
	 *
	 * @return bool
	 */
	public function updateSubscription($oSubscription)
	{
		$bResult = false;
		try
		{
			if ($oSubscription->validate())
			{
				$bResult = $this->oStorage->createSubscription($oSubscription);
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $bResult;
	}
	
	/**
	 * @param int $iTenantID
	 * @param int $iSubscriptionID
	 *
	 * @return bool
	 */
	public function deleteSubscription($iTenantID, $iSubscriptionID)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteSubscription($iTenantID, $iSubscriptionID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param int $iTenantID
	 *
	 * @return array|bool
	 */
	public function getSubscriptions($iTenantID)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oStorage->getSubscriptions($iTenantID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		
		return $aResult;
	}

	/**
	 * @param int $iSubscriptionID
	 *
	 * @return CSubscription|bool|null
	 */
	public function getSubscriptionById($iSubscriptionID)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getSubscriptionById($iSubscriptionID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}
}
