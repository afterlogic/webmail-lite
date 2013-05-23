<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	$sQueryStart = '?';
	foreach ($_GET as $sKey => $sValue)
	{
		 $sQueryStart .=
			preg_replace('/[^a-z\-_]/i', '', $sKey)
			.'='.
			urlencode($sValue).'&';
	}
	
	@header('Content-type: text/html; charset=utf-8');
	
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico" />
		<title></title>
	</head>
	<body style="background-color: Silver">
		<img style="border: solid 1px Black;" src="attach.php<?php echo htmlspecialchars($sQueryStart); ?>">
	</body>
</html>