<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV;

class Client extends \Sabre\DAV\Client {

	/**
     * Performs an HTTP options request
     *
     * This method returns all the features from the 'DAV:' header as an array.
     * If there was no DAV header, or no contents this method will return an
     * empty array.
     *
     * @return array
     */
    public function options_ex()
	{
	    $response = array();
		$response = $this->request('OPTIONS');
		$result = array();
		$result['custom-server'] = false;

		if(isset($response['headers']['x-server']) &&
				($response['headers']['x-server'] == Constants::DAV_SERVER_NAME) != null)
		{
			$result['custom-server'] = true;
		}

        if (!isset($response['headers']['dav']))
		{
			$result['features'] = array();
        }
		else
		{
			$features = explode(',', $response['headers']['dav']);
			foreach($features as &$v)
			{
				$v = trim($v);
			}
			$result['features'] = $features;
		}

		if (!isset($response['headers']['allow']))
		{
			$result['allow'] = array();
        }
		else
		{
			$allow = explode(',', $response['headers']['allow']);
			foreach($allow as &$v)
			{
				$v = trim($v);
			}
			$result['allow'] = $allow;
		}
		return $result;
    }

	public function request($method, $url = '', $body = null, $headers = array())
	{
		$headers['user-agent'] = Constants::DAV_USER_AGENT;

		$sLog = "REQUEST: ".$method;
		if ($url != '')
		{
			$sLog = $sLog." ".$url;
		}
		if ($body != null)
		{
			$sLog = $sLog."\r\nBody:\r\n".$body;
		}
		\CApi::Log($sLog, \ELogLevel::Full, 'dav-');
		\CApi::LogObject($headers, \ELogLevel::Full, 'dav-');

		$response = array();
		try
		{
			$response = parent::request($method, $url, $body, $headers);
		}
		catch (\Sabre\DAV\Exception $ex)
		{
			\CApi::LogObject($ex->getMessage(), \ELogLevel::Full, 'dav-');
			throw $ex;
		}

		$sLog = "RESPONSE: ".$method;
		if (!empty($response['body']))
		{
			$sLog = $sLog."\r\nBody:\r\n".$response['body'];
		}
		\CApi::Log($sLog, \ELogLevel::Full, 'dav-');
		if (!empty($response['headers']))
		{
			\CApi::LogObject($response['headers'], \ELogLevel::Full, 'dav-');
		}

		return $response;
	}

	public function parseMultiStatus($body)
	{
		$body = str_replace('<D:', '<d:', $body);
		$body = str_replace('</D:', '</d:', $body);
		$body = str_replace(':D=', ':d=', $body);

		return parent::parseMultiStatus($body);
	}
}
