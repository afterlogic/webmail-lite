<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

use MailSo\Base\Utils as BaseUtils;

class CApiMailFolder
{
	/**
	 * @var string
	 */
	protected $sParentFullNameRaw;

	/**
	 * @var array
	 */
	protected $aNamesRaw;

	/**
	 * @var string
	 */
	protected $sFullNameSorted;

	/**
	 * @var int
	 */
	protected $iNestingLevel;

	/**
	 * @var int
	 */
	protected $iType;

	/**
	 * @var bool
	 */
	protected $bExisten;

	/**
	 * @var bool
	 */
	protected $bSubscribed;

	/**
	 * @var bool
	 */
	protected $bNamespaceFolder;

	/**
	 * @var bool
	 */
	protected $bGmailFolder;

	/**
	 * @var \MailSo\Imap\Folder
	 */
	protected $oImapFolder;

	/**
	 * @var CApiMailFolderCollection
	 */
	protected $oSubFolders;

	/**
	 * @param \MailSo\Imap\Folder $oImapFolder
	 * @param bool $bSubscribed = true
	 * @param bool $bExisten = true
	 * @return void
	 *
	 * @throws \MailSo\Base\Exceptions\InvalidArgumentException
	 */
	protected function __construct($oImapFolder, $bSubscribed = true, $bExisten = true)
	{
		if ($oImapFolder instanceof \MailSo\Imap\Folder)
		{
			$this->oImapFolder = $oImapFolder;
			$this->oSubFolders = null;
			$this->iType = EFolderType::Custom;
			$this->iDeep = 0;
			
			$this->sFullNameSorted = '';

			$this->bNamespaceFolder = false;
			$this->bGmailFolder = false;

			$this->aNamesRaw = explode($this->oImapFolder->Delimiter(), $this->oImapFolder->FullNameRaw());
			$this->iNestingLevel = count($this->aNamesRaw);

			if (1 === $this->iNestingLevel && 'INBOX' === strtoupper($this->oImapFolder->FullNameRaw()))
			{
				$this->iType = EFolderType::Inbox;
			}

			$this->sParentFullNameRaw = '';
			if (1 < $this->iNestingLevel)
			{
				$aNames = $this->aNamesRaw;
				array_pop($aNames);
				$this->sParentFullNameRaw = implode($this->oImapFolder->Delimiter(), $aNames);
			}

			$this->bSubscribed = $bSubscribed;
			$this->bExisten = $bExisten;
		}
		else
		{
			throw new \MailSo\Base\Exceptions\InvalidArgumentException();
		}
	}

	/**
	 * @param \MailSo\Imap\Folder $oImapFolder
	 * @param bool $bSubscribed = true
	 * @param bool $bExisten = true
	 *
	 * @return CApiMailFolder
	 *
	 * @throws \MailSo\Base\Exceptions\InvalidArgumentException
	 */
	public static function NewInstance($oImapFolder, $bSubscribed = true, $bExisten = true)
	{
		return new self($oImapFolder, $bSubscribed, $bExisten);
	}

	/**
	 * @param string $sFullNameRaw
	 * @param string $sDelimiter
	 *
	 * @return CApiMailFolder
	 *
	 * @throws \MailSo\Base\Exceptions\InvalidArgumentException
	 * @throws \MailSo\Base\Exceptions\InvalidArgumentException
	 */
	public static function NewNonExistenInstance($sFullNameRaw, $sDelimiter)
	{
		return self::NewInstance(
			\MailSo\Imap\Folder::NewInstance($sFullNameRaw, $sDelimiter, array('/Noselect')), true, false);
	}

	/**
	 * @return int
	 */
	public function Type()
	{
		return $this->iType;
	}

	/**
	 * @return int
	 */
	public function NestingLevel()
	{
		return $this->iNestingLevel;
	}

	/**
	 * @param int $iType
	 *
	 * @return CApiMailFolder
	 */
	public function SetType($iType)
	{
		$this->iType = $iType;

		return $this;
	}

	/**
	 * @return string
	 */
	public function Name()
	{
		return BaseUtils::ConvertEncoding($this->NameRaw(),
			\MailSo\Base\Enumerations\Charset::UTF_7_IMAP,
			\MailSo\Base\Enumerations\Charset::UTF_8);
	}

	/**
	 * @return string
	 */
	public function FullName()
	{
		return BaseUtils::ConvertEncoding($this->FullNameRaw(),
			\MailSo\Base\Enumerations\Charset::UTF_7_IMAP,
			\MailSo\Base\Enumerations\Charset::UTF_8);
	}

	/**
	 * @return bool
	 */
	public function HasSortedName()
	{
		return 0 < \strlen($this->sFullNameSorted);
	}

	/**
	 * @return string
	 */
	public function FullNameSorted()
	{
		return 0 === \strlen($this->sFullNameSorted) ?
			$this->FullName() : $this->sFullNameSorted;
	}

	/**
	 * @return string
	 */
	public function SetFullNameSorted($sFullNameSorted)
	{
		$this->sFullNameSorted = $sFullNameSorted;
	}

	/**
	 * @return string
	 */
	public function NameRaw()
	{
		return $this->oImapFolder->NameRaw();
	}

	/**
	 * @return string
	 */
	public function FullNameRaw()
	{
		return $this->oImapFolder->FullNameRaw();
	}

	/**
	 * @return bool
	 */
	public function NamespaceFolder()
	{
		return $this->bNamespaceFolder;
	}

	/**
	 * @return bool
	 */
	public function GmailFolder()
	{
		return $this->bGmailFolder;
	}

	/**
	 * @param bool $bNamespaceFolder
	 */
	public function SetNamespaceFolder($bNamespaceFolder)
	{
		$this->bNamespaceFolder = !!$bNamespaceFolder;
	}
	/**
	 * @param bool $bGmailFolder
	 */
	public function SetGmailFolder($bGmailFolder)
	{
		$this->bGmailFolder = !!$bGmailFolder;
	}

	/**
	 * @return string
	 */
	public function ParentFullName()
	{
		return BaseUtils::ConvertEncoding($this->sParentFullNameRaw,
			\MailSo\Base\Enumerations\Charset::UTF_7_IMAP,
			\MailSo\Base\Enumerations\Charset::UTF_8);
	}

	/**
	 * @return string
	 */
	public function ParentFullNameRaw()
	{
		return $this->sParentFullNameRaw;
	}

	/**
	 * @return string
	 */
	public function Delimiter()
	{
		return $this->oImapFolder->Delimiter();
	}

	/**
	 * @return array
	 */
	public function Flags()
	{
		return $this->oImapFolder->Flags();
	}

	/**
	 * @return array
	 */
	public function FlagsLowerCase()
	{
		return $this->oImapFolder->FlagsLowerCase();
	}

	/**
	 * @return array
	 */
	public function NamesRaw()
	{
		return $this->aNamesRaw;
	}

	/**
	 * @param bool $bCreateIfNull = false
	 *
	 * @return CApiMailFolderCollection
	 */
	public function SubFolders($bCreateIfNull = false)
	{
		if ($bCreateIfNull && !$this->oSubFolders)
		{
			$this->oSubFolders = CApiMailFolderCollection::NewInstance();
		}

		return $this->oSubFolders;
	}

	/**
	 * @return bool
	 */
	public function HasSubFolders()
	{
		return $this->oSubFolders && 0 < $this->oSubFolders->Count();
	}

	/**
	 * @return bool
	 */
	public function HasVisibleSubFolders()
	{
		$sList = array();
		if ($this->oSubFolders)
		{
			$sList = $this->oSubFolders->FilterList(function (CApiMailFolder $oFolder) {
				return $oFolder->IsSubscribed();
			});
		}

		return 0 < count($sList);
	}

	/**
	 * @return mixed
	 */
	public function Status()
	{
		return $this->oImapFolder->GetExtended('STATUS');
	}

	/**
	 * @return bool
	 */
	public function IsSubscribed()
	{
		return $this->bSubscribed;
	}

	/**
	 * @return bool
	 */
	public function IsExists()
	{
		return $this->bExisten;
	}

	/**
	 * @return bool
	 */
	public function IsSelectable()
	{
		return $this->oImapFolder->IsSelectable() && $this->IsExists();
	}

	/**
	 * @return bool
	 */
	public function IsInbox()
	{
		return $this->oImapFolder->IsInbox();
	}

	/**
	 * @return int
	 */
	public function GetFolderXListType()
	{
		$aFlags = $this->oImapFolder->FlagsLowerCase();
		$iXListType = EFolderType::Custom;

		if (is_array($aFlags))
		{
			switch (true)
			{
				case in_array('\inbox', $aFlags):
					$iXListType = EFolderType::Inbox;
					break;
				case in_array('\sent', $aFlags):
					$iXListType = EFolderType::Sent;
					break;
				case in_array('\drafts', $aFlags):
					$iXListType = EFolderType::Drafts;
					break;
				case in_array('\junk', $aFlags):
				case in_array('\spam', $aFlags):
					$iXListType = EFolderType::Spam;
					break;
				case in_array('\bin', $aFlags):
				case in_array('\trash', $aFlags):
					$iXListType = EFolderType::Trash;
					break;
//				case in_array('\important', $aFlags):
//				case in_array('\starred', $aFlags):
//				case in_array('\all', $aFlags):
//				case in_array('\archive', $aFlags):
//				case in_array('\allmail', $aFlags):
//					break;
			}
		}

		return $iXListType;
	}
}
