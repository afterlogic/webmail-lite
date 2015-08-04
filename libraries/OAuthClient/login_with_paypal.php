<?php
/*
 * login_with_paypal.php
 *
 * @(#) $Id: login_with_paypal.php,v 1.1 2014/11/07 08:26:17 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;

	 // Use 'PaypalApplication' for application only authorization
	$client->server = 'PaypalApplication';

	$client->debug = false;
	$client->debug_http = true;
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_paypal.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	/*
	 *  Set the grant_type to client_credentials to obtain application only authorization
	 */ 
	// $client->grant_type = 'client_credentials';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Paypal Developer site and create an application '.
			'https://developer.paypal.com/webapps/developer/applications/myapps '.
			'and in the line '.$application_line. ' set the client_id to Client ID '.
			'and client_secret with Client secret. '.
			'The site domain must have the same domain of '.$client->redirect_uri);

	/* API permissions
	 */
	$client->scope = 'profile email address';
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
				$success = $client->CallAPI(
					'https://api.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid',
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
<title>Paypal OAuth client results</title>
</head>
<body>
<?php
		$name = (IsSet($user->given_name) ? $user->given_name : $user->user_id);
		echo '<h1>', HtmlSpecialChars($name),
			' you have logged in successfully with Paypal!</h1>';
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