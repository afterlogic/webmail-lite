<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

/**
 * @package Contacts
 * @subpackage Helpers
 */
class CApiContactsCsvParser
{
	protected $aContainer;

	protected $aMap;

	public function __construct()
	{
		$this->aContainer = array();

		$this->aMap = array(
			'tokens' => array(
				'Title' => 'Title',
				'First Name' => 'FirstName',
				'FirstName' => 'FirstName',
				'First' => 'FirstName',
				'Middle Name' => '',
				'MiddleName' => '',
				'Last Name' => 'LastName',
				'LastName' => 'LastName',
				'Last' => 'LastName',
				'FullName' => 'FullName',
				'Full Name' => 'FullName',
				'EmailDisplayName' => 'FullName',
				'Email Display Name' => 'FullName',
				'DisplayName' => 'FullName',
				'Display Name' => 'FullName',
				'Name' => 'FullName',
				'NickName' => 'NickName',
				'Nick Name' => 'NickName',
				'Company' => 'BusinessCompany',
				'Department' => 'BusinessDepartment',
				'Job Title' => 'BusinessJobTitle',
				'Business Email' => 'BusinessEmail',
				'Business E-mail' => 'BusinessEmail',
				'Business Web' => 'BusinessWeb',
				'Business Web Page' => 'BusinessWeb',
				'Business Website' => 'BusinessWeb',
				'Business Street' => 'BusinessStreet',
				'Business City' => 'BusinessCity',
				'Business State' => 'BusinessState',
				'Business Postal Code' => 'BusinessZip',
				'Business Country' => 'BusinessCountry',
				'Business Fax' => 'BusinessFax',
				'Business Phone' => 'BusinessPhone',
				'Home Street' => 'HomeStreet',
				'Home Address' => 'HomeStreet',
				'Home City' => 'HomeCity',
				'Home State' => 'HomeState',
				'Home Postal Code' => 'HomeZip',
				'Home Country' => 'HomeCountry',
				'Home Fax' => 'HomeFax',
				'Home Phone' => 'HomePhone',
				'Mobile Phone' => 'HomeMobile',
				'Email' => 'HomeEmail',
				'E-mail' => 'HomeEmail',
				'E-mail Address' => 'HomeEmail',
				'Email Address' => 'HomeEmail',
				'EmailAddress' => 'HomeEmail',
				'Notes' => 'Notes',
				'Office Location' => 'BusinessOffice',
				'Web Page' => 'HomeWeb',
				'Web-Page' => 'HomeWeb',
				'WebPage' => 'HomeWeb',
				'Personal Website' => 'HomeWeb',
				'Other Email' => 'OtherEmail',
				'Other E-mail' => 'OtherEmail'
			),

			'tokensWithSpecialTreatmentImport' => array(
				'Date of birth' => 'bdayImportForm',
				'Birthday' => 'bdayImportForm'
			)
		);
	}

	public function Reset()
	{
		$this->aContainer = array();
	}

	/**
	 * @param array $aContainer
	 */
	public function SetContainer($aContainer)
	{
		$this->aContainer = $aContainer;
	}

	public function GetParameters()
	{
		$aResult = array();
		$aLowerTokensMap = array_change_key_case($this->aMap['tokens'], CASE_LOWER);
		$aLowerSpecialMap = array_change_key_case($this->aMap['tokensWithSpecialTreatmentImport'], CASE_LOWER);

		if ($this->aContainer && 0 < count($this->aContainer))
		{
			foreach ($this->aContainer as $sHeaderName => $sValue)
			{
				$sHeaderName = trim($sHeaderName);
				$sValue = trim($sValue);
				if (!empty($sValue))
				{
					if (!empty($aLowerTokensMap[strtolower($sHeaderName)]))
					{
						$aResult[$aLowerTokensMap[strtolower($sHeaderName)]] = $sValue;
					}
					else if (!empty($aLowerSpecialMap[strtolower($sHeaderName)]))
					{
						$sFunctionName = $aLowerSpecialMap[strtolower($sHeaderName)];
						$mFuncResult = call_user_func_array(
							array(&$this, $sFunctionName), array($sHeaderName, $sValue)
						);

						if (is_array($mFuncResult) && 0 < count($mFuncResult))
						{
							foreach ($mFuncResult as $sKey => $mResult)
							{
								if (!empty($mResult))
								{
									$aResult[$sKey] = $mResult;
								}
							}
						}
					}
				}
			}
		}

		return $aResult;
	}

	protected function bdayImportForm($sToken, $sTokenValue)
	{
		$aReturn = $aExplodeArray = array();
		if (false !== strpos($sTokenValue, '-'))
		{
			$aExplodeArray = explode('-', $sTokenValue, 3);
		}
		else if (false !== strpos($sTokenValue, '.'))
		{
			$aExplodeArray = explode('.', $sTokenValue, 3);
		}
		else if (false !== strpos($sTokenValue, '/'))
		{
			$aExplodeArray = explode('/', $sTokenValue, 3);
		}

		if (3 === count($aExplodeArray))
		{
			$iYear = $iDay = 0;
			if (4 === strlen($aExplodeArray[0]))
			{
				$iYear = (int) $aExplodeArray[0];
				$iDay = (int) $aExplodeArray[2];
			}
			else
			{
				$iYear = (int) $aExplodeArray[2];
				$iDay = (int) $aExplodeArray[0];
			}

			$iMonth = (int) $aExplodeArray[1];

			if (checkdate($iMonth, $iDay, $iYear))
			{
				$aReturn['BirthdayDay'] = $iDay;
				$aReturn['BirthdayMonth'] = $iMonth;
				$aReturn['BirthdayYear'] = $iYear;
			}
		}

		return $aReturn;
	}
}