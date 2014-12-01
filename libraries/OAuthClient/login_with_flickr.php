<?php
/*
 * login_with_flickr.php
 *
 * @(#) $Id: login_with_flickr.php,v 1.5 2014/10/13 07:42:07 mlemos Exp $
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
	$client->server = 'Flickr';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_flickr.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Flickr Apps page http://www.flickr.com/services/apps/create/ , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Key and client_secret with Secret.');

	$client->scope = 'read'; // 'read', 'write' or 'delete'
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://api.flickr.com/services/rest/', 
					'GET', array(
						'method'=>'flickr.test.login',
						'format'=>'json',
						'nojsoncallback'=>'1'
					), array('FailOnAccessError'=>true), $user);
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
<title>Flickr OAuth client results</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->user->username->_content), 
			' you have logged in successfully with Flickr!</h1>';
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