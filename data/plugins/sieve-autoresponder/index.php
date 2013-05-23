<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class_exists('CApi') or die();

CApi::Inc('common.plugins.autoresponder');

class CSieveAutoResponderPlugin extends AApiAutoResponderPlugin
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
	protected function validateIfAccountCanChangeAutoresponder($oAccount)
	{
		$bResult = false;
		if ($oAccount instanceof CAccount)
		{
			$aDomains = CApi::GetConf('plugins.sieve-autoresponder.options.domains', null);
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
	 * @return array [enabled, subject, body]  | false
	 */
	protected function getAutoresponder($oAccount)
	{
		return $this->getSieveManager()->GetAutoresponder($oAccount);
	}
	
	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	protected function disableAutoresponder($oAccount)
	{
		return $this->getSieveManager()->DisableAutoresponder($oAccount);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sSubject
	 * @param string $sMessage
	 * @return bool
	 */
	protected function setAutoresponder($oAccount, $sSubject, $sMessage)
	{
		return $this->getSieveManager()->SetAutoresponder($oAccount, $sMessage, $sSubject, true);
	}
}

return new CSieveAutoResponderPlugin($this);
