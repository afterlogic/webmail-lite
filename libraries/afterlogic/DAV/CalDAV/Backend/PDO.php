<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\CalDAV\Backend;

use afterlogic\DAV\Constants;

class PDO extends \Sabre\CalDAV\Backend\PDO {

    /**
     * pdo 
     * 
     * @var \PDO
     */
    protected $pdo;

	public $calendarTableName;

	public $calendarObjectTableName;

	public $delegatesTableName;

	public $principalsTableName;

    /**
     * Creates the backend 
     * 
     * @param \PDO $pdo 
     */
    public function __construct(\PDO $pdo, $dBPrefix) {

        $this->pdo = $pdo;
        
		$this->calendarTableName = $dBPrefix.Constants::T_CALENDARS;
        $this->calendarObjectTableName = $dBPrefix.Constants::T_CALENDAROBJECTS;
		$this->delegatesTableName = $dBPrefix.Constants::T_DELEGATES;

    }

    /**
     * Delete a calendar and all it's objects 
     * 
     * @param string $calendarId 
     * @return void
     */
    public function deleteCalendar($calendarId) {

		parent::deleteCalendar($calendarId);
		
		$stmt = $this->pdo->prepare('DELETE FROM `'.$this->delegatesTableName.'` WHERE calendarid = ?');
		$stmt->execute(array($calendarId));
    }
	
}
