<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 */

$sDefaultTimeZone = function_exists('date_default_timezone_get') ? @date_default_timezone_get() : 'US/Pacific';
$sDefaultTimeZone = ($sDefaultTimeZone && strlen($sDefaultTimeZone) > 0) ? $sDefaultTimeZone : 'US/Pacific';
@date_default_timezone_set($sDefaultTimeZone);

$iExpireTime = 31536000;
$iTime = time();
$sETag = '"7a86e-c3eb-1d612a0f"';

if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $sETag)
{
	ReturnCache();
}
else if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !empty($_SERVER['HTTP_IF_MODIFIED_SINCE']))
{
	ReturnCache();
}

@ini_set('session.cache_limiter', '');

header('Cache-Control: Public', true);
header('Pragma: Public', true);
header('Last-Modified: '.gmdate('D, d M Y H:i:s', $iTime - $iExpireTime).' UTC', true);
header('Expires: '.date('D, j M Y H:i:s', $iTime + $iExpireTime).' UTC', true);
header('Etag: '.$sETag, true);

function ReturnCache()
{
	global $iTime, $iExpireTime, $sETag;

	if (isset($_SERVER['SERVER_PROTOCOL']) && !empty($_SERVER['SERVER_PROTOCOL']))
	{
		header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified', true);
	}
	else
	{
		header('HTTP/1.0 304 Not Modified', true);
	}
	
	header('Cache-Control: Public', true);
	header('Pragma: Public', true);
	
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
	{
		header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'], true);
	}
	header('Expires: '.date('D, j M Y H:i:s', $iTime + $iExpireTime).' UTC', true);
	header('Etag: '.$sETag, true);
	header('Content-Length: 0', true);
	exit();
}
