<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV\Backend;

class OwnCloudPDO extends \Sabre\CardDAV\Backend\PDO {
	
    public function __construct() {

		$oPdo = self::GetPDO();
		$sDbPrefix = '';

		parent::__construct($oPdo, $sDbPrefix.'oc_contacts_addressbooks', $sDbPrefix.'oc_contacts_cards');
    }
	
	/**
	 * @return PDO|false
	 */
	public static function GetPDO()
	{
		static $oPdoCache = null;
		if (null !== $oPdoCache)
		{
			return $oPdoCache;
		}

		$sDbPort = '';
		$sUnixSocket = '';

		$iDbType = \EDbType::MySQL;
		$sDbHost = 'localhost';
		$sDbName = 'owncloud';
		$sDbLogin = 'root';
		$sDbPassword = 'ukladchik1';

		$iPos = strpos($sDbHost, ':');
		if (false !== $iPos && 0 < $iPos)
		{
			$sAfter = substr($sDbHost, $iPos + 1);
			$sDbHost = substr($sDbHost, 0, $iPos);

			if (is_numeric($sAfter))
			{
				$sDbPort = $sAfter;
			}
			else
			{
				$sUnixSocket = $sAfter;
			}
		}

		$oPdo = false;
		if (class_exists('PDO'))
		{
			try
			{
				$oPdo = @new \PDO((\EDbType::PostgreSQL === $iDbType ? 'pgsql' : 'mysql').':dbname='.$sDbName.
					(empty($sDbHost) ? '' : ';host='.$sDbHost).
					(empty($sDbPort) ? '' : ';port='.$sDbPort).
					(empty($sUnixSocket) ? '' : ';unix_socket='.$sUnixSocket), $sDbLogin, $sDbPassword);

				if ($oPdo)
				{
					$oPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				}
			}
			catch (\Exception $oException)
			{
				$oPdo = false;
			}
		}

		if (false !== $oPdo)
		{
			$oPdoCache = $oPdo;
		}

		return $oPdo;
	}
}

