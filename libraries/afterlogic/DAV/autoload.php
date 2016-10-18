<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

function DAVLibrariesAutoload($className)
{
	if (0 === strpos($className, 'afterlogic') && false !== strpos($className, '\\'))
	{
		include CApi::LibrariesPath().'afterlogic/'.str_replace('\\', '/',substr($className, 11)).'.php';
	}
	else if (0 === strpos($className, 'Sabre') && false !== strpos($className, '\\'))
	{
		include CApi::LibrariesPath().'Sabre/'.str_replace('\\', '/',substr($className, 6)).'.php';
	}
}

spl_autoload_register('DAVLibrariesAutoload');
