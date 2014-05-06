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
     * Creates the backend object.
     *
     * @return void
     */
    public function __construct()
	{
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
				if ($oAccount && $oAccount->IsDisabled)
				{
					return null;
				}

				$bIsOutlookSyncClient = \afterlogic\DAV\Utils::ValidateClient('outlooksync');

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
					\afterlogic\DAV\Utils::CheckPrincipals($sUserName);
					
					return md5($sUserName.':'.$sRealm.':'.$oAccount->IncomingMailPassword);
				}
			}
		}

		return null;
	}
}
