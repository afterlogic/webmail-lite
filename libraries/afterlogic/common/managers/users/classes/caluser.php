<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdCalUser Identifier of calendar user.
 * @property int $IdUser Identifier of user wich contains the calendar user.
 * @property bool $ShowWeekEnds If **true** Saturday and Sunday will be highlighted.
 * @property bool $ShowWorkDay If **true** working time will be highlighted.
 * @property int $WorkDayStarts The start of the working day.
 * @property int $WorkDayEnds The end of the working day: 6 - Saturday, 0 - Sunday, 1 - Monday.
 * @property int $WeekStartsOn Day of the week to start the week.
 * @property int $DefaultTab Work with calendar begins with this panel: 1 - Day view, 2 - Week view, 3 - Month view.
 *
 * @package Users
 * @subpackage Classes
 */
class CCalUser extends api_AContainer
{
	/**
	 * Creates a new instance of the object.
	 * 
	 * @param int $iUserId User identifier.
	 * 
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

			$iDomainId = $oApiUsersManager->getDefaultAccountDomainId($iUserId);
			if (0 < $iDomainId)
			{
				/* @var $oApiDomainsManager CApiDomainsManager */
				$oApiDomainsManager = CApi::Manager('domains');
				$oDomain = $oApiDomainsManager->getDomainById($iDomainId);
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
	 * Checks if the calendar user has only valid data.
	 * 
	 * @return bool
	 */
	public function isValid()
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
	 * Obtains static map of calendar user fields. Function with the same name is used for other objects in a unified container **api_AContainer**.
	 * 
	 * @return array
	 */
	public function getMap()
	{
		return self::getStaticMap();
	}

	/**
	 * Obtains static map of calendar user fields.
	 * 
	 * @return array
	 */
	public static function getStaticMap()
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
