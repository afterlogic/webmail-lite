<?php

// $aSieveDomains = array('*');
$aSieveDomains = array('domain1.com', 'domain2.com');

return array(
	
	'plugins.sieve-forward' => false,
	'plugins.sieve-filters' => false,
	'plugins.sieve-autoresponder' => false,
	
	'plugins.sieve-forward.options.domains' => $aSieveDomains,
	'plugins.sieve-filters.options.domains' => $aSieveDomains,
	'plugins.sieve-autoresponder.options.domains' => $aSieveDomains
	
);