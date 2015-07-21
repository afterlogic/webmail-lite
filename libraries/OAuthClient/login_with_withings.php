<?php
/*
 * login_with_withings.php
 *
 * @(#) $Id: login_with_withings.php,v 1.1 2014/01/26 05:07:10 mlemos Exp $
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
	$client->server = 'Withings';
	$client->redirect_uri = 'https://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_withings.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Withings Apps page https://oauth.withings.com/en/partner/add , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to API key and client_secret with API secret. '.
			'The Callback URL must be '.$client->redirect_uri).'.';

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'http://wbsapi.withings.net/user?action=getbyuserid&userid=0', 
					'GET', array(), array('FailOnAccessError'=>true, 'ResponseContentType'=>'application/json'), $user);
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
<title>Withings OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->body->users[0]->firstname), 
			' you have logged in successfully with Withings!</h1>';
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