<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

namespace afterlogic\DAV;

class_exists('CApi') || die();

class Server extends \Sabre\DAV\Server
{
	protected $authBackend;
	protected $principalBackend;
	protected $caldavBackend;
	protected $carddavBackend;
	protected $lockBackend;
	protected $remindersBackend;
	protected $delegatesBackend;

	public $oSettings;
	public $oApiCollaborationManager;

	public static $UseDigest;

	public function GetAuthBackend()
	{
		return $this->authBackend;
	}

	public function GetPrincipalBackend()
	{
		return $this->principalBackend;
	}

	public function GetCaldavBackend()
	{
		return $this->caldavBackend;
	}

	public function GetCarddavBackend()
	{
		return $this->carddavBackend;
	}

	public function GetLockBackend()
	{
		return $this->lockBackend;
	}

	public function GetRemindersBackend()
	{
		return $this->remindersBackend;
	}

	public function GetDelegatesBackend()
	{
		return $this->delegatesBackend;
	}

	public function __construct($baseUri = '/')
	{
		$this->oAccount = null;

		self::$UseDigest = true;
		$this->debugExceptions = false;

		$this->setBaseUri($baseUri);
		date_default_timezone_set('GMT');

		/* Get WebMail Settings */
		$this->oSettings =& \CApi::GetSettings();

		$sDbPrefix = $this->oSettings->GetConf('Common/DBPrefix');

		/* Database */
		$oPdo = \CApi::GetPDO();

		if ($oPdo)
		{
			$this->authBackend = Auth\Backend\Factory::getBackend($oPdo, $sDbPrefix);
			$this->principalBackend = new Principal\Backend\PDO($oPdo, $sDbPrefix);
			$this->caldavBackend = new CalDAV\Backend\PDO($oPdo, $sDbPrefix);
			$this->carddavBackend = new CardDAV\Backend\PDO($oPdo, $sDbPrefix);
			$this->lockBackend = new Locks\Backend\PDO($oPdo, $sDbPrefix);
			$this->delegatesBackend = new Delegates\Backend\PDO($oPdo, $sDbPrefix);
			$this->remindersBackend = new Reminders\Backend\PDO($oPdo, $sDbPrefix);

			$this->oApiCollaborationManager = \CApi::Manager('collaboration');

			/* Authentication Plugin */
			$authPlugin = new \Sabre\DAV\Auth\Plugin($this->authBackend, 'SabreDAV');
			$this->addPlugin($authPlugin);

			/* Logs Plugin */
			$logsPlugin = new Logs\Plugin();
			$this->addPlugin($logsPlugin);

			$pubCollection = array();

			/* Global Address Book */
			$pubCollection[] = new CardDAV\GAddressBooks($authPlugin, 'globals',
					Constants::GLOBAL_CONTACTS);

			/* Public files folder */
			$pubDir = \CApi::DataPath() . '/files';
			if (!file_exists($pubDir))
			{
				mkdir($pubDir);
			}
			$pubDir .= '/public';
			if (!file_exists($pubDir))
			{
				mkdir($pubDir);
			}
			$pubCollection[] = new FileStorage\PublicDirectory($pubDir, 'Files');

			/* Directory tree */
			$aTree = array();

			$aTree[] = new \Sabre\CardDAV\AddressBookRoot($this->principalBackend, $this->carddavBackend);
			$aTree[] = new \Sabre\CalDAV\CalendarRootNode($this->principalBackend, $this->caldavBackend);
			if ($this->oApiCollaborationManager && $this->oApiCollaborationManager->IsCalendarSharingSupported())
			{
				$aTree[] = new Delegates\Root($oPdo, $this->principalBackend, $this->caldavBackend, true);
			}
			$oPrincipalCollection = new \Sabre\DAVACL\PrincipalCollection($this->principalBackend);
			$oPrincipalCollection->disableListing = true;
			$aTree[] = $oPrincipalCollection;
			$aTree[] = new \Sabre\DAV\SimpleCollection('public', $pubCollection);

			/* Private files folder */
			if (\CApi::GetConf('labs.dav.use-files', false) !== false)
			{
				$sPath = \CApi::DataPath() . '/files/private';
				if (!file_exists($sPath))
				{
					mkdir($sPath);
				}
				$aTree[] = new FileStorage\Root($this->principalBackend, $sPath);
			}

			/* Initializing server */
			parent::__construct($aTree);
			$this->httpResponse->setHeader("X-Server", "AfterlogicDAVServer");

			/* DAV ACL Plugin */
			$aclPlugin = new \Sabre\DAVACL\Plugin();
			$mAdminPrincipal = \CApi::GetConf('labs.dav.admin-principal', false);
			if ($mAdminPrincipal !== false)
			{
				$aclPlugin->adminPrincipals = array($mAdminPrincipal);
			}
			$aclPlugin->hideNodesFromListings = true;
			$this->addPlugin($aclPlugin);

			/* Reminders Plugin */
			$this->addPlugin(new Reminders\Plugin($this->remindersBackend));

			$oApiDavManager = \CApi::Manager('dav');
			if (isset($oApiDavManager) && $oApiDavManager->IsMobileSyncEnabled())
			{
				/* CalDAV Plugin */
				$this->addPlugin(new \Sabre\CalDAV\Plugin());

				/* CardDAV Plugin */
				$this->addPlugin(new \Sabre\CardDAV\Plugin());
			}

			/* Calendar Delegation Plugin */
			$this->addPlugin(new Delegates\Plugin($this->delegatesBackend));

			/* Export Plugins */
			if (\CApi::GetConf('labs.dav.use-export-plugin', false) !== false)
			{
				$this->addPlugin(new \Sabre\CalDAV\ICSExportPlugin());
				$this->addPlugin(new \Sabre\CardDAV\VCFExportPlugin());
			}

			/* HTML Frontend Plugin */
			if (\CApi::GetConf('labs.dav.use-browser-plugin', false) !== false)
			{
				$this->addPlugin(new \Sabre\DAV\Browser\Plugin(true, true));
			}

			/* Locks Plugin */
//			$this->addPlugin(new \Sabre\DAV\Locks\Plugin($this->lockBackend));

			$this->subscribeEvent('beforeGetProperties', array($this, 'beforeGetProperties'), 90);
		}
    }

	/**
	 * @param string $path
	 * @param \Sabre\DAV\INode $node
	 * @param array $requestedProperties
	 * @param array $returnedProperties
	 * @return void
	 */
	function beforeGetProperties($path, \Sabre\DAV\INode $node, &$requestedProperties, &$returnedProperties)
	{
		$authPlugin = $this->getPlugin('auth');
		if (isset($authPlugin))
		{
			$sUser = $authPlugin->getCurrentUser();
			if (!empty($sUser))
			{
				if (null === $this->oAccount)
				{
					$apiUsersManager = \CApi::Manager('users');
					$this->oAccount = $apiUsersManager->GetAccountOnLogin($sUser);
				}

				$carddavPlugin = $this->getPlugin('Sabre\CardDAV\Plugin');
				if (null !== $this->oAccount && $this->oAccount->User->AllowContacts &&
						$this->oAccount->User->GetCapa('GAB') &&
						isset($carddavPlugin) &&
						$this->oApiCollaborationManager &&
						$this->oApiCollaborationManager->IsContactsGlobalSupported())
				{
					$carddavPlugin->directories = array('public/globals');
				}
			}
		}
	}
}