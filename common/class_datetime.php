<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/class_convertutils.php');

	define('DATEFORMAT_DEFAULT', 0);
	define('DATEFORMAT_DDMMYY', 1);
	define('DATEFORMAT_MMDDYY', 2);
	define('DATEFORMAT_DDMonth', 3);
	define('DATEFORMAT_Advanced', 4);

	define('DATEFORMAT_FLAG', '|#');

	define('DATEFORMAT_DEF0', 'mm/dd/yy');
	define('DATEFORMAT_DEF1', 'd/m/y');

	define('DATEFORMAT_TIME_24', 'H:i');
	define('DATEFORMAT_TIME_12', 'g:i A');

	class CDateTime
	{
		/**
		 * @var int
		 */
		var $TimeStamp;

		/**
		 * @var string
		 */
		var $FormatString = 'Default';

		/**
		 * @var int
		 */
		var $TimeFormat = 1; // 0/1 - 24/12

		/**
		 * @param int $timestamp optional
		 * @return CDateTime
		 */
		function CDateTime($timestamp = null)
		{
			if ($timestamp != null)
			{
				$this->TimeStamp = $timestamp;
			}
		}

		/**
		 * @return string
		 */
		function GetAsStr()
		{
			return gmdate('D, j M Y H:i:s O (T)', $this->TimeStamp);
		}

		/**
		 * @return string
		 */
		function GetAsStrNew()
		{
			return gmdate('D, j M Y H:i:s O', $this->TimeStamp);
		}

		/**
		 * $date should have YYYY-MM-DD HH:II:SS format
		 * @param string $datetime
		 */
		function SetFromANSI($datetime)
		{
			$dt = explode(' ', $datetime);
			$date = explode('-', $dt[0]);
			$time = explode(':', $dt[1]);
			$this->TimeStamp = gmmktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
		}

		/**
		 * return current timestamp in ANSI format
		 * @return string
		 */
		function ToANSI($newStamp = null)
		{
			if ($newStamp != null) return gmdate('Y-m-d H:i:s', $newStamp);
			return gmdate('Y-m-d H:i:s', $this->TimeStamp);
		}

		/**
		 * @param short $timeOffsetInMinutes
		 * @return string
		 */
		function GetFormattedDate($timeOffsetInMinutes)
		{
			$localTimeStamp = $this->TimeStamp + $timeOffsetInMinutes * 60;

			$timeTemp = ($this->TimeFormat === 1) ? DATEFORMAT_TIME_12 : DATEFORMAT_TIME_24;

			switch ($this->GetDateFormatTypeByString())
			{
				default:
				case DATEFORMAT_DEFAULT:
					return gmdate(DATEFORMAT_DEF1.' '.$timeTemp, $localTimeStamp);

				case DATEFORMAT_DDMMYY:
					return gmdate('d/m/y '.$timeTemp, $localTimeStamp);

				case DATEFORMAT_MMDDYY:
					return gmdate('m/d/y '.$timeTemp, $localTimeStamp);

				case DATEFORMAT_DDMonth:
					return gmdate('d M '.$timeTemp, $localTimeStamp);

				case DATEFORMAT_Advanced:
					$outStr = $this->FormatString;
					$outStr = preg_replace('/month/i', gmdate('M', $localTimeStamp), $outStr);
					$outStr = preg_replace('/yyyy/i', gmdate('Y', $localTimeStamp), $outStr);
					$outStr = preg_replace('/yy/i', gmdate('y', $localTimeStamp), $outStr);
					$outStr = str_replace('y', gmdate('z', $localTimeStamp)+1, $outStr);
					$outStr = preg_replace('/dd/i', gmdate('d', $localTimeStamp), $outStr);
					$outStr = preg_replace('/mm/i', gmdate('m', $localTimeStamp), $outStr);
					$outStr = str_replace('q', floor((gmdate('n', $localTimeStamp)-1)/4)+1, $outStr);
					$outStr = str_replace('ww', gmdate('W', $localTimeStamp), $outStr);
					$outStr = str_replace('w', gmdate('w', $localTimeStamp)+1, $outStr);
					$outStr .= gmdate(' '.$timeTemp, $localTimeStamp);

					return $outStr;
			}
		}

		function GetFormattedFullDate($timeOffsetInMinutes)
		{
			$localTimeStamp = $this->TimeStamp + $timeOffsetInMinutes * 60;
			return CDateTime::GetShortDay(gmdate('w', $localTimeStamp)).', '.
				CDateTime::GetShortMonthJanuary($localTimeStamp).', '.gmdate('Y, '.
				(($this->TimeFormat === 1) ? DATEFORMAT_TIME_12 : DATEFORMAT_TIME_24), $localTimeStamp);
		}

		function GetFormattedShortDate($timeOffsetInMinutes)
		{
			$localTimeStamp = $this->TimeStamp + $timeOffsetInMinutes * 60;
			$todayTime = time() + $timeOffsetInMinutes * 60;

//			if ($localTimeStamp > $todayTime || (gmdate('j', $localTimeStamp) + 0 == gmdate('j', $todayTime) + 0 && gmdate('j n Y', $localTimeStamp) == gmdate('j n Y', $todayTime)))
			if (gmdate('j', $localTimeStamp) + 0 == gmdate('j', $todayTime) + 0 && gmdate('j n Y', $localTimeStamp) == gmdate('j n Y', $todayTime))
			{
				return gmdate(($this->TimeFormat === ETimeFormat::F12) ? DATEFORMAT_TIME_12 : DATEFORMAT_TIME_24, $localTimeStamp);
			}
			else if (gmdate('j', $localTimeStamp) + 1 == gmdate('j', $todayTime) + 0 && gmdate('n Y', $localTimeStamp) == gmdate('n Y', $todayTime))
			{
				return DateYesterday;
			}
			else if ($localTimeStamp > $todayTime - 28512000)
			{
				return CDateTime::GetShortMonthJanuary($localTimeStamp);
			}

			return CDateTime::GetShortMonthJanuary($localTimeStamp).', '.gmdate('Y', $localTimeStamp);
		}

		function GetFormattedTime($timeOffsetInMinutes)
		{
			$localTimeStamp = $this->TimeStamp + $timeOffsetInMinutes * 60;
			return gmdate(($this->TimeFormat === 1) ? DATEFORMAT_TIME_12 : DATEFORMAT_TIME_24, $localTimeStamp);
		}

		public static function GetShortMonthJanuary($timestamp)
		{
			return CDateTime::GetShortMonth(gmdate('n', $timestamp)).' '.gmdate('j', $timestamp);
		}

		public static function GetShortMonth($shotMonth)
		{
			$shotMonth = (int) $shotMonth;
			switch ($shotMonth)
			{
				case 1: return ShortMonthJanuary;
				case 2: return ShortMonthFebruary;
				case 3: return ShortMonthMarch;
				case 4: return ShortMonthApril;
				case 5: return ShortMonthMay;
				case 6: return ShortMonthJune;
				case 7: return ShortMonthJuly;
				case 8: return ShortMonthAugust;
				case 9: return ShortMonthSeptember;
				case 10: return ShortMonthOctober;
				case 11: return ShortMonthNovember;
				case 12: return ShortMonthDecember;
			}

			return '';
		}

		public static function GetShortDay($shotDay)
		{
			switch ($shotDay)
			{
				case 1: return DayToolMonday;
				case 2: return DayToolTuesday;
				case 3: return DayToolWednesday;
				case 4: return DayToolThursday;
				case 5: return DayToolFriday;
				case 6: return DayToolSaturday;
				case 0: return DayToolSunday;
			}

			return '';
		}

		/**
		 * @return short
		 */
		function GetDateFormatTypeByString()
		{
			switch (strtolower($this->FormatString))
			{
				case 'default':
					return DATEFORMAT_DEFAULT;
				case 'dd/mm/yy':
					return DATEFORMAT_DDMMYY;
				case 'mm/dd/yy':
					return DATEFORMAT_MMDDYY;
				case 'dd month':
					return DATEFORMAT_DDMonth;
				default:
					return DATEFORMAT_Advanced;
			}
		}

		/**
		 * @param string $bdDateFormat
		 * @return int
		 */
		public static function GetTimeFormatFromBd($bdDateFormat)
		{
			if (!$bdDateFormat) return 0;
			$l = strlen($bdDateFormat);

			return (int) ($l > 2 && substr($bdDateFormat, -2) == DATEFORMAT_FLAG);
		}

		/**
		 * @return string
		 */
		public static function GetMySqlDateFormat($fieldName)
		{
			return 'DATE_FORMAT('.$fieldName.', "%Y-%m-%d %T")';
		}
	}