<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\Delegates;

class CalendarParent extends \Sabre\DAV\Collection {

    protected $pdo;
    protected $calendarInfo;
    protected $principalBackend;

    public function __construct(\Sabre\DAVACL\PrincipalBackend\BackendInterface $principalBackend, \Sabre\CalDAV\Backend\AbstractBackend $calendarBackend, array $calendarInfo) {

        $this->calendarInfo = $calendarInfo;
        $this->principalBackend = $principalBackend;
        $this->calendarBackend = $calendarBackend;

    }

    function getName() {

        return $this->calendarInfo['id'];

    }

    function getChildren() {

        return array(
            new Principal($this->principalBackend, $this->calendarInfo),
            new Calendar($this->principalBackend, $this->calendarBackend, $this->calendarInfo),
        );

    }

}
