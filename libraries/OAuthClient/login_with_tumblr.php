<?php
/*
 * login_with_tumblr.php
 *
 * @(#) $Id: login_with_tumblr.php,v 1.4 2015/02/06 05:37:36 mlemos Exp $
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
	$client->server = 'Tumblr';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_tumblr.php';

		/*
		 * Custom HTTP connection options if needed
		 */
	$client->http_arguments = array(
		/*
		 *  If you need to access a site using a proxy server, use these
		 *  arguments to set the proxy host and authentication credentials if
		 *  necessary.
		 */
		/*
		'ProxyHostName'=>'127.0.0.1',
		'ProxyHostPort'=>3128,
		'ProxyUser'=>'proxyuser',
		'ProxyPassword'=>'proxypassword',
		'ProxyRealm'=>'proxyrealm',  // Proxy authentication realm or domain
		'ProxyWorkstation'=>'proxyrealm',
		*/
	);
		
	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Tumblr Apps page http://www.tumblr.com/oauth/apps , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Default callback URL must be '.$client->redirect_uri);

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'http://api.tumblr.com/v2/user/info', 
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
<title>Tumblr OAuth client results</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->response->user->name), 
			' you have logged in successfully with Tumblr!</h1>';
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