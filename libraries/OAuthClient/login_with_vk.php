<?php
/*
 * login_with_vk.php
 *
 * @(#) $Id: login_with_vk.php,v 1.2 2017/08/20 20:15:53 mlemos Exp $
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
	$client->server = 'VK';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_vk.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to VK create application page http://vk.com/editapp?act=create , '.
			'create a Website application, and in the line '.$application_line.
			' set the client_id to App ID/API Key and client_secret with App Secret');

	/* API permissions
	 *
	 * Check for the numbers for each permission to add at
	 *
	 * https://vk.com/dev/permissions
	 *
	 * email - 4194304
	 */
	$client->scope = strval(4194304+0);
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://api.vk.com/method/users.get', 
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
<title>VK OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->response[0]->first_name), 
			' you have logged in successfully with VK!</h1>';
		echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
		echo '<p>User email and other details returned with the access token:</p>';
		echo '<pre>', HtmlSpecialChars(print_r($client->access_token_response, 1)), '</pre>';
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