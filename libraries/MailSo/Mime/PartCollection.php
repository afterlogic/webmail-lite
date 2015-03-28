<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Mime;

/**
 * @category MailSo
 * @package Mime
 */
class PartCollection extends \MailSo\Base\Collection
{
	/**
	 * @access protected
	 */
	protected function __construct()
	{
		parent::__construct();
	}

	/**
	 * @return \MailSo\Mime\PartCollection
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @param string $sBoundary
	 *
	 * @return resorce
	 */
	public function ToStream($sBoundary)
	{
		$rResult = null;
		if (0 < \strlen($sBoundary))
		{
			$aResult = array();

			$aParts =& $this->GetAsArray();
			foreach ($aParts as /* @var $oPart \MailSo\Mime\Part */ &$oPart)
			{
				if (0 < count($aResult))
				{
					$aResult[] = \MailSo\Mime\Enumerations\Constants::CRLF.
						'--'.$sBoundary.\MailSo\Mime\Enumerations\Constants::CRLF;
				}

				$aResult[] = $oPart->ToStream();
			}
			
			return \MailSo\Base\StreamWrappers\SubStreams::CreateStream($aResult);
		}

		return $rResult;
	}
}
