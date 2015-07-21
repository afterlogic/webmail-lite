<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @internal
 * 
 * @package Users
 * @subpackage Storages
 */
class CApiMailStorage extends AApiManagerStorage
{
	/**
	 * Creates instance of the object.
	 * 
	 * @param string $sStorageName
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct($sStorageName, CApiGlobalManager &$oManager)
	{
		parent::__construct('mail', $sStorageName, $oManager);
	}
}