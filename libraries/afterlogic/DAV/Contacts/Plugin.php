<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\Contacts;

class Plugin extends \Sabre\DAV\ServerPlugin
{

    /**
     * Reference to main server object
     *
     * @var \Sabre\DAV\Server
     */
    private $server;
	
	private $oApiContactsManager;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
		$this->oApiContactsManager = \CApi::Manager('contactsmain');
	}

    public function initialize(\Sabre\DAV\Server $server)
    {
        $this->server = $server;
		$this->server->subscribeEvent('beforeUnbind', array($this, 'beforeUnbind'),30);
        $this->server->subscribeEvent('afterUnbind', array($this, 'afterUnbind'),30);
		$this->server->subscribeEvent('afterWriteContent', array($this, 'afterWriteContent'), 30);
		$this->server->subscribeEvent('afterCreateFile', array($this, 'afterCreateFile'), 30);
    }

    /**
     * Returns a plugin name.
     *
     * Using this name other plugins will be able to access other plugins
     * using \Sabre\DAV\Server::getPlugin
     *
     * @return string
     */
    public function getPluginName()
    {
        return 'contacts';
    }

    /**
     * @param string $path
     * @throws \Sabre\DAV\Exception\NotAuthenticated
     * @return bool
     */
    public function beforeUnbind($path)
    {
		if ('sabredav' !== \CApi::GetManager()->GetStorageByType('contacts'))
		{
			$sAddressBookName = basename(dirname($path));

			if (basename(dirname($path)) === \afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME)
			{
//				return false;	
			}
		}		
		return true;
	}    
	
	/**
     * @param string $path
     * @throws \Sabre\DAV\Exception\NotAuthenticated
     * @return bool
     */
    public function afterUnbind($path)
    {
		if ('sabredav' !== \CApi::GetManager()->GetStorageByType('contacts'))
		{
			if (true)//(basename(dirname($path)) !== \afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME)
			{
				$oAccount = $this->server->getAccount();
				if (isset($oAccount))
				{
					$oContact = $this->oApiContactsManager->GetContactByStrId($oAccount->IdUser, basename($path));

					if ($oContact)
					{
						if (true)//($sAddressBookName !== \afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME)
						{
							$this->oApiContactsManager->DeleteContacts($oAccount->IdUser, array($oContact->IdContact));		
						}
					}
				}
			}
		}		
		return true;
	}
	
	function afterCreateFile($path, \Sabre\DAV\ICollection $parent)
	{
		if ('sabredav' !== \CApi::GetManager()->GetStorageByType('contacts'))
		{
			$sFileName = basename($path);
			$node = $parent->getChild($sFileName);
			if ($node instanceof \Sabre\CardDAV\ICard)
			{
				$oAccount = $this->server->getAccount();
				if (isset($oAccount))
				{
					$oContact = new \CContact();
					$oContact->InitFromVCardStr($oAccount->IdUser, $node->get());
					$oContact->IdContactStr = $sFileName;
					$this->oApiContactsManager->CreateContact($oContact);		
				}
			}
		}
	}

	function afterWriteContent($path, \Sabre\DAV\IFile $node)
	{
		if ('sabredav' !== \CApi::GetManager()->GetStorageByType('contacts'))
		{
			if ($node instanceof \Sabre\CardDAV\ICard)
			{
				$oAccount = $this->server->getAccount();
				if (isset($oAccount))
				{
					$iUserId = $oAccount->IdUser;
					$iTenantId = ($node instanceof \afterlogic\DAV\CardDAV\SharedCard) ? $oAccount->IdTenant : null;

					$sContactFileName = $node->getName();
					$oContactDb = $this->oApiContactsManager->GetContactByStrId($iUserId, $sContactFileName, $iTenantId);
					if (!isset($oContactDb))
					{
						$oDavManager = \CApi::Manager('dav');
						$oVCard = $oDavManager ? $oDavManager->VObjectReaderRead($node->get()) : null;
						if ($oVCard && $oVCard->UID)
						{
							$oContactDb = $this->oApiContactsManager->GetContactByStrId($iUserId, (string)$oVCard->UID . '.vcf', $iTenantId);							
						}
					}
					
					$oContact = new \CContact();
					$oContact->InitFromVCardStr($iUserId, $node->get());
					$oContact->IdContactStr = $sContactFileName;
					$oContact->IdTenant = $iTenantId;
					
					if (isset($oContactDb))
					{
						$oContact->IdContact = $oContactDb->IdContact;
						$oContact->IdDomain = $oContactDb->IdDomain;
						$oContact->SharedToAll = !!$oContactDb->SharedToAll;
						
						$this->oApiContactsManager->UpdateContact($oContact);
					}
					else
					{
						$this->oApiContactsManager->CreateContact($oContact);		
					}
				}
			}
		}
	}

}

