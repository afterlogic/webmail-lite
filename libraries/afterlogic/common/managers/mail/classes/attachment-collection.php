<?php

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
	 * @return int
	 */
	public function InlineCount()
	{
		$aList = $this->FilterList(function ($oAttachment) {
			return $oAttachment && $oAttachment->IsInline();
		});

		return is_array($aList) ? count($aList) : 0;
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