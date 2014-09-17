<?php

/**
 * @package base
 */
class api_Twilio
{
	public static function NewInstance()
	{
		return new self();
	}

	public function Init($aPaths, $oHttp)
	{
		/* @var $oApiIntegrator \CApiIntegratorManager */
		$oApiIntegrator = \CApi::Manager('integrator');
		$oApiUsers = \CApi::Manager('users');

		$oAccount = $oApiIntegrator->GetLogginedDefaultAccount();

		$bDirection = $oHttp->GetRequest('Direction') === 'inbound' ? true : false;
		$sDigits = $oHttp->GetRequest('Digits');
//		$sFrom = str_replace('client:', '', $oHttp->GetRequest('From'));
		$sFrom = $oHttp->GetRequest('From');

		$sTenantId = isset($aPaths[1]) ? $aPaths[1] : null;

//		$bTwilioAllowUser = $oAccount->User->TwilioEnable;
//		$bTwilioDefaultNumber = $oAccount->User->TwilioDefaultNumber;
		$aTwilioNumbers = $oApiUsers->GetUserTwilioNumbers($sTenantId);

		$bTwilioAllowTenant = false;
		$sTwilioPhoneNumber = '';

		if (is_numeric($sTenantId))
		{
			$oApiTenants = \CApi::Manager('tenants');
			$oTenant = $oApiTenants ? $oApiTenants->GetTenantById($sTenantId) : null;

			if ($oTenant)
			{
				$bTwilioAllowTenant = $oTenant->TwilioAllow && $oTenant->TwilioAllowConfiguration; //TODO consider user enable twilio checkbox
				$sTwilioPhoneNumber = $oTenant->TwilioPhoneNumber;
			}
		}
		else
		{
			$bTwilioAllowTenant = true; //TODO if no tenant system
		}

		@header('Content-type: text/xml');

		$aResult = array('<?xml version="1.0" encoding="UTF-8"?>');
		$aResult[] = '<Response>';

		if ($bTwilioAllowTenant)
		{
			if ($bDirection) //inbound
			{
				// TODO
				if ($sDigits)
				{
					$aResult[] = '<Dial><Client>'.$sDigits.'</Client></Dial>';
//					$aResult[] = self::_getDialToDefault($oApiUsers->GetUserTwilioNumbers($sTenantId));
				}
				else
				{
					$aResult[] = '<Gather timeout="10" numDigits="4">';
//					$aResult[] = '<Say>Please enter the extension number or stay on the line to talk to an operator</Say>';
					$aResult[] = '<Say>Please enter the extension number or stay on the line</Say>';
					$aResult[] = '</Gather>';
//					$aResult[] = '<Say>You will be connected with an operator</Say>';
//					$aResult[] = '<Dial><Client></Client></Dial>';
//					$aResult[] = '<Dial></Dial>';
					$aResult[] = self::_getDialToDefault($oApiUsers->GetUserTwilioNumbers($sTenantId));
				}
			}
			else //Outbound
			{
				/* @var $oApiCapability \CApiCapabilityManager */
				$oApiCapability = \CApi::Manager('capability');

				if ($oApiCapability->IsTwilioSupported($oAccount))
				{
					$sPhoneNumber = $oHttp->GetRequest('PhoneNumber');
					if (preg_match("/^[\d\+\-\(\) ]+$/", $sPhoneNumber) && strlen($sPhoneNumber) > 10)
					{
						$aResult[] = '<Dial callerId="'.$sTwilioPhoneNumber.'">'.$sPhoneNumber.'</Dial>';
					}
					else
					{
						$aResult[] = '<Dial callerId="'.$sFrom.'"><Client>'.$sPhoneNumber.'</Client></Dial>';
					}
				}
			}
		} else
		{
			$aResult[] = '<Say>This functionality doesn\'t allowed</Say>';
		}

		$aResult[] = '</Response>';

		//$sResult = implode("\r\n", $aResult);

		\CApi::LogObject('twilio_xml_start');
		\CApi::LogObject($aPaths);
		\CApi::LogObject($_REQUEST);
//		\CApi::LogObject($bTwilioAllowUser);
//		\CApi::LogObject($bTwilioDefaultNumber);
		\CApi::LogObject($aTwilioNumbers);
		\CApi::LogObject($aResult);
		\CApi::LogObject($sFrom);
		\CApi::LogObject('twilio_xml_end');

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
		$sDial = '<Dial>';
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
