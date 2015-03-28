<?php
/*
 * cookie_oauth_client.php
 *
 * @(#) $Id: cookie_oauth_client.php,v 1.2 2013/08/14 12:44:50 mlemos Exp $
 *
 */

class cookie_oauth_client_class extends oauth_client_class
{
	var $session = '';
	var $key = '';
	var $cookie_name = 'OAuth_session';
	var $cookie_value;

	Function Encrypt($text)
	{
		$encode_time = time();
		$iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
		$iv = str_repeat(chr(0), $iv_size);
		$key_size = mcrypt_get_key_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
		$key = $encode_time.$this->key;
		if(strlen($key) > $key_size)
			$key=substr($key, 0, $key_size);
 		return base64_encode(mcrypt_cfb(MCRYPT_3DES, $key, $text, MCRYPT_ENCRYPT, $iv)).':'.$encode_time;
	}

	Function Decrypt($encoded, &$encode_time)
	{
		if(GetType($colon = strpos($encoded, ':')) != 'integer'
		|| ($encode_time = intval(substr($encoded, $colon + 1))) == 0
		|| $encode_time > time()
		|| !($encrypted = base64_decode(substr($encoded, 0, $colon))))
			return '';
		$iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
		$iv = str_repeat(chr(0), $iv_size);
		$key_size = mcrypt_get_key_size(MCRYPT_3DES, MCRYPT_MODE_CFB);
		$key = $encode_time.$this->key;
		if(strlen($key) > $key_size)
			$key=substr($key, 0, $key_size);
		return mcrypt_cfb(MCRYPT_3DES, $key, $encrypted, MCRYPT_DECRYPT, $iv);
	}

	Function Unserialize()
	{
		if(IsSet($this->cookie_value))
			return $this->cookie_value;
		if(!IsSet($_COOKIE[$this->cookie_name])
		|| strlen($serialized = $this->Decrypt($_COOKIE[$this->cookie_name], $encode_time)) == 0)
			return null;
		$value = @unserialize($serialized);
		if(GetType($value) != 'array')
			return null;
		return($this->cookie_value = $value);
	}

	Function Serialize($s)
	{
		SetCookie($this->cookie_name, $this->Encrypt(serialize($this->cookie_value = $s)));
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
					$this->OutputDebug('Could not decrypt the OAuth session cookie');
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
			$this->Serialize($s);
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