<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 * 
 */

namespace afterlogic\DAV\Reminders\Backend;

abstract class AbstractBackend {
	
	abstract function getReminders($start = null, $end = null);	

	abstract function getReminder($eventId);
	
	abstract function addReminder($user, $calendarUri, $eventId, $time = null, $starttime = null);
	
	abstract function deleteReminder($eventId);
	
	abstract function deleteReminderByCalendar($calendarUri);

}
