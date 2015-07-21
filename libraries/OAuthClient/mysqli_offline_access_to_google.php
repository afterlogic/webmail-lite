<?php
/*
 * mysqli_offline_access_to_google.php
 *
 * @(#) $Id: mysqli_offline_access_to_google.php,v 1.3 2013/07/31 11:48:04 mlemos Exp $
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

	$client->server = 'Google';

	/*
	 * Set the offline access only if you need to call an API
	 * when the user is not present and the token may expire
	 */
	$client->offline = true;

	$client->debug = false;
	$client->debug_http = true;

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Google APIs console page '.
			'http://code.google.com/apis/console in the API access tab, '.
			'create a new client ID, and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client Secret.');

	if(($success = $client->Initialize()))
	{
		/*
		 * The call to the Process function should not be done here anymore
		 * because the access token will be retrieved from the database
		 */
		$success = $client->CallAPI(
			'https://www.googleapis.com/oauth2/v1/userinfo',
			'GET', array(), array('FailOnAccessError'=>true), $user);
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