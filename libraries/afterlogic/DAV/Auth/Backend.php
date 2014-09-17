<?php

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