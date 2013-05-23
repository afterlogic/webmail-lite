<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	$_rtl = (isset($_GET['rtl']) && $_GET['rtl'] == '1') ? ' dir="rtl"' : '';

	@header('Content-type: text/html; charset=utf-8');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo $_rtl; ?>>
<head>
	<style> .misspel { background: url(skins/redline.gif) repeat-x bottom; display: inline; } </style>
	<script>
		function onLoad()
		{
			try {
				parent.EditAreaLoadHandler();
			}
			catch (er) {}
		}
	</script>
</head>
<body onload="onLoad();">
</body>
</html>