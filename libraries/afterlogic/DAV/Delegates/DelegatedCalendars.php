<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 * 
 */

namespace afterlogic\DAV\Delegates;

class DelegatedCalendars implements \Sabre\DAV\IExtendedCollection, \Sabre\DAVACL\IACL {

    /**
     * Principal backend
     *
     * @var \Sabre\DAVACL\PrincipalBackend\BackendInterface
     */
    protected $principalBackend;

    /**
     * CalDAV backend
     *
     * @var \Sabre\CalDAV\Backend\AbstractBackend
     */
    protected $caldavBackend;

    /**
     * Delegates backend
     *
     * @var Backend\Abstract
     */
    protected $delegatesBackend;

	/**
     * Principal information
     *
     * @var array
     */
    protected $principalInfo;


    /**
     * Constructor
     *
     * @param \Sabre\DAVACL\PrincipalBackend\BackendInterface $principalBackend
     * @param \Sabre\CalDAV\Backend\AbstractBackend $caldavBackend
     * @param \afterlogic\DAV\Delegates\Backend\AbstractBackend $delegatesBackend
     * @param mixed $userUri
     */
    public function __construct(\Sabre\DAVACL\PrincipalBackend\BackendInterface $principalBackend, 
			\Sabre\CalDAV\Backend\AbstractBackend $caldavBackend, 
			\afterlogic\DAV\Delegates\Backend\AbstractBackend $delegatesBackend, $userUri) {

        $this->principalBackend = $principalBackend;
        $this->caldavBackend = $caldavBackend;
        $this->delegatesBackend = $delegatesBackend;
        $this->principalInfo = $principalBackend->getPrincipalByPath($userUri);

    }

    /**
     * Returns the name of this object
     *
     * @return string
     */
    public function getName() {

        list(,$name) = \Sabre\DAV\URLUtil::splitPath($this->principalInfo['uri']);
        return $name;

    }

    /**
     * Updates the name of this object
     *
     * @param string $name
     * @return void
     */
    public function setName($name) {

        throw new \Sabre\DAV\Exception\Forbidden();

    }

    /**
     * Deletes this object
     *
     * @return void
     */
    public function delete() {

        throw new \Sabre\DAV\Exception\Forbidden();

    }

    /**
     * Returns the last modification date
     *
     * @return int
     */
    public function getLastModified() {

        return null;

    }

    /**
     * Creates a new file under this object.
     *
     * This is currently not allowed
     *
     * @param string $filename
     * @param resource $data
     * @return void
     */
    public function createFile($filename, $data=null) {

        throw new \Sabre\DAV\Exception\MethodNotAllowed('Creating new files in this collection is not supported');

    }

    /**
     * Creates a new directory under this object.
     *
     * This is currently not allowed.
     *
     * @param string $filename
     * @return void
     */
    public function createDirectory($filename) {

        throw new \Sabre\DAV\Exception\MethodNotAllowed('Creating new collections in this collection is not supported');

    }

    /**
     * Returns a single calendar, by name
     *
     * @param string $name
     * @todo needs optimizing
     * @return \Sabre\CalDAV\Calendar
     */
    public function getChild($name) {

        foreach($this->getChildren() as $child) {
            if ($name==$child->getName())
                return $child;

        }
        throw new \Sabre\DAV\Exception\NotFound('Calendar with name \'' . $name . '\' could not be found');

    }

    /**
     * Checks if a calendar exists.
     *
     * @param string $name
     * @todo needs optimizing
     * @return bool
     */
    public function childExists($name) {

        foreach($this->getChildren() as $child) {
            if ($name==$child->getName())
                return true;

        }
        return false;

    }

    /**
     * Returns a list of calendars
     *
     * @return array
     */
    public function getChildren() {

        $calendars = $this->delegatesBackend->getDeligatedCalendarsForUser($this->principalInfo['id']);
        $objs = array();
        foreach($calendars as $calendar) {
            $objs[] = new \afterlogic\DAV\Delegates\Calendar($this->principalBackend, $this->caldavBackend, $calendar);
        }
        return $objs;

    }

    /**
     * Creates a new calendar
     *
     * @param string $name
     * @param array $resourceType
     * @param array $properties
     * @return void
     */
    public function createExtendedCollection($name, array $resourceType, array $properties) {

        if (!in_array('{urn:ietf:params:xml:ns:caldav}calendar',$resourceType) || count($resourceType)!==2) {
            throw new \Sabre\DAV\Exception\InvalidResourceType('Unknown resourceType for this collection');
        }
        $this->caldavBackend->createCalendar($this->principalInfo['uri'], $name, $properties);

    }

    /**
     * Returns the owner principal
     *
     * This must be a url to a principal, or null if there's no owner
     *
     * @return string|null
     */
    public function getOwner() {

        return $this->principalInfo['uri'];

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

        return array(
            array(
                'privilege' => '{DAV:}read',
                'principal' => $this->principalInfo['uri'],
                'protected' => true,
            ),
            array(
                'privilege' => '{DAV:}write',
                'principal' => $this->principalInfo['uri'],
                'protected' => true,
            ),
            array(
                'privilege' => '{DAV:}read',
                'principal' => $this->principalInfo['uri'] . '/calendar-proxy-write',
                'protected' => true,
            ),
            array(
                'privilege' => '{DAV:}write',
                'principal' => $this->principalInfo['uri'] . '/calendar-proxy-write',
                'protected' => true,
            ),
            array(
                'privilege' => '{DAV:}read',
                'principal' => $this->principalInfo['uri'] . '/calendar-proxy-read',
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

        throw new \Sabre\DAV\Exception\MethodNotAllowed('Changing ACL is not yet supported');

    }

    /**
     * Returns the list of supported privileges for this node.
     *
     * The returned data structure is a list of nested privileges.
     * See \Sabre\DAVACL\Plugin::getDefaultSupportedPrivilegeSet for a simple
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
