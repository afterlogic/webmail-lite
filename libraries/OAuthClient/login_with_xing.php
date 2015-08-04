<?php
/*
 * login_with_xing.php
 *
 * @(#) $Id: login_with_xing.php,v 1.2 2013/07/31 11:48:04 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = 0;
	$client->debug_http = 1;
	$client->server = 'XING';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_xing.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to XING My Apps page https://dev.xing.com/applications , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret.');

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://api.xing.com/v1/users/me', 
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
<title>XING OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->users[0]->display_name), 
			' you have logged in successfully with XING!</h1>';
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