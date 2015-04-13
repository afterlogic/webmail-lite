<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CApiMailAttachmentCollection extends \MailSo\Base\Collection
{
	/**
	 * @return void
	 */
	protected function __construct()
	{
		parent::__construct();
	}

	/**
	 * @return CApiMailAttachmentCollection
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @param bool $bCheckContentID = false
	 * @return int
	 */
	public function InlineCount($bCheckContentID = false)
	{
		$aList = $this->FilterList(function ($oAttachment) use ($bCheckContentID) {
			return $oAttachment && $oAttachment->IsInline() &&
				($bCheckContentID ? ($oAttachment->Cid() ? true : false) : true);
		});

		return is_array($aList) ? count($aList) : 0;
	}

	/**
	 * @return bool
	 */
	public function HasNonInlineAttachments()
	{
		return 0 < $this->Count() && $this->Count() > $this->InlineCount(true);
	}

	/**
	 * @return bool
	 */
	public function HasVcardAttachment()
	{
		$aList = $this->FilterList(function ($oAttachment) {
			return $oAttachment && $oAttachment->IsVcard();
		});

		return is_array($aList) && 0 < count($aList);
	}

	/**
	 * @return bool
	 */
	public function HasIcalAttachment()
	{
		$aList = $this->FilterList(function ($oAttachment) {
			return $oAttachment && $oAttachment->IsIcal();
		});

		return is_array($aList) && 0 < count($aList);
	}
}