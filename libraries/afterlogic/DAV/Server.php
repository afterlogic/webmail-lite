<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

namespace afterlogic\DAV;

class_exists('CApi') || die();

class Server extends \Sabre\DAV\Server
{
	/**
	 * @var \CApiCapabilityManager
	 */
	private $oApiCapaManager;
	
	public $oAccount = null;

	public function __construct($baseUri = '/')
	{
		$this->debugExceptions = false;
		self::$exposeVersion = false;

		$this->setBaseUri($baseUri);
		date_default_timezone_set('GMT');

		if (\CApi::GetPDO())
		{
			/* Authentication Plugin */
			$authPlugin = new Auth\Plugin(Backends::Auth(), 'SabreDAV');
			$this->addPlugin($authPlugin);

			/* Logs Plugin */
			$this->addPlugin(new Logs\Plugin());

			/* DAV ACL Plugin */
			$aclPlugin = new \Sabre\DAVACL\Plugin();
			$mAdminPrincipal = \CApi::GetConf('labs.dav.admin-principal', false);
			$aclPlugin->hideNodesFromListings = true;
			if ($mAdminPrincipal !== false)
			{
				$aclPlugin->adminPrincipals = array($mAdminPrincipal);
			}
			$this->addPlugin($aclPlugin);

			$oPrincipalColl = new \Sabre\DAVACL\PrincipalCollection(Backends::Principal());
			$oPrincipalColl->disableListing = true;

			/* Directory tree */
			$aTree = array(
				new CardDAV\AddressBookRoot(Backends::Principal(), Backends::Carddav()),
				new CalDAV\CalendarRootNode(Backends::Principal(), Backends::Caldav()),
				new CardDAV\GAddressBooks($authPlugin, 'gab', Constants::GLOBAL_CONTACTS), /* Global Address Book */
			);

			$this->oApiCapaManager = \CApi::Manager('capability');

			/* Files folder */
			if ($this->oApiCapaManager->IsFilesSupported())
			{
				$bErrorCreateDir = false;
				
				/* Public files folder */
				$publicDir = \CApi::DataPath() . Constants::FILESTORAGE_PATH_ROOT;
				if (!file_exists($publicDir))
				{
					if (!@mkdir($publicDir))
					{
						$bErrorCreateDir = true;
					}
				}

				$publicDir .= Constants::FILESTORAGE_PATH_CORPORATE;
				if (!file_exists($publicDir))	
				{
					if (!@mkdir($publicDir))
					{
						$bErrorCreateDir = true;
					}
				}

				$privateDir = \CApi::DataPath() . Constants::FILESTORAGE_PATH_ROOT . 
						Constants::FILESTORAGE_PATH_PRIVATE;
				if (!file_exists($privateDir))
				{
					if (!@mkdir($privateDir))
					{
						$bErrorCreateDir = true;
					}
				}
				$sharedDir = \CApi::DataPath() . Constants::FILESTORAGE_PATH_ROOT . 
						Constants::FILESTORAGE_PATH_SHARED;
				if (!file_exists($sharedDir))
				{
					if (!@mkdir($sharedDir))
					{
						$bErrorCreateDir = true;
					}
				}
				
				if ($bErrorCreateDir)
				{
					throw new \Sabre\DAV\Exception('Can\'t create directory in ' . \CApi::DataPath() . Constants::FILESTORAGE_PATH_ROOT , 500);
				}

				$aFilesTree = array(
					new FS\PrivateRoot($authPlugin, $privateDir),
					new FS\PublicRoot($authPlugin, $publicDir)
				);
				$bSharedFiles = \CApi::GetConf('labs.files-sharing', false);
				if ($bSharedFiles)
				{
					array_push($aFilesTree, new FS\SharedRoot($authPlugin, $sharedDir));
				}
				
				array_push($aTree, new \Sabre\DAV\SimpleCollection('files', $aFilesTree));
				
				$this->addPlugin(new FS\Plugin());

				// Automatically guess (some) contenttypes, based on extesion
				$this->addPlugin(new \Sabre\DAV\Browser\GuessContentType());				
			}
			
			array_push($aTree, $oPrincipalColl);

			/* Initializing server */
			parent::__construct($aTree);
			$this->httpResponse->setHeader("X-Server", Constants::DAV_SERVER_NAME);
			
			/* Reminders Plugin */
			$this->addPlugin(new Reminders\Plugin(Backends::Reminders()));

			/* Contacts Plugin */
			$this->addPlugin(new Contacts\Plugin());

			if ($this->oApiCapaManager->IsMobileSyncSupported())
			{
				/* CalDAV Plugin */
				$this->addPlugin(new \Sabre\CalDAV\Plugin());

				/* CardDAV Plugin */
				$this->addPlugin(new \Sabre\CardDAV\Plugin());
				
				/* ICS Export Plugin */
				$this->addPlugin(new \Sabre\CalDAV\ICSExportPlugin());

				/* VCF Export Plugin */
				$this->addPlugin(new \Sabre\CardDAV\VCFExportPlugin());
			}

			/* Calendar Sharing Plugin */
			$this->addPlugin(new \Sabre\CalDAV\SharingPlugin());

			/* HTML Frontend Plugin */
			if (\CApi::GetConf('labs.dav.use-browser-plugin', false) !== false)
			{
				$this->addPlugin(new \Sabre\DAV\Browser\Plugin(true, false));
			}

			/* Locks Plugin */
//			$this->addPlugin(new \Sabre\DAV\Locks\Plugin($this->lockBackend));

			$this->subscribeEvent('beforeGetProperties', array($this, 'beforeGetProperties'), 90);
		}
    }
	
	public function getAccount()
	{
		if (null === $this->oAccount)
		{
			$authPlugin = $this->getPlugin('auth');
			if (isset($authPlugin))
			{
				$sUser = $authPlugin->getCurrentUser();

				if (!empty($sUser))
				{
					$apiUsersManager = \CApi::Manager('users');
					$this->oAccount = $apiUsersManager->GetAccountOnLogin($sUser);
				}
			}
		}
		return $this->oAccount;
	}	

	/**
	 * @param string $path
	 * @param \Sabre\DAV\INode $node
	 * @param array $requestedProperties
	 * @param array $returnedProperties
	 * @return void
	 */
	public function beforeGetProperties($path, \Sabre\DAV\INode $node, &$requestedProperties, &$returnedProperties)
	{
		$oAccount = $this->getAccount();
		if (isset($oAccount)/* && $node->getName() === 'root'*/)
		{
			$carddavPlugin = $this->getPlugin('Sabre\CardDAV\Plugin');
			if (null !== $oAccount && isset($carddavPlugin) &&
				$this->oApiCapaManager->IsGlobalContactsSupported($oAccount, false))
			{
				$carddavPlugin->directories = array('gab');
			}
		}
	}
}