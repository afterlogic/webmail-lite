<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property mixed  $Id
 * @property mixed  $IdCalendar
 * @property string $Start
 * @property string $End
 * @property bool   $AllDay
 * @property string $Name
 * @property string $Description
 * @property CRRule $RRule
 * @property array  $Alarms
 * @property array  $Attendees;
 * @property bool $Deleted;
 * @property bool $Modified;
 *
 * @package Calendar
 * @subpackage Classes
 */
class CEvent
{
	public $Id;
	public $IdCalendar;
	public $Start;
	public $End;
	public $AllDay;
	public $Name;
	public $Description;
	public $Location;
	public $RRule;
	public $Alarms;
	public $Attendees;
    public $Deleted;
	public $Modified;

	public function __construct()
	{
		$this->Id			  = null;
		$this->IdCalendar	  = null;
		$this->Start		  = null;
		$this->End			  = null;
		$this->AllDay		  = false;
		$this->Name			  = null;
		$this->Description	  = null;
		$this->Location		  = null;
		$this->RRule		  = null;
		$this->Alarms		  = array();
		$this->Attendees	  = array();
		$this->Deleted		  = null;
		$this->Modified		  = false;
	}
}

/**
 * @property mixed $IdRecurrence;
 * @property mixed $IdRepeat
 * @property string $StartTime;
 * @property bool $Deleted;
 *
 * @package Calendar
 * @subpackage Classes
 */
class CExclusion
{
	public $IdRecurrence;
	public $IdRepeat;
	public $StartTime;
    public $Deleted;

	public function __construct()
	{
		$this->IdRecurrence = null;
		$this->IdRepeat   = null;
		$this->StartTime  = null;
		$this->Deleted    = null;
	}
}

class CRRule
{
	protected $Account;

	public $StartBase;
	public $EndBase;
	public $Period;
	public $Count;
	public $Until;
	public $Interval;
	public $End;
	public $WeekNum;
	public $ByDays;
	
	public function __construct($oAccount)
	{
		$this->Account = $oAccount;

		$this->StartBase  = null;
		$this->EndBase    = null;
		$this->Period	  = null;
		$this->Count	  = null;
		$this->Until	  = null;
		$this->Interval	  = null;
		$this->End		  = null;
		$this->WeekNum	  = null;
		$this->ByDays	  = array();
	}
	
	public function Populate($aRRule)
	{
		if (isset($aRRule['period']))
		{
			$this->Period = $aRRule['period'];
		}
		if (isset($aRRule['count']))
		{
			$this->Count = $aRRule['count'];
		}
		if (isset($aRRule['until']))
		{
			$this->Until = $aRRule['until'];
		}
		if (isset($aRRule['interval']))
		{
			$this->Interval = $aRRule['interval'];
		}
		if (isset($aRRule['end']))
		{
			$this->End = $aRRule['end'];
		}
		if (isset($aRRule['weekNum']))
		{
			$this->WeekNum = $aRRule['weekNum'];
		}
		if (isset($aRRule['byDays']))
		{
			$this->ByDays = $aRRule['byDays'];
		}
	}
	
	public function GetTimeZone()
	{
		return $this->Account->GetDefaultStrTimeZone();
	}
	
	public function toArray()
	{
		return array(
			'startBase' => $this->StartBase,
			'endBase' => $this->EndBase,
			'period' => $this->Period,
			'interval' => $this->Interval,
			'end' => !isset($this->End) ? 0 : $this->End,
			'until' => $this->Until,
			'weekNum' => $this->WeekNum,
			'count' => $this->Count,
			'byDays' => $this->ByDays
		);
	}
	
    public function __toString()
	{
		$aPeriods = array(
			EPeriod::Secondly, 
			EPeriod::Minutely, 
			EPeriod::Hourly, 
			EPeriod::Daily, 
			EPeriod::Weekly, 
			EPeriod::Monthly, 
			EPeriod::Yearly
		);
		
		$sRule = '';

		if (null !== $this->Period)
		{
			$iPeriod = (int)$this->Period;

			$iWeekNumber = null;
			if ($iPeriod == 3 || $iPeriod == 4)
			{
				if (null !== $this->WeekNum)
				{
					$iWeekNumber = ((int)$this->WeekNum < 0 || (int)$this->WeekNum > 4) ? 0 : (int)$this->WeekNum;
				}
			}
	
			$sUntil = '';
			if (null !== $this->Until)
			{
				$oDTUntil = CCalendarHelper::PrepareDateTime($this->Until, $this->GetTimeZone(), true);
				$sUntil = $oDTUntil->format('Ymd');
			}

			$iInterval = (null !== $this->Interval) ? (int)$this->Interval : 0;
			
			$iEnd = 0;
			if (null !== $this->End)
			{
				$iEnd = ((int)$this->End < 0 || (int)$this->End > 3) ? 0 : (int)$this->End;
			}
			
			$sFreq = strtoupper($aPeriods[$iPeriod + 2]);
			$sRule = 'FREQ=' . $sFreq . ';INTERVAL=' . $iInterval;
			if ($iEnd === 1)
			{
				$iCount = (null !== $this->Count) ? (int)$this->Count : 0;
				$sRule .= ';COUNT=' . $iCount;
			}
			else if ($iEnd === 2)
			{
				$sRule .= ';UNTIL=' . $sUntil;
			}

			$sByDay = null;
			if ($sFreq === 'WEEKLY' || $sFreq === 'MONTHLY' || $sFreq === 'YEARLY')
			{
				$sByDay = implode(',', $this->ByDays);
			}
			if (!empty($sByDay))
			{
				if (($sFreq === 'MONTHLY' || $sFreq === 'YEARLY') && isset($iWeekNumber))
				{
					if ($iWeekNumber === 0) $sByDay = '1'.$sByDay;
					if ($iWeekNumber === 1) $sByDay = '2'.$sByDay;
					if ($iWeekNumber === 2) $sByDay = '3'.$sByDay;
					if ($iWeekNumber === 3) $sByDay = '4'.$sByDay;
					if ($iWeekNumber === 4) $sByDay = '-1'.$sByDay;
				}
				$sRule .= ';BYDAY=' . $sByDay;
			}
		}
        return $sRule;		
	}
}
