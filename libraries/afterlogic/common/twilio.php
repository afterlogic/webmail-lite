<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package base
 */
class api_Twilio
{
	public static function NewInstance()
	{
		return new self();
	}

	public function getTwiML($aPaths, $oHttp)
	{
		$oApiCapability = \CApi::Manager('capability');
		$oApiUsers = \CApi::Manager('users');
		$oApiTenants = \CApi::Manager('tenants');

		$sTenantId = isset($aPaths[1]) ? $aPaths[1] : null;
		$oTenant = null;
		if ($oApiTenants)
		{
			$oTenant = $sTenantId ? $oApiTenants->GetTenantById($sTenantId) : $oApiTenants->GetDefaultGlobalTenant();
		}

		$sTwilioPhoneNumber = $oTenant->TwilioPhoneNumber;

		$sDigits = $oHttp->GetRequest('Digits');
		//$sFrom = str_replace('client:', '', $oHttp->GetRequest('From'));
		$sFrom = $oHttp->GetRequest('From');
		$sTo = $oHttp->GetRequest('PhoneNumber');

		$aTwilioNumbers = $oApiUsers->GetUserTwilioNumbers($sTenantId);

		@header('Content-type: text/xml');
		$aResult = array('<?xml version="1.0" encoding="UTF-8"?>');
		$aResult[] = '<Response>';

		if ($oHttp->GetRequest('CallSid'))
		{
			if ($oHttp->GetRequest('AfterlogicCall')) //internal call from webmail first occurrence
			{
				if (preg_match("/^[\d\+\-\(\) ]+$/", $sTo) && strlen($sTo) > 0 && strlen($sTo) < 10) //to internal number
				{
					$aResult[] = '<Dial callerId="'.$sFrom.'"><Client>'.$sTo.'</Client></Dial>';
				}
				else if (strlen($sTo) > 10) //to external number
				{
					$aResult[] = '<Dial callerId="'.$sFrom.'">'.$sTo.'</Dial>';
				}

				//@setcookie('twilioCall['.$oHttp->GetRequest('CallSid').']', $sTo, time()+60);
				@setcookie('PhoneNumber', $sTo);
			}
			else //call from other systems or internal call second occurrence
			{
				if ($oTenant->TwilioAccountSID === $oHttp->GetRequest('AccountSid') && $oTenant->TwilioAppSID === $oHttp->GetRequest('ApplicationSid')) //internal call second occurrence
				{
					/*$sTo = isset($_COOKIE['twilioCall'][$oHttp->GetRequest('CallSid')]) ? $_COOKIE['twilioCall'][$oHttp->GetRequest('CallSid')] : '';
					@setcookie ('twilioCall['.$oHttp->GetRequest('CallSid').']', '', time() - 1);*/
					if (strlen($sTo) > 0 && strlen($sTo) < 10) //to internal number
					{
						$aResult[] = '<Dial callerId="'.$sFrom.'"><Client>'.$sTo.'</Client></Dial>';
					}
					else if (strlen($sTo) > 10) //to external number
					{
						$aResult[] = '<Dial callerId="'.$sTwilioPhoneNumber.'">'.$sTo.'</Dial>'; //in there caller id must be full with country code number!
					}
				}
				else //call from other systems
				{
					if ($sDigits) //second occurrence
					{
						$aResult[] = '<Dial callerId="'.$sDigits.'"><Client>'.$sDigits.'</Client></Dial>';
					}
					else //first occurrence
					{
						$aResult[] = '<Gather timeout="5" numDigits="4">';
						$aResult[] = '<Say>Please enter the extension number or stay on the line</Say>';
						$aResult[] = '</Gather>';
						//$aResult[] = '<Say>You will be connected with an operator</Say>';
						$aResult[] = self::_getDialToDefault($oApiUsers->GetUserTwilioNumbers($sTenantId));
					}
				}
			}
		}
		else
		{
			$aResult[] = '<Say>This functionality doesn\'t allowed</Say>';
		}

		$aResult[] = '</Response>';

		\CApi::LogObject('twilio_xml_start');
		\CApi::LogObject($aPaths);
		\CApi::LogObject($_REQUEST);
		\CApi::LogObject($aTwilioNumbers);
		\CApi::LogObject($aResult);
		\CApi::LogObject('twilio_From-'.$sFrom);
		\CApi::LogObject('twilio_TwilioPhoneNumber-'.$oTenant->TwilioPhoneNumber);
		\CApi::LogObject('twilio_TwilioAllow-'.$oTenant->TwilioAllow);
		\CApi::LogObject('twilio_xml_end');

		//return implode("\r\n", $aResult);
		return implode('', $aResult);
	}

	public function getCallSimpleStatus($sStatus, $sUserDirection)
	{
		$sSimpleStatus = '';

		if (($sStatus === 'busy' || $sStatus === 'completed') && $sUserDirection === 'incoming')
		{
			$sSimpleStatus = 'incoming';
		}
		else if (($sStatus === 'busy' || $sStatus === 'completed' || $sStatus === 'failed' || $sStatus === 'no-answer') && $sUserDirection === 'outgoing')
		{
			$sSimpleStatus = 'outgoing';
		}
		else if ($sStatus === 'no-answer' && $sUserDirection === 'incoming')
		{
			$sSimpleStatus = 'missed';
		}

		return $sSimpleStatus;
	}

	private static function _getDialToDefault($aPhones)
	{
		// the number of <Client> may not exceed 10
		$sDial = '<Dial>';
		$sDial .= '<Client>default</Client>';
		foreach ($aPhones as $iKey => $sValue) {
			if($aPhones[$iKey])
			{
				$sDial .= '<Client>'.$iKey.'</Client>';
			}
		}
		$sDial .= '</Dial>';

		return $sDial;
	}
}
