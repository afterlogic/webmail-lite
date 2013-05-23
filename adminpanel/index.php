<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	include 'core/cadminpanel.php';

	$oAdminPanel = new CAdminPanel(__FILE__);
	$oAdminPanel->Run()->End();