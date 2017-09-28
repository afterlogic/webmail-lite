<?php
/*
 * login_with_odnoklassniki.php
 *
 * @(#) $Id: login_with_odnoklassniki.php,v 1.2 2017/08/20 09:38:13 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = true;
	$client->debug_http = true;
	$client->server = 'Odnoklassniki';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_odnoklassniki.php';

	$application_key = '';
	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Odnoklassniki create application page '.
			'https://ok.ru/dk?st.cmd=appEditBasic&st._aid=Apps_Info_MyDev_AddApp&st.vpl.mini=false'.
			' , create an application, and in the line '.$application_line.
			' set the client_id to application ID and client_secret with secret application key.');

	/* API permissions
	 */
	$client->scope = 'VALUABLE_ACCESS;GET_EMAIL';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$url = 'https://api.ok.ru/graph/me/info/';
				$success = $client->CallAPI(
					$url,
					'GET', array(), array('FailOnAccessError'=>true, 'Accept' => 'application/json'), $user);
				if(IsSet($user->error_msg))
				{
					$client->error = $user->error_msg;
					$success = false;
				}
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
<title>Odnoklassniki OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->name), 
			' you have logged in successfully with Odnoklassniki!</h1>';
		echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
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