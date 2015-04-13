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
	public function CreateSubscription(&$oSubscription)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->CreateSubscription($oSubscription)))
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
	public function UpdateSubscription($oSubscription)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->UpdateSubscription($oSubscription));

		$this->throwDbExceptionIfExist();
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
		$bResult = $this->oConnection->Execute(
			$this->oCommandCreator->DeleteSubscription($iTenantID, $iSubscriptionID));

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iTenantID
	 *
	 * @return array|bool
	 */
	public function GetSubscriptions($iTenantID)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetSubscriptions($iTenantID)))
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
	public function GetSubscriptionById($iSubscriptionID)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetSubscriptionById($iSubscriptionID)))
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
