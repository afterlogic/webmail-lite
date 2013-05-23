<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

include_once WM_ROOTPATH.'common/class_actionfilters.php';

class CAppXmlBuilder
{
	/**
	 * @param CXmlDocument &$oRequestXml
	 * @param CXmlDocument &$oResultXml
	 * @param CAccount $oAccount
	 * @param CApiContactsManager $oApiContactsManager
	 * @param string | int $sNewContactId = ''
	 * @return void
	 */
	public static function BuildContactList(CXmlDocument &$oRequestXml, CXmlDocument &$oResultXml, $oAccount, $oApiContactsManager, $sNewContactId = '')
	{
		$iPage = $oRequestXml->GetParamValueByName('page');
		$mSortField = $oRequestXml->GetParamValueByName('sort_field');
		$bSortOrder = (bool) $oRequestXml->GetParamValueByName('sort_order');

		$sGroupId = $oRequestXml->GetParamValueByName('id_group');
		$sSearch = $oRequestXml->XmlRoot->GetChildValueByTagName('look_for', true);

		$oResLookForNode = $oRequestXml->XmlRoot->GetChildNodeByTagName('look_for');
		$iLookForType = isset($oResLookForNode->Attributes['type']) ? (int) $oResLookForNode->Attributes['type'] : 0;

		$sFirstCharacter = $oRequestXml->GetParamValueByName('look_first_character');

		$aList = array();
		$aCounts = array(0, 0);

		// suggest contacts
		if (1 === $iLookForType)
		{
			$aContacts = $oApiContactsManager->GetSuggestItems(
				$oAccount, $sSearch, CApi::GetConf('webmail.suggest-contacts-limit', 20));

			if (is_array($aContacts))
			{
				$aList = array_merge($aList, $aContacts);
			}
			$aCounts[0] = count($aList);

			CApi::Plugin()->RunHook('webmail.change-suggest-list', array($oAccount, $sSearch, &$aList, &$aCounts));
		}
		// contacts manager
		else if (0 === $iLookForType)
		{
			if (in_array((string) $sGroupId, array('', '0', '-1')))
			{
				$aCounts[0] = $oApiContactsManager->GetContactItemsCount($oAccount->IdUser, $sSearch, $sFirstCharacter);
				$aCounts[1] = $oApiContactsManager->GetGroupItemsCount($oAccount->IdUser, $sSearch, $sFirstCharacter);
			}
			else
			{
				$aCounts[0] = $oApiContactsManager->GetContactItemsCount($oAccount->IdUser, $sSearch, $sFirstCharacter, $sGroupId);
			}

			if (0 < $aCounts[1])
			{
				$iGroupOffset = ($iPage - 1) * $oAccount->User->ContactsPerPage;
				if ($iGroupOffset < $aCounts[1])
				{
					$aGroups = $oApiContactsManager->GetGroupItems(
						$oAccount->IdUser, $mSortField, (int) $bSortOrder, $iGroupOffset,
						$oAccount->User->ContactsPerPage, $sSearch, $sFirstCharacter);

					if (is_array($aGroups))
					{
						$aList = array_merge($aList, $aGroups);
					}
				}
			}

			if (0 < $aCounts[0])
			{
				if ($oAccount->User->ContactsPerPage > count($aList))
				{
					$iContactOffset = ($iPage - 1) * $oAccount->User->ContactsPerPage - $aCounts[1] + count($aList);

					$aContacts = $oApiContactsManager->GetContactItems(
						$oAccount->IdUser, $mSortField, (int) $bSortOrder, $iContactOffset,
						$oAccount->User->ContactsPerPage - count($aList), $sSearch, $sFirstCharacter, $sGroupId);

					if (is_array($aContacts))
					{
						$aList = array_merge($aList, $aContacts);
					}
				}
			}
		}

		$oGroupsNode = new CXmlDomNode('groups');

		$oContactsNode = new CXmlDomNode('contacts_groups');
		$oContactsNode->AppendAttribute('contacts_count', $aCounts[0]);
		$oContactsNode->AppendAttribute('groups_count', $aCounts[1]);

		$oContactsNode->AppendAttribute('page', $iPage);
		$oContactsNode->AppendAttribute('sort_field', $mSortField);
		$oContactsNode->AppendAttribute('sort_order', (int) $bSortOrder);

		$oContactsNode->AppendAttribute('look_first_character', $sFirstCharacter);

		$oContactsNode->AppendAttribute('id_group', $sGroupId);
		$oContactsNode->AppendAttribute('added_contact_id', $sNewContactId);

		$oNewLookForNode = new CXmlDomNode('look_for', $sSearch, true);
		$oNewLookForNode->AppendAttribute('type', $iLookForType);

		$oContactsNode->AppendChild($oNewLookForNode);

		$oContactListItem = null;
		foreach ($aList as /* @var $oContactListItem CContactListItem */ $oContactListItem)
		{
			$oContactNode = new CXmlDomNode('contact_group');
			$oContactNode->AppendAttribute('id', $oContactListItem->Id);
			$oContactNode->AppendAttribute('is_group', (int) $oContactListItem->IsGroup);
			$oContactNode->AppendAttribute('read_only', (int) $oContactListItem->ReadOnly);
			$oContactNode->AppendChild(new CXmlDomNode('name', $oContactListItem->Name, true));
			$oContactNode->AppendChild(new CXmlDomNode('email', $oContactListItem->Email, true));

			$oContactsNode->AppendChild($oContactNode);
			unset($oContactNode, $oContactListItem);
		}

		// ------------- edited dy Sash
		$aGroups = $oApiContactsManager->GetGroupItems($oAccount->IdUser);

		if (is_array($aGroups))
		{
			$oContactListItem = null;
			foreach ($aGroups as /* @var $oContactListItem CContactListItem */ $oContactListItem)
			{
				$oGroupNode = new CXmlDomNode('group');
				$oGroupNode->AppendAttribute('id', $oContactListItem->Id);
				$oGroupNode->AppendChild(new CXmlDomNode('name', $oContactListItem->Name, true));
				$oGroupsNode->AppendChild($oGroupNode);
				unset($oGroupNode);
			}
		}
		$oResultXml->XmlRoot->AppendChild($oGroupsNode);
		// -------------------------

		$oResultXml->XmlRoot->AppendChild($oContactsNode);

		if (-1 !== $sNewContactId && !empty($sNewContactId))
		{
			$oNewContact = $oApiContactsManager->GetContactById($oAccount->IdUser, $sNewContactId);
			if ($oNewContact)
			{
				self::BuildContact($oResultXml, $oNewContact, $oApiContactsManager);
			}
		}
	}

	/**
	 * @param CXmlDocument &$oRequestXml
	 * @param CXmlDocument &$oResultXml
	 * @param CAccount $oAccount
	 * @param CApiGcontactManager $oApiGcontactManager
	 * @return void
	 */
	public static function BuildGlobalContactList(CXmlDocument &$oRequestXml, CXmlDocument &$oResultXml, $oAccount, $oApiGcontactManager)
	{
		$iPage = $oRequestXml->GetParamValueByName('page');
		$mSortField = $oRequestXml->GetParamValueByName('sort_field');
		$bSortOrder = (bool) $oRequestXml->GetParamValueByName('sort_order');

		$sSearch = $oRequestXml->XmlRoot->GetChildValueByTagName('look_for', true);

		$oResLookForNode = $oRequestXml->XmlRoot->GetChildNodeByTagName('look_for');
		$iLookForType = isset($oResLookForNode->Attributes['type']) ? (int) $oResLookForNode->Attributes['type'] : 0;

		$iCount = 0;
		$aList = array();

		if ($oApiGcontactManager)
		{
			$iCount = $oApiGcontactManager->GetContactItemsCount($oAccount, $sSearch);

			if (0 < $iCount)
			{
				$iContactOffset = ($iPage - 1) * $oAccount->User->ContactsPerPage;

				$aList = $oApiGcontactManager->GetContactItems(
					$oAccount, $mSortField, (int) $bSortOrder, $iContactOffset,
					$oAccount->User->ContactsPerPage, $sSearch);
			}
		}

		$oContactsNode = new CXmlDomNode('global_contacts');
		$oContactsNode->AppendAttribute('contacts_count', $iCount);
		$oContactsNode->AppendAttribute('groups_count', 0);

		$oContactsNode->AppendAttribute('page', $iPage);
		$oContactsNode->AppendAttribute('sort_field', $mSortField);
		$oContactsNode->AppendAttribute('sort_order', (int) $bSortOrder);

		$oContactsNode->AppendAttribute('look_first_character', '');

		$oContactsNode->AppendAttribute('id_group', (int) 0);
		$oContactsNode->AppendAttribute('added_contact_id', (int) -1);

		$oNewLookForNode = new CXmlDomNode('look_for', $sSearch, true);
		$oNewLookForNode->AppendAttribute('type', $iLookForType);

		$oContactsNode->AppendChild($oNewLookForNode);

		$oContactListItem = null;
		foreach ($aList as /* @var $oContactListItem CContactListItem */ $oContactListItem)
		{
			$oContactNode = new CXmlDomNode('contact_group');
			$oContactNode->AppendAttribute('id', $oContactListItem->Id);
			$oContactNode->AppendAttribute('is_group', (int) 0);
			$oContactNode->AppendChild(new CXmlDomNode('name', $oContactListItem->Name, true));
			$oContactNode->AppendChild(new CXmlDomNode('email', $oContactListItem->Email, true));

			$oContactsNode->AppendChild($oContactNode);
			unset($oContactNode, $oContactListItem);
		}

		$oResultXml->XmlRoot->AppendChild($oContactsNode);
	}

	public static function LocalMultipleContactGetValueByName($oObject, $sName)
	{
		if (!isset($oObject))
		{
			return '';
		}

		return property_exists($oObject, $sName) ? $oObject->{$sName} : '';
	}

	public static function GetMultipleContactSortName($iSortType)
	{
		$sSortName = '';
		switch ($iSortType)
		{
			case 0:
				$sSortName = 'origAddr';
				break;
			case 1:
				$sSortName = 'recipAddr';
				break;
			case 2:
				$sSortName = 'submitTm';
				break;
			case 3:
				$sSortName = 'complTm';
				break;
			case 4:
				$sSortName = 'msgStatus';
				break;
		}

		return $sSortName;
	}

	public static function SortSSpec($aList, $sSortName, $sSecondSortName, $bSortOrder)
	{
		// TODO
		/**
		usort($aList, function ($a, $b) use ($sSortName, $sSecondSortName, $bSortOrder) {
			$sAValue = CAppXmlBuilder::LocalMultipleContactGetValueByName($a, $sSortName);
			$sBValue = CAppXmlBuilder::LocalMultipleContactGetValueByName($b, $sSortName);

			if ($sAValue === $sBValue)
			{
				if (!empty($sSecondSortName))
				{
					$sASecValue = CAppXmlBuilder::LocalMultipleContactGetValueByName($a, $sSecondSortName);
					$sBSecValue = CAppXmlBuilder::LocalMultipleContactGetValueByName($b, $sSecondSortName);

					if ($sASecValue === $sBSecValue)
					{
						return 0;
					}

					return !$bSortOrder
						? (int) strcasecmp($sBSecValue, $sASecValue)
						: (int) strcasecmp($sASecValue, $sBSecValue);
				}

				return 0;
			}

			return !$bSortOrder
				? (int) strcasecmp($sBValue, $sAValue)
				: (int) strcasecmp($sAValue, $sBValue);
		});
		/**/

		return $aList;
	}

	/**
	 * @param CXmlDocument &$oRequestXml
	 * @param CXmlDocument &$oResultXml
	 * @param CAccount $oAccount
	 * @param CApiContactManager $oApiGcontactManager
	 * @param CApiGcontactManager $oApiGcontactManager
	 * @return void
	 */
	public static function BuildMultipleContactList(CXmlDocument &$oRequestXml, CXmlDocument &$oResultXml, $oAccount,
		$oApiContactsManager, $oApiGcontactManager)
	{
		$mSortField = $oRequestXml->GetParamValueByName('sort_field');
		$bSortOrder = (bool) $oRequestXml->GetParamValueByName('sort_order');

		$iCpp = $oAccount->User->ContactsPerPage;
		$iCppHalf = (int) $iCpp / 2;

		$sSortName = CAppXmlBuilder::GetMultipleContactSortName($mSortField);

		$sSearch = $oRequestXml->XmlRoot->GetChildValueByTagName('look_for', true);

		$aList = array();
		$aContacts = array();
		$aGcontacts = array();

		if ($oApiContactsManager)
		{
			$aContacts = $oApiContactsManager->GetContactItems(
				$oAccount->IdUser, $mSortField, (int) $bSortOrder, 0,
				$iCpp + 1, $sSearch);

			if (!is_array($aContacts))
			{
				$aContacts = array();
			}
		}

		if ($oApiGcontactManager)
		{
			$aGcontacts = $oApiGcontactManager->GetContactItems(
				$oAccount, $mSortField, (int) $bSortOrder, 0,
				$iCpp + 1, $sSearch);

			if (!is_array($aGcontacts))
			{
				$aGcontacts = array();
			}
		}

		if ($iCppHalf < count($aContacts))
		{
			if ($iCppHalf < count($aGcontacts))
			{
				$aContacts = array_slice($aContacts, 0, $iCpp);
			}
			else if (count($aContacts) > count($aGcontacts))
			{
				$aContacts = array_slice($aContacts, 0, $iCpp - count($aGcontacts));
			}
		}

		$aList = array_merge($aContacts, $aGcontacts);
		$aList = array_slice($aList, 0, $iCpp);

		$sSortName = '';
		$sSecondSortName = '';

		switch ((int) $mSortField)
		{
			default:
			case EContactSortField::Name:
				$sSortName = 'Name';
				$sSecondSortName = 'Email';
				break;
			case EContactSortField::EMail:
				$sSortName = 'Email';
				$sSecondSortName = 'Name';
				break;
		}

		$aList = self::SortSSpec($aList, $sSortName, $sSecondSortName, $bSortOrder);
		$aList = array_slice($aList, 0, $iCpp);

		$oContactsNode = new CXmlDomNode('multiple_contacts');

		$oContactsNode->AppendAttribute('page', 1);
		$oContactsNode->AppendAttribute('sort_field', $mSortField);
		$oContactsNode->AppendAttribute('sort_order', (int) $bSortOrder);
		$oContactsNode->AppendAttribute('contacts_count', count($aList));
		$oContactsNode->AppendAttribute('groups_count', 0);

		$oNewLookForNode = new CXmlDomNode('look_for', $sSearch, true);

		$oContactsNode->AppendChild($oNewLookForNode);

		$oContactListItem = null;
		foreach ($aList as /* @var $oContactListItem CContactListItem */ $oContactListItem)
		{
			$oContactNode = new CXmlDomNode('contact_group');
			$oContactNode->AppendAttribute('id', $oContactListItem->Id);
			$oContactNode->AppendAttribute('is_group', (int) 0);
			$oContactNode->AppendAttribute('is_global', (int) $oContactListItem->Global);
			$oContactNode->AppendChild(new CXmlDomNode('name', $oContactListItem->Name, true));
			$oContactNode->AppendChild(new CXmlDomNode('email', $oContactListItem->Email, true));

			$oContactsNode->AppendChild($oContactNode);
			unset($oContactNode, $oContactListItem);
		}

		$oResultXml->XmlRoot->AppendChild($oContactsNode);
	}

	/**
	 * @param CXmlDocument $oResultXml
	 * @param CContact $oContact
	 * @param CApiContactsManager $oApiContactsManager = null
	 * @return void
	 */
	public static function BuildContact(CXmlDocument &$oResultXml, $oContact, $oApiContactsManager = null, $bIsGlobal = false)
	{
		$oContactNode = new CXmlDomNode($bIsGlobal ? 'global_contact' : 'contact');
		$oContactNode->AppendAttribute('id', $oContact->IdContact);
		$oContactNode->AppendAttribute('etag', $oContact->ETag);
		$oContactNode->AppendAttribute('primary_email', $oContact->PrimaryEmail);
		$oContactNode->AppendAttribute('use_friendly_name', (int) $oContact->UseFriendlyName);
		$oContactNode->AppendAttribute('read_only', (int) $oContact->ReadOnly);
		$oContactNode->AppendChild(new CXmlDomNode('title', $oContact->Title, true));
		$oContactNode->AppendChild(new CXmlDomNode('fullname', $oContact->FullName, true));
		$oContactNode->AppendChild(new CXmlDomNode('firstname', $oContact->FirstName, true));
		$oContactNode->AppendChild(new CXmlDomNode('lastname', $oContact->LastName, true));
		$oContactNode->AppendChild(new CXmlDomNode('nickname', $oContact->NickName, true));

		$oBirthdayNode = new CXmlDomNode('birthday');
		$oBirthdayNode->AppendAttribute('day', $oContact->BirthdayDay);
		$oBirthdayNode->AppendAttribute('month', $oContact->BirthdayMonth);
		$oBirthdayNode->AppendAttribute('year', $oContact->BirthdayYear);
		$oContactNode->AppendChild($oBirthdayNode);

		$oPersonalNode = new CXmlDomNode('personal');
		$oPersonalNode->AppendChild(new CXmlDomNode('email', $oContact->HomeEmail, true));
		$oPersonalNode->AppendChild(new CXmlDomNode('street', $oContact->HomeStreet, true));
		$oPersonalNode->AppendChild(new CXmlDomNode('city', $oContact->HomeCity, true));
		$oPersonalNode->AppendChild(new CXmlDomNode('state', $oContact->HomeState, true));
		$oPersonalNode->AppendChild(new CXmlDomNode('zip', $oContact->HomeZip, true));
		$oPersonalNode->AppendChild(new CXmlDomNode('country', $oContact->HomeCountry, true));
		$oPersonalNode->AppendChild(new CXmlDomNode('fax', $oContact->HomeFax, true));
		$oPersonalNode->AppendChild(new CXmlDomNode('phone', $oContact->HomePhone, true));
		$oPersonalNode->AppendChild(new CXmlDomNode('mobile', $oContact->HomeMobile, true));
		$oPersonalNode->AppendChild(new CXmlDomNode('web', $oContact->HomeWeb, true));
		$oContactNode->AppendChild($oPersonalNode);

		$oBusinessNode = new CXmlDomNode('business');
		$oBusinessNode->AppendChild(new CXmlDomNode('email', $oContact->BusinessEmail, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('company', $oContact->BusinessCompany, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('job_title', $oContact->BusinessJobTitle, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('department', $oContact->BusinessDepartment, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('office', $oContact->BusinessOffice, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('street', $oContact->BusinessStreet, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('city', $oContact->BusinessCity, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('state', $oContact->BusinessState, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('zip', $oContact->BusinessZip, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('country', $oContact->BusinessCountry, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('fax', $oContact->BusinessFax, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('phone', $oContact->BusinessPhone, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('mobile', $oContact->BusinessMobile, true));
		$oBusinessNode->AppendChild(new CXmlDomNode('web', $oContact->BusinessWeb, true));
		$oContactNode->AppendChild($oBusinessNode);

		$oOtherNode = new CXmlDomNode('other');
		$oOtherNode->AppendChild(new CXmlDomNode('email', $oContact->OtherEmail, true));
		$oOtherNode->AppendChild(new CXmlDomNode('notes', $oContact->Notes, true));
		$oContactNode->AppendChild($oOtherNode);

		$oGroupsNode = new CXmlDomNode('groups');

		if ($oApiContactsManager)
		{
			$oGroup = null;
			$aContactsOfGroup = $oApiContactsManager->GetGroupItems(
				$oContact->IdUser, EContactSortField::Name, ESortOrder::ASC, 0, 99, '', '', $oContact->IdContact);

			foreach ($aContactsOfGroup as /* @var $oGroup CContactListItem */ $oGroup)
			{
				$oGroupNode = new CXmlDomNode('group');
				$oGroupNode->AppendAttribute('id', $oGroup->Id);
				$oGroupNode->AppendChild(new CXmlDomNode('name', $oGroup->Name, true));

				$oGroupsNode->AppendChild($oGroupNode);
				unset($oGroupNode, $oGroup);
			}
		}

		$oContactNode->AppendChild($oGroupsNode);
		$oResultXml->XmlRoot->AppendChild($oContactNode);
	}

	/**
	 * @param CXmlDocument $oResultXml
	 * @param CGroup $oGroup
	 * @param CApiContactsManager $oApiContactsManager
	 * @return void
	 */
	public static function BuildGroup(CXmlDocument &$oResultXml, $oGroup, $oApiContactsManager)
	{
		$oGroupNode = new CXmlDomNode('group');
		$oGroupNode->AppendAttribute('id', $oGroup->IdGroup);
		$oGroupNode->AppendAttribute('organization', (int) $oGroup->IsOrganization);

		$oGroupNode->AppendChild(new CXmlDomNode('name', $oGroup->Name, true));
		$oGroupNode->AppendChild(new CXmlDomNode('email', $oGroup->Email, true));
		$oGroupNode->AppendChild(new CXmlDomNode('company', $oGroup->Company, true));
		$oGroupNode->AppendChild(new CXmlDomNode('street', $oGroup->Street, true));
		$oGroupNode->AppendChild(new CXmlDomNode('city', $oGroup->City, true));
		$oGroupNode->AppendChild(new CXmlDomNode('state', $oGroup->State, true));
		$oGroupNode->AppendChild(new CXmlDomNode('zip', $oGroup->Zip, true));
		$oGroupNode->AppendChild(new CXmlDomNode('country', $oGroup->Country, true));
		$oGroupNode->AppendChild(new CXmlDomNode('phone', $oGroup->Phone, true));
		$oGroupNode->AppendChild(new CXmlDomNode('fax', $oGroup->Fax, true));
		$oGroupNode->AppendChild(new CXmlDomNode('web', $oGroup->Web, true));

		$oContactsNode = new CXmlDomNode('contacts');

		$oContact = null;
		$aContactsOfGroup = $oApiContactsManager->GetContactItems(
			$oGroup->IdUser, EContactSortField::Name, ESortOrder::ASC, 0, 99, '', '', $oGroup->IdGroup);

		if (is_array($aContactsOfGroup))
		{
			foreach ($aContactsOfGroup as /* @var $oContact CContactListItem */ $oContact)
			{
				$oContactNode = new CXmlDomNode('contact');
				$oContactNode->AppendAttribute('id', $oContact->Id);
				$oContactNode->AppendChild(new CXmlDomNode('fullname', $oContact->Name, true));
				$oContactNode->AppendChild(new CXmlDomNode('email', $oContact->Email, true));

				$oContactsNode->AppendChild($oContactNode);
				unset($oContactNode, $oContact);
			}
		}

		$oGroupNode->AppendChild($oContactsNode);

		$oResultXml->XmlRoot->AppendChild($oGroupNode);
	}

	/**
	 * @param CXmlDocument $oResultXml
	 * @param FilterCollection $oFilters
	 * @param int $iAccountId
	 * @return void
	 */
	public static function BuildFilterList(CXmlDocument &$oResultXml, $oFilters, $iAccountId)
	{
		$oFiltersNode = new CXmlDomNode('filters');
		$oFiltersNode->AppendAttribute('id_acct', $iAccountId);

		$aFilterKeys = array_keys($oFilters->Instance());
		foreach ($aFilterKeys as $mKey)
		{
			$oFilter =& $oFilters->Get($mKey);
			if ($oFilter->IsSystem)
			{
				continue;
			}

			$oFilterNode = new CXmlDomNode('filter', $oFilter->Filter, true);
			$oFilterNode->AppendAttribute('id', $oFilter->Id);
			$oFilterNode->AppendAttribute('field', $oFilter->Field);
			$oFilterNode->AppendAttribute('condition', $oFilter->Condition);
			$oFilterNode->AppendAttribute('action', $oFilter->Action);
			$oFilterNode->AppendAttribute('id_folder', $oFilter->IdFolder);
			$oFilterNode->AppendAttribute('applied', (int) $oFilter->Applied);
			$oFiltersNode->AppendChild($oFilterNode);
			unset($oFilterNode, $oFilter);
		}

		$oResultXml->XmlRoot->AppendChild($oFiltersNode);
	}

	/**
	 * @param CXmlDocument $oResultXml
	 * @param array $aAccounts
	 * @return void
	 */
	public static function BuildIdentities(CXmlDocument &$oResultXml, $aAccounts)
	{
		$oIdentitiesNode = new CXmlDomNode('identities');

		/* @var $oApiUsersManager CApiUsersManager */
		$oApiUsersManager = CApi::Manager('users');
		$oSettings =& CApi::GetSettings();

		$iIdentityType = $oSettings->GetConf('WebMail/AllowIdentities')
			? EIdentityType::Normal : EIdentityType::Virtual;

		$oAccount = null;
		foreach ($aAccounts as /* @var $oAccount Account */ $oAccount)
		{
			$aIdentities = $oApiUsersManager->GetIdentities($oAccount, $iIdentityType);
			if ($aIdentities && is_array($aIdentities))
			{
				foreach ($aIdentities as /* @var $oIdentity CIdentity */ $oIdentity)
				{
					$oIdentityNode = new CXmlDomNode('identity');
					$oIdentityNode->AppendAttribute('id', $oIdentity->IdIdentity);
					$oIdentityNode->AppendAttribute('id_acct', $oIdentity->IdAccount);
					$oIdentityNode->AppendAttribute('virtual', (int) $oIdentity->Virtual);

					$oIdentityNode->AppendAttribute('use_signature', (int) $oIdentity->UseSignature);
					$oIdentityNode->AppendAttribute('html_signature',
						(int) (EAccountSignatureType::Html === $oIdentity->SignatureType));

					$oIdentityNode->AppendChild(new CXmlDomNode('email', $oIdentity->Email, true));
					$oIdentityNode->AppendChild(new CXmlDomNode('name', $oIdentity->FriendlyName, true));
					$oIdentityNode->AppendChild(new CXmlDomNode('signature', $oIdentity->Signature, true));
					$oIdentitiesNode->AppendChild($oIdentityNode);

					unset($oIdentityNode, $oIdentity);
				}
			}
		}

		$oResultXml->XmlRoot->AppendChild($oIdentitiesNode);
	}

	protected static function buildContactOfMessage(&$oResultXml, $oAccount, &$oFromNode, $sEmail)
	{
		static $aContactCache = array();
		static $oApiContactsManager = null;

		$oContact = null;
		if (null === $oApiContactsManager)
		{
			/* @var $oApiContactsManager CApiContactsManager */
			$oApiContactsManager = CApi::Manager('contacts');
		}

		if (!isset($aContactCache[$sEmail]) && $oApiContactsManager)
		{
			$oContact = $oApiContactsManager->GetContactByEmail($oAccount->IdUser, $sEmail);
		}
		else if ($oApiContactsManager && $aContactCache[$sEmail])
		{
			$oContact = $aContactCache[$sEmail];
		}

		if ($oContact)
		{
			$oFromNode->AppendAttribute('contact_id', $oContact->IdContact);
			self::BuildContact($oResultXml, $oContact, $oApiContactsManager);
			$aContactCache[$sEmail] = $oContact;
		}
	}

	/**
	 * @param CXmlDocument $oResultXml
	 * @param CAccount $oAccount
	 * @return void
	 */
	public static function BuildMessage(CXmlDocument &$oResultXml, CAccount $oAccount,
		&$oMailProcessor, $oMessage, $oFolder, $iMode, $iCharsetNum, $bShowImages)
	{
		$bSafety = true;
		$oMessageNode = new CXmlDomNode('message');

		$iMsgId = $oMessage->IdMsg;
		$mMsgUid = $oMessage->Uid;

		$oMessageInfo = new CMessageInfo();
		$oMessageInfo->SetInfo($iMsgId, $mMsgUid, $oFolder->IdDb, $oFolder->FullName);

		$iMessageClassType = $oMessage->TextBodies->ClassType();
		$bVoice = $oMessage->IsVoiceMessage();

		$oMessageNode->AppendAttribute('id', $iMsgId);
		$oMessageNode->AppendAttribute('size', $oMessage->GetMailSize());
		$oMessageNode->AppendAttribute('html', (int) (($iMessageClassType & 2) == 2));
		$oMessageNode->AppendAttribute('plain', (int) (($iMessageClassType & 1) == 1));
		$oMessageNode->AppendAttribute('priority', $oMessage->GetPriorityStatus());
		$oMessageNode->AppendAttribute('mode', $iMode);
		$oMessageNode->AppendAttribute('charset', $iCharsetNum);
		$oMessageNode->AppendAttribute('downloaded', (int) $oMessage->Downloaded);
		$oMessageNode->AppendAttribute('sensivity', $oMessage->GetSensitivity());
		$oMessageNode->AppendAttribute('voice', (int) $bVoice);

		$bHasCharset = (bool) $oMessage->HasCharset;

		$oMaf =& MessageActionFilters::CreateInstance();
		$aMafNoReply = $oMaf->GetNoReplyEmails();
		$aMafNoReplyAll = $oMaf->GetNoReplyAllEmails();
		$aMafNoForward = $oMaf->GetNoForwardEmails();

		$mFromEmail = $oMessage->GetFrom();
		$mFromEmail = $mFromEmail->Email;

		$sTextCharset = $oMessage->GetTextCharset();

		$iRtl = 0;
		$bIsUtf = false;
		if (null !== $sTextCharset)
		{
			switch (ConvertUtils::GetCodePageNumber($sTextCharset))
			{
				case 1255:
				case 1256:
				case 28596:
				case 28598:
					$iRtl = 1;
					break;
				case 65001:
					$bIsUtf = true;
					break;
			}
		}

		$oMessageNode->AppendChild(new CXmlDomNode('uid', $mMsgUid, true));

		$oFolderNode = new CXmlDomNode('folder', $oFolder->FullName, true);
		$oFolderNode->AppendAttribute('id', $oFolder->IdDb);
		$oMessageNode->AppendChild($oFolderNode);

		$iAccountOffset = $oAccount->GetDefaultTimeOffset();

		if (($iMode & 1) == 1)
		{
			$oHeadersNode = new CXmlDomNode('headers');
			$oFromNode = new CXmlDomNode('from');

			$oFrom4search =& $oMessage->GetFrom();
			if ($oFrom4search)
			{
				self::buildContactOfMessage($oResultXml, $oAccount, $oFromNode, $oFrom4search->Email);
			}

			$oFromNode->AppendChild(new CXmlDomNode('short', WebMailMessage::ClearForSend(trim($oFrom4search->DisplayName)), true));
			$oFromNode->AppendChild(new CXmlDomNode('full', WebMailMessage::ClearForSend(trim($oFrom4search->ToDecodedString())), true));
			$oHeadersNode->AppendChild($oFromNode);

			$oHeadersNode->AppendChild(new CXmlDomNode('to', $oMessage->GetToAsString(true), true));
			$oHeadersNode->AppendChild(new CXmlDomNode('cc', $oMessage->GetCcAsString(true), true));
			$oHeadersNode->AppendChild(new CXmlDomNode('bcc', $oMessage->GetBccAsString(true), true));

			$oHeadersNode->AppendChild(new CXmlDomNode('reply_to', $oMessage->GetReplyToAsString(true), true));
			$oHeadersNode->AppendChild(new CXmlDomNode('subject', $oMessage->GetSubject(true), true));

			$sMailConfirmation = $oMessage->GetReadMailConfirmationAsString();
			if (0 < strlen($sMailConfirmation))
			{
				$oHeadersNode->AppendChild(new CXmlDomNode('mailconfirmation', $sMailConfirmation, true));
			}

			$oDate = $oMessage->GetDate();
			$oDate->FormatString = $oAccount->User->DefaultDateFormat;
			$oDate->TimeFormat = $oAccount->User->DefaultTimeFormat;

			$oHeadersNode->AppendChild(new CXmlDomNode('short_date', $oDate->GetFormattedShortDate($iAccountOffset), true));
			$oHeadersNode->AppendChild(new CXmlDomNode('full_date', $oDate->GetFormattedFullDate($iAccountOffset), true));
			$oHeadersNode->AppendChild(new CXmlDomNode('time', $oDate->GetFormattedTime($iAccountOffset), true));

			$oMessageNode->AppendChild($oHeadersNode);
		}

		$sHtmlPart = '';
		if (($iMode & 2) == 2 && ($iMessageClassType & 2) == 2)
		{
			$sHtmlPart = ConvertUtils::ReplaceJSMethod(
				$oMessage->GetCensoredHtmlWithImageLinks(true, $oMessageInfo));

			if (!$bShowImages)
			{
				$sHtmlPart = ConvertUtils::HtmlBodyWithoutImages($sHtmlPart);
				if (isset($GLOBALS[GL_WITHIMG]) && $GLOBALS[GL_WITHIMG])
				{
					$GLOBALS[GL_WITHIMG] = false;
					$bSafety = false;
				}
			}
		}

		$bHtmlIsShort = 10 > strlen(trim($sHtmlPart));
		$sModifiedPlainText = '';
		if (($iMode & 4) == 4 || ($iMode & 2) == 2 && ($iMessageClassType & 2) != 2)
		{
			$sModifiedPlainText = $oMessage->GetCensoredTextBody(true);
		}

		if (($iMode & 8) == 8)
		{
			$sReplyHtml = '';

			if (!$bShowImages)
			{
				$sReplyHtml = ConvertUtils::AddToLinkMailToCheck(
					ConvertUtils::HtmlBodyWithoutImages(
						ConvertUtils::ReplaceJSMethod(
							$oMessage->GetRelpyAsHtml(true, $iAccountOffset, $oMessageInfo))));

				if (isset($GLOBALS[GL_WITHIMG]) && $GLOBALS[GL_WITHIMG])
				{
					$GLOBALS[GL_WITHIMG] = false;
					$bSafety =  false;
				}
			}
			else
			{
				$sReplyHtml = ConvertUtils::AddToLinkMailToCheck(
					ConvertUtils::ReplaceJSMethod($oMessage->GetRelpyAsHtml(true, $iAccountOffset, $oMessageInfo)));
			}

			$oMessageNode->AppendChild(new CXmlDomNode('reply_html', $sReplyHtml, true, true));

			$iRtl = (0 === $iRtl && $bIsUtf && ConvertUtils::IsHebUtf8($sReplyHtml)) ? 1 : 0;
		}

		if (($iMode & 16) == 16)
		{
			$sReplyPlain = ConvertUtils::AddToLinkMailToCheck(
				$oMessage->GetRelpyAsPlain(true, $iAccountOffset));

			$oMessageNode->AppendChild(new CXmlDomNode('reply_plain', $sReplyPlain, true, true));

			$iRtl = (0 === $iRtl && $bIsUtf && ConvertUtils::IsHebUtf8($sReplyPlain)) ? 1 : 0;
		}

		if (($iMode & 32) == 32)
		{
			$sForwardHtml = '';

			if (!$bShowImages)
			{
				$sForwardHtml = ConvertUtils::AddToLinkMailToCheck(
					ConvertUtils::HtmlBodyWithoutImages(ConvertUtils::ReplaceJSMethod(
						$oMessage->GetForwardAsHtml(true, $iAccountOffset, $oMessageInfo))));

				if (isset($GLOBALS[GL_WITHIMG]) && $GLOBALS[GL_WITHIMG])
				{
					$GLOBALS[GL_WITHIMG] = false;
					$bSafety =  false;
				}
			}
			else
			{
				$sForwardHtml = ConvertUtils::AddToLinkMailToCheck(
					ConvertUtils::ReplaceJSMethod($oMessage->GetForwardAsHtml(true, $iAccountOffset, $oMessageInfo)));
			}

			$oMessageNode->AppendChild(new CXmlDomNode('forward_html', $sForwardHtml, true, true));
			$iRtl = (0 === $iRtl && $bIsUtf && ConvertUtils::IsHebUtf8($sForwardHtml)) ? 1 : 0;
		}

		if (($iMode & 64) == 64)
		{
			$sForwardPlain = ConvertUtils::AddToLinkMailToCheck($oMessage->GetForwardAsPlain(true, $iAccountOffset));
			$oMessageNode->AppendChild(new CXmlDomNode('forward_plain', $sForwardPlain, true, true));
			$iRtl = (0 === $iRtl && $bIsUtf && ConvertUtils::IsHebUtf8($sForwardPlain)) ? 1 : 0;
		}

		if (($iMode & 128) == 128)
		{
			$oMessageNode->AppendChild(new CXmlDomNode('full_headers',
				WebMailMessage::ClearForSend(ConvertUtils::ConvertEncoding(
				$oMessage->OriginalHeaders, $GLOBALS[MailInputCharset], CPAGE_UTF8)), true, true));
		}

		$oMessageNode->AppendAttribute('safety', (int) $bSafety);
		$sMsqAttachLine = 'msg_id='.$iMsgId.'&msg_uid='.urlencode($mMsgUid).
			'&folder_id='.$oFolder->IdDb.'&folder_fname='.urlencode($oFolder->FullName);

		$sICal = '';
		$sVCard = '';
		$oApiCollaborationManager = /* @var $oApiCollaborationManager CApiCollaborationManager */ CApi::Manager('collaboration');
		$bParseICal = $oApiCollaborationManager && $oApiCollaborationManager->IsCalendarAppointmentsSupported();
		if ($bParseICal)
		{
			$bParseICal = $oAccount->User->GetCapa('MEETINGS');
		}

		$aAddAttachArray = array();
		if (($iMode & 256) == 256 || ($iMode & 8) == 8 || ($iMode & 16) == 16 || ($iMode & 32) == 32 || ($iMode & 64) == 64)
		{
			$oAttachments =& $oMessage->Attachments;
			if ($oAttachments && 0 < $oAttachments->Count())
			{
				$oTempFiles =& CTempFiles::CreateInstance($oAccount);
				$oAttachmentsNode = new CXmlDomNode('attachments');
				$aAttachmentsKeys = array_keys($oAttachments->Instance());
				foreach ($aAttachmentsKeys as $iAttachKey)
				{
					$aAttachArray = array();

					$oAttachment =& $oAttachments->Get($iAttachKey);

					$sTempName = $oMessage->IdMsg.'-'.$iAttachKey.'_'.ConvertUtils::ClearFileName($oAttachment->GetTempName());
					$sFileName = ConvertUtils::ClearFileName(ConvertUtils::ClearUtf8($oAttachment->GetFilenameFromMime(), $GLOBALS[MailInputCharset], CPAGE_UTF8));
					$iSize = 0;
					$bIsBodyStructureAttachment = false;
					if ($oAttachment->MimePart && $oAttachment->MimePart->BodyStructureIndex !== null &&
						$oAttachment->MimePart->BodyStructureSize !== null)
					{
						$bIsBodyStructureAttachment = true;
						$iSize = $oAttachment->MimePart->BodyStructureSize;
					}
					else
					{
						$iSize = $oTempFiles->SaveFile($sTempName, $oAttachment->GetBinaryBody());
						$iSize = ($iSize < 0) ? 0 : $iSize;
					}

					$aAttachArray['name'] = $sFileName;
					$aAttachArray['tempname'] = $sTempName;
					$aAttachArray['size'] = $iSize;

					$sBodyStructureUrlAdd = '';
					if ($bIsBodyStructureAttachment)
					{
						$sBodyStructureUrlAdd = 'bsi='.urlencode($oAttachment->MimePart->BodyStructureIndex);
						if ($oAttachment->MimePart->BodyStructureEncode !== null && 0 < strlen($oAttachment->MimePart->BodyStructureEncode))
						{
							$sBodyStructureUrlAdd .= '&bse='.urlencode(ConvertUtils::GetBodyStructureEncodeType($oAttachment->MimePart->BodyStructureEncode));
						}
					}

					$oAttachmentNode = new CXmlDomNode('attachment');
					$oAttachmentNode->AppendAttribute('size', $iSize);
					$oAttachmentNode->AppendAttribute('duration',  $oAttachment->GetDuration());
					$oAttachmentNode->AppendAttribute('inline',
						($oAttachment->IsInline && !$bHtmlIsShort) ? '1': '0');

					$oAttachmentNode->AppendChild(new CXmlDomNode('filename', $sFileName, true));

					$sViewUrl = (substr(strtolower($sFileName), -4) == '.eml')
						? 'message-view.php?type='.MESSAGE_VIEW_TYPE_ATTACH.'&tn='.urlencode($sTempName)
						: 'view-image.php?img&tn='.urlencode($sTempName).'&filename='.urlencode($sFileName);

					if ($bIsBodyStructureAttachment)
					{
						$sViewUrl .= '&'.$sBodyStructureUrlAdd.'&'.$sMsqAttachLine;
					}

					$oAttachmentNode->AppendChild(new CXmlDomNode('view', $sViewUrl, true));

					$sLinkUrl = 'attach.php?tn='.urlencode($sTempName);
					if ($bIsBodyStructureAttachment)
					{
						$sLinkUrl .= '&'.$sBodyStructureUrlAdd.'&'.$sMsqAttachLine;
					}

					$sDownloadUrl = $sLinkUrl.'&filename='.urlencode($sFileName);

					if ($bVoice)
					{
						$bAttachmentVoice = false;
						CApi::Plugin()->RunHook('webmail.voice-attachment-detect',
							array($oAttachment, $sFileName, &$bAttachmentVoice));

						if ($bAttachmentVoice)
						{
							$aAttachArray['voice'] = $sDownloadUrl.'&play';
							$oAttachmentNode->AppendChild(new CXmlDomNode('voice', $sDownloadUrl.'&play', true));
						}
					}

					$oAttachmentNode->AppendChild(new CXmlDomNode('download', $sDownloadUrl, true));
					$oAttachmentNode->AppendChild(new CXmlDomNode('tempname', $sTempName, true));
					$sMimeType = ConvertUtils::GetContentTypeFromFileName($sFileName);
					$oAttachmentNode->AppendChild(new CXmlDomNode('mime_type', $sMimeType, true));

					//++ICal
					if ($bParseICal && empty($sICal) && $oAttachment)
					{
						$sContentType = $oAttachment->GetContentType();
						if (0 === strpos(strtolower(trim($sContentType)), 'text/calendar'))
						{
							if ($oAttachment->MimePart && $oAttachment->MimePart->BodyStructureIndex !== null &&
								$oAttachment->MimePart->BodyStructureSize !== null)
							{
								$sEncodedCal = $oMailProcessor->GetBodyPartByIndex(
									$oAttachment->MimePart->BodyStructureIndex,
									$mMsgUid, $oFolder);

								$sICal = ConvertUtils::DecodeBodyByType($sEncodedCal,
									$oAttachment->MimePart->BodyStructureEncode);

								unset($sEncodedCal);
							}
							else
							{
								$sICal = $oAttachment->GetBinaryBody();
							}
						}

						if (!empty($sICal) && false !== strpos($sICal, 'BEGIN:VCALENDAR'))
						{
							$sICal = preg_replace('/(.*)(BEGIN\:VCALENDAR(.+)END\:VCALENDAR)(.*)/ms', '$2', $sICal);
						}
						else
						{
							$sICal = '';
						}
					}
					//--ICal

					//++VCard
					if (empty($sVCard) && $oAttachment)
					{
						$aPathInfo = pathinfo($oAttachment->Filename);
						if (isset($aPathInfo['extension']) && $aPathInfo['extension'] == 'vcf')
						{
							if ($oAttachment->MimePart && $oAttachment->MimePart->BodyStructureIndex !== null &&
								$oAttachment->MimePart->BodyStructureSize !== null)
							{
								$sEncodedCard = $oMailProcessor->GetBodyPartByIndex(
									$oAttachment->MimePart->BodyStructureIndex,
									$mMsgUid, $oFolder);

								$sVCard = ConvertUtils::DecodeBodyByType($sEncodedCard,
									$oAttachment->MimePart->BodyStructureEncode);

								unset($sEncodedCard);
							}
							else
							{
								$sVCard = $oAttachment->GetBinaryBody();
							}
						}

						if (!empty($sVCard) && false !== strpos($sVCard, 'BEGIN:VCARD'))
						{
							$sVCard = preg_replace('/(.*)(BEGIN\:VCARD(.+)END\:VCARD)(.*)/ms', '$2', $sVCard);
						}
						else
						{
							$sVCard = '';
						}
					}
					//--VCard


					$aAttachArray['link'] = $sLinkUrl;
					$aAttachArray['mime_type'] = $sMimeType;
					$aAttachArray['download'] = $sDownloadUrl;

					$aAddAttachArray[] = $aAttachArray;
					$oAttachmentsNode->AppendChild($oAttachmentNode);
					unset($oAttachment, $oAttachmentNode, $aAttachArray);
				}

				$oMessageNode->AppendChild($oAttachmentsNode);
			}
		}

		//++ICal
		if ($bParseICal && !empty($sICal))
		{
			$mResult = false;
			$oApiCalendarManager = CApi::Manager('calendar');
			if ($oApiCalendarManager)
			{
				$mResult = $oApiCalendarManager->PreprocessICS($oAccount, trim($sICal), $mFromEmail);

				if (is_array($mResult) && !empty($mResult['Action']) && !empty($mResult['Body']))
				{
					$oTempFiles =& CTempFiles::CreateInstance($oAccount);
					$sTemptFile = md5($mResult['Body']).'.ics';

					if ($oTempFiles->SaveFile($sTemptFile, $mResult['Body']))
					{
						$oIcsNode = new CXmlDomNode('ics');
						$oIcsNode->AppendAttribute('uid', $mResult['UID']);
						$oIcsNode->AppendAttribute('file', $sTemptFile);
						$oIcsNode->AppendAttribute('type', $mResult['Action']);

						if (!empty($mResult['Location']))
						{
							$oIcsNode->AppendChild(new CXmlDomNode('location', $mResult['Location'], true));
						}

						if (!empty($mResult['Description']))
						{
							$oIcsNode->AppendChild(new CXmlDomNode('description',
								ConvertUtils::FindLinksInPlainText($mResult['Description']), true));
						}

						if (!empty($mResult['When']))
						{
							$oIcsNode->AppendChild(new CXmlDomNode('when', $mResult['When'], true));
						}

						if (!empty($mResult['CalendarId']))
						{
							$oIcsNode->AppendChild(new CXmlDomNode('calendar_id', $mResult['CalendarId'], true));
						}

						if (isset($mResult['Calendars']) && is_array($mResult['Calendars']) && 0 < count($mResult['Calendars']))
						{
							$oCalendarsNode = new CXmlDomNode('calendars');
							foreach ($mResult['Calendars'] as $sUid => $sName)
							{
								$oCalendarNode = new CXmlDomNode('calendar');
								$oCalendarNode->AppendChild(new CXmlDomNode('id', $sUid, true));
								$oCalendarNode->AppendChild(new CXmlDomNode('name', $sName, true));

								$oCalendarsNode->AppendChild($oCalendarNode);
								unset($oCalendarNode);
							}

							$oIcsNode->AppendChild($oCalendarsNode);
						}

						$oMessageNode->AppendChild($oIcsNode);
					}
					else
					{
						CApi::Log('Can\'t save temp file "'.$sTemptFile.'"', ELogLevel::Error);
					}
				}
			}
		}
		//--ICal

		//++VCard
		if (!empty($sVCard))
		{
			$oContact = new CContact();
			$oContact->InitFromVCardStr($oAccount->IdUser, $sVCard);
			$oContact->InitBeforeChange();

			$bContactExists = false;
			$oApiContactsManager = CApi::Manager('contacts');
			if ($oApiContactsManager)
			{
				if ($oApiContactsManager->GetContactById($oAccount->IdUser, $oContact->IdContact))
				{
					$bContactExists = true;
				}
			}

			$oTempFiles =& CTempFiles::CreateInstance($oAccount);
			$sTemptFile = md5($sVCard).'.vcf';

			if ($oTempFiles->SaveFile($sTemptFile, $sVCard))
			{
				$oVcfNode = new CXmlDomNode('vcf');
				$oVcfNode->AppendAttribute('uid', $oContact->IdContact);
				$oVcfNode->AppendAttribute('file', $sTemptFile);
				$oVcfNode->AppendAttribute('exists', $bContactExists ? '1' : '0');

				$oVcfNode->AppendChild(new CXmlDomNode('name', $oContact->FullName, true));
				$oVcfNode->AppendChild(new CXmlDomNode('email', $oContact->ViewEmail, true));

				$oMessageNode->AppendChild($oVcfNode);
			}
			else
			{
				CApi::Log('Can\'t save temp file "'.$sTemptFile.'"', ELogLevel::Error);
			}
		}
		//--VCard

		$bHasCharset = empty($sHtmlPart) && empty($sModifiedPlainText) ? true : $bHasCharset;
		$oMessageNode->AppendAttribute('has_charset', (int) $bHasCharset);

		CApi::Plugin()->RunHook('webmail-change-html-text-from-attachment',
			array(&$oMessage, &$sHtmlPart, &$sModifiedPlainText, &$aAddAttachArray));

		if (($iMode & 2) == 2 && ($iMessageClassType & 2) == 2)
		{
			$oMessageNode->AppendChild(new CXmlDomNode('html_part',
				ConvertUtils::AddToLinkMailToCheck($sHtmlPart), true, true));

			$iRtl = (0 === $iRtl && $bIsUtf && ConvertUtils::IsHebUtf8($sHtmlPart)) ? 1 : 0;
		}

		if (($iMode & 4) == 4 || ($iMode & 2) == 2 && ($iMessageClassType & 2) != 2)
		{
			$oMessageNode->AppendChild(new CXmlDomNode('modified_plain_text',
				ConvertUtils::AddToLinkMailToCheck($sModifiedPlainText), true, true));

			$iRtl = (0 === $iRtl && $bIsUtf && ConvertUtils::IsHebUtf8($sModifiedPlainText)) ? 1 : 0;
		}

		if (($iMode & 512) == 512)
		{
			$unmodified_plain_text = $oMessage->GetNotCensoredTextBody(true);
			$oMessageNode->AppendChild(new CXmlDomNode('unmodified_plain_text', $unmodified_plain_text, true, true));
			$iRtl = (0 === $iRtl && $bIsUtf && ConvertUtils::IsHebUtf8($unmodified_plain_text)) ? 1 : 0;
		}

		$oMessageNode->AppendAttribute('rtl', $iRtl);

		$oMessageNode->AppendChild(new CXmlDomNode('save_link', 'attach.php?'.$sMsqAttachLine, true));
		$oMessageNode->AppendChild(new CXmlDomNode('print_link', 'message-view.php?type='.MESSAGE_VIEW_TYPE_PRINT.'&'.$sMsqAttachLine.'&charset='.$iCharsetNum, true));

		$oMessageNode->AppendAttribute('no_reply', (count($aMafNoReply) > 0 && in_array($mFromEmail, $aMafNoReply)) ? '1' : '0');
		$oMessageNode->AppendAttribute('no_reply_all', (count($aMafNoReplyAll) > 0 && in_array($mFromEmail, $aMafNoReplyAll)) ? '1' : '0');
		$oMessageNode->AppendAttribute('no_forward', (count($aMafNoForward) > 0 && in_array($mFromEmail, $aMafNoForward)) ? '1' : '0');

		$oResultXml->XmlRoot->AppendChild($oMessageNode);
	}

	/**
	 * @param CXmlDocument &$oResultXml
	 * @param int $iAccountId
	 * @param int $iAccountSizeLimit = 0
	 * @param int $iAccountSize = 0
	 */
	public static function BuildAccountImapQuotaNode(CXmlDocument &$oResultXml, $iAccountId, $iAccountSizeLimit = 0, $iAccountSize = 0)
	{
		if (0 < $iAccountId && 0 < $iAccountSizeLimit)
		{
			$oUpdateNode = new CXmlDomNode('update');
			$oUpdateNode->AppendAttribute('value', 'account_properties');

			$oAccountNode = new CXmlDomNode('account');
			$oAccountNode->AppendAttribute('id', $iAccountId);
			$oAccountNode->AppendAttribute('imap_quota_limit', $iAccountSizeLimit);
			$oAccountNode->AppendAttribute('imap_quota', $iAccountSize);

			$oUpdateNode->AppendChild($oAccountNode);

			$oResultXml->XmlRoot->AppendChild($oUpdateNode);
		}
	}

	/**
	 * @param CXmlDocument $oResultXml
	 * @param CAccount $oAccount
	 * @param CDomain $oDomain
	 * @return void
	 */
	public static function BuildSettingsList(CXmlDocument &$oResultXml, CAccount $oAccount, $oDomain)
	{
		$oSettings =& CApi::GetSettings();

		$oSettingsListNode = new CXmlDomNode('settings_list');

		$oSettingsListNode->AppendAttribute('allow_change_interface_settings', (int) $oDomain->AllowUsersChangeInterfaceSettings);
		$oSettingsListNode->AppendAttribute('allow_change_account_settings', (int) $oDomain->AllowUsersChangeEmailSettings);
		$oSettingsListNode->AppendAttribute('allow_add_account', (int) $oDomain->AllowUsersAddNewAccounts);
		$oSettingsListNode->AppendAttribute('msgs_per_page', (int) $oAccount->User->MailsPerPage);
		$oSettingsListNode->AppendAttribute('contacts_per_page', (int) $oAccount->User->ContactsPerPage);
		$oSettingsListNode->AppendAttribute('auto_checkmail_interval', (int) $oAccount->User->AutoCheckMailInterval);
		$oSettingsListNode->AppendAttribute('def_editor', (int) $oAccount->User->DefaultEditor);

		$oSettingsListNode->AppendAttribute('layout', (int) $oAccount->User->Layout);
		$oSettingsListNode->AppendAttribute('def_timezone', $oAccount->User->DefaultTimeZone);
		$oSettingsListNode->AppendAttribute('client_timeoffset', $oAccount->User->ClientTimeOffset);

		$iSaveMail = $oSettings->GetConf('WebMail/SaveMail');
		$iSaveMail = ESaveMail::Always !== $iSaveMail ? $oAccount->User->SaveMail : ESaveMail::Always;
		$oSettingsListNode->AppendAttribute('save_mail', (int) $iSaveMail);

		$iAttachmentSizeLimit = ((bool) $oSettings->GetConf('WebMail/EnableAttachmentSizeLimit'))
			? (int) $oSettings->GetConf('WebMail/AttachmentSizeLimit') : 0;
		$oSettingsListNode->AppendAttribute('attachment_size_limit', $iAttachmentSizeLimit);

		$oSettingsListNode->AppendAttribute('allow_compose_message', (int) $oAccount->AllowCompose);
		$oSettingsListNode->AppendAttribute('allow_reply_message', (int) $oAccount->AllowReply);
		$oSettingsListNode->AppendAttribute('allow_forward_message', (int) $oAccount->AllowForward);

		/* @var $oApiDavManager CApiDavManager */
		$oApiDavManager = CApi::Manager('dav');
		$sDavUrl = $oApiDavManager ? $oApiDavManager->GetServerUrl($oAccount) : '';

		/* @var $oApiCapabilityManager CApiCapabilityManager */
		$oApiCapabilityManager = CApi::Manager('capability');

		$oSettingsListNode->AppendAttribute('allow_calendar', (int)
			($oApiCapabilityManager->IsCalendarSupported() && $oAccount->User->AllowCalendar && $oAccount->User->GetCapa('CALENDAR')));

		$oSettingsListNode->AppendAttribute('imap4_delete_like_pop3', (int) 1); // TODO hc magic
		$oSettingsListNode->AppendAttribute('idle_session_timeout', (int) $oSettings->GetConf('WebMail/IdleSessionTimeout'));

		$oSettingsListNode->AppendAttribute('allow_insert_image', (int) $oSettings->GetConf('WebMail/AllowInsertImage'));
		$oSettingsListNode->AppendAttribute('allow_body_size', (int) $oSettings->GetConf('WebMail/AllowBodySize'));
		$oSettingsListNode->AppendAttribute('max_body_size', (int) $oSettings->GetConf('WebMail/MaxBodySize'));
		$oSettingsListNode->AppendAttribute('max_subject_size', (int) $oSettings->GetConf('WebMail/MaxSubjectSize'));

		$oSettingsListNode->AppendAttribute('mobile_sync_enable_system', (int)
			(!empty($sDavUrl) && (bool) $oSettings->GetConf('Common/EnableMobileSync')));

		/* @var $oApiCollaborationManager CApiCollaborationManager */
		$oApiCollaborationManager = CApi::Manager('collaboration');
		$iOutlookSyncEnable = (int) $oAccount->User->GetCapa('OUTLOOK_SYNC') && $oApiCollaborationManager;

		$oSettingsListNode->AppendAttribute('outlook_sync_enable', $iOutlookSyncEnable);

		$oSettingsListNode->AppendAttribute('allow_first_character_search', (int) CApi::GetConf('webmail.allow-first-character-search', false));
		$oSettingsListNode->AppendAttribute('allow_identities', (int) $oSettings->GetConf('WebMail/AllowIdentities'));
		$oSettingsListNode->AppendAttribute('autosave', (int) CApi::GetConf('webmail.autosave', true));

		$bPab = (int) ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'));
		$bGab = (int) ($oAccount->User->AllowContacts &&
			$oAccount->User->GetCapa('GAB') &&
			$oApiCollaborationManager && $oApiCollaborationManager->IsContactsGlobalSupported());

		$oSettingsListNode->AppendAttribute('show_personal_contacts', (int) $bPab);
		$oSettingsListNode->AppendAttribute('show_global_contacts', (int) ($bGab && $oSettings->GetConf('Contacts/ShowGlobalContactsInAddressBook')));
		$oSettingsListNode->AppendAttribute('show_multiple_contacts', (int) ($bGab && $bPab && CApi::GetConf('labs.contacts.allow-multiple-contacts', false)));

		$oSettingsListNode->AppendChild(new CXmlDomNode('def_skin', $oAccount->User->DefaultSkin, true));
		$oSettingsListNode->AppendChild(new CXmlDomNode('def_lang', $oAccount->User->DefaultLanguage, true));
		$oSettingsListNode->AppendChild(new CXmlDomNode('def_date_fmt', $oAccount->User->DefaultDateFormat, true));
		$oSettingsListNode->AppendAttribute('time_format', $oAccount->User->DefaultTimeFormat);

		if ($oSettings->GetConf('WebMail/EnableLastLoginNotification'))
		{
			$sLastLogin = (string) CSession::Get(EAccountSessKey::LastLogin, '');
			if (!empty($sLastLogin))
			{
				$oLastLoginDate = new CDateTime((int) $sLastLogin);
				$oLastLoginDate->FormatString = $oAccount->User->DefaultDateFormat;
				$oLastLoginDate->TimeFormat = $oAccount->User->DefaultTimeFormat;

				$oSettingsListNode->AppendChild(new CXmlDomNode('last_login',
					$oLastLoginDate->GetFormattedFullDate($oAccount->GetDefaultTimeOffset()), true));
			}
		}

		if (CApi::GetConf('labs.webmail.disable-pop3-accounts', false))
		{
			$oSettingsListNode->AppendAttribute('disable_pop3', 1);
		}

		// mini-webmail-custom
		$aCustomFields = $oAccount->User->CustomFields;
		if (isset($aCustomFields['MiniWindowWidth'], $aCustomFields['MiniWindowHeight']))
		{
			$oSettingsListNode->AppendAttribute('mini_window_width', (int) $aCustomFields['MiniWindowWidth']);
			$oSettingsListNode->AppendAttribute('mini_window_height', (int) $aCustomFields['MiniWindowHeight']);
		}
		// ---

		$oResultXml->XmlRoot->AppendChild($oSettingsListNode);
	}

	/**
	 * @param CXmlDocument $oResultXml
	 * @param array $aAccounts
	 * @param int $iCurrentId
	 * @param int $iLastId = -1
	 * @return void
	 */
	public static function BuildAccountList(CXmlDocument &$oResultXml, $aAccounts, $iCurrentId, $iLastId = -1)
	{
		$oAccountsNode = new CXmlDomNode('accounts');
		$oAccountsNode->AppendAttribute('last_id', $iLastId);
		$oAccountsNode->AppendAttribute('curr_id', $iCurrentId);

		foreach ($aAccounts as /* @var $oAccount CAccount */ $oAccount)
		{
			self::BuildAccount($oAccountsNode, $oAccount);
		}

		$oResultXml->XmlRoot->AppendChild($oAccountsNode);
	}

	/**
	 * @param CXmlDocument $oResultXml
	 * @param type $oMessageCollection
	 * @param CAccount $oAccount
	 * @param MailProcessor $oMailProcessor
	 * @param type $oFolder
	 * @param type $sLookFor
	 * @param type $iLookField
	 * @param type $iPage
	 * @param type $iSortField
	 * @param type $iSortOrder
	 * @param int $iFilter = APP_MESSAGE_LIST_FILTER_NONE
	 * @return type
	 */
	public static function BuildMessagesList(CXmlDocument &$oResultXml, $oMessageCollection, $oAccount,
		&$oMailProcessor, $oFolder, $sLookFor, $iLookField, $iPage, $iSortField, $iSortOrder, $iFilter = APP_MESSAGE_LIST_FILTER_NONE)
	{
		if (($oMessageCollection instanceof WebMailMessageCollection) && null !== $oAccount && null !== $oFolder)
		{
			$oMessagesNode = new CXmlDomNode('messages');
			$oMessagesNode->AppendAttribute('id_acct', $oAccount->IdAccount);
			$oMessagesNode->AppendAttribute('page', $iPage);
			$oMessagesNode->AppendAttribute('sort_field', $iSortField);
			$oMessagesNode->AppendAttribute('sort_order', $iSortOrder);
			$oMessagesNode->AppendAttribute('filter', $iFilter);
			$oMessagesNode->AppendAttribute('count', $oFolder->MessageCount);
			$oMessagesNode->AppendAttribute('count_new', $oFolder->UnreadMessageCount);

			if ($oMessageCollection->Error)
			{
				$oMessagesNode->AppendAttribute('error', '1');
			}

			$oMessagesNode->AppendAttribute('allow_forwarded_flag',
				(int) $oMailProcessor->IsLastSelectedFolderSupportForwardedFlag());

			$ofolderOutNode = new CXmlDomNode('folder');
			$ofolderOutNode->AppendAttribute('id', $oFolder->IdDb);
			$ofolderOutNode->AppendAttribute('type', $oFolder->Type);

			if (ConvertUtils::IsLatin($oFolder->Name))
			{
				$ofolderOutNode->AppendChild(new CXmlDomNode('name',
					ConvertUtils::ConvertEncoding($oFolder->Name, CPAGE_UTF7_Imap, CPAGE_UTF8), true));
			}
			else
			{
				$ofolderOutNode->AppendChild(new CXmlDomNode('name',
					ConvertUtils::ConvertEncoding($oFolder->Name, $oAccount->User->DefaultIncomingCharset, CPAGE_UTF8), true));
			}

			$ofolderOutNode->AppendChild(new CXmlDomNode('full_name', $oFolder->FullName, true));
			$oMessagesNode->AppendChild($ofolderOutNode);
			unset($ofolderOutNode);

			$oLookForNode = new CXmlDomNode('look_for', $sLookFor, true);
			$oLookForNode->AppendAttribute('fields', (int) $iLookField);
			$oMessagesNode->AppendChild($oLookForNode);

			$aMsgFolderFullNames = array();

			$oMaf =& MessageActionFilters::CreateInstance();

			$aMafNoReply = $oMaf->GetNoReplyEmails();
			$aMafNoReplyAll = $oMaf->GetNoReplyAllEmails();
			$aMafNoForward = $oMaf->GetNoForwardEmails();

			for ($iIndex = 0, $iCount = $oMessageCollection->Count(); $iIndex < $iCount; $iIndex++)
			{
				$oMessage =& $oMessageCollection->Get($iIndex);
				$oMessageNode = new CXmlDomNode('message');
				$oMessageNode->AppendAttribute('id', $oMessage->IdMsg);
				$oMessageNode->AppendAttribute('has_attachments', (int) $oMessage->HasAttachments(false));
				$oMessageNode->AppendAttribute('priority', $oMessage->GetPriorityStatus());
				$oMessageNode->AppendAttribute('size', $oMessage->Size);

				$iFlags = $oMessage->Flags;
				$iFlags &= ~MESSAGEFLAGS_Deleted;
				$oMessageNode->AppendAttribute('flags', $iFlags);

				$oMessageNode->AppendAttribute('charset', $oMessage->Charset);
				$oMessageNode->AppendAttribute('voice', (int) $oMessage->IsVoiceMessage());

				if (!isset($aMsgFolderFullNames[$oMessage->IdFolder]))
				{
					$aMsgFolderFullNames[$oMessage->IdFolder] = $oMailProcessor->GetFolderFullName($oMessage->IdFolder, $oAccount->IdAccount);
				}

				$oFolderMsgNode = new CXmlDomNode('folder', $aMsgFolderFullNames[$oMessage->IdFolder], true);
				$oFolderMsgNode->AppendAttribute('id', $oMessage->IdFolder);
				$oMessageNode->AppendChild($oFolderMsgNode);
				unset($oFolderMsgNode);

				$oMessageNode->AppendChild(new CXmlDomNode('from', $oMessage->GetFromAsStringForSend(), true));
				$oMessageNode->AppendChild(new CXmlDomNode('to', $oMessage->GetToAsStringForSend(), true));
				$oMessageNode->AppendChild(new CXmlDomNode('reply_to', $oMessage->GetReplyToAsStringForSend(), true));
				$oMessageNode->AppendChild(new CXmlDomNode('cc', $oMessage->GetCcAsStringForSend(), true));
				$oMessageNode->AppendChild(new CXmlDomNode('bcc', $oMessage->GetBccAsStringForSend(), true));
				$oMessageNode->AppendChild(new CXmlDomNode('subject', $oMessage->GetSubject(true), true));

				$oMessageNode->AppendAttribute('sensivity', $oMessage->GetSensitivity());

				$sFromEmail = $oMessage->GetFrom()->Email;

				$oMessageNode->AppendAttribute('no_reply', (count($aMafNoReply) > 0 && in_array($sFromEmail, $aMafNoReply)) ? '1' : '0');
				$oMessageNode->AppendAttribute('no_reply_all', (count($aMafNoReplyAll) > 0 && in_array($sFromEmail, $aMafNoReplyAll)) ? '1' : '0');
				$oMessageNode->AppendAttribute('no_forward', (count($aMafNoForward) > 0 && in_array($sFromEmail, $aMafNoForward)) ? '1' : '0');

				$oDate = $oMessage->GetDate();
				$oDate->FormatString = $oAccount->User->DefaultDateFormat;
				$oDate->TimeFormat = $oAccount->User->DefaultTimeFormat;

				$oMessageNode->AppendChild(new CXmlDomNode('short_date', $oDate->GetFormattedShortDate($oAccount->GetDefaultTimeOffset()), true));
				$oMessageNode->AppendChild(new CXmlDomNode('full_date', $oDate->GetFormattedFullDate($oAccount->GetDefaultTimeOffset()), true));

				$oMessageNode->AppendChild(new CXmlDomNode('uid', $oMessage->Uid, true));
				$oMessagesNode->AppendChild($oMessageNode);
				unset($oMessageNode, $oMessage);
			}

			$oResultXml->XmlRoot->AppendChild($oMessagesNode);
			return true;
		}

		return false;
	}

	/**
	 * @param CXmlDomNode $oAccountsNode
	 * @param object $oFolders
	 * @param CAccount $oAccount
	 * @param MailProcessor $oMailProcessor
	 * @return void
	 */
	public static function BuildFolders(CXmlDocument &$oResultXml, $oFolders, $oAccount, $oMailProcessor)
	{
		$oFoldersList = new CXmlDomNode('folders_list');
		$oFoldersList->AppendAttribute('sync', 0);
		$oFoldersList->AppendAttribute('id_acct', $oAccount->IdAccount);
		$oFoldersList->AppendAttribute('namespace', $oAccount->Namespace);

		$sGMailFolder = (CApi::GetConf('labs.webmail.gmail-fix-folders', false) &&
			'@gmail.com' === substr(strtolower($oAccount->Email), -10)) ? '[Gmail]' : '';

		if (!empty($sGMailFolder))
		{
			$oFoldersList->AppendAttribute('gmailfix', $sGMailFolder);
		}

		CAppXmlBuilder::BuildFolderList($oFoldersList, $oFolders, $oMailProcessor, $oAccount, $sGMailFolder);

		$oResultXml->XmlRoot->AppendChild($oFoldersList);
	}

	/**
	 * @param CXmlDomNode $oAccountsNode
	 * @param object $oFolders
	 * @param MailProcessor $oMailProcessor
	 * @param CAccount $oAccount
	 * @param string $sGMailFolder = ''
	 * @return void
	 */
	public static function BuildFolderList(CXmlDomNode &$oFoldersNode, $oFolders, $oMailProcessor, $oAccount, $sGMailFolder = '')
	{
		$bIgnoreSubscribeStatus = $oAccount->IsEnabledExtension(CAccount::IgnoreSubscribeStatus);
		for ($iIndex = 0, $iCount = $oFolders->Count(); $iIndex < $iCount; $iIndex++)
		{
			$oFolder =& $oFolders->Get($iIndex);

			$oFolderNode = new CXmlDomNode('folder');

			$oFolderNode->AppendAttribute('id', $oFolder->IdDb);
			$oFolderNode->AppendAttribute('id_parent', $oFolder->IdParent);
			$oFolderNode->AppendAttribute('type', $oFolder->Type);
			$oFolderNode->AppendAttribute('sync_type', $oFolder->SyncType);
			$oFolderNode->AppendAttribute('fld_order', (int) $oFolder->FolderOrder);
			$oFolderNode->AppendAttribute('noselect', (int) $oFolder->IsNoSelect());
			$oFolderNode->AppendAttribute('hide', (int) (($oFolder->IsNoSelect() || $bIgnoreSubscribeStatus) ? false : $oFolder->Hide));
			$oFolderNode->AppendAttribute('invisible', (int) ($oFolder->FullName === $sGMailFolder));

			if ($oFolder->SyncType == FOLDERSYNC_DirectMode)
			{
				$oMailProcessor->GetFolderMessageCount($oFolder);
			}

			$oFolderNode->AppendAttribute('count', $oFolder->MessageCount);
			$oFolderNode->AppendAttribute('count_new', $oFolder->UnreadMessageCount);
			$oFolderNode->AppendAttribute('size', $oFolder->Size);

			if (ConvertUtils::IsLatin($oFolder->Name))
			{
				$oFolderNode->AppendChild(new CXmlDomNode('name',
					ConvertUtils::ConvertEncoding($oFolder->Name, CPAGE_UTF7_Imap, CPAGE_UTF8), true));
			}
			else
			{
				$oFolderNode->AppendChild(new CXmlDomNode('name',
					ConvertUtils::ConvertEncoding($oFolder->Name, $oAccount->User->DefaultIncomingCharset, CPAGE_UTF8), true));
			}

			$oFolderNode->AppendChild(new CXmlDomNode('full_name', $oFolder->FullName, true));

			if ($oFolder->SubFolders != null && 0 < $oFolder->SubFolders->Count())
			{
				$oSubFoldersNode = new CXmlDomNode('folders');
				self::BuildFolderList($oSubFoldersNode, $oFolder->SubFolders, $oMailProcessor, $oAccount);
				$oFolderNode->AppendChild($oSubFoldersNode);
				unset($oSubFoldersNode);
			}

			$oFoldersNode->AppendChild($oFolderNode);
			unset($oFolderNode, $oFolder);
		}
	}

	/**
	 * @param CXmlDomNode $oAccountsNode
	 * @param CAccount $oAccount
	 * @return void
	 */
	public static function BuildAccount(CXmlDomNode &$oAccountsNode, CAccount $oAccount)
	{
		$oAccountNode = new CXmlDomNode('account');
		$oAccountNode->AppendAttribute('id', $oAccount->IdAccount);
		$oAccountNode->AppendAttribute('linked', (int) (0 < $oAccount->IdDomain));
		$oAccountNode->AppendAttribute('def_order', $oAccount->DefaultOrder);
		$oAccountNode->AppendAttribute('mail_protocol', $oAccount->IncomingMailProtocol);
		$oAccountNode->AppendAttribute('mail_inc_port', $oAccount->IncomingMailPort);
		$oAccountNode->AppendAttribute('mail_out_port', $oAccount->OutgoingMailPort);
		$oAccountNode->AppendAttribute('mail_out_auth', (int) (ESMTPAuthType::NoAuth !== $oAccount->OutgoingMailAuth));

		$oAccountNode->AppendAttribute('mails_on_server_days', $oAccount->MailsOnServerDays);
		$oAccountNode->AppendAttribute('mail_mode', $oAccount->MailMode);
		$oAccountNode->AppendAttribute('getmail_at_login', (int) $oAccount->GetMailAtLogin);
		$oAccountNode->AppendAttribute('is_internal', (int) $oAccount->IsInternal);

//      REF #581
		$oMailProcessor = null;
		$aSortSupportCache = CSession::Get('AP_SORT_SUPPORT', array());
		if (!isset($aSortSupportCache[$oAccount->IdAccount]))
		{
			$oMailProcessor = new MailProcessor($oAccount, true);
			$oMailProcessor->MailStorage->Connect();

			$aSortSupportCache[$oAccount->IdAccount] = (bool) $oMailProcessor->IsSortSupport();
			CSession::Set('AP_SORT_SUPPORT', $aSortSupportCache);
		}

		$oAccountNode->AppendAttribute('allow_sorting',
			((isset($aSortSupportCache[$oAccount->IdAccount]) && $aSortSupportCache[$oAccount->IdAccount]) ? 1 : 0)
		);

		$oAccountNode->AppendChild(new CXmlDomNode('friendly_name', $oAccount->FriendlyName, true));
		$oAccountNode->AppendChild(new CXmlDomNode('email', $oAccount->Email, true));
		$oAccountNode->AppendChild(new CXmlDomNode('mail_inc_host', $oAccount->IncomingMailServer, true));
		$oAccountNode->AppendChild(new CXmlDomNode('mail_inc_login', $oAccount->IncomingMailLogin, true));
		$oAccountNode->AppendChild(new CXmlDomNode('mail_out_host', $oAccount->OutgoingMailServer, true));

		// extensions
		if ($oAccount->IsEnabledExtension(CAccount::IgnoreSubscribeStatus) &&
			!$oAccount->IsEnabledExtension(CAccount::DisableManageSubscribe))
		{
			$oAccount->EnableExtension(CAccount::DisableManageSubscribe);
		}

		$oExtensionsNode = new CXmlDomNode('extensions');
		$aExtensions = $oAccount->GetExtensions();
		foreach ($aExtensions as $sExtensionName)
		{
			if ($oAccount->IsEnabledExtension($sExtensionName))
			{
				$oExtensionsNode->AppendAttribute($sExtensionName, 'true');
			}
		}
		$oAccountNode->AppendChild($oExtensionsNode);

		$oAccountsNode->AppendChild($oAccountNode);
		unset($oAccountNode);
	}
}