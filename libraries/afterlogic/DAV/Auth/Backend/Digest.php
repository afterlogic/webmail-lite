<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
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
    public function __construct(\PDO $pdo, $dBPrefix = '')
	{
        $this->pdo = $pdo;
		$this->dbPrefix = $dBPrefix;
    }

    public function setCurrentUser($user)
	{
		$this->currentUser = $user;
	}
	
	public function getDigestHash($sRealm, $sUserName)
	{
		if (class_exists('CApi') && \CApi::IsValid())
		{
			/* @var $oApiUsersManager \CApiUsersManager */
			$oApiUsersManager = \CApi::Manager('users');

			/* @var $oApiCalendarManager \CApiCalendarManager */
			$oApiCalendarManager = \CApi::Manager('calendar');

			/* @var $oApiCapabilityManager \CApiCapabilityManager */
			$oApiCapabilityManager = \CApi::Manager('capability');

			if ($oApiUsersManager && $oApiCalendarManager && $oApiCapabilityManager)
			{
				$oAccount = $oApiUsersManager->GetAccountOnLogin($sUserName);

				$bIsOutlookSyncClient = Helper::ValidateClient('outlooksync');

				$bIsMobileSync = false;
				$bIsOutlookSync = false;
				$bIsDemo = false;

				if ($oAccount)
				{
					$bIsMobileSync = $oApiCapabilityManager->IsMobileSyncSupported($oAccount);
					$bIsOutlookSync = $oApiCapabilityManager->IsOutlookSyncSupported($oAccount);
					\CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
				}

				if (
					($oAccount && (($bIsMobileSync && !$bIsOutlookSyncClient) || ($bIsOutlookSync && $bIsOutlookSyncClient))) ||
					$bIsDemo ||
					$sUserName === $oApiCalendarManager->GetPublicUser()
				)
				{
					\afterlogic\DAV\Auth\Backend\Helper::CheckPrincipals($sUserName);
					
					return md5($sUserName.':'.$sRealm.':'.$oAccount->IncomingMailPassword);
				}
			}
		}

		return null;
	}
}
