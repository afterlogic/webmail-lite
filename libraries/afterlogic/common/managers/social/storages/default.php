<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Social
 */
class CApiSocialStorage extends AApiManagerStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct($sStorageName, CApiGlobalManager &$oManager)
	{
		parent::__construct('social', $sStorageName, $oManager);
	}
	
	/**
	 * @param int $iIdAccount
	 * @return array
	 */
	public function GetSocials($iIdAccount)
	{
	
	}	
	
	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return string
	 */
	public function GetSocial($iIdAccount, $iType)
	{
	
	}	

	/**
	 * @param CSocial &$oSocial
	 * @return bool
	 */
	public function CreateSocial(CSocial &$oSocial)
	{
		
	}

	/**
	 * @param CSocial &$oSocial
	 * @return bool
	 */
	public function UpdateSocial(CSocial &$oSocial)
	{

	}
	
	/**
	 * @param int $iIdAccount
	 * @param int $iType
	 * @return bool
	 */
	public function DeleteSocial($iIdAccount, $iType)
	{
		
	}
	
	public function DeleteSocialByAccountId($iIdAccount)
	{

	}	
	
	/**
	 * @param int $iType
	 * @param string $sIdSocial
	 * @return string
	 */
	public function SocialExists($iType, $sIdSocial)
	{
		
	}	
}

