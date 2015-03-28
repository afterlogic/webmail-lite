<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Api
 * @deprecated since 7.0.0
 */
abstract class AApiForwardPlugin extends AApiPlugin
{
	/**
	 * @param string $sVersion
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct($sVersion, CApiPluginManager $oPluginManager)
	{
		parent::__construct($sVersion, $oPluginManager);
	}
}
