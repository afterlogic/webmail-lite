<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\Delegates;

class Calendar extends \Sabre\CalDAV\Calendar {

    public function __construct(\Sabre\DAVACL\PrincipalBackend\BackendInterface $principalBackend, \Sabre\CalDAV\Backend\AbstractBackend $calendarBackend, array $calendarInfo) {

        parent::__construct($calendarBackend, $calendarInfo);

    }

    public function getName() {

        return $this->calendarInfo['id'];

    }

    public function getMode() {

        return $this->calendarInfo['mode'];

    }

	public function getACL() {

        return array(
            array(
                'privilege' => '{DAV:}read',
                'principal' => 'delegation/' . $this->calendarInfo['id'] . '/principal/calendar-proxy-write',
                'protected' => true,
            ),
            array(
                'privilege' => '{DAV:}write',
                'principal' => 'delegation/' . $this->calendarInfo['id'] . '/principal/calendar-proxy-write',
                'protected' => true,
            ),
            array(
                'privilege' => '{DAV:}read',
                'principal' => 'delegation/' . $this->calendarInfo['id'] . '/principal/calendar-proxy-read',
                'protected' => true,
            ),
        );

    
    }
    /**
     * Returns a calendar object
     *
     * The contained calendar objects are for example Events or Todo's.
     * 
     * @param string $name 
     * @return \Sabre\DAV\ICalendarObject 
     */
    public function getChild($name) {

        $obj = $this->caldavBackend->getCalendarObject($this->calendarInfo['id'],$name);
        if (!$obj) throw new \Sabre\DAV\Exception\FileNotFound('Calendar object not found');
        return new CalendarObject($this->caldavBackend,$this->calendarInfo,$obj);

    }

    /**
     * Returns the full list of calendar objects  
     * 
     * @return array 
     */
    public function getChildren() {

        $objs = $this->caldavBackend->getCalendarObjects($this->calendarInfo['id']);
        $children = array();
        foreach($objs as $obj) {
            $children[] = new CalendarObject($this->caldavBackend,$this->calendarInfo,$obj);
        }
        return $children;

    }

}
