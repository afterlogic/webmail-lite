<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Db
 * @subpackage Enum
 */
class ESyncVerboseType extends AEnumeration
{
	const CreateTable = 0;
	const CreateField = 1;
	const DeleteField = 2;
	const CreateIndex = 3;
	const DeleteIndex = 4;
}
