<?php
/*
 * login_with_mail.ru.php
 *
 * @(#) $Id: login_with_mail.ru.php,v 1.1 2014/08/26 06:26:49 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->server = 'mail.ru';

	$client->debug = false;
	$client->debug_http = true;
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_mail.ru.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';
	
	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to mail.ru sites page '.
			'http://api.mail.ru/sites/my/add/ , add a new site '.
			'and in the line '.$application_line.
			' set the client_id to application ID and client_secret with secret key. '.
			'The callback URL must be '.$client->redirect_uri);

	/* API permissions
	 */
	$client->scope = '';
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
				/*
				 *  Request parameters must be signed for the request to be
				 *  accepted
				 *
				 *  The current user ID is taken from the x_mailru_vid response
				 *  parameters
				 */
				$parameters = array(
					'method'=>'users.getInfo',
					'uids'=>$client->access_token_response['x_mailru_vid'],
					'app_id'=>$client->client_id,
					'secure'=>'1'
				);
				ksort($parameters);
				$values = '';
				$url = 'http://www.appsmail.ru/platform/api?sig={signature}';
				foreach($parameters as $key => $value)
				{
					$values .= $key.'='.$value;
					$url .= '&'.$key.'='.$value;
				}
				$url = str_replace('{signature}', md5($values.$client->client_secret), $url);
				$success = $client->CallAPI(
					$url,
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
<title>mail.ru OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>', HtmlSpecialChars($user[0]->first_name),
			' you have logged in successfully with mail.ru!</h1>';
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