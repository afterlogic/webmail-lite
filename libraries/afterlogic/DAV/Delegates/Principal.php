<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\Delegates;

class Principal extends \Sabre\DAV\Collection implements \Sabre\DAVACL\IPrincipal {

    protected $calendarInfo;

    public function __construct(\Sabre\DAVACL\PrincipalBackend\BackendInterface $principalBackend, array $calendarInfo) {

        $this->calendarInfo = $calendarInfo;
        $this->principalBackend = $principalBackend;

    }

    function getName() {

        return 'principal';

    }

    function getAlternateUriSet() {

        return array();

    }

    function getPrincipalUrl() {

        return 'delegation/' . $this->calendarInfo['id'] . '/principal';

    }

    function getGroupMemberSet() {

        return array();

    }

    function setGroupMemberSet(array $groupMembers) {

        throw new \Sabre\DAV\Exception\Forbidden('Updating group members on this principal is not allowed');

    }

    function getGroupMemberShip() {

        return array();

    }

    /**
     * This method returns the displayname for a calendar.
     *
     * We're returning the calendar name instead. 
     * 
     * @return string 
     */
    function getDisplayName() {

        $displayName = null;
		if($this->calendarInfo['{DAV:}displayname']) 
		{
            $displayName = $this->calendarInfo['{DAV:}displayname'];
        } 
		else 
		{
            $displayName = $this->calendarInfo['uri'];
        }
        return $displayName;  

    }

    function getChildren() {

        $properties = array(
            'uri' => $this->getPrincipalUrl(),
        );
        return array(
            new \Sabre\CalDAV\Principal\ProxyRead($this->principalBackend,$properties),
            new \Sabre\CalDAV\Principal\ProxyWrite($this->principalBackend, $properties),
        );

    }

}
