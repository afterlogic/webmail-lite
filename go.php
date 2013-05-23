<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 */

	if (!empty($_GET['cal']) && preg_match('/^[a-zA-Z0-9_\-.]+$/', $_GET['cal']))
	{
		header('Location: calendar-pub.php?cal='.$_GET['cal']);
	}
	