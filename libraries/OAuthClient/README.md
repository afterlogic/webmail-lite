## PHP OAuth Library to Access Any OAuth API

The OAuth protocol is not hard to understand but it requires learning about many details and the many differences of operation with different OAuth servers.

## OAuth PHP: Solutions

PHP has an extension for using OAuth but it practically requires that you learn all the specification documents for all aspects of the OAuth protocol.

Therefore it is better to use a client class like this encapsulate all steps so you do not have to learn so much about the OAuth protocol in all its versions.

## PHP OAuth1 Client

This PHP class can work with OAuth 1.0 and 1.0a. Despite the class supports servers that work with OAuth 1.0, it is not a secure solution. So most servers that you see and support OAuth 1.0, it is actually OAuth 1.0a which is secure.

## PHP OAuth2 Example

OAuth 2.0 is not a better version of OAuth 1.0a as if it was an upgrade. You may still see many servers that work securely using OAuth 1.0a.

Nowadays most servers use OAuth 2.0 because it is a protocol version that support more extensions.

The PHP OAuth class either OAuth 1.0, OAuth 1.0a and OAuth 2.0 . For the developer that uses this class, it does not make much difference because the function calls to use are the same.

The main internal difference is that OAuth 1.0a servers return both an access token value and an access token secret.

## PHP OAuth Tutorial

Several articles have been written to tell not only how to use this package but also to tell about how the different versions of the OAuth protocol work.

You can read all the available tutorial articles in the [package blog](https://www.phpclasses.org/blog/package/7700/).

The main tutorial article is entitled [PHP OAuth Tutorial on How to Use a Pure PHP OAuth Class with an Example Without using the PECL module Implementation](https://www.phpclasses.org/blog/package/7700/post/1-Painless-OAuth-with-PHP.html).

## OAuth Server PHP Configuration: Setting the PHP OAuth Server Variable to Access Any API

This PHP OAuth class can work with any server using OAuth1 or OAuth2. Just change the server variable to the name supported API.

The class provides built-in support for a few common APIs but any new API can be supported by adding a new entry to the oauth_configuration.json file.

This configuration file can be used to presets option values for class variables with the following names. Check the class documentation to learn the meaning of each of these option variables:

    oauth_version
    dialog_url
    reauthenticate_dialog_url
    pin_dialog_url
    access_token_url
    request_token_url
    append_state_to_redirect_uri
    authorization_header
    url_parameters
    token_request_method
    signature_method
    access_token_authentication
    access_token_parameter
    default_access_token_type
    store_access_token_response
    refresh_token_authentication
    grant_type
    access_token_content_type

## Facebook OAuth2 PHP OAuth Example

Here is a simple example of getting the authorization token and making an API call to Facebook Graph API.

Check the complete [Facebook OAuth2 PHP OAuth example here](https://www.phpclasses.org/package/7700-PHP-Authorize-and-access-APIs-using-OAuth.html#view_files/files/42010).

``` php
// Include the necessary class files directly or
// vendor/autoload.php if you used composer to install the package.
require('http.php');
require('oauth_client.php');

$client = new oauth_client_class;
$client->server = 'Facebook';

$client->client_id = 'your application id here';
$client->client_secret = 'your application secret here';

$client->scope = 'email';

if(($success = $client->Initialize()))
{
    if(($success = $client->Process()))
    {
        if(strlen($client->access_token))
        {
            $success = $client->CallAPI(
                'https://graph.facebook.com/v2.3/me?'.
                'fields=id,first_name,last_name,verified,email',
                'GET', array(), array('FailOnAccessError'=>true), $user);
        }
        $success = $client->Finalize($success);
    }
    if($client->exit)
        exit;
}     
if($success)
{
    echo '<h1>', HtmlSpecialChars($user->name), 
        ' you have logged in successfully with Facebook!</h1>';
}
else
{
    echo 'Error: ', HtmlSpecialChars($client->error);
}
```

## Vimeo API PHP OAuth Example

Here is a simple example of getting the authorization token and making an API call to Vimeo API.

Check the complete [Vimeo API PHP example here](https://www.phpclasses.org/package/7700-PHP-Authorize-and-access-APIs-using-OAuth.html#view_files/files/52344).

```php
// Include the necessary class files directly or
// vendor/autoload.php if you used composer to install the package.
require('http.php');
require('oauth_client.php');

$client = new oauth_client_class;
$client->server = 'Vimeo';

$client->client_id = 'your application id here';
$client->client_secret = 'your application secret here';

if(($success = $client->Initialize()))
{
   if(($success = $client->Process()))
   {
      if(strlen($client->access_token))
      {
         $success = $client->CallAPI(
            'https://api.vimeo.com/me/?format=json', 
            'GET', array(), array('FailOnAccessError'=>true), $user);
      }
   }
   $success = $client->Finalize($success);
}
if($client->exit)
   exit;
if($success)
{
   echo '<h1>', HtmlSpecialChars($user->name), 
      ' you have logged in successfully with Vimeo!</h1>';
   echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
}
else
{
  echo 'Error: ', HtmlSpecialChars($client->error);
}
```

## Google Contacts API PHP Example

This example retrieves the Google user contacts using the People API.

Check the complete [Google Contacts API PHP example here](https://www.phpclasses.org/package/7700-PHP-Authorize-and-access-APIs-using-OAuth.html#view_files/files/130440).

``` php
// Include the necessary class files directly or
// vendor/autoload.php if you used composer to install the package.
require('http.php');
require('oauth_client.php');

$client = new oauth_client_class;
$client->server = 'Google';

$client->client_id = 'your application id here';
$client->client_secret = 'your application secret here';

$client->scope = 'https://www.googleapis.com/auth/contacts.readonly';
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
                'https://people.googleapis.com/v1/people/me/connections'.
                '?fields=connections(emailAddresses%2Cnames)',
                'GET', array(), array('FailOnAccessError'=>true), $contacts);
        }
    }
    $success = $client->Finalize($success);
}
if($client->exit)
    exit;
if($success)
{
        echo '<pre>';
        foreach($contacts->connections as $contact)
        {
            echo htmlspecialchars($contact->names[0]->displayName), "\n";
        }
        echo '</pre>';
}
else
{
  echo 'Error: ', HtmlSpecialChars($client->error);
}
```

##     Pinterest API PHP OAuth Example

This example retrieves the Pinterest user details using the Pinterst API.

Check the complete [Pinterest API PHP example here](https://www.phpclasses.org/package/7700-PHP-Authorize-and-access-APIs-using-OAuth.html#view_files/files/).

```php
require('http.php');
require('oauth_client.php');

$client = new oauth_client_class;
$client->server = 'Pinterest';

$client->client_id = 'your application id here';
$client->client_secret = 'your application secret here';

$client->scope = 'read_public';
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
                'https://api.pinterest.com/v1/me/',
                'GET', array(), array('FailOnAccessError'=>true), $user);
        }
    }
    $success = $client->Finalize($success);
}
if($client->exit)
    exit;
if($success)
{
    echo '<h1>', HtmlSpecialChars($user->data->first_name),
        ' you have logged in successfully with Google!</h1>';
    echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
}
else
{
  echo 'Error: ', HtmlSpecialChars($client->error);
}
```
