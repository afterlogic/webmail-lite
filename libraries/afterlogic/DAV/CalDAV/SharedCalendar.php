<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CalDAV;

class SharedCalendar extends \Sabre\CalDAV\SharedCalendar{

    protected $principalInfo;
	
	/**
     * Constructor
     *
     * @param Backend\BackendInterface $caldavBackend
     * @param array $calendarInfo
     * @param array $principalInfo
     */
    public function __construct(\Sabre\CalDAV\Backend\BackendInterface $caldavBackend, $calendarInfo, $principalInfo) {

		$this->principalInfo = $principalInfo;
        parent::__construct($caldavBackend, $calendarInfo);

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

		$sTenantPrincipalUri = \afterlogic\DAV\Utils::getTenantPrincipalUri($this->principalInfo['uri']);

		// The top-level ACL only contains access information for the true
        // owner of the calendar, so we need to add the information for the
        // sharee.
        $acl = parent::getACL();
        $acl[] = array(
            'privilege' => '{DAV:}read',
            'principal' => $this->calendarInfo['principaluri'],
            'protected' => true,
        );
        if (!$this->calendarInfo['{http://sabredav.org/ns}read-only']) {
            $acl[] = array(
                'privilege' => '{DAV:}write',
                'principal' => $this->calendarInfo['principaluri'],
                'protected' => true,
            );
        }
		if ($sTenantPrincipalUri === $this->calendarInfo['principaluri'])
		{
			$acl[] = array(
				'privilege' => '{DAV:}read',
				'principal' => $this->principalInfo['uri'],
				'protected' => true,
			);
			if (!$this->calendarInfo['{http://sabredav.org/ns}read-only']) {
				$acl[] = array(
					'privilege' => '{DAV:}write',
					'principal' => $this->principalInfo['uri'],
					'protected' => true,
				);
			}
		}
		
        return $acl;

    }
	
	/**
     * Updates properties such as the display name and description
     *
     * @param array $mutations
     * @return array
     */
    public function updateProperties($mutations) {

        if (isset($mutations['href']))
		{
			return $this->caldavBackend->updateShares($this->calendarInfo['id'], array($mutations));
		}
		else
		{
			return $this->caldavBackend->updateCalendar($this->calendarInfo['id'], $mutations);
		}

    }	
	
    /**
     * Deletes the calendar.
     *
     * @return void
     */
    public function delete() {

		$sTenantPrincipalUri = \afterlogic\DAV\Utils::getTenantPrincipalUri($this->principalInfo['uri']);
		if ($sTenantPrincipalUri !== $this->calendarInfo['principaluri'])
		{
			$sEmail = basename($this->principalInfo['uri']);
			$this->caldavBackend->updateShares($this->calendarInfo['id'], array(), array($sEmail));
		}

    }	

}
