<?php

/**
 * @package base
 */
class api_Twilio
{
	public function Init($aPaths, $oHttp)
	{

		\CApi::Log('twilio_xml');

		\CApi::LogObject($aPaths);
		\CApi::LogObject($_REQUEST);
		\CApi::LogObject($oHttp->GetRequest('Direction'));

		$bDirection = $oHttp->GetRequest('Direction') === 'inbound' ? true : false;
		$sDigits = $oHttp->GetRequest('Digits');

		$sTenantId = isset($aPaths[1]) ? $aPaths[1] : null;

		$bAllowTwilio = false;
		$sTwilioPhoneNumber = '';

		if (is_numeric($sTenantId))
		{
			$oApiTenants = \CApi::Manager('tenants');
			$oTenant = $oApiTenants ? $oApiTenants->GetTenantById($sTenantId) : null;

			if ($oTenant)
			{
				$bAllowTwilio = $oTenant->TwilioAllow && $oTenant->TwilioAllowConfiguration;
				$sTwilioPhoneNumber = $oTenant->TwilioPhoneNumber;
			}
		}
		else
		{
			// TODO
			$bAllowTwilio = true;
		}

		@header('Content-type: text/xml');

		$aResult = array('<?xml version="1.0" encoding="UTF-8"?>');
		$aResult[] = '<Response>';

		\CApi::Log($bAllowTwilio);
		\CApi::Log($bDirection);

		if ($bAllowTwilio)
		{
			if ($bDirection) // inbound
			{
				if (!$sDigits)
				{
					$aResult[] = '<Gather timeout="10" numDigits="4">';
					$aResult[] = '<Say>Please enter the extension number or stay on the line to talk to an operator</Say>';
					$aResult[] = '</Gather>';
					$aResult[] = '<Say>You will be connected with an operator</Say>';
					$aResult[] = '<Dial><Client>TwilioAftId_'.$sTenantId.'_0</Client></Dial>';
				}
				else
				{
//							$aResult[] = '<Dial><Client>TwilioAftId_'.$sTenantId.'_'.$sDigits.'</Client></Dial>';
					$aResult[] = '<Dial><Client>TwilioAftId_'.$sDigits.'</Client></Dial>';
				}
			}
			else //Outbound
			{
				/* @var $oApiCapability \CApiCapabilityManager */
				$oApiCapability = \CApi::Manager('capability');

				/* @var $oApiIntegrator \CApiIntegratorManager */
				$oApiIntegrator = \CApi::Manager('integrator');

				$oAccount = $oApiIntegrator->GetLogginedDefaultAccount();
				\CApi::LogObject($oAccount);


				\CApi::Log($oApiCapability->IsTwilioSupported($oAccount));
				if ($oApiCapability->IsTwilioSupported($oAccount))
//						{
					$sPhoneNumber = $oHttp->GetRequest('PhoneNumber');
//						$aResult[] = '<Say>Call to phone number '.$sPhoneNumber.'</Say>';
//						$aResult[] = '<Dial callerId="17064030887">'.$sPhoneNumber.'</Dial>';
//						$aResult[] = '<Dial callerId="17064030887"><Client>TwilioAftId_'.$sPhoneNumber.'</Client></Dial>';
//						}
				if (preg_match("/^[\d\+\-\(\) ]+$/", $sPhoneNumber) && strlen($sPhoneNumber) > 10) {
					$aResult[] = '<Dial callerId="'.$sTwilioPhoneNumber.'">'.$sPhoneNumber.'</Dial>';
				} else {
					$aResult[] = '<Dial callerId="'.$sPhoneNumber.'"><Client>TwilioAftId_'.$sPhoneNumber.'</Client></Dial>';
				}
			}
		} else {
			$aResult[] = '<Say>This functionality doesn\'t allowed</Say>';
		}

		$aResult[] = '</Response>';

		$sResult = implode("\r\n", $aResult);

		\CApi::Log($aResult);
		\CApi::Log('twilio_end_xml');
	}
}
