<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 * 
 */


namespace afterlogic\DAV\Reminders;

class Plugin extends \Sabre\DAV\ServerPlugin {

    /**
     * Reference to Server class 
     * 
     * @var \Sabre\DAV\Server 
     */
    private $server;
	
    /**
     * cacheBackend 
     * 
     * @var Backend\PDO 
     */
    private $backend;	

    /**
     * Returns a plugin name.
     *
     * Using this name other plugins will be able to access other plugins
     * using \Sabre\DAV\Server::getPlugin
     *
     * @return string
     */
    public function getPluginName() {

        return 'reminders';

    }
	
	/**
     * __construct 
     * 
     * @param Backend\PDO $backend 
     * @return void
     */
    public function __construct(Backend\PDO $backend = null) {

        $this->backend = $backend;        
    }
	
	/**
     * Initializes the plugin and registers event handlers 
     * 
     * @param \Sabre\DAV\Server $server 
     * @return void
     */
    public function initialize(\Sabre\DAV\Server $server) 
	{

        $this->server = $server;
		
		$this->server->subscribeEvent('beforeMethod', array($this, 'beforeMethod'), 90);
		$this->server->subscribeEvent('afterCreateFile', array($this, 'afterCreateFile'), 90);		
		$this->server->subscribeEvent('afterWriteContent', array($this, 'afterWriteContent'), 90);		
    }
	
	protected function getEventId($uri)
	{
		return basename($uri, '.ics');
	}
		
	protected function getEventUri($uri)
	{
		return basename($uri);
	}

	protected function getCalendarUri($uri)
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
	
	protected function getUser()
	{
		$user = null;
		$authPlugin = $this->server->getPlugin('auth');
		if ($authPlugin !== null)
		{
			 $user = $authPlugin->getCurrentUser();
		}
		
		return $user;
	}

    /**
     * @param string $method
     * @param string $uri
     * @return void
     */
    public function beforeMethod($method, $uri) 
	{
		if ($this->isCalendar($uri))
		{
			if (strtoupper($method) == 'DELETE')
			{
				if ($this->isEvent($uri))
				{
					$eventId = $this->getEventId($uri);
					$this->deleteReminder($eventId);
				}
				else
				{
					$this->deleteReminderByCalendar($uri);
				}
			}
		}
    }
	
	public function afterCreateFile($uri, \Sabre\DAV\ICollection $parent)
	{
		$node = $parent->getChild($this->getEventUri($uri));
		$this->updateReminder($uri, $node->get(), $this->getUser());
	}
	
	public function afterWriteContent($uri, \Sabre\DAV\IFile $node)
	{
		$this->updateReminder($uri, $node->get(), $this->getUser());
	}
			
	public function getReminder($eventId, $user = null)
	{
		return $this->backend->getReminder($eventId, $user);
	}
	
	public function getReminders($start, $end)
	{
		return $this->backend->getReminders($start, $end);
	}
	
	public function addReminder($user, $calendarUri, $eventId, $time = null, $starttime = null)
	{
		return $this->backend->addReminder($user, $calendarUri, $eventId, $time, $starttime);
	}
	
	public function deleteReminder($eventId, $user = null)
	{
		$this->backend->deleteReminder($eventId, $user);
	}
	
	public function deleteReminderByCalendar($calendarUri)
	{
		$this->backend->deleteReminderByCalendar($calendarUri);
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
			if (isset($aBaseEvents[0]))
			{
				$oBaseEvent = $aBaseEvents[0];

				$oNowDT = new \DateTime('now', new \DateTimeZone('UTC'));
				$iReminderStartTS = $oNowDT->getTimestamp();
				if ($aReminder)
				{
					$iReminderStartTS = $aReminder['starttime'];
				}

				$oStartDT = $oBaseEvent->DTSTART->getDateTime();
				$oStartDT->setTimezone(new \DateTimeZone('UTC'));

				$oEndDT = $oBaseEvent->DTEND->getDateTime();
				$oEndDT->setTimezone(new \DateTimeZone('UTC'));

				$oInterval = $oStartDT->diff($oEndDT);

				$oStartDT = \CCalendarHelper::getNextRepeat($oNowDT, $oBaseEvent);
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
					$iStartTS = $oStartDT->getTimestamp();
					$this->addReminder($user, $calendarUri, $eventId, $iReminderTime, $iStartTS);
				}
			}
		}
	}
}
