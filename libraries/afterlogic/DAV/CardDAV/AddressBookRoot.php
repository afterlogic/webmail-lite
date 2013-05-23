<?php

namespace afterlogic\DAV\CardDAV;

class AddressBookRoot extends \Sabre\CardDAV\AddressBookRoot {

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

	public function getChildForPrincipal(array $principal) {

		$oAccount = $this->getAccount($principal['uri']);
		if (null !== $oAccount && $oAccount->User->GetCapa('PAB'))
		{
			return new UserAddressBooks($this->carddavBackend, $principal['uri']);
		}
		else
		{
			return new EmptyAddressBooks($this->carddavBackend, $principal['uri']);
		}
    }

}
