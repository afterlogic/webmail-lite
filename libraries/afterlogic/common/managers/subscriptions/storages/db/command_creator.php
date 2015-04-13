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
class CApiSubscriptionsCommandCreator extends api_CommandCreator
{
	/**
	 * @param int $iTenantID
	 * @return string
	 */
	public function GetSubscriptions($iTenantID)
	{
		$aMap = api_AContainer::DbReadKeys(CSubscription::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_subscriptions WHERE %s = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(),
			$this->escapeColumn('id_tenant'), $iTenantID);
	}
	
	/**
	 * @param int $iSubscriptionID
	 * @return string
	 */
	public function GetSubscriptionById($iSubscriptionID)
	{
		$aMap = api_AContainer::DbReadKeys(CSubscription::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sawm_subscriptions WHERE %s = %d';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(),
			$this->escapeColumn('id_subscription'), $iSubscriptionID);
	}

	/**
	 * @param int $iTenantID
	 * @param int $iSubscriptionID
	 * @return string
	 */
	public function DeleteSubscription($iTenantID, $iSubscriptionID)
	{
		$sSql = 'DELETE FROM %sawm_subscriptions WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_tenant'), $iTenantID,
			$this->escapeColumn('id_subscription'), $iSubscriptionID);
	}

	/**
	 * @param CSubscription $oSubscription
	 * @return string
	 */
	public function CreateSubscription($oSubscription)
	{
		$aResults = api_AContainer::DbInsertArrays($oSubscription, $this->oHelper);

		if ($aResults[0] && $aResults[1])
		{
			$sSql = 'INSERT INTO %sawm_subscriptions ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CSubscription $oSubscription
	 * @return string
	 */
	public function UpdateSubscription($oSubscription)
	{
		$aResult = api_AContainer::DbUpdateArray($oSubscription, $this->oHelper);

		$sSql = 'UPDATE %sawm_fetchers SET %s WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult),
			$this->escapeColumn('id_tenant'), $oSubscription->IdTenant,
			$this->escapeColumn('id_subscription'), $oSubscription->IdSubscription);
	}
}

/**
 * @package Subscriptions
 */
class CApiSubscriptionsCommandCreatorMySQL extends CApiSubscriptionsCommandCreator
{
	
}

class CApiSubscriptionsCommandCreatorPostgreSQL  extends CApiSubscriptionsCommandCreator
{
	
}
