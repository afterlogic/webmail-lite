<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiMailMessageCollection is used for work with mail messages.
 * 
 * @package Mail
 * @subpackage Classes
 */

class CApiMailMessageCollection extends \MailSo\Base\Collection
{
	/**
	 * Number of messages in the folder.
	 * 
	 * @var int
	 */
	public $MessageCount;

	/**
	 * Number of unread mails in the folder.
	 * 
	 * @var int
	 */
	public $MessageUnseenCount;

	/**
	 * Number of messages returned upon running search.
	 * 
	 * @var int
	 */
	public $MessageResultCount;

	/**
	 * Full name of the folder.
	 * 
	 * @var string
	 */
	public $FolderName;

	/**
	 * Along with **Limit**, denotes a range of message list to retrieve.
	 * 
	 * @var int
	 */
	public $Offset;

	/**
	 * Along with **Offset**, denotes a range of message list to retrieve.
	 * 
	 * @var int
	 */
	public $Limit;

	/**
	 * Denotes search string.
	 * 
	 * @var string
	 */
	public $Search;

	/**
	 * Denotes message lookup type. Typical use case is search in Starred folder.
	 * 
	 * @var string
	 */
	public $Filters;

	/**
	 * List of message UIDs.
	 * 
	 * @var array
	 */
	public $Uids;

	/**
	 * UIDNEXT value for the current folder.
	 * 
	 * @var string
	 */
	public $UidNext;

	/**
	 * Value which changes if any folder parameter, such as message count, was changed.
	 * 
	 * @var string
	 */
	public $FolderHash;

	/**
	 * List of information about new messages. $UidNext is used for obtaining this information.
	 * 
	 * @var array
	 */
	public $New;

	/**
	 * Initializes collection properties.
	 * 
	 * @return void
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->clear();
	}

	/**
	 * Removes all messages from the collection.
	 * 
	 * @return CApiMailMessageCollection
	 */
	public function clear()
	{
		parent::clear();

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
	 * Creates new instance of the object.
	 * 
	 * @return CApiMailMessageCollection
	 */
	public static function createInstance()
	{
		return new self();
	}
}