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
class CCalendarHelper
{

	public static function GetActualReminderTime($oEvent, $oNowDT, $oStartDT)
	{
		$aReminders = CalendarParser::ParseAlarms($oEvent);

		$iNowTS = $oNowDT->getTimestamp();

		if ($oStartDT)
		{
			$iStartEventTS = $oStartDT->getTimestamp();

			$aRemindersTime = array();
			foreach ($aReminders as $iReminder)
			{
				$aRemindersTime[] = $iStartEventTS - $iReminder * 60;
			}
			sort($aRemindersTime);
			foreach ($aRemindersTime as $iReminder)
			{
				if ($iReminder > $iNowTS)
				{
					return $iReminder;
				}
			}
		}
		return false;
	}

	public static function getNextRepeat(DateTime $sDtStart, $oVCal, $sUid = null)
	{
		$oRecur = new \Sabre\VObject\RecurrenceIterator($oVCal, $sUid);
		$oRecur->fastForward($sDtStart);
		return $oRecur->current();
	}

	/**
	 * @param int $iData
	 * @param int $iMin
	 * @param int $iMax
	 * @return bool
	 */
	public static function validate($iData, $iMin, $iMax)
	{
		if (null === $iData)
		{
			return false;
		}
		$iData = round($iData);
		return (isset($iMin) && isset($iMax)) ? ($iMin <= $iData && $iData <= $iMax) : ($iData > 0);
	}

	public static function PrepareDateTime($mDateTime, $sTimeZone, $bAllday = false)
	{
		$oDateTime = new \DateTime();
		if (is_numeric($mDateTime) && strlen($mDateTime) !== 8)
		{
			$oDateTime->setTimestamp($mDateTime);
		}	
		else
		{
			$oDateTime = \Sabre\VObject\DateTimeParser::parse($mDateTime);
		}
		$oDateTime->setTimezone(new DateTimeZone('UTC'));
		if ($bAllday)
		{
			$oDateTime->setTimezone(new DateTimeZone($sTimeZone));
		}

		return $oDateTime;
	}

	public static function GetDateTime($dt, $sTimeZone = 'UTC')
	{
		$result = null;
		if ($dt)
		{
			$result = $dt->getDateTime();
		}
		if (isset($result))
		{
			$result->setTimezone(new DateTimeZone($sTimeZone));
		}
		return $result;
	}

	public static function GetStrDate($dt, $sTimeZone, $format = 'Y-m-d H:i:s')
	{
		$result = null;
		$oDateTime = self::GetDateTime($dt, $sTimeZone);
		if ($oDateTime)
		{
			$result = $oDateTime->format($format);
		}
		return $result;
	}

	public static function DateTimeToStr($dt, $format = 'Y-m-d H:i:s')
	{
		return $dt->format($format);
	}

	
	public static function getRecurrenceId($oComponent)
	{
		$oRecurrenceId = $oComponent->DTSTART;
		if ($oComponent->{'RECURRENCE-ID'})
		{
			$oRecurrenceId = $oComponent->{'RECURRENCE-ID'};
		}
		$dRecurrence = $oRecurrenceId->getDateTime();
		return $dRecurrence->getTimestamp();
	}
	
	public static function isRecurrenceExists($oVEvent, $sRecurrenceId)
	{
		$mResult = false;
		foreach($oVEvent as $mKey => $oEvent)
		{
			if (isset($oEvent->{'RECURRENCE-ID'}))
			{
				$recurrenceId = (string) self::getRecurrenceId($oEvent);

				if ($recurrenceId === $sRecurrenceId)
				{
					$mResult = $mKey;
					break;
				}
			}
		}

		return $mResult;
	}

    /**
	 * @param DateInterval $oInterval
	 * @return string
	 */
	public static function GetOffsetInMinutes($oInterval)
	{
 		$aIntervals = array(5,10,15,30,60,120,180,240,300,360,420,480,540,600,660,720,1080,1440,2880,4320,5760,10080,20160);
		$iMinutes = 0;
		try
		{
			$iMinutes = $oInterval->i + $oInterval->h*60 + $oInterval->d*24*60;
		}
		catch (Exception $ex)
		{
			$iMinutes = 15;
		}
		if (!in_array($iMinutes, $aIntervals))
		{
			$iMinutes = 15;
		}

		return $iMinutes;
	}

    /**
	 * @param string $iMinutes
	 * @return string
	 */
	public static function GetOffsetInStr($iMinutes)
	{
		return '-PT' . $iMinutes . 'M';
	}

	public static function GetBaseVEventIndex($oVEvents)
	{
		$iIndex = -1;
		foreach($oVEvents as $oVEvent)
		{
			$iIndex++;
			if (empty($oVEvent->{'RECURRENCE-ID'}))
			{
				break;
			}
		}
		return ($iIndex >= 0) ? $iIndex : false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sTo
	 * @param string $sSubject
	 * @param string $sBody
	 * @param string $sMethod = null
	 * @param bool $bAllDay = false
	 * @return WebMailMessage
	 */
	public static function BuildAppointmentMessage($oAccount, $sTo, $sSubject, $sBody, $sMethod = null, $bAllDay = false, $sHtmlBody)
	{
		$oMessage = null;
		if ($oAccount && !empty($sTo) && !empty($sBody))
		{
			$oMessage = \MailSo\Mime\Message::NewInstance();
			$oMessage->RegenerateMessageId();
			$oMessage->DoesNotCreateEmptyTextPart();

			$sXMailer = \CApi::GetConf('webmail.xmailer-value', '');
			if (0 < strlen($sXMailer))
			{
				$oMessage->SetXMailer($sXMailer);
			}

			$oMessage
				->SetFrom(\MailSo\Mime\Email::NewInstance($oAccount->Email))
				->SetSubject($sSubject)
			;

			$oMessage->AddHtml($sHtmlBody);

			$oToEmails = \MailSo\Mime\EmailCollection::NewInstance($sTo);
			if ($oToEmails && $oToEmails->Count())
			{
				$oMessage->SetTo($oToEmails);
			}
			
			if ($sMethod)
			{
				$oMessage->SetCustomHeader('Method', $sMethod);
			}

			$oMessage->Attachments()->Add(
				\MailSo\Mime\Attachment::NewInstance(
					\MailSo\Base\ResourceRegistry::CreateMemoryResourceFromString($sBody), 'invite.ics', strlen($sBody),
						false, false, '', null === $sMethod ? array() : array('method' => $sMethod))
			);
		}

		return $oMessage;
	}

	/**
	 * @param \CAccount $oAccount
	 * @param string $sTo
	 * @param string $sSubject
	 * @param string $sBody
	 * @param string $sMethod
	 * @param bool $bAllDay
	 * @return WebMailMessage
	 */
	public static function SendAppointmentMessage($oAccount, $sTo, $sSubject, $sBody, $sMethod, $bAllDay = false, $sHtmlBody='')
	{
		$oMessage = self::BuildAppointmentMessage($oAccount, $sTo, $sSubject, $sBody, $sMethod, $bAllDay, $sHtmlBody);

		CApi::Plugin()->RunHook('webmail-change-appointment-message-before-send',
			array(&$oMessage, &$oAccount));

		if ($oMessage)
		{
			try
			{
				$oApiMail = CApi::Manager('mail');
				CApi::Log('IcsAppointmentActionSendOriginalMailMessage');
				return $oApiMail ? $oApiMail->MessageSend($oAccount, $oMessage) : false;
			}
			catch (\CApiManagerException $oException)
			{
				$iCode = \ProjectSeven\Notifications::CanNotSendMessage;
				switch ($oException->getCode())
				{
					case Errs::Mail_InvalidRecipients:
						$iCode = \ProjectSeven\Notifications::InvalidRecipients;
						break;
				}

				throw new \ProjectSeven\Exceptions\ClientException($iCode, $oException);
			}
		}

		return false;
	}

	/**
	 * @param \CAccount $oAccount
	 * @param \CEvent $oEvent
	 * @param \Sabre\VObject\Component\VEvent $oVEvent
	 */
	public static function PopulateVCalendar($oAccount, $oEvent, &$oVEvent)
	{
		unset($oVEvent->{'LAST-MODIFIED'});
		$oVEvent->add('LAST-MODIFIED',  new \DateTime('now'));

		$oVCal =& $oVEvent->parent;

		$oVEvent->UID = $oEvent->Id;

		if (!empty($oEvent->Start) && !empty($oEvent->End))
		{
			$oDTStart = self::PrepareDateTime(
				$oEvent->Start,
				$oAccount->GetDefaultStrTimeZone(),
				$oEvent->AllDay
			);
			$oDTEnd = self::PrepareDateTime(
				$oEvent->End,
				$oAccount->GetDefaultStrTimeZone(),
				$oEvent->AllDay
			);
			$aDateTimeValue = ($oEvent->AllDay) ? array('VALUE' => 'DATE') : array();
			$sDateTimeFormat = ($oEvent->AllDay) ? 'Ymd' : 'Ymd\\THis\\Z';

			if (isset($oDTStart))
			{
				unset($oVEvent->DTSTART);
				$oVEvent->add('DTSTART', $oDTStart->format($sDateTimeFormat), $aDateTimeValue);
			}
			if (isset($oDTEnd))
			{
				unset($oVEvent->DTEND);
				$oVEvent->add('DTEND', $oDTEnd->format($sDateTimeFormat), $aDateTimeValue);
			}
		}

		if (isset($oEvent->Name))
		{
			$oVEvent->SUMMARY = $oEvent->Name;
		}
		if (isset($oEvent->Description))
		{
			$oVEvent->DESCRIPTION = $oEvent->Description;
		}
		if (isset($oEvent->Location))
		{
			$oVEvent->LOCATION = $oEvent->Location;
		}

		unset($oVEvent->RRULE);
		if (isset($oEvent->RRule))
		{
			$sRRULE = '';
			if (isset($oVEvent->RRULE) && null === $oEvent->RRule)
			{
				$oRRule = \CalendarParser::ParseRRule($oAccount, $oVCal, (string)$oVEvent->UID);
				if ($oRRule && $oRRule instanceof \CRRule)
				{
					$sRRULE = (string) $oRRule;
				}
			}
			else
			{
				$sRRULE = (string)$oEvent->RRule;
			}
			if (trim($sRRULE) !== '')
			{
				$oVEvent->add('RRULE', $sRRULE);
			}
		}

		unset($oVEvent->VALARM);
		if (isset($oEvent->Alarms))
		{
			foreach ($oEvent->Alarms as $sOffset)
			{
				$oVEvent->add('VALARM', array(
					'TRIGGER' => self::GetOffsetInStr($sOffset),
					'DESCRIPTION' => 'Alarm',
					'ACTION' => 'DISPLAY'
				));
			}
		}

		$ApiCapabilityManager = CApi::Manager('capability');
		if ($ApiCapabilityManager->IsCalendarAppointmentsSupported($oAccount))
		{
			$aAttendees = array();
			$aAttendeeEmails = array();
			$aObjAttendees = array();
			if (isset($oVEvent->ATTENDEE))
			{
				$aAttendeeEmails = array();
				foreach ($oEvent->Attendees as $aItem)
				{
					$sStatus = '';
					switch ($aItem['status']) 
					{
						case \EAttendeeStatus::Accepted:
							$sStatus = 'ACCEPTED';
							break;
						case \EAttendeeStatus::Declined:
							$sStatus = 'DECLINED';
							break;
						case \EAttendeeStatus::Tentative:
							$sStatus = 'TENTATIVE';
							break;
						case \EAttendeeStatus::Unknown:
							$sStatus = 'NEEDS-ACTION';
							break;
					}
					
					$aAttendeeEmails[strtolower($aItem['email'])] = $sStatus;
				}
				
				$aObjAttendees = $oVEvent->ATTENDEE;
				unset($oVEvent->ATTENDEE);
				foreach($aObjAttendees as $oAttendee)
				{
					$sAttendee = str_replace('mailto:', '', strtolower((string)$oAttendee));
					$oPartstat = $oAttendee->offsetGet('PARTSTAT');
					if (in_array($sAttendee, array_keys($aAttendeeEmails)))
					{
						if (isset($oPartstat) && (string)$oPartstat === $aAttendeeEmails[$sAttendee])
						{
							$oVEvent->add($oAttendee);
							$aAttendees[] = $sAttendee;
						}
					}
					else
					{
						if (!isset($oPartstat) || (isset($oPartstat) && (string)$oPartstat != 'DECLINED'))
						{
							$oVCal->METHOD = 'CANCEL';
							$sSubject = (string)$oVEvent->SUMMARY . ': Canceled';
							self::SendAppointmentMessage($oAccount, $sAttendee, $sSubject, $oVCal->serialize(), (string)$oVCal->METHOD);
							unset($oVCal->METHOD);
						}
					}
				}
			}
			
			if (count($oEvent->Attendees) > 0)
			{
				if (!isset($oVEvent->ORGANIZER))
				{
					$oVEvent->ORGANIZER = 'mailto:' . $oAccount->Email;
				}
				foreach($oEvent->Attendees as $oAttendee)
				{
					if (!in_array($oAttendee['email'], $aAttendees))
					{
						$oVEvent->add(
							'ATTENDEE', 
							'mailto:' . $oAttendee['email'], 
							array(
								'CN'=>$oAttendee['name']
							)
						);
					}
				}
			}
			else 
			{
				unset($oVEvent->ORGANIZER);
			}

			if (isset($oVEvent->ATTENDEE))
			{
				foreach($oVEvent->ATTENDEE as $oAttendee)
				{
					$sAttendee = str_replace('mailto:', '', strtolower((string)$oAttendee));
					
					if (($sAttendee !== $oAccount->Email) && 
						(!isset($oAttendee['PARTSTAT']) || 
							(isset($oAttendee['PARTSTAT']) 
								&& (string)$oAttendee['PARTSTAT'] !== 'DECLINED')))
					{
						$oAttendee['PARTSTAT'] = 'NEEDS-ACTION';
						$oAttendee['RSVP'] = 'TRUE';

						$oApiCalendar = \CApi::Manager('calendar', 'sabredav');
						
						
						$sStartDateFormat = $oVEvent->DTSTART->hasTime() ? 'D, F d, o, H:i' : 'D, F d, o';
						$sStartDate = self::GetStrDate($oVEvent->DTSTART, $oAccount->GetDefaultStrTimeZone(), $sStartDateFormat);				
						
						$oCalendar = $oApiCalendar->GetCalendar($oAccount, $oEvent->IdCalendar);
						$sHtml = self::CreateHtmlFromEvent($oEvent, $oAccount->Email, $sAttendee, $oCalendar->DisplayName, $sStartDate);

						$oVCal->METHOD = 'REQUEST';
						self::SendAppointmentMessage($oAccount, $sAttendee, (string)$oVEvent->SUMMARY, $oVCal->serialize(), (string)$oVCal->METHOD, false, $sHtml);
						unset($oVCal->METHOD);
					}
				}
			}
		}
	}

	public static function CreateHtmlFromEvent($oEvent, $sAccountEmail, $sAttendee, $sCalendarName, $sStartDate)
	{
		$aValues = array(
			'attendee' => $sAttendee,
			'organizer' => $sAccountEmail,
			'calendarId' => $oEvent->IdCalendar,
			'eventId' => $oEvent->Id
		);
		
		$aValues['action'] = 'ACCEPTED';
		$sEncodedValueAccept = \CApi::EncodeKeyValues($aValues);
		$aValues['action'] = 'TENTATIVE';
		$sEncodedValueTentative = \CApi::EncodeKeyValues($aValues);
		$aValues['action'] = 'DECLINED';
		$sEncodedValueDecline = \CApi::EncodeKeyValues($aValues);

		$sHref = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?invite=';
		$sHtml = file_get_contents(PSEVEN_APP_ROOT_PATH.'templates/Invite.html');
		$sHtml = strtr($sHtml, array(
			'{{INVITE/CALENDAR}}'	=> \CApi::I18N('INVITE/CALENDAR'),
			'{{INVITE/LOCATION}}'	=> \CApi::I18N('INVITE/LOCATION'),
			'{{INVITE/WHEN}}'		=> \CApi::I18N('INVITE/WHEN'),
			'{{INVITE/DESCRIPTION}}'=> \CApi::I18N('INVITE/DESCRIPTION'),
			'{{INVITE/INFORMATION}}'=> \CApi::I18N('INVITE/INFORMATION', array('Email' => $sAttendee)),
			'{{INVITE/ACCEPT}}'		=> \CApi::I18N('INVITE/ACCEPT'),
			'{{INVITE/TENTATIVE}}'	=> \CApi::I18N('INVITE/TENTATIVE'),
			'{{INVITE/DECLINE}}'	=> \CApi::I18N('INVITE/DECLINE'),
			'{{Calendar}}'			=> $sCalendarName.' '.$sAccountEmail,
			'{{Location}}'			=> $oEvent->Location,
			'{{Start}}'				=> $sStartDate,
			'{{Description}}'		=> $oEvent->Description,
			'{{HrefAccept}}'		=> $sHref.$sEncodedValueAccept,
			'{{HrefTentative}}'		=> $sHref.$sEncodedValueTentative,
			'{{HrefDecline}}'		=> $sHref.$sEncodedValueDecline
		));

		return $sHtml;
	}
	
	public static function FindGroupsHashTagsFromString($sString)
	{
		$aResult = array();
		
		preg_match_all("/[#]([^#\s]+)/", $sString, $aMatches);
		
		if (\is_array($aMatches) && isset($aMatches[0]) && \is_array($aMatches[0]) && 0 < \count($aMatches[0]))
		{
			$aResult = $aMatches[0];
		}
		
		return $aResult;
	}
	
}
