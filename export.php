<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
	include_once WM_ROOTPATH.'application/include.php';
	require_once WM_ROOTPATH.'common/inc_constants.php';
	require_once WM_ROOTPATH.'common/class_convertutils.php';
	
	$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');
	$iAccountId = $oApiUsersManager->GetDefaultAccountId(CSession::Get(APP_SESSION_USER_ID));
	$oAccount = /* @var $oAccount CAccount */ $oApiUsersManager->GetAccountById($iAccountId);

	if ($oAccount)
	{
		ConvertUtils::SetLimits();
		AppIncludeLanguage($oAccount->User->DefaultLanguage);
		
		if (isset($_GET['contacts']))
		{
			$oApiContactsManager = /* @var $oApiContactsManager CApiContactsManager */ CApi::Manager('contacts');

			$sOutput = $oApiContactsManager->Export($oAccount->IdUser, 'csv');
			if (false !== $sOutput)
			{
				header('Pragma: public');
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="export.csv";');
				header('Content-Transfer-Encoding: binary');

				echo $sOutput;
			}
		}
		else if (isset($_GET['calendar']))
		{
			$sCalendarId = urlencode($_GET['calendar_id']);
			$oApiCalendarManager = /* @var $oApiCalendarManager CApiCalendarManager */ CApi::Manager('calendar');
			$sOutput = $oApiCalendarManager->ExportCalendarToIcs($oAccount, $sCalendarId);
			if (false !== $sOutput)
			{
				header('Pragma: public');
				header('Content-Type: text/calendar');
				header('Content-Disposition: attachment; filename="'.$sCalendarId.'.ics";');
				header('Content-Transfer-Encoding: binary');

				echo $sOutput;
			}
		}
	}
		
