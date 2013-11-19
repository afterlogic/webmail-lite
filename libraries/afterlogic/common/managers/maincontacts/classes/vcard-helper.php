<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Maincontacts
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
			$oVCard->add('ADR', $sADRHome, array('TYPE' => 'HOME'));
		}
		if (!$bFindWork)
		{
			$oVCard->add('ADR', $sADRWork, array('TYPE' => 'WORK'));
		}
	}

	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component\VCard $oVCard
	* @return void
	*/
	public static function UpdateVCardEmailsFromContact($oContact, &$oVCard)
	{
		$bFindHome = false;
		$bFindWork = false;
		$bFindOther = false;

		$oVCardCopy = clone $oVCard;

		if (empty($oContact->HomeEmail))
		{
			$bFindHome = true;
		}
		if (empty($oContact->BusinessEmail))
		{
			$bFindWork = true;
		}
		if (empty($oContact->OtherEmail))
		{
			$bFindOther = true;
		}

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
						if ($bFindHome)
						{
							unset($oEmail);
						}
						else
						{
							$bFindHome = true;
							if ($oContact->PrimaryEmail == EPrimaryEmailType::Home)
							{
								$oTypes->addValue('PREF');
							}
							$oEmail->setValue($oContact->HomeEmail);
						}
					}
					else if ($oTypes->has('WORK'))
					{
						if ($bFindWork)
						{
							unset($oEmail);
						}
						else
						{
							$bFindWork = true;
							if ($oContact->PrimaryEmail == EPrimaryEmailType::Business)
							{
								$oTypes->addValue('PREF');
							}
							$oEmail->setValue($oContact->HomeEmail);
						}
					}
					else
					{
						if (isset($oVCard->{'X-ABLabel'}))
						{
							foreach ($oVCard->{'X-ABLabel'} as $oABLabel)
							{
								if (isset($oEmail) && $oEmail->group === $oABLabel->group && (string)$oABLabel === '_$!<Other>!$_')
								{
									if ($bFindOther)
									{
										unset($oEmail);
									}
									else
									{
										$bFindOther = true;
										if ($oContact->PrimaryEmail == EPrimaryEmailType::Other)
										{
											$oTypes->addValue('PREF');
										}
										$oEmail->setValue($oContact->OtherEmail);
										$oEmail->group = $oABLabel->group;
										break;
									}
								}
							}
						}
					}
				}
			}
			if (isset($oEmail))
			{
				$oVCard->add($oEmail);
			}
		}


		if (!$bFindHome)
		{
			$oEmail = $oVCard->add('EMAIL', $oContact->HomeEmail, array('TYPE' => 'INTERNET', 'TYPE' => 'HOME'));
			if ($oContact->PrimaryEmail == EPrimaryEmailType::Home)
			{
				$oEmail['TYPE']->addValue('PREF');
			}
		}
		if (!$bFindWork)
		{
			$oEmail = $oVCard->add('EMAIL', $oContact->BusinessEmail, array('TYPE' => 'INTERNET', 'TYPE' => 'WORK'));
			if ($oContact->PrimaryEmail == EPrimaryEmailType::Business)
			{
				$oEmail['TYPE']->addValue('PREF');
			}
		}
		if (!$bFindOther)
		{
			if (!isset($oVCard->{'Other.X-ABLabel'}))
			{
				$oVCard->add('Other.X-ABLabel', '_$!<Other>!$_');
			}

			$oEmail = $oVCard->add('Other.EMAIL', $oContact->OtherEmail, array('TYPE' => 'INTERNET'));
			if ($oContact->PrimaryEmail == EPrimaryEmailType::Other)
			{
				$oEmail['TYPE']->addValue('PREF');
			}			
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
			$oVCard->add('URL', $oContact->HomeWeb, array('TYPE' => 'HOME'));
		}
		if (!$bFindWork)
		{
			$oVCard->add('URL', $oContact->BusinessWeb, array('TYPE' => 'WORK'));
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
			$oVCard->add('TEL', $oContact->HomePhone, array('TYPE' => 'VOICE', 'TYPE' => 'HOME'));
		}
		if (!$bFindWork)
		{
			$oVCard->add('TEL', $oContact->BusinessPhone, array('TYPE' => 'VOICE', 'TYPE' => 'WORK'));
		}
		if (!$bFindCell)
		{
			$oVCard->add('TEL', $oContact->HomeMobile, array('TYPE' => 'VOICE', 'TYPE' => 'CELL'));
		}
		if (!$bFindHomeFax)
		{
			$oVCard->add('TEL', $oContact->HomeFax, array('TYPE' => 'FAX', 'TYPE' => 'HOME'));
		}
		if (!$bFindWorkFax)
		{
			$oVCard->add('TEL', $oContact->BusinessFax, array('TYPE' => 'FAX', 'TYPE' => 'WORK'));
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
		$oVCard->PRODID = '-//Afterlogic//6.5.x//EN';

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
