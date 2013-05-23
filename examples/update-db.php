<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	// remove the following line for real use
	exit('remove this line');
	
	if (!isset($_GET['p']))
	{
		exit('auth error');
	}

	// determining main directory
	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));
	
	function SystemTablesSyncCallBack($iType, $bResult, $sTable, $aFields = array(), $sError = '')
	{
		echo '<br />';

		switch ($iType)
		{
			case ESyncVerboseType::CreateTable:
				echo 'Create <b>'.$sTable.'</b> table:';
				break;
			case ESyncVerboseType::CreateField:
				echo 'Add <b>'.implode(', ', $aFields).'</b> column(s) in '.$sTable.' table:';
				break;
			case ESyncVerboseType::CreateIndex:
				echo 'Add index(s) on '.implode(', ', $aFields).' column(s) in '.$sTable.' table:';
				break;
			case ESyncVerboseType::DeleteField:
				echo 'Delete <b>'.implode(', ', $aFields).'</b> column(s) in '.$sTable.' table:';
				break;
			case ESyncVerboseType::DeleteIndex:
				echo 'Delete index(s) on '.implode(', ', $aFields).' in '.$sTable.' table:';
				break;
		}

		if ($bResult)
		{
			echo ' <font color="green"><b>done!</b></font>';
		}
		else
		{
			$sError = empty($sError) ? 'unknown error' : $sError;
			echo '<font color="red"><b>error!</b><br /><br />'.$sError.'</font><br /><br />';
		}
	}

	// utilizing WebMail Pro API
	include_once WM_ROOTPATH.'libraries/afterlogic/api.php';
	if (class_exists('CApi') && CApi::IsValid())
	{
		$oSettings =& CApi::GetSettings();
		if ($oSettings->GetConf('Common/AdminPassword') !== $_GET['p'])
		{
			exit('auth error');
		}		
		
		// Getting required API class
		$oApiDbManager = /* @var $oApiDbManager CApiDbManager */ CApi::Manager('db');
		
		echo $oApiDbManager->SyncTables('SystemTablesSyncCallBack')
			? '<br /><br />DONE!' : '<br /><br />ERROR';
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}
