<?php

namespace afterlogic\DAV\CardDAV;

class AddressBook extends \Sabre\CardDAV\AddressBook {

    /**
     * Returns the full list of cards
     *
     * @return array
     */
    public function getChildrenByOffset($iOffset = 0, $iRequestLimit=20) {

        $objs = $this->carddavBackend->getCardsByOffset($this->addressBookInfo['id'], $iOffset, $iRequestLimit);
        $children = array();
        foreach($objs as $obj) {
            $children[] = new \Sabre\CardDAV\Card($this->carddavBackend,$this->addressBookInfo,$obj);
        }
        return $children;

    }	
}
