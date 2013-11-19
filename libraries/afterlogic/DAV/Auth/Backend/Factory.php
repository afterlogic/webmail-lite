<?php

namespace afterlogic\DAV\Auth\Backend;

use afterlogic\DAV\Server;

class Factory
{
	public static function getBackend(\PDO $pdo, $dBPrefix = '')
	{
		$oBackend = null;
		if (\afterlogic\DAV\Constants::DAV_DIGEST_AUTH)	
		{
			$oBackend = new Digest($pdo, $dBPrefix);
		}
		else
		{
			$oBackend = new Basic($pdo, $dBPrefix);
		}
		return $oBackend;
	}
}