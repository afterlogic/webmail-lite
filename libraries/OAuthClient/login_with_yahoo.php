<?php
/*
 * login_with_yahoo.php
 *
 * @(#) $Id: login_with_yahoo.php,v 1.4 2014/03/31 07:18:57 mlemos Exp $
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
	$client->server = 'Yahoo';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_yahoo.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Yahoo Apps page https://developer.apps.yahoo.com/projects/ , '.
			'create a project, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Callback URL must be '.$client->redirect_uri).' Make sure you enable the '.
			'necessary permissions to execute the API calls your application needs.';

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://query.yahooapis.com/v1/yql', 
					'GET', array(
						'q'=>'select * from social.profile where guid=me',
						'format'=>'json'
					), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if(strlen($client->authorization_error))
	{
		$client->error = $client->authorization_error;
		$success = false;
	}
	if($success)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Yahoo OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->query->results->profile->nickname), 
			' you have logged in successfully with Yahoo!</h1>';
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
<p>Error: <?php echo HtmlSpecialChars($client->error); ?></p>
</body>
</html>
<?php
	}

?>