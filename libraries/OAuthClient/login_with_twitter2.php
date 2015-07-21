<?php
/*
 * login_with_twitter2.php
 *
 * @(#) $Id: login_with_twitter2.php,v 1.1 2014/09/29 00:59:40 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = 1;
	$client->debug_http = 1;
	$client->server = 'Twitter2';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_twitter2.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	/*
	 *  Set the grant_type to client_credentials to obtain application only authorization
	 */ 
	$client->grant_type = 'client_credentials';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Twitter Apps page https://dev.twitter.com/apps/new , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Callback URL must be '.$client->redirect_uri.' If you want to post to '.
			'the user timeline, make sure the application you create has write permissions');

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://api.twitter.com/1.1/users/show.json?screen_name=phpclasses', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
			else
				$success = strlen($client->error = $client->access_token_error) === 0;
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
<title>Twitter OAuth 2 client results</title>
</head>
<body>
<?php
		echo '<h1>Retrieved the Twitter profile of ', HtmlSpecialChars($user->name), 
			' successfully!</h1>';
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