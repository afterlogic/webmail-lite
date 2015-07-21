<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

if (!defined('PSEVEN_APP_ROOT_PATH'))
{
	$sV = PHP_VERSION;
	if (-1 === version_compare($sV, '5.3.0') || !function_exists('spl_autoload_register'))
	{
		echo
			'PHP '.$sV.' detected, 5.3.0 or above required.
			<br />
			<br />
			You need to upgrade PHP engine installed on your server.
			If it\'s a dedicated or your local server, you can download the latest version of PHP from its
			<a href="http://php.net/downloads.php" target="_blank">official site</a> and install it yourself.
			In case of a shared hosting, you need to ask your hosting provider to perform the upgrade.';
		
		exit(0);
	}

	/*if (!isset($_COOKIE["cookiecheck"]))
	{
		if (isset($_REQUEST["cookiecheck"]) && isset($_COOKIE["cookiecheck"]))
		{
			header("Location: $_SERVER[PHP_SELF]");
		}
		else if (isset($_REQUEST["cookiecheck"]))
		{
			echo 'Cookies are disabled in the browser. Please enable them for this web site and reload the page.';
			exit(0);
		}
		else
		{
			setcookie("cookiecheck", "1");
			header("Location: $_SERVER[PHP_SELF]?cookiecheck=1");
		}
	}
	else if (isset($_REQUEST["cookiecheck"]))
	{
		header("Location: $_SERVER[PHP_SELF]");
	}*/

	define('PSEVEN_APP_ROOT_PATH', rtrim(realpath(__DIR__), '\\/').'/');
	define('PSEVEN_APP_LIBRARY_PATH', PSEVEN_APP_ROOT_PATH.'libraries/ProjectCore/');
	define('PSEVEN_APP_SYMFONY_LIBRARY_PATH', PSEVEN_APP_ROOT_PATH.'libraries/Symfony/');
	define('PSEVEN_APP_THUMB_LIBRARY_PATH', PSEVEN_APP_ROOT_PATH.'libraries/PHPThumb/');
	define('PSEVEN_APP_KNP_LIBRARY_PATH', PSEVEN_APP_ROOT_PATH.'libraries/Knp/');
	define('PSEVEN_APP_START', microtime(true));

	/**
	 * @param string $sClassName
	 *
	 * @return mixed
	 */
	function ProjectCoreSplAutoLoad($sClassName)
	{
		if (0 === strpos($sClassName, 'ProjectCore') && false !== strpos($sClassName, '\\'))
		{
			$sFileName = PSEVEN_APP_LIBRARY_PATH.str_replace('\\', '/', substr($sClassName, 12)).'.php';
			if (file_exists($sFileName))
			{
				return include $sFileName;
			}
		}
		else if (0 === strpos($sClassName, 'Symfony') && false !== strpos($sClassName, '\\'))
		{
			$sFileName = PSEVEN_APP_SYMFONY_LIBRARY_PATH.str_replace('\\', '/', substr($sClassName, 8)).'.php';
			if (file_exists($sFileName))
			{
				return include $sFileName;
			}
		}
		else if (0 === strpos($sClassName, 'Knp') && false !== strpos($sClassName, '\\'))
		{
			$sFileName = PSEVEN_APP_KNP_LIBRARY_PATH.str_replace('\\', '/', substr($sClassName, 4)).'.php';
			if (file_exists($sFileName))
			{
				return include $sFileName;
			}
		}
		else if (0 === strpos($sClassName, 'PHPThumb') && false !== strpos($sClassName, '\\'))
		{
			$sFileName = PSEVEN_APP_THUMB_LIBRARY_PATH.str_replace('\\', '/', substr($sClassName, 9)).'.php';
			if (file_exists($sFileName))
			{
				return include $sFileName;
			}
		}

		return false;
	}

	spl_autoload_register('ProjectCoreSplAutoLoad');

	if (class_exists('ProjectCore\Service'))
	{
		include PSEVEN_APP_ROOT_PATH.'libraries/afterlogic/api.php';
		include PSEVEN_APP_ROOT_PATH.'libraries/ProjectCore/Boot.php';
	}
	else
	{
		spl_autoload_unregister('ProjectCoreSplAutoLoad');
	}
}
