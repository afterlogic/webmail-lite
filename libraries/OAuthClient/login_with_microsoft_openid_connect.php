<?php
/*
 * login_with_microsoft_openid_connect.php
 *
 * @(#) $Id: login_with_microsoft_openid_connect.php,v 1.2 2017/02/24 11:47:59 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->server = 'MicrosoftOpenIDConnect';
	$client->debug = true;
	$client->debug_http = true;
	$client->redirect_uri = 'https://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_microsoft_openid_connect.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Microsoft App Registration Portal '.
			'https://apps.dev.microsoft.com/ and create a new'.
			'application, and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client secret. '.
			'The callback URL must be '.$client->redirect_uri.' but make sure '.
			'the domain is valid and can be resolved by a public DNS.');

	/* API permissions
	 * 
	 * The access token will be empty if the requested scopes
	 * are from OpenID only and not of any API.
	 */
	$client->scope = 'openid email profile';
	session_start();
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->authorization_error))
			{
				$client->error = $client->authorization_error;
				$success = false;
			}
			elseif(strlen($client->access_token))
			{
				/*
				 * Call any APIs from here
				 */

				/*
				$success = $client->CallAPI(
					'https://apis.live.net/v5.0/me',
					'GET', array(), array('FailOnAccessError'=>true), $user);
				 */
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
<title>Microsoft OpenID Connect OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($client->id_token->name),
			' you have logged in successfully with Microsoft OpenID Connect!</h1>';
		echo '<pre>', HtmlSpecialChars(print_r($client->id_token, 1)), '</pre>';
?>
</body>
</html>
<?php
	}
	else
	{
		$client->ResetAccessToken();
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