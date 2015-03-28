<?php
/*
 * login_with_amazon.php
 *
 * @(#) $Id: login_with_amazon.php,v 1.1 2014/03/17 09:45:08 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->server = 'Amazon';

	$client->debug = false;
	$client->debug_http = true;
	$client->redirect_uri = 'https://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_amazon.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Amazon Developer Center page '.
			'https://sellercentral.amazon.com/gp/homepage.html in the Register'.
			' Your Application, create a new client ID, and in the line '.
			$application_line.' set the client_id to Cliente ID and '.
			'client_secret with Client Secret. The callback URL must be '.
			$client->redirect_uri.' but make sure the domain is valid and can '.
			'be resolved by a public DNS.');

	/* API permissions
	 */
	$client->scope = 'profile';
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
					'https://api.amazon.com/user/profile',
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
<title>Amazon OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->name),
			' you have logged in successfully with Amazon!</h1>';
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