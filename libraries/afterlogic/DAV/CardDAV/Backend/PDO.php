<?php

namespace afterlogic\DAV\CardDAV\Backend;

use afterlogic\DAV\Constants;

class PDO extends \Sabre\CardDAV\Backend\PDO {
	
	/**
     * Sets up the object
     *
     * @param \PDO $pdo
     * @param string $addressBooksTableName
     * @param string $cardsTableName
     */
    public function __construct(\PDO $pdo, $sDbPrefix = '') {

        $this->pdo = $pdo;
        $this->addressBooksTableName = $sDbPrefix.Constants::T_ADDRESSBOOKS;
        $this->cardsTableName = $sDbPrefix.Constants::T_CARDS;

    }
	
    /**
     * Returns all cards for a specific addressbook id.
     *
     * This method should return the following properties for each card:
     *   * carddata - raw vcard data
     *   * uri - Some unique url
     *   * lastmodified - A unix timestamp
     *
     * It's recommended to also return the following properties:
     *   * etag - A unique etag. This must change every time the card changes.
     *   * size - The size of the card in bytes.
     *
     * If these last two properties are provided, less time will be spent
     * calculating them. If they are specified, you can also ommit carddata.
     * This may speed up certain requests, especially with large cards.
     *
     * @param mixed $addressbookId
     * @return array
     */
    public function getCardsByOffset($addressbookId, $iOffset, $iRequestLimit) {

        $stmt = $this->pdo->prepare(
				'SELECT id, carddata, uri, lastmodified 
					FROM ' . $this->cardsTableName . ' 
						WHERE addressbookid = ? LIMIT ?, ?');
        $stmt->execute(array($addressbookId, $iOffset, $iRequestLimit));

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);


    }	
}

