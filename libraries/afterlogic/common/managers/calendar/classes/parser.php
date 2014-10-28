<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Calendar
 * @subpackage Classes
 */
class CalendarParser
{
	public static function ParseEvent($oAccount, $oCalendar, $oVCal, $oVCalOriginal = null)
	{
		$ApiCapabilityManager = CApi::Manager('capability');
		$ApiUsersManager = CApi::Manager('users');

		$aResult = array();
		$aRules = array();
		$aExcludedRecurrences = array();
		
		if (isset($oVCalOriginal))
		{
			$aRules = CalendarParser::GetRRules($oAccount, $oVCalOriginal);
			$aExcludedRecurrences = CalendarParser::GetExcludedRecurrences($oVCalOriginal);
		}

		if (isset($oVCal, $oVCal->VEVENT))
		{
			foreach ($oVCal->VEVENT as $oVEvent)
			{
				$sOwnerEmail = $oCalendar->Owner;
				$aEvent = array();
				
				if (isset($oVEvent, $oVEvent->UID))
				{
					$sUid = (string)$oVEvent->UID;
					$sRecurrenceId = CCalendarHelper::getRecurrenceId($oVEvent);

					$sId = $sUid . '-' . $sRecurrenceId;
					
					if (array_key_exists($sId, $aExcludedRecurrences))
					{
						$oVEvent = $aExcludedRecurrences[$sId];
						$aEvent['excluded'] = true;
					}

					$bIsAppointment = false;
					$aEvent['attendees'] = array();
					if ($ApiCapabilityManager->IsCalendarAppointmentsSupported($oAccount) && isset($oVEvent->ATTENDEE))
					{
						$aEvent['attendees'] = self::ParseAttendees($oVEvent);

						if (isset($oVEvent->ORGANIZER))
						{
							$sOwnerEmail = str_replace('mailto:', '', strtolower((string)$oVEvent->ORGANIZER));
						}
						$bIsAppointment = ($sOwnerEmail !== $oAccount->Email);
					}
					
					$oOwner = $ApiUsersManager->GetAccountOnLogin($sOwnerEmail);
					$sOwnerName = ($oOwner) ? $oOwner->FriendlyName : '';
					
					$aEvent['appointment'] = $bIsAppointment;
					$aEvent['appointmentAccess'] = 0;
					
					$aEvent['alarms'] = self::ParseAlarms($oVEvent);

					$bAllDay = (isset($oVEvent->DTSTART) && !$oVEvent->DTSTART->hasTime());
					$sTimeZone = ($bAllDay) ? 'UTC' : $oAccount->GetDefaultStrTimeZone();

					$sStart = CCalendarHelper::GetStrDate($oVEvent->DTSTART, $sTimeZone);
					$sEnd = CCalendarHelper::GetStrDate($oVEvent->DTEND, $sTimeZone);

					$oDTStart = CCalendarHelper::GetDateTime($oVEvent->DTSTART);

					$aEvent['calendarId'] = $oCalendar->Id;
					$aEvent['id'] = $sId;
					$aEvent['uid'] = $sUid;
					$aEvent['subject'] = $oVEvent->SUMMARY ? (string)$oVEvent->SUMMARY : '';
					$aDescription = $oVEvent->DESCRIPTION ? \Sabre\VObject\Parser\MimeDir::unescapeValue((string)$oVEvent->DESCRIPTION) : array('');
					$aEvent['description'] = $aDescription[0];
					$aEvent['location'] = $oVEvent->LOCATION ? (string)$oVEvent->LOCATION : '';
					$aEvent['start'] = $sStart;
					$aEvent['startTS'] = $oDTStart->getTimestamp();
					$aEvent['end'] = $sEnd;
					$aEvent['allDay'] = $bAllDay;
					$aEvent['owner'] = $sOwnerEmail;
					$aEvent['ownerName'] = $sOwnerName;
					$aEvent['modified'] = false;
					
					$aEvent['recurrenceId'] = $sRecurrenceId;
					
					if (isset($aEvent['uid'], $aRules[$aEvent['uid']]))
					{
						$aEvent['rrule'] = $aRules[$aEvent['uid']]->toArray();
					}
				}
				
				$aResult[] = $aEvent;
			}
		}

		return $aResult;
	}
	
	public static function ParseAlarms($oVEvent)
	{
		$aResult = array();
		
		if ($oVEvent->VALARM)
		{
			foreach($oVEvent->VALARM as $oVAlarm)
			{
				if (isset($oVAlarm->TRIGGER) && $oVAlarm->TRIGGER instanceof \Sabre\VObject\Property\ICalendar\Duration)
				{
					$aResult[] = CCalendarHelper::GetOffsetInMinutes($oVAlarm->TRIGGER->getDateInterval());
				}
			}
			rsort($aResult);
		}	
		
		return $aResult;
	}

	public static function ParseAttendees($oVEvent)
	{
		$aResult = array();
		
		if (isset($oVEvent->ATTENDEE))
		{
			foreach($oVEvent->ATTENDEE as $oAttendee)
			{
				$iStatus = \EAttendeeStatus::Unknown;
				if (isset($oAttendee['PARTSTAT']))
				{
					switch (strtoupper((string)$oAttendee['PARTSTAT']))
					{
						case 'ACCEPTED':
							$iStatus = \EAttendeeStatus::Accepted;
							break;
						case 'DECLINED':
							$iStatus = \EAttendeeStatus::Declined;
							break;
						case 'TENTATIVE':
							$iStatus = \EAttendeeStatus::Tentative;;
							break;
					}
				}

				$aResult[] = array(
					'access' => 0,
					'email' => isset($oAttendee['EMAIL']) ? (string)$oAttendee['EMAIL'] : str_replace('mailto:', '', strtolower($oAttendee->getValue())),
					'name' => isset($oAttendee['CN']) ? (string)$oAttendee['CN'] : '',
					'status' => $iStatus
				);
			}
		}
		return $aResult;
	}
	
	public static function ParseRRule($oAccount, $oVCal, $sUid)
	{
		$oResult = null;

		$aWeekDays = array('SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA');
		$aPeriods = array(
			EPeriod::Secondly,
			EPeriod::Minutely,
			EPeriod::Hourly,
			EPeriod::Daily,
			EPeriod::Weekly,
			EPeriod::Monthly,
			EPeriod::Yearly
		);
		
		$oVEventBase = $oVCal->getBaseComponent();
		if (isset($oVEventBase->RRULE))
		{
			$oResult = new \CRRule($oAccount);
			$aRules = $oVEventBase->RRULE->getParts();
			if (isset($aRules['FREQ']))
			{
				$bIsPosiblePeriod = array_search(strtolower($aRules['FREQ']), array_map('strtolower', $aPeriods));
				if ($bIsPosiblePeriod !== false)
				{
					$oResult->Period = $bIsPosiblePeriod - 2;
				}
			}
			if (isset($aRules['INTERVAL']))
			{
				$oResult->Interval = $aRules['INTERVAL'];
			}
			if (isset($aRules['COUNT']))
			{
				$oResult->Count = $aRules['COUNT'];
			}
			if (isset($aRules['UNTIL']))
			{
				$oResult->Until = date_format(date_create($aRules['UNTIL']), 'U');
			}
			if (isset($oResult->Count))
			{
				$oResult->End = 1;
			}
			if (isset($oResult->Until))
			{
				$oResult->End = 2;
			}
/*
			if (isset($oRecurrence->bySetPos))
			{
				$oResult->WeekNum = $oRecurrence->bySetPos;
			}
 */
			if (isset($aRules['BYDAY']) && is_array($aRules['BYDAY']))
			{
				foreach ($aRules['BYDAY'] as $sDay)
				{
					if (strlen($sDay) > 2)
					{
						$iNum = (int)substr($sDay, 0, -2);

						if ($iNum === 1) $oResult->WeekNum = 0;
						if ($iNum === 2) $oResult->WeekNum = 1;
						if ($iNum === 3) $oResult->WeekNum = 2;
						if ($iNum === 4) $oResult->WeekNum = 3;
						if ($iNum === -1) $oResult->WeekNum = 4;
					}

					foreach ($aWeekDays as $sWeekDay)
					{
						if (strpos($sDay, $sWeekDay) !== false) 
						{
							$oResult->ByDays[] = $sWeekDay;
						}
					}
				}
			}
		}
		return $oResult;
	}	
	
	public static function GetRRules($oAccount, $oVCal)
	{
		$aResult = array();
		
		foreach($oVCal->getBaseComponents('VEVENT') as $oVEvent)
		{
			if (isset($oVEvent->RRULE))
			{
				$oRRule = CalendarParser::ParseRRule($oAccount, $oVCal, (string)$oVEvent->UID);
				if ($oRRule)
				{
					$sTimeZone = $oAccount->GetDefaultStrTimeZone();
					$oDTStart = CCalendarHelper::GetDateTime($oVEvent->DTSTART, $sTimeZone);
					$oDTEnd = CCalendarHelper::GetDateTime($oVEvent->DTEND, $sTimeZone);

					$oRRule->StartBase = $oDTStart->getTimestamp();
					if (isset($oDTEnd))
					{
						$oRRule->EndBase = $oDTEnd->getTimestamp();
					}
					
					$aResult[(string)$oVEvent->UID] = $oRRule;
				}
			}
		}
		
		return $aResult;
	}	
	
	public static function GetExcludedRecurrences($oVCal)
	{
        $aRecurrences = array();
        foreach($oVCal->children as $oComponent) {

            if (!$oComponent instanceof \Sabre\VObject\Component)
			{
                continue;
			}

            if (isset($oComponent->{'RECURRENCE-ID'}))
			{
				$iRecurrenceId = CCalendarHelper::getRecurrenceId($oComponent);
				$aRecurrences[(string)$oComponent->UID . '-' . $iRecurrenceId] = $oComponent;
			}
        }

        return $aRecurrences;
	}
	
}