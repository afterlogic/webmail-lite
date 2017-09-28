<?php
/*
 * login_with_ihealth.php
 *
 * @(#) $Id: login_with_ihealth.php,v 1.1 2017/03/16 13:24:24 mlemos Exp $
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
	$client->server = 'iHealth';
	$client->redirect_uri = 'https://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_ihealth.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	/*  APIName of each API you want to access. Valid API names are:
	 *  OpenApiActivity OpenApiBG OpenApiBP OpenApiSleep OpenApiSpO2 OpenApiUserInfo OpenApiWeight
	 *  Separate API names with a space, not with +
	 */
	$client->scope = 'OpenApiUserInfo';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to iHealth App registration page, '.
			'http://developer.ihealthlabs.com/developerappaddpage.htm'.
			' create an application, and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client secret. '.
			'The Callback URL must be '.$client->redirect_uri).' Make sure you enable the '.
			'necessary permissions to execute the API calls your application needs.';

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$sc = 'insert here the serial number user';
				$sv = 'insert here the serial number value';
				$success = $client->CallAPI(
					'https://api.ihealthlabs.com:8443/openapiv2/application/userinfo.json/'.
						'?sc='.UrlEncode($sc).
						'&sv='.UrlEncode($sv).
						'&client_id='.UrlEncode($client->client_id).
						'&client_secret='.UrlEncode($client->client_secret).
						'&redirect_uri='.UrlEncode($client->redirect_uri), 
					'GET', array(), array('FailOnAccessError'=>true), $user);
				if($success
				&& IsSet($user->Error))
				{
					$success = false;
					$client->error = $user->Error;
				}
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
<title>iHealth OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->nickname), 
			' you have logged in successfully with iHealth!</h1>';
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