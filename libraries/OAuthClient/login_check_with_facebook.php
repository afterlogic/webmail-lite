<?php
/*
 * login_check_with_facebook.php
 *
 * @(#) $Id: login_check_with_facebook.php,v 1.2 2016/08/07 04:37:14 mlemos Exp $
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
	$client->server = 'Facebook';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_check_with_facebook.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Facebook Apps page https://developers.facebook.com/apps , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to App ID/API Key and client_secret with App Secret');

	/* The initial page to redirect is not set;
	 */
	$redirect_url = null;
	
	/* API permissions
	 */
	$client->scope = 'email';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->CheckAccessToken($redirect_url)))
		{
			/*
			 * Is there a valid access token or shall we need to 
			 * redirect the user to the OAuth server authorization page?
			 */
			if(IsSet($redirect_url))
			{
				/*
				 * It seems the access token was not yet retrieved
				 * or it was expired and could not be renewed
				 */
			}
			elseif(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://graph.facebook.com/v2.3/me?fields=id,first_name,gender,last_name,link,locale,name,timezone,updated_time,verified,email', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($success)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Facebook OAuth client results</title>
</head>
<body>
<?php
		/*
		 * Check if the redirect URL is set, so the user needs to authorize
		 * to obtain the access token
		 */
		if(IsSet($redirect_url))
		{
			echo '<h1><a href="', HtmlSpecialChars($redirect_url).'">Login with Facebook</a></h1>';
		}
		else
		{
			echo '<h1>', HtmlSpecialChars($user->name), 
				' you have logged in successfully with Facebook!</h1>';
			echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
		}
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