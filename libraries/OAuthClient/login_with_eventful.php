<?php
/*
 * login_with_eventful.php
 *
 * @(#) $Id: login_with_eventful.php,v 1.3 2014/07/29 22:18:45 mlemos Exp $
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
	$client->server = 'Eventful';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_eventful.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';
	$application_key = '';
	$account = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Eventful API request key page http://api.eventful.com/keys/new , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to oAuth Consumer Key and client_secret with oAuth Consumer Secret. '.
			'The Callback URL must be '.$client->redirect_uri);

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'http://api.evdb.com/rest/users/get', 
					'GET', array(
						'id'=>$account,
						'app_key'=>$application_key
					), array('FailOnAccessError'=>true), $user);
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
<title>Eventful OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>This is the account of ', HtmlSpecialChars($me), ' !</h1>';
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