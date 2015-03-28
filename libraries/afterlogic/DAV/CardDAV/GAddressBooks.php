<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV;

class GAddressBooks extends \Sabre\DAV\Collection implements \Sabre\CardDAV\IDirectory, \Sabre\DAV\IProperties {

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
    public function __construct($name, $displayname = '')
	{
		$this->name = $name;
		$this->account = null;

		$this->addressBookInfo['{DAV:}displayname'] = (empty($displayname)) ? $name : $displayname;
    }


	public function getAccount()
	{
		if ($this->account == null)
		{
			$sUser = \afterlogic\DAV\Auth\Backend::getInstance()->getCurrentUser();
			$this->account = \afterlogic\DAV\Utils::GetAccountByLogin($sUser);
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

		$oApiCapabilityManager = /* @var \CApiCapabilityManager */ \CApi::Manager('capability');

		if ($oAccount instanceof \CAccount &&
			$oApiCapabilityManager->IsGlobalContactsSupported($oAccount))
		{
			$aContacts = array();
			$oApiGcontactManager = /* @var \CApiGcontactsManager */ \CApi::Manager('gcontacts');
			if ($oApiGcontactManager)
			{
				$aContacts = $oApiGcontactManager->GetContactItems($oAccount,
					\EContactSortField::EMail, \ESortOrder::ASC, 0, 9999);
			}

			foreach($aContacts as $oContact)
			{
				$sUID = md5($oContact->Email .'-'. $oContact->Id);
				$vCard = new \Sabre\VObject\Component\VCard(
					array(
						'VERSION' => '3.0',
						'UID' => $sUID,
						'FN' => $oContact->Name,
					)
				);
				$vCard->add(
					'EMAIL',
					$oContact->Email,
					array(
						'type' => array(
							'work'
						),
						'pref' => 1,
					)
				);				

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
