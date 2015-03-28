<?php
/*
 * login_with_buffer.php
 *
 * @(#) $Id: login_with_buffer.php,v 1.1 2014/03/17 09:45:08 mlemos Exp $
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
	$client->server = 'Buffer';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_buffer.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';
	
	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please create an application in Buffer Apps page '.
			'https://bufferapp.com/developers/apps/create '.
			' and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client'.
			' Secret');

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
					'https://api.bufferapp.com/1/user.json', 
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
<title>Buffer OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->name), 
			' you have logged in successfully with Buffer!</h1>';
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