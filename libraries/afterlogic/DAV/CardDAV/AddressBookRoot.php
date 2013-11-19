<?php

namespace afterlogic\DAV\CardDAV;

class AddressBookRoot extends \Sabre\CardDAV\AddressBookRoot
{
	protected function getAccount($principalUri)
	{
		$oAccount = null;
		$sUser = basename($principalUri);
		if (!empty($sUser))
		{
			$apiUsersManager = \CApi::Manager('users');
			$oAccount = $apiUsersManager->GetAccountOnLogin($sUser);
		}
		return $oAccount;
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
