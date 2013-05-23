<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Api
 */
abstract class AApiForwardPlugin extends AApiPlugin
{
	/**
	 * @param string $sVersion
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct($sVersion, CApiPluginManager $oPluginManager)
	{
		parent::__construct($sVersion, $oPluginManager);

		$this->AddHook('api-change-account-by-id', 'PluginChangeAccountById');

		$this->AddXmlHook('DoGetForward', 'DoGetForward');
		$this->AddXmlHook('DoUpdateForward', 'DoUpdateForward');
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	abstract protected function validateIfAccountCanUseForward($oAccount);

	/**
	 * @param CAccount $oAccount
	 * @return array [enabled, email] | false
	 */
	abstract protected function getForward($oAccount);

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	abstract protected function disableForward($oAccount);

	/**
	 * @param CAccount $oAccount
	 * @param string $sForwardEmail
	 * @return bool
	 */
	abstract protected function setForward($oAccount, $sForwardEmail);

	/**
	 * @param CAccount $oAccount
	 */
	public function PluginChangeAccountById(&$oAccount)
	{
		if (($oAccount instanceof CAccount) && $this->validateIfAccountCanUseForward($oAccount))
		{
			$oAccount->EnableExtension(CAccount::ForwardExtension);
		}
	}

	/**
	 * @param CAppServer $oServer
	 */
	public function DoGetForward(&$oServer)
	{
		$iAccountId = (int) $oServer->GetRequestXml()->GetParamValueByName('id_acct');
		$oAccount = $oServer->getAccount($iAccountId);
		if ($oAccount)
		{
			if (($oAccount instanceof CAccount) && $oAccount->IsEnabledExtension(CAccount::ForwardExtension))
			{
				$aForwardValue = /* @var $aForwardValue array */ $this->getForward($oAccount);
				if (isset($aForwardValue['email'], $aForwardValue['enabled']))
				{
					$oForwardNode = new CXmlDomNode('forward');

					$oForwardNode->AppendAttribute('enable', ((bool) $aForwardValue['enabled']) ? '1' : '0');
					$oForwardNode->AppendAttribute('id_acct', $oAccount->IdAccount);
					$oForwardNode->AppendChild(new CXmlDomNode('email', $aForwardValue['email'], true));

					$oServer->GetResultXml()->XmlRoot->AppendChild($oForwardNode);
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
	public function DoUpdateForward(&$oServer)
	{
		$iAcctountId = (int) $oServer->GetRequestXml()->GetParamValueByName('id_acct');
		$oAccount = $oServer->getAccount($iAcctountId);
		if ($oAccount)
		{
			if ($oAccount instanceof CAccount && $oAccount->IsEnabledExtension(CAccount::ForwardExtension))
			{
				$bIsDemo = false;
				CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
				if ($bIsDemo)
				{
					$oServer->SetErrorResponse('For security reasons, setting forward address is disabled in this demo.');
				}
				else
				{
					$oAutoresponderNode = $oServer->GetRequestXml()->XmlRoot->GetChildNodeByTagName('forward');
					$bIsEnabled = (bool) $oAutoresponderNode->GetAttribute('enable', 0);
					$sForwardEmail = $oAutoresponderNode->GetChildValueByTagName('email');

					$oDomain = $oServer->getDefaultAccountDomain($oAccount);
					if (!$oDomain)
					{
						$oServer->SetErrorResponse(WebMailException);
					}
					else if ($oAccount->IsEnabledExtension(CAccount::ForwardExtension))
					{
						if ($bIsEnabled)
						{
							$this->setForward($oAccount, $sForwardEmail);
						}
						else
						{
							$this->disableForward($oAccount);
						}

						$oServer->SetUpdateResponse('forward');
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
