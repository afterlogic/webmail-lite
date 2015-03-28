<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class_exists('CApi') or die();

CApi::Inc('common.plugins.change-password');

class CPoppassdChangePasswordPlugin extends AApiChangePasswordPlugin
{
	/**
	 * @var CApiPoppassdProtocol
	 */
	protected $oPopPassD;

	/**
	 * @var CDomain
	 */
	protected $oDefaultDomain;

	/**
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct(CApiPluginManager $oPluginManager)
	{
		parent::__construct('1.0', $oPluginManager);

		$this->oPopPassD = null;
		$this->oDefaultDomain = null;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	protected function validateIfAccountCanChangePassword($oAccount)
	{
		if (null === $this->oDefaultDomain)
		{
			/* @var $oApiDomainsManager CApiDomainsManager */
			$oApiDomainsManager = CApi::Manager('domains');
			if ($oApiDomainsManager)
			{
				$this->oDefaultDomain = $oApiDomainsManager->GetDefaultDomain();
			}
		}

		return (($oAccount instanceof CAccount) && $this->oDefaultDomain &&
			$this->oDefaultDomain->IncomingMailServer === $oAccount->IncomingMailServer);
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function ChangePasswordProcess($oAccount)
	{
		if (0 < strlen($oAccount->PreviousMailPassword) &&
			$oAccount->PreviousMailPassword !== $oAccount->IncomingMailPassword)
		{
			if (null === $this->oPopPassD)
			{
				CApi::Inc('common.net.protocols.poppassd');

				$this->oPopPassD = new CApiPoppassdProtocol(
					CApi::GetConf('plugins.poppassd-change-password.config.host', '127.0.0.1'),
					CApi::GetConf('plugins.poppassd-change-password.config.port', 106)
				);
			}

			if ($this->oPopPassD && $this->oPopPassD->Connect())
			{
				try
				{
//					if ($this->oPopPassD->Login(api_Utils::GetAccountNameFromEmail($oAccount->IncomingMailLogin), $oAccount->PreviousMailPassword))
					if ($this->oPopPassD->Login($oAccount->IncomingMailLogin, $oAccount->PreviousMailPassword))
					{
						if (!$this->oPopPassD->NewPass($oAccount->IncomingMailPassword))
						{
							throw new CApiManagerException(Errs::UserManager_AccountNewPasswordRejected);
						}
					}
					else
					{
						throw new CApiManagerException(Errs::UserManager_AccountOldPasswordNotCorrect);
					}
				}
				catch (Exception $oException)
				{
					$this->oPopPassD->Disconnect();
					throw $oException;
				}
			}
			else
			{
				throw new CApiManagerException(Errs::UserManager_AccountNewPasswordUpdateError);
			}
		}
	}
}

return new CPoppassdChangePasswordPlugin($this);
