<?php
/*
 * mysqli_offline_access_to_twitter.php
 *
 * @(#) $Id: mysqli_offline_access_to_twitter.php,v 1.3 2013/07/31 11:48:04 mlemos Exp $
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
	 * ID of the user in the database of your application
	 */
	$client->user = 1;

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
		/*
		 * The call to the Process function should not be done here anymore
		 * because the access token will be retrieved from the database
		 */
		$success = $client->CallAPI(
			'https://api.twitter.com/1.1/account/verify_credentials.json', 
			'GET', array(), array('FailOnAccessError'=>true), $user);

/* Tweet with an attached image
 
				$success = $client->CallAPI(
					"https://api.twitter.com/1.1/statuses/update_with_media.json",
					'POST', array(
						'status'=>'This is a test tweet to evaluate the PHP OAuth API support to upload image files sent at '.strftime("%Y-%m-%d %H:%M:%S"),
						'media[]'=>'php-oauth.png'
					),array(
						'FailOnAccessError'=>true,
						'Files'=>array(
							'media[]'=>array(
							)
						)
					), $user);
*/
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;

	/*
	 * Use this script from the command line, so no HTML output is needed.
	 */
	if($success)
	{
		if(strlen($client->access_token))
		{
			echo 'The user name is ', $user->name, "\n";
			echo print_r($user, 1);
		}
		else
			echo 'The access token is not available!', "\n";
	}
	else
		echo 'Error: ', $client->error, "\n";
?>