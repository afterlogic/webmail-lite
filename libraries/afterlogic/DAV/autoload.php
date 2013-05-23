<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
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
