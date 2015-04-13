<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

defined('P7_ROOTPATH') || define('P7_ROOTPATH', (dirname(__FILE__).'/../'));

include_once P7_ROOTPATH.'libraries/afterlogic/api.php';

class CReminder
{
	/**
	 * @var CApiUsersManager
	 */
	private $oApiUsersManager;

	/**
	 * @var CApiCalendarManager
	 */
	private $oApiCalendarManager;

	/**
	 * @var CApiMailManager
	 */
	private $oApiMailManager;

	/**
	 * @var array
	 */
	private $aAccounts;

	/**
	 * @var array
	 */
	private $aCalendars;

	/**
	 * @var string
	 */
	private $sCurRunFilePath;

	/**
	 * @var string
	 */
	private $sLang;

	public function __construct()
	{
		$oSettings =& CApi::GetSettings();
		
		$this->aAccounts = array();
		$this->aCalendars = array();
		$this->sCurRunFilePath = CApi::DataPath().'/reminder-run';
		$this->sLang = $oSettings->GetConf('Common/DefaultLanguage');

		$this->oApiUsersManager = CApi::Manager('users');
		$this->oApiCalendarManager = CApi::Manager('calendar');
		$this->oApiMailManager = CApi::Manager('mail');
	}
	
	public static function NewInstance()
	{
		return new self();
	}
	
	/**
	 * @param string $sKey
	 * @param CAccount $oAccount = null
	 * @param array $aParams = null
	 *
	 * @return string
	 */
	private function i18n($sKey, $oAccount = null, $aParams = null, $iMinutes = null)
	{
		return CApi::ClientI18N($sKey, $oAccount, $aParams, $iMinutes);
	}

	/**
	 * @param string $sLogin
	 *
	 * @return CAccount
	 */
	private function &getAccount($sLogin)
	{
		$mResult = null;

		if (!isset($this->aAccounts[$sLogin]))
		{
			$this->aAccounts[$sLogin] = $this->oApiUsersManager->GetAccountOnLogin($sLogin);
		}

		$mResult =& $this->aAccounts[$sLogin];

		if (30 < count($this->aAccounts[$sLogin]))
		{
			$this->aAccounts = array_slice($this->aAccounts, -30);
		}

		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sUri
	 *
	 * @return CalendarInfo|null
	 */
	private function &getCalendar($oAccount, $sUri)
	{
		$mResult = null;
		if ($this->oApiCalendarManager)
		{
			if (!isset($this->aCalendars[$sUri]))
			{
				$this->aCalendars[$sUri] = $this->oApiCalendarManager->GetCalendar($oAccount, $sUri);
			}

			if (isset($this->aCalendars[$sUri]))
			{
				$mResult =& $this->aCalendars[$sUri];
			}
		}

		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sEventName
	 * @param string $sDateStr
	 * @param string $sCalendarName
	 * @param string $sEventText
	 * @param string $sCalendarColor
	 *
	 * @return string
	 */
	private function createBodyHtml($oAccount, $sEventName, $sDateStr, $sCalendarName, $sEventText, $sCalendarColor)
	{
		$sEventText = nl2br($sEventText);

		return sprintf('
			<div style="padding: 10px; font-size: 12px; text-align: center; word-wrap: break-word;">
				<div style="border: 4px solid %s; padding: 15px; width: 370px;">
					<h2 style="margin: 5px; font-size: 18px; line-height: 1.4;">%s</h2>
					<span>%s%s</span><br/>
					<span>%s: %s</span><br/><br/>
					<span>%s</span><br/>
				</div>
				<p style="color:#667766; width: 400px; font-size: 10px;">%s</p>
			</div>',
			$sCalendarColor,
			$sEventName,
			ucfirst($this->i18n('REMINDERS/EVENT_BEGIN', $oAccount)),
			$sDateStr,
			$this->i18n('REMINDERS/CALENDAR', $oAccount),
			$sCalendarName,
			$sEventText,
			$this->i18n('REMINDERS/EMAIL_EXPLANATION', $oAccount, array(
				'EMAIL' => '<a href="mailto:'.$oAccount->Email.'">'.$oAccount->Email.'</a>',
				'CALENDAR_NAME' => $sCalendarName
			))
		);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sEventName
	 * @param string $sDateStr
	 * @param string $sCalendarName
	 * @param string $sEventText
	 *
	 * @return string
	 */
	private function createBodyText($oAccount, $sEventName, $sDateStr, $sCalendarName, $sEventText)
	{
		return sprintf("%s\r\n\r\n%s%s\r\n\r\n%s: %s %s\r\n\r\n%s",
			$sEventName,
			ucfirst($this->i18n('REMINDERS/EVENT_BEGIN', $oAccount)),
			$sDateStr,
			$this->i18n('REMINDERS/CALENDAR', $oAccount),
			$sCalendarName,
			$sEventText,
			$this->i18n('REMINDERS/EMAIL_EXPLANATION', $oAccount, array(
				'EMAIL' => '<a href="mailto:'.$oAccount->Email.'">'.$oAccount->Email.'</a>',
				'CALENDAR_NAME' => $sCalendarName
			))
		);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sSubject
	 * @param string $mHtml = null
	 * @param string $mText = null
	 *
	 * @return \MailSo\Mime\Message
	 */
	private function createMessage($oAccount, $sSubject, $mHtml = null, $mText = null)
	{
		$oMessage = \MailSo\Mime\Message::NewInstance();
		$oMessage->RegenerateMessageId();

		$sXMailer = CApi::GetConf('webmail.xmailer-value', '');
		if (0 < strlen($sXMailer))
		{
			$oMessage->SetXMailer($sXMailer);
		}

		$oMessage
			->SetFrom(\MailSo\Mime\Email::NewInstance($oAccount->Email))
			->SetSubject($sSubject)
		;

		$oToEmails = \MailSo\Mime\EmailCollection::NewInstance($oAccount->Email);
		if ($oToEmails && $oToEmails->Count())
		{
			$oMessage->SetTo($oToEmails);
		}

		if ($mHtml !== null)
		{
			$oMessage->AddText($mHtml, true);
		}

		if ($mText !== null)
		{
			$oMessage->AddText($mText, false);
		}

		return $oMessage;
	}

	/**
	 *
	 * @param CAccount $oAccount
	 * @param string $sSubject
	 * @param string $sEventName
	 * @param string $sDate
	 * @param string $sCalendarName
	 * @param string $sEventText
	 * @param string $sCalendarColor
	 *
	 * @return bool
	 */
	private function sendMessage($oAccount, $sSubject, $sEventName, $sDate, $sCalendarName, $sEventText, $sCalendarColor)
	{
		$oMessage = $this->createMessage($oAccount, $sSubject,
			$this->createBodyHtml($oAccount, $sEventName, $sDate, $sCalendarName, $sEventText, $sCalendarColor),
			$this->createBodyText($oAccount, $sEventName, $sDate, $sCalendarName, $sEventText));

		try
		{
			return $this->oApiMailManager->MessageSend($oAccount, $oMessage);
		}
		catch (Exception $oException)
		{
			CApi::Log('MessageSend Exception', ELogLevel::Error, 'cron-');
			CApi::LogException($oException, ELogLevel::Error, 'cron-');
		}

		return false;
	}
	
	private function getSubject($oAccount, $sEventStart, $iEventStartTS, $sEventName, $sDate, $iNowTS, $bAllDay = false)
	{
		$sSubject = '';
		
		if ($bAllDay)
		{
			$oEventStart = new \DateTime("@$iEventStartTS", new \DateTimeZone('UTC'));
			$oEventStart->setTimezone(new \DateTimeZone($oAccount->GetDefaultStrTimeZone()));
			$iEventStartTS = $oEventStart->getTimestamp() - $oEventStart->getOffset();
		}
		
		$iMinutes = round(($iEventStartTS - $iNowTS) / 60);
		
		if ($iMinutes > 0 && $iMinutes < 60)
		{
			$sSubject = $this->i18n('REMINDERS/SUBJECT_MINUTES_PLURAL', $oAccount, array(
				'EVENT_NAME' => $sEventName,
				'DATE' => date('G:i', strtotime($sEventStart)),
				'COUNT' => $iMinutes
			), $iMinutes);
		}
		else if ($iMinutes >= 60 && $iMinutes < 1440)
		{
			$sSubject = $this->i18n('REMINDERS/SUBJECT_HOURS_PLURAL', $oAccount, array(
				'EVENT_NAME' => $sEventName,
				'DATE' => date('G:i', strtotime($sEventStart)),
				'COUNT' => round($iMinutes / 60)
			), round($iMinutes / 60));
		}
		else if ($iMinutes >= 1440 && $iMinutes < 10080)
		{
			$sSubject = $this->i18n('REMINDERS/SUBJECT_DAYS_PLURAL', $oAccount, array(
				'EVENT_NAME' => $sEventName,
				'DATE' => $sDate,
				'COUNT' => round($iMinutes / 1440)
			), round($iMinutes / 1440));
		}
		else if ($iMinutes >= 10080)
		{
			$sSubject = $this->i18n('REMINDERS/SUBJECT_WEEKS_PLURAL', $oAccount, array(
				'EVENT_NAME' => $sEventName,
				'DATE' => $sDate,
				'COUNT' => round($iMinutes / 10080)
			), round($iMinutes / 10080));
		}
		else
		{
			$sSubject = $this->i18n('REMINDERS/SUBJECT', $oAccount, array(
				'EVENT_NAME' => $sEventName,
				'DATE' => $sDate
			));
		}		
		
		return $sSubject;
	}
	
	private function getDateTimeFormat($oAccount) 
	{
		$sDateFormat = 'm/d/Y';
		$sTimeFormat = 'h:i A';

		if ($oAccount->User->DefaultDateFormat === EDateFormat::DDMMYYYY)
		{
			$sDateFormat = 'd/m/Y';
		}
		else if ($oAccount->User->DefaultDateFormat === EDateFormat::DD_MONTH_YYYY)
		{
			$sDateFormat = 'd m Y';
		}

		if ($oAccount->User->DefaultTimeFormat == ETimeFormat::F24)
		{
			$sTimeFormat = 'H:i';
		}
		
		return $sDateFormat.' '.$sTimeFormat;
	}
	
	public function GetReminders($iStart, $iEnd)
	{
		$aReminders = $this->oApiCalendarManager->GetReminders($iStart, $iEnd);
		$aEvents = array();

		if ($aReminders && is_array($aReminders) && count($aReminders) > 0)
		{
			$aCacheEvents = array();
			foreach($aReminders as $aReminder)
			{
				$oAccount = $this->getAccount($aReminder['user']);

				$sCalendarUri = $aReminder['calendaruri'];
				$sEventId = $aReminder['eventid'];
				$iStartTime = $aReminder['starttime'];

				if (!isset($aCacheEvents[$sEventId]) && isset($oAccount))
				{
					$aCacheEvents[$sEventId]['data'] = $this->oApiCalendarManager->GetEvent($oAccount, $sCalendarUri, $sEventId);

					$dt = new \DateTime();
					$dt->setTimestamp($iStartTime);
					$sDefaultTimeZone = new \DateTimeZone($oAccount->GetDefaultStrTimeZone());
					$dt->setTimezone($sDefaultTimeZone);

					$aCacheEvents[$sEventId]['time'] = $dt->format($this->getDateTimeFormat($oAccount));
				}

				if (isset($aCacheEvents[$sEventId]))
				{
					$aEvents[$aReminder['user']][$sCalendarUri][$sEventId] = $aCacheEvents[$sEventId];
				}
			}
		}
		return $aEvents;
	}

	public function Execute()
	{
		CApi::Log('---------- Start cron script', ELogLevel::Full, 'cron-');

		$oTimeZoneUTC = new \DateTimeZone('UTC');
		$oNowDT_UTC = new \DateTime('now', $oTimeZoneUTC);
		$iNowTS_UTC = $oNowDT_UTC->getTimestamp();

		$oStartDT_UTC = clone $oNowDT_UTC;
		$oStartDT_UTC->sub(new DateInterval('PT30M'));

		if (file_exists($this->sCurRunFilePath))
		{
			$handle = fopen($this->sCurRunFilePath, 'r');
			$sCurRunFileTS = fread($handle, 10);
			if (!empty($sCurRunFileTS) && is_numeric($sCurRunFileTS))
			{
				$oStartDT_UTC = new \DateTime("@$sCurRunFileTS");
			}
		}

		$iStartTS_UTC = $oStartDT_UTC->getTimestamp();

		if ($iNowTS_UTC >= $iStartTS_UTC)
		{
			CApi::Log('Start time: '.$oStartDT_UTC->format('r'), ELogLevel::Full, 'cron-');
			CApi::Log('End time: '.$oNowDT_UTC->format('r'), ELogLevel::Full, 'cron-');
			
			$aEvents = $this->GetReminders($iStartTS_UTC, $iNowTS_UTC);

			foreach ($aEvents as $sEmail => $aUserCalendars)
			{
				foreach ($aUserCalendars as $sCalendarUri => $aUserEvents)
				{
					foreach ($aUserEvents as $aUserEvent)
					{
						$aSubEvents = $aUserEvent['data'];

						if (isset($aSubEvents, $aSubEvents['vcal']))
						{
							$vCal = $aSubEvents['vcal'];
							foreach ($aSubEvents as $mKey => $aEvent)
							{
								if ($mKey !== 'vcal')
								{
									$oAccount = $this->getAccount($sEmail);
									$oCalendar = $this->getCalendar($oAccount, $sCalendarUri);

									if ($oCalendar)
									{
										$sEventId = $aEvent['uid'];
										$sEventStart = $aEvent['start'];
										$iEventStartTS = $aEvent['startTS'];
										$sEventName = $aEvent['subject'];
										$sEventText = $aEvent['description'];
										$bAllDay = $aEvent['allDay'];

										$sDate = $aUserEvent['time'];
										
										$sSubject = $this->getSubject($oAccount, $sEventStart, $iEventStartTS, $sEventName, $sDate, $iNowTS_UTC, $bAllDay);
										
										$aAccounts = array();
										$aAccounts[] = $oAccount;
										
										$aCalendarUsers = $this->oApiCalendarManager->GetCalendarUsers($oAccount, $oCalendar);
										if (0 < count($aCalendarUsers))
										{
											foreach ($aCalendarUsers as $aCalendarUser)
											{
												$oCalendarAccount = $this->getAccount($aCalendarUser['email']);
												if ($oCalendarAccount)
												{
													$aAccounts[] = $oCalendarAccount;
												}
											}
										}
										
										foreach ($aAccounts as $oAccountItem)
										{
											$bIsMessageSent = $this->sendMessage($oAccountItem, $sSubject, $sEventName, $sDate, $oCalendar->DisplayName, $sEventText, $oCalendar->Color);
											if ($bIsMessageSent)
											{
												$this->oApiCalendarManager->UpdateReminder($oAccountItem->Email, $sCalendarUri, $sEventId, $vCal->serialize());
												CApi::Log('Send reminder for event: \''.$sEventName.'\' started on \''.$sDate.'\' to \''.$oAccountItem->Email.'\'', \ELogLevel::Full, 'cron-');
											}
											else
											{
												CApi::Log('Send reminder for event: FAILED!', ELogLevel::Full, 'cron-');
											}
										}
									}
									else
									{
										CApi::Log('Calendar '.$sCalendarUri.' not found!', ELogLevel::Full, 'cron-');
									}
								}
							}
						}
					}
				}
			}

			file_put_contents($this->sCurRunFilePath, $iNowTS_UTC);
		}

		CApi::Log('---------- End cron script', ELogLevel::Full, 'cron-');
	}
}

$iTimer = microtime(true);

\CReminder::NewInstance()->Execute();

CApi::Log('Cron execution time: '.(microtime(true) - $iTimer).' sec.', ELogLevel::Full, 'cron-');
