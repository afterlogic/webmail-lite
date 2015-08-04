<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV;

class UserAddressBooks extends \Sabre\CardDAV\UserAddressBooks {

	/**
     * Returns a list of addressbooks
     *
     * @return array
     */
    public function getChildren() 
	{
        $objs = array();
		/* @var $oApiCapaManager \CApiCapabilityManager */
		$oApiCapaManager = \CApi::Manager('capability');
		
		$addressbooks = $this->carddavBackend->getAddressbooksForUser($this->principalUri);
		foreach($addressbooks as $addressbook) 
		{
			$objs[] = new AddressBook($this->carddavBackend, $addressbook);
		}
		if ($oApiCapaManager->isCollaborationSupported())
		{
			$sharedAddressbook = $this->carddavBackend->getSharedAddressBook($this->principalUri);
			$objs[] = new SharedAddressBook($this->carddavBackend, $sharedAddressbook, $this->principalUri);
		}
        return $objs;

    }	
}