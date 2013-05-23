<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	$objectName = isset($_POST['HistoryStorageObjectName']) ? $_POST['HistoryStorageObjectName'] : '';
	$historyKey = isset($_POST['HistoryKey']) ? $_POST['HistoryKey'] : '';
	
	if (!preg_match('/^[a-zA-Z]*$/', $objectName))
	{
		$objectName = '';
	}
	
	if (preg_match('/[\(\)&<>]/', $historyKey))
	{
		$historyKey = '';
	}

	@header('Content-type: text/html; charset=utf-8');
	
	if (strlen($objectName) > 0)
	{
		echo '<html><body><script type="text/javascript">parent.'.$objectName.'.ProcessHistory(\''.$historyKey.'\');</script></body></html>';
	}