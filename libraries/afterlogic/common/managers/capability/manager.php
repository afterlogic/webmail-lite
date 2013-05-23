<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Capability
 */
class CApiCapabilityManager extends AApiManager
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('capability', $oManager);
	}

	/**
	 * @return bool
	 */
	public function HasSslSupport()
	{
		return api_Utils::HasSslSupport();
	}

	/**
	 * @return bool
	 */
	public function HasGdSupport()
	{
		return api_Utils::HasGdSupport();
	}

	/**
	 * @return bool
	 */
	public function IsCalendarSupported()
	{
		return api_Utils::IsPhp53();
	}
}
