<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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

		$stmt = $this->pdo->prepare('SELECT id, user, calendaruri, eventid, time, starttime, allday'
				. ' FROM '.$this->table.' WHERE eventid = ?'.$userWhere); 
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
		
        $stmt = $this->pdo->prepare('SELECT id, user, calendaruri, eventid, time, starttime, allday'
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
                'starttime' => $row['starttime'],
				'allday' => $row['allday']
				);
		}		
		return $cache;
	}
	
	public function addReminder($user, $calendarUri, $eventId, $time = null, $starttime = null, $allday = false)
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
		
		$fieldNames[] = 'allday';
		$values[':allday'] = $allday ? 1 : 0;
		
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
	
	public static function getEventId($uri)
	{
		return basename($uri, '.ics');
	}
		
	public static function getEventUri($uri)
	{
		return basename($uri);
	}

	public static function getCalendarUri($uri)
	{
		return dirname($uri);
	}
	
	public static function isEvent($uri)
	{
		$sUriExt = pathinfo($uri, PATHINFO_EXTENSION);
		return ($sUriExt != null && strtoupper($sUriExt) == 'ICS');
	}
	
	public static function isCalendar($uri)
	{
		return (strpos($uri, 'calendars/') !== false ||	strpos($uri, 'delegation/') !== false);
	}	

	public function updateReminder($uri, $data, $user)
	{
		if (self::isCalendar($uri) && self::isEvent($uri))
		{
			$calendarUri = trim($this->getCalendarUri($uri), '/');
			$eventId = $this->getEventId($uri);
			
			$aReminder = $this->getReminder($eventId, $user);
			$this->deleteReminder($eventId, $user);

			$vCal = \Sabre\VObject\Reader::read($data);
			$aBaseEvents = $vCal->getBaseComponents('VEVENT');
			$bAllDay = false;
			if (isset($aBaseEvents[0]))
			{
				$oBaseEvent = $aBaseEvents[0];

				$oNowDT = new \DateTime('now', new \DateTimeZone('UTC'));
				$iReminderStartTS = $oNowDT->getTimestamp();
				if ($aReminder)
				{
					$iReminderStartTS = $aReminder['starttime'];
				}

				$bAllDay = !$oBaseEvent->DTSTART->hasTime();
				$oStartDT = $oBaseEvent->DTSTART->getDateTime();
				
				$oStartDT->setTimezone(new \DateTimeZone('UTC'));

				$oEndDT = $oBaseEvent->DTEND->getDateTime();
				$oEndDT->setTimezone(new \DateTimeZone('UTC'));

				$oInterval = $oStartDT->diff($oEndDT);

				$oStartDT = \CCalendarHelper::getNextRepeat($oNowDT, $oBaseEvent);
				if ($oStartDT)
				{
					$iReminderTime = \CCalendarHelper::GetActualReminderTime($oBaseEvent, $oNowDT, $oStartDT);

					if ($iReminderTime === false && isset($oBaseEvent->RRULE))
					{
						$iStartTS = $oStartDT->getTimestamp();
						if ($iStartTS == $iReminderStartTS)
						{
							$oStartDT->add($oInterval);
							$oStartDT = \CCalendarHelper::getNextRepeat($oStartDT, $oBaseEvent);
						}

						$iReminderTime = \CCalendarHelper::GetActualReminderTime($oBaseEvent, $oNowDT, $oStartDT);
					}

					if ($iReminderTime !== false)
					{
						$iOffset = 0;
						if ($bAllDay)
						{
							$oAccount = \afterlogic\DAV\Utils::GetAccountByLogin($user);
							if ($oAccount)
							{
								$oClientTZ = new \DateTimeZone($oAccount->User->ClientTimeZone);
								$oNowDTClientTZ = new \DateTime("now", $oClientTZ);
								$iOffset = $oNowDTClientTZ->getOffset();
							}
						}

						$iStartTS = $oStartDT->getTimestamp();
						$this->addReminder($user, $calendarUri, $eventId, $iReminderTime - $iOffset, $iStartTS - $iOffset, $bAllDay);
					}
				}
			}
		}
	}
}