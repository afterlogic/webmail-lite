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
		$oAttachment = null;

		$aList = $this->FilterList(function ($oAttachment) {
			return $oAttachment && $oAttachment->IsInline();
		});

		return is_array($aList) ? count($aList) : 0;
	}
}