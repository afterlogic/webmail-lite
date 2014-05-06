<?php

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
<div style="padding: 10px; font-size: 12px; text-align: center;">
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

	public function Execute()
	{
		CApi::Log('---------- Start cron script', ELogLevel::Full, 'cron-');

		$oNowDT = new \DateTime('now', new \DateTimeZone('UTC'));
		$iNowTS = $oNowDT->getTimestamp();

		$oStartDT = clone $oNowDT;
		$oStartDT->sub(new DateInterval('PT30M'));

		if (file_exists($this->sCurRunFilePath))
		{
			$handle = fopen($this->sCurRunFilePath, 'r');
			$sCurRunFileTS = fread($handle, 10);
			if (!empty($sCurRunFileTS) && is_numeric($sCurRunFileTS))
			{
				$oStartDT = new \DateTime();
				$oStartDT->setTimestamp($sCurRunFileTS);
				$oStartDT->setTimezone(new \DateTimeZone('UTC'));
			}
		}

		$iStartTS = $oStartDT->getTimestamp();

		if ($iNowTS >= $iStartTS)
		{
			CApi::Log('Start time: '.$oStartDT->format('r'), ELogLevel::Full, 'cron-');
			CApi::Log('End time: '.$oNowDT->format('r'), ELogLevel::Full, 'cron-');

			$aReminders = $this->oApiCalendarManager->GetReminders($iStartTS, $iNowTS);
			$aEvents = array();

			if ($aReminders && is_array($aReminders) && count($aReminders) > 0)
			{
				$aCacheEvents = array();
				foreach($aReminders as $aItem)
				{
					$oAccount = $this->getAccount($aItem['user']);

					$sCalendarUri = $aItem['calendaruri'];
					$sEventId = $aItem['eventid'];
					$iStartTime = $aItem['starttime'];

					if (!isset($aCacheEvents[$sEventId]) && isset($oAccount))
					{
						$aCacheEvents[$sEventId]['data'] = $this->oApiCalendarManager->GetEvent($oAccount, $sCalendarUri, $sEventId);

						$dt = new \DateTime();
						$dt->setTimestamp($iStartTime);
						$dt->setTimezone(new \DateTimeZone($oAccount->GetDefaultStrTimeZone()));

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

						$aCacheEvents[$sEventId]['time'] = $dt->format($sDateFormat.' '.$sTimeFormat);
					}

					if (isset($aCacheEvents[$sEventId]))
					{
						$aEvents[$aItem['user']][$sCalendarUri][$sEventId] = $aCacheEvents[$sEventId];
					}
				}
			}

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
										$aCalendarUsers = $this->oApiCalendarManager->GetCalendarUsers($oAccount, $oCalendar);

										$sEventId = $aEvent['uid'];
										$sEventStart = $aEvent['start'];
										$iEventStartTS = $aEvent['startTS'];
										$sDate = $aUserEvent['time'];

										$sEventName = $aEvent['subject'];
										$sEventText = $aEvent['description'];

										$sCalendarName = $oCalendar->DisplayName;
										$sCalendarColor = $oCalendar->Color;

										$iMinutes = round(($iEventStartTS - $iNowTS)/60);

										if ($iMinutes > 0 && $iMinutes < 60)
										{
											$sSubject = $this->i18n('REMINDERS/SUBJECT_MINUTES_PLURAL', $oAccount, array(
												'EVENT_NAME' => $sEventName,
												'DATE' => date('G:i', strtotime ($sEventStart)),
												'COUNT' => $iMinutes
											), $iMinutes);
										}
										else if ($iMinutes >= 60 && $iMinutes < 1440)
										{
											$sSubject = $this->i18n('REMINDERS/SUBJECT_HOURS_PLURAL', $oAccount, array(
												'EVENT_NAME' => $sEventName,
												'DATE' => date('G:i', strtotime ($sEventStart)),
												'COUNT' => $iMinutes/60
											), $iMinutes/60);
										}
										else if ($iMinutes >= 1440 && $iMinutes < 10080)
										{
											$sSubject = $this->i18n('REMINDERS/SUBJECT_DAYS_PLURAL', $oAccount, array(
												'EVENT_NAME' => $sEventName,
												'DATE' => $sDate,
												'COUNT' => $iMinutes/1440
											), $iMinutes/1440);
										}
										else if ($iMinutes >= 10080)
										{
											$sSubject = $this->i18n('REMINDERS/SUBJECT_WEEKS_PLURAL', $oAccount, array(
												'EVENT_NAME' => $sEventName,
												'DATE' => $sDate,
												'COUNT' => $iMinutes/10080
											), $iMinutes/10080);
										}
										else
										{
											$sSubject = $this->i18n('REMINDERS/SUBJECT', $oAccount, array(
												'EVENT_NAME' => $sEventName,
												'DATE' => $sDate
											));
										}

										$bIsMessageSent = $this->sendMessage($oAccount, $sSubject, $sEventName, $sDate, $sCalendarName, $sEventText, $sCalendarColor);

										if ($bIsMessageSent)
										{
											$this->oApiCalendarManager->UpdateReminder($oAccount->Email, $sCalendarUri, $sEventId, $vCal->serialize());
											CApi::Log('Send reminder for event: \''.$sEventName.'\' started on \''.$sDate.'\' to \''.$oAccount->Email.'\'', \ELogLevel::Full, 'cron-');
										}
										else
										{
											CApi::Log('Send reminder for event: FAILED!', ELogLevel::Full, 'cron-');
										}

										if (0 < count($aCalendarUsers))
										{
											foreach ($aCalendarUsers as $aCalendarUser)
											{
												$oCalendarAccount = $this->getAccount($aCalendarUser['email']);
												if ($oCalendarAccount)
												{
													$this->sendMessage($oCalendarAccount, $sSubject, $sEventName, $sDate, $sCalendarName, $sEventText, $sCalendarColor);
												}
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

			file_put_contents($this->sCurRunFilePath, $iNowTS);
		}

		CApi::Log('---------- End cron script', ELogLevel::Full, 'cron-');
	}
}

$iTimer = microtime(true);

$oReminder = new CReminder();
$oReminder->Execute();

CApi::Log('Cron execution time: '.(microtime(true) - $iTimer).' sec.', ELogLevel::Full, 'cron-');
