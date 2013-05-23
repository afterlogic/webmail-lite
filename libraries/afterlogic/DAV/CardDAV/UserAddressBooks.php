<?php

namespace afterlogic\DAV\CardDAV;

class UserAddressBooks extends \Sabre\CardDAV\UserAddressBooks {


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
        return $objs;

    }	
}