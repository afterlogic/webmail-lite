<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace ProjectSeven\Storage\Enumerations;

/**
 * @category ProjectSeven
 * @package Storage
 * @subpackage Enumerations
 */
class UploadClientError
{
	const NORMAL = 0;
	const FILE_IS_TO_BIG = 1;
	const FILE_PARTIALLY_UPLOADED = 2;
	const FILE_NO_UPLOADED = 3;
	const MISSING_TEMP_FOLDER = 4;
	const FILE_ON_SAVING_ERROR = 5;
	const UNKNOWN = 9;
}

