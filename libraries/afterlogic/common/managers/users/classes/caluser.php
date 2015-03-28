<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdCalUser
 * @property int $IdUser
 * @property bool $ShowWeekEnds
 * @property bool $ShowWorkDay
 * @property int $WorkDayStarts
 * @property int $WorkDayEnds
 * @property int $WeekStartsOn
 * @property int $DefaultTab
 *
 * @package Users
 * @subpackage Classes
 */
class CCalUser extends api_AContainer
{
	/**
	 * @param int $iUserId
	 * @return void
	 */
	public function __construct($iUserId)
	{
		parent::__construct(get_class($this), 'IdCalUser');

		$iUserId = (int) $iUserId;

		$oDomain = null;
		$oSettings =& CApi::GetSettings();
		if (0 < $iUserId)
		{
			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');

			$iDomainId = $oApiUsersManager->GetDefaultAccountDomainId($iUserId);
			if (0 < $iDomainId)
			{
				/* @var $oApiDomainsManager CApiDomainsManager */
				$oApiDomainsManager = CApi::Manager('domains');
				$oDomain = $oApiDomainsManager->GetDomainById($iDomainId);
			}
		}

		$this->SetDefaults(array(
			'IdCalUser' => 0,
			'IdUser' => (int) $iUserId,
			'ShowWeekEnds' => (bool)
				($oDomain ? $oDomain->CalendarShowWeekEnds : $oSettings->GetConf('Calendar/ShowWeekEnds')),
			'ShowWorkDay' => (bool)
				($oDomain ? $oDomain->CalendarShowWorkDay : $oSettings->GetConf('Calendar/ShowWorkDay')),
			'WorkDayStarts' => (int)
				($oDomain ? $oDomain->CalendarWorkdayStarts : $oSettings->GetConf('Calendar/WorkdayStarts')),
			'WorkDayEnds' => (int)
				($oDomain ? $oDomain->CalendarWorkdayEnds : $oSettings->GetConf('Calendar/WorkdayEnds')),
			'WeekStartsOn' => (int)
				($oDomain ? $oDomain->CalendarWeekStartsOn : $oSettings->GetConf('Calendar/WeekStartsOn')),
			'DefaultTab' => (int)
				($oDomain ? $oDomain->CalendarDefaultTab : $oSettings->GetConf('Calendar/DefaultTab'))
		));

		CApi::Plugin()->RunHook('api-caluser-construct', array(&$this));
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		switch (true)
		{
			case 0 >= $this->IdUser:
			case 0 > $this->WorkDayStarts || 23 < $this->WorkDayStarts:
			case 0 > $this->WorkDayEnds || 23 < $this->WorkDayEnds:
			case $this->WorkDayStarts >= $this->WorkDayEnds:
				throw new CApiValidationException(Errs::Validation_InvalidParameters);
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function GetMap()
	{
		return self::GetStaticMap();
	}

	/**
	 * @return array
	 */
	public static function GetStaticMap()
	{
		return array(
			'IdCalUser'		=> array('int', 'settings_id', false, false),
			'IdUser'		=> array('int', 'user_id', true, false),
			'ShowWeekEnds'	=> array('bool', 'showweekends'),
			'ShowWorkDay'	=> array('bool', 'showworkday'),
			'WorkDayStarts'	=> array('int', 'workdaystarts'),
			'WorkDayEnds'	=> array('int', 'workdayends'),
			'WeekStartsOn'	=> array('int', 'weekstartson'),
			'DefaultTab'	=> array('int', 'defaulttab'),
		);
	}
}
