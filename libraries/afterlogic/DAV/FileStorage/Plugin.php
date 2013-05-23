<?php
namespace afterlogic\DAV\FileStorage;

class Plugin extends \Sabre\DAV\ServerPlugin {

    /**
     * Url to the files
     */
    const FILES_ROOT = 'files';

    /**
     * Server class
     *
     * @var \Sabre\DAV\Server
     */
    protected $server;

    /**
     * Initializes the plugin
     *
     * @param \Sabre\DAV\Server $server
     * @return void
     */
    public function initialize(\Sabre\DAV\Server $server) {

        $this->server = $server;

    }

    /**
     * Returns a list of supported features.
     *
     * This is used in the DAV: header in the OPTIONS and PROPFIND requests.
     *
     * @return array
     */
    public function getFeatures() {

        return array('files');

    }
}
