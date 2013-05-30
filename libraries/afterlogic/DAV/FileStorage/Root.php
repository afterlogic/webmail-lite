<?php
namespace afterlogic\DAV\FileStorage;

class Root extends \Sabre\DAVACL\AbstractPrincipalCollection {
	
	/**
     * Creates the object
     *
     * This object must be passed the principal backend. This object will
     * filter all principals from a specified prefix ($principalPrefix). The
     * default is 'principals', if your principals are stored in a different
     * collection, override $principalPrefix
     *
     *
     * @param \Sabre\DAVACL\PrincipalBackend\BackendInterface $principalBackend
	 * @param string $sPrivateFoldersRoot
     * @param string $principalPrefix
     */
    public function __construct(\Sabre\DAVACL\PrincipalBackend\BackendInterface $principalBackend, $path, $principalPrefix = 'principals') {

        $this->principalBackend = $principalBackend;
		$this->path = $path;
        $this->principalPrefix = $principalPrefix;

    }
	
	/**
     * Returns the name of the node
     *
     * @return string
     */
    public function getName() {

        return Plugin::FILES_ROOT;

    }	
	
    /**
     * This method returns a node for a principal.
     *
     * The passed array contains principal information, and is guaranteed to
     * at least contain a uri item. Other properties may or may not be
     * supplied by the authentication backend.
     *
     * @param array $principal
     * @return \Sabre\DAV\INode
     */
    public function getChildForPrincipal(array $principal) {

		return new \afterlogic\DAV\FileStorage\Directory($this->path, $principal['uri']);

    }	
	
}
