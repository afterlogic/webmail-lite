<?php

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
		$this->oApiContactsManager = \CApi::Manager('maincontacts');
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

			if ($sAddressBookName === \afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME)
			{
				return false;	
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
			$sAddressBookName = basename(dirname($path));

			if ($sAddressBookName !== \afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME)
			{
				$oAccount = $this->server->getAccount();
				if (isset($oAccount))
				{
					$iUserId = $oAccount->IdUser;
					$oContact = null;
					$oContact = $this->oApiContactsManager->GetContactByStrId($iUserId, basename($path));

					if ($oContact)
					{
						if ($sAddressBookName !== \afterlogic\DAV\Constants::ADDRESSBOOK_COLLECTED_NAME)
						{
							$this->oApiContactsManager->DeleteContacts($iUserId, array($oContact->IdContact));		
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
					$oContact->InitFromVCardStr($oAccount->IdUser, $node->get(), $sFileName);
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

					$sFileName = $node->getName();
					$oContactDb = $this->oApiContactsManager->GetContactByStrId($iUserId, $sFileName);

					$oContact = new \CContact();
					$oContact->InitFromVCardStr($iUserId, $node->get(), $sFileName);
					$oContact->IdContact = $oContactDb->IdContact;

					$this->oApiContactsManager->UpdateContact($oContact);
				}
			}
		}
	}

}

