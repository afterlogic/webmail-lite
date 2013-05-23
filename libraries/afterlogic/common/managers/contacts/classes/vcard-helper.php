<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Contacts
 * @subpackage Helpers
 */
class CApiContactsVCardHelper
{
	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component $vCard
	* @return void
	*/
	public static function UpdateVCardAddressesFromContact($oContact, &$vCard)
	{
		$bFindHome = false;
		$bFindWork = false;

		$vCardCopy = clone $vCard;

		$sADRHome = ';;'.$oContact->HomeStreet.';'.$oContact->HomeCity.';'.$oContact->HomeState.';'.
			$oContact->HomeZip.';'.$oContact->HomeCountry;

		if (empty($oContact->HomeStreet) && empty($oContact->HomeCity) &&
				empty($oContact->HomeState) && empty($oContact->HomeZip) &&
						empty($oContact->HomeCountry))
		{
			$bFindHome = true;
		}

		$sADRWork = ';;'.$oContact->BusinessStreet.';'.$oContact->BusinessCity.';'.$oContact->BusinessState.';'.
				$oContact->BusinessZip.';'.$oContact->BusinessCountry;

		if (empty($oContact->BusinessStreet) && empty($oContact->BusinessCity) &&
				empty($oContact->BusinessState) && empty($oContact->BusinessZip) &&
						empty($oContact->BusinessCountry))
		{
			$bFindWork = true;
		}

		if (isset($vCardCopy->ADR))
		{
			unset($vCard->ADR);
			foreach ($vCardCopy->ADR as $oAdr)
			{
				if ($oAdr->offsetExists('TYPE'))
				{
					$aTypes = array();
					$oTypes = $oAdr->offsetGet('TYPE');
					foreach ($oTypes as $oType)
					{
						$aTypes[] = strtoupper($oType->value);
					}
					if (in_array('HOME', $aTypes))
					{
						if ($bFindHome)
						{
							unset($oAdr);
						}
						else
						{
							$oAdr->value = $sADRHome;
							$bFindHome = true;
						}
					}
					if (in_array('WORK', $aTypes))
					{
						if ($bFindWork)
						{
							unset($oAdr);
						}
						else
						{
							$oAdr->value = $sADRWork;
							$bFindWork = true;
						}
					}
				}
				if (isset($oAdr))
				{
					$vCard->add($oAdr);
				}
			}
		}

		if (!$bFindHome)
		{
			$oADRHome = new \Sabre\VObject\Property('ADR', $sADRHome);
			$oADRHome->add(new \Sabre\VObject\Parameter('TYPE', 'HOME'));
			$vCard->add($oADRHome);
		}
		if (!$bFindWork)
		{
			$oADRWork = new \Sabre\VObject\Property('ADR', $sADRWork);
			$oADRWork->add(new \Sabre\VObject\Parameter('TYPE', 'WORK'));
			$vCard->add($oADRWork);
		}
	}

	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component $vCard
	* @return void
	*/
	public static function UpdateVCardEmailsFromContact($oContact, &$vCard)
	{
		$bFindHome = false;
		$bFindWork = false;
		$bFindOther = false;

		$vCardCopy = clone $vCard;

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

		if (isset($vCardCopy->EMAIL))
		{
			unset($vCard->EMAIL);
			foreach ($vCardCopy->EMAIL as $oEmail)
			{
				if ($oEmail->offsetExists('TYPE'))
				{
					$aTypes = array();
					$oTypes = $oEmail->offsetGet('TYPE');
					foreach ($oTypes as $oType)
					{
						$sType = strtoupper($oType->value);
						if ($sType !== 'PREF')
						{
							$aTypes[] = strtoupper($oType->value);
						}
					}

					if (in_array('HOME', $aTypes))
					{
						if ($bFindHome)
						{
							unset($oEmail);
						}
						else
						{
							$bFindHome = true;
							$oEmail = new \Sabre\VObject\Property('EMAIL', $oContact->HomeEmail);
							if ($oContact->PrimaryEmail == EPrimaryEmailType::Home)
							{
								$aTypes[] = 'PREF';
							}
							foreach ($aTypes as $sType)
							{
								$oEmail->add(new \Sabre\VObject\Parameter('TYPE', $sType));
							}
						}
					}
					else if (in_array('WORK', $aTypes))
					{
						if ($bFindWork)
						{
							unset($oEmail);
						}
						else
						{
							$bFindWork = true;
							$oEmail = new \Sabre\VObject\Property('EMAIL', $oContact->BusinessEmail);
							if ($oContact->PrimaryEmail == EPrimaryEmailType::Business)
							{
								$aTypes[] = 'PREF';
							}
							foreach ($aTypes as $sType)
							{
								$oEmail->add(new \Sabre\VObject\Parameter('TYPE', $sType));
							}
						}
					}
					else
					{
						if (isset($vCard->{'X-ABLabel'}))
						{
							foreach ($vCard->{'X-ABLabel'} as $oABLabel)
							{
								if (isset($oEmail) && $oEmail->group == $oABLabel->group &&
										$oABLabel->value == '_$!<Other>!$_')
								{
									if ($bFindOther)
									{
										unset($oEmail);
									}
									else
									{
										$bFindOther = true;
										$oEmail = new \Sabre\VObject\Property('EMAIL', $oContact->OtherEmail);
										$oEmail->group = $oABLabel->group;
										if ($oContact->PrimaryEmail == EPrimaryEmailType::Other)
										{
											$aTypes[] = 'PREF';
										}
										foreach ($aTypes as $sType)
										{
											$oEmail->add(new \Sabre\VObject\Parameter('TYPE', $sType));
										}
										break;
									}
								}
							}
						}
					}
				}
				if (isset($oEmail))
				{
					$vCard->add($oEmail);
				}
			}
		}


		if (!$bFindHome)
		{
			$oEMAILHome = new \Sabre\VObject\Property('EMAIL', $oContact->HomeEmail);
			$oEMAILHome->add(new \Sabre\VObject\Parameter('TYPE', 'INTERNET'));
			$oEMAILHome->add(new \Sabre\VObject\Parameter('TYPE', 'HOME'));
			if ($oContact->PrimaryEmail == EPrimaryEmailType::Home)
			{
				$oEMAILHome->add(new \Sabre\VObject\Parameter('TYPE', 'PREF'));
			}
			$vCard->add($oEMAILHome);
		}
		if (!$bFindWork)
		{
			$oEMAILWork = new \Sabre\VObject\Property('EMAIL', $oContact->BusinessEmail);
			$oEMAILWork->add(new \Sabre\VObject\Parameter('TYPE', 'INTERNET'));
			$oEMAILWork->add(new \Sabre\VObject\Parameter('TYPE', 'WORK'));
			if ($oContact->PrimaryEmail == EPrimaryEmailType::Business)
			{
				$oEMAILWork->add(new \Sabre\VObject\Parameter('TYPE', 'PREF'));
			}
			$vCard->add($oEMAILWork);
		}
		if (!$bFindOther)
		{
			if (!isset($vCard->{'Other.X-ABLabel'}))
			{
				$vCard->add(new \Sabre\VObject\Property('Other.X-ABLabel', '_$!<Other>!$_'));
			}

			$oEMAILOther = new \Sabre\VObject\Property('Other.EMAIL', $oContact->OtherEmail);
			$oEMAILOther->add(new \Sabre\VObject\Parameter('TYPE', 'INTERNET'));
			if ($oContact->PrimaryEmail == EPrimaryEmailType::Other)
			{
				$oEMAILOther->add(new \Sabre\VObject\Parameter('TYPE', 'PREF'));
			}
			$vCard->add($oEMAILOther);
		}
	}

	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component $vCard
	* @return void
	*/
	public static function UpdateVCardUrlsFromContact($oContact, &$vCard)
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

		if (isset($vCard->URL))
		{
			foreach ($vCard->URL as $oUrl)
			{
				if ($oUrl->offsetExists('TYPE'))
				{
					$aTypes = array();
					$oTypes = $oUrl->offsetGet('TYPE');
					foreach ($oTypes as $oType)
					{
						$aTypes[] = strtoupper($oType->value);
					}
					if (in_array('HOME', $aTypes))
					{
						if ($bFindHome)
						{
							unset($oUrl);
						}
						else
						{
							$oUrl->value = $oContact->HomeWeb;
							$bFindHome = true;
						}
					}
					if (in_array('WORK', $aTypes))
					{
						if ($bFindWork)
						{
							unset($oUrl);
						}
						else
						{
							$oUrl->value = $oContact->BusinessWeb;
							$bFindWork = true;
						}
					}
				}
			}
		}

		if (!$bFindHome)
		{
			$oURLHome = new \Sabre\VObject\Property('URL', $oContact->HomeWeb);
			$oURLHome->add(new \Sabre\VObject\Parameter('TYPE', 'HOME'));
			$vCard->add($oURLHome);
		}
		if (!$bFindWork)
		{
			$oURLWork = new \Sabre\VObject\Property('URL', $oContact->BusinessWeb);
			$oURLWork->add(new \Sabre\VObject\Parameter('TYPE', 'WORK'));
			$vCard->add($oURLWork);
		}
	}

	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component $vCard
	* @return void
	*/
	public static function UpdateVCardPhonesFromContact($oContact, &$vCard)
	{
		$bFindHome = false;
		$bFindWork = false;
		$bFindCell = false;
		$bFindHomeFax = false;
		$bFindWorkFax = false;

		$vCardCopy = clone $vCard;

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

		if (isset($vCardCopy->TEL))
		{
			unset($vCard->TEL);
			foreach ($vCardCopy->TEL as $oTel)
			{
				if ($oTel->offsetExists('TYPE'))
				{
					$aTypes = array();
					$oTypes = $oTel->offsetGet('TYPE');
					foreach ($oTypes as $oType)
					{
						$aTypes[] = strtoupper($oType->value);
					}
					if (in_array('VOICE', $aTypes))
					{
						if (in_array('HOME', $aTypes))
						{
							if ($bFindHome)
							{
								unset($oTel);
							}
							else
							{
								$oTel->value = $oContact->HomePhone;
								$bFindHome = true;
							}
						}
						if (in_array('WORK', $aTypes))
						{
							if ($bFindWork)
							{
								unset($oTel);
							}
							else
							{
								$oTel->value = $oContact->BusinessPhone;
								$bFindWork = true;
							}
						}
						if (in_array('CELL', $aTypes))
						{
							if ($bFindCell)
							{
								unset($oTel);
							}
							else
							{
								$oTel->value = $oContact->HomeMobile;
								$bFindCell = true;
							}
						}
					}
					else if (in_array('FAX', $aTypes))
					{
						if (in_array('HOME', $aTypes))
						{
							if ($bFindHomeFax)
							{
								unset($oTel);
							}
							else
							{
								$oTel->value = $oContact->HomeFax;
								$bFindHomeFax = true;
							}
						}
						if (in_array('WORK', $aTypes))
						{
							if ($bFindWorkFax)
							{
								unset($oTel);
							}
							else
							{
								$oTel->value = $oContact->BusinessFax;
								$bFindWorkFax = true;
							}
						}
					}
				}
				if (isset($oTel))
				{
					$vCard->add($oTel);
				}
			}
		}

		if (!$bFindHome)
		{
			$oTELHome = new \Sabre\VObject\Property('TEL', $oContact->HomePhone);
			$oTELHome->add(new \Sabre\VObject\Parameter('TYPE', 'VOICE'));
			$oTELHome->add(new \Sabre\VObject\Parameter('TYPE', 'HOME'));
			$vCard->add($oTELHome);
		}
		if (!$bFindWork)
		{
			$oTELWork = new \Sabre\VObject\Property('TEL', $oContact->BusinessPhone);
			$oTELWork->add(new \Sabre\VObject\Parameter('TYPE', 'VOICE'));
			$oTELWork->add(new \Sabre\VObject\Parameter('TYPE', 'WORK'));
			$vCard->add($oTELWork);
		}
		if (!$bFindCell)
		{
			$oTELCell = new \Sabre\VObject\Property('TEL', $oContact->HomeMobile);
			$oTELCell->add(new \Sabre\VObject\Parameter('TYPE', 'VOICE'));
			$oTELCell->add(new \Sabre\VObject\Parameter('TYPE', 'CELL'));
			$vCard->add($oTELCell);
		}
		if (!$bFindHomeFax)
		{
			$oTELHomeFax = new \Sabre\VObject\Property('TEL', $oContact->HomeFax);
			$oTELHomeFax->add(new \Sabre\VObject\Parameter('TYPE', 'FAX'));
			$oTELHomeFax->add(new \Sabre\VObject\Parameter('TYPE', 'HOME'));
			$vCard->add($oTELHomeFax);
		}
		if (!$bFindWorkFax)
		{
			$oTELWorkFax = new \Sabre\VObject\Property('TEL', $oContact->BusinessFax);
			$oTELWorkFax->add(new \Sabre\VObject\Parameter('TYPE', 'FAX'));
			$oTELWorkFax->add(new \Sabre\VObject\Parameter('TYPE', 'WORK'));
			$vCard->add($oTELWorkFax);
		}
	}

	/**
	* @param CContact $oContact
	* @param \Sabre\VObject\Component $vCard
	* @param bool $bIsUpdate = false
	* @return void
	*/
	public static function UpdateVCardFromContact($oContact, &$vCard, $bIsUpdate = false)
	{
		$vCard->VERSION = '3.0';
		$vCard->PRODID = '-//Afterlogic//6.5.x//EN';

		$sIdContact = $oContact->IdContact;
		$aPathInfo = pathinfo($oContact->IdContact);
		if (isset($aPathInfo['filename']))
		{
			$sIdContact = $aPathInfo['filename'];
		}

		$vCard->UID = $sIdContact;

		if (!empty($oContact->FullName))
		{
			$vCard->FN = $oContact->FullName;
		}
		if (!empty($oContact->LastName) || !empty($oContact->FirstName))
		{
			$vCard->N = $oContact->LastName.';'.$oContact->FirstName.';;'.$oContact->Title.';;';
		}
		if (!empty($oContact->BusinessOffice))
		{
			$vCard->{'X-AFTERLOGIC-OFFICE'} = $oContact->BusinessOffice;
		}
		$vCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'} = $oContact->UseFriendlyName ? '1' : '0';
		if (!empty($oContact->BusinessJobTitle))
		{
			$vCard->TITLE = $oContact->BusinessJobTitle;
		}
		if (!empty($oContact->NickName))
		{
			$vCard->NICKNAME = $oContact->NickName;
		}
		if (!empty($oContact->Notes))
		{
			$vCard->NOTE = $oContact->Notes;
		}
		if (!empty($oContact->BusinessCompany) || !empty($oContact->BusinessDepartment))
		{
			$vCard->ORG = $oContact->BusinessCompany.';'.$oContact->BusinessDepartment;
		}
		if(count($oContact->GroupsIds) > 0)
		{
			$vCard->CATEGORIES = implode(',', $oContact->GroupsIds);
		}

		self::UpdateVCardAddressesFromContact($oContact, $vCard);
		self::UpdateVCardEmailsFromContact($oContact, $vCard);
		self::UpdateVCardUrlsFromContact($oContact, $vCard);
		self::UpdateVCardPhonesFromContact($oContact, $vCard);

		unset($vCard->BDAY);
		if ($oContact->BirthdayYear !== 0 && $oContact->BirthdayMonth !== 0 &&
				$oContact->BirthdayDay !== 0)
		{
			$sBDayDT = $oContact->BirthdayYear.'-'.$oContact->BirthdayMonth.'-'.$oContact->BirthdayDay;
			$oBDay = new \Sabre\VObject\Property('BDAY', $sBDayDT);
			$oBDay->add(new \Sabre\VObject\Parameter('VALUE', 'DATE'));
			$vCard->add($oBDay);
		}
	}

	/**
	* @param CGroup $oGroup
	* @param \Sabre\VObject\Component $vCard
	* @param bool $bIsUpdate = false
	* @return void
	*/
	public static function UpdateVCardFromGroup($oGroup, &$vCard, $bIsUpdate = false)
	{
		$vCard->VERSION = '3.0';
		$vCard->PRODID = '-//Afterlogic//6.5.x//EN';
		$vCard->UID = $oGroup->IdGroup;

		if (!empty($oGroup->Name))
		{
			$vCard->FN = $oGroup->Name;
		}

		unset($vCard->CARD);
		foreach ($oGroup->ContactsIds as $sContactsId)
		{
			$vCard->add('CARD', $sContactsId);
		}
	}
}
