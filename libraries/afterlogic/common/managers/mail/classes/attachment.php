<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CApiMailAttachment
{
	/**
	 * @var string
	 */
	protected $sFolder;

	/**
	 * @var int
	 */
	protected $iUid;

	/**
	 * @var string
	 */
	protected $sContent;

	/**
	 * @var \MailSo\Imap\BodyStructure
	 */
	protected $oBodyStructure;

	/**
	 * @return void
	 */
	protected function __construct()
	{
		$this->Clear();
	}

	/**
	 * @return CApiMailAttachment
	 */
	public function Clear()
	{
		$this->sFolder = '';
		$this->iUid = 0;
		$this->oBodyStructure = null;
		$this->sContent = '';

		return $this;
	}

	/**
	 * @return string
	 */
	public function Folder()
	{
		return $this->sFolder;
	}

	/**
	 * @return int
	 */
	public function Uid()
	{
		return $this->iUid;
	}

	/**
	 * @return string
	 */
	public function Content()
	{
		return $this->sContent;
	}

	/**
	 * @param string $sContent
	 */
	public function SetContent($sContent)
	{
		$this->sContent = $sContent;
	}

	/**
	 * @return string
	 */
	public function MimeIndex()
	{
		return $this->oBodyStructure ? $this->oBodyStructure->PartID() : '';
	}

	/**
	 * @return string
	 */
	public function FileName($bCalculateOnEmpty = false)
	{
		$sFileName = '';
		if ($this->oBodyStructure)
		{
			$sFileName = $this->oBodyStructure->FileName();
			if ($bCalculateOnEmpty && 0 === strlen(trim($sFileName)))
			{
				$sMimeType = strtolower(trim($this->MimeType()));
				if ('message/rfc822' === $sMimeType)
				{
					$sFileName = 'message'.$this->MimeIndex().'.eml';
				}
				else if ('text/calendar' === $sMimeType)
				{
					$sFileName = 'calendar'.$this->MimeIndex().'.ics';
				}
				else if ('text/vcard' === $sMimeType || 'text/x-vcard' === $sMimeType)
				{
					$sFileName = 'contacts'.$this->MimeIndex().'.vcf';
				}
				else if (!empty($sMimeType))
				{
					$sFileName = str_replace('/', $this->MimeIndex().'.', $sMimeType);
				}
			}
		}

		return $sFileName;
	}

	/**
	 * @return string
	 */
	public function MimeType()
	{
		return $this->oBodyStructure ? $this->oBodyStructure->ContentType() : '';
	}

	/**
	 * @return string
	 */
	public function ContentTransferEncoding()
	{
		return $this->oBodyStructure ? $this->oBodyStructure->MailEncodingName() : '';
	}

	/**
	 * @return int
	 */
	public function EncodedSize()
	{
		return $this->oBodyStructure ? $this->oBodyStructure->Size() : 0;
	}

	/**
	 * @return int
	 */
	public function EstimatedSize()
	{
		return $this->oBodyStructure ? $this->oBodyStructure->EstimatedSize() : 0;
	}

	/**
	 * @return string
	 */
	public function Cid()
	{
		return $this->oBodyStructure ? $this->oBodyStructure->ContentID() : '';
	}

	/**
	 * @return string
	 */
	public function ContentLocation()
	{
		return $this->oBodyStructure ? $this->oBodyStructure->ContentLocation() : '';
	}

	/**
	 * @return bool
	 */
	public function IsInline()
	{
		return $this->oBodyStructure ? $this->oBodyStructure->IsInline() : false;
	}

	/**
	 * @return bool
	 */
	public function IsVcard()
	{
		return in_array($this->MimeType(), array('text/vcard', 'text/x-vcard'));
	}

	/**
	 * @return bool
	 */
	public function IsIcal()
	{
		return in_array($this->MimeType(), array('text/calendar', 'text/x-calendar'));
	}

	/**
	 * @return CApiMailAttachment
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @param string $sFolder
	 * @param int $iUid
	 * @param \MailSo\Imap\BodyStructure $oBodyStructure
	 *
	 * @return CApiMailAttachment
	 */
	public static function NewBodyStructureInstance($sFolder, $iUid, $oBodyStructure)
	{
		return self::NewInstance()->InitByBodyStructure($sFolder, $iUid, $oBodyStructure);
	}

	/**
	 * @param string $sFolder
	 * @param int $iUid
	 * @param \MailSo\Imap\BodyStructure $oBodyStructure
	 *
	 * @return CApiMailAttachment
	 */
	public function InitByBodyStructure($sFolder, $iUid, $oBodyStructure)
	{
		$this->sFolder = $sFolder;
		$this->iUid = $iUid;
		$this->oBodyStructure = $oBodyStructure;
		return $this;
	}
}
