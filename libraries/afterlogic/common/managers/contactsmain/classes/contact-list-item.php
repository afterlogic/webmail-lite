<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Contactsmain
 * @subpackage Classes
 */
class CContactListItem
{
	/**
	 * @var mixed
	 */
	public $Id;

	/**
	 * @var string
	 */
	public $IdStr;
	
	/**
	 *  @var int $IdUser	
	 */
	public $IdUser;

	/**
	 * @var string
	 */
	public $ETag;

	/**
	 * @var bool
	 */
	public $IsGroup;
	
	/**
	 * @var bool
	 */
	public $IsOrganization;
	

	/**
	 * @var string
	 */
	public $Name;

	/**
	 * @var string
	 */
	public $Email;

	/**
	 * @var array
	 */
	public $Phones;

	/**
	 * @var int
	 */
	public $Frequency;

	/**
	 * @var bool
	 */
	public $UseFriendlyName;

	/**
	 * @var bool
	 */
	public $Global;

	/**
	 * @var bool
	 */
	public $ItsMe;

	/**
	 * @var bool
	 */
	public $ReadOnly;

	/**
	 * @var bool
	 */
	public $Auto;
	
	/**
	 * @var bool
	 */
	public $ForSharedToAll;
	
	/**
	 * @var bool
	 */
	public $SharedToAll;

	/**
	 * @var array
	 */
	public $Events;	
	
	/**
	 * @var int
	 */
	public $AgeScore;
	
	public function __construct()
	{
		$this->Id = null;
		$this->IdStr = null;
		$this->IdUser = null;
		$this->ETag = null;
		$this->IsGroup = false;
		$this->IsOrganization = false;
		$this->Name = '';
		$this->Email = '';
		$this->Emails = array();
		$this->Phones = array();
		$this->Frequency = 0;
		$this->UseFriendlyName = false;
		$this->Global = false;
		$this->ItsMe = false;
		$this->ReadOnly = false;
		$this->Auto = false;
		$this->ForSharedToAll = false;
		$this->SharedToAll = false;
		$this->Events = array();
		$this->AgeScore = 1;
	}

	/**
	 * @param \Sabre\CardDAV\Card $oVCard
	 */
	public function InitBySabreCardDAVCard($oVCard)
	{
		if ($oVCard)
		{
			if ($oVCard->name == 'VCARD')
			{
				if (isset($oVCard->UID))
				{
					$this->Id = (string)$oVCard->UID;
					$this->IdStr = $this->Id;
				}
				$this->IsGroup = false;

				if (isset($oVCard->FN))
				{
					$this->Name = (string)$oVCard->FN;
				}

				if (isset($oVCard->EMAIL))
				{
					$this->Email = (string)$oVCard->EMAIL[0];
					foreach($oVCard->EMAIL as $oEmail)
					{
						if ($oTypes = $oEmail['TYPE'])
						{
							if ($oTypes->has('PREF'))
							{
								$this->Email = (string)$oEmail;
								break;
							}
						}
					}
				}
				if (isset($oVCard->{'X-AFTERLOGIC-USE-FREQUENCY'}))
				{
					$this->Frequency = (int)$oVCard->{'X-AFTERLOGIC-USE-FREQUENCY'}->getValue();
				}
				else
				{
					$this->Frequency = 0;
				}

				$this->UseFriendlyName = true;
				if (isset($oVCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'}))
				{
					$this->UseFriendlyName = '1' === (string)$oVCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'};
				}
			}
		}
	}

	/**
	 * @param string $sRowType
	 * @param array $aRow
	 */
	public function InitByLdapRowWithType($sRowType, $aRow)
	{
		if ($aRow)
		{
			switch ($sRowType)
			{
				case 'contact':
					$this->Id = $aRow['un'][0];
					$this->IdStr = $this->Id;
					$this->IsGroup = false;
					$this->Name = (string) $aRow['cn'][0];
					$this->Email = isset($aRow['mail'][0]) ? (string) $aRow['mail'][0] :
						(isset($aRow['homeemail'][0]) ? (string) $aRow['homeemail'][0] : '');
					$this->Frequency = 0;
					$this->UseFriendlyName = true;
					break;

				case 'group':
					$this->Id = $aRow['un'][0];
					$this->IdStr = $this->Id;
					$this->IsGroup = true;
					$this->Name = $aRow['cn'][0];
					$this->Email = '';
					$this->Frequency = 0;
					$this->UseFriendlyName = true;
					break;
			}
		}
	}

	/**
	 * @param string $sDbRowType
	 * @param stdClass $oRow
	 * @param mixed $mItsMeTypeId = null
	 */
	public function InitByDbRowWithType($sDbRowType, $oRow, $mItsMeTypeId = null)
	{
		if ($oRow)
		{
			switch ($sDbRowType)
			{
				case 'contact':
				case 'suggest-contacts':
					$this->Id = (int) $oRow->id_addr;
					$this->IdStr = (string) $oRow->str_id;
					$this->IdUser = (int) $oRow->id_user;
					$this->IsGroup = false;
					$this->Name = (string) $oRow->fullname;
					$this->Email = (string) $oRow->view_email;
					$this->Auto = isset($oRow->auto_create) ? (bool) $oRow->auto_create : false;
					$this->AgeScore = isset($oRow->age_score) ? (int) $oRow->age_score : 1;

					if (!empty($oRow->h_email))
					{
						$this->Emails[] = trim($oRow->h_email);
					}
					if (!empty($oRow->b_email))
					{
						$this->Emails[] = trim($oRow->b_email);
					}
					if (!empty($oRow->other_email))
					{
						$this->Emails[] = trim($oRow->other_email);
					}

					if (!empty($oRow->b_phone))
					{
						$this->Phones[] = trim($oRow->b_phone);
					}
					if (!empty($oRow->h_phone))
					{
						$this->Phones[] = trim($oRow->h_phone);
					}
					if (!empty($oRow->h_mobile))
					{
						$this->Phones[] = trim($oRow->h_mobile);
					}
//					if (0 === strlen($this->Name))
//					{
//						$this->Name = (string) $oRow->firstname;
//					}

					switch ((int) $oRow->primary_email)
					{
						case EPrimaryEmailType::Home:
							$this->Email = (string) $oRow->h_email;
							break;
						case EPrimaryEmailType::Business:
							$this->Email = (string) $oRow->b_email;
							break;
						case EPrimaryEmailType::Other:
							$this->Email = (string) $oRow->other_email;
							break;
					}
					$this->Frequency = (int) $oRow->use_frequency;
					$this->UseFriendlyName = (bool) $oRow->use_friendly_nm;

					if (null !== $mItsMeTypeId &&
						((int) $oRow->type === EContactType::Global_ || (int) $oRow->type === EContactType::GlobalAccounts) &&
						(string) $oRow->type_id === (string) $mItsMeTypeId
					)
					{
						$this->ItsMe = true;
						$this->ReadOnly = false;
					}
					
					if (
						(int) $oRow->type === EContactType::GlobalAccounts
						||
						(int) $oRow->type === EContactType::GlobalMailingList
					)
					{
						$this->Global = true;
						if (!$this->ItsMe)
						{
							$this->ReadOnly = true;
						}
					}

					$this->SharedToAll = (bool) $oRow->shared_to_all;

					break;

				case 'global':
				case 'suggest-global':
					$this->Id = (int) $oRow->id_addr;
					$this->IdStr = (string) $oRow->str_id;
					$this->IsGroup = false;
					$this->ReadOnly = true;
					$this->Global = true;
					$this->Name = (string) $oRow->fullname;
					$this->Email = (string) $oRow->view_email;

					if (!empty($oRow->b_phone))
					{
						$this->Phones[] = trim($oRow->b_phone);
					}
					if (!empty($oRow->h_phone))
					{
						$this->Phones[] = trim($oRow->h_phone);
					}
					if (!empty($oRow->h_mobile))
					{
						$this->Phones[] = trim($oRow->h_mobile);
					}
					
					switch ((int) $oRow->primary_email)
					{
						case EPrimaryEmailType::Home:
							$this->Email = (string) $oRow->h_email;
							break;
						case EPrimaryEmailType::Business:
							$this->Email = (string) $oRow->b_email;
							break;
						case EPrimaryEmailType::Other:
							$this->Email = (string) $oRow->other_email;
							break;
					}
					$this->Frequency = (int) $oRow->use_frequency;
					$this->UseFriendlyName = (bool) $oRow->use_friendly_nm;

					if (null !== $mItsMeTypeId &&
						(int) $oRow->type === EContactType::GlobalAccounts &&
						(string) $oRow->type_id === (string) $mItsMeTypeId
					)
					{
						$this->ItsMe = true;
						$this->ReadOnly = false;
					}

					break;
				
				case 'group':
					$this->Id = (int) $oRow->id_group;
					$this->IsGroup = true;
					$this->Name = (string) $oRow->group_nm;
					$this->Email = '';
					$this->Frequency = (int) $oRow->use_frequency;
					$this->IsOrganization = (bool) $oRow->organization;
					$this->UseFriendlyName = true;
					break;
			}
		}
	}

	/**
	 * @return string
	 */
	public function ToString()
	{
		return ($this->UseFriendlyName && 0 < strlen(trim($this->Name)) && !$this->IsGroup)
			? '"'.trim($this->Name).'" <'.trim($this->Email).'>'
			: (($this->IsGroup) ? trim($this->Name) : trim($this->Email));
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->ToString();
	}
}
