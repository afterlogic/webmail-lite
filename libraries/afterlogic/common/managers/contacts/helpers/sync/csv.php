<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Contacts
 * @subpackage Helpers
 */
class CApiContactsSyncCsv
{
	/**
	 * @var CApiContactsCsvFormatter
	 */
	protected $oApiContactsManager;

	/**
	 * @var CApiContactsCsvParser
	 */
	protected $oFormatter;

	/**
	 * @var CApiContactsCsvParser
	 */
	protected $oParser;

	public function __construct($oApiContactsManager)
	{
		$this->oApiContactsManager = $oApiContactsManager;
		$this->oFormatter = new CApiContactsCsvFormatter();
		$this->oParser = new CApiContactsCsvParser();
	}

	/**
	 * @param int $iUserId
	 * @return string
	 */
	public function Export($iUserId)
	{
		$iOffset = 0;
		$iRequestValue = 50;

		$sResult = '';

		$iCount = $this->oApiContactsManager->GetContactItemsCount($iUserId);
		if (0 < $iCount)
		{
			while ($iOffset < $iCount)
			{
				$aList = $this->oApiContactsManager->GetContactItemsWithoutOrder($iUserId, $iOffset, $iRequestValue);

				if (is_array($aList))
				{
					$oContactListItem = null;
					foreach ($aList as $oContactListItem)
					{
						$oContact = $this->oApiContactsManager->GetContactById($iUserId, $oContactListItem->Id);
						if ($oContact)
						{
							$this->oFormatter->SetContainer($oContact);
							$this->oFormatter->Form();
							$sResult .= $this->oFormatter->GetValue();
						}
					}

					$iOffset += $iRequestValue;
				}
				else
				{
					break;
				}
			}
		}

		return $sResult;
	}

	/**
	 * @param int $iUserId
	 * @param string $sTempFileName
	 * @param int $iParsedCount
	 * @param int $iGroupId
	 * @param bool $bIsShared
	 * @return int
	 */
	public function Import($iUserId, $sTempFileName, &$iParsedCount, $iGroupId, $bIsShared)
	{
		$iCount = -1;
		$iParsedCount = 0;
		if (file_exists($sTempFileName))
		{
			$aCsv = api_Utils::CsvToArray($sTempFileName);
			if (is_array($aCsv))
			{
				$oApiUsersManager = CApi::Manager('users');
				$oAccount = $oApiUsersManager->GetDefaultAccount($iUserId);

				$iCount = 0;
				foreach ($aCsv as $aCsvItem)
				{
					set_time_limit(30);

					$this->oParser->Reset();

					$oContact = new CContact();
					$oContact->IdUser = $iUserId;

					$this->oParser->SetContainer($aCsvItem);
					$aParameters = $this->oParser->GetParameters();

					foreach ($aParameters as $sPropertyName => $mValue)
					{
						if ($oContact->IsProperty($sPropertyName))
						{
							$oContact->{$sPropertyName} = $mValue;
						}
					}

					if (0 === strlen($oContact->FullName))
					{
						$oContact->FullName = trim($oContact->FirstName.' '.$oContact->LastName);
					}
					
					if (0 !== strlen($oContact->HomeEmail))
					{
						$oContact->PrimaryEmail = \EPrimaryEmailType::Home;
						$oContact->ViewEmail = $oContact->HomeEmail;
					}
					else if (0 !== strlen($oContact->BusinessEmail))
					{
						$oContact->PrimaryEmail = \EPrimaryEmailType::Business;
						$oContact->ViewEmail = $oContact->BusinessEmail;
					}
					else if (0 !== strlen($oContact->OtherEmail))
					{
						$oContact->PrimaryEmail = \EPrimaryEmailType::Other;
						$oContact->ViewEmail = $oContact->OtherEmail;
					}
					
					if (strlen($oContact->BirthdayYear) === 2)
					{
						$oDt = DateTime::createFromFormat('y', $oContact->BirthdayYear);
						$oContact->BirthdayYear = $oDt->format('Y');
					}					

					$iParsedCount++;
					$oContact->__SKIP_VALIDATE__ = true;

					if ($oAccount)
					{
						$oContact->IdDomain = $oAccount->IdDomain;
						$oContact->IdTenant = $oAccount->IdTenant;
					}
					$oContact->SharedToAll = $bIsShared;
					$oContact->GroupsIds = array($iGroupId);

					if ($this->oApiContactsManager->CreateContact($oContact))
					{
						$iCount++;
					}

					unset($oContact, $aParameters, $aCsvItem);
				}
			}
		}

		return $iCount;
	}
}
