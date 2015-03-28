<?php
/*
 * login_with_wordpress.php
 *
 * @(#) $Id: login_with_wordpress.php,v 1.1 2014/04/12 09:45:03 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = true;
	$client->debug_http = true;
	$client->server = 'Wordpress';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_wordpress.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';
	
	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Wordpress Apps page https://developer.wordpress.com/apps/new/ , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client Secret');

	/* API permissions
	 */
	$client->scope = '';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://public-api.wordpress.com/rest/v1/me', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Wordpress OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->display_name), 
			' you have logged in successfully with Wordpress!</h1>';
		echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
?>
</body>
</html>
<?php
	}
	else
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
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