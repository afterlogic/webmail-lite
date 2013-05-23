<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

class_exists('CApi') or die();

class CSieveFilters extends AApiPlugin
{
	/**
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct(CApiPluginManager $oPluginManager)
	{
		parent::__construct('1.0', $oPluginManager);

		$this->AddHook('api-change-account-by-id', 'PluginChangeAccountById');
		$this->AddHook('get-sieve-filters', 'GetSieveFilters');
		$this->AddHook('update-sieve-filters', 'UpdateSieveFilters');

		$this->sSieveFolderCharset =
			CApi::GetConf('plugins.sieve-filters.options.folder-charset', 'utf7-imap'); // utf7-imap | utf-8
	}

	/**
	 * @param string $sValue
	 * @return string
	 */
	protected function quoteValue($sValue)
	{
		$sValue = str_replace('"', '\\"', trim($sValue));
		return $sValue;
		return str_replace(array("\r", "\n", "\t"), array('\r', '\n', '\t'), trim($sValue));
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function PluginChangeAccountById(&$oAccount)
	{
		if ($oAccount instanceof CAccount)
		{
			$aDomains = CApi::GetConf('plugins.sieve-filters.options.domains', null);
			if (
				($oAccount->IsInternal && CApi::GetConf('mailsuite', false)) ||
				(is_array($aDomains) &&
					(
						(1 === count($aDomains) && '*' === $aDomains[0]) ||
						(in_array(api_Utils::GetDomainFromEmail($oAccount->Email), $aDomains))
					)
				)
			)
			{
				$oAccount->EnableExtension(CAccount::SieveFiltersExtension);
			}
		}
	}

	/**
	 * @param CAcount $oAccount
	 * @return string | bool
	 */
	protected function GetSieveFiltersText(&$oAccount)
	{
		return $this->getSieveManager()->GetFiltersRawData($oAccount);
	}

	/**
	 * @param CAcount $oAccount
	 * @param string $sFilters
	 * @return string
	 */
	protected function SetSieveFiltersText(&$oAccount, $sFilters)
	{
		return $this->getSieveManager()->SetFiltersRawData($oAccount, $sFilters);
	}

	protected function getMailProcessorFolders($oAccount)
	{
		static $oFolders = null;
		if (null === $oFolders)
		{
			$oMailProcessor = new MailProcessor($oAccount);
			$oFolders = $oMailProcessor->GetFolders();
		}
		return $oFolders;
	}

	/**
	 * @param CAcount $oAccount
	 * @param CFilterCollection $oFilters
	 */
	public function GetSieveFilters(&$oAccount, &$oFilters)
	{
		if (!$oAccount->IsEnabledExtension(CAccount::SieveFiltersExtension))
		{
			return false;
		}

		$sScript = $this->GetSieveFiltersText($oAccount);
		if (false === $sScript)
		{
			return false;
		}

		$oFilters = new FilterCollection();

		$aFilters = explode("\n", $sScript);

		$aFoldersCache = array();
		foreach ($aFilters as $sFilter)
		{
			$pattern = '#sieve_filter:';
			if (strpos($sFilter, $pattern) !== false)
			{
				$sFilter = substr($sFilter, strlen($pattern));

				$aFilter = array();
				$aFilter = explode(";", $sFilter);

				$oFilter = new Filter();
				$oFilter->Id = -1;
				$oFilter->IdAcct = $oAccount->IdAccount;
				$oFilter->IsSystem = false;
				$oFilter->Applied = trim($aFilter[0]);
				$oFilter->Condition = trim($aFilter[1]);
				$oFilter->Field = trim($aFilter[2]);
				$oFilter->Filter = trim($aFilter[3]);
				$oFilter->Action = trim($aFilter[4]);

				if (FILTERACTION_MoveToFolder === (int) $oFilter->Action
					&& !empty($aFilter[5]))
				{
					$aFilter[5] = api_Utils::ConvertEncoding($aFilter[5],
						$this->sSieveFolderCharset, 'utf7-imap');

					$oFolders = $this->getMailProcessorFolders($oAccount);
					if ($oFolders)
					{
						if (!isset($aFoldersCache[$aFilter[5]]))
						{
							$aFoldersCache[$aFilter[5]] = $oFolders->GetFolderByFullName($aFilter[5]);
						}

						$oFolder = $aFoldersCache[$aFilter[5]];
						if ($oFolder)
						{
							$oFilter->IdFolder = $oFolder->IdDb;
							$oFilter->FolderFullName = $oFolder->FullName;
						}
						else
						{
							$oFilter = null;
						}
					}
					else
					{
						$oFilter = null;
					}
				}

				if ($oFilter)
				{
					$oFilters->Add($oFilter);
				}

				unset($oFilter);
			}
		}

		$oFilters->List->_list = array_reverse($oFilters->List->_list);
	}

	/**
	 * @param CAccount $oAccount
	 * @param CAccount $oFiltersNode
	 * @param bool $bSuccess
	 */
	public function UpdateSieveFilters(&$oAccount, &$oFiltersNode, &$bSuccess)
	{
		if (!$oAccount->IsEnabledExtension(CAccount::SieveFiltersExtension))
		{
			return false;
		}

		$sFilters = "#sieve filter\n\n";

		for ($mKey = count($oFiltersNode->Children) - 1; $mKey >= 0; $mKey--)
		{
			$oFilterNode =& $oFiltersNode->Children[$mKey];
			if (isset($oFilterNode->Attributes['status']))
			{
				$sStatus = $oFilterNode->Attributes['status'];
				switch ($sStatus)
				{
					case 'new':
					case 'updated':
					case 'unchanged':
						$oFilter = CAppXmlHelper::GetFilter($oFilterNode);
						if ($oFilter)
						{
							// field
							$field = 'From';
							switch($oFilter->Field)
							{
								case FILTERFIELD_From:
									$field = 'From';
									break;
								case FILTERFIELD_To:
									$field = 'To';
									break;
								case FILTERFIELD_Subject:
									$field = 'Subject';
									break;
							}

							// filter
							$filter = $oFilter->Filter;

							// condition
							$condition = '';
							switch ($oFilter->Condition)
							{
								case FILTERCONDITION_ContainSubstring:
									$condition = 'if header :contains "'.$this->quoteValue($field).'" "'.$this->quoteValue($filter).'" {';
									break;
								case FILTERCONDITION_ContainExactPhrase:
									$condition = 'if header :is "'.$this->quoteValue($field).'" "'.$this->quoteValue($filter).'" {';
									break;
								case FILTERCONDITION_NotContainSubstring:
									$condition = 'if not header :contains "'.$this->quoteValue($field).'" "'.$this->quoteValue($filter).'" {';
									break;
							}

							// folder
							$folderFullName = '';
							if ((int) $oFilter->Action === FILTERACTION_MoveToFolder && $oAccount)
							{
								$oFolders = $this->getMailProcessorFolders($oAccount);
								if ($oFolders)
								{
									$folder = $oFolders->GetFolderById($oFilter->IdFolder);
									if ($folder)
									{
										$folderFullName = api_Utils::ConvertEncoding($folder->FullName,
											'utf7-imap', $this->sSieveFolderCharset);
									}
								}
							}

							// action
							$action = "";
							switch($oFilter->Action)
							{
								case FILTERACTION_DeleteFromServerImmediately:
									$action = 'discard ;';
									break;
								case FILTERACTION_MoveToFolder:
									$action = 'fileinto "'.$this->quoteValue($folderFullName).'" ;'."\n";
									$action .= 'stop ;';
									break;
							}

							$end = '}';

							if (!$oFilter->Applied)
							{
								$condition = '#'.$condition;
								$action = '#'.$action;
								$end = '#'.$end;
							}

							$sFilters .= "\n".'#sieve_filter:'.
								implode(';', array(
									$oFilter->Applied, $oFilter->Condition, $oFilter->Field,
									$oFilter->Filter, $oFilter->Action, $folderFullName))."\n";

							$sFilters .= $condition."\n";
							$sFilters .= $action."\n";
							$sFilters .= $end."\n";
						}
						break;
				}
			}
		}
		$sFilters = $sFilters . "\n".'#end sieve filter'."\n";

		$this->SetSieveFiltersText($oAccount, $sFilters);
	}
}

return new CSieveFilters($this);
