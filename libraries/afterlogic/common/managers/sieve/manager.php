<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
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

		$this->aSieves = array();
		$this->sGeneralPassword = '';
		$this->sSieveFileName = CApi::GetConf('sieve.config.file', 'sieve');
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
	protected function quoteValue($sValue)
	{
		$sValue = str_replace('"', '\\"', trim($sValue));
		return $sValue;
		return str_replace(array("\r", "\n", "\t"), array('\r', '\n', '\t'), trim($sValue));
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
		if (!empty($sData) && preg_match('/#data=([\d])~([^\n]+)/', $sData, $aMatch) && !empty($aMatch[1]) && !empty($aMatch[2]))
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
	 * @param string $sText
	 * @param string $sSubject
	 * @param bool $bEnabled
	 * @return bool
	 */
	public function SetAutoresponder($oAccount, $sText, $sSubject, $bEnabled = true)
	{
		$sSubject = str_replace(array("\r", "\n", "\t"), ' ', trim($sSubject));

		$sData =
			'#data='.($bEnabled ? '1' : '0').'~'.base64_encode($sSubject."\x0".$sText)."\n".
			($bEnabled ? '' : '#').'vacation :subject "'.
				$this->quoteValue($sSubject).'" "'.$this->quoteValue($sText).'";';

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
		if (!empty($sData) && preg_match('/#data=([\d])~([^\n]+)/', $sData, $aMatch) && !empty($aMatch[1]) && !empty($aMatch[2]))
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
	 * @return CApiSieveProtocol | false
	 */
	protected function getSieveDriver(CAccount $oAccount)
	{
		$oSieve = false;
		if ($oAccount instanceof CAccount)
		{
			$oSieve = isset($this->aSieves[$oAccount->Email])
				? $this->aSieves[$oAccount->Email] : new CApiSieveProtocol(
					CApi::GetConf('sieve.config.host', '127.0.0.1'),
					(int) CApi::GetConf('sieve.config.port', 2000));

			$this->aSieves[$oAccount->Email] = $oSieve;
		}

		return $oSieve;
	}

	/**
	 * @param CAccount $oAccount
	 * @return CApiSieveProtocol | false
	 */
	protected function connectSieve($oAccount)
	{
		$bResult = false;
		$oSieve = $this->getSieveDriver($oAccount);
		if ($oSieve)
		{
			if (!$oSieve->IsConnected())
			{
				$sGeneralPassword = CApi::GetConf('sieve.config.general-password', '');
				$bResult = $oSieve->ConnectAndLogin(
					$oAccount->IncomingMailLogin,
					empty($sGeneralPassword) ? $oAccount->IncomingMailPassword : $sGeneralPassword);
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
		$oSieve = $this->connectSieve($oAccount);
		if ($oSieve)
		{
			$sResult = $oSieve->GetScriptIfActive($this->sSieveFileName);
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

		$oSieve = $this->connectSieve($oAccount);

		$iResult = 0;
		if ($oSieve)
		{
			$iResult = (int) $oSieve->SendScript($this->sSieveFileName, $sText);
			$iResult &= $oSieve->SetActiveScript($this->sSieveFileName);
		}

		return (bool) $iResult;
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
