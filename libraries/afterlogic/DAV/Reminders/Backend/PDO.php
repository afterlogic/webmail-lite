<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 * 
 */

namespace afterlogic\DAV\Reminders\Backend;

use afterlogic\DAV\Constants;

class PDO
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
	
	protected $principalsTbl;

	/**
     * Creates the backend object. 
     *
     * @return void
     */
    public function __construct() 
	{
        $dBPrefix = \CApi::GetSettings()->GetConf('Common/DBPrefix');

		$this->pdo = \CApi::GetPDO();
        $this->table = $dBPrefix.Constants::T_REMINDERS;
        $this->calendarTbl = $dBPrefix.Constants::T_CALENDARS;
        $this->principalsTbl = $dBPrefix.Constants::T_PRINCIPALS;
    } 
	
	public function getReminder($eventId, $user = null)
	{
		$userWhere = '';
		$params = array($eventId);
		if (isset($user))
		{
			$userWhere = ' AND user = ?';
			$params[] = $user;
		}

		$stmt = $this->pdo->prepare('SELECT id, user, calendaruri, eventid, time, starttime '
				. 'FROM '.$this->table.' WHERE eventid = ?'.$userWhere); 
        $stmt->execute($params);
		
        return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getReminders($start = null, $end = null)
	{
		$values = array();

		$timeFilter = '';
		if ($start != null && $end != null)
		{
			$timeFilter = ' and time > ? and time <= ?';
			$values = array(
				(int) $start,
				(int) $end
			);
		}
		
        $stmt = $this->pdo->prepare('SELECT id, user, calendaruri, eventid, time, starttime'
				. ' FROM '.$this->table.' WHERE 1 = 1' . $timeFilter); 
		
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

		$stmt = $this->pdo->prepare("INSERT INTO ".$this->table." (".implode(', ', $fieldNames).") VALUES (".implode(', ',array_keys($values)).")");
        $stmt->execute($values);

        return $this->pdo->lastInsertId();		
	}

	public function deleteReminder($eventId, $user = null)
	{
		$userWhere = '';
		$params = array($eventId);
		if (isset($user))
		{
			$userWhere = ' AND user = ?';
			$params[] = $user;
		}
        $stmt = $this->pdo->prepare('DELETE FROM '.$this->table.' WHERE eventid = ?'.$userWhere);
        $stmt->execute($params);
	}
	
	public function deleteReminderByCalendar($calendarUri)
	{
        $stmt = $this->pdo->prepare('DELETE FROM '.$this->table.' WHERE calendaruri = ?');
        $stmt->execute(array($calendarUri));
	}
}