<?php
/*
 * google_contacts_api_php_example.php
 *
 * @(#) $Id: google_contacts_api_php_example.php,v 1.1 2017/03/15 12:51:49 mlemos Exp $
 *
 */

// Include the necessary class files directly or
// vendor/autoload.php if you used composer to install the package.
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->server = 'Google';
	$client->debug = false;
	$client->debug_http = true;

	$client->redirect_uri = 'https://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/google_contacts_api_php_example.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Google APIs console page '.
			'http://code.google.com/apis/console in the API access tab, '.
			'create a new client ID, and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client Secret. '.
			'The callback URL must be '.$client->redirect_uri.' but make sure '.
			'the domain is valid and can be resolved by a public DNS.');

	/* API permissions
	 */
	$client->scope = 'https://www.googleapis.com/auth/contacts.readonly';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->authorization_error))
			{
				$client->error = $client->authorization_error;
				$success = false;
			}
			elseif(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://people.googleapis.com/v1/people/me/connections?fields=connections(emailAddresses%2Cnames)',
					'GET', array(), array('FailOnAccessError'=>true), $contacts);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
?>
<!DOCTYPE html>
<html>
<head>
<title>Google Contacts API PHP Example</title>
</head>
<body>
<h1>Google Contacts API PHP Example</h1>
<?php
		echo '<pre>';
		foreach($contacts->connections as $contact)
		{
			echo htmlspecialchars($contact->names[0]->displayName), "\n";
		}
		echo '</pre>';
?>
</body>
</html>
<?php
	}
	else
	{
?>
<!DOCTYPE html>
<html>
<head>
<title>OAuth client error</title>
</head>
<body>
<h1>OAuth client error</h1>
<pre>Error: <?php echo HtmlSpecialChars($client->error); ?></pre>
</body>
</html>
<?php
	}

?>