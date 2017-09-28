<?php
/*
 * cookie_oauth_client.php
 *
 * @(#) $Id: cookie_oauth_client.php,v 1.6 2017/02/26 07:29:23 mlemos Exp $
 *
 */

class cookie_oauth_client_class extends oauth_client_class
{
	var $session = '';
	var $key = '';
	var $cookie_name = 'OAuth_session';
	var $cookie_value;

	Function Encrypt($text, &$encrypted)
	{
		if(strlen($this->key) === 0)
		{
			$this->error = 'the cookie encryption key is empty';
			return false;
		}
		$encode_time = time();
		$key = $encode_time.$this->key;
		$key_size = mcrypt_get_key_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
		if(strlen($key) > $key_size)
			$key=substr($key, 0, $key_size);
		if(strlen($key)<$key_size)
			$key=$key.str_repeat(chr(0),$key_size - strlen($key));
		error_log(__LINE__.' '.strlen($key));
		$iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		if(!($cipher = mcrypt_encrypt(MCRYPT_3DES, $key, $text, MCRYPT_MODE_CFB, $iv)))
		{
			$this->error = 'could not encrypt data';
			return false;
		}
    $encrypted = base64_encode($iv.$cipher).':'.$encode_time;
		return true;
	}

	Function Decrypt($encoded, &$encode_time, &$decrypted)
	{
		if(strlen($this->key) === 0)
		{
			$this->error = 'the cookie encryption key is empty';
			return false;
		}
		if(GetType($colon = strpos($encoded, ':')) != 'integer'
		|| ($encode_time = intval(substr($encoded, $colon + 1))) == 0
		|| $encode_time > time()
		|| !($encrypted = base64_decode(substr($encoded, 0, $colon))))
		{
			$this->OutputDebug($this->error = 'invalid encrypted data to decode: '.$encoded);
			return false;
		}
		$iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
		$iv = substr($encrypted, 0, $iv_size);
		$key = $encode_time.$this->key;
		$key_size = mcrypt_get_key_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
		if(strlen($key) > $key_size)
			$key = substr($key, 0, $key_size);
		if(strlen($key)<$key_size)
			$key=$key.str_repeat(chr(0),$key_size - strlen($key));
		$decrypted = mcrypt_decrypt(MCRYPT_3DES, $key, substr($encrypted, $iv_size), MCRYPT_MODE_CFB, $iv);
		return true;
	}

	Function Unserialize()
	{
		if(IsSet($this->cookie_value))
			return $this->cookie_value;
		if(!IsSet($_COOKIE[$this->cookie_name]))
			return null;
		if(!$this->Decrypt($_COOKIE[$this->cookie_name], $encode_time, $serialized))
			return null;
		$value = unserialize($serialized);
		if(GetType($value) != 'array')
			return null;
		return($this->cookie_value = $value);
	}

	Function Serialize($s)
	{
		if(!$this->Encrypt(serialize($this->cookie_value = $s), $encrypted))
			return false;
		SetCookie($this->cookie_name, $encrypted);
		return true;
	}

	Function SetupSession(&$session)
	{
		if(!$this->GetAccessTokenURL($access_token_url))
			return false;
		if(strlen($this->session)
		|| IsSet($_COOKIE[$this->cookie_name]))
		{
			$s = $this->Unserialize();
			if(!IsSet($s))
			{
				if($this->debug)
					$this->OutputDebug('Could not decrypt the OAuth session cookie: '.$this->error);
				$session = null;
			}
			else
				$session = (IsSet($s[$access_token_url]) ? $s[$access_token_url] : null);
		}
		else
			$session = null;
		if(!IsSet($session))
		{
			$session = array(
				'state' => md5(time().rand()),
				'access_token'=>''
			);
			$session['session'] = md5($session['state'].time().rand());
			$s = array($access_token_url => $session);
			if(!$this->Serialize($s))
				return false;
		}
		$this->session = $session['session'];
		return true;
	}

	Function GetStoredState(&$state)
	{
		if(!$this->SetupSession($session))
			return false;
		$state = $session['state'];
		return true;
	}

	Function StoreAccessToken($access_token)
	{
		if(!$this->GetAccessTokenURL($access_token_url))
			return false;
		if(!$this->SetupSession($session))
			return false;
		$session['access_token'] = $access_token['value'];
		$session['access_token_secret'] = (IsSet($access_token['secret']) ? $access_token['secret'] : '');
		$session['authorized'] = (IsSet($access_token['authorized']) ? $access_token['authorized'] : null);
		$session['expiry'] = (IsSet($access_token['expiry']) ? $access_token['expiry'] : null);
		if(IsSet($access_token['type']))
			$session['type'] = $access_token['type'];
		$session['refresh_token'] = (IsSet($access_token['refresh']) ? $access_token['refresh'] : '');
		$session['access_token_response'] = (IsSet($access_token['response']) ? $access_token['response'] : null);
		$s = $this->unserialize();
		if(!IsSet($s))
			return $this->SetError('could not decrypt the OAuth session cookie');
		$s[$access_token_url] = $session;
		$this->Serialize($s);
		return true;
	}

	Function GetAccessToken(&$access_token)
	{
		if(!$this->SetupSession($session))
			return false;
		if(strlen($session['access_token']))
		{
			$access_token = array(
				'value'=>$session['access_token'],
				'secret'=>$session['access_token_secret']
			);
			if(IsSet($session['authorized']))
				$access_token['authorized'] = $session['authorized'];
			if(IsSet($session['expiry']))
				$access_token['expiry'] = $session['expiry'];
			if(strlen($session['type']))
				$access_token['type'] = $session['type'];
			if(strlen($session['refresh_token']))
				$access_token['refresh'] = $session['refresh_token'];
			if(IsSet($session['access_token_response']))
				$access_token['response'] = $session['access_token_response'];
		}
		else
			$access_token = array();
		return true;
	}

	Function ResetAccessToken()
	{
		if(!$this->GetAccessTokenURL($access_token_url))
			return false;
		if($this->debug)
			$this->OutputDebug('Resetting the access token status for OAuth server located at '.$access_token_url);
		$s = $this->unserialize();
		if(!IsSet($s))
			return $this->SetError('could not decrypt the OAuth session cookie');
		UnSet($s[$access_token_url]);
		$this->serialize($s);
		return true;
	}
};

?>