<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Sieve
 * @subpackage Enum
 */
class EFilterFiels extends AEnumeration
{
	const From = 0;
	const To = 1;
	const Subject = 2;
	const XSpam = 3;
	const XVirus = 4;
	const CustomHeader = 5;
}

/**
 * @package Sieve
 * @subpackage Enum
 */
class EFilterCondition extends AEnumeration
{
	const ContainSubstring = 0;
	const ContainExactPhrase = 1;
	const NotContainSubstring = 2;
	const StartFrom = 3;
}

/**
 * @package Sieve
 * @subpackage Enum
 */
class EFilterAction extends AEnumeration
{
	const DoNothing = 0;
	const DeleteFromServerImmediately = 1;
	const MarkGrey = 2;
	const MoveToFolder = 3;
	const MoveToSpamFolder = 4;
	const SpamDetect = 5;
	const VirusDetect = 6;
}
