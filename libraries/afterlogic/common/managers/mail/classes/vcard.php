<?php

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
