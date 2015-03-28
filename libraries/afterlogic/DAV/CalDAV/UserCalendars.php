<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CalDAV;

class UserCalendars extends \Sabre\CalDAV\UserCalendars{

    /**
     * Returns a list of calendars
     *
     * @return array
     */
    public function getChildren() {

        $calendars = $this->caldavBackend->getCalendarsForUser($this->principalInfo['uri']);

        $objs = array();
        foreach($calendars as $calendar) {
			
            if ($this->caldavBackend instanceof \Sabre\CalDAV\Backend\SharingSupport) {
                if (isset($calendar['{http://calendarserver.org/ns/}shared-url'])) {
					$objs[] = new SharedCalendar($this->caldavBackend, $calendar, $this->principalInfo);
                } else {
                    $objs[] = new \Sabre\CalDAV\ShareableCalendar($this->caldavBackend, $calendar);
                }
            } else {
                $objs[] = new \Sabre\CalDAV\Calendar($this->caldavBackend, $calendar);
            }
        }
        $objs[] = new \Sabre\CalDAV\Schedule\Outbox($this->principalInfo['uri']);

        // We're adding a notifications node, if it's supported by the backend.
		
        if ($this->caldavBackend instanceof \Sabre\CalDAV\Backend\NotificationSupport && \CApi::GetConf('labs.dav.caldav.notification', false)) {
            $objs[] = new \Sabre\CalDAV\Notifications\Collection($this->caldavBackend, $this->principalInfo['uri']);
        }
		return $objs;

    }

}
