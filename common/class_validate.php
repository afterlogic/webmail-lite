<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class Validate 
{
	/**
	 * @param String $strEmail
	 * @return bool
	 */
	public static function checkEmail($strEmail)
	{
		$pattern = '/[A-Z0-9\!#\$%\^\{\}`~&\'\+-=_\.]+@[A-Z0-9\.-]/i';  
		$strEmail = $strEmail;
		return (preg_match($pattern, $strEmail));
	}
	
	/**
	 * @param string $strLogin
	 * @return bool
	 */
	public static function checkLogin($strLogin)
	{
		$strLogin = $strLogin;
		return (!Validate::HasSpecSymbols($strLogin));
	}
	
	/**
	 * @param int $port
	 * @return bool
	 */
	public static function checkPort($port)
	{
		$port = intval($port);
		return ($port > 0 && $port < 65535);
	}
	
	/**
	 * @param String $strServerName
	 * @return bool
	 */
	public static function checkServerName($strServerName)
	{
		$pattern = '/[^A-Z0-9\.\-\:\/]/i';
		$strServerName = $strServerName;
		return (!preg_match($pattern, $strServerName));
	}
		
	/**
	 * @param String $strValue
	 * @return bool
	 */
	public static function HasSpecSymbols($_srt)
    {
        return preg_match('/["\/\\\*\?<>\|:]/', $_srt);
    }
	
    /**
     * @param String $strWeb
     * @return String
     */
    public static function cleanWebPage($strWebPage)
    {
    	$pattern = '/^[\/;<=>\[\\#\?]+/';
    	$strWebPage = trim($strWebPage);
    	return preg_replace($pattern, '', $strWebPage);
    }
}
