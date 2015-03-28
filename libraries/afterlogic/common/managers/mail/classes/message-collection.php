<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CApiMailMessageCollection extends \MailSo\Base\Collection
{
	/**
	 * @var int
	 */
	public $MessageCount;

	/**
	 * @var int
	 */
	public $MessageUnseenCount;

	/**
	 * @var int
	 */
	public $MessageResultCount;

	/**
	 * @var string
	 */
	public $FolderName;

	/**
	 * @var int
	 */
	public $Offset;

	/**
	 * @var int
	 */
	public $Limit;

	/**
	 * @var string
	 */
	public $Search;

	/**
	 * @var string
	 */
	public $Filters;

	/**
	 * @var array
	 */
	public $Uids;

	/**
	 * @var string
	 */
	public $UidNext;

	/**
	 * @var string
	 */
	public $FolderHash;

	/**
	 * @var array
	 */
	public $New;

	/**
	 * @return void
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->Clear();
	}

	/**
	 * @return CApiMailMessageCollection
	 */
	public function Clear()
	{
		parent::Clear();

		$this->MessageCount = 0;
		$this->MessageUnseenCount = 0;
		$this->MessageResultCount = 0;

		$this->FolderName = '';
		$this->Offset = 0;
		$this->Limit = 0;
		$this->Search = '';
		$this->Filters = '';

		$this->UidNext = '';
		$this->FolderHash = '';
		$this->Uids = array();

		$this->New = array();

		return $this;
	}

	/**
	 * @return CApiMailMessageCollection
	 */
	public static function NewInstance()
	{
		return new self();
	}
}