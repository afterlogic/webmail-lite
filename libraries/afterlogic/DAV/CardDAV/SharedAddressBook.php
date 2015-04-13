<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV;

class SharedAddressBook extends AddressBook {
    
	protected $principalUri;
	
	/* @var $oAccount \CAccount */
	protected $oAccount = null;

	/* @var $oApiUsersManager \CApiUsersManager */
	protected $oApiUsersManager;

	protected $oApiContactsManager;
	
	public function getUsersManager()
	{
		if (!isset($this->oApiUsersManager))
		{
			$this->oApiUsersManager = \CApi::Manager('users');
		}
		return $this->oApiUsersManager;
	}
	
	public function getContactsManager()
	{
		if (!isset($this->oApiContactsManager))
		{
			$this->oApiContactsManager = \CApi::Manager('contacts');
		}
		return $this->oApiContactsManager;
	}

	/**
     * Constructor
     *
     * @param Backend\BackendInterface $carddavBackend
     * @param array $addressBookInfo
     */
    public function __construct(\Sabre\CardDAV\Backend\BackendInterface $carddavBackend, array $addressBookInfo, $principalUri) {
        
		parent::__construct($carddavBackend, $addressBookInfo);
		$this->principalUri = $principalUri;
		
    }	

	public function getAccount() {
		
		if (null === $this->oAccount)
		{
			$this->oAccount = \afterlogic\DAV\Utils::GetAccountByLogin(basename($this->principalUri));
		}
		return $this->oAccount;
	}

	/**
     * Returns a card
     *
     * @param int $iUserId
     * @param string $sContactId
     * @return \Sabre\CardDAV\\ICard
     */
    public function getChildObj($iUserId, $sContactId) {
		
		$oResult = null;

		/* @var $oApiUsersManager \CApiUsersManager */
		$oApiUsersManager = $this->getUsersManager();

		/* @var $oAccount \CAccount */
		$oAccount = $oApiUsersManager->GetAccountById($oApiUsersManager->GetDefaultAccountId($iUserId));
		
		if ($oAccount)
		{
			$aAddressBook = $this->carddavBackend->getAddressBookForUser(\afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $oAccount->Email, 
					\afterlogic\DAV\Constants::ADDRESSBOOK_DEFAULT_NAME);
			if ($aAddressBook)
			{
				$obj = $this->carddavBackend->getCard($aAddressBook['id'], $sContactId);
				if (is_array($obj))
				{
					$oResult = new SharedCard($this->carddavBackend, $aAddressBook, $obj, $this->principalUri);
				}
			}
		}
		
		return $oResult;

	}
	
	/**
     * Returns a card
     *
     * @param string $name
     * @return \Sabre\CardDAV\\ICard
     */
    public function getChild($name) {

		$bResult = null;
		/* @var $oApiContactsManager \CApiContactsManager */
		$oApiContactsManager = $this->getContactsManager();
		
		$oAccount = $this->getAccount();

		/* @var $oContact \CContact */
		$oContact = $oApiContactsManager->GetContactByStrId($oAccount->IdUser, $name, $oAccount->IdTenant);
		if ($oContact)
		{
			$bResult = $this->getChildObj($oContact->IdUser, $name);
		}			
		
		if (!isset($bResult))
		{
			throw new \Sabre\DAV\Exception\NotFound('Card not found');
		}
		
        return $bResult;

    }

    /**
     * Returns the full list of cards
     *
     * @return array
     */
    public function getChildren() {

        $children = array();

		$oAccount = $this->getAccount();
		if ($oAccount)
		{
			/* @var $oApiContactsManager \CApiContactsManager */
			$oApiContactsManager = $this->getContactsManager();

			$aContactListItems = $oApiContactsManager->GetContactItems($oAccount->IdUser, \EContactSortField::EMail, \ESortOrder::ASC, 0, 999, '', '', '', $oAccount->IdTenant);
			foreach ($aContactListItems as $oContactListItem)
			{
				$child = $this->getChildObj($oContactListItem->IdUser, $oContactListItem->IdStr);
				if ($child)
				{
					$children[] = $child;
				}
			}

		}
        return $children;

    }
	
    public function createFile($name,$vcardData = null) {

        throw new \Sabre\DAV\Exception\Forbidden('Permission denied to create file (filename ' . $name . ')');

    }

    public function delete() {

        throw new \Sabre\DAV\Exception\Forbidden('Could not delete addressbook');

    }	
}
