<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
		if (Backend\PDO::isCalendar($uri))
		{
			if (strtoupper($method) == 'DELETE')
			{
				if (Backend\PDO::isEvent($uri))
				{
					$eventId = Backend\PDO::getEventId($uri);
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
		$node = $parent->getChild(Backend\PDO::getEventUri($uri));
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
	
	public function addReminder($user, $calendarUri, $eventId, $time = null, $starttime = null, $allday = false)
	{
		return $this->backend->addReminder($user, $calendarUri, $eventId, $time, $starttime, $allday);
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
		$this->backend->updateReminder($uri, $data, $user);
	}
}
