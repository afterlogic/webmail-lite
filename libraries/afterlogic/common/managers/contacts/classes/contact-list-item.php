<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Contacts
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
	public $OnlyRead;

	public function __construct()
	{
		$this->Id = null;
		$this->ETag = null;
		$this->IsGroup = false;
		$this->ReadOnly = false;
		$this->Name = '';
		$this->Email = '';
		$this->Frequency = 0;
		$this->UseFriendlyName = false;
		$this->Global = false;
	}

	/**
	 * @param \Sabre\CardDAV\Card $vCard
	 */
	public function InitBySabreCardDAVCard($vCard)
	{
		if ($vCard)
		{
			if ($vCard->name == 'VCARD')
			{
				if (isset($vCard->UID))
				{
					$this->Id = $vCard->UID->value;
				}
				$this->IsGroup = false;

				if (isset($vCard->FN))
				{
					$this->Name = $vCard->FN->value;
				}

				if (isset($vCard->EMAIL))
				{
					$this->Email = $vCard->EMAIL[0]->value;
					foreach($vCard->EMAIL as $oEmail)
					{
						if ($oEmail->offsetExists('TYPE'))
						{
							$aTypes = array();
							$oTypes = $oEmail->offsetGet('TYPE');
							foreach ($oTypes as $oType)
							{
								$aTypes[] = strtoupper($oType->value);
							}
							if (in_array('PREF', $aTypes))
							{
								$this->Email = $oEmail->value;
								break;
							}
						}
					}
				}
				if (isset($vCard->{'X-AFTERLOGIC-USE-FREQUENCY'}))
				{
					$this->Frequency = (int)$vCard->{'X-AFTERLOGIC-USE-FREQUENCY'}->value;
				}
				else
				{
					$this->Frequency = 0;
				}

				$this->UseFriendlyName = true;
				if (isset($vCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'}))
				{
					$this->UseFriendlyName = '1' === (string) $vCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'}->value;
				}
			}
			else if ($vCard->name == 'VLIST')
			{
				if (isset($vCard->UID))
				{
					$this->Id = $vCard->UID->value;
				}
				$this->IsGroup = true;

				if (isset($vCard->FN))
				{
					$this->Name = $vCard->FN->value;
				}

				$this->Frequency = 0;
				$this->UseFriendlyName = true;
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
					$this->IsGroup = false;
					$this->Name = (string) $aRow['cn'][0];
					$this->Email = isset($aRow['mail'][0]) ? (string) $aRow['mail'][0] :
						(isset($aRow['homeemail'][0]) ? (string) $aRow['homeemail'][0] : '');
					$this->Frequency = 0;
					$this->UseFriendlyName = true;
					break;

				case 'group':
					$this->Id = $aRow['un'][0];
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
	 */
	public function InitByDbRowWithType($sDbRowType, $oRow)
	{
		if ($oRow)
		{
			switch ($sDbRowType)
			{
				case 'contact':
					$this->Id = (int) $oRow->id_addr;
					$this->IsGroup = false;
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
					break;

				case 'suggest-contacts':
					$this->Id = (int) $oRow->id_addr;
					$this->IsGroup = false;
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
					break;

				case 'global':
				case 'suggest-global':
					$this->Id = (int) $oRow->id_acct;
					$this->IsGroup = false;
					$this->ReadOnly = true;
					$this->Global = true;
					$this->Name = (string) $oRow->friendly_nm;
					$this->Email = (string) $oRow->email;
					$this->Frequency = 0;
					$this->UseFriendlyName = true;
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
