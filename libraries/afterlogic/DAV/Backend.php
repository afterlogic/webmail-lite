<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV;

class Backend
{
	public static function __callStatic($sMethod, $aArgs)
	{
		return Backends::GetBackend(strtolower($sMethod));
	}	
}