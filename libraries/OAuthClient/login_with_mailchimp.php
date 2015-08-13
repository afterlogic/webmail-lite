<?php
/*
 * login_with_mailchimp.php
 *
 * @(#) $Id: login_with_mailchimp.php,v 1.1 2014/12/05 05:09:11 mlemos Exp $
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
	$client->server = 'MailChimp';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_mailchimp.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	$api_key = ''; $api_key_line = __LINE__;
	$domain = '';

	/*
	 *  API permission scopes
	 */
	$client->scope = '';

	if(strlen($api_key) == 0
	|| strlen($domain) == 0)
		die('Create an API key in https://admin.mailchimp.com/account/api/ '.
			'and set it in the line '.$api_key_line.' for instance "e33f7adf23e3e7beb89dc74b7985d2e7-us9". '.
			' The domain should be the end of the characters of the API key after - for instance "us9".');

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to MailChimp Apps page https://admin.mailchimp.com/account/oauth2/client/ , '.
			'register an application, and in the line '.$application_line.
			' set the client_id to client_id and client_secret with Client secret. '.
			'The Callback URL must be '.$client->redirect_uri);

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://'.$domain.'.api.mailchimp.com/2.0/users/profile', 
					'GET', array(
						'apikey'=>$api_key,
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
<title>MailChimp OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->name), 
			' you have logged in successfully with MailChimp!</h1>';
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