<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Maincontacts
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
	 * @var string
	 */
	public $ETag;

	/**
	 * @var bool
	 */
	public $IsGroup;

	/**
	 * @var string
	 */
	public $Name;

	/**
	 * @var string
	 */
	public $Email;

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
	public $OnlyRead;

	public function __construct()
	{
		$this->Id = null;
		$this->IdStr = null;
		$this->ETag = null;
		$this->IsGroup = false;
		$this->Name = '';
		$this->Email = '';
		$this->Frequency = 0;
		$this->UseFriendlyName = false;
		$this->Global = false;
		$this->ItsMe = false;
		$this->ReadOnly = false;
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
					$this->IsGroup = false;
					$this->Name = (string) $oRow->fullname;
					$this->Email = (string) $oRow->view_email;

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
						(int) $oRow->type === EContactType::Global_ &&
						(string) $oRow->type_id === (string) $mItsMeTypeId
					)
					{
						$this->ItsMe = true;
						$this->ReadOnly = false;
					}

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
