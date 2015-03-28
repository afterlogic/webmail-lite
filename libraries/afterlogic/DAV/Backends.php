<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV;

class Backends
{
	public static $aBackends = array();

	public static function GetBackend($sName)
	{
		if (!isset(self::$aBackends[$sName]))
		{
			$oBackend = null;
			switch ($sName) {
				case 'auth':
					$oBackend = \afterlogic\DAV\Auth\Backend::getInstance();
					break;
				case 'principal':
					$oBackend = new \afterlogic\DAV\Principal\Backend\PDO();
					break;
				case 'caldav':
					$oBackend = new \afterlogic\DAV\CalDAV\Backend\PDO();
					break;
				case 'carddav':
					$oBackend = new \afterlogic\DAV\CardDAV\Backend\PDO();
					break;
				case 'carddav-owncloud':
					$oBackend = new \afterlogic\DAV\CardDAV\Backend\OwnCloudPDO();
					break;
				case 'lock':
					$oBackend = new \afterlogic\DAV\Locks\Backend\PDO();
					break;
				case 'reminders':
					$oBackend = new \afterlogic\DAV\Reminders\Backend\PDO();
					break;
			}
			if (isset($oBackend))
			{
				self::$aBackends[$sName] = $oBackend;
			}
		}
		return self::$aBackends[$sName];
	}
}