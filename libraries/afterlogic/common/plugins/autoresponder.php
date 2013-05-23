<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Api
 */
abstract class AApiAutoResponderPlugin extends AApiPlugin
{
	/**
	 * @param string $sVersion
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct($sVersion, CApiPluginManager $oPluginManager)
	{
		parent::__construct($sVersion, $oPluginManager);

		$this->AddHook('api-change-account-by-id', 'PluginChangeAccountById');

		$this->AddXmlHook('DoGetAutoresponder', 'DoGetAutoresponder');
		$this->AddXmlHook('DoUpdateAutoresponder', 'DoUpdateAutoresponder');
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	abstract protected function validateIfAccountCanChangeAutoresponder($oAccount);

	/**
	 * @param CAccount $oAccount
	 * @return array [enabled, subject, body]  | false
	 */
	abstract protected function getAutoresponder($oAccount);

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	abstract protected function disableAutoresponder($oAccount);

	/**
	 * @param CAccount $oAccount
	 * @param string $sSubject
	 * @param string $sMessage
	 * @return bool
	 */
	abstract protected function setAutoresponder($oAccount, $sSubject, $sMessage);

	/**
	 * @param CAccount $oAccount
	 */
	public function PluginChangeAccountById(&$oAccount)
	{
		if (($oAccount instanceof CAccount) &&
			$this->validateIfAccountCanChangeAutoresponder($oAccount))
		{
			$oAccount->EnableExtension(CAccount::AutoresponderExtension);
		}
	}

	/**
	 * @param CAppServer $oServer
	 */
	public function DoGetAutoresponder(&$oServer)
	{
		$iAccountId = (int) $oServer->GetRequestXml()->GetParamValueByName('id_acct');
		$oAccount = $oServer->getAccount($iAccountId);
		if ($oAccount)
		{
			if (($oAccount instanceof CAccount) && $oAccount->IsEnabledExtension(CAccount::AutoresponderExtension))
			{
				$aAutoResponderValue = $this->getAutoresponder($oAccount);
				if (isset($aAutoResponderValue['subject'], $aAutoResponderValue['body'], $aAutoResponderValue['enabled']))
				{
					$oAutoResponderNode = new CXmlDomNode('autoresponder');

					$oAutoResponderNode->AppendAttribute('enable', (bool) $aAutoResponderValue['enabled']);
					$oAutoResponderNode->AppendAttribute('id_acct', $oAccount->IdAccount);
					$oAutoResponderNode->AppendChild(new CXmlDomNode('subject', $aAutoResponderValue['subject'], true));
					$oAutoResponderNode->AppendChild(new CXmlDomNode('message', $aAutoResponderValue['body'], true));

					$oServer->GetResultXml()->XmlRoot->AppendChild($oAutoResponderNode);
				}
				else
				{
					$oServer->SetErrorResponse(WebMailException);
				}
			}
			else
			{
				$oServer->SetErrorResponse(WebMailException);
			}
		}
		else
		{
			$oServer->SetAccountErrorResponse();
		}
	}

	/**
	 * @param CAppServer $oServer
	 */
	public function DoUpdateAutoresponder(&$oServer)
	{
		$iAcctountId = (int) $oServer->GetRequestXml()->GetParamValueByName('id_acct');
		$oAccount = $oServer->getAccount($iAcctountId);
		if ($oAccount)
		{
			if ($oAccount instanceof CAccount && $oAccount->IsEnabledExtension(CAccount::AutoresponderExtension))
			{
				$bIsDemo = false;
				CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
				if ($bIsDemo)
				{
					$oServer->SetErrorResponse('For security reasons, setting autoresponder is disabled in this demo.');
				}
				else
				{
					$oAutoresponderNode = $oServer->GetRequestXml()->XmlRoot->GetChildNodeByTagName('autoresponder');
					$bIsEnabled = (bool) $oAutoresponderNode->GetAttribute('enable', 0);
					$sSubject = $oAutoresponderNode->GetChildValueByTagName('subject');
					$sMessage = $oAutoresponderNode->GetChildValueByTagName('message');

					$oDomain = $oServer->getDefaultAccountDomain($oAccount);
					if (!$oDomain)
					{
						$oServer->SetErrorResponse(WebMailException);
					}
					else if ($oAccount->IsEnabledExtension(CAccount::AutoresponderExtension))
					{
						if ($bIsEnabled)
						{
							$this->setAutoresponder($oAccount, $sSubject, $sMessage);
						}
						else
						{
							$this->disableAutoresponder($oAccount);
						}

						$oServer->SetUpdateResponse('autoresponder');
					}
					else
					{
						$oServer->SetErrorResponse(PROC_CANT_UPDATE_ACCT);
					}
				}
			}
			else
			{
				$oServer->SetErrorResponse(PROC_CANT_UPDATE_ACCT);
			}
		}
		else
		{
			$oServer->SetAccountErrorResponse();
		}
	}
}
