<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\Logs;

class Plugin extends \Sabre\DAV\ServerPlugin
{
	/**
     * Reference to main server object
     *
     * @var \Sabre\DAV\Server
     */
    private $server;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function initialize(\Sabre\DAV\Server $server)
    {
        $this->server = $server;
        $this->server->subscribeEvent('beforeMethod', array($this, 'beforeMethod'),30);
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
        return 'logs';
    }

    /**
     * This method is called before any HTTP method, but after authentication.
     *
     * @param string $sMethod
     * @param string $path
     * @throws \Sabre\DAV\Exception\NotAuthenticated
     * @return bool
     */
    public function beforeMethod($sMethod, $path)
    {
		$aHeaders = $this->server->httpRequest->getHeaders();

    	\CApi::Log($sMethod . ' ' . $path, \ELogLevel::Full, 'sabredav-');
    	\CApi::LogObject($aHeaders, \ELogLevel::Full, 'sabredav-');

		$bLogBody = (bool) \CApi::GetConf('labs.dav.log-body', false);
		if ($bLogBody)
		{
			$body = $this->server->httpRequest->getBody(true); 		
			$this->server->httpRequest->setBody($body);
			\CApi::LogObject($body, \ELogLevel::Full, 'sabredav-');
		}
    	
		\CApi::Log('-------------------------------------------', \ELogLevel::Full, 'sabredav-');

    	return;
    }
}

