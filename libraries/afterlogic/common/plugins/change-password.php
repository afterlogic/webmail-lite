<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Api
 */
abstract class AApiChangePasswordPlugin extends AApiPlugin
{
	/**
	 * @param string $sVersion
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct($sVersion, CApiPluginManager $oPluginManager)
	{
		parent::__construct($sVersion, $oPluginManager);

		$this->AddHook('api-change-account-by-id', 'PluginChangeAccountById');
		$this->AddHook('api-update-account', 'PluginUpdateAccount');
	}

	abstract protected function validateIfAccountCanChangePassword($oAccount);

	abstract public function ChangePasswordProcess($oAccount);

	/**
	 * @param CAccount $oAccount
	 * @param bool $bUseOnlyHookUpdate
	 */
	public function PluginUpdateAccount(&$oAccount, &$bUseOnlyHookUpdate)
	{
		if ($this->validateIfAccountCanChangePassword($oAccount) && $oAccount->IsEnabledExtension(CAccount::ChangePasswordExtension))
		{
			$this->ChangePasswordProcess($oAccount);
		}
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function PluginChangeAccountById(&$oAccount)
	{
		if ($this->validateIfAccountCanChangePassword($oAccount))
		{
			if ($oAccount)
			{
				$oAccount->EnableExtension(CAccount::ChangePasswordExtension);
			}
		}
	}
}
