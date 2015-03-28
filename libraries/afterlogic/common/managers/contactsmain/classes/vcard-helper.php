<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Contactsmain
 * @subpackage Helpers
 */
class CApiContactsVCardHelper
{
	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component $oVCard
	* @return void
	*/
	public static function UpdateVCardAddressesFromContact($oContact, &$oVCard)
	{
		$bFindHome = false;
		$bFindWork = false;

		$oVCardCopy = clone $oVCard;

		$sADRHome = array(
			'',
			'',
			$oContact->HomeStreet,
			$oContact->HomeCity,
			$oContact->HomeState,
			$oContact->HomeZip,
			$oContact->HomeCountry
		);

		if (empty($oContact->HomeStreet) && empty($oContact->HomeCity) &&
				empty($oContact->HomeState) && empty($oContact->HomeZip) &&
						empty($oContact->HomeCountry))
		{
			$bFindHome = true;
		}

		$sADRWork = array(
			'',
			'',
			$oContact->BusinessStreet,
			$oContact->BusinessCity,
			$oContact->BusinessState,
			$oContact->BusinessZip,
			$oContact->BusinessCountry
		);

		if (empty($oContact->BusinessStreet) && empty($oContact->BusinessCity) &&
				empty($oContact->BusinessState) && empty($oContact->BusinessZip) &&
						empty($oContact->BusinessCountry))
		{
			$bFindWork = true;
		}

		if (isset($oVCardCopy->ADR))
		{
			unset($oVCard->ADR);
			foreach ($oVCardCopy->ADR as $oAdr)
			{
				if ($oTypes = $oAdr['TYPE'])
				{
					if ($oTypes->has('HOME'))
					{
						if ($bFindHome)
						{
							unset($oAdr);
						}
						else
						{
							$oAdr->setValue($sADRHome);
							$bFindHome = true;
						}
					}
					if ($oTypes->has('WORK'))
					{
						if ($bFindWork)
						{
							unset($oAdr);
						}
						else
						{
							$oAdr->setValue($sADRWork);
							$bFindWork = true;
						}
					}
				}
				if (isset($oAdr))
				{
					$oVCard->add($oAdr);
				}
			}
		}

		if (!$bFindHome)
		{
			$oVCard->add('ADR', $sADRHome, array('TYPE' => array('HOME')));
		}
		if (!$bFindWork)
		{
			$oVCard->add('ADR', $sADRWork, array('TYPE' => array('WORK')));
		}
	}

	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component\VCard $oVCard
	* @return void
	*/
	public static function UpdateVCardEmailsFromContact($oContact, &$oVCard)
	{
		$bFindHome = (empty($oContact->HomeEmail)) ? false : true;
		$bFindWork = (empty($oContact->BusinessEmail)) ? false : true;
		$bFindOther = (empty($oContact->OtherEmail)) ? false : true;

		$oVCardCopy = clone $oVCard;

		if (isset($oVCardCopy->EMAIL))
		{
			unset($oVCard->EMAIL);
			foreach ($oVCardCopy->EMAIL as $oEmail)
			{
				if ($oTypes = $oEmail['TYPE'])
				{
					$aTypes = array();
					foreach ($oTypes as $sType)
					{
						if ('PREF' !== strtoupper($sType))
						{
							$aTypes[] = $sType;
						}
					}
					$oTypes->setValue($aTypes);

					if ($oTypes->has('HOME'))
					{
						if (!$bFindHome)
						{
							unset($oEmail);
						}
						else
						{
							$bFindHome = false;
							if ($oContact->PrimaryEmail == EPrimaryEmailType::Home)
							{
								$oTypes->addValue('PREF');
							}
							$oEmail->setValue($oContact->HomeEmail);
						}
					}
					else if ($oTypes->has('WORK'))
					{
						if (!$bFindWork)
						{
							unset($oEmail);
						}
						else
						{
							$bFindWork = false;
							if ($oContact->PrimaryEmail == EPrimaryEmailType::Business)
							{
								$oTypes->addValue('PREF');
							}
							$oEmail->setValue($oContact->BusinessEmail);
						}
					}
					else if ($oTypes->has('OTHER'))
					{
						if (!$bFindOther)
						{
							unset($oEmail);
						}
						else
						{
							$bFindOther = false;
							if ($oContact->PrimaryEmail == EPrimaryEmailType::Other)
							{
								$oTypes->addValue('PREF');
							}
							$oEmail->setValue($oContact->OtherEmail);
						}
					}
					else if ($oEmail->group && isset($oVCardCopy->{$oEmail->group.'.X-ABLabel'}) &&
							(strtolower((string) $oVCardCopy->{$oEmail->group.'.X-ABLabel'}) === '_$!<other>!$_') ||
							(strtolower((string) $oVCardCopy->{$oEmail->group.'.X-ABLabel'}) === 'other'))
					{
						if (!$bFindOther)
						{
							unset($oVCardCopy->{$oEmail->group.'.X-ABLabel'});
							unset($oEmail);
						}
						else
						{
							$bFindOther = false;
							if ($oContact->PrimaryEmail == EPrimaryEmailType::Other)
							{
								$oTypes->addValue('PREF');
							}
							$oEmail->setValue($oContact->OtherEmail);
						}
					}
						
				}
				if (isset($oEmail))
				{
					$oVCard->add($oEmail);
				}
			}
		}
		
		if ($bFindHome)
		{
			$aTypes = array('HOME');
			if ($oContact->PrimaryEmail == EPrimaryEmailType::Home)
			{
				$aTypes[] = 'PREF';
			}
			$oEmail = $oVCard->add('EMAIL', $oContact->HomeEmail, array('TYPE' => $aTypes));
		}
		if ($bFindWork)
		{
			$aTypes = array('WORK');
			if ($oContact->PrimaryEmail == EPrimaryEmailType::Business)
			{
				$aTypes[] = 'PREF';
			}
			$oEmail = $oVCard->add('EMAIL', $oContact->BusinessEmail, array('TYPE' => $aTypes));
		}
		if ($bFindOther)
		{
			$aTypes = array('OTHER');
			if ($oContact->PrimaryEmail == EPrimaryEmailType::Other)
			{
				$aTypes[] = 'PREF';
			}			
			$oEmail = $oVCard->add('EMAIL', $oContact->OtherEmail, array('TYPE' => $aTypes));
		}
	}

	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component $oVCard
	* @return void
	*/
	public static function UpdateVCardUrlsFromContact($oContact, &$oVCard)
	{
		$bFindHome = false;
		$bFindWork = false;

		if (empty($oContact->HomeWeb))
		{
			$bFindHome = true;
		}
		if (empty($oContact->BusinessWeb))
		{
			$bFindWork = true;
		}

		if (isset($oVCard->URL))
		{
			foreach ($oVCard->URL as $oUrl)
			{
				if ($oTypes = $oUrl['TYPE'])
				{
					if ($oTypes->has('HOME'))
					{
						if ($bFindHome)
						{
							unset($oUrl);
						}
						else
						{
							$oUrl->setValue($oContact->HomeWeb);
							$bFindHome = true;
						}
					}
					if ($oTypes->has('WORK'))
					{
						if ($bFindWork)
						{
							unset($oUrl);
						}
						else
						{
							$oUrl->setValue($oContact->BusinessWeb);
							$bFindWork = true;
						}
					}
				}
			}
		}

		if (!$bFindHome)
		{
			$oVCard->add('URL', $oContact->HomeWeb, array('TYPE' => array('HOME')));
		}
		if (!$bFindWork)
		{
			$oVCard->add('URL', $oContact->BusinessWeb, array('TYPE' => array('WORK')));
		}
	}

	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component\VCard $oVCard
	* @return void
	*/
	public static function UpdateVCardPhonesFromContact($oContact, &$oVCard)
	{
		$bFindHome = false;
		$bFindWork = false;
		$bFindCell = false;
		$bFindHomeFax = false;
		$bFindWorkFax = false;

		$oVCardCopy = clone $oVCard;

		if (empty($oContact->HomePhone))
		{
			$bFindHome = true;
		}
		if (empty($oContact->BusinessPhone))
		{
			$bFindWork = true;
		}
		if (empty($oContact->HomeMobile))
		{
			$bFindCell = true;
		}
		if (empty($oContact->HomeFax))
		{
			$bFindHomeFax = true;
		}
		if (empty($oContact->BusinessFax))
		{
			$bFindWorkFax = true;
		}

		if (isset($oVCardCopy->TEL))
		{
			unset($oVCard->TEL);
			foreach ($oVCardCopy->TEL as $oTel)
			{
				if ($oTypes = $oTel['TYPE'])
				{
					if ($oTypes->has('VOICE'))
					{
						if ($oTypes->has('HOME'))
						{
							if ($bFindHome)
							{
								unset($oTel);
							}
							else
							{
								$oTel->setValue($oContact->HomePhone);
								$bFindHome = true;
							}
						}
						if ($oTypes->has('WORK'))
						{
							if ($bFindWork)
							{
								unset($oTel);
							}
							else
							{
								$oTel->setValue($oContact->BusinessPhone);
								$bFindWork = true;
							}
						}
						if ($oTypes->has('CELL'))
						{
							if ($bFindCell)
							{
								unset($oTel);
							}
							else
							{
								$oTel->setValue($oContact->HomeMobile);
								$bFindCell = true;
							}
						}
					}
					else if ($oTypes->has('FAX'))
					{
						if ($oTypes->has('HOME'))
						{
							if ($bFindHomeFax)
							{
								unset($oTel);
							}
							else
							{
								$oTel->setValue($oContact->HomeFax);
								$bFindHomeFax = true;
							}
						}
						if ($oTypes->has('WORK'))
						{
							if ($bFindWorkFax)
							{
								unset($oTel);
							}
							else
							{
								$oTel->setValue($oContact->BusinessFax);
								$bFindWorkFax = true;
							}
						}
					}
				}
				if (isset($oTel))
				{
					$oVCard->add($oTel);
				}
			}
		}

		if (!$bFindHome)
		{
			$oVCard->add('TEL', $oContact->HomePhone, array('TYPE' => array('VOICE', 'HOME')));
		}
		if (!$bFindWork)
		{
			$oVCard->add('TEL', $oContact->BusinessPhone, array('TYPE' => array('VOICE', 'WORK')));
		}
		if (!$bFindCell)
		{
			$oVCard->add('TEL', $oContact->HomeMobile, array('TYPE' => array('VOICE', 'CELL')));
		}
		if (!$bFindHomeFax)
		{
			$oVCard->add('TEL', $oContact->HomeFax, array('TYPE' => array('FAX', 'HOME')));
		}
		if (!$bFindWorkFax)
		{
			$oVCard->add('TEL', $oContact->BusinessFax, array('TYPE' => array('FAX', 'WORK')));
		}
	}

	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component $oVCard
	* @param bool $bIsUpdate = false
	* @return void
	*/
	public static function UpdateVCardFromContact($oContact, &$oVCard, $bIsUpdate = false)
	{
		$oVCard->VERSION = '3.0';
		$oVCard->PRODID = '-//Afterlogic//7.5.x//EN';

		$sIdContact = $oContact->IdContactStr;
		$aPathInfo = pathinfo($oContact->IdContactStr);
		if (isset($aPathInfo['filename']))
		{
			$sIdContact = $aPathInfo['filename'];
		}

		$oVCard->UID = $sIdContact;

		$oVCard->FN = $oContact->FullName;
		$oVCard->N = array(
			$oContact->LastName,
			$oContact->FirstName,
			'',
			$oContact->Title,
			'',
			''
		);
		$oVCard->{'X-AFTERLOGIC-OFFICE'} = $oContact->BusinessOffice;
		$oVCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'} = $oContact->UseFriendlyName ? '1' : '0';
		$oVCard->TITLE = $oContact->BusinessJobTitle;
		$oVCard->NICKNAME = $oContact->NickName;
		$oVCard->NOTE = $oContact->Notes;
		$oVCard->ORG = array(
			$oContact->BusinessCompany,
			$oContact->BusinessDepartment
		);
		$oVCard->CATEGORIES = $oContact->GroupsIds;

		self::UpdateVCardAddressesFromContact($oContact, $oVCard);
		self::UpdateVCardEmailsFromContact($oContact, $oVCard);
		self::UpdateVCardUrlsFromContact($oContact, $oVCard);
		self::UpdateVCardPhonesFromContact($oContact, $oVCard);

		unset($oVCard->BDAY);
		if ($oContact->BirthdayYear !== 0 && $oContact->BirthdayMonth !== 0 && $oContact->BirthdayDay !== 0)
		{
			$sBDayDT = $oContact->BirthdayYear.'-'.$oContact->BirthdayMonth.'-'.$oContact->BirthdayDay;
			$oVCard->add('BDAY', $sBDayDT);
		}
	}
}
