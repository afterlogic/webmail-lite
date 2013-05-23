<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
include_once WM_ROOTPATH.'application/include.php';

$sA = CGet::Get('a');
CSession::Set('wm_sess_saver', (preg_match('/^[a-z0-9]+$/i', $sA)) ? $sA : md5(microtime(true).rand(0, 999)));

@header('Content-type: text/html; charset=utf-8');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><meta http-equiv="refresh" content="420; URL=session-saver.php?a=<?php echo md5(time());?>" /></head><body></body></html>
