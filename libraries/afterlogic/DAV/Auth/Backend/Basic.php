<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

namespace afterlogic\DAV\Auth\Backend;

class Basic extends \Sabre\DAV\Auth\Backend\AbstractBasic
{
    /**
     * Reference to PDO connection
     *
     * @var PDO
     */
    protected $pdo;

    /**
     *
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

    /**
     * Validates a username and password
     *
     * This method should return true or false depending on if login
     * succeeded.
     *
     * @return bool
     */
    protected function validateUserPass($username, $password)
	{
		if (class_exists('CApi') && \CApi::IsValid())
		{
			$oApiUsersManager = \CApi::Manager('users');
			$oAccount = $oApiUsersManager->GetAccountOnLogin($username);

			/* @var $oApiCalendarManager \CApiCalendarManager */
			$oApiCalendarManager = \CApi::Manager('calendar');

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
			
			if ($username ===  $oApiCalendarManager->GetPublicUser() || ($bIsDemo) ||
				($oAccount && $oAccount->IncomingMailPassword === $password && 
				 ($bIsActiveSync || $bIsOutlookSync || 
				  ($oAccount->User->GetCapa('DAV_SYNC') && !$bIsActiveSyncClient && !$bIsOutlookSyncClient))))
			{
				$oHelper = new Helper($this->pdo, $this->dbPrefix);
				$oHelper->CheckPrincipals($username);
				return true;
			}
		}

		return false;
	}
}
