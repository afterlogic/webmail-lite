<?php
/*
 * login_with_rdio.php
 *
 * @(#) $Id: login_with_rdio.php,v 1.1 2014/03/17 09:45:08 mlemos Exp $
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
	$client->server = 'Rdio';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_rdio.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	// Rdio
	$client->client_id = 'sy8dschhj67vb5t9kprxb53c';
	$client->client_secret = 'rF7NJf5h9P';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Rdio Developers My Applications page '.
			'http://rdio.mashery.com/apps/register , create an application, and '.
			'in the line '.$application_line.' set the client_id to key and '.
			'client_secret with Shared secret. The Callback URL must be '.
			$client->redirect_uri);

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'http://api.rdio.com/1/', 
					'POST', array('method'=>'currentUser'), array('FailOnAccessError'=>true), $user);
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
<title>Rdio OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->firstName), 
			' you have logged in successfully with Rdio!</h1>';
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