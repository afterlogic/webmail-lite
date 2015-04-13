<?php
/*
 * mysqli_login_with_twitter.php
 *
 * @(#) $Id: mysqli_login_with_twitter.php,v 1.5 2014/02/22 06:32:25 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');
	require('database_oauth_client.php');
	require('mysqli_oauth_client.php');

	/*
	 * Create an object of the sub-class of the OAuth client class that is
	 * specialized in storing and retrieving access tokens from MySQL
	 * databases using the mysqli extension
	 * 
	 * If you use a different database, replace this class by another
	 * specialized in accessing that type of database
	 */
	$client = new mysqli_oauth_client_class;

	/*
	 * Define options specific to your database connection  
	 */
	$client->database = array(
		'host'=>'',
		'user'=>'oauth',
		'password'=>'oauth',
		'name'=>'oauth',
		'port'=>0,
		'socket'=>'/var/lib/mysql/mysql.sock'
	);
	$client->server = 'Twitter';

	/*
	 * Set the offline access only if you need to call an API
	 * when the user is not present and the token may expire
	 */
	$client->offline = true;

	$client->debug = true;
	$client->debug_http = true;
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/mysqli_login_with_twitter.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Twitter Apps page https://dev.twitter.com/apps/new , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Callback URL must be '.$client->redirect_uri.' If you want to post to '.
			'the user timeline, make sure the application you create has write permissions');

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
					'https://api.twitter.com/1.1/account/verify_credentials.json', 
					'GET', array(), array('FailOnAccessError'=>true), $user);

				/*
				 * Once you were able to access the user account using the API
				 * you should associate the current OAuth access token a specific
				 * user, so you can call the API without the user presence, just
				 * specifying the user id in your database.
				 *
				 * In this example the user id is 1 . Your application should
				 * determine the right user is to associate.
				 */
				if($success)
					$success = $client->SetUser(1);
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
<title>Twitter OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->name),
			' you have logged in successfully with Twitter!</h1>';
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