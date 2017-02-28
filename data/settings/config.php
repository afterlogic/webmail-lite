<?php

$aSieveDomains = array('127.0.0.1', 'localhost');

return array(
	
	'sieve' => false,
	'sieve.autoresponder' => true,
	'sieve.forward' => true,
	'sieve.filters' => true,
	'sieve.config.host' => '',
	'sieve.config.port' => 2000,
	'sieve.config.filters-folder-charset' => 'utf-8', // [utf7-imap, utf-8]
	'sieve.config.domains' => $aSieveDomains,

	'links.importing-contacts' => 'http://www.afterlogic.org/docs/webmail-lite/frequently-asked-questions/importing-contacts',
	
	'plugins.external-services' => true,
	'plugins.external-services.connectors' => array(
		'google',
		'dropbox',
		'facebook',
		'twitter'
	),	
);