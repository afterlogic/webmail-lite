<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

require_once dirname(__FILE__).'/libraries/afterlogic/api.php';

/* Mapping PHP errors to exceptions */
function exception_error_handler($errno, $errstr, $errfile, $errline )
{
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

\set_time_limit(3000);
\set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// CApi::$bUseDbLog = false;

$baseUri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'/'.basename(__FILE__))).'/'.basename(__FILE__).'/';
$server = afterlogic\DAV\Server::NewInstance($baseUri);
$server->exec();
