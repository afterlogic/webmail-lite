<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV;

class GAddressBooks extends \Sabre\DAV\Collection implements \Sabre\CardDAV\IDirectory, \Sabre\DAV\IProperties, \Sabre\DAVACL\IACL {

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
			$oApiCapabilityManager->isGlobalContactsSupported($oAccount))
		{
			$aContacts = array();
			$oApiGcontactManager = /* @var \CApiGcontactsManager */ \CApi::Manager('gcontacts');
			if ($oApiGcontactManager)
			{
				$aContacts = $oApiGcontactManager->getContactItems($oAccount,
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
						'uri' => $sUID . '.vcf',
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
	
    /**
     * Returns the owner principal
     *
     * This must be a url to a principal, or null if there's no owner
     *
     * @return string|null
     */
    public function getOwner() {

		$oAccount = $this->getAccount();
		return ($oAccount) ? 'principals/' . $oAccount->Email : null;

    }

    /**
     * Returns a group principal
     *
     * This must be a url to a principal, or null if there's no owner
     *
     * @return string|null
     */
    public function getGroup() {

        return null;

    }

    /**
     * Returns a list of ACE's for this node.
     *
     * Each ACE has the following properties:
     *   * 'privilege', a string such as {DAV:}read or {DAV:}write. These are
     *     currently the only supported privileges
     *   * 'principal', a url to the principal who owns the node
     *   * 'protected' (optional), indicating that this ACE is not allowed to
     *      be updated.
     *
     * @return array
     */
    public function getACL() {

		$oAccount = $this->getAccount();
        return array(
            array(
                'privilege' => '{DAV:}read',
                'principal' => ($oAccount) ? 'principals/' . $oAccount->Email : null,
                'protected' => true,
            ),
        );

    }

    /**
     * Updates the ACL
     *
     * This method will receive a list of new ACE's.
     *
     * @param array $acl
     * @return void
     */
    public function setACL(array $acl) {

        throw new DAV\Exception\MethodNotAllowed('Changing ACL is not yet supported');

    }

    /**
     * Returns the list of supported privileges for this node.
     *
     * The returned data structure is a list of nested privileges.
     * See Sabre\DAVACL\Plugin::getDefaultSupportedPrivilegeSet for a simple
     * standard structure.
     *
     * If null is returned from this method, the default privilege set is used,
     * which is fine for most common usecases.
     *
     * @return array|null
     */
    public function getSupportedPrivilegeSet() {

        return null;

    }	
}
