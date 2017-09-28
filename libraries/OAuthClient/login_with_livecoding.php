<?php
/*
 * login_with_livecoding.php
 *
 * @(#) $Id: login_with_livecoding.php,v 1.1 2016/10/10 00:03:52 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = false;
	$client->debug_http = true;
	$client->server = 'Livecoding';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_livecoding.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Livecoding application registration page https://www.livecoding.tv/developer/applications/register/ , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Client id and client_secret with Client secret. '.
			'The Callback URL must be '.$client->redirect_uri);

	/* API permissions
	 */
	$client->scope = 'read';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://www.livecoding.tv/api/user/', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		if(!$client->Finalize($success))
			$success = false;
	}
	if($client->exit)
		exit;
	if($success)
	{
?>
<!DOCTYPE html>
<html>
<head>
<title>Livecoding OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->username), 
			' you have logged in successfully with Livecoding!</h1>';
		echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
?>
</body>
</html>
<?php
	}
	else
	{
		$client->ResetAccessToken();
?>
<!DOCTYPE html>
<html>
<head>
<title>OAuth client error</title>
</head>
<body>
<h1>OAuth client error</h1>
<p>Error: <?php echo HtmlSpecialChars($client->error); ?></p>
</body>
</html>
<?php
	}

?>