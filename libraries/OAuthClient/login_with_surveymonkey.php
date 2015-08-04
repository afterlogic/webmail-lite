<?php
/*
 * login_with_surveymonkey.php
 *
 * @(#) $Id: login_with_surveymonkey.php,v 1.2 2013/07/31 11:48:04 mlemos Exp $
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
	$client->server = 'SurveyMonkey';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_surveymonkey.php';

	$client->client_id = ''; $application_line = __LINE__; 
	$client->client_secret = '';
	$client->api_key = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to SurveyMonkey applications page '.
			'https://developer.surveymonkey.com/apps/register in the API access tab, '.
			'create a new client ID, and in the line '.$application_line.
			' set the client_id to SurveyMonkey user account, client_secret with '.
			'shared secret and api_key with the API key '.
			'The Callback URL must be '.$client->redirect_uri);

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
				$parameters = new stdClass;
				$success = $client->CallAPI(
					'https://api.surveymonkey.net/v2/surveys/get_survey_list?api_key='.$client->api_key,
					'POST', $parameters, array('FailOnAccessError'=>true, 'RequestContentType'=>'application/json'), $surveys);
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
<title>SurveyMonkey OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>You have logged in successfully with SurveyMonkey!</h1>';
		echo '<pre>Surveys: ', HtmlSpecialChars(print_r($surveys->data->surveys, 1)), '</pre>';
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