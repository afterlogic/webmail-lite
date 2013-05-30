<?php

namespace afterlogic\DAV\Delegates;

class Plugin extends \Sabre\DAV\ServerPlugin
{

    /**
     * Reference to main server object 
     * 
     * @var \Sabre\DAV\Server 
     */
    private $server;
    
	/**
     * cacheBackend 
     * 
     * @var Backend\AbstractBackend
     */
    private $delegatesBackend;	
    
	/**
     * __construct 
     * 
     * @return void
     */
    public function __construct(Backend\AbstractBackend $delegatesBackend)
    {
		$this->delegatesBackend = $delegatesBackend;
	}

    public function initialize(\Sabre\DAV\Server $server)
    {
        $this->server = $server;
		$this->server->subscribeEvent('beforeGetProperties', array($this, 'beforeGetProperties'), 90);
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
        return 'delegates';
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
		if ($node instanceof Principal)
		{
			$calHome = '{' . \Sabre\CalDAV\Plugin::NS_CALDAV . '}calendar-home-set';
			if (($index = array_search($calHome,$requestedProperties)) !== false)
			{
				$returnedProperties[200][$calHome] = new \Sabre\DAV\Property\Href(dirname($path) . '/');
				unset($requestedProperties[$index]);
			}
		}
	}
	
	public function getDelegatesByCalendar($calendarUri)
	{
		$calendarUri = basename($calendarUri);
		return $this->delegatesBackend->getDelegatesByCalendar($calendarUri);
	}

}

