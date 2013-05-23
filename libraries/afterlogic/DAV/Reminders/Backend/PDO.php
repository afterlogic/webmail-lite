<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\Reminders\Backend;

use afterlogic\DAV\Constants;

class PDO extends AbstractBackend
{

    /**
     * Reference to PDO connection 
     * 
     * @var \PDO 
     */
    protected $pdo;

    /**
     * PDO table name we'll be using  
     * 
     * @var string
     */
    protected $table;
	
    protected $calendarTbl;

	protected $delegatesTbl;
	
	protected $principalsTbl;

	/**
     * Creates the backend object. 
     *
     * If the filename argument is passed in, it will parse out the specified file fist.
     * 
     * @param string $filename
     * @param string $tableName The PDO table name to use 
     * @return void
     */
    public function __construct(\PDO $pdo, $dBPrefix = '') 
	{
        $this->pdo = $pdo;
        $this->table = $dBPrefix.'adav_reminders';
        $this->calendarTbl = $dBPrefix.Constants::T_CALENDARS;
        $this->delegatesTbl = $dBPrefix.Constants::T_DELEGATES;
        $this->principalsTbl = $dBPrefix.Constants::T_PRINCIPALS;
    } 
	
	public function getReminder($eventId)
	{
		$fields = array();
        $fields[] = 'id';
        $fields[] = 'user';
        $fields[] = 'calendaruri';
        $fields[] = 'eventid';
        $fields[] = 'time';
        $fields[] = 'starttime';

        $fields = implode(', ', $fields);
        $stmt = $this->pdo->prepare('SELECT ' . $fields . ' FROM `'.$this->table.
				'` WHERE eventid = ?'); 
		
        $stmt->execute(array($eventId));
		
        return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getReminders($start = null, $end = null)
	{
		$fields = array();
        $fields[] = 'id';
        $fields[] = 'user';
        $fields[] = 'calendaruri';
        $fields[] = 'eventid';
        $fields[] = 'time';
        $fields[] = 'starttime';

		$values = array();

		$timeFilter = '';
		if ($start != null && $end != null)
		{
			$timeFilter = ' and time > ? and time <= ?';
			$values[] = (int) $start;
			$values[] = (int) $end;
		}
		
        $fields = implode(', ', $fields);
        $stmt = $this->pdo->prepare('SELECT ' . $fields . ' FROM `'.$this->table.
				'` WHERE 1 = 1' . $timeFilter); 
		
        $stmt->execute($values);
		
        $cache = array();
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) 
		{
            $cache[] = array(
                'id' => $row['id'],
                'user' => $row['user'],
                'calendaruri' => $row['calendaruri'],
                'eventid' => $row['eventid'],
                'time' => $row['time'],
                'starttime' => $row['starttime']
            );
		}		
		return $cache;
	}
	
	public function addReminder($user, $calendarUri, $eventId, $time = null, $starttime = null)
	{
		$values = $fieldNames = array();
        $fieldNames[] = 'user';
		$values[':user'] = $user;

		$fieldNames[] = 'calendaruri';
		$values[':calendaruri'] = $calendarUri;

		$fieldNames[] = 'eventid';
		$values[':eventid'] = $eventId;

		if ($time != null)
		{
			$fieldNames[] = 'time';
			$values[':time'] = (int) $time;
		}

		if ($starttime != null)
		{
			$fieldNames[] = 'starttime';
			$values[':starttime'] = (int) $starttime;
		}

		$stmt = $this->pdo->prepare("INSERT INTO `".$this->table."` (".implode(', ', $fieldNames).") VALUES (".implode(', ',array_keys($values)).")");
        $stmt->execute($values);

        return $this->pdo->lastInsertId();		
	}

	public function deleteReminder($eventId)
	{
        $stmt = $this->pdo->prepare('DELETE FROM `'.$this->table.'` WHERE eventid = ?');
        $stmt->execute(array($eventId));
	}
	
	public function deleteReminderByCalendar($calendarUri)
	{
        $stmt = $this->pdo->prepare('DELETE FROM `'.$this->table.'` WHERE calendaruri = ?');
        $stmt->execute(array($calendarUri));
	}
}