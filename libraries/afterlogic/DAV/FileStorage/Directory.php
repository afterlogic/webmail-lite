<?php
namespace afterlogic\DAV\FileStorage;

use \afterlogic\DAV\Constants;

class Directory extends \Sabre\DAV\FS\Directory implements \Sabre\DAVACL\IACL {

    /**
     * Principal information
     *
     * @var string
     */
    protected $principalUri;

	/**
     * Constructor
     *
     * @param string $path
     * @param mixed $principalUri
     */
    public function __construct($path, $principalUri) {

        $this->principalUri = $principalUri;
		$this->path = $path . '/' . $this->getName();
		if (!file_exists($this->path))
		{
			mkdir($this->path);
		}
    }

    /**
     * Returns the name of this object
     *
     * @return string
     */
    public function getName() {

        list(,$name) = \Sabre\DAV\URLUtil::splitPath($this->principalUri);
        return $name;

    }

    /**
     * Updates the name of this object
     *
     * @param string $name
     * @return void
     */
    public function setName($name) {

        throw new \Sabre\DAV\Exception\Forbidden();

    }

    /**
     * Deletes this object
     *
     * @return void
     */
    public function delete() {

        throw new \Sabre\DAV\Exception\Forbidden();

    }

    /**
     * Returns the owner principal
     *
     * This must be a url to a principal, or null if there's no owner
     *
     * @return string|null
     */
    public function getOwner() {

        return $this->principalUri;

    }

    /**
     * Returns a group principal
     *
     * This must be a url to a principal, or null if there's no owner
     *
     * @return string|null
     */
    public function getGroup() {

        return null;

    }

    /**
     * Returns a list of ACE's for this node.
     *
     * Each ACE has the following properties:
     *   * 'privilege', a string such as {DAV:}read or {DAV:}write. These are
     *     currently the only supported privileges
     *   * 'principal', a url to the principal who owns the node
     *   * 'protected' (optional), indicating that this ACE is not allowed to
     *      be updated.
     *
     * @return array
     */
    public function getACL() {

        return array(
            array(
                'privilege' => '{DAV:}read',
                'principal' => $this->principalUri,
                'protected' => true,
            ),
            array(
                'privilege' => '{DAV:}write',
                'principal' => $this->principalUri,
                'protected' => true,
            ),

        );


    }

    /**
     * Updates the ACL
     *
     * This method will receive a list of new ACE's.
     *
     * @param array $acl
     * @return void
     */
    public function setACL(array $acl) {

        throw new \Sabre\DAV\Exception\MethodNotAllowed('Changing ACL is not yet supported');

    }

    /**
     * Returns the list of supported privileges for this node.
     *
     * The returned data structure is a list of nested privileges.
     * See \Sabre\DAVACL\Plugin::getDefaultSupportedPrivilegeSet for a simple
     * standard structure.
     *
     * If null is returned from this method, the default privilege set is used,
     * which is fine for most common usecases.
     *
     * @return array|null
     */
    public function getSupportedPrivilegeSet() {

        return null;

    }
	
    /**
     * Returns available diskspace information
     *
     * @return array
     */
    public function getQuotaInfo() {

        $Size = api_Utils::getDirectorySize($this->path);
		return array(
            $Size,
            Constants::FILESTORAGE_QUOTA - $Size
            );

    }
	
	

}
