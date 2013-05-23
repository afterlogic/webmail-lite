<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class_exists('CApi') or die();

CApi::Inc('common.plugins.change-password');

class CCustomChangePasswordPlugin extends AApiChangePasswordPlugin
{
	/**
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct(CApiPluginManager $oPluginManager)
	{
		parent::__construct('1.0', $oPluginManager);
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function validateIfAccountCanChangePassword($oAccount)
	{
		$bResult = false;
		if ($oAccount instanceof CAccount)
		{
			$bResult = true;
		}
		
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function ChangePasswordProcess($oAccount)
	{
		$bResult = false;
		if (0 < strlen($oAccount->PreviousMailPassword) &&
			$oAccount->PreviousMailPassword !== $oAccount->IncomingMailPassword)
		{
			// TODO
		}
		
		return $bResult;
	}
}

return new CCustomChangePasswordPlugin($this);
