<?php
/*
 * login_with_infusionsoft.php
 *
 * @(#) $Id: login_with_infusionsoft.php,v 1.1 2016/01/30 04:59:39 mlemos Exp $
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
	$client->server = 'Infusionsoft';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_infusionsoft.php';

	$client->client_id = ''; $application_line = __LINE__;
	$client->client_secret = '';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Infusionsoft Apps page https://keys.developer.infusionsoft.com/apps/register , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Key and client_secret with Secret');

	/* API permissions
	 */
	$client->scope = 'full';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->error = $client->authorization_error))
				$success = false;
			elseif(strlen($client->access_token))
			{
				$request = "<?xml version='1.0' encoding='UTF-8'?>
<methodCall>
  <methodName>ContactService.findByEmail</methodName>
  <params>
    <param>
      <value><string>privateKey</string></value>
    </param>
    <param>
      <value><string>mlemos@acm.org</string></value>
    </param>
    <param>
      <value><array>
        <data>
          <value><string>Id</string></value>
          <value><string>FirstName</string></value>
          <value><string>LastName</string></value>
        </data>
      </array></value>
    </param>
  </params>
</methodCall>
";
				$success = $client->CallAPI(
					'https://api.infusionsoft.com/crm/xmlrpc/v1', 
					'POST', array(), array(
						'RequestBody'=>$request,
						'RequestContentType'=>'text/xml',
						'DecodeXMLResponse'=>'simplexml',
						'FailOnAccessError'=>true
					), $search);
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
<title>Infusionsoft OAuth client results</title>
</head>
<body>
<?php
		echo '<h1>You have logged in successfully with Infusionsoft!</h1>';
		echo '<pre>', HtmlSpecialChars(print_r($search, 1)), '</pre>';
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