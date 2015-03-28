<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Voice
 */
class CApiVoiceManager extends AApiManager
{
	/**
	 * @var $oApiContactsManager CApiContactsmainManager
	 */
	private $oApiContactsManager;

	/*
	 * @var $oApiGContactsManager CApiGcontactsManager
	 */
	private $oApiGContactsManager;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('voice', $oManager);

		$this->oApiContactsManager = CApi::Manager('contactsmain');
		$this->oApiGContactsManager = CApi::Manager('gcontacts');
	}

	/**
	 * @param int $iIdUser
	 * @return string
	 */
	private function generateCacheFileName($iIdUser)
	{
		return 0 < $iIdUser ? implode('-', array('user-contacts', $iIdUser, 'callers-names.json')) : '';
	}

	/**
	 * @param int $iIdUser
	 */
	public function FlushCallersNumbersCache($iIdUser)
	{
		$sCacheKey = $this->generateCacheFileName($iIdUser);
		$oApiFileCache = /* @var $oApiFileCache \CApiFilecacheManager */ CApi::Manager('filecache');
		$oApiUsers = /* @var $oApiUsers \CApiUsersManager */ CApi::Manager('users');
		
		if ($oApiFileCache && $oApiUsers && !empty($sCacheKey))
		{
			$oAccount = $oApiUsers->GetDefaultAccount($iIdUser);
			if ($oAccount)
			{
				$oApiFileCache->Clear($oAccount, $sCacheKey);
				CApi::Log('Cache: clear contacts names cache');
			}
		}
	}

	/**
	 * @param CAccount $oAccount
	 * @param array $aNumbers
	 * @param bool $bUseCache = true
	 * @return array
	 */
	public function GetNamesByCallersNumbers($oAccount, $aNumbers, $bUseCache = true)
	{
		$mResult = false;
		$oApiContactsManager = CApi::Manager('contactsmain');
		if (is_array($aNumbers) && 0 < count($aNumbers) && $oAccount && $oApiContactsManager)
		{
			$bFromCache = false;
			$sCacheKey = '';
			$mNamesResult = null;
			$oApiFileCache = $bUseCache ? /* @var $oApiFileCache \CApiFilecacheManager */ CApi::Manager('filecache') : false;
			if ($oApiFileCache)
			{
				$sCacheKey = $this->generateCacheFileName($oAccount->IdUser);
				if (!empty($sCacheKey))
				{
					$sData = $oApiFileCache->Get($oAccount, $sCacheKey);
					if (!empty($sData))
					{
						$mNamesResult = @json_decode($sData, true);
						if (!is_array($mNamesResult))
						{
							$mNamesResult = null;
						}
						else
						{
							$bFromCache = true;
							CApi::Log('Cache: get contacts names from cache (count:'.count($mNamesResult).')');
						}
					}
				}
			}
			
			if (!is_array($mNamesResult))
			{
				$mNamesResult = $oApiContactsManager->GetAllContactsNamesWithPhones($oAccount);
			}

			if (is_array($mNamesResult))
			{
				if (!$bFromCache && $oApiFileCache && 0 < strlen($sCacheKey))
				{
					$oApiFileCache->Put($oAccount, $sCacheKey, @json_encode($mNamesResult));
					CApi::Log('Cache: save contacts names to cache (count:'.count($mNamesResult).')');
				}

				$aNormNumbers = array();
				foreach ($aNumbers as $sNumber)
				{
					$aNormNumbers[$sNumber] = api_Utils::ClearPhone($sNumber);
				}

				foreach ($aNormNumbers as $sInputNumber => $sClearNumber)
				{
					$aNormNumbers[$sInputNumber] = isset($mNamesResult[$sClearNumber])
						? $mNamesResult[$sClearNumber] : '';
				}

				$mResult = $aNormNumbers;
			}
		}
		else if (is_array($aNumbers))
		{
			$mResult = array();
		}

		return $mResult;
	}
}
