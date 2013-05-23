<?php

/**
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @property mixed $IdContact
 * @property string $IdContactStr
 * @property int $IdUser
 * @property array $GroupsIds
 * @property string $FullName
 * @property bool $UseFriendlyName
 * @property string $ViewEmail
 * @property int $PrimaryEmail
 * @property string $Title
 * @property string $FirstName
 * @property string $LastName
 * @property string $NickName
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
 * @property string $ETag
 *
 * @package Contacts
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

			'GroupsIds'			=> array(),

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

			'ETag'				=> ''
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
		return self::STR_PREFIX.$this->IdContact;
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

			'GroupsIds'			=> array('array'),

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

			'ETag'				=> array('string(50)', 'etag')
		);
	}

	/**
	 * @param int $iUserId
	 * @param string $sData
	 */
	public function InitFromVCardStr($iUserId, $sData)
	{
		$oDavManager = CApi::Manager('dav');
		$vCard = $oDavManager ? $oDavManager->VObjectReaderRead($sData) : null;
		if ($vCard && $vCard->UID)
		{
			$this->IdUser = $iUserId;
			$this->UseFriendlyName = true;
			$this->IdContact = $vCard->UID->value . '.vcf';
			$this->IdContactStr = $this->IdContact;

			if (isset($vCard->CATEGORIES))
			{
				$this->GroupsIds = explode(',', $vCard->CATEGORIES->value);
			}

			if (isset($vCard->FN))
			{
				$this->FullName = $vCard->FN->value;
			}

			if (isset($vCard->N))
			{
				$aNames = explode(';', $vCard->N->value);

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

			if (isset($vCard->NICKNAME))
			{
				$this->NickName = $vCard->NICKNAME->value;
			}

			if (isset($vCard->NOTE))
			{
				$this->Notes = $vCard->NOTE->value;
			}

			if (isset($vCard->BDAY))
			{
				$aDateTime = explode('T', $vCard->BDAY->value);
				if (isset($aDateTime[0]))
				{
					$aDate = explode('-', $aDateTime[0]);
					$this->BirthdayYear = $aDate[0];
					$this->BirthdayMonth = $aDate[1];
					$this->BirthdayDay = $aDate[2];
				}
			}

			if (isset($vCard->ORG))
			{
				$aOrgs = explode(';', $vCard->ORG->value);
				if (!empty($aOrgs[0]))
				{
					$this->BusinessCompany = $aOrgs[0];
				}
				if (!empty($aOrgs[1]))
				{
					$this->BusinessDepartment = $aOrgs[1];
				}
			}

			if (isset($vCard->TITLE))
			{
				$this->BusinessJobTitle = $vCard->TITLE->value;
			}

			if (isset($vCard->ADR))
			{
				foreach($vCard->ADR as $oAdr)
				{
					$aAdrs = explode(';', $oAdr->value);
					if ($oAdr->offsetExists('TYPE'))
					{
						$aTypes = array();
						$oTypes = $oAdr->offsetGet('TYPE');
						foreach ($oTypes as $oType)
						{
							$aTypes[] = strtoupper($oType->value);
						}

						if (in_array('WORK', $aTypes))
						{
							$this->BusinessStreet = $aAdrs[2];
							$this->BusinessCity = $aAdrs[3];
							$this->BusinessState = $aAdrs[4];
							$this->BusinessZip = $aAdrs[5];
							$this->BusinessCountry = $aAdrs[6];
						}
						else if (in_array('HOME', $aTypes))
						{
							$this->HomeStreet = $aAdrs[2];
							$this->HomeCity = $aAdrs[3];
							$this->HomeState = $aAdrs[4];
							$this->HomeZip = $aAdrs[5];
							$this->HomeCountry = $aAdrs[6];
						}
					}
				}
			}

			if (isset($vCard->EMAIL))
			{
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

						if (in_array('WORK', $aTypes))
						{
							if (in_array('PREF', $aTypes))
							{
								$this->PrimaryEmail = EPrimaryEmailType::Business;
							}
							$this->BusinessEmail = $oEmail->value;
						}
						else if (in_array('HOME', $aTypes))
						{
							if (in_array('PREF', $aTypes))
							{
								$this->PrimaryEmail = EPrimaryEmailType::Home;
							}
							$this->HomeEmail = $oEmail->value;
						}
						else
						{
							if (isset($vCard->{'X-ABLabel'}))
							{
								$aABLabels = $vCard->{'X-ABLabel'};
								foreach ($aABLabels as $oABLabel)
								{
									if ($oEmail->group == $oABLabel->group &&
											$oABLabel->value == '_$!<Other>!$_')
									{
										$this->OtherEmail = $oEmail->value;
										if (in_array('PREF', $aTypes))
										{
											$this->PrimaryEmail = EPrimaryEmailType::Other;
										}
										break;
									}
								}
							}
						}
					}
				}
			}

			if (isset($vCard->URL))
			{
				foreach($vCard->URL as $oUrl)
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
							$this->HomeWeb = $oUrl->value;
						}
						else if (in_array('WORK', $aTypes))
						{
							$this->BusinessWeb = $oUrl->value;
						}
					}
				}
			}

			if (isset($vCard->TEL))
			{
				foreach($vCard->TEL as $oTel)
				{
					if ($oTel->offsetExists('TYPE'))
					{
						$aTypes = array();
						$oTypes = $oTel->offsetGet('TYPE');
						foreach ($oTypes as $oType)
						{
							$aTypes[] = strtoupper($oType->value);
						}

						if (in_array('FAX', $aTypes))
						{
							if (in_array('HOME', $aTypes))
							{
								$this->HomeFax = $oTel->value;
							}
							if (in_array('WORK', $aTypes))
							{
								$this->BusinessFax = $oTel->value;
							}
						}
						else
						{
							if (in_array('CELL', $aTypes))
							{
								$this->HomeMobile = $oTel->value;
							}
							else if (in_array('HOME', $aTypes))
							{
								$this->HomePhone = $oTel->value;
							}
							else if (in_array('WORK', $aTypes))
							{
								$this->BusinessPhone = $oTel->value;
							}
						}
					}
				}
			}

			if (isset($vCard->{'X-AFTERLOGIC-OFFICE'}))
			{
				$this->BusinessOffice = $vCard->{'X-AFTERLOGIC-OFFICE'}->value;
			}

			if (isset($vCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'}))
			{
				$this->UseFriendlyName = $vCard->{'X-AFTERLOGIC-USE-FRIENDLY-NAME'}->value == '1'
						? true : false;
			}
		}
	}
}
