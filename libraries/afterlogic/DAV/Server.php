<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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

	/**
	 * @return \afterlogic\DAV\Server
	 */
	public static function NewInstance($baseUri = '/')
	{
		return new self($baseUri);
	}
	
	public function __construct($baseUri = '/')
	{
		$this->debugExceptions = false;
		self::$exposeVersion = false;

		$this->setBaseUri($baseUri);
		date_default_timezone_set('GMT');

		if (\CApi::GetPDO())
		{
			/* Authentication Plugin */
			$this->addPlugin(new \Sabre\DAV\Auth\Plugin(Backend::Auth(), 'SabreDAV'));

			/* Logs Plugin */
			$this->addPlugin(new Logs\Plugin());

			/* DAV ACL Plugin */
			$aclPlugin = new \Sabre\DAVACL\Plugin();
			$aclPlugin->hideNodesFromListings = true;
			$aclPlugin->defaultUsernamePath = Constants::PRINCIPALS_PREFIX;
			
			$mAdminPrincipal = \CApi::GetConf('labs.dav.admin-principal', false);
			if ($mAdminPrincipal !== false)
			{
				$aclPlugin->adminPrincipals = array(Constants::PRINCIPALS_PREFIX . '/' . $mAdminPrincipal);
			}
			$this->addPlugin($aclPlugin);

			$bIsOwncloud = false;
			/* Directory tree */
			$aTree = array(
				($bIsOwncloud) ? new CardDAV\AddressBookRoot(Backend::Principal(), Backend::GetBackend('carddav-owncloud')) : new CardDAV\AddressBookRoot(Backend::Principal(), Backend::Carddav()),
				new CalDAV\CalendarRootNode(Backend::Principal(), Backend::Caldav()),
				new CardDAV\GAddressBooks('gab', Constants::GLOBAL_CONTACTS), /* Global Address Book */
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

				$personalDir = \CApi::DataPath() . Constants::FILESTORAGE_PATH_ROOT . 
						Constants::FILESTORAGE_PATH_PERSONAL;
				if (!file_exists($personalDir))
				{
					if (!@mkdir($personalDir))
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
					new FS\RootPersonal($personalDir)
				);
				if ($this->oApiCapaManager->IsCollaborationSupported())
				{
					array_push($aFilesTree, new FS\RootPublic($publicDir));
				}
				if (\CApi::GetConf('labs.files-sharing', false))
				{
					array_push($aFilesTree, new FS\RootShared($sharedDir));
				}
				
				array_push($aTree, new \Sabre\DAV\SimpleCollection('files', $aFilesTree));
				
				$this->addPlugin(new FS\Plugin());

				// Automatically guess (some) contenttypes, based on extesion
				$this->addPlugin(new \Sabre\DAV\Browser\GuessContentType());				
			}
			
			$oPrincipalColl = new \Sabre\DAVACL\PrincipalCollection(Backend::Principal());
//			$oPrincipalColl->disableListing = true;

			array_push($aTree, $oPrincipalColl);

			/* Initializing server */
			parent::__construct($aTree);
			$this->httpResponse->setHeader("X-Server", Constants::DAV_SERVER_NAME);
			
			/* Reminders Plugin */
			$this->addPlugin(new Reminders\Plugin(Backend::Reminders()));

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
				$this->addPlugin(new \Sabre\DAV\Browser\Plugin(false, false));
			}

			/* Locks Plugin */
//			$this->addPlugin(new \Sabre\DAV\Locks\Plugin(new \Sabre\DAV\Locks\Backend\File(\CApi::DataPath() . '/locks.dat')));

			$this->subscribeEvent('beforeGetProperties', array($this, 'beforeGetProperties'), 90);
		}
    }
	
	public function getAccount()
	{
		if (null === $this->oAccount)
		{
			$sUser = \afterlogic\DAV\Auth\Backend::getInstance()->getCurrentUser();

			if (!empty($sUser))
			{
				$this->oAccount = \afterlogic\DAV\Utils::GetAccountByLogin($sUser);
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