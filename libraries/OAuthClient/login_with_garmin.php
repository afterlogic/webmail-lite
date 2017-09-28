<?php
/*
 * login_with_garmin.php
 *
 * @(#) $Id: login_with_garmin.php,v 1.2 2016/03/30 04:31:54 mlemos Exp $
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
	$client->server = 'Garmin2Legged';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_twitter.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Garmin Apps page , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. ');

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$activitySummaryRequest = new stdClass;
				$activitySummaryRequest->consumerToken = $client->client_id;
				$activitySummaryRequest->unacknowledgedOnly=false;
				$activitySummaryRequest->beginTimeMillis=0;
				$activitySummaryRequest->endTimeMillis = time()*1000;

				$activitySummary = new stdClass;
				$activitySummary->activitySummaryRequest = $activitySummaryRequest;

				$activityRequest = new stdClass;
				$activityRequest->GET_ACTIVITY_SUMMARY = array( $activitySummary );

				$WELLNESS = new stdClass;
				$WELLNESS->activityRequests = array( $activityRequest );

				$serviceRequests = new stdClass;
				$serviceRequests->WELLNESS = $WELLNESS;

				$parameters = new stdClass;
				$parameters->serviceRequests = $serviceRequests;

				$success = $client->CallAPI(
					'http://gcsapitest.garmin.com/gcs-api/api/json',
					'POST', array(), array(
						'FailOnAccessError' => true, 
						'RequestBody' => json_encode($parameters), 
						'RequestContentType' => 'application/octet-stream',
					), $activity);
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
<title>Garmin OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>You have logged in successfully with Garmin!</h1>';
		echo '<pre>Activity:', "\n\n", HtmlSpecialChars(print_r($activity, 1)), '</pre>';
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