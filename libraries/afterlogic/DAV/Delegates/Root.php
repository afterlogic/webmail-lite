<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\Delegates;

class Root extends \Sabre\DAV\Collection {

    protected $pdo;

    public $disableListing = false;

    function __construct(\PDO $pdo, \Sabre\DAVACL\PrincipalBackend\BackendInterface $principalBackend, \Sabre\CalDAV\Backend\AbstractBackend $calendarBackend, $disableListing = false) {

        $this->pdo = $pdo;
        $this->principalBackend = $principalBackend;
        $this->calendarBackend = $calendarBackend;
		$this->disableListing = $disableListing;

    }

	function getName() {

		return 'delegation';

	}

    function getChildren() {

        if ($this->disableListing) {
            throw new \Sabre\DAV\Exception\MethodNotAllowed('Listing of items in this collection is not allowed');
        }

        $fields = array_values($this->calendarBackend->propertyMap);
        $fields[] = 'id';
        $fields[] = 'uri';
        $fields[] = 'ctag';
        $fields[] = 'components';
        $fields[] = 'principaluri';

        // Making fields a comma-delimited list 
        $fields = implode(', ', $fields);
        $stmt = $this->pdo->query('SELECT ' . $fields . ' FROM `'.$this->calendarBackend->calendarTableName.'`'); 

        $calendars = array();
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $components = explode(',',$row['components']);

            $calendar = array(
                'id' => $row['id'],
                'uri' => $row['uri'],
                'principaluri' => $row['principaluri'],
                '{' . \Sabre\CalDAV\Plugin::NS_CALENDARSERVER . '}getctag' => $row['ctag']?$row['ctag']:'0',
                '{' . \Sabre\CalDAV\Plugin::NS_CALDAV . '}supported-calendar-component-set' => new \Sabre\CalDAV\Property\SupportedCalendarComponentSet($components),
            );
        

            foreach($this->calendarBackend->propertyMap as $xmlName=>$dbName) {
                $calendar[$xmlName] = $row[$dbName];
            }
            $calendars[] = new CalendarParent($this->principalBackend, $this->calendarBackend, $calendar);

        }

        return $calendars;

    }

    function getChild($name) {

        $fields = array_values($this->calendarBackend->propertyMap);
        $fields[] = 'id';
        $fields[] = 'uri';
        $fields[] = 'ctag';
        $fields[] = 'components';
        $fields[] = 'principaluri';

        // Making fields a comma-delimited list 
        $fields = implode(', ', $fields);
        $stmt = $this->pdo->prepare('SELECT ' . $fields . ' FROM `'.$this->calendarBackend->calendarTableName.'` WHERE id = ?'); 
        $stmt->execute(array($name));

        if(!$row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            throw new \Sabre\DAV\Exception\FileNotFound('Calendar with id: ' . $name . ' could not be found');
        }


        $components = explode(',',$row['components']);

        $calendar = array(
            'id' => $row['id'],
            'uri' => $row['uri'],
            'principaluri' => $row['principaluri'],
            '{' . \Sabre\CalDAV\Plugin::NS_CALENDARSERVER . '}getctag' => $row['ctag']?$row['ctag']:'0',
            '{' . \Sabre\CalDAV\Plugin::NS_CALDAV . '}supported-calendar-component-set' => new \Sabre\CalDAV\Property\SupportedCalendarComponentSet($components),
        );
    

        foreach($this->calendarBackend->propertyMap as $xmlName=>$dbName) {
            $calendar[$xmlName] = $row[$dbName];
        }

        return new CalendarParent($this->principalBackend, $this->calendarBackend, $calendar);

    }

}

?>
