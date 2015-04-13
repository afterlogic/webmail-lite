<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Sieve
 */
class CApiSieveManager extends AApiManager
{
	/**
	 * @var bool
	 */
	static $AutoSave = true;

	/**
	 * @var CApiSieveProtocol
	 */
	protected $oSieve;

	/**
	 * @var string
	 */
	protected $sSieveFileName;

	/**
	 * @var array
	 */
	protected $aSectionsData;

	/**
	 * @var array
	 */
	protected $aSectionsOrders;

	/**
	 * @var array
	 */
	protected $aSieves;

	/**
	 * @var string
	 */
	protected $sGeneralPassword;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('sieve', $oManager);

		CApi::Inc('common.net.protocols.sieve');
		
		$this->inc('classes.enum');
		$this->inc('classes.filter');

		$this->aSieves = array();
		$this->sGeneralPassword = '';
		$this->sSieveFileName = CApi::GetConf('sieve.config.file', 'sieve');
		$this->sSieveFolderCharset = CApi::GetConf('sieve.config.filters-folder-charset', 'utf-8');
		$this->bSectionsParsed = false;
		$this->aSectionsData = array();
		$this->aSectionsOrders = array(
			'forward',
			'autoresponder',
			'filters'
		);
	}

	/**
	 * @param string $sValue
	 * @return string
	 */
	private function quoteValue($sValue)
	{
		return str_replace('"', '\\"', trim($sValue));
	}

	/**
	 * @param CAccount $oAccount
	 * @return array
	 */
	public function GetAutoresponder($oAccount)
	{
		$this->parseSectionsData($oAccount);
		$sData = $this->getSectionData('autoresponder');

		$bEnabled = false;
		$sSubject = '';
		$sText = '';

		$aMatch = array();
		if (!empty($sData) && preg_match('/#data=([\d])~([^\n]+)/', $sData, $aMatch) && isset($aMatch[1]) && isset($aMatch[2]))
		{
			$bEnabled = '1' === (string) $aMatch[1];
			$aParts = explode("\x0", base64_decode($aMatch[2]), 2);
			if (is_array($aParts) && 2 === count($aParts))
			{
				$sSubject = $aParts[0];
				$sText = $aParts[1];
			}
		}

		return array(
			'enabled' => $bEnabled,
			'subject' => $sSubject,
			'body' => $sText
		);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sSubject
	 * @param string $sText
	 * @param bool $bEnabled
	 * @return bool
	 */
	public function SetAutoresponder($oAccount, $sSubject, $sText, $bEnabled = true)
	{
		$sSubject = str_replace(array("\r", "\n", "\t"), ' ', trim($sSubject));
		$sText = str_replace(array("\r"), '', trim($sText));

		$sData = '#data='.($bEnabled ? '1' : '0').'~'.base64_encode($sSubject."\x0".$sText)."\n";
		$sScriptText = 'vacation :days 1 :subject "'.$this->quoteValue($sSubject).'" "'.$this->quoteValue($sText).'";';

		if ($bEnabled)
		{
			$sData .= $sScriptText;
		}
		else
		{
			$sData .= '#'.implode("\n#", explode("\n", $sScriptText));
		}

		$this->parseSectionsData($oAccount);
		$this->setSectionData('autoresponder', $sData);

		if (CApiSieveManager::$AutoSave)
		{
			return $this->resaveSectionsData($oAccount);
		}

		return true;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sText
	 * @param string $sSubject
	 * @param bool $bEnabled
	 * @return bool
	 */
	public function DisableAutoresponder($oAccount)
	{
		$aData = $this->GetAutoresponder($oAccount);

		$sText = '';
		$sSubject = '';

		if ($aData && isset($aData[1], $aData[2]))
		{
			$sText = $aData[2];
			$sSubject = $aData[1];
		}

		return $this->SetAutoresponder($oAccount, $sText, $sSubject, false);
	}

	/**
	 * @param CAccount $oAccount
	 * @return array | false
	 */
	public function GetForward($oAccount)
	{
		$this->parseSectionsData($oAccount);
		$sData = $this->getSectionData('forward');

		$bEnabled = false;
		$sForward = '';

		$aMatch = array();
		if (!empty($sData) && preg_match('/#data=([\d])~([^\n]+)/', $sData, $aMatch) && isset($aMatch[1]) && isset($aMatch[2]))
		{
			$bEnabled = '1' === (string) $aMatch[1];
			$sForward = base64_decode($aMatch[2]);
		}
		
		return array(
			'enabled' => $bEnabled,
			'email' => $sForward
		);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sForward
	 * @param bool $bEnabled = true
	 * @return bool
	 */
	public function SetForward($oAccount, $sForward, $bEnabled = true)
	{
		$sData =
			'#data='.($bEnabled ? '1' : '0').'~'.base64_encode($sForward)."\n".
			($bEnabled ? '' : '#').'redirect :copy "'.$this->quoteValue($sForward).'";'."\n";

		$this->parseSectionsData($oAccount);
		$this->setSectionData('forward', $sData);

		if (CApiSieveManager::$AutoSave)
		{
			return $this->resaveSectionsData($oAccount);
		}

		return true;
	}

	/**
	 * @param CAcount $oAccount
	 *
	 * @return array|false
	 */
	public function GetSieveFilters($oAccount)
	{
		$mResult = false;

		$sScript = $this->GetFiltersRawData($oAccount);
		if (false !== $sScript)
		{
			$mResult = array();
			
			$aFilters = explode("\n", $sScript);

			foreach ($aFilters as $sFilter)
			{
				$sPattern = '#sieve_filter:';
				if (strpos($sFilter, $sPattern) !== false)
				{
					$sFilter = substr($sFilter, strlen($sPattern));

					$aFilter = explode(";", $sFilter);

					if (is_array($aFilter) && 5 < count($aFilter))
					{
						$oFilter = new CFilter($oAccount);
						$oFilter->Enable = (bool) trim($aFilter[0]);
						$oFilter->Field = (int) trim($aFilter[2]);
						$oFilter->Condition = (int) trim($aFilter[1]);
						$oFilter->Action = (int) trim($aFilter[4]);
						$oFilter->Filter = (string) trim($aFilter[3]);

						if (EFilterAction::MoveToFolder === $oFilter->Action && isset($aFilter[5]))
						{
							$oFilter->FolderFullName = api_Utils::ConvertEncoding($aFilter[5],
								$this->sSieveFolderCharset, 'utf7-imap');
						}

						$mResult[] = $oFilter;
					}

					unset($oFilter);
				}
			}
		}
		
		return $mResult;
	}

	/**
	 * @param CAcount $oAccount
	 * @param array $aFilters
	 *
	 * @return bool
	 */
	public function UpdateSieveFilters($oAccount, $aFilters)
	{
		$sFilters = "#sieve filter\n\n";

		if ($oAccount)
		{
			foreach ($aFilters as /* @var $oFilter CFilter */ $oFilter)
			{
				if  ('' === trim($oFilter->Filter))
				{
					continue;
				}

				if  (EFilterAction::MoveToFolder === $oFilter->Action && '' === trim($oFilter->FolderFullName))
				{
					continue;
				}

				$aFields = array();
				switch($oFilter->Field)
				{
					default :
					case EFilterFiels::From:
						$aFields[] = 'From';
						break;
					case EFilterFiels::To:
						$aFields[] = 'To';
						$aFields[] = 'CC';
						break;
					case EFilterFiels::Subject:
						$aFields[] = 'Subject';
						break;
				}

				// condition
				foreach ($aFields as $iIndex => $sField)
				{
					$aFields[$iIndex] = '"'.$this->quoteValue($sField).'"';
				}

				$sCondition = '';
				$sFields = implode(',', $aFields);
				switch ($oFilter->Condition)
				{
					case EFilterCondition::ContainSubstring:
						$sCondition = 'if header :contains ['.$sFields.'] "'.$this->quoteValue($oFilter->Filter).'" {';
						break;
					case EFilterCondition::ContainExactPhrase:
						$sCondition = 'if header :is ['.$sFields.'] "'.$this->quoteValue($oFilter->Filter).'" {';
						break;
					case EFilterCondition::NotContainSubstring:
						$sCondition = 'if not header :contains ['.$sFields.'] "'.$this->quoteValue($oFilter->Filter).'" {';
						break;
				}

				// folder
				$sFolderFullName = '';
				if (EFilterAction::MoveToFolder === $oFilter->Action)
				{
					$sFolderFullName = api_Utils::ConvertEncoding($oFilter->FolderFullName,
						'utf7-imap', $this->sSieveFolderCharset);
				}

				// action
				$sAction = '';
				switch($oFilter->Action)
				{
					case EFilterAction::DeleteFromServerImmediately:
						$sAction = 'discard ;';
						$sAction .= 'stop ;';
						break;
					case EFilterAction::MoveToFolder:
						$sAction = 'fileinto "'.$this->quoteValue($sFolderFullName).'" ;'."\n";
						$sAction .= 'stop ;';
						break;
				}

				$sEnd = '}';

				if (!$oFilter->Enable)
				{
					$sCondition = '#'.$sCondition;
					$sAction = '#'.$sAction;
					$sEnd = '#'.$sEnd;
				}

				$sFilters .= "\n".'#sieve_filter:'.implode(';', array(
					$oFilter->Enable ? '1' : '0', $oFilter->Condition, $oFilter->Field,
					$oFilter->Filter, $oFilter->Action, $sFolderFullName))."\n";

				$sFilters .= $sCondition."\n";
				$sFilters .= $sAction."\n";
				$sFilters .= $sEnd."\n";
			}

			$sFilters = $sFilters."\n".'#end sieve filter'."\n";

			return $this->SetFiltersRawData($oAccount, $sFilters);
		}

		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sForward
	 * @param bool $bEnabled = true
	 * @return bool
	 */
	public function DisableForward($oAccount)
	{
		$sForward = '';
		$aData = $this->GetForward($oAccount);

		if ($aData && isset($aData[1]))
		{
			$sForward = $aData[1];
		}

		return $this->SetForward($oAccount, $sForward, false);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sSectionName = ''
	 * @param string $sSectionData = ''
	 * @return bool
	 */
	public function Resave($oAccount, $sSectionName = '', $sSectionData = '')
	{
		$this->parseSectionsData($oAccount);
		if (!empty($sSectionName) && !empty($sSectionData))
		{
			$this->setSectionData($sSectionName, $sSectionData);
		}

		return $this->resaveSectionsData($oAccount);
	}

	/**
	 * @param CAccount $oAccount
	 * @return string
	 */
	public function GetFiltersRawData($oAccount)
	{
		$this->parseSectionsData($oAccount);
		return $this->getSectionData('filters');
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFiltersRawData
	 * @return bool
	 */
	public function SetFiltersRawData($oAccount, $sFiltersRawData)
	{
		$this->parseSectionsData($oAccount);
		$this->setSectionData('filters', $sFiltersRawData);

		if (CApiSieveManager::$AutoSave)
		{
			return $this->resaveSectionsData($oAccount);
		}
		return true;
	}

	/**
	 * @param CAccount $oAccount
	 * @return \MailSo\Sieve\ManageSieveClient | false
	 */
	protected function getSieveDriver(CAccount $oAccount)
	{
		$oSieve = false;
		if ($oAccount instanceof CAccount)
		{
			if (!isset($this->aSieves[$oAccount->Email]))
			{
				$oSieve = \MailSo\Sieve\ManageSieveClient::NewInstance();
				$oSieve->SetLogger(\CApi::MailSoLogger());

				$this->aSieves[$oAccount->Email] = $oSieve;
			}
			else
			{
				$oSieve = $this->aSieves[$oAccount->Email];
			}
		}

		return $oSieve;
	}

	/**
	 * @param CAccount $oAccount
	 * @return \MailSo\Sieve\ManageSieveClient | false
	 */
	protected function connectSieve($oAccount)
	{
		$bResult = false;
		$oSieve = $this->getSieveDriver($oAccount);
		if ($oSieve)
		{
			if (!$oSieve->IsConnected())
			{
				$sGeneralHost = CApi::GetConf('sieve.config.host', '');
				$sGeneralPassword = CApi::GetConf('sieve.config.general-password', '');
				$bResult = $oSieve
					->Connect($oAccount->IsInternal || 0 === strlen($sGeneralHost) ? $oAccount->IncomingMailServer : $sGeneralHost, (int) CApi::GetConf('sieve.config.port', 2000), \MailSo\Net\Enumerations\ConnectionSecurityType::NONE)
					->Login($oAccount->IncomingMailLogin, 0 === strlen($sGeneralPassword) ? $oAccount->IncomingMailPassword : $sGeneralPassword)
				;
			}
			else
			{
				$bResult = true;
			}

			if ($oSieve)
			{
				return $oSieve;
			}
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return string | false
	 */
	protected function getSieveFile($oAccount)
	{
		$sResult = false;
		
		try
		{
			$oSieve = $this->connectSieve($oAccount);
			if ($oSieve)
			{
				if ($oSieve->IsActiveScript($this->sSieveFileName))
				{
					$sResult = $oSieve->GetScript($this->sSieveFileName);
				}
			}
		}
		catch (\Exception $oException)
		{
			$sResult = false;
		}

		return is_string($sResult) ? str_replace("\r", '', $sResult) : false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sText
	 * @return bool
	 */
	protected function setSieveFile($oAccount, $sText)
	{
		$sText = str_replace("\r", '', $sText);

		try
		{
			$oSieve = $this->connectSieve($oAccount);
			if ($oSieve)
			{
				$oSieve->CheckScript($sText);
				
				$oSieve->PutScript($this->sSieveFileName, $sText);
				$oSieve->SetActiveScript($this->sSieveFileName);

				$bResult = true;
			}
		}
		catch (\Exception $oException)
		{
			$bResult = false;
		}

		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	protected function resaveSectionsData($oAccount)
	{
		$this->bSectionsParsed = false;
		return $this->setSieveFile($oAccount, $this->selectionsDataToString());
	}

	/**
	 * @param CAccount $oAccount
	 * @param bool $bForced = false
	 */
	protected function parseSectionsData($oAccount, $bForced = false)
	{
		if (!$this->bSectionsParsed || $bForced)
		{
			$sText = $this->getSieveFile($oAccount);
			if (false !== $sText)
			{
				if (is_array($this->aSectionsOrders))
				{
					foreach ($this->aSectionsOrders as $sSectionName)
					{
						$aParams = $this->getSectionParams($sSectionName, $sText);
						if ($aParams)
						{
							$this->aSectionsData[$sSectionName] = trim(substr($sText,
								$aParams[0] + strlen($aParams[2]),
								$aParams[1] - $aParams[0] - strlen($aParams[2])
							));
						}
					}
				}
			}
		}
	}

	/**
	 * @return string
	 */
	protected function selectionsDataToString()
	{
		$sResult = '';
		if (is_array($this->aSectionsOrders))
		{
			foreach ($this->aSectionsOrders as $sSectionName)
			{
				if (!empty($this->aSectionsData[$sSectionName]))
				{
					$sResult .= "\n".
						$this->getComment($sSectionName, true)."\n".
						$this->aSectionsData[$sSectionName]."\n".
						$this->getComment($sSectionName, false)."\n";
				};
			}
		}

		$sResult = 'require ["fileinto", "copy", "vacation"] ;'."\n".$sResult;
		$sResult = "# Sieve filter\n".$sResult;
		$sResult .= "keep ;\n";
		return $sResult;
	}

	/**
	 * @param string $sSectionName
	 * @return string
	 */
	protected function getSectionData($sSectionName)
	{
		if (in_array($sSectionName, $this->aSectionsOrders) && !empty($this->aSectionsData[$sSectionName]))
		{
			  return $this->aSectionsData[$sSectionName];
		}

		return '';
	}

	/**
	 * @param string $sSectionName
	 * @param string $sData
	 */
	protected function setSectionData($sSectionName, $sData)
	{
		if (in_array($sSectionName, $this->aSectionsOrders))
		{
			 $this->aSectionsData[$sSectionName] = $sData;
		}
	}

	protected function getComment($sSectionName, $bIsBeginComment = true)
	{
		return '#'.($bIsBeginComment ? 'begin' : 'end').' = '.$sSectionName.' =';
	}

	protected function getSectionParams($sSectionName, $sText)
	{
		$aResult = false;

		if (!empty($sText))
		{
			$sBeginComment = $this->getComment($sSectionName, true);
			$sEndComment = $this->getComment($sSectionName, false);

			$iBegin = strpos($sText, $sBeginComment);
			if (false !== $iBegin)
			{
				$iEnd = strpos($sText, $sEndComment, $iBegin);
				if (false !== $iEnd)
				{
					$aResult = array($iBegin, $iEnd, $sBeginComment, $sEndComment);
				}
			}
		}

		return $aResult;
	}

}
