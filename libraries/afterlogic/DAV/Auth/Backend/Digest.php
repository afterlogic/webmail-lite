<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

namespace afterlogic\DAV\Auth\Backend;

class Digest extends \Sabre\DAV\Auth\Backend\AbstractDigest
{
    /**
     * Reference to PDO connection
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $dbPrefix;
	
    /**
     * Creates the backend object.
     *
     * If the filename argument is passed in, it will parse out the specified file fist.
     *
     * @param string $filename
     * @param string $tableName The PDO table name to use
     * @return void
     */
    public function __construct(\PDO $pdo, $dBPrefix = '') {

        $this->pdo = $pdo;
		$this->dbPrefix = $dBPrefix;
    }

	public function getDigestHash($realm, $username)
	{
		if (class_exists('CApi') && \CApi::IsValid())
		{
			$oApiUsersManager = \CApi::Manager('users');
			$oApiCalendarManager = \CApi::Manager('calendar');

			if ($oApiUsersManager && $oApiCalendarManager)
			{
				$oAccount = /* @var $oAccount CAccount */ $oApiUsersManager->GetAccountOnLogin($username);

				$bIsActiveSyncClient = Helper::ValidateClient('activesync');
				$bIsOutlookSyncClient = Helper::ValidateClient('outlooksync');

				$bIsActiveSync = false;
				$bIsOutlookSync = false;
				$bIsDemo = false;

				if ($oAccount)
				{
					$bIsActiveSync = $bIsActiveSyncClient && $oAccount->User->GetCapa('ACTIVE_SYNC');
					$bIsOutlookSync = $bIsOutlookSyncClient && $oAccount->User->GetCapa('OUTLOOK_SYNC');
					\CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
				}
				
				if (($oAccount && ($bIsActiveSync || $bIsOutlookSync || 
						($oAccount->User->GetCapa('DAV_SYNC') && !$bIsActiveSyncClient && !$bIsOutlookSyncClient))) 
						|| $username === $oApiCalendarManager->GetPublicUser() || $bIsDemo)
				{
					$oHelper = new Helper($this->pdo, $this->dbPrefix);
					$oHelper->CheckPrincipals($username);

					return md5($username . ':' . $realm . ':' . $oAccount->IncomingMailPassword);
				}
			}
		}

		return null;
	}
}
