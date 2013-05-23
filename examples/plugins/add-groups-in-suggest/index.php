<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class_exists('CApi') or die();

class CAddGroupsInSuggestPlugin extends AApiPlugin
{
	/**
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct(CApiPluginManager $oPluginManager)
	{
		parent::__construct('1.0', $oPluginManager);

		$this->AddHook('webmail.change-suggest-list', 'PluginWebmailChangeSuggestList');
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sSearch
	 * @param array $aList
	 * @param array $aCounts
	 */
	public function PluginWebmailChangeSuggestList($oAccount, $sSearch, &$aList, &$aCounts)
	{
		$iSuggestContactsLimit = CApi::GetConf('webmail.suggest-contacts-limit', 20);
		if (count($aList) < $iSuggestContactsLimit)
		{
			/* @var $oApiContactsManager CApiContactsManager */
			$oApiContactsManager = CApi::Manager('contacts');
				
			$aGroups = $oApiContactsManager->GetGroupItems($oAccount->IdUser,
				EContactSortField::Name, ESortOrder::ASC, 0, $iSuggestContactsLimit - count($aList), $sSearch);

			if (is_array($aGroups) && 0 < count($aGroups))
			{
				$iCount = 0;
				$oContactListItem = $oContact = null;
				foreach ($aGroups as /* @var $oContactListItem CContactListItem */ &$oContactListItem)
				{
					$aContactsOfGroup = $oApiContactsManager->GetContactItems($oAccount->IdUser,
						EContactSortField::Name, ESortOrder::ASC, 0, 99, '', '', $oContactListItem->Id);

					$aEmails = array();
					if (is_array($aContactsOfGroup))
					{
						foreach ($aContactsOfGroup as /* @var $oContact CContactListItem */ $oContact)
						{
							$aEmails[] = $oContact->Email;
						}
					}

					if (0 < count($aEmails))
					{
						$iCount++;
						$oContactListItem->Email = implode(', ', $aEmails);
						$aList[] = $oContactListItem;
					}

					unset($oContactListItem);
				}

				$aCounts[1] = $iCount;
			}
		}
	}
}

return new CAddGroupsInSuggestPlugin($this);
