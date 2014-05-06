<?php

/**
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @property mixed $IdContact
 * @property string $IdContactStr
 * @property int $IdUser
 * @property int $IdDomain
 * @property int $IdTenant
 * @property array $GroupsIds
 * @property int $Type
 * @property string $IdTypeLink
 * @property string $FullName
 * @property bool $UseFriendlyName
 * @property string $ViewEmail
 * @property int $PrimaryEmail
 * @property string $Title
 * @property string $FirstName
 * @property string $LastName
 * @property string $NickName
 * @property string $Skype
 * @property string $Facebook
 * @property string $HomeEmail
 * @property string $HomeStreet
 * @property string $HomeCity
 * @property string $HomeState
 * @property string $HomeZip
 * @property string $HomeCountry
 * @property string $HomePhone
 * @property string $HomeFax
 * @property string $HomeMobile
 * @property string $HomeWeb
 * @property string $BusinessEmail
 * @property string $BusinessCompany
 * @property string $BusinessStreet
 * @property string $BusinessCity
 * @property string $BusinessState
 * @property string $BusinessZip
 * @property string $BusinessCountry
 * @property string $BusinessJobTitle
 * @property string $BusinessDepartment
 * @property string $BusinessOffice
 * @property string $BusinessPhone
 * @property string $BusinessMobile
 * @property string $BusinessFax
 * @property string $BusinessWeb
 * @property string $OtherEmail
 * @property string $Notes
 * @property int $BirthdayDay
 * @property int $BirthdayMonth
 * @property int $BirthdayYear
 * @property bool $ReadOnly
 * @property bool $Global
 * @property bool $ItsMe
 * @property string $ETag
 * @property bool $SharedToAll
 * @property bool $HideInGAB
 *
 * @package Maincontacts
 * @subpackage Classes
 */
class CContact extends api_AContainer
{
	const STR_PREFIX = '040000008200E00074C5B7101A82E008';

	/**
	 * @var bool
	 */
	public $__LOCK_DATE_MODIFIED__;

	/**
	 * @var bool
	 */
	public $__SKIP_VALIDATE__;

	public function __construct()
	{
		parent::__construct(get_class($this), 'IdContact');

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults(array(
			'IdContact'		=> '',
			'IdContactStr'	=> '',
			'IdUser'		=> 0,
			'IdDomain'		=> 0,
			'IdTenant'		=> 0,

			'GroupsIds'		=> array(),

			'Type'			=> EContactType::Personal,
			'IdTypeLink'	=> '',

			'FullName'			=> '',
			'UseFriendlyName'	=> true,
			'ViewEmail'			=> '',
			'PrimaryEmail'		=> CApi::GetConf('contacts.default-primary-email', EPrimaryEmailType::Home),

			'DateCreated'		=> time(),
			'DateModified'		=> time(),

			'Title'			=> '',
			'FirstName'		=> '',
			'LastName'		=> '',
			'NickName'		=> '',
			'Skype'			=> '',
			'Facebook'		=> '',

			'HomeEmail'		=> '',
			'HomeStreet'	=> '',
			'HomeCity'		=> '',
			'HomeState'		=> '',
			'HomeZip'		=> '',
			'HomeCountry'	=> '',
			'HomePhone'		=> '',
			'HomeFax'		=> '',
			'HomeMobile'	=> '',
			'HomeWeb'		=> '',

			'BusinessEmail'		=> '',
			'BusinessCompany'	=> '',
			'BusinessStreet'	=> '',
			'BusinessCity'		=> '',
			'BusinessState'		=> '',
			'BusinessZip'		=> '',
			'BusinessCountry'	=> '',
			'BusinessJobTitle'	=> '',
			'BusinessDepartment'=> '',
			'BusinessOffice'	=> '',
			'BusinessPhone'		=> '',
			'BusinessMobile'	=> '',
			'BusinessFax'		=> '',
			'BusinessWeb'		=> '',

			'OtherEmail'		=> '',
			'Notes'				=> '',

			'BirthdayDay'		=> 0,
			'BirthdayMonth'		=> 0,
			'BirthdayYear'		=> 0,

			'ReadOnly'			=> false,
			'Global'			=> false,
			'ItsMe'				=> false,

			'ETag'				=> '',
			'SharedToAll'		=> false,
			'HideInGAB'			=> false
		));

		$this->__LOCK_DATE_MODIFIED__ = false;
		$this->__SKIP_VALIDATE__ = false;

		CApi::Plugin()->RunHook('api-contact-construct', array(&$this));
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 * @return void
	 */
	public function __set($sKey, $mValue)
	{
		if (is_string($mValue))
		{
	        $mValue = str_replace(array("\r","\n\n"), array('\n','\n'), $mValue);
		}

		parent::__set($sKey, $mValue);
	}

	/**
	 * @return string
	 */
	public function GenerateStrId()
	{
		return \Sabre\DAV\UUIDUtil::getUUID().'.vcf';
	}

	/**
	 * @param stdClass $oRow
	 */
	public function InitByDbRow($oRow)
	{
		parent::InitByDbRow($oRow);

		if (!$this->ReadOnly && (EContactType::Global_ === $this->Type || EContactType::GlobalAccounts === $this->Type ||
			EContactType::GlobalMailingList === $this->Type))
		{
			$this->ReadOnly = true;
		}
		
		if (EContactType::GlobalAccounts === $this->Type || EContactType::GlobalMailingList === $this->Type)
		{
			$this->Global = true;
		}
	}

	/**
	 * @return bool
	 */
	public function InitBeforeChange()
	{
		parent::InitBeforeChange();

		if (0 === strlen($this->IdContactStr) &&
			((is_int($this->IdContact) && 0 < $this->IdContact) ||
			(is_string($this->IdContact) && 0 < strlen($this->IdContact)))
		)
		{
			$this->IdContactStr = $this->GenerateStrId();
		}

		if (!$this->__LOCK_DATE_MODIFIED__)
		{
			$this->DateModified = time();
		}

		switch ((int) $this->PrimaryEmail)
		{
			//ReadOnly
			case EPrimaryEmailType::Home:
				$this->ViewEmail = (string) $this->HomeEmail;
				break;
			case EPrimaryEmailType::Business:
				$this->ViewEmail = (string) $this->BusinessEmail;
				break;
			case EPrimaryEmailType::Other:
				$this->ViewEmail = (string) $this->OtherEmail;
				break;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		if (!$this->__SKIP_VALIDATE__)
		{
			switch (true)
			{
				case
					api_Validate::IsEmpty($this->FullName) &&
					api_Validate::IsEmpty($this->HomeEmail) &&
					api_Validate::IsEmpty($this->BusinessEmail) &&
					api_Validate::IsEmpty($this->OtherEmail):

					throw new CApiValidationException(Errs::Validation_FieldIsEmpty_OutInfo);
			}
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function GetMap()
	{
		return self::GetStaticMap();
	}

	/**
	 * @return array
	 */
	public static function GetStaticMap()
	{
		return array(
			'IdContact'		=> array('string', 'id_addr', false, false),
			'IdContactStr'	=> array('string(255)', 'str_id', false),
			'IdUser'		=> array('int', 'id_user'),
			'IdDomain'		=> array('int', 'id_domain', true, false),
			'IdTenant'		=> array('int', 'id_tenant', true),

			'GroupsIds'			=> array('array'),

			'Type'			=> array('int', 'type'),
			'IdTypeLink'	=> array('string(100)', 'type_id'),

			'ViewEmail'			=> array('string(255)', 'view_email'),
			'PrimaryEmail'		=> array('int', 'primary_email'),

			'DateCreated'		=> array('datetime', 'date_created', true, false),
			'DateModified'		=> array('datetime', 'date_modified'),

			'UseFriendlyName'	=> array('bool', 'use_friendly_nm'),

			'Title'			=> array('string'),
			'FullName'		=> array('string(255)', 'fullname'),
			'FirstName'		=> array('string(100)', 'firstname'),
			'LastName'		=> array('string(100)', 'surname'),
			'NickName'		=> array('string(100)', 'nickname'),
			'Skype'			=> array('string(100)', 'skype'),
			'Facebook'		=> array('string(255)', 'facebook'),

			'HomeEmail'		=> array('string(255)', 'h_email'),
			'HomeStreet'	=> array('string(255)', 'h_street'),
			'HomeCity'		=> array('string(200)', 'h_city'),
			'HomeState'		=> array('string(200)', 'h_state'),
			'HomeZip'		=> array('string(10)', 'h_zip'),
			'HomeCountry'	=> array('string(200)', 'h_country'),
			'HomePhone'		=> array('string(50)', 'h_phone'),
			'HomeFax'		=> array('string(50)', 'h_fax'),
			'HomeMobile'	=> array('string(50)', 'h_mobile'),
			'HomeWeb'		=> array('string(255)', 'h_web'),

			'BusinessEmail'		=> array('string(255)', 'b_email'),
			'BusinessCompany'	=> array('string(200)', 'b_company'),
			'BusinessStreet'	=> array('string(255)', 'b_street'),
			'BusinessCity'		=> array('string(200)', 'b_city'),
			'BusinessState'		=> array('string(200)', 'b_state'),
			'BusinessZip'		=> array('string(10)', 'b_zip'),
			'BusinessCountry'	=> array('string(200)', 'b_country'),
			'BusinessJobTitle'	=> array('string(100)', 'b_job_title'),
			'BusinessDepartment'=> array('string(200)', 'b_department'),
			'BusinessOffice'	=> array('string(200)', 'b_office'),
			'BusinessPhone'		=> array('string(50)', 'b_phone'),
			'BusinessMobile'	=> array('string'),
			'BusinessFax'		=> array('string(50)', 'b_fax'),
			'BusinessWeb'		=> array('string(255)', 'b_web'),

			'OtherEmail'		=> array('string(255)', 'other_email'),
			'Notes'				=> array('string(255)', 'notes'),

			'BirthdayDay'		=> array('int', 'birthday_day'),
			'BirthdayMonth'		=> array('int', 'birthday_month'),
			'BirthdayYear'		=> array('int', 'birthday_year'),

			'ReadOnly'			=> array('bool'),
			'Global'			=> array('bool'),
			'ItsMe'				=> array('bool'),

			'ETag'				=> array('string(50)', 'etag'),
			
			'SharedToAll'		=> array('bool', 'shared_to_all'),
			'HideInGAB'			=> array('bool', 'hide_in_gab')
		);
	}

	private function compareProperty($oContact, $sName)
	{
		if ($this->{$sName} !== $oContact->{$sName})
		{
			$this->{$sName} = $oContact->{$sName};
			return false;
		}

		return true;
	}

	/**
	 * @param CContact $oContact
	 * @retur bool
	 */
	public function CompareAndComputedByNewGlobalContact($oContact)
	{
		$iChanged = 1;
		$iChanged &= $this->compareProperty($oContact, 'Title');
		$iChanged &= $this->compareProperty($oContact, 'FullName');
		$iChanged &= $this->compareProperty($oContact, 'FirstName');
		$iChanged &= $this->compareProperty($oContact, 'LastName');
		$iChanged &= $this->compareProperty($oContact, 'NickName');

		$iChanged &= $this->compareProperty($oContact, 'PrimaryEmail');

		$iChanged &= $this->compareProperty($oContact, 'HomeEmail');
		$iChanged &= $this->compareProperty($oContact, 'HomeStreet');
		$iChanged &= $this->compareProperty($oContact, 'HomeCity');
		$iChanged &= $this->compareProperty($oContact, 'HomeState');
		$iChanged &= $this->compareProperty($oContact, 'HomeZip');
		$iChanged &= $this->compareProperty($oContact, 'HomeCountry');
		$iChanged &= $this->compareProperty($oContact, 'HomePhone');
		$iChanged &= $this->compareProperty($oContact, 'HomeFax');
		$iChanged &= $this->compareProperty($oContact, 'HomeMobile');
		$iChanged &= $this->compareProperty($oContact, 'HomeWeb');

		$iChanged &= $this->compareProperty($oContact, 'BusinessEmail');
		$iChanged &= $this->compareProperty($oContact, 'BusinessCompany');
		$iChanged &= $this->compareProperty($oContact, 'BusinessStreet');
		$iChanged &= $this->compareProperty($oContact, 'BusinessCity');
		$iChanged &= $this->compareProperty($oContact, 'BusinessState');
		$iChanged &= $this->compareProperty($oContact, 'BusinessZip');
		$iChanged &= $this->compareProperty($oContact, 'BusinessCountry');
		$iChanged &= $this->compareProperty($oContact, 'BusinessJobTitle');
		$iChanged &= $this->compareProperty($oContact, 'BusinessDepartment');
		$iChanged &= $this->compareProperty($oContact, 'BusinessOffice');
		$iChanged &= $this->compareProperty($oContact, 'BusinessPhone');
		$iChanged &= $this->compareProperty($oContact, 'BusinessMobile');
		$iChanged &= $this->compareProperty($oContact, 'BusinessFax');
		$iChanged &= $this->compareProperty($oContact, 'BusinessWeb');

		$iChanged &= $this->compareProperty($oContact, 'OtherEmail');
		$iChanged &= $this->compareProperty($oContact, 'Notes');
		
		$iChanged &= $this->compareProperty($oContact, 'Skype');
		$iChanged &= $this->compareProperty($oContact, 'Facebook');

		$iChanged &= $this->compareProperty($oContact, 'BirthdayDay');
		$iChanged &= $this->compareProperty($oContact, 'BirthdayMonth');
		$iChanged &= $this->compareProperty($oContact, 'BirthdayYear');

		$iChanged &= $this->compareProperty($oContact, 'HideInGAB');

		return !$iChanged;
	}
	
	/**
	 * @param int $iUserId
	 * @param string $sData
	 */
	public function InitFromVCardStr($iUserId, $sData, $sFileName = null)
	{
		$oDavManager = CApi::Manager('dav');
		$oVCard = $oDavManager ? $oDavManager->VObjectReaderRead($sData) : null;
		if ($oVCard && $oVCard->UID)
		{
			$sUid = ($sFileName !== null) ? $sFileName : (string)$oVCard->UID . '.vcf';
			
			$this->IdUser = $iUserId;
			$this->UseFriendlyName = true;
			$this->IdContact = $sUid;
			$this->IdContactStr = $this->IdContact;

			$aResultGroupsIds = $this->GroupsIds;
			if (isset($oVCard->CATEGORIES))
			{
				$aGroupsIds = $oVCard->CATEGORIES->getParts();
				foreach($aGroupsIds as $sGroupsId)
				{
					if (!empty($sGroupsId))
					{
						$aResultGroupsIds[] = (string) $sGroupsId;
					}
				}
			}
			$this->GroupsIds = $aResultGroupsIds;

			if (isset($oVCard->FN))
			{
				$this->FullName = (string)$oVCard->FN;
			}

			if (isset($oVCard->N))
			{
				$aNames = $oVCard->N->getParts();

				if (!empty($aNames[0]))
				{
					$this->LastName = $aNames[0];
				}
				if (!empty($aNames[1]))
				{
					$this->FirstName = $aNames[1];
				}
				if (!empty($aNames[3]))
				{
					$this->Title = $aNames[3];
				}
			}

			if (isset($oVCard->NICKNAME))
			{
				$this->NickName = (string) $oVCard->NICKNAME;
			}

			if (isset($oVCard->NOTE))
			{
				$this->Notes = (string) $oVCard->NOTE;
			}

			if (isset($oVCard->BDAY))
			{
				$aDateTime = explode('T', (string)$oVCard->BDAY);
				if (isset($aDateTime[0]))
				{
					$aDate = explode('-', $aDateTime[0]);
					$this->BirthdayYear = $aDate[0];
					$this->BirthdayMonth = $aDate[1];
					$this->BirthdayDay = $aDate[2];
				}
			}

			if (isset($oVCard->ORG))
			{
				$aOrgs = $oVCard->ORG->getParts();
				if (!empty($aOrgs[0]))
				{
					$this->BusinessCompany = $aOrgs[0];
				}
				if (!empty($aOrgs[1]))
				{
					$this->BusinessDepartment = $aOrgs[1];
				}
			}

			if (isset($oVCard->TITLE))
			{
				$this->BusinessJobTitle = (string)$oVCard->TITLE;
			}

			if (isset($oVCard->ADR))
			{
				foreach($oVCard->ADR as $oAdr)
				{
					$aAdrs = $oAdr->getParts();
					if ($oTypes = $oAdr['TYPE'])
					{
						if ($oTypes->has('WORK'))
						{
							$this->BusinessStreet = isset($aAdrs[2]) ? $aAdrs[2] : '';
							$this->BusinessCity = isset($aAdrs[3]) ? $aAdrs[3] : '';
							$this->BusinessState = isset($aAdrs[4]) ? $aAdrs[4] : '';
							$this->BusinessZip = isset($aAdrs[5]) ? $aAdrs[5] : '';
							$this->BusinessCountry = isset($aAdrs[6]) ? $aAdrs[6] : '';
						}
						if ($oTypes->has('HOME'))
						{
							$this->HomeStreet = isset($aAdrs[2]) ? $aAdrs[2] : '';
							$this->HomeCity = isset($aAdrs[3]) ? $aAdrs[3] : '';
							$this->HomeState = isset($aAdrs[4]) ? $aAdrs[4] : '';
							$this->HomeZip = isset($aAdrs[5]) ? $aAdrs[5] : '';
							$this->HomeCountry = isset($aAdrs[6]) ? $aAdrs[6] : '';
						}
					}
				}
			}

			if (isset($oVCard->EMAIL))
			{
				$bHasOtherEmail = false;
				foreach($oVCard->EMAIL as $oEmail)
				{
					if ($oType = $oEmail['TYPE'])
					{
						if ($oType->has('WORK'))
						{
							$this->BusinessEmail = (string)$oEmail;
							if ($oType->has('PREF'))
							{
								$this->PrimaryEmail = EPrimaryEmailType::Business;
							}
						}
						else if ($oType->has('HOME'))
						{
							$this->HomeEmail = (string)$oEmail;
							if ($oType->has('PREF'))
							{
								$this->PrimaryEmail = EPrimaryEmailType::Home;
							}
						}
						else if (!$bHasOtherEmail)
						{
							if (isset($oVCard->{'X-ABLabel'}))
							{
								$aABLabels = $oVCard->{'X-ABLabel'};
								foreach ($aABLabels as $oABLabel)
								{
									if ($oEmail->group == $oABLabel->group && (string)$oABLabel == '_$!<Other>!$_')
									{
										$bHasOtherEmail = true;
										break;
									}
								}
							}
							else
							{
								$bHasOtherEmail = true;
							}
							
							$this->OtherEmail = (string)$oEmail;
							if ($oType->has('PREF'))
							{
								$this->PrimaryEmail = EPrimaryEmailType::Other;
							}
						}
					}
				}
			}

			if (isset($oVCard->URL))
			{
				foreach($oVCard->URL as $oUrl)
				{
					if ($oTypes = $oUrl['TYPE'])
					{
						if ($oTypes->has('HOME'))
						{
							$this->HomeWeb = (string)$oUrl;
						}
						else if ($oTypes->has('WORK'))
						{
							$this->BusinessWeb = (string)$oUrl;
						}
					}
				}
			}

			if (isset($oVCard->TEL))
			{
				foreach($oVCard->TEL as $oTel)
				{
					if ($oTypes = $oTel['TYPE'])
					{
						if ($oTypes->has('FAX'))
						{
							if ($oTypes->has('HOME'))
							{
								$this->HomeFax = (string)$oTel;
							}
							if ($oTypes->has('WORK'))
							{
								$this->BusinessFax = (string)$oTel;
							}
						}
						else
						{
							if ($oTypes->has('CELL'))
							{
								$this->HomeMobile = (string)$oTel;
							}
							else if ($oTypes->has('HOME'))
							{
								$this->HomePhone = (string)$oTel;
							}
							else if ($oTypes->has('WORK'))
							{
								$this->BusinessPhone = (string)$oTel;
							}
						}
					}
				}
			}

			if (isset($oVCard->{'X-AFTERLOGIC-OFFICE'}))
			{
				$this->BusinessOffice = (string)$oVCard->{'X-AFTERLOGIC-OFFICE'};
			}

			if (isset($oVCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'}))
			{
				$this->UseFriendlyName = '1' === (string)$oVCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'};
			}
		}
	}
}
