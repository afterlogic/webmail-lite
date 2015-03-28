<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CApiMailVcard
{
	/**
	 * @var string
	 */
	public $Uid;

	/**
	 * @var string
	 */
	public $File;

	private function __construct()
	{
		$this->Uid = '';
		$this->File = '';
		$this->Exists = false;
		$this->Name = '';
		$this->Email = '';
	}

	/**
	 * @return CApiMailVcard
	 */
	public static function NewInstance()
	{
		return new self();
	}
}
