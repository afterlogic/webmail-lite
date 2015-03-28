<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\Auth;

class Backend
{
	protected static $instance;
	
	public static function getInstance()
	{
        if(null === self::$instance) 
		{
            self::$instance = (\afterlogic\DAV\Constants::DAV_DIGEST_AUTH) ? new Backend\Digest() : new Backend\Basic();
        }
        return self::$instance;		
	}
}