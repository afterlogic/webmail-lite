<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

	// remove the following line for real use
	exit('remove this line');

	// utilizing API
	include_once __DIR__.'/../libraries/afterlogic/api.php';
	
	if (class_exists('CApi') && CApi::IsValid())
	{
		// Getting required API class
		$oApiDbManager = CApi::Manager('db');

		$oSettings =& CApi::GetSettings();
		$oSettings->SetConf('Common/DBPrefix', '');

		$sSql = $oApiDbManager->GetSqlSchemaAsString(true);
//		file_put_contents('../SQL-'.gmdate('Y-m-d_H-i-s').'.txt', str_replace("\r", '', $sSql));
		echo $sSql;
	}
	else
	{
		echo 'AfterLogic API isn\'t available';
	}
