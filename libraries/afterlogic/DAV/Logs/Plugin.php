<?php

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
    	\CApi::Log('-------------------------------------------', \ELogLevel::Full, 'sabredav-');

    	return;
    }
	
	function afterCreateFile($path, \Sabre\DAV\ICollection $parent)
	{
    	\CApi::Log($path, \ELogLevel::Full, 'sabredav-');
		$node = $parent->getChild(basename($path));
    	\CApi::LogObject($node->get(), \ELogLevel::Full, 'sabredav-');
    	\CApi::Log('-------------------------------------------', \ELogLevel::Full, 'sabredav-');
	}

	function afterWriteContent($path, \Sabre\DAV\IFile $node)
	{
    	\CApi::Log($path, \ELogLevel::Full, 'sabredav-');
    	\CApi::LogObject($node->get(), \ELogLevel::Full, 'sabredav-');
    	\CApi::Log('-------------------------------------------', \ELogLevel::Full, 'sabredav-');
	}

}

