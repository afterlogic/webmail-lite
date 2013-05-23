<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

namespace afterlogic\DAV\CardDAV;

class GAddressBooks extends \Sabre\DAV\Collection implements \Sabre\CardDAV\IDirectory, \Sabre\DAV\IProperties {

	/**
	 * @var $apiGcontactsManager CApiGcontactsManager
	 */
	private $apiGcontactsManager;

	/**
	 * @var $apiUsersManager CApiUsersManager
	 */
	private $apiUsersManager;

    /**
	 * @var \Sabre\DAV\Auth\Plugin
     */
    private $authPlugin;

    /**
	 * @var string
     */
    private $name;

    /**
	 * @var array
     */
	private $addressBookInfo;

    /**
	 * @var CAccount
     */
	private $account;
	
	/**
     * Constructor
     */
    public function __construct(\Sabre\DAV\Auth\Plugin $authPlugin, $name, $displayname = '')
	{
		$this->apiUsersManager = \CApi::Manager('users');
		$this->authPlugin = $authPlugin;
		$this->name = $name;
		$this->account = null;
		if (empty($displayname))
		{
			$displayname = $name;
		}
		$this->addressBookInfo['{DAV:}displayname'] = $displayname;
    }


	public function getAccount()
	{
		if ($this->account == null)
		{
			$sUser = $this->authPlugin->getCurrentUser();
			if (!empty($sUser))
			{
				$this->account = $this->apiUsersManager->GetAccountOnLogin($sUser);
			}
		}
		return $this->account;
	}

	/**
     * @return string
     */
    public function getName()
	{
        return $this->name;
    }

    /**
     * @return array
     */
    public function getChildren()
	{
		$oAccount = $this->getAccount();
        $aCards = array();
		if (isset($oAccount) && $oAccount->User->GetCapa('GAB'))
		{
			$aContacts = array();
			$oApiCollaborationManager = \CApi::Manager('collaboration');
			if ($oApiCollaborationManager)
			{
				$this->apiGcontactsManager = $oApiCollaborationManager->GetGlobalContactsManager();
				$aContacts = $this->apiGcontactsManager->GetContactItems($oAccount,
					\EContactSortField::EMail, \ESortOrder::ASC, 0, 9999);
			}

			foreach($aContacts as $oContact)
			{
				$vCard = new \Sabre\VObject\Component('VCARD');
				$vCard->VERSION = '3.0';
				$vCard->UID = $oContact->Id;
	            $vCard->EMAIL = $oContact->Email;
		        $vCard->FN = $oContact->Name;

				$aCards[] = new GCard(
					array(
						'uri' => md5($oContact->Email .'-'. $oContact->Id) . '.vcf',
						'carddata' => $vCard->serialize(),
						'lastmodified' => strtotime('2001-01-01 00:00:00')
					)
				);
			}
		}
        return $aCards;
    }

    public function getProperties($properties) {

        $response = array();
        foreach($properties as $propertyName) {

            if (isset($this->addressBookInfo[$propertyName])) {

                $response[$propertyName] = $this->addressBookInfo[$propertyName];

            }

        }

        return $response;

    }

	/* @param array $mutations
     * @return bool|array
     */
    public function updateProperties($mutations) {

        return false;

    }
}
