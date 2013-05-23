<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\Delegates\Backend;

abstract class AbstractBackend {
	
	abstract function UpdateShare($sCalendarId, $FromUser, $ToUser, $iMode);
}
