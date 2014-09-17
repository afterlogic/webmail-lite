<?php

namespace afterlogic\DAV\CalDAV;

class CalendarRootNode extends \Sabre\CalDAV\CalendarRootNode{

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

        return new UserCalendars($this->caldavBackend, $principal);

    }

}
