<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 * 
 */

namespace afterlogic\DAV\Delegates\Backend;

abstract class AbstractBackend {
	
	abstract function UpdateShare($sCalendarId, $FromUser, $ToUser, $iMode);
	
	abstract function DeleteShares($sCalendarId, $FromUser);
}
