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
class CApiContactsCsvFormatter
{
	const CRLF = "\r\n";

	/**
	 * @var array
	 */
	protected $aMap;

	/**
	 * @var string
	 */
	protected $sValue;

	/**
	 * @var string
	 */
	protected $sDelimiter;

	/**
	 * @var bool
	 */
	protected $bIsHeadersInit;

	/**
	 * @var mixed
	 */
	protected $oContainer;

	public function __construct()
	{
		$this->sDelimiter = ',';

		$this->sValue = '';
		$this->oContainer = null;
		$this->bIsHeadersInit = false;

		$this->aMap = array(
			'tokens' => array(
				'Title' => 'Title',
				'First Name' => 'FirstName',
				'Middle Name' => '',
				'Last Name' => 'LastName',
				'Nick Name' => 'NickName',
				'Display Name' => 'FullName',
				'Company' => 'BusinessCompany',
				'Department' => 'BusinessDepartment',
				'Job Title' => 'BusinessJobTitle',
				'Business Email' => 'BusinessEmail',
				'Business Street' => 'BusinessStreet',
				'Business City' => 'BusinessCity',
				'Business State' => 'BusinessState',
				'Business Postal Code' => 'BusinessZip',
				'Business Country' => 'BusinessCountry',
				'Home Street' => 'HomeStreet',
				'Home City' => 'HomeCity',
				'Home State' => 'HomeState',
				'Home Postal Code' => 'HomeZip',
				'Home Country' => 'HomeCountry',
				'Business Fax' => 'BusinessFax',
				'Business Phone' => 'BusinessPhone',
				'Home Fax' => 'HomeFax',
				'Home Phone' => 'HomePhone',
				'Mobile Phone' => 'HomeMobile',
				'E-mail Address' => 'HomeEmail',
				'Notes' => 'Notes',
				'Other Email' => 'OtherEmail',
				'Office Location' => 'BusinessOffice',
				'Web Page' => 'HomeWeb'
			),

			'tokensWithSpecialTreatment' => array(
				'Birthday' => array('bdayForm', 'BirthdayDay', 'BirthdayMonth', 'BirthdayYear'),
			)
		);
	}

	public function Clear()
	{
		$this->sValue = '';
		$this->oContainer = null;
		$this->bIsHeadersInit = false;
	}

	/**
	 * @param string $sDelimiter
	 */
	public function SetDelimiter($sDelimiter)
	{
		$this->sDelimiter = $sDelimiter;
	}

	/**
	 * @return bool
	 */
	public function Form()
	{
		$this->sValue = '';
		$this->formHeader();
		$this->formTokens();
		return true;
	}

	/**
	 * @return bool
	 */
	protected function formHeader()
	{
		if (!$this->bIsHeadersInit && isset($this->aMap['tokens']) && is_array($this->aMap['tokens']))
		{
			$aList = array();
			foreach ($this->aMap['tokens'] as $sToken => $sPropertyName)
			{
				$aList[] = $this->escapeValue($sToken, true);
			}

			foreach ($this->aMap['tokensWithSpecialTreatment'] as $sToken => $mProperties)
			{
				$aList[] = $this->escapeValue($sToken, true);
			}

			$this->sValue .= implode($this->sDelimiter, $aList);
			$this->sValue .= CApiContactsCsvFormatter::CRLF;

			$this->bIsHeadersInit = true;
		}
	}

	/**
	 * @return bool
	 */
	protected function formTokens()
	{
		if ($this->bIsHeadersInit && isset($this->aMap['tokens']) && is_array($this->aMap['tokens']))
		{
			$aList = array();
			foreach ($this->aMap['tokens'] as $sToken => $sPropertyName)
			{
				if (!empty($sPropertyName))
				{
					$aList[] = $this->escapeValue($this->oContainer->{$sPropertyName}, true);
				}
				else
				{
					$aList[] = $this->escapeValue('', true);
				}
			}

			foreach ($this->aMap['tokensWithSpecialTreatment'] as $sToken => $aParams)
			{
				$sFunctionName = $aParams[0];
				$aParams[0] = $sToken;

				$mValue = (string) @call_user_func_array(array(&$this, $sFunctionName), $aParams);
				$aList[] = $this->escapeValue($mValue, true);
			}

			$this->sValue .= implode($this->sDelimiter, $aList);
			$this->sValue .= CApiContactsCsvFormatter::CRLF;

			$this->bIsHeadersInit = true;
		}
	}

	/**
	 * @param mixed $oContainer
	 */
	public function SetContainer($oContainer)
	{
		$this->oContainer = $oContainer;
	}

	/**
	 * @return string
	 */
	public function GetValue()
	{
		return $this->sValue;
	}

	/**
	 * @param string $sToken
	 * @param string $sDayFieldName
	 * @param string $sMonthFieldName
	 * @param string $sYearFieldName
	 * @return string
	 */
	protected function bdayForm($sToken, $sDayFieldName, $sMonthFieldName, $sYearFieldName)
	{
		$iMonth = $iDay = $iYear = 0;
		if ($this->oContainer)
		{
			$iDay = $this->oContainer->{$sDayFieldName};
			$iMonth = $this->oContainer->{$sMonthFieldName};
			$iYear = $this->oContainer->{$sYearFieldName};
		}

		return checkdate($iMonth, $iDay, $iYear) ? $iDay.'/'.$iMonth.'/'.$iYear : '';
	}

	/**
	 * @param string $sValue
	 * @param bool $bAddQuotation = false
	 * @return string
	 */
	protected function escapeValue($sValue, $bAddQuotation = false)
	{
		$sValue = str_replace('"', '""', $sValue);
		return $bAddQuotation ?
			(empty($sValue) ? '' : '"'.$sValue.'"') : $sValue;
	}
}
