<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV;

class AddressBook extends \Sabre\CardDAV\AddressBook {

	/* @var $oApiContactsManager \CApiContactsManager */
	protected $oApiContactsManager;

	public function getContactsManager()
	{
		if (!isset($this->oApiContactsManager))
		{
			$this->oApiContactsManager = \CApi::Manager('contacts');
		}
		return $this->oApiContactsManager;
	}

	/**
     * Returns the full list of cards
     *
     * @return array
     */
    public function getChildren() {

		$objs = $this->carddavBackend->getCards($this->addressBookInfo['id']);
		$aContactIds = $this->getSharedChildrenIds();
		
        $children = array();
        foreach($objs as $obj) {
			if (!in_array($obj['uri'], $aContactIds))
			{
				$children[] = new \Sabre\CardDAV\Card($this->carddavBackend, $this->addressBookInfo, $obj);
			}
        }
        return $children;

    }
	
	/**
     * Returns the id list of shared cards
     *
     * @return array
     */
    public function getSharedChildrenIds() {

		$aContactIds = array();
		
		$oAccount = \afterlogic\DAV\Utils::getCurrentAccount();
		if ($oAccount)
		{
			$oContactsManager = $this->getContactsManager();
			if ($oContactsManager)
			{
				$aContactIds = $oContactsManager->GetSharedContactIds($oAccount->IdUser, $oAccount->IdTenant);
			}
		}
		
        return $aContactIds;

    }	
	
	/**
     * Returns the full list of cards
     *
     * @return array
     */
    public function getChildrenByOffset($iOffset = 0, $iRequestLimit = 20) {

        $objs = $this->carddavBackend->getCardsByOffset($this->addressBookInfo['id'], $iOffset, $iRequestLimit);
        $children = array();
        foreach($objs as $obj) {
            $children[] = new \Sabre\CardDAV\Card($this->carddavBackend,$this->addressBookInfo,$obj);
        }
        return $children;

    }	
}
