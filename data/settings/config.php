<?php

$aSieveDomains = array('imap.domain1.com', 'imap.domain2.com');

return array(
	
	'sieve' => false,
	'sieve.autoresponder' => true,
	'sieve.forward' => true,
	'sieve.filters' => true,
	'sieve.config.host' => '',
	'sieve.config.port' => 2000,
	'sieve.config.filters-folder-charset' => 'utf-8', // [utf7-imap, utf-8]
	'sieve.config.domains' => $aSieveDomains,

	'links.importing-contacts' => 'http://www.afterlogic.com/wiki/Importing_contacts_(WebMail_Lite)'
	
);