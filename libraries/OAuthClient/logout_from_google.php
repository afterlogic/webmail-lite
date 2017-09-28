<?php
/*
 * logout_from_google.php
 *
 * @(#) $Id: logout_from_google.php,v 1.1 2017/03/18 07:27:06 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->server = 'Google';

	$client->debug = true;
	$client->debug_http = true;

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	$client->client_id = '824905321889-8r28qhhhc7prcsac3cv9va0n3722aj6b.apps.googleusercontent.com';
	$client->client_secret = 'WwsS1qvjpq5kGtdK5EB8zRs5';
	
//	$client->client_id = '2926943334-snlqnq5b6l03jkn8ks0dpq8idat8n2k8.apps.googleusercontent.com';
//	$client->client_secret = '0mGwCwAY2LOF-HLBK4IzS3OE';

	$client->client_id = '373233604549-fj6m9um8dbapjm9ecur5vkk438ktvs4u.apps.googleusercontent.com';
	$client->client_secret = 'UiGmlDSGTaaPvyhwtXsThOhc';

	if(($success = $client->Initialize()))
	{
		if(($success = $client->CheckAccessToken($redirect_url)))
		{
			$valid_token = !IsSet($redirect_url);
			if($valid_token)
				$success = $client->RevokeToken();
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
<title>Google OAuth Client Revoke Token</title>
</head>
<body>
<?php
	if($valid_token)
		echo '<h1>You are logged out: The OAuth access token was revoked successfully!</h1>';
	else
		echo '<h1>You were not logged in: There is no valid access token to revoke!</h1>';
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