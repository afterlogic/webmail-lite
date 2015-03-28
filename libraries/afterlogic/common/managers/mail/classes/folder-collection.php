<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CApiMailFolderCollection extends \MailSo\Base\Collection
{
	/**
	 * @var string
	 */
	protected $sNamespace;

	/**
	 * @return void
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->sNamespace = '';
	}

	/**
	 * @return CApiMailFolderCollection
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @param string $sFullNameRaw
	 * @param bool $bRec = false
	 *
	 * @return CApiMailFolder | null
	 */
	public function &GetByFullNameRaw($sFullNameRaw, $bRec = false)
	{
		$mResult = null;
		foreach ($this->aItems as /* @var $oFolder CApiMailFolder */ $oFolder)
		{
			if ($oFolder->FullNameRaw() === $sFullNameRaw)
			{
				$mResult = $oFolder;
				break;
			}
			else if ($bRec && $oFolder->HasSubFolders())
			{
				$mResult = $oFolder->SubFolders()->GetByFullNameRaw($sFullNameRaw, true);
				if ($mResult)
				{
					break;
				}
			}
		}

		return $mResult;
	}

	/**
	 * @return string
	 */
	public function GetNamespace()
	{
		return $this->sNamespace;
	}

	/**
	 * @param string $sNamespace
	 *
	 * @return CApiMailFolderCollection
	 */
	public function SetNamespace($sNamespace)
	{
		$this->sNamespace = $sNamespace;

		$this->MapList(function ($oFolder) use ($sNamespace) {
			if ($oFolder && $oFolder->SubFolders())
			{
				$oFolder->SubFolders()->SetNamespace($sNamespace);
			}
		});

		if (1 < strlen($this->sNamespace))
		{
			$oFolder = null;
			$oFolder =& $this->GetByFullNameRaw(substr($this->sNamespace, 0, -1));
			if ($oFolder)
			{
				$oFolder->SetNamespaceFolder(true);
			}
		}

		return $this;
	}

	/**
	 * @param CApiMailFolder $oFolderA
	 * @param CApiMailFolder $oFolderB
	 *
	 * @return int
	 */
	protected function aASortHelper($oFolderA, $oFolderB)
	{
		return strnatcmp($oFolderA->FullName(), $oFolderB->FullName());
	}

	/**
	 * @param array $aUnsortedMailFolders
	 * @return void
	 */
	public function InitByUnsortedMailFolderArray($aUnsortedMailFolders)
	{
		$this->Clear();

		$aSortedByLenImapFolders = array();
		foreach ($aUnsortedMailFolders as /* @var $oMailFolder CApiMailFolder */ &$oMailFolder)
		{
			$aSortedByLenImapFolders[$oMailFolder->FullNameRaw()] =& $oMailFolder;
			unset($oMailFolder);
		}
		unset($aUnsortedMailFolders);

		$aAddedFolders = array();
		foreach ($aSortedByLenImapFolders as /* @var $oMailFolder CApiMailFolder */ $oMailFolder)
		{
			$sDelimiter = $oMailFolder->Delimiter();
			$aFolderExplode = explode($sDelimiter, $oMailFolder->FullNameRaw());

			if (1 < count($aFolderExplode))
			{
				array_pop($aFolderExplode);

				$sNonExistenFolderFullNameRaw = '';
				foreach ($aFolderExplode as $sFolderExplodeItem)
				{
					$sNonExistenFolderFullNameRaw .= (0 < strlen($sNonExistenFolderFullNameRaw))
						? $sDelimiter.$sFolderExplodeItem : $sFolderExplodeItem;

					if (!isset($aSortedByLenImapFolders[$sNonExistenFolderFullNameRaw]))
					{
						$aAddedFolders[$sNonExistenFolderFullNameRaw] =
							CApiMailFolder::NewNonExistenInstance($sNonExistenFolderFullNameRaw, $sDelimiter);
					}
				}
			}
		}

		$aSortedByLenImapFolders = array_merge($aSortedByLenImapFolders, $aAddedFolders);
		unset($aAddedFolders);

		uasort($aSortedByLenImapFolders, array(&$this, 'aASortHelper'));

		// INBOX and Utf-7 modified sort
		$aFoot = $aTop = array();
		foreach ($aSortedByLenImapFolders as $sKey => /* @var $oMailFolder CApiMailFolder */ &$oMailFolder)
		{
			if (0 === strpos($sKey, '&'))
			{
				$aFoot[] = $oMailFolder;
				unset($aSortedByLenImapFolders[$sKey]);
			}
			else if ('INBOX' === strtoupper($sKey))
			{
				array_unshift($aTop, $oMailFolder);
				unset($aSortedByLenImapFolders[$sKey]);
			}
			else if ('[GMAIL]' === strtoupper($sKey))
			{
				$aTop[] = $oMailFolder;
				unset($aSortedByLenImapFolders[$sKey]);
			}
		}

		$aSortedByLenImapFolders = array_merge($aTop, $aSortedByLenImapFolders, $aFoot);

		foreach ($aSortedByLenImapFolders as /* @var $oMailFolder CApiMailFolder */ &$oMailFolder)
		{
			$this->AddWithPositionSearch($oMailFolder);
			unset($oMailFolder);
		}

		unset($aSortedByLenImapFolders);
	}
	
	/**
	 * @param callable $fCallback
	 * 
	 * @return void
	 */
	public function SortByCallback($fCallback)
	{
		if (is_callable($fCallback))
		{
			$aList =& $this->GetAsArray();

			usort($aList, $fCallback);

			foreach ($aList as &$oItemFolder)
			{
				if ($oItemFolder->HasSubFolders())
				{
					$oItemFolder->SubFolders()->SortByCallback($fCallback);
				}
			}
		}
	}

	/**
	 * @param CApiMailFolder $oMailFolder
	 * @return bool
	 */
	public function AddWithPositionSearch($oMailFolder)
	{
		$oItemFolder = null;
		$bIsAdded = false;
		$aList =& $this->GetAsArray();

		foreach ($aList as /* @var $oItemFolder CApiMailFolder */ $oItemFolder)
		{
			if ($oMailFolder instanceof CApiMailFolder &&
				0 === strpos($oMailFolder->FullNameRaw(), $oItemFolder->FullNameRaw().$oItemFolder->Delimiter()))
			{
				if ($oItemFolder->SubFolders(true)->AddWithPositionSearch($oMailFolder))
				{
					$bIsAdded = true;
				}

				break;
			}
		}

		if (!$bIsAdded && $oMailFolder instanceof CApiMailFolder)
		{
			$bIsAdded = true;
			$this->Add($oMailFolder);
		}

		return $bIsAdded;
	}

	/**
	 * @param mixed $mCallback
	 */
	public function ForeachListWithSubFolders($mCallback)
	{
		if (\is_callable($mCallback))
		{
			$this->ForeachList(function (/* @var $oFolder CApiMailFolder */ $oFolder) use ($mCallback) {
				if ($oFolder)
				{
					\call_user_func($mCallback, $oFolder);

					if ($oFolder->HasSubFolders())
					{
						$oFolder->SubFolders()->ForeachListWithSubFolders($mCallback);
					}
				}
			});
		}
	}

	/**
	 * @param mixed $mCallback
	 */
	public function ForeachListOnRootInboxAndGmailSubFolder($mCallback)
	{
		$oInboxFolder = $this->GetByFullNameRaw('INBOX');
		if ($oInboxFolder && $oInboxFolder->HasSubFolders())
		{
			$oInboxFolder->SubFolders()->ForeachList($mCallback);
		}

		$oGmailFolder = $this->GetByFullNameRaw('[Gmail]');
		if ($oGmailFolder && $oGmailFolder->HasSubFolders())
		{
			$oGmailFolder->SubFolders()->ForeachList($mCallback);
		}

		$this->ForeachList($mCallback);
	}
}
