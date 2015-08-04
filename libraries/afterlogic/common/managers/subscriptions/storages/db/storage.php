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
class CApiSubscriptionsDbStorage extends CApiSubscriptionsStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * @var CApiSubscriptionsCommandCreatorMySQL
	 */
	protected $oCommandCreator;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('db', $oManager);

		$this->oConnection =& $oManager->GetConnection();
		$this->oCommandCreator =& $oManager->GetCommandCreator(
			$this, array(
				EDbType::MySQL => 'CApiSubscriptionsCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiSubscriptionsCommandCreatorPostgreSQL'
			)
		);
	}

	/**
	 * @param CSubscription $oSubscription
	 *
	 * @return bool
	 */
	public function createSubscription(&$oSubscription)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createSubscription($oSubscription)))
		{
			$bResult = true;
			$oSubscription->IdSubscription = $this->oConnection->GetLastInsertId('awm_subscriptions', 'id_subscription');
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CSubscription $oSubscription
	 *
	 * @return bool
	 */
	public function updateSubscription($oSubscription)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->updateSubscription($oSubscription));

		$this->throwDbExceptionIfExist();
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
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->deleteSubscription($iTenantID, $iSubscriptionID));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantID
	 *
	 * @return array|bool
	 */
	public function getSubscriptions($iTenantID)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getSubscriptions($iTenantID)))
		{
			$oRow = null;
			$mResult = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				if ($oRow && isset($oRow->id_tenant) && $iTenantID === (int) $oRow->id_tenant)
				{
					$oSubscription = new CSubscription($iTenantID);
					$oSubscription->InitByDbRow($oRow);

					$mResult[] = $oSubscription;
				}
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param int $iSubscriptionID
	 *
	 * @return array|bool
	 */
	public function getSubscriptionById($iSubscriptionID)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getSubscriptionById($iSubscriptionID)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow && isset($oRow->id_tenant) && 0 < (int) $oRow->id_tenant)
			{
				$oSubscription = new CSubscription((int) $oRow->id_tenant);
				$oSubscription->InitByDbRow($oRow);

				$mResult = $oSubscription;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}
}
