<?php

namespace afterlogic\DAV\Auth\Backend;

class Factory
{
	public static function getBackend()
	{
		return (\afterlogic\DAV\Constants::DAV_DIGEST_AUTH) ? new Digest() : new Basic();
	}
}