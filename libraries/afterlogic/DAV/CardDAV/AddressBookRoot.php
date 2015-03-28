<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV;

class AddressBookRoot extends \Sabre\CardDAV\AddressBookRoot
{
	protected $oAccount = null;
	
	protected function getAccount($principalUri)
	{
		if (null === $this->oAccount)
		{
			$this->oAccount = \afterlogic\DAV\Utils::GetAccountByLogin(basename($principalUri));
		}
		return $this->oAccount;
	}

	public function getChildForPrincipal(array $principal)
	{
		$oApiCapabilityManager = /* @var \CApiCapabilityManager */ \CApi::Manager('capability');
		
		$oAccount = $this->getAccount($principal['uri']);
		if ($oAccount instanceof \CAccount &&
			$oApiCapabilityManager->IsPersonalContactsSupported($oAccount))
		{
			return new UserAddressBooks($this->carddavBackend, $principal['uri']);
		}
		else
		{
			return new EmptyAddressBooks($this->carddavBackend, $principal['uri']);
		}
    }

}
