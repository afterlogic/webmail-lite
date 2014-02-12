<?php

namespace afterlogic\DAV\CalDAV\Backend;

class Factory
{
	public static function getBackend(\PDO $oPdo, $sDbPrefix = '')
	{
		$oApiCapaManager = \CApi::Manager('capability');
		return $oApiCapaManager->IsCalendarSharingSupported() ? new SharePDO($oPdo, $sDbPrefix) : new PDO($oPdo, $sDbPrefix);
	}
}