<?php

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
	public $MessageSearchCount;

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
		$this->MessageSearchCount = 0;

		$this->FolderName = '';
		$this->Offset = 0;
		$this->Limit = 0;
		$this->Search = '';

		$this->UidNext = '';
		$this->FolderHash = '';
		$this->Uids = array();

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