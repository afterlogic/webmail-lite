<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV;

class UserAddressBooks extends \Sabre\CardDAV\UserAddressBooks {


    public function getSharedAddressBook()
	{
		return array(
			'id'  => 0,
			'uri' => \afterlogic\DAV\Constants::ADDRESSBOOK_SHARED_WITH_ALL_NAME,
			'principaluri' => $this->principalUri,
			'{DAV:}displayname' => \afterlogic\DAV\Constants::ADDRESSBOOK_SHARED_WITH_ALL_DISPLAY_NAME,
			'{' . \Sabre\CardDAV\Plugin::NS_CARDDAV . '}addressbook-description' => '',
			'{http://calendarserver.org/ns/}getctag' => 0,
			'{' . \Sabre\CardDAV\Plugin::NS_CARDDAV . '}supported-address-data' =>
				new \Sabre\CardDAV\Property\SupportedAddressData(),
			
			// some specific properies for shared addressbooks
//			'{http://calendarserver.org/ns/}shared-to-all' => true
		);
	}
	
	/**
     * Returns a list of addressbooks
     *
     * @return array
     */
    public function getChildren() {

        $addressbooks = $this->carddavBackend->getAddressbooksForUser($this->principalUri);
        $objs = array();
        foreach($addressbooks as $addressbook) {
            $objs[] = new AddressBook($this->carddavBackend, $addressbook);
        }
		
		$objs[] = new SharedAddressBook($this->carddavBackend, $this->getSharedAddressBook(), $this->principalUri);
		
        return $objs;

    }	
}