<?php
/*
 * file_oauth_client.php
 *
 * @(#) $Id: file_oauth_client.php,v 1.2 2015/10/17 18:55:09 mlemos Exp $
 *
 */

class file_oauth_client_class extends oauth_client_class
{
	var $opened_file = false;
/*
	var $user = 0;
*/
	var $session_cookie = 'oauth_session';
	var $session_path = '/';
	var $sessions = array();

	Function Initialize()
	{
		if(!IsSet($this->file)
		|| !IsSet($this->file['name']))
			return $this->SetError('it was not specified a valid token storage file name');
		if(!parent::Initialize())
			return false;
		return true;
	}

	Function Finalize($success)
	{
		if($this->opened_file)
		{
			fclose($this->opened_file);
			$this->opened_file = false;
		}
		return parent::Finalize($success);
	}

	Function GetStoredState(&$state)
	{
		if(!$this->SetupSession($session))
			return false;
		$state = $session->state;
		return true;
	}

	Function SaveSession($session)
	{
		$name = $this->file['name'];
		if(!$this->opened_file)
		{
			if(!($this->opened_file = fopen($name, 'c+')))
				return $this->SetPHPError('could not open the token file '.$name, $php_error_message);
		}
		if(!flock($this->opened_file, LOCK_EX))
			return $this->SetPHPError('could not lock the token file '.$name.' for writing', $php_error_message);
		if(fseek($this->opened_file, 0))
			return $this->SetPHPError('could not rewind the token file '.$name.' for writing', $php_error_message);
		if(!ftruncate($this->opened_file, 0))
			return $this->SetPHPError('could not truncate the token file '.$name.' for writing', $php_error_message);
		if(!fwrite($this->opened_file, json_encode($session)))
			return $this->SetPHPError('could not write to the token file '.$name, $php_error_message);
		if(!fclose($this->opened_file))
			return $this->SetPHPError('could not close to the token file '.$name, $php_error_message);
		$this->opened_file = false;
		return true;
	}

	Function ReadSession(&$session)
	{
		$session = null;
		$name = $this->file['name'];
		if(!file_exists($name))
			return true;
		if(!($this->opened_file = fopen($name, 'c+')))
			return $this->SetPHPError('could not open the token file '.$name, $php_error_message);
		if(!flock($this->opened_file, LOCK_SH))
			return $this->SetPHPError('could not lock the token file '.$name.' for reading', $php_error_message);
		$json = '';
		while(!feof($this->opened_file))
		{
			$data = fread($this->opened_file, 1000);
			if(!$data
			&& !feof($this->opened_file))
			{
				$this->SetError('could not read the token file'.$name, $php_error_message);
				fclose($this->opened_file);
				$this->opened_file = false;
				return false;
			}
			$json .= $data;
		}
		flock($this->opened_file, LOCK_UN);
		$session = json_decode($json);
		if(!IsSet($session))
			return $this->SetPHPError('It was not possible to decode the OAuth token file '.$name, $php_errormsg);
		if(GetType($session) !== 'object')
			return $this->SetError('It was not possible to decode the OAuth token file '.$name.' because it seems corrupted');
		return true;
	}
	
	Function CreateOAuthSession($user, &$session)
	{
		$this->InitializeOAuthSession($session);
		return $this->SaveSession($session);
	}
	
	Function SetOAuthSession(&$oauth_session, $session)
	{
		$oauth_session = new oauth_session_value_class;
		$oauth_session->id = $session->id;
		$oauth_session->session = $session->session;
		$oauth_session->state = $session->state;
		$oauth_session->access_token = $session->access_token;
		$oauth_session->access_token_secret = $session->access_token_secret;
		$oauth_session->expiry = $session->expiry;
		$oauth_session->authorized = $session->authorized;
		$oauth_session->type = $session->type;
		$oauth_session->server = $session->server;
		$oauth_session->creation = $session->creation;
		$oauth_session->refresh_token = $session->refresh_token;
		$oauth_session->access_token_response = (IsSet($session->access_token_response) ? $session->access_token_response : null);
	}

	Function GetOAuthSession($session_id, $server, &$oauth_session)
	{
		if(IsSet($this->sessions[$session_id][$server]))
		{
			$oauth_session = $this->sessions[$session_id][$server];
			return true;
		}
		if(!$this->ReadSession($session))
			return false;
		if(!IsSet($session))
		{
			$oauth_session = null;
			return true;
		}
		$this->SetOAuthSession($oauth_session, $session);
		$this->sessions[$session_id][$server] = $oauth_session;
		return true;
	}
	
	Function StoreAccessToken($access_token)
	{
		if(!$this->SetupSession($session))
			return false;
		$session->access_token = $access_token['value'];
		$session->access_token_secret = (IsSet($access_token['secret']) ? $access_token['secret'] : '');
		$session->authorized = (IsSet($access_token['authorized']) ? $access_token['authorized'] : null);
		$session->expiry = (IsSet($access_token['expiry']) ? $access_token['expiry'] : null);
		if(IsSet($access_token['type']))
			$session->type = $access_token['type'];
		$session->refresh_token = (IsSet($access_token['refresh']) ? $access_token['refresh'] : '');
		$session->access_token_response = (IsSet($access_token['response']) ? $access_token['response'] : null);
		if(!$this->GetOAuthSession($session->session, $this->server, $oauth_session))
			return($this->SetError('OAuth session error: '.$this->error));
		if(!IsSet($oauth_session))
		{
			$this->error = 'the session to store the access token was not found';
			return false;
		}
		$oauth_session->access_token = $session->access_token;
		$oauth_session->access_token_secret = $session->access_token_secret;
		$oauth_session->authorized = (IsSet($session->authorized) ? $session->authorized : null);
		$oauth_session->expiry = (IsSet($session->expiry) ? $session->expiry : null);
		$oauth_session->type = (IsSet($session->type) ? $session->type : '');
		$oauth_session->refresh_token = $session->refresh_token;
		$oauth_session->access_token_response = (IsSet($session->access_token_response) ? $session->access_token_response : null);
		return $this->SaveSession($oauth_session);
	}

	Function GetAccessToken(&$access_token)
	{
		if(!$this->ReadSession($session))
			return false;
		if(IsSet($session)
		&& strlen($session->access_token))
		{
			$access_token = array(
				'value'=>$session->access_token,
				'secret'=>$session->access_token_secret
			);
			if(IsSet($session->authorized))
				$access_token['authorized'] = $session->authorized;
			if(IsSet($session->expiry))
				$access_token['expiry'] = $session->expiry;
			if(strlen($session->type))
				$access_token['type'] = $session->type;
			if(strlen($session->refresh_token))
				$access_token['refresh'] = $session->refresh_token;
			if(IsSet($session->access_token_response))
				$access_token['response'] = $session->access_token_response;
		}
		else
			$access_token = array();
		return true;
	}

	Function ResetAccessToken()
	{
		if($this->opened_file)
		{
			fclose($this->opened_file);
			$this->opened_file = false;
		}
		$name = $this->file['name'];
		if(!file_exists($name))
			return true;
		if($this->debug)
			$this->OutputDebug('Resetting the access token status for OAuth server by removing the token file '.$name);
		return unlink($name);
	}
};

?>