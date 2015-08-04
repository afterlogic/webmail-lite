<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Subscriptions
 * @subpackage Storages
 */
class CApiSubscriptionsCommandCreator extends api_CommandCreator
{
	/**
	 * @param int $iTenantID
	 *
	 * @return string
	 */
	public function getSubscriptions($iTenantID)
	{
		$aMap = api_AContainer::DbReadKeys(CSubscription::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_subscriptions WHERE %s = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(),
			$this->escapeColumn('id_tenant'), $iTenantID);
	}
	
	/**
	 * @param int $iSubscriptionID
	 *
	 * @return string
	 */
	public function getSubscriptionById($iSubscriptionID)
	{
		$aMap = api_AContainer::DbReadKeys(CSubscription::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_subscriptions WHERE %s = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(),
			$this->escapeColumn('id_subscription'), $iSubscriptionID);
	}

	/**
	 * @param int $iTenantID
	 * @param int $iSubscriptionID
	 *
	 * @return string
	 */
	public function deleteSubscription($iTenantID, $iSubscriptionID)
	{
		$sSql = 'DELETE FROM %sawm_subscriptions WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('id_tenant'), $iTenantID,
			$this->escapeColumn('id_subscription'), $iSubscriptionID);
	}

	/**
	 * @param CSubscription $oSubscription
	 *
	 * @return string
	 */
	public function createSubscription($oSubscription)
	{
		$aResults = api_AContainer::DbInsertArrays($oSubscription, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_subscriptions ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CSubscription $oSubscription
	 *
	 * @return string
	 */
	public function updateSubscription($oSubscription)
	{
		$aResult = api_AContainer::DbUpdateArray($oSubscription, $this->oHelper);

		$sSql = 'UPDATE %sawm_fetchers SET %s WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult),
			$this->escapeColumn('id_tenant'), $oSubscription->IdTenant,
			$this->escapeColumn('id_subscription'), $oSubscription->IdSubscription);
	}
}

/**
 * @package Subscriptions
 * @subpackage Storages
 */
class CApiSubscriptionsCommandCreatorMySQL extends CApiSubscriptionsCommandCreator
{
	
}

/**
 * @package Subscriptions
 * @subpackage Storages
 */
class CApiSubscriptionsCommandCreatorPostgreSQL  extends CApiSubscriptionsCommandCreator
{
	
}
