<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class_exists('CApi') or die();

CApi::Inc('common.plugins.forward');

class CSieveForwardPlugin extends AApiForwardPlugin
{
	/**
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct(CApiPluginManager $oPluginManager)
	{
		parent::__construct('1.0', $oPluginManager);
	}
	
	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	protected function validateIfAccountCanUseForward($oAccount)
	{
		$bResult = false;
		if ($oAccount instanceof CAccount)
		{
			$aDomains = CApi::GetConf('plugins.sieve-forward.options.domains', null);
			if (
				($oAccount->IsInternal && CApi::GetConf('mailsuite', false)) ||
				(is_array($aDomains) &&
					(
						(1 === count($aDomains) && '*' === $aDomains[0]) ||
						(in_array(api_Utils::GetDomainFromEmail($oAccount->Email), $aDomains))
					)
				)
			)
			{
				$bResult = true;
			}
		}
		
		return $bResult;
	}
		
	/**
	 * @param CAccount $oAccount
	 * @return array [enabled, email] | false
	 */
	protected function getForward($oAccount)
	{
		return $this->getSieveManager()->GetForward($oAccount);
	}
	
	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	protected function disableForward($oAccount)
	{
		return $this->getSieveManager()->DisableForward($oAccount);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sForwardEmail
	 * @return bool
	 */
	protected function setForward($oAccount, $sForwardEmail)
	{
		return $this->getSieveManager()->SetForward($oAccount, $sForwardEmail, true);
	}
}

return new CSieveForwardPlugin($this);
