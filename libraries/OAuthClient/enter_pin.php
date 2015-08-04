<?php
/*
 * enter_pin.php
 *
 * @(#) $Id: enter_pin.php,v 1.2 2015/02/15 01:22:18 mlemos Exp $
 *
 */

	/*
	 * List of authorization scripts to redirect
	 * after the user enters the pin
	 */
	$authorizations = array(
		'login_with_twitter.php'=>'Twitter',
		'login_with_imgur.php'=>'imgur',
		'login_with_linkedin.php'=>'Linkedin',
		'login_with_flickr.php'=>'Flickr',
	);

	/*
	 * Did the user submit the pin yet?
	 */
	if(IsSet($_GET['submit'])
	&& IsSet($_GET['pin'])
	&& IsSet($_GET['script'])
	&& IsSet($authorizations[$_GET['script']]))
	{
		/*
		 * Set the PIN constant value and
		 * include the selected authorization script
		 */
		define('OAUTH_PIN', $_GET['pin']);
		require $_GET['script'];
	}
	else
	{
		/*
		 * Present the pin input form
		 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>imgur OAuth client results</title>
</head>
<body>
<form method="GET" action="">
<h1>Enter the  authorization pin</h1>
<p><label for="pin" accesskey="P">Pin: <input type="text" id="pin" name="pin"></p>
<p><select id="script" name="script">
<?php
	foreach($authorizations as $script => $name)
	{
		echo '<option value="'.HtmlSpecialChars($script).'">'.HtmlSpecialChars($name).'</option>', "\n";
	}
?>
</select></p>
<p><input type="submit" value="Authorize" name="submit"></p>
</form>
</body>
</html>
<?php
	}
?>