<?php
/*
 * login_with_facebook.php
 *
 * @(#) $Id: login_with_facebook.php,v 1.6 2016/08/07 04:37:14 mlemos Exp $
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

	// set the reauthenticate access only if you need to force the user to
	// authenticate again even after the user has authorized the application
	// before.
	$client->reauthenticate = false;

	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_facebook.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Facebook Apps page https://developers.facebook.com/apps , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to App ID/API Key and client_secret with App Secret');

	/* API permissions
	 */
	$client->scope = 'email,publish_actions,user_friends';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://graph.facebook.com/v2.3/me?fields=id,first_name,gender,last_name,link,locale,name,timezone,updated_time,verified,email', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
/*
				if($success)
				{
					// Get Friends that use the same application

					$success = $client->CallAPI(
						'https://graph.facebook.com/v2.3/me/friends', 
						'GET', array(), array('FailOnAccessError'=>true), $friends);
				}
*/
				if($success)
				{
					// Requires publish_actions permissions and your application needs to be submitted for review
					$values = array(
						// You can no longer pre-fill the user message
						'message'=>'',
						'link'=>'http://www.phpclasses.org/package/7700-PHP-Authorize-and-access-APIs-using-OAuth.html',
						// The name of the post can be retrieved from the page title
						//'name' => 'This is the title',
						// the description of the post can be retrieved from the page meta description
						'description'=>'This post was submitted using this PHP OAuth API client class.',
						'picture' => 'http://files.phpclasses.org/files/blog/package/7700/file/PHP%2BOAuth.png'
					);
					$success = $client->CallAPI(
						'https://graph.facebook.com/v2.3/me/feed', 
						'POST', $values, array('FailOnAccessError'=>true), $post);
				}
/*
				if($success)
				{
					// Post photos in your time line

					$success = $client->CallAPI(
						"https://graph.facebook.com/me/photos",
						'POST', array(
							'message'=>'This is a test to post photos in Facebook time line using this the PHP OAuth API class: http://www.phpclasses.org/oauth-api',
							'source'=>'picture.jpg'
						),
						array(
							'FailOnAccessError'=>true,
							'Files'=>array(
								'source'=>array(
						)
					)
				), $upload);
				}
*/
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
<title>Facebook OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user->name), 
			' you have logged in successfully with Facebook!</h1>';
		echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
		echo '<pre>', HtmlSpecialChars(print_r($post, 1)), '</pre>';
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