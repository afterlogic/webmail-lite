<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
