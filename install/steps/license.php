<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class CLicenseStep extends AInstallerStep
{
	public function DoPost()
	{
		return true;
	}

	public function TemplateValues()
	{
		return array();
	}
}